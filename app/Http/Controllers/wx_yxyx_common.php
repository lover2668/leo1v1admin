<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;
use Illuminate\Support\Facades\Redis ;
use Illuminate\Support\Facades\Session ;

class wx_yxyx_common extends Controller
{
    var $check_login_flag=false;

    public function wx_jump_page(){
        $code       = $this->get_in_str_val("code");
        $wx_config  = \App\Helper\Config::get_config("yxyx_wx");
        $wx         = new \App\Helper\Wx( $wx_config["appid"] , $wx_config["appsecret"] );
        $token_info = $wx->get_token_from_code($code);
        $openid     = @$token_info["openid"];
        if(!$openid) {
            dd("请关闭 重进");
            exit;
        }
        global $_SERVER;
        session(["wx_yxyx_openid" => $openid]);
        \App\Helper\Utils::logger("HOST:".$_SERVER["HTTP_HOST"] );
        \App\Helper\Utils::logger("new_openid:$openid");
        \App\Helper\Utils::logger("sessionid:".session_id());

        $goto_url     = urldecode(hex2bin($this->get_in_str_val("goto_url")));
        $goto_url_arr = preg_split("/\//", $goto_url);
        $action       = @$goto_url_arr[2];

        //$web_html_url = "http://wx-yxyx-web.leo1v1.com";
        $web_html_url= preg_replace("/wx-yxyx/","wx-yxyx-web", $wx_config["url"] ) ;

        if($action=="bind"){
            $url="$web_html_url/index.html#bind";
        }else{
            \App\Helper\Utils::logger('yxyx_www_openid:'.$openid);
            $agent_info = $this->t_agent->get_agent_info_by_openid($openid);
            $id = $agent_info['id'];
            \App\Helper\Utils::logger('yxyx_www_id:'.$id);
            if($id){
                session([
                    "login_user_role" => 10,
                    "agent_id"    => $id,
                ]);
                $url = "/wx_yxyx_web/$action";
            }else{
                $url = "$web_html_url/index.html#bind?".$action;
            }
        }
        \App\Helper\Utils::logger("JUMP URL:$url");
        header("Location: $url");
        return "succ";
    }

    public function logout(){
        session([
            "agent_id" => 0,
            "login_user_role" =>0,
        ]);
        return $this->output_succ('注销成功!');
    }

    public function send_phone_code() {
        $phone = trim($this->get_in_str_val('phone'));
        $wx_openid  = session("wx_yxyx_openid");
        if(!preg_match("/^1\d{10}$/",$phone)){
            return $this->output_err("请输入规范的手机号!");
        }else{
            $openid = '';
            // $agent_info = $this->t_agent->get_agent_info_by_openid($wx_openid);
            $agent_info = $this->t_agent->get_agent_info_by_phone($phone);
            if(isset($agent_info['wx_openid'])){
                $openid = $agent_info['wx_openid'];
            }
            if($openid){
                $id = $agent_info['id'];
                return $this->output_err("您已绑定过！");
            }
        }

        $msg_num= \App\Helper\Common::redis_set_json_date_add("WX_P_PHONE_$phone",1000000);
        $code = rand(1000,9999);
        $ret=\App\Helper\Utils::sms_common($phone, 10671029,[
            "code" => $code,
            "index" => $msg_num
        ] );

        session([
            'wx_yxyx_code'=>$code,
            'wx_yxyx_phone'=>$phone
        ]);

        return $this->output_succ(["msg_num" =>$msg_num,"code" => $code ]);
    }

    public function bind(){
        $phone      = $this->get_in_str_val("phone");
        $code       = $this->get_in_str_val("code");
        $check_code = session("wx_yxyx_code");
        $wx_openid  = session("wx_yxyx_openid");
        if(!$wx_openid){
            return $this->output_err('微信绑定失败!请重新登录后绑定!"');
        }
        if(!preg_match("/^1\d{10}$/",$phone)){
            return $this->output_err("请输入规范的手机号!");
        }

        if($code==$check_code){
            $agent_info = [];
            $agent_info = $this->t_agent->get_agent_info_by_phone($phone);
            $user_info  = $this->get_wx_user_info($wx_openid);
            $userid     = $agent_info['userid'];
            $headimgurl = $user_info['headimgurl'];
            $nickname   = $user_info['nickname'];
            if(isset($agent_info['id'])){
                $student_info = $this->t_student_info->field_get_list($userid,"*");
                $orderid = 0;
                if($userid){
                    $order_info = $this->t_order_info->get_nomal_order_by_userid($userid,time());
                    if($order_info['orderid']){
                        $orderid = $order_info['orderid'];
                    }
                }
                $userid_new   = $student_info['userid'];
                $type_new     = $student_info['type'];
                $is_test_user = $student_info['is_test_user'];
                if($userid && $type_new ==  E\Estudent_type::V_0 && $is_test_user == 0 && $orderid){//在读非测试
                    $level = E\Eagent_level::V_2 ;
                }else{
                    $level = E\Eagent_level::V_1 ;
                }
                $id = $this->t_agent->update_field_list('t_agent',['wx_openid'=>$wx_openid,'headimgurl'=>$headimgurl,'nickname'=>$nickname,'agent_level'=>$level],'id',$agent_info['id']);
                $id = $agent_info['id'];
            }else{
                $userid = null;
                $userid_new = $this->t_phone_to_user->get_userid_by_phone($phone, E\Erole::V_STUDENT );
                if($userid_new){
                    $userid = $userid_new;
                }
                $id = $this->t_agent->add_agent_bind_new($phone,$headimgurl,$nickname,$wx_openid,$userid,E\Eagent_level::V_1);
            }
            if(!$id){
                return $this->output_err("生成失败！请退出重试！");
            }

            session(["agent_id"=>$id]);
            session(["wx_yxyx_openid" => $wx_openid]);
            session(["login_user_role"=>10]);
            return $this->output_succ("绑定成功!");
        }else{
            return $this->output_err ("验证码错误");
        }
    }

    public function get_wx_user_info($wx_openid){
        $wx_config    = \App\Helper\Config::get_config("yxyx_wx");
        $wx           = new \App\Helper\Wx( $wx_config["appid"] , $wx_config["appsecret"] );
        $access_token = $wx->get_wx_token($wx_config["appid"],$wx_config["appsecret"]);
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$wx_openid."&lang=zh_cn";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($output,true);

        return $data;
    }

    public function agent_add_send_phone_code () {
        $phone = trim($this->get_in_str_val('phone'));
        $code_flag= $this->get_in_int_val("code_flag",0) ;

        if ( strlen($phone) != 11) {
            return $this->output_err("电话号码出错");
        }
        \App\Helper\Utils::logger("sessionid:".session_id());

        $msg_num = \App\Helper\Common::redis_set_json_date_add("STU_PHONE_$phone",1000000);
        $code    = rand(1000,9999);

        \App\Helper\Common::redis_set("JOIN_USER_PHONE_$phone", $code );

        $ret=\App\Helper\Utils::sms_common($phone, 10671029,[
            "code" => $code,
            "index" => $msg_num
        ] );


        $ret_arr= ["msg_num" =>$msg_num  ];
        if ( $code_flag ) {
            $ret_arr["code"] =  $code;
        }

        return $this->output_succ($ret_arr);

    }

    public function agent_add_with_code() {
        $code       = $this->get_in_str_val('code');
        $phone      = $this->get_in_phone();
        $check_code = \App\Helper\Common::redis_get("JOIN_USER_PHONE_$phone" );

        \App\Helper\Utils::logger("check_code:".$check_code." code:".$code." sessionid:".session_id());
        if ($check_code != $code) {
            return $this->output_err("手机验证码不对,请重新输入");
        }

        return $this->agent_add();
    }


    public function agent_add(){
        $p_phone = $this->get_in_str_val('p_phone');
        $phone   = $this->get_in_str_val('phone');
        $type   = $this->get_in_int_val('type');
        $userid = $this->t_phone_to_user->get_userid($phone);
        $student_info = $this->t_student_info->field_get_list($userid,'*');
        $orderid = 0;
        if($userid){
            $order_info = $this->t_order_info->get_nomal_order_by_userid($userid   );
            $orderid = $order_info['orderid'];
        }
        if(!preg_match("/^1\d{10}$/",$phone)){
            return $this->output_err("请输入规范的手机号!");
        }
        if($p_phone == $phone){
            return $this->output_err("不能邀请自己!");
        }
        if(!$type){
            return $this->output_err("请选择报名类型!");
        }
        if($userid
           && $student_info['type'] ==  E\Estudent_type::V_0
           && $student_info['is_test_user'] == 0
           && $orderid
           && $type == E\Eagent_type::V_1
        ){//在读非测试
            return $this->output_err("您已是在读学员!");
        }
        if(!$p_phone){
            return $this->output_err("无推荐人!");
        }
        $phone_str = implode(',',[$phone,$p_phone]);
        $ret_list = $this->t_agent->get_id_by_phone($phone_str);
        foreach($ret_list as $item){
            if($phone == $item['phone']){
                $ret_info = $item;
            }else{
                $ret_info_p = $item;
            }
        }
        $parentid = $ret_info_p['id'];
        $p_wx_openid = $ret_info_p['wx_openid'];
        $p_agent_level = $ret_info_p['agent_level'];
        $pp_wx_openid = $ret_info_p['pp_wx_openid'];
        $pp_agent_level = $ret_info_p['pp_agent_level'];
        if(isset($ret_info['id'])){//已存在,则更新父级和类型
            if($type == $ret_info['type'] or $ret_info['type']==3){
                return $this->output_err("您已被邀请过!");
            }
            $type_new = $ret_info['type']=0?$type:3;
            $this->t_agent->field_update_list($ret_info['id'],[
                //"parentid" => $parentid,
                "type"     => $type_new,
            ]);
            $this->send_agent_p_pp_msg_for_wx($phone,$p_phone,$type,$p_wx_openid,$p_agent_level,$pp_wx_openid,$pp_agent_level);
            return $this->output_succ("邀请成功!");
        }
        if($type == 1){//进例子
            $db_userid = $this->t_phone_to_user->get_userid_by_phone($phone, E\Erole::V_STUDENT );

            if ($db_userid){
                $add_time=$this->t_seller_student_new->get_add_time($userid);
                if ($add_time < time(NULL) -60*86400 ) { //60天前例子
                    $usreid= $this->t_seller_student_new->book_free_lesson_new($nick='',$phone,$grade=0,$origin='优学优享',$subject=0,$has_pad=0);

                    /*
                    // 优学优享例子分配给 王春雷 [442] [开始]
                    $opt_adminid = 442; // 王春雷
                    $opt_account=$this->t_manager_info->get_account($opt_adminid);
                    $self_adminid = 684;
                    $account = '系统';
                    $this->t_seller_student_new->allot_userid_to_cc($opt_adminid, $opt_account, $userid, $self_adminid,$account);
                    // 优学优享例子分配给 王春雷 [结束]
                    */

                    \App\Helper\Utils::logger(" yxyx-lizi1 $userid ");


                    if($userid){
                        $this->t_student_info->field_update_list($userid, [
                            "origin_level" => E\Eorigin_level::V_99
                        ]);

                        $this->t_seller_student_new->field_update_list($userid, [
                            "global_tq_called_flag" =>0 ,
                            "global_seller_student_status" =>0 ,
                        ]);
                    }
                }
            }else{
                $userid = $this->t_seller_student_new->book_free_lesson_new($nick='',$phone,$grade=0,$origin='优学优享',$subject=0,$has_pad=0);

                /*
                // 优学优享例子分配给 王春雷 [442]
                $opt_adminid = 442; // 王春雷
                $opt_account=$this->t_manager_info->get_account($opt_adminid);
                $self_adminid = 684;
                // $account = $this->get_account();
                $account = '系统';
                $this->t_seller_student_new->allot_userid_to_cc($opt_adminid, $opt_account, $userid, $self_adminid,$account);
                */
                \App\Helper\Utils::logger(" yxyx-lizi2 $userid ");

            }
        }


        $userid = null;
        $userid_new = $this->t_phone_to_user->get_userid_by_phone($phone, E\Erole::V_STUDENT );
        if($userid_new){
            $userid = $userid_new;
        }
        $ret = $this->t_agent->add_agent_row($parentid,$phone,$userid,$type);
        if($ret){
            $agent_id=$this->t_agent->get_last_insertid();
            dispatch( new \App\Jobs\agent_reset ($agent_id));
            $this->send_agent_p_pp_msg_for_wx($phone,$p_phone,$type,$p_wx_openid,$p_agent_level,$pp_wx_openid,$pp_agent_level);
            return $this->output_succ("邀请成功!");
        }else{
            return $this->output_err("数据请求异常!");
        }
    }

    public function send_agent_p_pp_msg_for_wx($phone,$p_phone,$type,$p_wx_openid,$p_agent_level,$pp_wx_openid,$pp_agent_level){
        $template_id = '70Yxa7g08OLcP8DQi4m-gSYsd3nFBO94CcJE7Oy6Xnk';
        $url = '';
        if($p_wx_openid){
            if($type == 1){//邀请学员
                $type_str = '邀请学员成功!';
                if($p_agent_level == 1){//黄金
                    $remark = '恭喜您成功邀请的学员'.$phone.'报名参加测评课，如学员成功购课则可获得最高500元的奖励哦。';
                }else{//水晶
                    $remark = '恭喜您成功邀请的学员'.$phone.'报名参加测评课，如学员成功购课则可获得最高1000元的奖励哦。';
                }
            }else{//邀请会员
                $type_str = '邀请会员成功!';
                $remark = '恭喜您成功邀请会员'.$phone;
            }
            $data = [
                'first'    => $type_str,
                'keyword1' => $phone,
                'keyword2' => $phone,
                'keyword3' => date('Y-m-d H:i:s',time()),
                'remark'   => $remark,
            ];
            \App\Helper\Utils::send_agent_msg_for_wx($p_wx_openid,$template_id,$data,$url);
        }
        if($pp_wx_openid){
            if($type == 1){//邀请学员
                $type_str = '邀请学员成功!';
                if($pp_agent_level == 1){//黄金
                    $remark = '恭喜您邀请的会员'.$p_phone."成功邀请了".$phone.'报名参加测评课。';
                }else{//水晶
                    $remark = '恭喜您邀请的会员'.$p_phone."成功邀请了".$phone.'报名参加测评课，如学员成功购课则可获得最高500元的奖励哦。';
                }
            }else{//邀请会员
                $type_str = '邀请会员成功!';
                $remark = '恭喜您邀请的会员'.$p_phone."成功邀请了".$phone;
            }
            $data_p = [
                'first'    => $type_str,
                'keyword1' => $phone,
                'keyword2' => $phone,
                'keyword3' => date('Y-m-d H:i:s',time()),
                'remark'   => $remark,
            ];
            \App\Helper\Utils::send_agent_msg_for_wx($pp_wx_openid,$template_id,$data_p,$url);
        }
    }

    public function get_wx_yxyx_js_config(){
        $ref = $this->get_in_str_val("ref");
        $signature_str = $this->get_signature_str($ref);
        $config = [
            'debug' => 'false',
            'appId' => 'wxb4f28794ec117af0', // 必填，公众号的唯一标识
            'timestamp' => '1501516800', // 必填，生成签名的时间戳(随意值)
            'nonceStr'  => 'leo456', // 必填，生成签名的随机串(随意值)
            'signature' => $signature_str,// 必填，签名
            'jsApiList' => [
                "checkJsApi",
                "chooseImage",
                "previewImage",
                "uploadImage",
                "downloadImage",
                "getLocalImgData",
            ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
        ];
        \App\Helper\Utils::logger('yxyx_sig:'.$signature_str);

        return $this->output_succ($config);
    }

/**
     *老师端微信上传图片
    **/
    public function get_signature_str( $ref, $appid_yxyx= 'wxb4f28794ec117af0', $appscript_yxyx= '4a4bc7c543698b8ac499e5c72c22f242' ){
        $token = $this->get_wx_token_jssdk($appid_yxyx, $appscript_yxyx);
        $key_arr     = "wx_yxyx_jssdk_arr_$appid_yxyx";
        $key_str     = "wx_yxyx_jssdk_str_$appid_yxyx";
        $ret_arr = \App\Helper\Common::redis_get_json($key_arr);
        $now     = time(NULL);
        if (!$ret_arr || !isset($ret_arr["ticket"])  ||  $ret_arr["get_time"]+7000 <  $now ) {
            $jssdk    = $this->get_wx_jsapi_ticket($token);
            $ret_arr  = \App\Helper\Utils::json_decode_as_array($jssdk);
            $ret_arr["get_time"] = time(NULL);
            \App\Helper\Common::redis_set_json($key_arr,$ret_arr );
        }
        $jsapi_ticket = $ret_arr["ticket"];
        // $ref= $ref?$ref:$_SERVER['HTTP_REFERER'];
        $signature = "jsapi_ticket=$jsapi_ticket&noncestr=leo456&timestamp=1501516800";
                   // . "&url=$ref" ;
        \App\Helper\Utils::logger( "signature:$signature" );

        $signature_str = sha1($signature);
        return $signature_str;
    }

    public function get_wx_token_jssdk($appid_yxyx = 'wxb4f28794ec117af0', $appscript_yxyx= '4a4bc7c543698b8ac499e5c72c22f242' ){
        $wx  = new \App\Helper\Wx();
        return $wx->get_wx_token($appid_yxyx,$appscript_yxyx);
    }

    public function get_wx_jsapi_ticket($token){
        $json_jssdk_data=file_get_contents("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=$token&type=jsapi");
        return $json_jssdk_data;
    }

    public function get_all_test_pic(){
        //title,date,用户未读取标志（14天内的消息），十张海报（当天之前的，可跳转）
        $grade     = $this->get_in_int_val('grade',-1);
        $subject   = $this->get_in_int_val('subject',-1);
        $test_type = $this->get_in_int_val('test_type',-1);
        $wx_openid = $this->get_in_str_val('wx_openid', 0);
        $page_info = $this->get_in_page_info();
        $ret_info  = $this->t_yxyx_test_pic_info->get_all_for_wx($grade, $subject, $test_type, $page_info, $wx_openid);
        $start_time = strtotime('-14 days');
        $end_time   = strtotime('tomorrow');
        foreach ($ret_info['list'] as &$item) {
            if (!$item['flag'] && $item['create_time'] < $end_time && $item['create_time'] > $start_time) {
                $item['flag'] = 0;
            } else {
                $item['flag'] = 1;
            }
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
        }

        //随机获取十张海报/不足十张，取所有,取100条以内,时间倒序
        $all_id     = $this->t_yxyx_test_pic_info->get_all_id_poster(0,0,$end_time);
        $count_num  = count($all_id)-1;
        $poster_arr = [];
        $num_arr    = [];
        $loop_num   = 0;
        $max_loop  = $count_num >10?10:$count_num;
        while ( $loop_num < $max_loop) {
            $key = mt_rand(0, $count_num);
            if( !in_array($key, $num_arr)) {
                $num_arr[]    = $key;
                $poster_arr[] = $all_id[$key];
                $loop_num++;
            }
        }
        $ret_info['poster'] = $poster_arr;
        return $this->output_succ(["home_info"=>$ret_info]);
    }

    public function get_one_test_and_other() {
        $id   = $this->get_in_int_val('id',-1);
        $flag = $this->get_in_int_val('flag', 1);
        $wx_openid = $this->get_in_str_val('wx_openid', 0);
        if ($id < 0){
            return $this->output_err('信息有误！');
        }
        $ret_info = $this->t_yxyx_test_pic_info->get_one_info($id);
        if ($ret_info) {
            if (!$flag) {
                $this->t_yxyx_test_pic_visit_info->add_visit_info($id,$wx_openid);//添加到访问记录
            }
            $this->t_yxyx_test_pic_info->add_field_num($id,"visit_num");//添加访问量

            \App\Helper\Utils::unixtime2date_for_item($ret_info,"create_time");
            E\Egrade::set_item_value_str($ret_info,"grade");
            E\Esubject::set_item_value_str($ret_info,"subject");
            E\Etest_type::set_item_value_str($ret_info,"test_type");
            $ret_info['pic_arr'] = explode( '|',$ret_info['pic']);
            unset($ret_info['pic']);
            //获取所有id，随机选取四个(当天之前的14天之内)->改为取１００条，时间倒叙
            // $start_time = strtotime('-15 days');
            $end_time  = strtotime('today');
            $all_id    = $this->t_yxyx_test_pic_info->get_all_id_poster($id, 0, $end_time);
            $count_num = count($all_id)-1;
            $id_arr    = [];
            $num_arr   = [];
            $loop_num  = 0;
            $max_loop  = $count_num >4?4:$count_num;
            while ( $loop_num < $max_loop) {
                $key = mt_rand(0, $count_num);
                if( !in_array($key, $num_arr) ) {
                    $num_arr[] = $key;
                    $id_arr[]  = $all_id[$key]['id'];
                    $loop_num++;
                }
            }
            if ( count($id_arr) ) {
                $id_str = join($id_arr,',');
                $create_time = strtotime('today');
                $ret_info['other'] = $this->t_yxyx_test_pic_info->get_other_info($id_str, $create_time);
            } else {
                $ret_info['other'] = [];
            }
            return $this->output_succ(['list' => $ret_info]);
        } else {
            return $this->output_err("您查看的信息不存在！");
        }

    }

    public function add_share_num(){
        $id = $this->get_in_int_val('id',-1);
        $this->t_yxyx_test_pic_info->add_field_num($id,"share_num");//添加分享次数
    }

    public function get_yxyx_all_new(){
        $ret_info = $this->t_yxyx_new_list->get_all_for_wx();
        foreach ($ret_info as &$item) {
            $content = str_replace(PHP_EOL, '', strip_tags($item['new_content']));
            $item['new_content'] = mb_substr( trim($content),0,30);
        }
        if ($ret_info) {
            return $this->output_succ(["data"=>$ret_info]);
        } else {
            return $this->output_err("信息有误！");
        }
    }

    public function get_yxyx_one_new(){
        $id = $this->get_in_int_val("id", -1);
        $ret_info = $this->t_yxyx_new_list->get_one_new_for_wx($id);
        if ($ret_info) {
            return $this->output_succ(["data"=>$ret_info]);
        } else {
            return $this->output_err("信息有误！");
        }
    }

    public function get_wx_js_config(){

        $ref=$this->get_in_str_val("ref");

        $wx_config  = \App\Helper\Config::get_config("yxyx_wx");

        $signature_str = $this->get_signature_str($ref, $wx_config["appid"] , $wx_config["appsecret"] );
        $config = [
            'debug' => 'false',
            'appId' => $wx_config["appid"], // 必填，公众号的唯一标识
            'timestamp' => '1494474414', // 必填，生成签名的时间戳(随意值)
            'nonceStr'  => 'leo123', // 必填，生成签名的随机串(随意值)
            'signature' => $signature_str,// 必填，签名
            'jsApiList' => [
                "checkJsApi",
                "chooseImage",
                "previewImage",
                "uploadImage",
                "downloadImage",
                "getLocalImgData",
            ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
        ];
        return $this->output_succ($config);
    }

    /**
     *老师端微信上传图片
    **/
    public function get_signature_str( $ref, $appid_tec= 'wxa99d0de03f407627', $appscript_tec= '61bbf741a09300f7f2fd0a861803f920' ){
        $token = $this->get_wx_token_jssdk(  $appid_tec, $appscript_tec);

        $key_arr     = "wx_tec_jssdk_arr_$appid_tec";
        $key_str     = 'wx_tec_jssdk_str_$appid_tec';

        $ret_arr = \App\Helper\Common::redis_get_json($key_arr);
        $now     = time(NULL);

        if (!$ret_arr || !isset($ret_arr["ticket"])  ||  $ret_arr["get_time"]+7000 <  $now ) {

            $jssdk    = $this->get_wx_jsapi_ticket($token);
            $ret_arr  = \App\Helper\Utils::json_decode_as_array($jssdk);
            $ret_arr["get_time"] = time(NULL);
            \App\Helper\Common::redis_set_json($key_arr,$ret_arr );

        }

        $jsapi_ticket = $ret_arr["ticket"];

        if(isset($_SERVER["HTTP_REFERER"])){
            $http_ref = $_SERVER["HTTP_REFERER"];
        }else{
            $http_ref = "";
        }
        $ref= $ref?$ref:$http_ref;
        $signature = "jsapi_ticket=$jsapi_ticket&noncestr=leo123&timestamp=1494474414"
                   . "&url=$ref" ;

        \App\Helper\Utils::logger( "signature:$signature" );

        $signature_str = sha1($signature);
        return $signature_str;
    }

    public function get_wx_token_jssdk($appid_tec= 'wxa99d0de03f407627', $appscript_tec= '61bbf741a09300f7f2fd0a861803f920' ){

        $wx        = new \App\Helper\Wx();
        return $wx->get_wx_token($appid_tec,$appscript_tec);
    }

    public function get_wx_jsapi_ticket($token){
        $json_jssdk_data=file_get_contents("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=$token&type=jsapi ");

        return $json_jssdk_data;
    }




}
