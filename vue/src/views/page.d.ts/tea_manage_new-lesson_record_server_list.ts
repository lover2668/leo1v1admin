interface self_Args {
	page_num:	number;
	page_count:	number;
	order_by_str:	string;
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	record_audio_server1:	string;
	xmpp_server_name:	string;
	lesson_type:	string;//枚举列表: \App\Enums\Econtract_type
 	subject:	string;//枚举列表: \App\Enums\Esubject
 	userid:	number;

}
interface self_RowData {

}
export  {self_RowData , self_Args  }
/*
tofile:
	 mkdir -p ../tea_manage_new; vi  ../tea_manage_new/lesson_record_server_list.ts

import Vue from 'vue'
import Component from 'vue-class-component'
import vtable from "../../components/vtable"
import {self_RowData, self_Args } from "../page.d.ts/tea_manage_new-lesson_record_server_list"
// @Component 修饰符注明了此类为一个 Vue 组件.
@Component({
  // 所有的组件选项都可以放在这里.
  template:  require("./lesson_record_server_list.html" ),
})
export default class extends vtable {

  get_args() :self_Args  {return  this.get_args_base();}
  data_ex() {
    //扩展的 data  数据
    var me=this;
    var field_list=[{
      field_name: "title",
      "title": "说明",
    },{
      field_name: "admin_nick",
      "order_field_name": "admin_nick",
      "title": "昵称",
      "default_display":  false,
      render:function(value, item:self_RowData ,index){
        return "<a class=\"fa btn\" >"+item.admin_nick+"</a>" ;
      }
    },{
      field_name: "auth_flag_str",
      "title": "管理员",
      "order_field_name": "auth_flag",
      need_power: "auth_flag",
      render:function(value, item:self_RowData ,index){
        return "<a class=\"fa btn\" >"+value+"</a>" ;
      }
    }];
    var  row_opt_list =[{
      face_icon: "fa-edit",
      on_click: me.opt_edit ,
      "title": "编辑",
    },{
      face_icon: "fa-times",
      "title": "删除",
    }];

    return {
      "table_config":  {
        "field_list": field_list,
        "row_opt_list": row_opt_list,
      }
    }
  }
  opt_edit( e:MouseEvent, opt_data: self_RowData ){
    alert(JSON.stringify( opt_data));
  }

  query_init( $header_query_info): void{
    console.log("init_query");
    var me =this;
		$.admin_date_select ({
		'join_header'  : $header_query_info,
		'title' : "时间",
		'date_type' : this.get_args().date_type,
		'opt_date_type' : this.get_args().opt_date_type,
		'start_time'    : this.get_args().start_time,
		'end_time'      : this.get_args().end_time,
		date_type_config : JSON.parse(this.get_args().date_type_config),
		as_header_query :true,
		});

	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "record_audio_server1" ,
		"placeholder" : "回车查询", 
		"length_css" : "col-xs-12 col-md-3", 
		"title"        :  "record_audio_server1",
		"select_value" : this.get_args().record_audio_server1,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "xmpp_server_name" ,
		"placeholder" : "回车查询", 
		"length_css" : "col-xs-12 col-md-3", 
		"title"        :  "xmpp_server_name",
		"select_value" : this.get_args().xmpp_server_name,
	});
	$.admin_enum_select({
		'join_header'  : $header_query_info,
"enum_type"    : "contract_type",
"field_name" : "lesson_type",
"title" : "lesson_type",
"select_value" : this.get_args().lesson_type,
		"multi_select_flag"     : true,
		"btn_id_config"     : {},
	});

	$.admin_enum_select({
		'join_header'  : $header_query_info,
"enum_type"    : "subject",
"field_name" : "subject",
"title" : "subject",
"select_value" : this.get_args().subject,
		"multi_select_flag"     : true,
		"btn_id_config"     : {},
	});

	$.admin_ajax_select_user({
		'join_header'  : $header_query_info,
		"user_type"    : "student",
		"field_name"    : "userid",
		"title"        :  "userid",
		"select_value" : this.get_args().userid,
	});

  }
}
*/