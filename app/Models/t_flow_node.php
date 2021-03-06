<?php
namespace App\Models;
use \App\Enums as E;

/**
 * @property t_flow  $t_flow
 */

class t_flow_node extends \App\Models\Zgen\z_t_flow_node
{
    public function __construct()
    {
        parent::__construct();
    }


    public function add_node($node_type,$flowid,$adminid, $flow_check_flag=0, $check_time=0 , $check_msg="", $next_nodeid=0, $auto_pass_flag=0 )  {
        $this->row_insert([
            'node_type'=>$node_type,
            'flowid'=>$flowid,
            'adminid' => $adminid,
            'add_time'=>time(NULL),
            'flow_check_flag'=>$flow_check_flag,
            'check_time'=>$check_time,
            'check_msg'=>$check_msg,
            'next_nodeid'=>$next_nodeid,
        ]);
        $retid= $this->get_last_insertid();

        $flow_info  = $this->t_flow->field_get_list($flowid,"*");
        $flow_type= $flow_info["flow_type"];
        $flow_class = \App\Flow\flow::get_flow_class($flow_type);


        $task= new \App\Console\Tasks\TaskController();
        $task->cache_set_item_account_nick($flow_info, "post_adminid","post_admin_nick");
        $msg="申请人:". $flow_info["post_admin_nick"] . "-". $flow_class::get_line_data( $flow_info["from_key_int"] ,$flow_info["from_key_str"],  $flow_info["from_key2_int"]);
        \App\Helper\Utils::logger(" XXSEND WX todo :next_adminid=$adminid");
        if (!$auto_pass_flag ) {
            if ($node_type== -2) { //抄送
                $this->task->t_manager_info->send_wx_todo_msg_by_adminid($adminid,"审批系统","有新的抄送:".E\Eflow_type::get_desc($flow_type),$msg,"/self_manage/flow_list");
            }else{
                $this->task->t_manager_info->send_wx_todo_msg_by_adminid($adminid,"审批系统","有新的审批:".E\Eflow_type::get_desc($flow_type),$msg,"/self_manage/flow_list");
            }
        }else{
            $this->t_manager_info->send_wx_todo_msg_by_adminid($adminid,"审批系统","有新的审批[自动通过]:".E\Eflow_type::get_desc($flow_type),$msg,"/self_manage/flow_list");
        }

        return  $retid;
    }

    public function set_check_info( $nodeid, $flow_check_flag, $next_nodeid , $check_msg="" ) {

        $this->field_update_list($nodeid,[
            "flow_check_flag"  => $flow_check_flag ,
            "check_time"  => time(NULL),
            "next_nodeid"  => $next_nodeid,
            "check_msg"  => $check_msg,
        ]);


    }
    public function del_by_flowid($flowid) {
        $sql=$this->gen_sql_new(
            "delete from %s where flowid=%s",
            self::DB_TABLE_NAME,
            $flowid
        );
        return $this->main_update($sql);

    }
    public function get_count_by_flowid($flowid) {
        $sql=$this->gen_sql_new(
            " select count(*) from %s where flowid=%s",
            self::DB_TABLE_NAME,
            $flowid
        );
        return $this->main_get_value($sql);
    }

    public function get_node_list($flowid, $order_str="desc") {
        $sql= $this->gen_sql_new("select * from %s where flowid=%u order by add_time $order_str ",
                                 self::DB_TABLE_NAME,
                                 $flowid);
        return $this->main_get_list_as_page($sql);
    }

    public function get_list($page_info,$page_type,$start_time,$end_time ,$adminid, $post_adminid, $flow_type  ,$flow_check_flag, $node_type=-1 ) {
        $where_arr=[];
        $order_by_str="flow_status asc,  add_time desc";
        if ( $page_type==0 ){ //我申请
            $where_arr[]=["post_adminid=%u", $adminid, -1 ] ;
            //$this->where_arr_add_time_range($where_arr,"post_time",$start_time,$end_time);
        }else {
            $where_arr[]=["n.adminid=%u", $adminid, -1 ] ;
            if ($page_type==1) {  //待审批
                $flow_check_flag=[ 0 ];
            }else if ( $page_type==2 ){ //已审批
                $flow_check_flag=[ 1,2,3,4,5 ];
            }else if ( $page_type==3 ){ //抄送我
                $node_type=-2;
            }else if ( $page_type==-1 ){ //抄送我
            }
            $this->where_arr_add_time_range($where_arr,"add_time",$start_time,$end_time);
            $this->where_arr_add_int_or_idlist($where_arr, "flow_check_flag", $flow_check_flag);
        }
        $this->where_arr_add_int_or_idlist($where_arr, "flow_type", $flow_type);


        $sql = $this->gen_sql_new(
            "select f.flowid ,n.nodeid,n.node_type,n.add_time, n.flow_check_flag, n.check_msg,"
            ." n.check_time, n.adminid, f.flow_status, f.post_adminid,f.post_time,f.post_msg,"
            ." f.flow_type ,f.from_key_int, f.from_key_str, f.from_key2_int "
            ." from %s f "
            . "left join %s n on  f.flowid=n.flowid  "
            ." where  %s  "
            ." order  by $order_by_str "
            ,t_flow::DB_TABLE_NAME
            ,self::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_info);
    }

}
