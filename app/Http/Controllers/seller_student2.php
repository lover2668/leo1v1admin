<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use App\Helper\Utils;

class seller_student2 extends Controller
{
    use CacheNick;
    use TeaPower;

    public function show_order_activity_info() {
        $open_flag   = $this->get_in_int_val('id_open_flag',-1);
        $can_disable_flag   = $this->get_in_int_val('id_can_disable_flag',-1);
        $contract_type_list     = $this->get_in_int_val('id_contract_type',-1);
        $period_flag_list     = $this->get_in_int_val('id_period_flag',-1);
        $page_num        = $this->get_in_page_num();
 
        $ret_list = $this->t_seller_student2->get_list($open_flag,$can_disable_flag,$contract_type_list,$period_flag_list,$page_num);
        
        $gradeArr = E\Egrade::$desc_map;
        if($ret_list['list']){
            foreach( $ret_list['list'] as &$item){
                $item['period_flag_list_str'] = '';
                if($item['period_flag_list']){
                    $periodArr = explode(",",$item['period_flag_list']);
                    foreach($periodArr as $pe){
                        $item['period_flag_list_str'] .= E\Eperiod_flag::get_desc($pe).',';
                    }
                    $item['period_flag_list_str'] = substr($item['period_flag_list_str'],0,-1);
                }

                $item['contract_type_list_str'] = '';
                if($item['contract_type_list']){
                    $conArr = explode(",",$item['contract_type_list']);
                    foreach($conArr as $con){
                        $item['contract_type_list_str'] .= E\Econtract_type::get_desc($con).',';
                    }
                    $item['contract_type_list_str'] = substr($item['contract_type_list_str'],0,-1);
                }


                $item['can_disable_flag_str']   = E\Ecan_disable_flag::get_desc($item['can_disable_flag']);
                $item['open_flag_str']   = E\Eopen_flag::get_desc($item['open_flag']);
                $item['order_activity_discount_type_str']   = E\Eorder_activity_discount_type::get_desc($item['order_activity_discount_type']);
                if($item['grade_list']){
                    if( strpos($item['grade_list'], ",")){
                        $gradeArr = explode(",",$item['grade_list']);
                        $item['grade_list_str'] = '';
                        foreach( $gradeArr as $grade){
                            $item['grade_list_str'] .= E\Egrade::get_desc($grade).',';
                        }
                        $item['grade_list_str'] = substr($item['grade_list_str'],0,-1);

                    }else{
                        $item['grade_list_str'] = E\Egrade::get_desc($item['grade_list']);
                    }
                }else{
                    $item['grade_list_str'] = '未设置';
                }

                if( $item['date_range_start'] && $item['date_range_end']){
                    $item['date_range_time'] = date('Y-m-d',$item["date_range_start"]).' 至 '.date('Y-m-d',$item["date_range_end"]);
                }else{
                    $item['date_range_time'] = "未设置";
                }

                if( $item['lesson_times_min'] && $item['lesson_times_max'] ){
                    $item['lesson_times_range'] = $item['lesson_times_min']."-".$item['lesson_times_max'];
                }else{
                    $item['lesson_times_range'] = "未设置";
                }

                if( $item['user_join_time_start'] && $item['user_join_time_end']){
                    $item['user_join_time_range'] = date('Y-m-d',$item["user_join_time_start"]).' 至 '.date('Y-m-d',$item["user_join_time_end"]);
                }else{
                    $item['user_join_time_range'] = "未设置";
                }

                if( $item['last_test_lesson_start'] && $item['last_test_lesson_end']){
                    $item['last_test_lesson_range'] = date('Y-m-d',$item["last_test_lesson_start"]).' 至 '.date('Y-m-d',$item["last_test_lesson_end"]);
                }else{
                    $item['last_test_lesson_range'] = "未设置";
                }
              
                //优惠列表展示
                $discount_list = $this->discount_list($item['order_activity_discount_type'],$item['discount_json']);
                
                $discount_str = '';
                if(!empty($discount_list)){
                    foreach( $discount_list as $v){
                        $discount_str .= $v.' ; ';
                    }
                }
         
                $item['discount_list'] = $discount_str;
            }
        }
        return $this->pageView(__METHOD__,$ret_list,
           [
             "_publish_version"      => "2017112211840",
             "gradeArr" => $gradeArr,
           ]
        );
    }

    public function add_order_activity(){
        $title = $this->get_in_str_val('title');

        $period_flag_list = $this->get_in_str_val('period_flag_list','0,1');
        $contract_type_list = $this->get_in_str_val('contract_type_list','0,3');
        $grade_list = $this->get_in_str_val('grade_list',0);
        $max_count = $this->get_in_int_val('max_count',20);

        $can_disable_flag = $this->get_in_int_val('can_disable_flag',1);
        $open_flag = $this->get_in_int_val('open_flag',0);
        $order_activity_discount_type = $this->get_in_int_val('order_activity_discount_type',1);
        $id = date('YmdHis',strtotime("now")).'01';
        return $this->t_seller_student2->row_insert([
            "id"   => $id,
            "title"   => $title,
            "period_flag_list"   => $period_flag_list,
            "contract_type_list"   => $contract_type_list,
            "grade_list"   => $grade_list,
            "can_disable_flag"   => $can_disable_flag,
            "open_flag"   => $open_flag,
            "order_activity_discount_type"   => $order_activity_discount_type,
        ]);
    }

    public function dele_order_activity(){
        $id = $this->get_in_int_val('id');
        $this->t_seller_student2->del_by_id($id);
        return $this->output_succ(); 
    }
    
    public function get_order_activity(){
        $id = $this->get_in_int_val('id');
        $item = $this->t_seller_student2->get_by_id($id);
        $activity_type_list_str = []; //已选组合列表
        $_activity_type_list = [];    //所有组合列表
        $dicount_list = [];           //优惠信息
        if($item){
            $item['period_flag_list_str'] = '';
            if($item['period_flag_list']){
                $periodArr = explode(",",$item['period_flag_list']);
                foreach($periodArr as $pe){
                    $item['period_flag_list_str'] .= E\Eperiod_flag::get_desc($pe).',';
                }
                $item['period_flag_list_str'] = substr($item['period_flag_list_str'],0,-1);
            }

            $item['contract_type_list_str'] = '';
            if($item['contract_type_list']){
                $conArr = explode(",",$item['contract_type_list']);
                foreach($conArr as $con){
                    $item['contract_type_list_str'] .= E\Econtract_type::get_desc($con).',';
                }
                $item['contract_type_list_str'] = substr($item['contract_type_list_str'],0,-1);
            }


            $item['can_disable_flag_str']   = E\Ecan_disable_flag::get_desc($item['can_disable_flag']);
            $item['open_flag_str']   = E\Eopen_flag::get_desc($item['open_flag']);
            $item['order_activity_discount_type_str']   = E\Eorder_activity_discount_type::get_desc($item['order_activity_discount_type']);
            if($item['grade_list']){
                if( strpos($item['grade_list'], ",")){
                    $gradeArr = explode(",",$item['grade_list']);
                    $item['grade_list_str'] = '';
                    foreach( $gradeArr as $grade){
                        $item['grade_list_str'] .= E\Egrade::get_desc($grade).',';
                    }
                    $item['grade_list_str'] = substr($item['grade_list_str'],0,-1);

                }else{
                    $item['grade_list_str'] = E\Egrade::get_desc($item['grade_list']);
                }
            }else{
                $item['grade_list_str'] = '';
            }
            //时间
            $item["date_range_start"] = !empty($item["date_range_start"]) ? date('Y-m-d',$item["date_range_start"]) : '';
            $item["date_range_end"] = !empty($item["date_range_end"]) ? date('Y-m-d',$item["date_range_end"]) : '';
            $item["user_join_time_start"] = !empty($item["user_join_time_start"]) ? date('Y-m-d',$item["user_join_time_start"]) : '';
            $item["user_join_time_end"] = !empty($item["user_join_time_end"]) ? date('Y-m-d',$item["user_join_time_end"]) : '';
            $item["last_test_lesson_start"] = !empty($item["last_test_lesson_start"]) ? date('Y-m-d',$item["last_test_lesson_start"]) : '';
            $item["last_test_lesson_end"] = !empty($item["last_test_lesson_end"]) ? date('Y-m-d',$item["last_test_lesson_end"]) : '';
 
            //寻找其它配额组合
            $_activity_list = $this->t_seller_student2->get_activity_list_by_id($id);
            $_activity_type_list = array_column($_activity_list, 'title', 'id');
           
            $activity_type_list_str = [];
            if($item['max_count_activity_type_list'] && $_activity_list){
             
                if(strpos($item['max_count_activity_type_list'], ",")>0){
               
                    $_activity_arr = explode(",",$item['max_count_activity_type_list']);
                    foreach( $_activity_arr as $type_id){
                        $activity_type_list_str[] = $_activity_type_list[$type_id];
                    }
                }else{
                    $type_id = $item['max_count_activity_type_list'];
                    $activity_type_list_str[] = $_activity_type_list[$type_id];   
                }

            }

            //优惠列表展示
            $discount_list = $this->discount_list($item['order_activity_discount_type'],$item['discount_json']);
            
        }
        $gradeArr = E\Egrade::$desc_map;
        return $this->pageView(__METHOD__,null,
            [
                "_publish_version"      => "201711241729",
                "ret_info" => $item,
                "gradeArr" => $gradeArr,
                "discount_list"=>$discount_list,
                'activity_type_list_str' => $activity_type_list_str,
                '_activity_type_list'=>$_activity_type_list, //配额组合
            ]
        );

    }

    private function discount_list($discount_type,$discout_json){
        $dicount_list = array();
        $before = '';
        $middle = '';
        $after = '';
        if(!$discout_json){
            return $dicount_list;
        }
        
        $discount_type = (int)$discount_type;
        $discout = json_decode($discout_json);
        if(is_array($discout) || is_object($discout)){
            switch($discount_type){
            case 1:
                $before = '满课次数：';
                $middle = ' 打';
                $after = '折';
                foreach( $discout as $var => $val){
                    $dicount_list[] = $before.$var.$middle.$val.$after;
                }
                break;
            case 2:
                $before = '年级：';
                $middle = ' 打';
                $after = '折';
                foreach( $discout as $var => $val){
                    $grade = E\Egrade::get_desc($var);
                    $dicount_list[] = $before.$grade.$middle.$val.$after;
                }
                break;
            case 3:
                $before = '满课次数：';
                $middle = ' 送';
                $after = '课';
                foreach( $discout as $var => $val){
                    $dicount_list[] = $before.$var.$middle.$val.$after;
                }
                break;
            case 4:
                $before = '满金额：￥';
                $middle = ' 立减￥';
                $after = '元';
                foreach( $discout as $var => $val){
                    $dicount_list[] = $before.$var.$middle.$val.$after;
                }
                break;
            default:
                $dicount_list = array();
                break;
            }
            
        }
        
        return $dicount_list;
    }

    public function update_order_activity_01(){
        //返回结果
        $result['status'] = 200;
       
        $id = $this->get_in_int_val('id');
        $id_after = $this->get_in_int_val('id_after');

        if(empty($id_after)){
            $result['status'] = 500;
            $result['info'] = "id不能为空！";
            return $result;

        }
        if( $id != $id_after){
            $item = $this->t_seller_student2->get_by_id($id_after);
            if($item){
                $result['status'] = 500;
                $result['info'] = "id:".$id_after."已经存在，请换个id输入！";
                return $result;
            }
        }
        $title = $this->get_in_str_val('title','-1');
        $date_range_start = trim($this->get_in_str_val('date_range_start',null));
        $date_range_end = trim($this->get_in_str_val('date_range_end',null));
        $lesson_times_min = $this->get_in_int_val('lesson_times_min',null);
        $lesson_times_max = $this->get_in_int_val('lesson_times_max',null);

        $updateArr = [
            'title' => $title,
            'id'=>$id_after,
            'lesson_times_min' => $lesson_times_min,
            'lesson_times_max' => $lesson_times_max,
        ];

        !empty($date_range_start) ? $updateArr['date_range_start'] = strtotime($date_range_start.' 00:00:00') : $updateArr['date_range_start'] = null;
        !empty($date_range_end) ? $updateArr['date_range_end'] = strtotime($date_range_end.' 23:59:59') : $updateArr['date_range_end'] = null;

 
        if($this->t_seller_student2->field_update_list($id,$updateArr)){
            $result['info'] = '更新成功';
            $result['data'] = $id_after;
            return $this->output_succ($result);
        }else{
            $result['info'] = '更新失败';
            $result['status'] = 500;
            return $this->output_succ($result);

        };

    }

    public function update_order_activity_02(){
        $id = $this->get_in_int_val('id');
        $contract_type_list = $this->get_in_str_val('contract_type_list',null);
        $period_flag_list = $this->get_in_str_val('period_flag_list',null);
        $grade_list = $this->get_in_str_val('grade_list',null);
        $updateArr = [
            'contract_type_list' => $contract_type_list,
            'period_flag_list' => $period_flag_list,
            'grade_list' => $grade_list,
        ];
        if($this->t_seller_student2->field_update_list($id,$updateArr)){
            return $this->output_succ();
        }else{
            return $this->output_err("更新出错！");
        };

    }

    public function update_order_activity_03(){
        $id = $this->get_in_int_val('id');
        $power_value = $this->get_in_int_val('power_value',null);
        $max_count = $this->get_in_int_val('max_count',null);
        $max_change_value = $this->get_in_int_val('max_change_value',null);
        $updateArr = [
            'power_value' => $power_value,
            'max_count' => $max_count,
            'max_change_value' => $max_change_value,
        ];
        if($this->t_seller_student2->field_update_list($id,$updateArr)){
            return $this->output_succ();
        }else{
            return $this->output_err("更新出错！");
        };

    }

    public function update_order_activity_04(){
        $id = $this->get_in_int_val('id');
        $max_count_activity_type_list = $this->get_in_str_val('max_count_activity_type_list',null);
        $updateArr = [
            'max_count_activity_type_list' => $max_count_activity_type_list,
        ];
        if($this->t_seller_student2->field_update_list($id,$updateArr)){
            return $this->output_succ();
        }else{
            return $this->output_err("更新出错！");
        };


    }

    public function update_order_activity_05(){
        $id = $this->get_in_int_val('id');
        $can_disable_flag = $this->get_in_int_val('can_disable_flag',null);
        $open_flag = $this->get_in_int_val('open_flag',null);
        $updateArr = [
            'can_disable_flag' => $can_disable_flag,
            'open_flag' => $open_flag,
        ];
        if($this->t_seller_student2->field_update_list($id,$updateArr)){
            return $this->output_succ();
        }else{
            return $this->output_err("更新出错！");
        };

    }

    public function update_order_activity_06(){
        $id = $this->get_in_int_val('id');
        $user_join_time_start = trim($this->get_in_str_val('user_join_time_start',null));
        $user_join_time_end = trim($this->get_in_str_val('user_join_time_end',null));
        $last_test_lesson_start = trim($this->get_in_str_val('last_test_lesson_start',null));
        $last_test_lesson_end = trim($this->get_in_str_val('last_test_lesson_end',null));
        $updateArr = [];

        !empty($user_join_time_start) ? $updateArr['user_join_time_start'] = strtotime($user_join_time_start.' 00:00:00') : $updateArr['user_join_time_start'] = null;
        !empty($user_join_time_end) ? $updateArr['user_join_time_end'] = strtotime($user_join_time_end.' 23:59:59') : $updateArr['user_join_time_end'] = null;
        !empty($last_test_lesson_start) ? $updateArr['last_test_lesson_start'] = strtotime($last_test_lesson_start.' 00:00:00') : $updateArr['last_test_lesson_start'] = null;
        !empty($last_test_lesson_end) ? $updateArr['last_test_lesson_end'] = strtotime($last_test_lesson_end.' 23:59:59') : $updateArr['last_test_lesson_end'] = null;


        if($this->t_seller_student2->field_update_list($id,$updateArr)){
            return $this->output_succ();
        }else{
            return $this->output_err("更新出错！");
        };


    }

    public function update_order_activity_07(){
        $id = $this->get_in_int_val('id');

        $order_activity_discount_type = $this->get_in_int_val('order_activity_discount_type',null);
        $discount_json = $this->get_in_str_val('discount_json',null);

        $updateArr = [
            'order_activity_discount_type' => $order_activity_discount_type,
            'discount_json' => $discount_json,
        ];
        if($this->t_seller_student2->field_update_list($id,$updateArr)){
            return $this->output_succ();
        }else{
            return $this->output_err("更新出错！");
        };

    }

}
