/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/authority-manager_list_offline.d.ts" />



$(function(){

    var show_name_key="";
    show_name_key="account_name_"+g_adminid;

    function load_data(){
        if ($.trim($("#id_user_info").val()) != g_args.user_info ) {
            $.do_ajax("/user_deal/set_item_list_add",{
                "item_key" :show_name_key,
                "item_name":  $.trim($("#id_user_info").val())
            },function(){});
        }

        $.reload_self_page ({
            user_info         : $('#id_user_info').val(),
            has_question_user : $('#id_has_question_user').val(),
            cardid            :	$('#id_cardid').val(),
            account_role      : $('#id_account_role').val(),
            tquin             :	$('#id_tquin').val(),
            day_new_user_flag :	$('#id_day_new_user_flag').val(),
            del_flag          : $('#id_del_flag').val()
        });
    }

    // var is_offline_flag = $.get_action_str();
    // if(is_offline_flag == 'manager_list_offline'){
    //     $('.opt-set-passwd').css('display','none');
    // }
    // alert(url);



    $( "#id_user_info" ).autocomplete({
        source: "/user_deal/get_item_list?list_flag=1&item_key="+show_name_key,
        minLength: 0,
        select: function( event, ui ) {
            load_data();
        }
    });



    Enum_map.append_option_list("boolean",$("#id_day_new_user_flag"));
    Enum_map.append_option_list("account_role", $('#id_account_role'));
    $('#id_account_role').val(g_args.account_role);
    $("#id_user_info").val(g_args.user_info);
    $("#id_has_question_user").val(g_args.has_question_user);
    $("#id_del_flag").val(g_args.del_flag);
    $('#id_cardid').val(g_args.cardid);
    $('#id_tquin').val(g_args.tquin);

    $('#id_day_new_user_flag').val(g_args.day_new_user_flag);



    $("#id_add_manager").on("click",function(){
        var account = $("#id_account").val();
        var name = $("#id_real_name").val();
        var email = $("#id_email").val();
        var phone = $("#id_phone").val();
        var passwd = $("#id_passwd").val();
        $.ajax({
            url: '/authority/add_manager',
            type: 'POST',
            data: {
                'account' : account,
                'name'    : name,
                'email'   : email,
                'phone'   : phone,
                'passwd'  : passwd
            },
            dataType: 'json',
            success: function(data) {
                if (data['ret'] == 0) {
                    alert("插入成功");
                    window.location.reload();
                }else if(data['ret'] != 0){
                    alert(data['info']);
                }
            }
        });
    });

    $(".opt-del").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var uid = opt_data.uid;
        var del_flag =$("<select/>");
        Enum_map.append_option_list( "boolean", del_flag,true);
        del_flag.val(opt_data.del_flag);
        var arr=[
            ["uid",opt_data.uid] ,
            ["account",opt_data.account] ,
            [" 是否离职",del_flag]
        ];

        $.show_key_value_table("更改员工状态", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax('/authority/del_manager', {
                    'uid': opt_data.uid,
                    'del_flag': del_flag.val()
                });
            }
        });


    });

    $(".opt-set-passwd").on("click", function(){
        var $passwd=$("<input/>");
        var account=$(this).get_opt_data("account");
        var arr =[
            ["account", account ] ,
            ["passwd", $passwd]
        ];
        $passwd.val(account);

        $.show_key_value_table("新增小班课", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax('/authority/set_passwd', {
                    'account': account,
                    'passwd': $passwd.val()
                });
            }
        });
    });

    $("#id_fix_passwd").on("click",function(){
        var account = $(this).data("account");
        var new_passwd = $("#id_new_passwd").val();

        if(new_passwd == ""){
            alert("请输入新密码");
        }else{
            $.ajax({
                url: '/authority/set_passwd',
                type: 'POST',
                data: {
                    'account': account,
                    'passwd': new_passwd
                },
                dataType: 'json',
                success: function(data) {
                    if(data['ret'] != 0){
                        alert(data['info']);
                    }else{
                        window.location.href = "/authority/manager_list";
                    }
                }
            });
        }
    });

    $(".add_player").on("click", function(){
        var $account=$("<input/>");
        var $phone=$("<input/>");
        var $role=$("<select/>");
        var $account_role=$("<select/>");

        $.do_ajax("/user_deal/admin_group_list_js",{},function(resp){
            var str="";
            $(resp.data).each(function(){
                if (g_args.assign_groupid >0   ) {
                    if(g_args.assign_groupid == this.groupid ) {
                        str+="<option value="+this.groupid+"> " + this.group_name+ "</option>" ;
                    }
                }else{
                    str+="<option value="+this.groupid+"> " + this.group_name+ "</option>" ;
                }
            });
            $role.append(str);
            $role.val(38);
        });

        var need_account_role_list=null;

        if (g_args.assign_account_role>0) {
            need_account_role_list=[ g_args.assign_account_role ];
        }
        Enum_map.append_option_list("account_role", $account_role,true,need_account_role_list);

        var arr=[
            ["account",$account] ,
            ["电话", $phone],
            ["角色", $account_role],
            ["权限", $role ],
        ];
        $.show_key_value_table("新增用户", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                var account=$.trim($account.val());
                if (account.length <2) {
                    alert("账号名太短");
                    return ;
                }

                $.do_ajax("/authority/add_manager",{
                    'account': account,
                    'name': account,
                    'email': account+"@leoedu.cn",
                    'phone': $phone.val(),
                    'groupid': $role.val(),
                    'passwd': account,
                    'account_role': $account_role.val()
                });
            }
        });
    });


    //编辑lala
    $(".edit-manage").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var uid= opt_data.uid;
        var $phone=$("<input/> ").val(opt_data.phone);
        var $email=$("<input/>").val(opt_data.email);
        var $account_role=$("<select/>");
        var $seller_level=$("<select/>");
        var $become_full_member_flag=$("<select/>");
        var $day_new_user_flag=$("<select/>");
        var $name=$("<input/>").val(opt_data.name);
        var $tquin=$("<input/>").val(opt_data.tquin);
        var $cardid=$("<input/>").val(opt_data.cardid);
        var $wx_id=$("<input/>").val(opt_data.wx_id);
        var $up_adminid=$("<input/>").val(opt_data.up_adminid );
        //var $ytx_phone=$("<input/>").val(opt_data.ytx_phone );
        var need_account_role_list=null;
        if (g_args.assign_account_role>0) {
            need_account_role_list=[ g_args.assign_account_role ];
        }
        Enum_map.append_option_list("account_role", $account_role,true,need_account_role_list);
        Enum_map.append_option_list("seller_level", $seller_level,true);
        Enum_map.append_option_list("boolean", $day_new_user_flag,true);
        Enum_map.append_option_list("boolean", $become_full_member_flag,true);

        $account_role.val(opt_data.account_role);
        $seller_level.val(opt_data.seller_level);
        $day_new_user_flag.val(opt_data.day_new_user_flag);
        $become_full_member_flag.val(opt_data.become_full_member_flag);

        var arr=[
            ["uid",opt_data.uid] ,
            ["account",opt_data.account] ,
            ["姓名", $name],
            ["电话",$phone] ,
            ["邮件",$email] ,
            ["角色",$account_role] ,
            ["每天新例子",$day_new_user_flag] ,
            ["考勤卡id",$cardid] ,
            ["tq座席号/云通讯电话",$tquin],
            ["咨询师等级",$seller_level] ,
            ["微信号",$wx_id] ,
            ["上级",$up_adminid],
            ["转正",$become_full_member_flag]
        ];

        $.show_key_value_table("修改用户信息", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax('/user_deal/update_admin_info', {
                    'uid': opt_data.uid,
                    'phone': $phone.val(),
                    'name': $name.val(),
                    'day_new_user_flag': $day_new_user_flag.val(),
                    'cardid': $cardid.val(),
                    'account_role': $account_role.val(),
                    'tquin': $tquin.val(),
                    'email': $email.val(),
                    'seller_level': $seller_level.val(),
                    'up_adminid': $up_adminid.val(),
                    'become_full_member_flag': $become_full_member_flag.val(),
                    //'ytx_phone': $ytx_phone.val(),
                    'wx_id': $wx_id.val()
                });
            }
        },function(){
            $.admin_select_user($up_adminid,"admin",function(){}, true );
        });


    });

    // =====================

    $(".opt-power").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var uid= opt_data.uid;
        var show_list=[];
        if ($.get_action_str()=="manager_list_for_seller" ) {
            show_list=[57	, 38	, 74	, 77, 80	,];
        }
        var show_all_flag=($.get_action_str()=="manager_list");

        var permission  = opt_data["permission"];
        $.do_ajax("/authority/get_permission_list",{
            "permission" : permission
        },function(response){
            var data_list   = [];
            var select_list = [];
            $.each( response.data,function(){
                if (  show_all_flag || $.inArray(  parseInt( this["groupid"]),  show_list) != -1 ) {
                    data_list.push([this["groupid"], this["group_name"]  ]);
                }

                if (this["has_power"]) {
                    select_list.push (this["groupid"]) ;
                }

            });

            $(this).admin_select_dlg({
                header_list     : [ "id","名称" ],
                data_list       : data_list,
                multi_selection : true,
                select_list     : select_list,
                onChange        : function( select_list,dlg) {
                    $.do_ajax("/authority/set_permission",{
                        "uid": uid,
                        "groupid_list":JSON.stringify(select_list)
                    });
                }
            });
        }) ;



    });



    $(".opt_set_openid").on("click",function(){
        var opt_data=$(this).get_opt_data();

        $(this).admin_select_dlg_ajax({

            "opt_type" :  "select", // or "list"

            select_no_select_value  :   0, // 没有选择是，设置的值
            select_no_select_title  :   '未设置', // "未设置"
            select_primary_field : "openid",
            select_display       : "",

            "url"          : "/user_deal/get_wx_user_list",
            //其他参数
            "args_ex" : {
            },

            //字段列表
            'field_list' :[
                {
                    title:"姓名",
                    width :50,
                    field_name:"nickname"
                },{
                    title:"openid",
                    //width :50,
                    render:function(val,item) {
                        return item.openid;
                    }
                },{
                    title:"时间",
                    //width :50,
                    render:function(val,item) {
                        return item.update_time;
                    }
                }
            ] ,
            //查询列表
            filter_list:[
                [
                    {
                        size_class: "col-md-8" ,
                        title :"微信姓名",
                        'arg_name' :  "nickname"  ,
                        type  : "input"
                    }

                ]
            ],

            "auto_close"       : true,
            //选择
            "onChange" : function(val) {
                $.do_ajax("/user_deal/binding_wx_to_admin",{
                    id: opt_data.uid,
                    wx_openid: val
                });
                /*
                  $.do_ajax( "/seller_student/set_test_lesson_st_arrange_lessonid",{
                  "st_arrange_lessonid" :  val ,
                  "phone" :  phone
                  });

                */
            },
            //加载数据后，其它的设置
            "onLoadData"       : null
        });



    });

    $(".set-account-role").on("click", function(){
        var opt_data= $(this).get_opt_data();
        var id_account_role=$("<select/>");
        var id_creater_adminid=$("<input/>");

        Enum_map.append_option_list("account_role", id_account_role,true);

        if (opt_data.creater_adminid) {
            id_creater_adminid.val( opt_data.creater_adminid );
        }else{
            id_creater_adminid.val( 287 );
        }

        if ( opt_data.account_role) {
            id_account_role.val( opt_data.account_role);
        }else{
            id_account_role.val( 2);
        }

        var arr               = [
            [ "创建者", id_creater_adminid] ,
            [ "角色", id_account_role] ,
        ];

        $.show_key_value_table("设置角色", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {

                $.ajax({
                    url: '/authority/set_account_role',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        'uid'      : opt_data.uid ,
                        'account_role' : id_account_role.val() ,
                        'creater_adminid' : id_creater_adminid.val()
                    },
                    success: function(data) {
                        if(data.ret != -1){
                            window.location.reload();
                        }
                    }
                });
            }
        },function(){
            $.admin_select_user(id_creater_adminid,"admin" );
        });

    });
    if (g_args.assign_groupid) {
        $(".set-account-role").hide();
    }
    if ( window.location.pathname !="/authority/manager_list" ) {
        //$('.opt-power').hide();
    }
    if ( window.location.pathname =="/authority/manager_list_for_ass" ) {
        $(".add_player").hide();
    }


    $('.opt-change').set_input_change_event(load_data);

    $(".opt-login").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.do_ajax("/login/login_other",{
            "login_adminid" : opt_data.uid
        });

    });


    $(".opt-change-account").on("click",function(){
        var opt_data=$(this).get_opt_data();

        var uid = opt_data.uid;
        var $account= $("<input/>");
        $account.val( opt_data.account);
        var arr=[
            ["uid",opt_data.uid] ,
            ["account",$account] ,
        ];

        $.show_key_value_table("更改员工账号", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax('/user_deal/set_account', {
                    'uid': opt_data.uid,
                    "account": $account.val()
                });
            }
        });


    });



    $(" .opt-sync-kaoqin ").on("click",function(){
        var opt_data=$(this).get_opt_data();

        $.do_ajax('/user_deal/sync_kaoqin', {
            'adminid': opt_data.uid,
        });


    });



});
