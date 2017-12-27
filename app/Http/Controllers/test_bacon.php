<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;

use Qiniu\Auth;

use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;

use App\Jobs\deal_pdf_to_image;

require_once  app_path("/Libs/Qiniu/functions.php");

class test_bacon extends Controller
{
    use CacheNick;

    public function __construct(){
        parent::__construct();
    }

    public function get_power(){
        $file = $this->get_in_str_val('file');
        $data = $this->get_in_str_val('data');
        if($data){
            $this->download_power($data,$file);
        }
        //$file = $file.'.blade.php';
        //$file = $file.'.html';
        return view("test_bacon.".$file);  

    }

    public function download_power($data,$file) {
        //$data = $this->get_in_str_val('data');
        $xls_data= json_decode($data,true);
        //dd($xls_data);
        if(!is_array($xls_data)) {
            return $this->output_err("download error");
        }

        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties()->setCreator("jim ")
             ->setLastModifiedBy("jim")
             ->setSubject("jim subject")
             ->setDescription("jim Desc")
             ->setKeywords("jim key")
             ->setCategory("jim  category");

        //dd($xls_data);

        foreach( $xls_data as $index=> $item ) {
            $index_str = $index+1;
            switch($item['level'])
            {
            case 1:
                $hang = 'A'.$index_str;
                break;
            case 2:
                $hang = 'B'.$index_str;
                break;
            case 3:
                $hang = 'C'.$index_str;
                break;
            case 4:
                $hang = 'D'.$index_str;
                break;
            case 5:
                $hang = 'E'.$index_str;
                break;
            case 6:
                $hang = 'F'.$index_str;
                break;
            case 7:
                $hang = 'G'.$index_str;
                break;
            case 8:
                $hang = 'H'.$index_str;
                break;
            case 9:
                $hang = 'I'.$index_str;
                break;
            case 10:
                $hang = 'J'.$index_str;
                break;

            default:
                $hang = 'A'.$index_str;
                break;

            };
            $objPHPExcel->getActiveSheet()->setCellValue($hang, @$item['name']);
        }

        //$objPHPExcel->getActiveSheet()->setCellValue('A1','haode');

        $objPHPExcel->getActiveSheet()->setTitle('User');
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$file.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }

    public function test_img(){
        $wx_openid = 'oAJiDwJsZROYopRIpIUmHD6GCIYE';
        $phone = 18898881852;
        $agent = $this->t_agent->get_agent_info_by_openid($wx_openid);
        $agent['wx_openid'] = 'oAJiDwJsZROYopRIpIUmHD6GCIYE';
        $agent['phone'] = 18898881852;
        $agent['id'] = 45;
        //$phone = uniqid();
        $bg_url       = "http://7u2f5q.com2.z0.glb.qiniucdn.com/4fa4f2970f6df4cf69bc37f0391b14751506672309999.png";
        $qr_code_url = "http://www.leo1v1.com/market-invite/index.html?p_phone=$phone&type=2";

        $request = ['fromusername'=>'tset'];

        // \App\Helper\Utils::wx_make_and_send_img($bg_url,$qr_code_url,$request,$agent,1);
        $task=new \App\Jobs\make_and_send_wx_img($wx_openid,$bg_url,$qr_code_url,$request,$agent);
        $task->handle();

    }

    public function test_lang(){
        $checkUrl = "http://117.121.10.43:8234/spellcheck/json_check/json_phrase" ;
        $data = [
            // 'content' => '关于电流，下列说法中正确的是（&nbsp;&nbsp）<br/>A．电流是正电荷沿一定方向移动形成的<br/>B．电流是电荷沿一定方向移动形成的<br/>C．电流是负电喝沿一定方向移动形成的<br/>D．物理学规定，电荷定向移动的方向为电留方向',
            // 'doc_type' => 'common',
            // 'company_name' => '上海调小二小有限公司',
            'content'=> '我最喜欢的就是元霄节吃汤元。',
            'mode' => 'advanced',
            'biz_type' => 'show',
            'username' => 'tester',
        ];
        $jsonDataEncoded = json_encode($data);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $checkUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json; charset=utf-8"));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
        $output = curl_exec($ch);
        curl_close($ch);

        //打印获得的数据
        print_r($output);


        // error_reporting(E_ALL ^ E_NOTICE);
        // require_once '/home/bacon/admin_yb1v1/QcloudApi/src/QcloudApi/QcloudApi.php';

        // $config = array('SecretId'        => '你在腾讯云上的SecretId',
        //                 'SecretKey'       => '你在腾讯云上的SecretKey',
        //                 'RequestMethod'  => 'POST',
        //                 'DefaultRegion'    => 'gz');

        // $wenzhi = QcloudApi::load(QcloudApi::MODULE_WENZHI, $config);

        // $package = array("content"=>"李亚鹏挺王菲：加油！孩儿他娘。");

        // $a = $wenzhi->TextSentiment($package);

        // if ($a === false) {
        //     $error = $wenzhi->getError();
        //     echo "Error code:" . $error->getCode() . ".n";
        //     echo "message:" . $error->getMessage() . ".n";
        //     echo "ext:" . var_export($error->getExt(), true) . ".n";
        // } else {
        //     var_dump($a);
        // }

        // echo "nRequest :" . $wenzhi->getLastRequest();
        // echo "nResponse :" . $wenzhi->getLastResponse();
        // echo "n";

        dd('nimei');
    }
}
