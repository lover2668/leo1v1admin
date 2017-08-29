<?php
namespace App\Config;
class seller_menu{
    static  public  function get_config()  {
        return [
            ["power_id"=>45, "name"=>"TSR事业部门户", "list"=>[
                ["power_id"=>11, "name"=>"排行榜",   "url"=>"/main_page/seller"],
                ["power_id"=>31, "name"=>"月度绩效提成",   "url"=>"/tongji2/self_seller_month_money_list"],
                ["power_id"=>12, "name"=>"学员中心", "list"=>[
                    ["power_id"=>1, "name"=>"分配例子",   "url"=>"/seller_student_new/assign_sub_adminid_list"],
                    ["power_id"=>2, "name"=>"分配例子-主管",   "url"=>"/seller_student_new/assign_member_list"],
                    ["power_id"=>3, "name"=>"转介绍待分配例子-总监",   "url"=>"/seller_student_new/assign_member_list_master"],
                    ["power_id"=>4, "name"=>"转介绍例子-全部","url"=>"/seller_student_new/ass_master_seller_student_list"],
                    ["power_id"=>5, "name"=>"转介绍例子-主管","url"=>"/seller_student_new/ass_master_seller_master_student_list"],
                    ["power_id"=>6, "name"=>"所有用户",   "url"=>"/seller_student_new/seller_student_list_all"],
                    ["power_id"=>7, "name"=>"抢新学生",   "url"=>"/seller_student_new/deal_new_user" ],
                    ["power_id"=>8, "name"=>"抢新学生-当前用户","url"=>"/seller_student_new/deal_new_user_tmk"],
                    ["power_id"=>9, "name"=>"公海-抢学生","url"=>"/seller_student_new/get_free_seller_list"],
                    ["power_id"=>10, "name"=>"试听未签-抢学生","url"=>"/seller_student_new/test_lesson_no_order_list"],
                    ["power_id"=>15, "name"=>"地中海-抢学生",   "url"=>"/seller_student_new/test_lesson_fail_list"],
                    ["power_id"=>11, "name"=>"new-转介绍例子", "url"=>"/seller_student_new/seller_seller_student_list"],
                    ["power_id"=>12, "name"=>"例子回流公海","url"=>"/seller_student_new/get_hold_list"],
                    ["power_id"=>13, "name"=>"试听签单与否反馈",   "url"=>"/seller_student_new/test_lesson_order_fail_list_seller"],
                    ["power_id"=>14, "name"=>"查找用户所在",   "url"=>"/seller_student_new/find_user" ],
                ]],
                ["power_id"=>13, "name"=>"试听排课", "list"=>[
                    ["power_id"=>35, "name"=>"销售-试听课表",   "url"=>"/human_resource/regular_course_seller"],
                    ["power_id"=>1, "name"=>"试听排课",   "url"=>"/seller_student_new2/test_lesson_plan_list_seller"],
                    ["power_id"=>2, "name"=>"老师推荐",   "url"=>"/human_resource/index_seller"],
                    ["power_id"=>3, "name"=>"暂停试听课老师",   "url"=>"/human_resource/index_new_seller_hold"],
                    ["power_id"=>4, "name"=>"申请推荐试听老师推荐",   "url"=>"/tea_manage_new/get_seller_require_commend_teacher_info_seller"],
                    ["power_id"=>5, "name"=>"课程状态-销售",   "url"=>"/supervisor/monitor_seller"],
                    ["power_id"=>6, "name"=>"课程列表-销售",   "url"=>"/tea_manage/lesson_list_seller"],
                    ["power_id"=>7, "name"=>"未绑定的试听课",   "url"=>"/seller_student_new2/test_lesson_no_binding_list"],
                    ["power_id"=>8, "name"=>"教务排课明细",   "url"=>"/tongji_ss/test_lesson_plan_detail_list"],
                    ["power_id"=>67, "name"=>"教师排课信息",   "url"=>"/human_resource/teacher_info_for_seller" ],
                    ["power_id"=>70, "name"=>"教学质量反馈列表",   "url"=>"/tea_manage_new/get_seller_ass_record_info_seller"],
                ]],
                ["power_id"=>14, "name"=>"合同", "list"=>[
                    ["power_id"=>1, "name"=>"合同-待付费",   "url"=>"/user_manage/contract_list_seller_add"],
                    ["power_id"=>2, "name"=>"合同-已付费",   "url"=>"/user_manage/contract_list_seller_payed"],
                    ["power_id"=>3, "name"=>"合同统计",   "url"=>"/tongji_ss/contract_count"],
                    ["power_id"=>4, "name"=>"合同每日统计",   "url"=>"/tongji/contract"],
                    ["power_id"=>5, "name"=>"折扣情况",   "url"=>"/contract_present/contract_present_info"],
                    ["power_id"=>6, "name"=>"销售-退款",   "url"=>"/user_manage/refund_list_seller"],
                ]],
                ["power_id"=>15, "name"=>"例子统计", "list"=>[
                    ["power_id"=>1, "name"=>"例子统计","url"=>"/tongji_ss/user_count"],
                    ["power_id"=>2, "name"=>"例子统计-个人","url"=>"/tongji/seller_user_count"],
                    ["power_id"=>3, "name"=>"例子销售分布","url"=>"/tongji2/seller_student_admin_list"],
                    ["power_id"=>4, "name"=>"例子销售拨打数","url"=>"/tongji_ex/call_count"],
                    ["power_id"=>5, "name"=>"新增例子分时统计","url"=>"/tongji_ss/new_user_count"],
                    ["power_id"=>6, "name"=>"销售主管未分配统计","url"=>"/tongji_ss/master_no_assign_count"],
                    ["power_id"=>7, "name"=>"销售个人统计", "url"=>"/tongji_ss/seller_count"],
                    ["power_id"=>8, "name"=>"销售个人统计-主管",   "url"=>"/tongji_ss/seller_count_seller_master"],
                    ["power_id"=>9, "name"=>"当前用户可抢数",   "url"=>"/seller_student_new2/seller_get_new_count_admin_list" ],
                    ["power_id"=>10, "name"=>"当前用户抢新统计",   "url"=>"/seller_student_new2/tongji_seller_get_new_count" ],
                    ["power_id"=>11, "name"=>"当前用户可抢数明细",   "url"=>"/seller_student_new2/seller_get_new_count_list" ],
                    ["power_id"=>12, "name"=>"当前未拨打未拨通",   "url"=>"/seller_student_new2/seller_no_call_to_free_list" ],
                    ["power_id"=>13, "name"=>"转介绍统计",   "url"=>"/tongji2/referral_count" ],
                    ["power_id"=>14, "name"=>"无效资源分类", "url"=>"/tongji_ss/invalid_user_list" ],
                    ["power_id"=>15, "name"=>"首次回访例子数-间隔",   "url"=>"/tongji2/first_call_info" ],
                    ["power_id"=>16, "name"=>"首次回访例子数-小时",   "url"=>"/tongji/first_revisite_time_list" ],
                ]],
                ["power_id"=>16, "name"=>"课量统计", "list"=>[
                    ["power_id"=>1, "name"=>"教务排课明细",   "url"=>"/tongji_ss/test_lesson_plan_detail_list"],
                    ["power_id"=>2, "name"=>"排课明细",   "url"=>"/seller_student_new2/test_lesson_detail_list"],
                    ["power_id"=>3, "name"=>"排课统计",   "url"=>"/tongji_ss/set_lesson_count"],
                    ["power_id"=>4, "name"=>"实时申请未排统计",   "url"=>"/tongji_ss/require_no_set_lesson_info"],
                    ["power_id"=>5, "name"=>"销售申请统计",   "url"=>"/tongji_ss/require_count_seller"],
                    ["power_id"=>6, "name"=>"在线预计课数",   "url"=>"/tongji/online_def_user_count_list"],
                    ["power_id"=>7, "name"=>"在线课数",   "url"=>"/tongji/online_user_count_list"],
                    ["power_id"=>8, "name"=>"销售试听转化率统计",   "url"=>"/tongji_ss/seller_test_lesson_transfor_per"],
                    ["power_id"=>9, "name"=>"周排课量统计",   "url"=>"/tongji2/seller_week_lesson"],
                    ["power_id"=>10, "name"=>"周排课量统计-主管",   "url"=>"/tongji2/seller_week_lesson_master"],
                    ["power_id"=>11, "name"=>"周排课量回访统计",   "url"=>"/tongji2/seller_week_lesson_call"],
                    ["power_id"=>12, "name"=>"周排课量回访统计-主管",   "url"=>"/tongji2/seller_week_lesson_call_master"],
                    ["power_id"=>13, "name"=>"周排课量回访列表",   "url"=>"/tongji2/lesson_call_list"],
                    ["power_id"=>14, "name"=>"试听首次回访时间统计",   "url"=>"/tongji2/test_lesson_frist_call_time"],
                    ["power_id"=>15, "name"=>"试听首次回访时间统计-主管",   "url"=>"/tongji2/test_lesson_frist_call_time_master"],
                    ["power_id"=>16, "name"=>"试听转化率统计",   "url"=>"/tongji_ss/tongji_seller_test_lesson_order_info"],
                    ["power_id"=>17, "name"=>"试听转化率统计-试卷",   "url"=>"/tongji_ss/tongji_seller_test_lesson_paper_order_info"],
                    ["power_id"=>18, "name"=>"转化率",   "url"=>"/tongji_ex/test_lesson_order_info"],
                    ["power_id"=>19, "name"=>"转化率-明细",   "url"=>"/tongji_ex/test_lesson_order_detail_list"],
                ]],
                ["power_id"=>17, "name"=>"综合统计", "list"=>[
                    ["power_id"=>1, "name"=>"日报",   "url"=>"/tongji_ss/day_report"],
                    ["power_id"=>25, "name"=>"销售月度绩效",   "url"=>"/tongji2/seller_month_money_list"],
                    ["power_id"=>2, "name"=>"销售月度统计报表",   "url"=>"/user_manage_new/seller_tongji_report_info"],
                    ["power_id"=>3, "name"=>"销售红黑榜",   "url"=>"/user_manage_new/seller_require_tq_time_list"],
                    ["power_id"=>4, "name"=>"设备统计",   "url"=>"/tongji_ss/lesson_device_info"],
                ]],
            ]],
        ];  
    }

}