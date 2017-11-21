interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	is_all:	number;
	has_openid:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	teacherid	:any;
	nick	:any;
	phone	:any;
	wx_openid	:any;
	score	:any;
	create_time	:any;
	has_openid_str	:any;
	create_time_str	:any;
}

/*

tofile: 
	 mkdir -p ../tea_manage; vi  ../tea_manage/train_not_through_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage-train_not_through_list.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		is_all:	$('#id_is_all').val(),
		has_openid:	$('#id_has_openid').val()
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
	$('#id_is_all').val(g_args.is_all);
	$('#id_has_openid').val(g_args.has_openid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_all</span>
                <input class="opt-change form-control" id="id_is_all" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">has_openid</span>
                <input class="opt-change form-control" id="id_has_openid" />
            </div>
        </div>
*/
