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
    public function manager_list()
    {
    }
    public function test(){
        
    }



    public function  tt(){
        $start_time = 1504195200;
        $end_time = 1506787200;
      //存档------------------------------------------------
        $finish_num = $this->t_student_info->get_finish_num($start_time,$end_time);//结课学员数
        $read_num   = $this->t_student_info->get_read_num($start_time,$end_time);//在读学员数量
        $lesson_plan    = $this->t_lesson_info->get_total_lesson($start_time,$end_time); //实际有效课时/排课量
        $lesson_income  = $this->t_lesson_info->get_total_income($start_time,$end_time);//课时有效收入
        $arr['finish_num'] = $finish_num;
        $arr['read_num']   = $read_num;
        //$arr['total_student'] = $lesson_consume['total_student']; //实际有效课时
        $arr['lesson_plan'] = $lesson_plan['total_plan']; //计划排课数量
        $arr['student_arrive'] = $lesson_plan['student_arrive']; //学生有效课程数量
        $arr['lesson_income'] = $lesson_income;
        if($arr['lesson_plan']){
            $arr['student_arrive_per'] = round(100*$arr['student_arrive']/$arr['lesson_plan'],2);
        }else{
            $arr['student_arrive_per'] = 0;
        }

        dd($arr);
    }
}
