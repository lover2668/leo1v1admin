interface GargsStatic {
	groupid:	number;
	show_flag:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	k1	:any;
	k2	:any;
	k3	:any;
	folder	:any;
	pid	:any;
	k_class	:any;
	class	:any;
	level	:any;
	has_power_flag	:any;
	url	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/power_group_edit.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-power_group_edit.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		groupid:	$('#id_groupid').val(),
		show_flag:	$('#id_show_flag').val()
		});
}
$(function(){


	$('#id_groupid').val(g_args.groupid);
	$('#id_show_flag').val(g_args.show_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">groupid</span>
                <input class="opt-change form-control" id="id_groupid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["groupid title", "groupid", "th_groupid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">show_flag</span>
                <input class="opt-change form-control" id="id_show_flag" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["show_flag title", "show_flag", "th_show_flag" ]])!!}
*/
