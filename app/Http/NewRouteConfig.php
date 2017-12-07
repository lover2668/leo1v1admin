<?php
namespace App\Http;
class NewRouteConfig {
    static public function check_is_new_url($url) {
        $ret = isset(static::$url_map [$url] )  ;
        return $ret;
        /*
        if ($ret) {
            return $ret;
        }
        if (  $_SERVER["SERVER_NAME"] == "dev.admin.yb1v1.com"  ) {
            return isset(static::$dev_url_map [$url] ) ;
        }else{
            return false;
        }
        */
    }
    //指定旧版本的control , 新版本不需要再配置了
    static public $old_ctl_map = [
        "appoint"=>true,
        "ass_manage"=>true,
        "authority"=>true,
        "common"=>true,
        "common_ex"=>true,
        "course_manage"=>true,
        "get_login_list"=>true,
        "homework_manage"=>true,
        "human_resource"=>true,
        "lesson_manage"=>true,
        "login"=>true,
        "monitor"=>true,
        "news_info"=>true,
        "order_course"=>true,
        "praise_manage"=>true,
        "present"=>true,
        "question"=>true,
        "revisit"=>true,
        "school_info"=>true,
        "seller_student2"=>true,
        "small_class"=>true,
        "stu_manage"=>true,
        "supervisor"=>true,
        "teacher_free"=>true,
        "tea_manage"=>true,
        "test_info"=>true,
        "todo"=>true,
        "upload"=>true,
        "user_book"=>true,
        "user_manage"=>true,
        "user_will"=>true,
        "wx"=>true,
        "test_lesson_opt"=>true,
        "rule_txt"=>true,
        "resource"=>true,
        'test_bacon'=>true,
        'question'=>true,
    ];

    static public function check_is_new_ctl($ctl) {
        return !isset(static::$old_ctl_map[$ctl]);
    }

    //config
    static public $url_map = [
        "/resource/add_or_del_or_edit"         => true,
        "/resource/resource_count"             => true,
        "/tea_manage_new/approved_data"        => true,
        "/resource/update_stu_hash"            => true,
        "/resource/reupload_resource"          => true,
        "/resource/rename_resource"            => true,
        "/resource/del_or_restore_resource"    => true,
        "/resource/add_file"                   => true,
        "/resource/add_resource"               => true,
        "/resource/get_all"                    => true,
        "/resource/get_del"                    => true,
        "/resource/resource_frame"             => true,
        "/resource/get_list_by_resource_id_js" => true,
        "/rule_txt/add_or_update_name"         => true,
        "/rule_txt/process_info"               => true,
        "/rule_txt/update_process"             => true,
        "/rule_txt/get_all"                    => true,
        "/rule_txt/add_or_update_title"           => true,
        "/rule_txt/rule_detail"                   => true,
        "/rule_txt/del_process"                   => true,
        "/rule_txt/up_or_down"                    => true,
        "/rule_txt/del_rule_detail"               => true,
        "/rule_txt/add_or_update_rule_detail"     => true,
        "/test_lesson_opt/test_opt_list"          => true,
        "/tea_manage/train_is_through_list"       => true,
        "/main_page/recruit_division"             => true,
        "/warning_overtime/add_overtime"          => true,
        "/grab_lesson/add_requireids"             => true,
        "/grab_lesson/upadte_lesson_link"         => true,
        "/grab_lesson/get_all_grab_info"          => true,
        "/teacher_info/grab_visit_info"           => true,
        "/lesson_manage/stu_status_count"         => true,
        "/admin_join/get_apply_info"              => true,
        "/supervisor/lesson_all_info"             => true,
        "/supervisor/monitor"                     => true,
        "/supervisor/monitor_seller"              => true,
        "/supervisor/monitor_ass"                 => true,
        "/supervisor/get_lesson_conditions"       => true,
        "/supervisor/get_lesson_conditions_js"    => true,
        "/supervisor/add_error_info"              => true,
        "/tea_manage/get_lesson_xmpp_audio"       => true,
        "/tea_manage/get_tea_pad_lesson_qr"       => true,
        "/login/teacher"                          => true,
        "/login/agent"                          => true,
        "/lesson_manage/get_lesson_info"          => true,
        "/common/send_papers_email"  => true,
        "/common/add_trial_train_lesson_by_admin" => true,
        "/common/del_qiniu_img"                   => true,
        "/common/get_teacher_hornor_list"         => true,
        "/common/upload_qiniu"                    => true,
        "/common/notify"                          => true,
        "/common/get_bucket_info"                 => true,
        "/common/send_wx_todo_msg"                => true,
        "/common/send_offer_info_by_teacherid"    => true,
        "/common/get_teacher_qr"                  => true,
        "/common/get_agent_qr"                    => true,
        "/common/get_agent_qr_new"                => true,
        "/common/get_comment_tags"                => true,
        "/common/get_finish_lessons"              => true,
        "/common/train_user_answer"               => true,
        "/common/base64"                          => true,
        "/common/teacher_record_detail_info"      => true,
        "/common/get_openim_info"                 => true,
        "/common/send_mail"                       => true,
        "/common/email_open_address"              => true,
        "/common/send_paper_email"                => true,
        "/common/show_offer_html"                 => true,
        "/common/show_level_up_html"              => true,
        "/common/show_ruzhi_html"                 => true,
        "/common_ex/book_free_lesson"             => true,
        "/common_ex/book_free_lesson_with_code"   => true,
        "/common_ex/send_phone_code"              => true,
        "/common/send_phone_code"                 => true,
        "/common/bind"                            => true,
        "/common/set_upload_info"                 => true,
        "/common/send_charge_info"                => true,
        "/common/get_webhooks_notice_test"        => true,
        "/common/get_webhooks_notice"             => true,
        "/common/dev_teacher_qr"                  => true,
        "/common/get_ppl_data"                    => true,
        "/common/send_baidu_money_charge"         => true,
        "/common/get_baidu_money_charge"          => true,
        "/common/send_ccb_order_charge"           => true,
        "/common/get_city_textbook_info"          => true,
        "/common/baidu_callback_return_info_test" => true,
        "/common/baidu_callback_return_info"      => true,

        "/user_manage/pay_money_stu_list"                       => true,
        "/user_manage/refund_duty_analysis"                     => true,
        "/user_manage/set_refund_money"                         => true,
        "/user_manage/complaint_department_deal_teacher_tea_jy" => true,
        "/user_manage/complaint_department_deal"        => true,
        "/user_manage/complaint_department_deal_teacher"     => true,
        "/user_manage/complaint_department_deal_teacher_qc"  => true,
        "/user_manage/complaint_department_deal_teacher_tea" => true,
        "/user_manage/complaint_department_deal_parent"      => true,
        "/user_manage/complaint_department_deal_parent_tea"  => true,
        "/user_manage/complaint_department_deal_qc"        => true,
        "/user_manage/complaint_department_deal_product"   => true,
        "/user_manage/qc_complaint_tea"                    => true,
        "/user_manage/qc_complaint"                        => true,
        "/user_manage/set_graduating_lesson_time"               => true,
        "/user_manage/get_weeks"                                => true,
        "/user_manage/graduating_lesson_time"                   => true,
        "/user_manage/add_qc_analysis_by_order_apply"           => true,
        "/user_manage/refund_analysis"                          => true,
        "/user_manage/set_refund_analysis"                      => true,
        "/user_manage/get_reply_by_orderid_apply_time" => true,
        "/user_manage/set_reply"    => true,
        "/user_manage/get_responsibility_by_orderid_apply_time" => true,
        "/user_manage/set_refund_analysis_info"                 => true,
        "/user_manage/get_refund_info"                          => true,
        "/user_manage/refund_list_seller"                       => true,
        "/user_manage/refund_list_ass"                          => true,
        "/user_manage/refund_list_finance"                      => true,
        "/user_manage/set_contract_payed_new"                   => true,
        "/user_manage/del_contract"                => true,
        "/user_manage/set_dynamic_passwd"                => true,
        "/user_manage/contract_list_seller_add"                 => true,
        "/user_manage/contract_list_seller_payed"               => true,
        "/user_manage/contract_list_seller_mix"                 => true,
        "/user_manage/set_refund_order"                         => true,
        "/user_manage/refund_list"                 => true,
        "/user_manage/set_contract_starttime"                   => true,
        "/user_manage/get_contract_starttime"                   => true,
        "/user_manage/get_self_contract"                        => true,
        "/user_manage/update_contract_type"                     => true,
        "/user_manage/parent_archive"                           => true,
        "/user_manage/set_spree"                                => true,
        "/user_manage/update_parent_info"                       => true,
        "/user_manage/get_parent_list"                          => true,
        "/user_manage/contract_list"                            => true,
        "/user_manage/all_users"                                => true,
        "/user_manage/index"                                    => true,
        "/user_manage/set_stu_type"                             => true,
        "/user_manage/ass_archive"                              => true,
        "/user_manage/ass_archive_ass"                          => true,
        "/user_manage/ass_count"                                => true,
        "/user_manage/ass_counts"                  => true,
        "/user_manage/contract_list_seller"                     => true,
        "/user_manage/contract_list_ass"                        => true,
        "/user_manage/get_user_list"                            => true,
        "/user_manage/set_test_user"                            => true,
        "/user_manage/set_stu_origin"              => true,
        "/user_manage/get_user_manage_list_for_js"              => true,
        "/user_manage/get_userid_by_phone"                      => true,
        "/user_manage/pc_relationship"                          => true,
        "/user_manage/count_zan"                                => true,
        "/user_manage/zan_info"                                 => true,
        "/user_manage/get_mypraise_info"                        => true,
        "/user_manage/add_praise"                               => true,
        "/user_manage/get_type_student_list"                    => true,
        "/user_manage/student_type_update"                      => true,
        "/user_manage/get_order_info"                           => true,
        "/user_manage/get_order_refund_list"                    => true,
        "/user_manage/get_user_normal_order"                    => true,
        "/user_manage/user_info_by_month"                       => true,
        "/user_manage/user_login_list"                          => true,
        "/user_manage/tongji_login_ip_info"                     => true,
        "/user_manage/ass_random_revisit"                       => true,
        "/user_manage/no_type_student_score"                    => true,
        "/user_manage/tongji_student_subject"                   => true,
        "/user_manage/tongji_student_grade_subject"             => true,
        "/user_manage/tongji_grade_lesson_count"                => true,
        "/user_manage/student_school_score_stat"                => true,
        "/user_manage/tongji_cc"                                => true,
        "/user_manage/student_single_subject"                   => true,
        "/user_manage/stu_all_teacher"                          => true,
        "/user_manage/get_stu_grade_info_month"                 => true,
        "/user_manage/get_stu_subject_info_month"               => true,
        "/user_manage/subject_by_month"                         => true,
        "/user_manage/tongji_check"                             => true,
        "/user_manage/ass_no_test_lesson_kk_list"               => true,
        "/user_manage/stu_all_teacher_all"                      => true,

        "/authority/get_account_role"                 => true,
        "/authority/set_account_role"                 => true,
        "/authority/get_login_list"                   => true,
        "/authority/manager_list"                     => true,
        "/authority/manager_list_for_kaoqin"            => true,
        "/authority/get_show_manage_info"    => true,
        "/authority/get_group_user_list"     => true,
        "/authority/get_group_user_list_ex"  => true,
        "/authority/add_manager"             => true,
        "/authority/del_manager"             => true,
        "/authority/add_or_update_gift"      => true,
        "/authority/del_gift"                => true,
        "/authority/manager_list_for_seller"          => true,
        "/authority/manager_list_for_ass"             => true,
        "/authority/manager_list_for_qz"              => true,
        "/authority/manager_list_for_qz_shanghai"     => true,
        "/authority/manager_list_for_qz_wuhan"    => true,
        "/authority/get_permission_list"              => true,
        "/authority/set_permission"          => true,
        "/authority/seller_edit_log_list"             => true,
        "/authority/set_group_img"                    => true,
        "/authority/update_lesson_call_end_time"             => true,
        "/ajax_deal2/register_student_parent_account" => true,

        "/login/login"                   => true,
        "/login/down_leo_file"           => true,
        "/login/login_teacher"           => true,
        "/login/agent_login"           => true,
        "/login/login_other"             => true,
        "/login/login_ex"                => true,
        "/login/logout"                  => true,
        "/login/logout_teacher"          => true,
        "/login/login_check_verify_code" => true,
        "/login/get_verify_code"         => true,

        "/tea_manage/auto_rank_lesson"            => true,
        "/tea_manage/train_not_through_list"      => true,
        "/tea_manage/train_not_through_list_px"   => true,
        "/tea_manage/set_teacher_part_remark"     => true,
        "/tea_manage/set_train_lecture_status"    => true,
        "/tea_manage/set_train_lecture_status_b1"    => true,
        "/tea_manage/set_train_lecture_status_b2"    => true,
        "/tea_manage/train_lecture_lesson_full_time" => true,
        "/tea_manage/train_lecture_lesson_zs"     => true,
        "/tea_manage/train_lecture_lesson"        => true,
        "/tea_manage/train_lecture_lesson_zj"     => true,
        "/tea_manage/train_lecture_lesson_fulltime"=> true,
        "/tea_manage/add_train_lesson_by_xls"     => true,
        "/tea_manage/set_teacher_record_account"  => true,
        "/tea_manage/trial_train_lesson_list"     => true,
        "/tea_manage/trial_train_lesson_list_zj"  => true,
        "/tea_manage/trial_train_lesson_list_zs"  => true,
        "/tea_manage/tea_imgs_show"               => true,
        "/tea_manage/get_lesson_list"              => true,
        "/tea_manage/tea_manage_all_info"          => true,
        "/tea_manage/get_stu_request"              => true,
        "/tea_manage/send_email_with_lessonid"     => true,
        "/tea_manage/lesson_account"               => true,
        "/tea_manage/change_enable_video"          => true,
        "/tea_manage/get_train_lesson"             => true,
        "/tea_manage/del_train_user"               => true,
        "/tea_manage/add_train_lesson"             => true,
        "/tea_manage/add_train_lesson_user"        => true,
        "/tea_manage/get_train_lesson_user"        => true,
        "/tea_manage/train_lesson_list"            => true,
        "/tea_manage/train_lesson_list_research"   => true,
        "/tea_manage/train_lesson_list_fulltime"   => true,
        "/tea_manage/get_open_course_list_for_js"  => true,
        "/tea_manage/get_open_package_list_for_js" => true,
        "/tea_manage/get_open_from_list_for_js"    => true,
        "/tea_manage/get_stu_performance"          => true,
        "/tea_manage/set_stu_performance"          => true,
        "/tea_manage/get_course_name"              => true,
        "/tea_manage/update_tea_money"             => true,
        "/tea_manage/get_lesson_name_and_intro"    => true,
        "/tea_manage/set_lesson_info"              => true,
        "/tea_manage/set_stu_performance_for_seller"=> true,
        "/tea_manage/set_course_name"              => true,
        "/tea_manage/lesson_list"                  => true,
        "/tea_manage/tea_lesson_list"              => true,
        "/tea_manage/quiz_info"                    => true,
        "/tea_manage/open_class2"                  => true,
        "/tea_manage/change_open_teacher"          => true,
        "/tea_manage/add_teacher"                  => true,
        "/tea_manage/delete_open_lesson"           => true,
        "/tea_manage/search_role"                  => true,
        "/tea_manage/upload_open_cw"               => true,
        "/tea_manage/add_from_lessonid"            => true,
        "/tea_manage/can_set_from_lessonid"        => true,
        "/tea_manage/get_teacherid"                => true,
        "/tea_manage/lesson_list_seller"           => true,
        "/tea_manage/lesson_list_research"         => true,
        "/tea_manage/lesson_list_zj"               => true,
        "/tea_manage/lesson_list_fulltime"         => true,
        "/tea_manage/add_robot_lesson"             => true,
        "/tea_manage/lesson_list_ass"              => true,
        "/tea_manage/course_plan"                  => true,
        "/tea_manage/course_plan_stu"              => true,
        "/tea_manage/course_plan_stu_ass"          => true,
        "/tea_manage/course_plan_stu_summer"       => true,
        "/tea_manage/course_plan_psychological"    => true,
        "/tea_manage/course_set_new"               => true,
        "/tea_manage/get_course_list"              => true,
        "/tea_manage/get_course_list_new"          => true,
        "/tea_manage/get_course_info"              => true,
        "/tea_manage/get_stu_performance_new"      => true,
        "/tea_manage/get_user_video_info"          => true,
        "/tea_manage/set_test_lesson_comment"      => true,
        "/tea_manage/get_teacher_info_by_phone"    => true,
        "/tea_manage/trial_train_no_pass_list"     => true,
        "/tea_manage/teacher_train_list"           => true,
        "/tea_manage/teacher_cc_count"             => true,

        "/small_class/index"                    => true,
        "/small_class/index_ass"                => true,
        "/small_class/lesson_list_new"          => true,
        "/small_class/lesson_list_new_ass"      => true,
        "/small_class/student_list_new"         => true,
        "/small_class/student_list_new_ass"     => true,
        "/small_class/del_lesson"               => true,
        "/small_class/get_config_courseid"      => true,
        "/small_class/get_teacher_clothes_list" => true,
        "/small_class/get_teacher_clothes"      => true,
        "/small_class/set_teacher_clothes"      => true,
        "/small_class/get_teacher_pic"          => true,
        "/small_class/add_small_student"    => true,

        "/user_book/get_booked_user"     => true,
        "/user_book/phone_user_list"     => true,
        "/user_book/phone_user_list_all" => true,
        "/user_book/set_sys_operator"    => true,
        "/user_book/add_book_info"       => true,
        "/user_book/add_book_revisit"    => true,
        "/user_book/get_book_revisit"    => true,
        "/user_book/update_user_info"    => true,
        "/user_book/set_class_time"      => true,
        "/user_book/get_tea_schedule"    => true,
        "/user_book/set_course_teacher"  => true,

        "/stu_manage/index"                    => true,
        "/stu_manage/score_list"                    => true,
        "/stu_manage/todo_list"                => true,
        "/stu_manage/call_list"                    => true,
        "/stu_manage/set_week_comment_num"     => true,
        "/stu_manage/set_enable_video"         => true,
        "/stu_manage/course_list"              => true,
        "/stu_manage/course_lesson_list"       => true,
        "/stu_manage/lesson_plan_edit"         => true,
        "/stu_manage/set_assistantid"          => true,
        "/stu_manage/set_lesson_teacherid"     => true,
        "/stu_manage/lesson_account"           => true,
        "/stu_manage/lesson_evaluation"        => true,
        "/stu_manage/get_stu_manage"           => true,
        "/stu_manage/get_stu_parent"           => true,
        "/stu_manage/set_stu_parent"           => true,
        "/stu_manage/parent_list"              => true,
        "/stu_manage/get_stu_lesson_left"      => true,
        "/stu_manage/add_course_order_for_stu" => true,
        "/stu_manage/get_lesson_simple_info"   => true,
        "/stu_manage/set_lesson_simple_info"   => true,
        "/stu_manage/order_lesson_list"        => true,
        "/stu_manage/order_info_list"          => true,
        "/stu_manage/init_info"                => true,
        "/stu_manage/init_info_tmp"            => true,
        "/stu_manage/init_info_by_contract_cc" => true,
        "/stu_manage/init_info_by_contract_cr" => true,
        "/stu_manage/return_book_record"       => true,
        "/stu_manage/return_record"            => true,
        "/stu_manage/set_subject"              => true,
        "/stu_manage/test_lesson_list"         => true,
        "/seller_tongji/test"                  => true,
        "/stu_manage/regular_course_stu"       => true,
        "/stu_manage/user_login_list"          => true,
        "/stu_manage/one_three_grade_student"  => true,
        "/stu_manage/score_list1"              => true,
        
        "/question/question_list"              => true,
        "/question/knowledge_list"              => true,
        "/question/question_know_list"              => true,
        "/question/answer_list"              => true,
        "/question/question_add"              => true,
        "/question/question_edit"              => true,
        "/question/knowledge_add"              => true,
        "/question/knowledge_edit"              => true,
        "/question/knowledge_dele"              => true,
        "/question/answer_add"              => true,
        "/question/answer_edit"              => true,
        "/question/answer_dele"              => true,
        "/question/question_know_get"        => true,
        "/question/question_know_add"        => true,
        "/question/question_know_dele"        => true,

        "/seller_student_new/tmk_seller_student_new"  => true,
        "/seller_student2/show_order_activity_info"  => true,
        "/seller_student2/add_order_activity"  => true,
        "/seller_student2/dele_order_activity"  => true,
        "/seller_student2/update_order_activity_01"  => true,
        "/seller_student2/update_order_activity_02"  => true,
        "/seller_student2/update_order_activity_03"  => true,
        "/seller_student2/update_order_activity_04"  => true,
        "/seller_student2/update_order_activity_05"  => true,
        "/seller_student2/update_order_activity_06"  => true,
        "/seller_student2/update_order_activity_07"  => true,
        "/seller_student2/update_order_activity_08"  => true,
        "/seller_student2/get_order_activity"  => true,
        "/seller_student2/get_activity_all_list"  => true,
        "/seller_student2/get_current_activity"  => true,
        "/seller_student2/update_power_value"  => true,
        "/seller_student2/get_all_activity"  => true,
        "/test_bacon/power_group_edit"  => true,
        "/appoint/index2"                  => true,
        "/appoint/get_package_simple_info" => true,
        "/appoint/get_package_pic"         => true,

        "/news_info/news_ad_info"            => true,
        "/news_info/add_user_message"        => true,
        "/news_info/get_ad_info"             => true,
        "/news_info/add_ad_info"             => true,
        "/news_info/del_ad_info"             => true,
        "/news_info/stu_message_list"        => true,
        "/news_info/stu_detail_message_list" => true,
        "/news_info/add_stu_message_content" => true,
        "/news_info/push_news_info"          => true,

        "/human_resource/get_lesson_modify_list" => true,
        "/human_resource/get_input_score_list" => true,
        "/human_resource/interview_remind"   => true,
        "/human_resource/get_check_textbook_tea_list"   => true,
        "/human_resource/switch_teacher_to_test"        => true,
        "/human_resource/origin_list"                   => true,
        "/human_resource/teacher_total_list"            => true,
        "/human_resource/zs_origin_list"     => true,
        "/human_resource/zs_origin_list_new" => true,
        "/human_resource/set_trial_train_lesson" => true,
        "/human_resource/change_teacher_to_new" => true,
        "/human_resource/transfer_teacher_info" => true,
        "/human_resource/more_grade_pdf_to_teacher" => true,
        "/human_resource/add_research_teacher" => true,
        "/human_resource/assistant_info_new2" => true,
        "/human_resource/set_teacher_grade_range"                    => true,
        "/human_resource/get_common_config_new_seller"               => true,
        "/human_resource/regular_course_seller"            => true,
        "/human_resource/add_teacher_record"                         => true,
        "/human_resource/set_teacher_ref_type"                       => true,
        "/human_resource/teacher_lecture_list"                       => true,
        "/human_resource/teacher_lecture_list_research"              => true,
        "/human_resource/teacher_lecture_list_fulltime"              => true,
        "/human_resource/teacher_lecture_list_zj"                    => true,
        "/human_resource/teacher_lecture_list_zs"                    => true,
        "/human_resource/set_teacher_level"                => true,
        "/human_resource/set_teacher_lecture_is_test"                => true,
        "/human_resource/change_phone"                     => true,
        "/human_resource/send_sms_by_video_error"             => true,
        "/human_resource/get_lesson_full_wage_old"             => true,
        "/human_resource/get_lesson_full_list"                => true,
        "/human_resource/get_reference_list"               => true,
        "/human_resource/get_stu_count_list"               => true,
        "/human_resource/teacher_lecture_origin_count"        => true,
        "/human_resource/set_teacher_lecture_account"         => true,
        "/human_resource/set_teacher_lecture_status_new"             => true,
        "/human_resource/get_teacher_simple_info"             => true,
        "/human_resource/set_teacher_record_info"             => true,
        "/human_resource/set_teacher_record_info_new"         => true,
        "/human_resource/get_teacher_record_list"                    => true,
        "/human_resource/otp_common_config"                          => true,
        "/human_resource/otp_common_config_new"                      => true,
        "/human_resource/otp_winter_common_config_new"               => true,
        "/human_resource/otp_summer_common_config_new"               => true,
        "/human_resource/index"                            => true,
        "/human_resource/index_ass"                        => true,
        "/human_resource/index_jw"                         => true,
        "/human_resource/index_zs"                         => true,
        "/human_resource/index_new_jw"                     => true,
        "/human_resource/index_new_jw_hold"                => true,
        "/human_resource/index_new_seller_hold"            => true,
        "/human_resource/teacher_lecture_appointment_info" => true,
        "/human_resource/teacher_lecture_appointment_info_zs" => true,
        "/human_resource/teacher_lecture_appointment_info_full_time" => true,
        "/human_resource/update_lecture_status"               => true,
        "/human_resource/regular_course"                   => true,
        "/human_resource/summer_regular_course"               => true,
        "/human_resource/winter_regular_course"               => true,
        "/human_resource/winter_regular_course_all"           => true,
        "/human_resource/regular_course_all"                         => true,
        "/human_resource/get_common_config"                   => true,
        "/human_resource/get_common_config_new"            => true,
        "/human_resource/get_winter_common_config_new"               => true,
        "/human_resource/get_summer_common_config_new"        => true,
        "/human_resource/specialty"                        => true,
        "/human_resource/assistant_info2"                     => true,
        "/human_resource/get_assistant_info"                  => true,
        "/human_resource/get_assistant_info2"                 => true,
        "/human_resource/update_assistant_info2"                     => true,
        "/human_resource/add_teacher"                                => true,
        "/human_resource/edit_teacher"                               => true,
        "/human_resource/get_simple_teacher_info"                    => true,
        "/human_resource/delete_tea_closest"               => true,
        "/human_resource/get_eve_config"                   => true,
        "/human_resource/course_full"                         => true,
        "/human_resource/preview"                             => true,
        "/human_resource/index_new"                           => true,
        "/human_resource/assistant_info_new"                  => true,
        "/human_resource/add_ass_new"                         => true,
        "/human_resource/get_apply_info"                      => true,
        "/human_resource/teacher_meeting_info"                => true,
        "/human_resource/teacher_meeting_join_info"           => true,
        "/human_resource/get_free_teacherid_arr"              => true,
        "/human_resource/get_free_teacherid_arr_new"                 => true,
        "/human_resource/teacher_assess"                      => true,
        "/human_resource/set_teacher_is_test"                 => true,
        "/human_resource/get_question_tongji"                 => true,
        "/human_resource/winter_teacher_lesson_list"          => true,
        "/human_resource/index_tea_qua"                       => true,
        "/human_resource/index_tea_qua_zj"                    => true,
        "/human_resource/index_fulltime"                      => true,
        "/human_resource/index_seller"                        => true,
        "/human_resource/teacher_record_detail_info"          => true,
        "/human_resource/teacher_record_detail_info_new"      => true,
        "/human_resource/teacher_record_detail_list"          => true,
        "/human_resource/teacher_record_detail_list_zj"          => true,
        "/human_resource/teacher_record_detail_list_new"      => true,
        "/human_resource/teacher_record_detail_list_new_zj"      => true,
        "/human_resource/get_week_confirm_num"                => true,
        "/human_resource/set_teacher_limit_plan_lesson"       => true,
        "/human_resource/add_lecture_revisit_record"          => true,
        "/human_resource/get_lecture_revisit_info"            => true,
        "/human_resource/research_qz_teacher_stu_info"        => true,
        "/human_resource/teacher_test_lesson_info"            => true,
        "/human_resource/teacher_test_lesson_info_zj"         => true,
        "/human_resource/teacher_test_lesson_info_fulltime"   => true,
        "/human_resource/get_broken_line_order_rate"      => true,
        "/human_resource/teacher_test_lesson_info_total"      => true,
        "/human_resource/teacher_test_lesson_info_total_fulltime"    => true,
        "/human_resource/get_avg_conversion_time"             => true,
        "/human_resource/get_assign_jw_adminid_list"          => true,
        "/human_resource/add_new_teacher_revisit_record"      => true,
        "/human_resource/get_new_teacher_revisit_info"        => true,
        "/human_resource/teacher_info_new"                    => true,
        "/human_resource/get_teacher_lecture_fail_score_info"        => true,
        "/human_resource/get_teacher_lecture_fail_score_info_zs"     => true,
        "/human_resource/teacher_info_for_seller"             => true,
        "/human_resource/set_teacher_appointment_info"        => true,
        "/human_resource/set_class_abnormal_record"                  => true,
        "/human_resource/reset_lecture_account"               => true,
        "/human_resource/quit_teacher_info"                   => true,
        "/human_resource/reaearch_teacher_lesson_list"        => true,
        "/human_resource/reaearch_teacher_lesson_list_fulltime"      => true,
        "/human_resource/reaearch_teacher_lesson_list_research"      => true,



        "/ass_manage/add_manager" => true,
        "/ass_manage/add_assistant"     => true,
        "/ass_manage/add_assistant_new" => true,

        "/authority/manager_list_offline"       => true,
        "/authority/edit_group"      => true,
        "/authority/jurisdiction"     => true,
        "/authority/member_group"    => true,
        "/authority/add_member_info"  => true,
        "/authority/delete_member"    => true,
        "/authority/get_member_info" => true,
        "/authority/edit_member_info" => true,
        "/authority/set_fulltime_teacher_type" => true,
        "/fulltime_teacher/fulltime_teacher_count" => true,
        "/tongji2/fulltime_teacher_kpi_chart"  => true,

        "/revisit/get_revisit_info"                 => true,
        "/revisit/update_revisit"                   => true,
        "/revisit/get_revisit_info_new" => true,
        "/revisit/add_revisit_record"   => true,
        "/revisit/add_revisit_record_b2"            => true,
        "/revisit/get_revisit_info_by_revisit_time" => true,

        "/monitor/smsmonitor"           => true,

        "/customer_service/intended_user_info"           => true,
        "/customer_service/complaint_info"               => true,
        "/customer_service/proposal_info"                => true,

        "/requirement/add_requirement_info"              => true,
        "/requirement/add_requirement_info_new"          => true,
        "/requirement/requirement_info_new"              => true,
        "/requirement/requirement_del"                   => true,
        "/requirement/re_edit_requirement_info"          => true,
        "/requirement/re_edit_requirement_info_new"      => true,
        "/requirement/requirement_info"                  => true,
        "/requirement/requirement_info_product"          => true,
        "/requirement/requirement_info_development"      => true,
        "/requirement/re_edit_requirement_info_product"  => true,
        "/requirement/requirement_info_test"             => true,
        "/requirement/product_deal"                      => true,
        "/requirement/product_re_edit"                   => true,
        "/requirement/product_reject"                    => true,
        "/requirement/product_delete"                    => true,
        "/requirement/product_do"                        => true,
        "/requirement/product_add"                       => true,
        "/requirement/development_deal"                  => true,
        "/requirement/development_reject"                => true,
        "/requirement/development_do"                    => true,
        "/requirement/development_finish"                => true,
        "/requirement/test_deal"                         => true,
        "/requirement/test_reject"                       => true,
        "/requirement/test_do"                           => true,
        "/requirement/test_finish"                       => true,
        "/aliyun_oss/file_manage"                        => true,
        "/channel_manage/admin_channel_manage"           => true,
        "/channel_manage/add_channel"                    => true,
        "/channel_manage/set_channel_id"                 => true,
        "/channel_manage/get_teacher_type_ref"           => true,
        "/channel_manage/update_channel_name"            => true,
        "/channel_manage/set_teacher_ref_type"           => true,
        "/channel_manage/zs_origin_list_new"             => true,

    ];

    static $dev_url_map = [
    ];
};
