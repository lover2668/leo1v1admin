<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
require_once(app_path("/Libs/OSS/autoload.php"));
use OSS\OssClient;

use OSS\Core\OssException;

use Illuminate\Support\Facades\Mail ;

require_once app_path('/Libs/TCPDF/tcpdf.php');
require_once app_path('/Libs/TCPDF/config/tcpdf_config.php');

class testbb extends Controller
{
    use CacheNick;

    var $check_login_flag = false;
    public function get_msg_num() {
        $a= new \App\Jobs\send_error_mail(1,33,33);
        $a->task->t_agent->get_agent_count_by_id(1);

    }



    public function assistant_info_new2(){
        $today      = date('Y-m-d',time(null));
        $today      = '20170626';
        $start_time = strtotime($today.'00:00:00');
        $end_time   = $start_time+24*3600;
        $userid=-1;
        $lesson_arr = [];
        $phone = '456';
        $lesson_arr = $this->t_agent->get_agent_info_row_by_phone($phone);
    }


    public function test1() {
        $account_id = $this->get_in_int_val('id');
        $ass_list = $this->t_admin_group_name->get_group_admin_list($account_id);
        $ass_list = array_column($ass_list,'adminid');
        $ass_list_str = implode(',',$ass_list);
        dd($ass_list_str);
    }



    public function test () {

        $num = $this->t_teacher_day_luck_draw->compute_time();

        dd($num);

        $a = 'http://1111';
        $d = preg_match('/Http/i',$a);
        dd($d);

        $rand = mt_rand(0,100000);
        $money = $rand;

        if($rand>1000 && $rand<=1035){ // 中 91.0元
            $money = '9100'; // 单位分
        }elseif($rand>2000 && $rand <=3000){ // 中9.1元
            $money = '910'; // 单位分
        }elseif($rand>20000 && $rand<33000){ // 中0.91元
            $money = '91'; // 单位分
        }

        echo $money;


    }

    public function lesson_send_msg(){
        $start_time = time(null);
        $this->t_teacher_info->get_lesson_info_by_time($start_time,$end_time);
    }







    public function set_teacher_free_time(){
        $free_time = $this->get_in_str_val('parent_modify_time');

        // 加一个时间的限制
    }


    public function get_nick_phone_by_account_type($account_type,&$item){
            $item["user_nick"]  = $this->cache_get_teacher_nick ($item["userid"] );
            $item['phone']      = $this->t_teacher_info->get_phone_by_nick($item['user_nick']);
    }




    public function tt() {
        $store=new \App\FileStore\file_store_tea();
        $ret=$store->list_dir("10001", "/log1");
        dd($ret);
    }
    public function rename_file() {
        dd(date('Y年m月'));
    }


    public function test_img(){

        $this->switch_tongji_database();
        $ss = $this->t_lesson_info_b3->get_next_day_lesson_info();
        dd($ss);
        $next_day_begin = strtotime(date('Y-m-d',strtotime("+1 days")));
        $next_day_end   = strtotime(date('Y-m-d',strtotime("+2 days")));;
        dd($next_day_end);
    }




    // public function sd(){
    //     $this->switch_tongji_database();
    //     $ret = $this->t_teacher_info->get_teacher_openid_list();

    //     $ww = [];
    //     foreach($ret as $item){
    //         $agent_arr = json_decode($item['user_agent'],true);
    //         $version_arr = explode('.',$agent_arr['version']);
    //         $v = substr($agent_arr['device_model'],0,3);
    //         if(($v == 'Win' || $v=='Mac') && !empty($version_arr) && (($version_arr[0]==3 && $version_arr[1]<=2) || ($version_arr[0]<3 ) ) ){
    //             dispatch( new \App\Jobs\send_wx_to_teacher_for_update_software($item['wx_openid']) );
    //         }
    //     }
    // }


    public function get_pdf_url(){
        // $pdf_file_path = "http:\/\/7tszue.com2.z0.glb.qiniucdn.com\/2d5e65b05f28090c07f9b1e994b1e7151504012597909.pdf?e=1504239357&token=yPmhHAZNeHlKndKBLvhwV3fw4pzNBVvGNU5ne6Px:SXTiWrNfY_mRJajzzUjXn6Sxcd4=";
        // $this->set_in_value('file_url',$file_url);
        // return $this->get_pdf_download_url();
        $pdf_url = "2d5e65b05f28090c07f9b1e994b1e7151504012597909.pdf";
        $lessonid = 247905;
        $pdf_file_path = $this->gen_download_url($pdf_url);


        $savePathFile = public_path('wximg').'/'.$pdf_url;

        if($pdf_url){

            \App\Helper\Utils::savePicToServer($pdf_file_path,$savePathFile);

            $path = public_path().'/wximg';

            @chmod($savePathFile, 0777);
            $imgs_url_list = @$this->pdf2png($savePathFile,$path,$lessonid);

            // dd($imgs_url_list);
            $file_name_origi = array();
            foreach($imgs_url_list as $item){
                $file_name_origi[] = @$this->put_img_to_alibaba($item);
            }

            $file_name_origi_str = implode(',',$file_name_origi);
            dd($file_name_origi_str);

            $ret = $t_lesson_info->save_tea_pic_url($lessonid, $file_name_origi_str);

            foreach($imgs_url_list as $item_orgi){
                @unlink($item_orgi);
            }

            @unlink($savePathFile);
        }



    }

    // public function get_pdf_download_url($file_url)
    // {
    //     if (strlen($file_url) == 0) {
    //         return $this->output_err(array( 'info' => '文件名为空', 'file' => $file_url));
    //     }

    //     if (preg_match("/http/", $file_url)) {
    //         return $this->output_succ( array('ret' => 0, 'info' => '成功', 'file' => $file_url));
    //     } else {
    //         $new_url=$this->gen_download_url($file_url);
    //         // dd($new_url);
    //         return $this->output_succ(array('ret' => 0, 'info' => '成功',
    //                          'file' => urlencode($new_url),
    //                          'file_ex' => $new_url,
    //         ));
    //     }
    // }

    private function gen_download_url($file_url)
    {
        // 构建鉴权对象
        $auth = new \Qiniu\Auth(
            \App\Helper\Config::get_qiniu_access_key(),
            \App\Helper\Config::get_qiniu_secret_key()
        );

        $file_url = \App\Helper\Config::get_qiniu_private_url()."/" .$file_url;

        $base_url=$auth->privateDownloadUrl($file_url );
        return $base_url;
    }

    //
    public function pdf2png($pdf,$path, $lessonid){

        if(!extension_loaded('imagick')){
            return false;
        }
        if(!$pdf){
            return false;
        }
        $IM =new \imagick();
        $IM->setResolution(100,100);
        $IM->setCompressionQuality(100);

        $is_exit = file_exists($pdf);

        if($is_exit){
            @$IM->readImage($pdf);
            foreach($IM as $key => $Var){
                @$Var->setImageFormat('png');
                $Filename = $path."/l_t_pdf_".$lessonid."_".$key.".png" ;
                if($Var->writeImage($Filename)==true){
                    $Return[]= $Filename;
                }
            }
            return $Return;
        }else{
            return [];
        }

    }


    public function put_img_to_alibaba($target){
        try {
            $config=\App\Helper\Config::get_config("ali_oss");
            $file_name=basename($target);

            $ossClient = new OssClient(
                $config["oss_access_id"],
                $config["oss_access_key"],
                $config["oss_endpoint"], false);


            $bucket=$config["public"]["bucket"];
            $ossClient->uploadFile($bucket, $file_name, $target  );

            \App\Helper\Utils::logger('shangchun55'. $config["public"]["url"]."/".$file_name);

            return $config["public"]["url"]."/".$file_name;

        } catch (OssException $e) {
            \App\Helper\Utils::logger( "init OssClient fail");
            return "" ;
        }

    }







    public function ss(){

        // $now = time();
        $now = 1504585740;

        $lesson_begin_five = $now-5*60;
        $lesson_end_five   = $now+6*60;
        $test_lesson_list_five  = $this->t_lesson_info_b2->get_test_lesson_info_for_time($lesson_begin_five,$lesson_end_five);


        foreach($test_lesson_list_five as &$item){
            $item['opt_time_tea'] = $this->t_lesson_opt_log->get_test_lesson_for_login($item['lessonid'],$item['teacherid'],$item['lesson_start'],$item['lesson_end']);
            $item['opt_time_stu'] = $this->t_lesson_opt_log->get_test_lesson_for_login($item['lessonid'],$item['userid'],$item['lesson_start'],$item['lesson_end']);
        }



        dd($test_lesson_list_five);

        $lesson_begin_halfhour = $now+29*60;
        $lesson_end_halfhour   = $now+30*60;


        // 获取试听课 课前30分钟
        $test_lesson_list_halfhour = $this->t_lesson_info_b2->get_test_lesson_info_for_time($lesson_begin_halfhour, $lesson_end_halfhour);

        dd($test_lesson_list_halfhour);
    }





}