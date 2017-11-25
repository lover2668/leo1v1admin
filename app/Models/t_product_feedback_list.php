<?php
namespace App\Models;
use \App\Enums as E;
class t_product_feedback_list extends \App\Models\Zgen\z_t_product_feedback_list
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_product_list($deal_flag, $feedback_adminid, $start_time, $end_time, $page_num, $opt_date_type){
        $where_arr = [
            ["pf.deal_flag=%d",$deal_flag,-1],
            ["pf.feedback_adminid=%d",$feedback_adminid,-1],
        ];

        $this->where_arr_add_time_range($where_arr, $opt_date_type, $start_time, $end_time);

        $sql = $this->gen_sql_new("  select pf.id, pf.deal_flag, pf.feedback_adminid, pf.record_adminid, pf.describe, pf.lesson_url, pf.reason,"
                                  ." pf.solution, pf.remark, pf.deal_flag, pf.create_time, s.nick as stu_nick, s.phone stu_phone, "
                                  ." s.user_agent as stu_agent, t.nick tea_nick, t.phone tea_phone, t.user_agent tea_agent"
                                  ." from %s pf"
                                  ." left join %s s on s.userid=pf.student_id"
                                  ." left join %s t on t.teacherid=pf.teacher_id"
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list_by_page($sql, $page_num, 10);
    }

}