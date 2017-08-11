interface GargsStatic {
	orderid:	number;
	aid:	number;
	pid:	number;
	p_price:	number;
	ppid:	number;
	pp_price:	number;
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
	orderid	:any;
	pid	:any;
	p_price	:any;
	ppid	:any;
	pp_price	:any;
	create_time	:any;
	aid	:any;
	phone	:any;
	nickname	:any;
	p_phone	:any;
	p_nickname	:any;
	pp_phone	:any;
	pp_nickname	:any;
}

/*

tofile: 
	 mkdir -p ../agent; vi  ../agent/agent_order_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/agent-agent_order_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			orderid:	$('#id_orderid').val(),
			aid:	$('#id_aid').val(),
			pid:	$('#id_pid').val(),
			p_price:	$('#id_p_price').val(),
			ppid:	$('#id_ppid').val(),
			pp_price:	$('#id_pp_price').val()
        });
    }


	$('#id_orderid').val(g_args.orderid);
	$('#id_aid').val(g_args.aid);
	$('#id_pid').val(g_args.pid);
	$('#id_p_price').val(g_args.p_price);
	$('#id_ppid').val(g_args.ppid);
	$('#id_pp_price').val(g_args.pp_price);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">orderid</span>
                <input class="opt-change form-control" id="id_orderid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">aid</span>
                <input class="opt-change form-control" id="id_aid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">pid</span>
                <input class="opt-change form-control" id="id_pid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">p_price</span>
                <input class="opt-change form-control" id="id_p_price" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">ppid</span>
                <input class="opt-change form-control" id="id_ppid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">pp_price</span>
                <input class="opt-change form-control" id="id_pp_price" />
            </div>
        </div>
*/
