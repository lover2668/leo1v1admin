<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;
use \App\Libs;
use \App\Config as C;
use \App\Enums as E;
use App\Helper\Utils;

class test_sam  extends Controller
{
    use CacheNick;
    use TeaPower;
    
    public function lesson_list()
    {
      
    }
    
    private function get_lesson_quiz_cfg($lesson_quiz_status, $lesson_type)
    {
    }

    private function get_work_url($work_value)
    {
        switch($work_value['work_status']) {
        case 1:
            return $work_value['issue_url'];
        case 2:
            return $work_value['finish_url'];
        case 3:
            return $work_value['check_url'];
        case 4:
            return $work_value['tea_research_url'];
        case 5:
            return $work_value['ass_research_url'];
        default:
            return '';
        }
    }



    public function manager_list()
    {
    }
    public function test(){
        
    }



     public function  tt(){

        //$t = 1507464000;
        $time = time();
        //$time = $t;
        $day_time = strtotime(date("Y-m-d",$time));
        $lesson_end = strtotime(date("Y-m-d",$time)." 19:30:00");
        $lesson_start = $lesson_end+1800;

        $lesson_list = $this->t_lesson_info_b2->get_off_time_lesson_info($lesson_start,$lesson_end);


        $day_time = date("Y-m-d H:i:s",$day_time);
        $lesson_end = date("Y-m-d H:i:s",$lesson_end);
        $lesson_start = date("Y-m-d H:i:s",$lesson_start);

        //t
        $time = 1507464000;
        $date_time = date("Y-m-d",$time);       
        if($date_time == "2017-10-08"){
            //deal 2017-10-08 22:00:00
          //$start_time = 1506787200;//2017/10/1 0:0:0
          //$end_time   = 1507471200;//2017/10/8 22:0:0
          $start_time = 1504195200; //2017/9/1 0:0:0
          $end_time   = 1504879200; //2017/9/8 22:0:0

          $lesson_info = $this->t_lesson_info_b2->get_qz_tea_lesson_info_b2($start_time,$end_time);
          $list=[];
          foreach($lesson_info as $val){
              if($val["lesson_type"]==1100 && $val["train_type"]==5){
                  @$list[$val["uid"]] += 0.8;
              }elseif($val["lesson_type"]==2){
                  @$list[$val["uid"]] += 1.5;
              }else{
                  @$list[$val["uid"]] += $val["lesson_count"]/100;
              }
          }
          $arr = [];
          foreach ($list as $key => $value) {
              $teacher_info = $this->t_manager_info->get_teacher_info_by_adminid($key);
              $teacherid = $teacher_info["teacherid"];
              $realname = $this->t_teacher_info->get_realname($teacherid);
              @$arr[$key]['teacherid'] = $teacherid;
              @$arr[$key]['realname']  = $this->t_teacher_info->get_realname($teacherid);
              @$arr[$key]['lesson_count'] = $value;
              @$arr[$key]['day_num'] = floor($value/10.5);
              @$arr[$key]['attendance_time'] = 1507478400;//2017/10/9 0:0:0
              if($arr[$key]['day_num'] == 0){
                @$arr[$key]['cross_time'] = "";
              }else{
                @$arr[$key]['cross_time'] = "10.9-".date('m-d',1507478400+$arr[$key]['day_num']*86400);
              }
              
          }


          foreach ($arr as $key => $value) {
              $this->t_manager_info->send_wx_todo_msg_by_adminid (
                //$key,
                944,
                "国庆延休统计",
                "延休数据汇总",
                "\n老师:".$value['realname'].
                "\n时间:2017-10-1 0:0:0 ~ 2017-10-8 22:0:0".
                "\n累计上课课时:".$value['lesson_count'].
                "\n延休天数:".$value['day_num'].
                "\n延休日期:".$value['cross_time'],'');
          }

          
          $table = '<table border=1 cellspacing="0" bordercolor="#000000"  style="border-collapse:collapse;"><tr><td colspan="2">全职老师假期累计上课时间及延休安排</td></tr>';
          $table .= '<tr><td>假期名称</td><td><font color="red">国庆节</font></td><td></td><td></td></tr>';
          $table .= "<tr><td>老师姓名</td><td>累计上课时长</td><td>延休天数</td><td>延休日期</td></tr>";
          foreach ($arr as $key => $value) {
              if($value['day_num'] != 0){
                  $table .= '<tr>';
                  $table .= '<td><font color="red">'.$value['realname'].'</font></td>';
                  $table .= '<td><font color="red">'.$value['lesson_count'].'</font></td>';
                  $table .= '<td><font color="red">'.$value['day_num'].'</font></td>';
                  $table .= '<td><font color="red">'.$value['cross_time'].'</font></td>';
                  $table .= '</tr>';
              }

          }
          $table .= "</table>";

          $content = "Dear all：<br>全职老师国庆延休安排情况如下<br/>";
          $content .= "数据见下表<br>";
          $content .= $table;
          $content .= "<br><br><br><div style=\"float:right\"><div>用心教学,打造高品质教学质量</div><div style=\"float:right\">理优监课组</div><div>";
          $email_arr = ["sam@leoedu.com"];
          foreach($email_arr as $email){
             dispatch( new \App\Jobs\SendEmailNew(
                $email,
                "全职老师国庆假期累计上课时间及延休安排",
                $content
             ));
  
         }

          $admin_list = [944];
          dd($arr);

          $name_list ="";
          $num=0;
          $name_list_research="";
          $num_research=0;

        }
       
    }        
}
