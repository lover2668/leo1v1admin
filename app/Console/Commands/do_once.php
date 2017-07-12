<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class do_once extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:do_once';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    /**
     * task
     *
     * @var \App\Console\Tasks\TaskController
     */

    var $task       ;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->task        = new \App\Console\Tasks\TaskController();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $ss=new \App\Http\Controllers\common_new();
        $ss->upload_from_xls_data("/tmp/a.xlsx");
    }
            /*

        $task        = new \App\Console\Tasks\TaskController();
        $teacher_list=$task->t_teacher_info->get_all_list();
        foreach ($teacher_list as $tea_item  ) {
            $teacherid=$tea_item["teacherid"];
            $arr=$task->t_teacher_freetime_for_week->get_common_lesson_config($teacherid);
            foreach( $arr as $item) {
                $end = $item['end_time'];
                $arr=explode("-",$item['start_time']);
                $start = @$arr[1];
                $lesson_start = strtotime(date("Y-m-d", time(NULL))." $start");
                $lesson_end = strtotime(date("Y-m-d", time(NULL))." $end");
                $diff=($lesson_end-$lesson_start)/60;
                if ($diff<=40) {
                    $lesson_count=100;
                } else if ( $diff <= 60) {
                    $lesson_count=150;
                } else if ( $diff <=90 ) {
                    $lesson_count=200;
                }else{
                    $lesson_count= ceil($diff/40)*100 ;
                }


                echo "insert into  db_weiyi.t_week_regular_course (teacherid,userid,start_time,end_time,lesson_count) values('".$teacherid."','".$item['userid']."','".$item['start_time']."','".$item['end_time']."','".$lesson_count."');\n" ;
            }

            */

        /*
        $task        = new \App\Console\Tasks\TaskController();
        $sql="select userid,grade from db_weiyi.t_student_info ";
        $list=$task->t_student_info->main_get_list($sql);
        foreach ($list as $item) {
            $userid         = $item["userid"];
            $grade = $item["grade"];
            $next_grade= \App\Helper\Utils::get_next_grade($grade);

            echo  "$userid\t$grade $next_grade\n  ";
            $task->t_student_info->field_update_list($userid,[
                "grade" => $next_grade,
            ]);

        }
        */

}
