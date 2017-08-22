/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-agent_cash_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            cash:    $('#id_cash').val(),
            phone:    $('#id_phone').val(),
            type:    $('#id_type').val(),
            aid:    $('#id_aid').val()
        });
    }


    $('#id_phone').val(g_args.phone);
    $('#id_cash').val(g_args.cash);
    $('#id_type').val(g_args.type);
    $('#id_aid').val(g_args.aid);

    $("#id_add").on("click",function(){
        var $aid  = $("<input/>");
        var $cash = $("<input/>");
        var $type = $("<select><option value='0'>请选择</option><option value='1'>银行卡</option><option value='2'>支付宝</option></select>");

        var arr=[
            ["转介绍id",  $aid],
            ["提现金额",  $cash],
            ["提现类型",  $type],
        ];
        $.show_key_value_table("新增数据", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ajax_deal/agent_cash_add",{
                    "aid"  : $aid.val(),
                    "cash" : $cash.val(),
                    "type" : $type.val(),
                })
            }
        })
    });


    $(".opt-money-check").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var $check_money_flag = $("<select><option value='0'>请选择</option><option value='1'>通过</option><option value='0'>未通过</option></select>");
        var $check_money_desc = $("<textarea rows='' cols=''>");
        $check_money_flag.val(opt_data.check_money_flag);
        $check_money_desc.val(opt_data.check_money_desc);
        var arr=[
            ["财务审核",  $check_money_flag],
            ["财务审核说明",  $check_money_desc],
        ];

        $.show_key_value_table("财务确认", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ajax_deal/agent_cash_edit",{
                    "id":opt_data.id,
                    "check_money_flag" : $check_money_flag.val(),
                    "check_money_desc" : $check_money_desc.val()
                })
            }
        })
    });

    $(".opt-del").on("click",function(){
        var opt_data = $(this).get_opt_data();
        BootstrapDialog.confirm(
            "要删除id为:" + opt_data.id + "的数据吗？",
            function(val) {
                if (val) {
                    $.do_ajax("/ajax_deal/agent_cash_del", {
                        "id": opt_data.id
                    })
                }
            })
    });



    $('.opt-change').set_input_change_event(load_data);
});
