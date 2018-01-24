<?php
namespace App\Models;
use \App\Enums as E;
class t_personality_poster extends \App\Models\Zgen\z_t_personality_poster
{
	public function __construct()
	{
		parent::__construct();
	}

    public function updateForwardNum($uid){
        $sql = $this->gen_sql_new(" update %s set forwardNum=forwardNum+1 where uid=$uid"
                                  ,self::DB_TABLE_NAME
        );
        $this->main_update($sql);
    }

    public function updatePosterNum($uid){
        $sql = $this->gen_sql_new(" update %s set posterNum=posterNum+1 where uid=$uid"
                                  ,self::DB_TABLE_NAME
        );
        $this->main_update($sql);
    }


    public function checkHas($RoleId){
        $sql = $this->gen_sql_new("  select 1 from %s "
                                  ." where uid=$RoleId"
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_get_value($sql);
    }
}











