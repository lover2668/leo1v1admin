/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-user_info_by_month.d.ts" />

$(function(){
   
    function load_data(){
        $.reload_self_page ( {

        });
    }




	$('.opt-change').set_input_change_event(load_data);



});
