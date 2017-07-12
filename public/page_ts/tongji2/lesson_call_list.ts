/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji2-lesson_call_list.d.ts" />

function load_data(){
    $.reload_self_page ( {
        date_type:	$('#id_date_type').val(),
        opt_date_type:	$('#id_opt_date_type').val(),
        start_time:	$('#id_start_time').val(),
        end_time:	$('#id_end_time').val(),
        seller_groupid_ex:$('#id_seller_groupid_ex').val(),
    });
}

$(function(){
    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        'stu_phone'      : g_args.stu_phone,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });
    $('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);
    $("#id_seller_groupid_ex").init_seller_groupid_ex(g_adminid_right);
    $('.opt-call-phone').on('click',function(){
        var opt_data=$(this).get_opt_data();
        $.wopen("/tq/get_list_by_phone?phone="+opt_data.stu_phone);
    });

    $('.opt-change').set_input_change_event(load_data);
});
