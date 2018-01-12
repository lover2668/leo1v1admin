/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-refund_tongji_sys_operator.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
        $.reload_self_page ( {
        order_by_str : g_args.order_by_str,
        date_type_config:   $('#id_date_type_config').val(),
        date_type:  $('#id_date_type').val(),
        opt_date_type:  $('#id_opt_date_type').val(),
        start_time: $('#id_start_time').val(),
        end_time:   $('#id_end_time').val(),
        sys_operator      : $("#id_sys_operator").val(),
        account_role      : $("#id_account_role").val(),
        });
}
$(function(){


    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });


    $("#id_account_role").val(g_args.account_role);
    $("#id_sys_operator").val(g_args.sys_operator);

    $('.opt-change').set_input_change_event(load_data);
});
