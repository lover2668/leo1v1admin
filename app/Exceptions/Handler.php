<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        CommandNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        if( \App\Helper\Utils::check_env_is_release() ) {

            $account=@$_SESSION["acc"];

            $bt_str= "user:$account<br/>.url:" .@$_SERVER["REQUEST_URI"]. "<br/>";

            foreach( $e->getTrace() as &$bt_item ) {
                //$args=json_encode($bt_item["args"]);
                $bt_str.= @$bt_item["class"]. @$bt_item["type"]. @$bt_item["function"]."---".
                    @$bt_item["file"].":".@$bt_item["line"].
                    "<br/>";
            }

            $ip=@$_SERVER["REMOTE_ADDR"];
            if ( substr($ip,0,9 )!= "121.42.0."  ) { //阿里云盾
                dispatch( new \App\Jobs\send_error_mail(
                    "","ADMIN ERR 111:" .$e->getMessage(),
                    $e->getMessage() ."<br>$bt_str".
                    "<br>client_ip:$ip"
                ));
                \App\Helper\Utils::logger("LOG_HANDER: " .$bt_str.",ip:$ip");
            }
        }else {
            //\App\Helper\Utils::logger("ERROR:".json_encode(session() ));
            //dd($_SESSION);
        }

        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render(  $request, Exception $e)
    {

        if ($this->isHttpException($e)) {
            if ($e->getStatusCode()==404) {
                /*
                if ( \App::environment("testing") ) {
                    global $g_request;
                    //  @var $g_request Illuminate\Http\Request
                    $path=$g_request->path();
                    $arr=explode("/", $path);
                    $act="";
                    if (isset($arr[1]) ) {
                        $act=$arr[1] ;
                    }
                    $ctr= $arr[0];
                    if (!$ctr)  {
                        $ctr="index";
                    }
                    if (!$act)  {
                        $act="index";
                    }

                    echo "path $path =>  $ctr @ $act \n";
                    $reflectionObj = new \ReflectionClass( "App\\Http\\Controllers\\$ctr" );
                    $data= $reflectionObj->newInstanceArgs()->$act();
                    return  $data;
                }
            */


                return  "no find url=[".  $request->url() ."]" ;
            }
        }



        return parent::render($request, $e);
    }
}
