<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;

class testbb extends Controller
{
    use CacheNick;

    var $check_login_flag = false;
    public function get_msg_num() {
        $bt_str=" ";
        $e=new \Exception();
        foreach( $e->getTrace() as &$bt_item ) {
            //$args=json_encode($bt_item["args"]);
            $bt_str.= @$bt_item["class"]. @$bt_item["type"]. @$bt_item["function"]."---".
                @$bt_item["file"].":".@$bt_item["line"].
                "<br/>";
        }
        echo $bt_str;

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

        $ret = $this->t_teacher_lecture_appointment_info->tongji_teacher_appoinment_lecture_info($time);
        dd($ret);

        $item_name = "自检部";
        // dd($item_name);
        dd(substr($item_name,-3));
        // substr($item_name,0,-1)== "部";
        //
        $item_name = "教学管理-1面试转化率模型";
        // dd(substr($item_name,0,13));
        if ( substr($item_name,0,13)== "教学管理-"  ) {
            dd(111);
        }





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

    }

}