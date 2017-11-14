interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	adminid:	number;
	uid:	number;
	user_name:	string;
	hand_get_adminid:	number;
	origin_ex:	string;
	global_tq_called_flag:	number;
	page_num:	number;
	page_count:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	id	:any;
	uid	:any;
	type	:any;
	create_time	:any;
	adminid	:any;
	old	:any;
	new	:any;
	global_tq_called_flag	:any;
	del_flag	:any;
	phone	:any;
	origin	:any;
	adminid_nick	:any;
	uid_nick	:any;
	del_flag_str	:any;
	global_tq_called_flag_str	:any;
}

/*

tofile: 
	 mkdir -p ../seller_student_new2; vi  ../seller_student_new2/seller_edit_log_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-seller_edit_log_list.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		adminid:	$('#id_adminid').val(),
		uid:	$('#id_uid').val(),
		user_name:	$('#id_user_name').val(),
		hand_get_adminid:	$('#id_hand_get_adminid').val(),
		origin_ex:	$('#id_origin_ex').val(),
		global_tq_called_flag:	$('#id_global_tq_called_flag').val()
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
	$('#id_adminid').val(g_args.adminid);
	$('#id_uid').val(g_args.uid);
	$('#id_user_name').val(g_args.user_name);
	$('#id_hand_get_adminid').val(g_args.hand_get_adminid);
	$('#id_origin_ex').val(g_args.origin_ex);
	$('#id_global_tq_called_flag').val(g_args.global_tq_called_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">adminid</span>
                <input class="opt-change form-control" id="id_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">uid</span>
                <input class="opt-change form-control" id="id_uid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">user_name</span>
                <input class="opt-change form-control" id="id_user_name" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">hand_get_adminid</span>
                <input class="opt-change form-control" id="id_hand_get_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">origin_ex</span>
                <input class="opt-change form-control" id="id_origin_ex" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">global_tq_called_flag</span>
                <input class="opt-change form-control" id="id_global_tq_called_flag" />
            </div>
        </div>
*/
