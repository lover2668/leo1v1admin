<?php
namespace App\Models;
use \App\Enums as E;
class t_teacher_train_info extends \App\Models\Zgen\z_t_teacher_train_info
{
	public function __construct()
	{
		parent::__construct();
	}

	public function get_list($page_info,$start_time,$end_time){
        $where_arr=[
            "status!=0",
            ["create_time>%d",$start_time,-1],
            ["create_time<%d",$end_time,-1],
        ];
        $sql = $this->gen_sql_new("select * from %s where %s order by create_time desc ",
                              self::DB_TABLE_NAME,
                              $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
	}
}