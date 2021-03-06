<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
require_once(app_path("/Libs/OSS/autoload.php"));
use OSS\OssClient;

use OSS\Core\OssException;

use Illuminate\Support\Facades\Mail ;

use  App\Jobs\send_wx_notic_for_software;
use  App\Jobs\send_wx_notic_to_tea;
use  App\Jobs\wxPicSendToParent;
use  App\Jobs\marketActivityPoster;
use Illuminate\Support\Facades\Cookie ;
use Illuminate\Support\Facades\Redis ;
use Illuminate\Support\Facades\Session ;




use Illuminate\Http\Request;

# 家长端微信
use LaneWeChat\Core\WeChatOAuth;
use LaneWeChat\Core\AccessToken;
use LaneWeChat\Core\ResponsePassive;
use LaneWeChat\Core\Media;
use LaneWeChat\Core\UserManage;



# 老师微信端
// use Teacher\Core\WeChatOAuth;

// use Teacher\Core\UserManage;

// use Teacher\Core\TemplateMessage;

// use Teacher\Core\Media;

// use Teacher\Core\AccessToken;





// 引入鉴权类
use Qiniu\Auth;

// 引入上传类
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;



include(app_path("Libs/LaneWeChat/lanewechat.php"));
// include(app_path("Wx/Teacher/lanewechat_teacher.php"));



require_once app_path('/Libs/TCPDF/tcpdf.php');
require_once app_path('/Libs/TCPDF/config/tcpdf_config.php');

class test_james extends Controller
{
    use CacheNick;

    var $check_login_flag = false;

    # 文件目录
    # 此处为 工具区 [勿删] 10000
    # 此处为 测试区        20000
    # 此处为 临时文件区    30000
    # 此处为 功能区实现区  40000
    # 此处为 代码预研区    50000
    #
    #


    # 工具区
    /**
     * @ 1.获取老师讲义[pdf]文件在七牛上的路径 [编号:10001 ]
     * @ 2.获取标签系统讲义链接 [编号:10002]
     * @ 3.手动转化老师讲义转png文件 [编号:10003]
     * @ 4.发送邮件 [编号:10004]
     * @ 5.获取组员信息 [编号:10005]
     * @
     * @
     * @
     */

    // 编号:10001
    public function get_file_url()
    {
        $file_url = $this->get_in_str_val('url');
        $auth = new \Qiniu\Auth(
            \App\Helper\Config::get_qiniu_access_key(),
            \App\Helper\Config::get_qiniu_secret_key()
        );

        $file_url = \App\Helper\Config::get_qiniu_private_url()."/" .$file_url;

        $base_url=$auth->privateDownloadUrl($file_url );
        return $base_url;
    }

    // 编号:10002
    public function getFileUrlTea(){
        $store=new \App\FileStore\file_store_tea();
        $auth=$store->get_auth();
        $pdf_url = $this->get_in_str_val('url');

        $pdf_file_path = $auth->privateDownloadUrl("http://teacher-doc.leo1v1.com/".$pdf_url);
        header("Location: $pdf_file_path ");
    }

    // 编号:10003
    public function get_pdf_url(){
        $pdf_url   = $this->get_in_str_val('pdf_url');
        $lessonid  = $this->get_in_int_val('lessonid');
        $pdf_file_path = $this->gen_download_url($pdf_url);

        $pdf_url = str_replace('/','_',$pdf_url);
        $savePathFile = public_path('wximg').'/'.$pdf_url;

        if($pdf_url){
            \App\Helper\Utils::savePicToServer($pdf_file_path,$savePathFile);
            $path = public_path().'/wximg';

            @chmod($savePathFile, 0777);
            $imgs_url_list = @$this->pdf2png($savePathFile,$path,$lessonid);

            $file_name_origi = array();
            foreach($imgs_url_list as $item){
                $file_name_origi[] = @$this->put_img_to_alibaba($item);
            }

            $file_name_origi_str = implode(',',$file_name_origi);
            $ret = $this->t_lesson_info->save_tea_pic_url($lessonid, $file_name_origi_str);

            foreach($imgs_url_list as $item_orgi){
                @unlink($item_orgi);
            }
            @unlink($savePathFile);
        }
    }

    public function getPdfUrl()
    {
        $auth = new \Qiniu\Auth(
            \App\Helper\Config::get_qiniu_access_key(),
            \App\Helper\Config::get_qiniu_secret_key()
        );
        $file_url = $this->get_in_str_val('url');

        $file_url = \App\Helper\Config::get_qiniu_private_url()."/" .$file_url;

        $base_url=$auth->privateDownloadUrl($file_url );
        return $base_url;
    }


    private function gen_download_url($file_url)
    {
        $auth = new \Qiniu\Auth(
            \App\Helper\Config::get_qiniu_access_key(),
            \App\Helper\Config::get_qiniu_secret_key()
        );

        $file_url = \App\Helper\Config::get_qiniu_private_url()."/" .$file_url;

        $base_url=$auth->privateDownloadUrl($file_url );
        return $base_url;
    }

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

    // 编号:10004
    public function get_msg_num() {
        $a= new \App\Jobs\send_error_mail(1,33,33);
        $a->task->t_agent->get_agent_count_by_id(1);
    }

    // 编号:10005
    public function getGroupList() {
        $account_id = $this->get_in_int_val('id');
        $ass_list = $this->t_admin_group_name->get_group_admin_list($account_id);
        $ass_list = array_column($ass_list,'adminid');
        $ass_list_str = implode(',',$ass_list);
        dd($ass_list_str);
    }



















    public function ttt(){// 更新扩课信息
        $require_id = $this->get_in_int_val('rid');
        $origin = $this->get_in_str_val('origin');
        $change_teacher_reason_type = $this->get_in_int_val('change_teacher_reason_type');
        $change_teacher_reason = $this->get_in_str_val('change_teacher_reason');

        $ret= $this->t_test_lesson_subject_require->field_update_list($require_id,[
            "origin" => $origin,
            "change_teacher_reason_type" => $change_teacher_reason_type,
            "change_teacher_reason" => $change_teacher_reason
        ]);

        return $ret;
    }


    public function test () {

        $ret=\App\Helper\Config::get_config("audio_server_list");
        //$ret=\App\Helper\Common::env_obj( "AUDIO_SERVER_LIST" );
        dd($ret);
        dd($_SERVER);
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

        //$this->t_teacher_info->
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




    //以下代码勿删





    public function get_num(){
        $no = rand(1,10000);
        $redirect_url=urlencode("http://wx-parent.leo1v1.com/wx_parent_gift/check_identity_for_book" );
        $appid = 'wx636f1058abca1bc1';

        $u= "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_url&response_type=code&no=$no&scope=snsapi_userinfo&state=STATE_$no&connect_redirect=1#wechat_redirect";

        header("location: $u");

        $this->switch_tongji_database();
        // $teacherid = $this->t_lesson_info_b3->get_on_num();
        $teacherid = $this->t_lesson_info_b3->get_on_teacherid();
        //get_on_num
        $aa = [];
        foreach($teacherid as $item){
            $aa[] = $item['teacherid'];
        }
        // dd($aa);
        $str = implode(',',$aa);

        $ret_info = $this->t_teacher_info->get_on_total($str);
        dd($ret_info);

    }


    public function installNew(){ // 新建表单
        // Schema::dropIfExists('db_weiyi.t_activity_usually');
        // 2018_03_06_164454_t_seller_student_new_add_tmk_sign_invalid
        Schema::table('db_weiyi.t_seller_student_new', function(Blueprint $table) {
            t_field($table->integer("tmk_sign_invalid_time"), "tmk标注无效资源时间");
            t_field($table->integer("tmk_sign_invalid_adminid"), "tmk标注无效资源人");

            $table->index('tmk_sign_invalid_time');
            $table->index('tmk_sign_invalid_adminid');
        });
        /**
           1、记录CC/CR获取转发链接的次数、名单、家长点击次数，家长ID、制作海报次数，最终获得常规课人数，通过此海报注册试听课人数；

           2、确保最终报名试听人员，进入开始分享链接CC/CR私库中；

           3、位置：统计>个性海报转发记录
         **/
        Schema::dropIfExists('t_personality_poster');
        Schema::table('db_weiyi.t_lesson_info', function(Blueprint $table) {
            t_field($table->string("uuid_stu"), "学生PPT讲义的uuid");
            t_field($table->string("zip_url_stu"), "学生讲义压缩包链接");
            t_field($table->tinyInteger("ppt_status_stu"), "学生ppt转化状态 0:未处理 1:已成功 2:失败");
            $table->index('uuid_stu', 'uuid_stu_ppt');
        });

        Schema::create('db_weiyi.t_ppt_file_update_log', function(Blueprint $table) {
            t_comment($table, "ppt转h5基础包文件更新日志表");
            t_field($table->increments("id"), "");
            t_field($table->integer("update_time"), "更新时间");
            t_field($table->integer("ip"), "请求方IP");

            $table->index('ip','ip');
            $table->index('update_time','update_time');
        });


    }

    # 二维码生成
    public function getQrCode(){
        $power_list = json_decode(session("power_list"),true);

        dd($power_list);
        $text         = "http://$www_url/market-invite/index.html?p_phone=".$phone."&type=2";
        $qr_url       = "/tmp/".$phone.".png";
        $bg_url       = "http://7u2f5q.com2.z0.glb.qiniucdn.com/4fa4f2970f6df4cf69bc37f0391b14751506672309999.png";
        \App\Helper\Utils::get_qr_code_png($text,$qr_url,5,4,3);

        exit;

        $qr_url = public_path('wximg').'/test_james.png';
        $qr_code_url = "http://www.leo1v1.com/market/index.html?%E6%9C%8D%E5%8A%A1%E5%8F%B7%E2%80%94%E8%8F%9C%E5%8D%95%E6%A0%8F=";
        $a = \App\Helper\Utils::get_qr_code_png($qr_code_url,$qr_url,5,4,3);
        echo $qr_url;
        dd($a);
    }



    public function https_post($url,$data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    public function ch_json_encode($data) {


        $ret = self::ch_urlencode($data);
        $ret = json_encode($ret);

        return urldecode($ret);
    }

    public function ch_urlencode($data) {
        if (is_array($data) || is_object($data)) {
            foreach ($data as $k => $v) {
                if (is_scalar($v)) {
                    if (is_array($data)) {
                        $data[$k] = urlencode($v);
                    } else if (is_object($data)) {
                        $data->$k = urlencode($v);
                    }
                } else if (is_array($data)) {
                    $data[$k] = self::ch_urlencode($v); //递归调用该函数
                } else if (is_object($data)) {
                    $data->$k = self::ch_urlencode($v);
                }
            }
        }

        return $data;
    }





    public function send_wx_msg(){
        $wx = new \App\Helper\Wx();

        $parent_template_id  = '9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU';

        $openid = 'orwGAs_IqKFcTuZcU1xwuEtV3Kek';

        $data_leo = [
            'first'    => "测试 first",
            'keyword1' => "keyword1",
            'keyword2' => "keyword2",
            'keyword3' => "keyword3",
            'remark'   => "测试信息!"
        ];

        $url_leo = 'http://admin.leo1v1.com/test_james/jilu?test=1';
        $wx->send_template_msg($openid, $parent_template_id, $data_leo, $url_leo);
    }



    public function power_group_edit() {
        $group_list = $this->t_authority_group->get_auth_groups();
        $default_groupid = 0;
        if (count($group_list)>0) {
            $default_groupid= $group_list[0]["groupid"];
        }
        $groupid  = $this->get_in_int_val("groupid",$default_groupid);
        $show_flag= $this->get_in_int_val("show_flag", -1);
        $user_list=[];
        $user_list=$this->t_manager_info->get_power_group_user_list($groupid);
        if ($show_flag!=2) { //只用户
            $power_map=$this->t_authority_group->get_auth_group_map($groupid);
            $list=$this->get_menu_list($power_map );

            $n=["k1"=>"","k2"=>"","k3"=>"" ];
            $n["k1" ]= "其它";
            $n["pid" ]= 0;
            $k1_class= $this->gen_class(1);
            $n["k_class" ]= $k1_class;
            $n["class" ]=  "l_1 $k1_class " ;
            $n["level" ]=  "1" ;
            $n["folder" ]=  true;
            $n["has_power_flag" ]= "" ;
            $list[]=$n;

            foreach (E\Epower::$desc_map as $k=> $v) {
                $n=["k1"=>"----","k2"=>"","k3"=>"" ];
                $k2_pid=$k;
                $n["k2" ]= $v ;
                $n["pid" ]= $k2_pid;
                $k2_class= $this->gen_class(2);
                $n["k_class" ]= $k2_class;
                $n["class" ]= "l_2 $k1_class $k2_class";
                $n["level" ]=  "2" ;
                $n["folder" ]=  false;
                $n["has_power_flag" ]= isset($power_map["$k2_pid"])?"checked":"" ;
                $list[]=$n;
            }
            $ret_info=\App\Helper\Utils::list_to_page_info($list);
        }else{
            $ret_info=\App\Helper\Utils::list_to_page_info([]);
        }

        // dd($ret_info);

        return $this->Pageview(__METHOD__,$ret_info,[
            "group_list"=>$group_list,
            "user_list"=>$user_list,
        ]);
    }








    public function upload_subject_grade_textbook_from_xls(){ // 测试区
        $file = Input::file('file');
        $list    = E\Eregion_version::$desc_map;
        $list_new =[];
        foreach($list as $k=>$i){
            $list_new[$i] = $k;
        }
        if ($file->isValid()) {
            //处理列
            $realPath = $file -> getRealPath();
            $objReader = \PHPExcel_IOFactory::createReader('Excel2007');

            $objPHPExcel = $objReader->load($realPath);
            $objPHPExcel->setActiveSheetIndex(0);
            $arr=$objPHPExcel->getActiveSheet()->toArray();
            foreach($arr as $k=>&$val){
                if(empty($val[0]) || $k==0 || $k==1 || $k==2){
                    unset($arr[$k]);
                }
            }
            foreach($arr as $item){
                $small = $item[2];
                $small_arr = explode("、",$small);
                $small_list=[];
                foreach($small_arr as $v){
                    if(isset($list_new[$v])){
                        $small_list[] = $list_new[$v];
                    }
                }

                $small_str =  implode(",",$small_list);

                // $is_exist3 = $this->t_location_subject_grade_textbook_info->check_is_exist($item[0],$item[1],100,2);
                $is_exist3=0;
                if($is_exist3>0){
                    $this->t_location_subject_grade_textbook_info->field_update_list($is_exist3,[
                        "teacher_textbook" =>$small_str
                    ]);
                }else{
                    $this->t_location_subject_grade_textbook_info->row_insert([
                        "province"  =>$item[0],
                        "city"      =>$item[1],
                        "subject"   =>3,
                        "grade"     =>100,
                        "teacher_textbook"=>$small_str,
                        // "educational_system" =>$item[2]
                    ]);
                }

                $middle = $item[3];
                $middle_arr = explode("、",$middle);
                $middle_list=[];
                foreach($middle_arr as $v){
                    if(isset($list_new[$v])){
                        $middle_list[] = $list_new[$v];
                    }
                }

                $middle_str =  implode(",",$middle_list);

                //$is_exist = $this->t_location_subject_grade_textbook_info->check_is_exist($item[0],$item[1],200,2);
                $is_exist=0;
                if($is_exist>0){
                    $this->t_location_subject_grade_textbook_info->field_update_list($is_exist,[
                       "teacher_textbook" =>$middle_str
                    ]);
                }else{
                    $this->t_location_subject_grade_textbook_info->row_insert([
                        "province"  =>$item[0],
                        "city"      =>$item[1],
                        "subject"   =>3,
                        "grade"     =>200,
                        "teacher_textbook"=>$middle_str,
                        // "educational_system" =>$item[2]
                    ]);
                }


                $senior = $item[4];
                $senior_arr = explode("、",$senior);
                $senior_list=[];
                foreach($senior_arr as $v){
                    if(isset($list_new[$v])){
                        $senior_list[] = $list_new[$v];
                    }
                }
                $senior_str =  implode(",",$senior_list);
                // $is_exist2 = $this->t_location_subject_grade_textbook_info->check_is_exist($item[0],$item[1],300,2);
                $is_exist2=0;
                if($is_exist2>0){
                    $this->t_location_subject_grade_textbook_info->field_update_list($is_exist2,[
                        "teacher_textbook" =>$senior_str
                    ]);

                }else{

                    $this->t_location_subject_grade_textbook_info->row_insert([
                        "province"  =>$item[0],
                        "city"      =>$item[1],
                        "subject"   =>3,
                        "grade"     =>300,
                        "teacher_textbook"=>$senior_str,
                        // "educational_system" =>$item[2]
                    ]);
                }



            }


            //dd($arr);
            //(new common_new()) ->upload_from_xls_data( $realPath);

            return outputjson_success();
        } else {
            //return 111;
            //dd(222);
            return outputjson_ret(false);
        }

    }



    public function download_xls ()  { // 测试
        // $xls_data= session("xls_data" );
        $xsl_data = '
[["Field","Type","Collation","Null","Key","Default","Extra","Privileges","Comment"],["id","int(10) unsigned","","NO","PRI","","auto_increment","select,insert,update",""],["parentid","int(11)","","NO","MUL","","","select,insert,update","家长id"],["get_prize_time","varchar(255)","latin1_bin","NO","MUL","","","select,insert,update","领奖时间"],["presenterid","int(11)","","NO","MUL","","","select,insert,update","发奖人"],["prize_time","int(11)","","NO","","","","select,insert,update","抽奖时间"],["stu_type","tinyint(4)","","NO","","","","select,insert,update","学员类型 1:新用户 2:老用户"],["create_time","int(11)","","NO","","","","select,insert,update","后台奖品录入时间"],["validity_time","int(11)","","NO","","","","select,insert,update","有效期"],["to_orderid","int(11)","","NO","MUL","","","select,insert,update","合同id"],["prize_type","int(11)","","NO","","","","select,insert,update","ruffian_prize_type 枚举类"]]
';

        $xsl_data = json_decode($xsl_data,true);


        if(!is_array($xsl_data)) {
            return $this->output_err("download error");
        }

        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties()->setCreator("jim ")
                             ->setLastModifiedBy("jim")
                             ->setTitle("jim title")
                             ->setSubject("jim subject")
                             ->setDescription("jim Desc")
                             ->setKeywords("jim key")
                             ->setCategory("jim  category");

        $objPHPExcel->setActiveSheetIndex(0);

        $col_list=[
            "A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T", "U","V","W","X","Y","Z"
            ,"AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ"
            ,"BA","BB","BC","BD","BE","BF","BG","BH","BI","BJ","BK","BL","BM","BN","BO","BP","BQ","BR","BS","BT","BU","BV","BW","BX","BY","BZ"
            ,"CA","CB","CC","CD","CE","CF","CG","CH","CI","CJ","CK","CL","CM","CN","CO","CP","CQ","CR","CS","CT","CU","CV","CW","CX","CY","CZ"

        ];

        foreach( $xsl_data as $index=> $item ) {
            foreach ( $item as $key => $cell_data ) {
                $index_str = $index+1;
                $pos_str   = $col_list[$key].$index_str;
                $objPHPExcel->getActiveSheet()->setCellValue( $pos_str, $cell_data);
            }
        }

      $date=\App\Helper\Utils::unixtime2date (time(NULL));
      header('Content-type: application/vnd.ms-excel');
      header( "Content-Disposition:attachment;filename=\"$date.xlsx\"");

      $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

      $objWriter->save('php://output');
    }


    public function check_file(){
        $filename = "D:\\296.mid";

        $arr = explode('.', $filename);

        dd($arr);

        $file = fopen($filename, "rb");
        $bin = fread($file, 2); //只读2字节
        fclose($file);
        $strInfo = @unpack("c2chars", $bin);
        $typeCode = intval($strInfo['chars1'].$strInfo['chars2']);
        $fileType = '';
        dd($typeCode);
        switch ($typeCode)
        {
        case 7790:
            $fileType = 'exe';
            break;
        case 7784:
            $fileType = 'midi';
            break;
        case 8297:
            $fileType = 'rar';
            break;
        case 255216:
            $fileType = 'jpg';
            break;
        case 7173:
            $fileType = 'gif';
            break;
        case 6677:
            $fileType = 'bmp';
            break;
        case 13780:
            $fileType = 'png';
            break;
        default:
            echo 'unknown';
        }
        echo 'this is a(an) '.$fileType.' file:'.$typeCode;
    }


    public function deal_untreated_pdf(){// 处理未成功pdf文件
        $num = $this->get_in_int_val('n',-1);
        $limit_time = $this->get_in_int_val('time',-1);

        $pdf_list = $this->t_pdf_to_png_info->get_untreated_pdf($num,$limit_time);

        foreach($pdf_list as $v){
            $this->set_in_value("pdf_url", $v['pdf_url']);
            $this->set_in_value("lessonid", $v['lessonid']);

            $this->get_pdf_url();

            $this->t_pdf_to_png_info->field_update_list($v['id'], [
                "id_do_flag" => 1
            ]);
        }
    }



    // {
    //     "touser":"OPENID",
    //         "msgtype":"image",
    //         "image":
    //     {
    //         "media_id":"MEDIA_ID"
    //             }
    // }

    public function wx_news(){ // 使用客服接口发送消息

        // $filename = "/home/ybai/tu.jpg";
        // $type = "image";

        // $Media_id = Media::upload($filename, $type);

        // $parent_list = $this->t_parent_info->get_parent_opend_list();

        // dd($parent_list);

        // dispatch( new \App\Jobs\wxPicSendToParent(''));




        return ;
        // dd($Media_id);
        //使用客服接口发送消息
        // $txt_arr = [
        //     'touser'   => 'orwGAs_IqKFcTuZcU1xwuEtV3Kek',// james
        //     'msgtype'  => 'news',
        //     "news"=>[
        //         "articles"=> [
        //             [
        //                 "title"=>"“呼朋唤友”活动来袭！邀请好友来上课，万元大礼等你拿！",
        //                 "description"=>"TEST",
        //                 "url"=>"",
        //                 "picurl"=>"http://loemobile.oss-cn-shanghai.aliyuncs.com/wx/%E7%90%86%E4%BC%98%E6%95%99%E8%82%B2%E5%9C%A8%E7%BA%BF-%E5%8E%9F%E5%9B%BE/%E6%B4%BB%E5%8A%A8/699592341.jpg"
        //             ]
        //         ]
        //     ]
        // ];


        //使用客服接口发送消息
        // $txt_arr = [
        //     'touser'   => 'oJ_4fxPmwXgLmkCTdoJGhSY1FTlc',// james
        //     'msgtype'  => 'text',
        //     "text"=>[
        //         "content"=>"Hello World <a  onclick='alert('你已经点击了我！');' >百度</a>"
        //     ]
        // ];


        // dd($Media_id);
        // 使用客服接口发送消息
        $txt_arr = [
            'touser'   => 'orwGAswh6yMByNDpPz8ToUPNhRpQ',// james
            'msgtype'  => 'image',
            "image"=>[
                "media_id"=>$Media_id['media_id']
            ]
        ];



        $appid_tec     = config('admin')['wx']['appid'];
        $appsecret_tec = config('admin')['wx']['appsecret'];

        $wx = new \App\Helper\Wx() ;
        $token = $wx->get_wx_token($appid_tec,$appsecret_tec);

        $txt = $this->ch_json_encode($txt_arr);
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$token;
        $txt_ret = $this->https_post($url,$txt);

        dd($txt_ret);

    }


    public function test_wx(){
        $template_id = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
        $data = [
            "first"     => "微信 老师推送测试",
            "keyword1"  => "\<a href='https://baidu.com \>1\<\/a\>",
            "keyword2"  => "微信推送测试 ".'<a onclick="alert("你已经点击了我！");" >百度</a>',
            "keyword3"  => date('Y-m-d H:i:s'),
        ];
        $openid = "oJ_4fxPmwXgLmkCTdoJGhSY1FTlc";
        $url    = "http://admin.leo1v1.com/test_james/wx_news";

        \App\Helper\Utils::send_teacher_msg_for_wx($openid,$template_id,$data,$url);
    }


    public function get_user_list(){
        $a = $this->get_in_str_val("s");
        $user_list = UserManage::getFansList($next_openId=$a);

        dd($user_list);
    }








    public function get_lesson_list(){
        $start_time = $this->get_in_int_val("s");
        $end_time   = $this->get_in_int_val("e");
        $type = $this->get_in_int_val('type',-1);
        $lesson_list = $this->t_lesson_info_b3->get_unlesson($start_time, $end_time,$type);
        dd($lesson_list);
    }

    public function ddd(){
        $lessonid    = $this->get_in_int_val("s");
        $lesson_type = $this->get_in_int_val("e");
        $is_fail = $this->t_lesson_info_b3->check_is_fail($lessonid,$lesson_type);

        dd($is_fail);
    }


    public function get_check_data(){
        $start_time = time();
        $end_time   = $start_time+60;

        $lesson_list = $this->t_lesson_info_b3->get_common_list($start_time,$end_time);

        foreach($lesson_list as $i=>$item){
            $not_first = $this->t_lesson_info_b3->check_not_first_lesson($item['userid'],$item['teacherid'],$item['subject'],$item['lesson_start']);

            if($not_first == 1){
                unset($lesson_list[$i]);
            }
        }

        foreach($lesson_list as &$item){
            $item['lesson_count_str'] = $item['lesson_count']/100;
            $item['subject_str'] = E\Esubject::get_desc($item['subject']);
            $item['tea_nick'] = $this->cache_get_teacher_nick($item['teacherid']);
        }

        dd($lesson_list);
    }

    /**
     * @ 学生和老师同时在教室的时长超过20分钟则认为课程有效
     * @ 否则 将该课程标记 通知人工进行审查
     **/
    public function check_lesson_status(){
        $lessonid = $this->get_in_int_val('lessonid');

        $userid = $this->t_lesson_info_b3->get_userid($lessonid);
        $teaid  = $this->t_lesson_info_b3->get_teacherid($lessonid);

        $login_log_stu = $this->t_lesson_opt_log->get_stu_log($lessonid,$userid);
        $login_log_tea = $this->t_lesson_opt_log->get_stu_log($lessonid,$teaid);


    }

    /**
     * @ 百度语音识别
     **/

    function request_post($url = '', $param = '') {
        if (empty($url) || empty($param)) {
            return false;
        }

        $postUrl = $url;
        $curlPost = $param;
        $curl = curl_init();//初始化curl
        curl_setopt($curl, CURLOPT_URL,$postUrl);//抓取指定网页
        curl_setopt($curl, CURLOPT_HEADER, 0);//设置header
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($curl, CURLOPT_POST, 1);//post提交方式
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($curl);//运行curl
        curl_close($curl);

        return $data;
    }

    /**
       App ID: 10485794

       API Key: sViPNnv5bUMEEHwC6FMriOOw

       Secret Key: 1e3f8259f26c7b5b202ec6c380b3de20


       string(585) "{"access_token":"24.17dde2e70ba5d12d9217220c62a95853.2592000.1515234816.282335-10485794","session_key":"9mzdCrO0iPQ\/XaC9XIeUd7o2tWb7JeLPwCHucfWyV6psR7MP+WceUG\/4AiEyFHExgiX1xtU\/zzpvH+vyHpjfcO21RfpfAQ==","scope":"public audio_voice_assistant_get wise_adapt lebo_resource_base lightservice_public hetu_basic lightcms_map_poi kaidian_kaidian ApsMisTest_Test\u6743\u9650 vis-classify_flower bnstest_fasf lpq_\u5f00\u653e","refresh_token":"25.9fb11b09ef6d52e2c7b8e1c437dbf801.315360000.1828002816.282335-10485794","session_secret":"06a483d75528389b43907dcb2ea66ba4","expires_in":2592000} "
     */

    public function get_baidu_token(){
        $url = 'https://aip.baidubce.com/oauth/2.0/token';
        $post_data['grant_type']       = 'client_credentials';
        $post_data['client_id']      = 'sViPNnv5bUMEEHwC6FMriOOw';
        $post_data['client_secret'] = '1e3f8259f26c7b5b202ec6c380b3de20';
        $o = "";
        foreach ( $post_data as $k => $v )
        {
            $o.= "$k=" . urlencode( $v ). "&" ;
        }
        $post_data = substr($o,0,-1);

        $res = $this->request_post($url, $post_data);

        var_dump($res);

    }


    public function chang_wen(){
        $url = "http://vop.baidu.com/server_api";

        $path = '/home/ybai/16k.wav';
        $fp = fopen($path, 'rb');  // 以二进制形式打开文件
        $content = fread($fp, filesize($path)); // 读取文件内容
        fclose($fp);
        $content = base64_encode($content); // 将二进制信息编码成字符串

        $content = str_replace("\n"," ",$content);

        $post_data = [
            "format"=>"wav",
            "rate"=>16000,
            "channel"=>1,
            "token"=>"24.17dde2e70ba5d12d9217220c62a95853.2592000.1515234816.282335-10485794",
            "cuid"=>"baidu_workshop122xuejijams",
            "len"=>127,
            "lan" => "zh",
            "speech"=>"$content",

            // "url" => "http://speech-doc.gz.bcebos.com/rest-api-asr/public_audio/16k.wav",
            // "callback" => "http://admin.leo1v1.com/test_james/get_post"

        ];

        $post_data = json_encode($post_data);



        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: '.strlen($post_data)
        ));
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);

        $ret_arr = json_decode($output,true);

        return $ret_arr;
    }

    public function get_post(){
        $a = $_SESSION;
        dd($a);
    }

    public function get_no(){
        $a = $this->t_lesson_info_b2->get_need_late_notic();
        $b = $this->t_lesson_info_b2->get_test_lesson_to_notic();
        dd($b);
    }


    /**
     * @ 讯飞听见 WebAPI

     */


    public function xunfei(){
        $url = "http://api.xfyun.cn/v1/aiui/v1/iat"; //

        $path = '/home/ybai/16k.wav';
        $fp = fopen($path, 'rb');  // 以二进制形式打开文件
        $content = fread($fp, filesize($path)); // 读取文件内容
        fclose($fp);
        $content = base64_encode($content); // 将二进制信息编码成字符串

        $Param = [
            "auf"   => '16k',
            "aue"   => 'raw',
            "scene" => 'main'
        ];

        $time = time();
        $param = base64_encode(json_encode($Param));
        $check_str = '07adb47e30dd4b9b8fdcddc5e96e6b78'.$time.''.$param.'data='.$content;
        $CheckSum = md5($check_str);

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type:application/x-www-form-urlencoded; charset=utf-8',
            'X-Param:'.$param,
            'X-Appid: 5a2a4204',
            'X-CurTime:'.$time,
            'X-CheckSum:'.$CheckSum
        ));
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'data='.$content);
        $output = curl_exec($ch);
        curl_close($ch);

        $ret_arr = json_decode($output,true);



        dd($ret_arr);
        return $ret_arr;
    }


    //以上讯飞已接入成功


    /**
     * @ 将远程录音下载到本地
     * @ 将录音格式 .MP3 => WAV [若不是WAV 则 => WAV]
     * @ 对录音进行分割
     * @ 对分割文件进行 文字转换
     * @ 对转换内容进行拼接 并存入数据库
     * @ 将视频删除
     * @
     * @
     **/

    public function chunk_voice(){
        $voice_url = $this->get_in_str_val('voice_url');
        // @将远程录音下载到本地
        $pdf_file_path = $this->get_pdf_download_url($voice_url);
        $savePathFile = public_path('wximg').'/1.mp3';
        $msg = \App\Helper\Utils::savePicToServer($pdf_file_path,$savePathFile);

        // @录音格式转换
        // ffmpeg -i sample.mp3 sample.wav
        $input_file  = "";
        $output_file = "";

        $transhell = 'ffmpeg -i '.$input_file.' '.$output_file.' ';
        shell_exec($transhell);

        dd($msg);
    }



    public function get_pdf_download_url($file_url){
        if (preg_match("/http/", $file_url)) {
            return $file_url;
        } else {
            return \App\Helper\Utils::gen_download_url($file_url);
        }
    }










    public function video_info() {

        //ffmpeg -i sample.mp3 sample.wav
        //mkdir split; X=0; while( [ $X -lt 5 ] ); do echo $X; ffmpeg -i big_audio_file.mp3 -acodec copy -t 00:30:00 -ss 0$X:00:00 split/${X}a.mp3; ffmpeg -i big_audio_file.mp3 -acodec copy -t 00:30:00 -ss 0$X:30:00 split/${X}b.mp3;  X=$((X+1)); done;
        $file = '/home/ybai/16k.wav';
        $ffmpeg = "ffmpeg";

        ob_start();
        passthru(sprintf($ffmpeg.' -i "%s" 2>&1', $file));
        $info = ob_get_contents();
        ob_end_clean();
        // 通过使用输出缓冲，获取到ffmpeg所有输出的内容。
        $ret = array();
        // Duration: 01:24:12.73, start: 0.000000, bitrate: 456 kb/s
        if(preg_match("/Duration: (.*?), start: (.*?), bitrate: (d*) kb\/s/", $info, $match)) {
            $ret['duration'] = $match[1]; // 提取出播放时间
            $da = explode(':', $match[1]);
            $ret['seconds'] = $da[0] * 3600 + $da[1] * 60 + $da[2]; // 转换为秒
            $ret['start'] = $match[2]; // 开始时间
            $ret['bitrate'] = $match[3]; // bitrate 码率 单位 kb
        }

        // Stream #0.1: Video: rv40, yuv420p, 512x384, 355 kb/s, 12.05 fps, 12 tbr, 1k tbn, 12 tbc
        if (preg_match("/Video: (.*?), (.*?), (.*?)[,s]/", $info, $match)) {
            $ret['vcodec'] = $match[1]; // 编码格式
            $ret['vformat'] = $match[2]; // 视频格式
            $ret['resolution'] = $match[3]; // 分辨率
            $a = explode('x', $match[3]);
            $ret['width'] = $a[0];
            $ret['height'] = $a[1];
        }

        // Stream #0.0: Audio: cook, 44100 Hz, stereo, s16, 96 kb/s
        if (preg_match("/Audio: (w*), (d*) Hz/", $info, $match)) {
            $ret['acodec'] = $match[1];       // 音频编码
            $ret['asamplerate'] = $match[2];  // 音频采样频率
        }

        if (isset($ret['seconds']) && isset($ret['start'])) {
            $ret['play_time'] = $ret['seconds'] + $ret['start']; // 实际播放时间
        }

        $ret['size'] = filesize($file); // 文件大小
        return $ret;
    }


    public function voice_to_text(){
        //输入文件名,输入文件总时间,输出文件名,分割点时间,模式[0保存前段1保存后段]
        // $mp3_cut=new mp3_cut("input.mp3",265,"output.mp3",120,0);
        $this->mp3_cut();
        echo $mp3_cut->error_message;
    }

    public function mp3_cut($old_file,$time_length,$new_file,$start_time,$type){
        $this->cut($type);
    }

    public function cut($type){
        $this->set_input_file_size();
        $this->byte_per_second();
        $this->open_input_mp3();
        $this->open_output_mp3();
        $this->make_new_mp3($type);
        $this->close_output_mp3();
        $this->close_input_mp3();
    }

    public function set_input_file_size(){
        $this->file_size=@filesize($this->old_file);
    }

    public function byte_per_second(){
        $this->byte_per_second=(integer)($this->file_size/$this->time_length);
    }

    public function make_new_mp3($type){
        $start_position=$this->start_time*$this->byte_per_second;
        if($type==1){
            fseek($this->input_data,$start_position);
            while(!@feof($this->input_data)){
                @fwrite($this->output_data,@fread($this->input_data,$this->data_buffer));
            }
        }
        else{
            while(@ftell($this->input_data)<=((integer)$start_position)){
                @fwrite($this->output_data,@fread($this->input_data,($this->byte_per_second/2)));
            }
        }
    }


    public function open_input_mp3(){
        if(file_exists($this->old_file)){
            $this->input_data=fopen($this->old_file,"r");
            $result=true;
        }
        else{
            $this->error("打开文件错误");
            $result=false;
        }
        return $result;
    }

    public function close_input_mp3(){
        if(@fclose($this->input_data)){$result=true;}
        else{
            $this->error("操作输入文件错误");
            $result=false;
        }
        return $result;
    }


    public function open_output_mp3(){
        $this->output_data=@fopen($this->new_file,"w");
        if($this->output_data){$result=true;}
        else{
            $this->error("创建文件错误");
            $result=false;
        }
        return $result;
    }

    public function close_output_mp3(){
        if(@fclose($this->output_data)){
            $result=true;
        }else{
            $this->error("操作输出文件错误");
            $result=false;
        }
        return $result;
    }


    public function error($error_message){
        $this->error_message.=$error_message."<br>";
    }

    public function dd(){
        $a = $this->t_parent_info->get_parent_opend_list();

        dd($a);

        $adminid_list = $this->t_admin_main_group_name->get_adminid_list_new("");
        // // $month_start_time = strtotime(date("Y-m-01",$end_time));
        $month_start_time = '1509465600';
        $month_end_time = strtotime(date('Y-m-01', strtotime('+1 month',$month_start_time)));
        // $main_type = 2;// 销售
        // $ret_info['seller_target_income'] = $this->get_month_finish_define_money(0,$month_start_time); // 销售月目标收入
        // if (!$ret_info['seller_target_income'] ) {
        //     $ret_info['seller_target_income'] = 1600000;
        // }

        $month_date_money_list = $this->t_order_info->get_seller_date_money_list($month_start_time,$month_end_time,$adminid_list);

        $price = 0;
        foreach($month_date_money_list as $v){
            $price += $v['money'];
        }
        echo $price;
        dd($month_date_money_list);

    }


    public function get_parent_tmp(){
        $num = $this->t_parent_info->getParentNum();

        dd($num);

    }


    /**
     * @ leo1v1.whytouch.com token:bbcffc83539bd9069b755e1d359bc70a
     ﻿﻿* @ ok:ge8f6101611aa12c5949fcd272c5b5e1 [cQ161]
     * @ 测试 文件上传
     * @ 已为您生成SDK测试账号，token：bbcffc83539bd9069b755e1d359bc70a，其权限与微演示账户michael@leoedu.com相同。
     * @ 使用方法请参看 http://ts.whytouch.com/help.php#dev
     * @ 测试期间请勿上传过量文件，以免影响系统正常运行，否则客服人员可能暂停或关闭本测试账户。
     * @ michael@leoedu.com 密码 ： 021130
     * @gf5090e8e98978bfbf0e3e074593ade[cq161]
     * @ g9029ce6062262c6fd33a4bb38956ac8 //uuid [test.pdf] g28bcd158f6b7d823d8f1c6df23bbaf5
     * @ curl -F doc=@'/home/ybai/test.pdf' 'http://ts.whytouch.com/mass_up.php?token=bbcffc83539bd9069b755e1d359bc70a&mode=-1&aut=James&f n=新文件.pptx'
     **/
    public function translate_pdf(){
        $path = $this->get_in_str_val('path');
        $cmd  = "curl -F doc=@'$path' 'http://leo1v1.whytouch.com/mass_up.php?token=bbcffc83539bd9069b755e1d359bc70a&mode=-1&aut=James&fn=新文件.pdf'";
        $uuid = shell_exec($cmd);
        dd($uuid);
    }

    public function get_teacher_note(){
        $file_link = $this->get_in_str_val("link");
        $store=new \App\FileStore\file_store_tea();
        $auth=$store->get_auth();
        $authUrl = $auth->privateDownloadUrl("http://teacher-doc.leo1v1.com/". $file_link );
        return $authUrl;
        dd($authUrl);
        return $this->output_succ(["url" => $authUrl]);
    }









    public function downloadLink(){
        $file_link = $this->get_in_str_val("link");
        $pdfOnlineLink = $this->getPdfLink($file_link);
        $savePathFile = public_path('wximg').'/'.$file_link;

        \App\Helper\Utils::savePicToServer($pdfOnlineLink,$savePathFile);
    }


    public function getPdfLink($file_link){
        $store = new \App\FileStore\file_store_tea();
        $auth  = $store->get_auth();
        $authUrl = $auth->privateDownloadUrl("http://teacher-doc.leo1v1.com/".$file_link );
        return $authUrl;
    }

    public function md5Pwd(){
        dd(md5('021130'));
    }

    public function test_ce(){
        $b = "http://leo1v1.whytouch.com/export.php?uuid=gf15a4973b034c84d4f631be74b21741&email=michael@leoedu.com&pwd=bbcffc83539bd9069b755e1d359bc70a";
        $a = file_get_contents($b);

        file_put_contents("./test_jammes_pdf.zip", fopen($h5DownloadUrl, 'r'));
        dd($a);

        $domain = config('admin')['qiniu']['public']['url'];
        $a = 'ok:gf15a4973b034c84d4f631be74b21741.zip';
        // $b = $domain."/".$a;

        $uuid_arr = explode(':', $a);
        dd($uuid_arr);

        // $b = substr($a,3);
        dd($b);
    }

    public function getUrl(){
        $url = $this->get_in_str_val('url');
        $domain = config('admin')['qiniu']['public']['url'];
        $change_reason_url = $domain.'/'.$url;
        dd($change_reason_url);
        //https://ybprodpub.leo1v1.com/
        //gdb752962bc31cc483cf576fb1fdd8d7.zip
    }


    public function do_qiniu ( $public_flag )  {

        /*
        'qiniu' => [
            "public" => [
                "url"    => env('QINIU_PUBLIC_URL', 'http://7u2f5q.com2.z0.glb.qiniucdn.com'),
                "bucket" => "ybprodpub",
            ] ,
            "private_url" => [
                "url"    => env('QINIU_PRIVATE_URL', 'http://7tszue.com2.z0.glb.qiniucdn.com'),
                "bucket" => "ybprod",
            ] ,
            "access_key" => "yPmhHAZNeHlKndKBLvhwV3fw4pzNBVvGNU5ne6Px",
            "secret_key" => "gPwzN2_b1lVJAr7Iw6W1PCRmUPZyrGF6QPbX1rxz",

        ],
        */
        $config=\App\Helper\Config::get_config("qiniu");


        // 构建鉴权对象
        $auth = new Auth($config["access_key"], $config["secret_key"]);

        if ($public_flag ) {
            $bucket_info=$config["public" ];
        }else{
            $bucket_info=$config["private_url" ];
        }
        $bucket=$bucket_info["bucket"];

        // 生成上传 Token
        $token = $auth->uploadToken($bucket);


        return  $this->output_succ([
            "bucket" =>  $bucket,
            "token" =>$token,
            "url"=> $bucket_info["url"]
        ] );
    }

    function qiniu_upload_token() {
        $public_flag=$this->get_in_int_val("publish_flag",0 );
        return $this->do_qiniu($public_flag );
    }



    public  function savePicToServer() {
        $savePathFile = "test.zip";
        $url = "http://wx-parent-web.leo1v1.com/wx-parent-activity/share.html?openid=orwGAs_IqKFcTuZcU1xwuEtV3Kek&type=102&web_page_id=0&from_adminid=0";
        $targetName   = $savePathFile;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        $fp = fopen($targetName,'wb');

        curl_setopt($ch,CURLOPT_URL,$pic_url);
        curl_setopt($ch,CURLOPT_FILE,$fp);
        curl_setopt($ch,CURLOPT_HEADER,0);
        $ret_info['state'] = curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        $ret_info['savePathFile'] = $savePathFile;

        return $ret_info;
    }

    public function dddd(){
        $key = $this->get_in_str_val("key");
        // 构建鉴权对象

        $qiniu     = \App\Helper\Config::get_config("qiniu");
        $bucket    = $qiniu['public']['bucket'];
        $accessKey = $qiniu['access_key'];
        $secretKey = $qiniu['secret_key'];

        $unzipFilePath  =  public_path('pdfToH5'); // 解压后的文件夹
        $auth = new \Qiniu\Auth ($accessKey, $secretKey);
        $h5Path = "pdfToH5"; // 环境文件夹
        // 上传到七牛后保存的文件名
        $upkey = $h5Path."/".$key;

        // 生成上传 Token
        $token = $auth->uploadToken($bucket,$upkey);
        $Upfile = $unzipFilePath."/".$key;

        // 初始化 UploadManager 对象并进行文件的上传。
        $uploadMgr = new \Qiniu\Storage\UploadManager();

        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        list($ret, $err) = $uploadMgr->putFile($token, $upkey, $Upfile);



        exit();


        $hotcat =array(
            array('catid'=>'1546','catname'=>'数组排序 一级','count'=>'588'),
            array('catid'=>'1546','catname'=>'数组排序二级','count'=>'584'),
            array('catid'=>'1546','catname'=>'数组排序 三级','count'=>'589')
        );

        foreach($hotcat as $i => $item){
            $ret[$i] = $item['count'];
        }

        array_multisort($ret,SORT_DESC,$hotcat);//此处对数组进行降序排列；SORT_DESC按降序排列

        dd($hotcat);
    }


    public function chooseResource(){

        $subject = $this->get_in_int_val('subject');
        $grade = $this->get_in_int_val('grade');
        $file_id = $this->t_resource->getResourceId($subject,$grade);
        $resource_id = $this->t_resource->getResourceId($subject,$grade);

        dd($resource_id);

        dd($file_id);

        $file_id   = $this->get_in_int_val('file_id');
        $teacherid = $this->get_in_int_val("teacherid");
        $lessonid  = $this->get_in_int_val("lessonid");

        $ret_info['checkIsUse'] = $this->t_lesson_info_b3->checkIsUse($lessonid);
        $ret_info['wx_index']  = '';

        if($ret_info['checkIsUse']){
            return $this->output_succ(["data"=>$ret_info]);
        }


        $pdfToImg = $this->t_resource_file->get_file_link($file_id);
        $store=new \App\FileStore\file_store_tea();
        $auth=$store->get_auth();
        $ret_info['wx_index'] = $auth->privateDownloadUrl("http://teacher-doc.leo1v1.com/".$pdfToImg);
        return $this->output_succ(["data"=>$ret_info]);
    }

    public function do_james(){

        $auth = new \Qiniu\Auth(
            \App\Helper\Config::get_qiniu_access_key(),
            \App\Helper\Config::get_qiniu_secret_key()
        );
        // $store=new \App\FileStore\file_store_tea();
        // $auth=$store->get_auth();
        $file_link = $this->get_in_str_val('f');

        // $a =  $this->get_pdf_download_url($file_link);

        // dd($a);
        $config=\App\Helper\Config::get_config("qiniu");
        $bucket_info=$config["private_url"]['url'];

        $pdf_file_path = $auth->privateDownloadUrl($bucket_info.$file_link );

        dd($pdf_file_path);


        $list = $this->t_resource_file->getList();

        foreach($list as $item){
            $file_poster = explode(',', $item['filelinks']);
            $this->t_resource_file->field_update_list($item['file_id'], [
                "change_status"=>1,
                "file_poster" => $file_poster[0]
            ]);
        }
    }



    public function getWxName(){
        $openid = $this->get_in_str_val('openid');
        $userInfo = UserManage::getUserInfo($openid);

        dd($userInfo);

        //第一步，获取CODE

        WeChatOAuth::getCode('http://wx-parent-web.leo1v1.com/test_james/returnName', 1, 'snsapi_base');

    }

    public function returnName(){
        //此时页面跳转到了http://www.lanecn.com/index.php，code和state在GET参数中。

        $code = $_GET['code'];

        //第二步，获取access_token网页版

        $openId = WeChatOAuth::getAccessTokenAndOpenId($code);

        //第三步，获取用户信息

        $userInfo = UserManage::getUserInfo($openId['openid']);
    }

    public function t_ss(){
        $file_id = $this->get_in_int_val('f',-1);
        $list = $this->t_resource_file->get_list_tmp($file_id);

        foreach($list as $v){
            $linkArr = explode(',', $v['filelinks']);
            $filePoster = $linkArr[0];

            $this->t_resource_file->field_update_list($v['file_id'], [
                "file_poster" => $filePoster
            ]);
        }

        return ;
    }


    public function check_function (){

        // $oneMinuteStart = strtotime($this->get_in_str_val('s'));
        $oneMinuteStart = 1515490140;

        $oneMinuteEnd   = $oneMinuteStart+120;
        $lessonEndList  = $this->t_lesson_info_b3->getLessonEndList($oneMinuteStart,$oneMinuteEnd);

        dd($lessonEndList);


        dd(number_format(1.5000,2));
        exit;
        $limit_time = strtotime(date('Y-m-1'));
        $six_time   = $limit_time + 5*86400;

        dd($six_time);
        $now = time();
        $oneMinuteStart = $now;
        $oneMinuteEnd   = $oneMinuteStart+60;
        $lessonEndList  = $this->t_lesson_info_b3->getLessonEndList($oneMinuteStart,$oneMinuteEnd);
        dd($lessonEndList);
        //ceshi
    }


    public function getSellerNum(){
        $s = $this->get_in_str_val('s');
        $ret = $this->t_admin_group_name->getGroupSellerNum(1,$s);
        dd($ret);
    }

    public function checkSellerNum(){
        $s = $this->get_in_str_val('s');
        $g = $this->get_in_str_val('g');
        $ret = $this->t_admin_group_name->get_group_seller_num($s,$g);
        dd($ret);
    }

    public function doTest(){
        $unbound_list = $this->t_teacher_info->get_unbound_teacher_list();
        dd($unbound_list);
        $onlineTime = strtotime('2018-01-17');
        dd($onlineTime);
        $pdf_url = $this->get_in_str_val('p');
        $store=new \App\FileStore\file_store_tea();
        $auth=$store->get_auth();
        $pdf_file_path = $auth->privateDownloadUrl("http://teacher-doc.leo1v1.com/".$pdf_url);
        dd($pdf_file_path);
    }

    public function uploadTo(){
        $zip_new_resource = $this->get_in_str_val('l');
        $file_link =  \App\Helper\Utils::qiniu_upload_private($zip_new_resource);
        $auth = new \Qiniu\Auth(
            \App\Helper\Config::get_qiniu_access_key(),
            \App\Helper\Config::get_qiniu_secret_key()
        );


        $config=\App\Helper\Config::get_config("qiniu");
        $bucket_info=$config["private_url"]['url'];

        $pdf_file_path = $auth->privateDownloadUrl($bucket_info.$file_link );

        dd($pdf_file_path);

    }


    public function getTeaUploadPPTLink(){
        $url = "http://p.admin.leo1v1.com/common_new/getTeaUploadPPTLink";
        $post_data = [];
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);
        $ret_arr = json_decode($output,true);
        return $ret_arr;
    }

    public function updateLessonUUid($lessonid,$uuid){
        $url = "http://admin.leo1v1.com/common_new/updateLessonUUid";
        $post_data = [
            "lessonid" => $lessonid,
            "uuid"     => $uuid
        ];
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $output = curl_exec($ch);
        curl_close($ch);
        $ret_arr = json_decode($output,true);
        return $ret_arr;
    }

    public function jiang(){
        $lessonid = $this->get_in_int_val('l');
        $subject = $this->get_in_int_val('s');
        $grade = $this->get_in_int_val('g');
        $resource_id_arr = $this->t_resource->getResourceId($subject,$grade);

        $hasResourceId = $this->t_lesson_info_b3->getResourceId($lessonid);
        echo $hasResourceId;
        dd($resource_id_arr);

    }

    public function test_job(){
        $add_num = 97;
        $noticeIndex = $add_num%97;
        dd($noticeIndex);
        $type = '';
        $qr_code_url = "http://www.leo1v1.com/market-invite/index.html?p_phone=111&type=2";
        $a = new \App\Jobs\marketActivityPoster('','',$qr_code_url,'','',$type);
        $a->handle();
        // dispatch( new \App\Jobs\marketActivityPoster('','',$qr_code_url,'',''));
    }


    # 测试Redis 存储功能
    public function getTea(){
        $key = "thinking_test1_hduhsushshhshsshhsh";
        $val = 'value1';
        $flag = Redis::set($key,$val);

        dd($flag);
    }

    public function getKey(){
        $key = $this->get_in_str_val('k');
        $ret_arr = Redis::get($key);
        dd($ret_arr);
    }

    public function getKeys(){
        $key = $this->get_in_str_val('k');
        $ret_arr = Redis::keys($key);
        dd($ret_arr);
    }

    public function checkTime(){

        $arrp = ['PDf'];
        if(strtolower($arrp[0]) == 'pdf'){
            dd(1);
        }
        dd($arrp[0]);
    }

    # 记录点击次数
    // $key = "thinking_openid_".@$user_info['openid']."_test";
    // $key_num = 'thinking_record_test';
    // Redis::INCR($key_num);
    // Redis::set($key,1);

    public function updateZipFile(){
        $account_role = $this->get_account_role();
        dd($account_role);

        $ip = getenv('REMOTE_ADDR');
        if($ip == '127.0.0.1'){
            echo 11;
        }
        // echo $ip;
        //微演示ip 47.104.104.138

    }


    # ppt 配置文档更新时发送通知提示
    /*
     * @ 微演示ip 47.104.104.138
     * @ 返回状态码 1:正确的IP 0:错误的IP
     */
    public function getUpdateState_tmp(){
        $ip = getenv('REMOTE_ADDR');
        // 记录日志
        $this->t_user_log->row_insert([
            "add_time" => time(),
            "msg"      => $ip,
            "user_log_type" => E\Euser_log_type::V_6
        ]);
        \App\Helper\Utils::logger("ppt_to_h5_log: $ip");
        if($ip == '101.81.227.245'){
            $fileUrl = "http://leo1v1.whytouch.com/ppt.rar";

            $saveH5FilePath = "/tmp/pptToH5Zip.rar";
            $unzipFilePath  = "/tmp/pptToH5File/"; // 解压后的文件夹

            $data=@file_get_contents($fileUrl);
            file_put_contents($saveH5FilePath, $data);
            $unzipShell = "rar x $saveH5FilePath $unzipFilePath";
            shell_exec($unzipShell);

            // canvg.js 替换其中的代码
            $cmd_sed = "sed -i 's/module.exports = /\/\/module.exports =/' ".$unzipFilePath.'canvg.js';
            shell_exec($cmd_sed);

            // 对修改后的文件打包
            $cmd_zip = "cd $unzipFilePath; zip -r /tmp/pptfile_new.zip ./*";
            shell_exec($cmd_zip);

            // 上传文件至远程
            $cmd_upload = 'sshpass -p yb142857 scp /tmp/pptfile_new.zip ybai@47.104.21.42:~/';
            shell_exec($cmd_upload);

            // 压缩远程老文件
            $cmd_exce_zip = "sshpass -p yb142857 ssh -o StrictHostKeyChecking=no ybai@47.104.21.42 'cd /var/www/admin.yb1v1.com/public/pptfiles/; zip -r ~/myppt_old.zip ./*; unzip -o -d /tmp/pptfiles/ ~/pptfile_new.zip; rm -f ~/pptfile_new.zip '";
            shell_exec($cmd_exce_zip);
            return 1;
        }else{
            return 0;
        }
    }



    # 获取调时间选项
    # 做冲突检测 : 如有连续的时间也不可以
    #
    #
    public function getChangeTimeList(){
        $lessonid  = $this->get_in_int_val('lessonid');
        $userid    = $this->t_lesson_info_b3->get_userid($lessonid);
        $teacherid = $this->t_lesson_info_b3->get_teacherid($lessonid);
        $limitTimeStart = strtotime(date('Y-m-d'));
        $limitTimeEnd   = $limitTimeStart+86400;
        // $lessonTimeList = $this->t_lesson_info_b3->getLessonTimeList($userid,$teacherid,$limitTimeStart,$limitTimeEnd);
        $lessonTimeList = [
            [
                "lesson_start" => "1519862400",//8
                "lesson_end" => "1519864200"//8.30
            ],
            [
                "lesson_start" => "1519864500",//8.35
                "lesson_end" => "1519866000"//9.0
            ],
            // [
            //     "lesson_start" => "1519870800",
            //     "lesson_end" => "1519873200"
            // ]
        ];

        $lesson_start   = $this->t_lesson_info_b3->get_lesson_start($lessonid);
        $lesson_end     = $this->t_lesson_info_b3->get_lesson_end($lessonid);
        // $lessonDuration = $lesson_end-$lesson_start;
        $lessonDuration = 40*60;

        $date_list = [];
        $total_list = [];

        # 列出所有时间段
        $timeNum = (24-6)*2; //每日时间的可选择范围开始时间为6:00，结束时间为24:00; 每半个小时一个节点
        $six = strtotime(date('Y-m-d 6:0:0'));
        $twentyFour = strtotime(date('Y-m-d 24:00:00'));
        $list = [];
        $lessonDuration = 40*60;//测试
        for($i=0;$i<=$timeNum;$i++){
            $list['lesson_start'] = date('Y-m-d H:i:s',$six+$i*30*60);
            $list['lesson_end']   = date('Y-m-d H:i:s',$six+$i*30*60+$lessonDuration);
            $total_list[] = $list;
        }

        # 检测并剔除冲突时间
        foreach($lessonTimeList as $i=>&$item){
            foreach($total_list as $ii=>$val){
                if(($item['lesson_start']>=strtotime($val['lesson_start']) && $item['lesson_start']<=strtotime($val['lesson_end'])) || ($item['lesson_end']<strtotime($val['lesson_end'])&&$item['lesson_end']>strtotime($val['lesson_start']))){
                    unset($total_list[$ii]);
                }
            }
        }

        dd($total_list);
    }

    # 获取可选日期
    public function getAvailableDate(){
        $time      = time();
        $lessonid  = $this->get_in_int_val('lessonid');
        $userid    = $this->t_lesson_info_b3->get_userid($lessonid);
        $teacherid = $this->t_lesson_info_b3->get_teacherid($lessonid);

        # 获取可选日期
        $lesson_start   = $this->t_lesson_info_b3->get_lesson_start($lessonid);
        $lesson_end     = $this->t_lesson_info_b3->get_lesson_end($lessonid);
        $lessonDuration = $lesson_end-$lesson_start;

        $today = strtotime(date('Y-m-d'));
        $dateNum = 2;// 检查获取20天以内的课时[待定]

        $ret = [];
        for($i=0;$i<=$dateNum;$i++){
            // 检查该天的可选性
            $limitTimeStart = $today+$i*86400;
            $ret[][$limitTimeStart] = $this->getCheckTime($lessonid,$userid,$teacherid,$limitTimeStart,$lessonDuration);
        }

        return $this->output_succ(['data'=>$ret]);
    }


    # 检查每天的课程有无空闲时间
    public function getCheckTime($lessonid,$userid,$teacherid,$limitTimeStart,$lessonDuration){
        $limitTimeEnd   = $limitTimeStart+86400;
        $lessonTimeList = $this->t_lesson_info_b3->getLessonTimeList($userid,$teacherid,$limitTimeStart,$limitTimeEnd);


        $date_list  = [];
        $total_list = [];

        # 列出所有时间段
        $timeNum    = (24-6)*2; //每日时间的可选择范围开始时间为6:00，结束时间为24:00; 每半个小时一个节点
        $six        = strtotime(date('Y-m-d 6:0:0',$limitTimeStart));
        $twentyFour = strtotime(date('Y-m-d 24:00:00',$limitTimeStart));
        $list = [];
        $lessonDuration = 40*60;//测试
        for($i=0;$i<=$timeNum;$i++){
            $list['lesson_start'] = date('Y-m-d H:i:s',$six+$i*30*60);
            $list['lesson_end']   = date('Y-m-d H:i:s',$six+$i*30*60+$lessonDuration);
            $total_list[] = $list;
        }

        # 检测并剔除冲突时间
        foreach($lessonTimeList as $i=>&$item){
            foreach($total_list as $ii=>$val){
                if(($item['lesson_start']>=strtotime($val['lesson_start']) && $item['lesson_start']<=strtotime($val['lesson_end'])) || ($item['lesson_end']<strtotime($val['lesson_end'])&&$item['lesson_end']>strtotime($val['lesson_start']))){
                    unset($total_list[$ii]);
                }
            }
        }

        return $total_list;
    }

    # 调课功能
    # 只有申请拒绝后，可继续申请
    # 只可以申请24小时后的课程
    public function changeLessonTime(){
        $lessonid = $this->get_in_int_val('lessonid');
        $parentid = $this->get_parentid();
        $lesson_time_str = $this->get_in_str_val('lesson_time_str');
        $lesson_start    = $this->t_lesson_info->get_lesson_start($lessonid);
        $lesson_end      = $this->t_lesson_info_b2->get_lesson_end($lessonid);
        $subject         = $this->t_lesson_info_b2->get_subject($lessonid);
        $original_lesson_time = $lesson_start.','.$lesson_end;
        if($lesson_start-time()<86400){
            return $this->output_err('只可以申请24小时以后的课程!');
        }
        $checkHad = $this->t_lesson_time_modify->checkHasExist($lessonid);
        $is_modify_time_flag = $this->t_lesson_time_modify->get_is_modify_time_flag($lessonid);
        if($is_modify_time_flag == 2 || $checkHad == 0){
            if($checkHad == 0){
                $this->t_lesson_time_modify->row_insert([
                    "lessonid" => $lessonid,
                    "parentid" => $parentid,
                    "parent_deal_time"     => time(),
                    "parent_modify_time"   => $lesson_time_str,
                    "original_lesson_time" => $original_lesson_time,
                    "backstage_type"       => 1
                ]);
            }

            if($is_modify_time_flag == 2){
                $this->t_lesson_time_modify->field_update_list($lessonid,[
                    "parentid" => $parentid,
                    "parent_deal_time"     => time(),
                    "parent_modify_time"   => $lesson_time_str,
                    "original_lesson_time" => $original_lesson_time,
                    "is_modify_time_flag"  => 0
                ]);
            }


            # 给老师发送微信推送
            $stu_name           = $this->t_student_info->get_nick($userid);
            $subject_str        = E\Esubject::get_desc($subject);
            $teacher_wx_openid  = $this->t_teacher_info->get_wx_openid_by_lessonid($lessonid);
            $lesson_old_date    = date('Y年m月d日 H:i',$lesson_start).'~'.date('H:i',$lesson_end);
            $template_id_teacher = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";
            $teacher_url = ''; //待定
            $data = [
                'first'    => "$stu_name 同学的常规课调课，请尽快完成确认",
                'keyword1' => "调课申请",
                'keyword2' => "\n 学生姓名: $stu_name \n科目: $subject_str \n原上课时间: $lesson_old_date",
                'keyword3' => date('Y-m-d H:i:s')
            ];

            \App\Helper\Utils::send_teacher_msg_for_wx($teacher_wx_openid,$template_id_teacher, $data,$teacher_url);
            return $this->output_succ();
        }else{
            if($checkHad == 1 && $is_modify_time_flag==0){
                $error = '本课程待老师确认';
            }elseif($is_modify_time_flag == 1){
                $error = '本节课时间调整已完成,不可重新申请!';
            }
            return $this->output_err($error);
        }
    }

    # 课程请假接口
    #　只可请假24H之后的课程

    public function leaveLesson(){
        $lessonid = $this->get_in_int_val('lessonid');
        $parentid = $this->get_parentid();
        $lesson_start = $this->t_lesson_info_b3->get_lesson_start($lessonid);
        $checkHad = $this->t_leave_lesson_log->getCheckFlag($lessonid);
        if($lesson_start-time()<86400){
            return $this->output_err('只可以请假24小时以后的课程!');
        }
        if($checkHad){
            return $this->output_err('本课程请假已完成!');
        }else{
            $this->t_lesson_info_b3->field_update_list($lessonid, [
                "lesson_del_flag" => 1,
                "confirm_flag"    => 2,
                "lesson_cancel_reason_type" => E\Elesson_cancel_reason_type::V_11,
                "lesson_cancel_time_type"   => 2,
                "confirm_reason"  => '家长微信端请假'
            ]);
            $this->t_leave_lesson_log->row_insert([
                "lessonid" => $lessonid,
                "parentid" => $parentid,
                "leave_time" => time()
            ]);
            return $this->output_succ();
        }
    }

    public function get_parentid(){
        $weekArr = ["日","一","二","三","四","五","六"];
        dd($weekArr[date('w')]);
        return 111;
    }


    # 获取老师调课列表
    public function getChangeLessonList(){
        $lessonid  = $this->get_in_int_val('lessonid');
        $teacherid = $this->t_lesson_info_b3->get_teacherid($lessonid);
        $weekArr = ["日","一","二","三","四","五","六"];

        $ret_info  = $this->t_lesson_time_modify->getChangeTimeInfo($teacherid);

        foreach($ret_info as &$item){
            $item['subject_str']   = E\Esubject::get_desc($item['subject']);
            $item['teacher_name']  = $this->cache_get_teacher_nick($item['teacherid']);
            $item['student_name']  = $this->cache_get_student_nick($item['studentid']);
            $item['modify_status'] = E\Eis_modify_time_flag::get_desc($item['is_modify_time_flag']);
            $oldLessonTime = explode($item['original_time'], ',');
            // 格式:2018年2月4日（周日） 13:00-15:00
            $item['old_lesson_time_str'] = date('Y年m月d日',$oldLessonTime['0'])." (周".$weekArr[date('w',$oldLessonTime['0'])].") ".date('H:i',$oldLessonTime[0])." ~ ".date('H:i',$oldLessonTime[1]);
            if($item['is_modify_time_flag'] == 1){ // 调课成功
                $item['now_lesson_time_str'] = date('Y年m月d日',$item['lesson_start'])." (周".$weekArr[date('w',$item['lesson_end'])].") ".date('H:i',$itme['lesson_start'])." ~ ".date('H:i',$item['lesson_end']);
            }else{
                $item['now_lesson_time_str'] = '';
            }

        }


        dd($ret_info);

        return $this->output_succ(['data'=>$ret_info]);
    }


    # 老师确认时间
    public function confirmChangeTime(){
        $lessonid = $this->get_in_int_val('lessonid');
        $lesson_time_str  = $this->get_in_str_val('lesson_time_str');
        $lesson_time_arr  = json_decode($lesson_time_str, true);
        $lesson_info      = $this->t_lesson_info_b3->getLessonInfo($lessonid);
        $original_time    = $lesson_info['lesson_start'].','.$lesson_info['lesson_end'];

        $this->t_lesson_time_modify->field_update_list($lessonid, [
            "is_modify_time_flag" => 1,
            "original_time"       => $original_time,
            "teacher_deal_time"   => time(),
            "teacher_modify_time" => $lesson_time_str
        ]);

        $this->t_lesson_info_b3->field_update_list($lessonid, [
            "lesson_start" => $lesson_time_arr[0],
            "lesson_end"   => $lesson_time_arr[1],
        ]);


        # 向家长发送微信推送
        # 向助教发送微信推送
        /*
          Wch1WZWbJvIckNJ8kA9r7v72nZeXlHM2cGFNLevfAQI
          {{first.DATA}}
          课程名称：{{keyword1.DATA}}
          课程时间：{{keyword2.DATA}}
          学生姓名：{{keyword3.DATA}}
          {{remark.DATA}}
         */

        $subject_str = E\Esubject::get_desc($lesson_info['subject']);
        $lesson_old_time_str = date('m月d日 H:i',$lesson_old_start)." ~ ".date('H:i',$lesson_old_end);
        $lesson_new_time_str = date('m月d日 H:i',$lesson_time_arr[0])." ~ ".date('H:i',$lesson_time_arr[1]);
        $stu_name = $this->cache_get_student_nick($lesson_info['userid']);
        $tea_name = $this->cache_get_student_nick($lesson_info['teacherid']);
        $tmplate_id_parent = "Wch1WZWbJvIckNJ8kA9r7v72nZeXlHM2cGFNLevfAQI";
        $parent_openid = $this->t_parent_info->getOpenidByLessonid($lessonid);
        $assistantid   = $this->t_lesson_info->get_assistantid($lessonid);
        $ass_openid    = $this->t_manager_info->getOpenidByAssId($assistantid);
        $url_parent    = "";

        $data_parent = [
            "first"     => "",
            "keyword1"  => "$subject_str",
            "keyword2"  => "\n 原上课时间: $lesson_old_time_str \n 修改后的时间: $lesson_new_time_str ",
            "keyword3"  => "$stu_name",
            "remark"    => "请注意调整后的时间,感谢家长的支持!"
        ];

        $data_ass = [
            "first"     => "",
            "keyword1"  => "$subject_str",
            "keyword2"  => "\n 原上课时间: $lesson_old_time_str \n 修改后的时间: $lesson_new_time_str ",
            "keyword3"  => "$stu_name",
            "remark"    => "请注意调整您的时间安排!"
        ];

        $wx=new \App\Helper\Wx();
        $wx->send_template_msg($parent_openid,$template_id_parent,$data_stu ,$url_parent);
        $wx->send_template_msg($ass_openid,$template_id_parent,$data_ass ,$url_parent);
        return $this->output_succ();
    }

    # 老师拒绝调课时间
    public function refuseChangeTime(){
        $lessonid = $this->get_in_int_val('lessonid');
        $this->t_lesson_time_modify->field_update_list($lessonid, [
            "is_modify_time_flag" => 2,
            "teacher_deal_time"   => time(),
        ]);

        // 向老师发送微信推送
        /*
          rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
          {{first.DATA}}
          待办主题：{{keyword1.DATA}}
          待办内容：{{keyword2.DATA}}
          日期：{{keyword3.DATA}}
          {{remark.DATA}}
        */

        $lesson_info = $this->t_lesson_info_b3->getLessonInfo($lessonid);
        $subject_str = E\Esubject::get_desc($lesson_info['subject']);
        $lesson_old_time_str = date('m月d日 H:i',$lesson_info['lesson_start']);
        $stu_name = $this->cache_get_student_nick($lesson_info['userid']);
        $tea_name = $this->cache_get_student_nick($lesson_info['teacherid']);

        $data_tea = [
            "first"     => "$stu_name 同学课程的调课申请被拒绝",
            "keyword1"  => "调课申请",
            "keyword2"  => "\n 学生姓名: $stu_name \n 科目: $subject_str \n 原上课时间: $lesson_old_time_str  \n 原因: 由于此时间段学生时间不方便，故调课申请未成功,请及时联系助教，协助处理。"

        ];



        // 向家长发送微信推送
        /*
          9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU
          {{first.DATA}}
          待办主题：{{keyword1.DATA}}
          待办内容：{{keyword2.DATA}}
          日期：{{keyword3.DATA}}
          {{remark.DATA}}
         */

        $tmplate_id_parent = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";
        $parent_openid = $this->t_parent_info->getOpenidByLessonid($lessonid);
        $assistantid   = $this->t_lesson_info->get_assistantid($lessonid);
        $ass_openid    = $this->t_manager_info->getOpenidByAssId($assistantid);
        $url_parent = "";
        $data_stu = [
            "first"     => "调课申请被拒绝",
            "keyword1"  => "调换 $lesson_old_time_str 上课时间被拒绝",
            "keyword2"  => "由于此时间段老师时间不方便,故调课申请未成功",
            "keyword3"  => date('Y-m-d H:i:s'),
            "remark"    => "请耐心等待助教老师进行沟通"
        ];

        // 向助教老师发送推送
        $data_ass = [
            "first"     => "您的学生 $stu_name 的家长申请修改 $lesson_old_time_str 上课时间被 $tea_name 老师拒绝",
            "keyword1"  => "老师拒绝调课申请",
            "keyword2"  => "原上课时间: $lesson_old_time_str",
            "keyword3"  => date("Y-m-d H:i:s"),
            "remark"    => "请尽快联系家长和老师进行处理"
        ];

        $wx=new \App\Helper\Wx();
        $wx->send_template_msg($parent_openid,$template_id_parent,$data_stu ,$url_parent);
        $wx->send_template_msg($ass_openid,$template_id_parent,$data_ass ,$url_parent);

        return $this->output_succ();
    }




}
