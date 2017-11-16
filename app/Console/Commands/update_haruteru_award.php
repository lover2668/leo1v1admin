<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class update_haruteru_award extends Command
{
    protected $award = [300,200,150,100,60];
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_haruteru_award';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新春辉奖奖金';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $task = new \App\Console\Tasks\TaskController();
        $start_time = strtotime(date('Y-m-1', strtotime('-1month')));
        $end_time = strtotime(date('Y-m-1', time()));
        // 小学
        $p_info =$task->t_lesson_info->get_teacher_test_person_num_list( $start_time,$end_time,-1,100,[],2,false);
        $this->get_person($p_info, 100);
        // 初中
        $m_info =$task->t_lesson_info->get_teacher_test_person_num_list( $start_time,$end_time,-1,200,[],2,false);
        $this->get_person($m_info, 200);
        // 高中
        $s_info =$task->t_lesson_info->get_teacher_test_person_num_list( $start_time,$end_time,-1,300,[],2,false);
        $this->get_person($s_info, 300);
    }

    // 处理结果获取春辉奖得奖人
    public function get_person($info, $grade) {
        $person = [];
        $sort_n = $sort_o =[];
        if ($info) {
            foreach($info as $key => $item) {
                $convers = $item['have_order'] / $item['lesson_num'] * 100;
                if ($item['lesson_num'] >= 6 && $convers >= 15) {
                    $sort_n[] = $item['lesson_num'];
                    $sort_o[] = $item['have_order'];
                    $item['convers'] = $convers;
                    $item['teacherid'] = $key;
                    $person[] = $item;
                }
            }
        }
        if ($person) {
            array_multisort($sort_n,SORT_DESC,$sort_o,SORT_DESC,$person );
            // 获取
            $person = array_slice($person,0,5);
            var_dump($person);
            // 添加数据
            $i = 0;
            foreach($person as $key => $item) {
                $comput = array_slice($person,0,$key+1);
                $money = $this->award[$i];
                foreach($comput as $k=>$val) {
                    if ($item['convers'] == $val['convers']) {
                        echo $k; break;
                    }
                }
                $i ++;
                echo 'money '.$money; 

                // $task->t_teacher_money_list->row_insert([
                //     'teacherid' => $key,
                //     'add_time' => time(),
                //     'type' => 7,
                //     'grade' => $grade
                // ]);
            }
        }
        return;
    }
}
