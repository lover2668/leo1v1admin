$(function(){
    $('#id_type').val(g_args.type);
    $('#id_start_time').val(g_start_time);
    $('#id_end_time').val(g_end_time);
    $("#id_revisit_status").val(g_args.status);
    $("#id_phone").val(g_args.phone);

    $("#id_stu_search").on ("click", function( ){
		load_data();
	});
	
	$("#id_phone").on ("keypress", function(e){
        if(e.keyCode==13){
		    load_data();
        }
	});
    
	$(".opt-query-change" ).on( "change",function(){
		load_data();
	});
    
    //时间控件
	$('#id_start_time').datetimepicker({
		lang:'ch',
		timepicker:false,
		onChangeDateTime :function(){
			load_data();
		},
		format:'Y-m-d'
	});
	
	$('#id_end_time').datetimepicker({
		lang:'ch',
		timepicker:false,
		onChangeDateTime :function(){
			load_data();
		},
		format:'Y-m-d'
	});
	
	//点击进入个人主页
	$('.opt-user').on('click',function(){
		var userid=$(this).parent().data("userid");
		var stu_nick=$(this).parent().data("stu_nick");
		wopen('/stu_manage?sid='+userid+'&nick='+stu_nick+"&source=audition");
	});

    //点击进入排课
	$('.opt-lesson').on('click',function(){
		var userid   = $(this).parent().data("userid");
		var stu_nick = $(this).parent().data("stu_nick");
		wopen( '/stu_manage/lesson_custom?sid='+userid+'&nick='+stu_nick+"&return_url="+   encodeURIComponent(window.location.href));
	});

	function load_data(){
        var type       = $("#id_type").val();
        var start_time = $("#id_start_time").val();
        var end_time   = $("#id_end_time").val();
        var phone      = $("#id_phone").val();
        var status     = $("#id_revisit_status").val();
		var url        = "/user_manage/audition?type="+type+"&start_time="+start_time+"&end_time="+end_time+"&phone="+phone+"&status="+status;
		window.location.href=url;
	}

    $('.opt-add_revisit_time').on('click',function(){
		var userid = $(this).parent().data("userid");
        var html_node = $('<div></div>').html(dlg_get_html_by_class('dlg-add_revisit_time'));
        
	    html_node.find('.update_revisit_time').datetimepicker({
		    lang:'ch',
		    timepicker:false,
		    format:'Y-m-d'
	    });

        BootstrapDialog.show({
            title: '添加下次回访时间',
            message : html_node,
            closable: true, 
            closeByBackdrop:false,
            buttons: [
                {
                    label: '确认',
                    cssClass: 'btn-primary',
                    action : function(dialog) {
                        var update_revisit_time= html_node.find(".update_revisit_time").val();
                        
                        $.ajax({
			                type     : "post",
			                url      : "/user_will/update_revisit_time",
			                dataType : "json",
			                data : {
                                "userid"       : userid,
                                "revisit_time" : update_revisit_time
                            },
			                success : function(result){
                                window.location.reload();
			                }
                        });
                        dialog.close();
                    }
                },{
                    label: '清除回访',
                    cssClass: 'btn-warning',
                    action: function(dialog) {
                        $.ajax({
                            url:  '/user_will/clear_revisit_time',
                            type: 'POST',
                            data: {
                                'userid':userid
                            },
                            dataType: 'json',
                            success: function(result){
                                window.location.reload();
                            }
                        });
                    }
                },{
                    label: '取消',
                    cssClass: 'btn',
                    action: function(dialog) {
                        dialog.close();
                    }
                }]
        });
    });

    
	//init  input data
	if (g_phone!=""){
		$("#id_phone").val(g_phone);
		$("#id_phone_title").hide();
		$("#id_phone").show();
	}
    
    $(".opt-update_status").on ("click", function(){
        var userid = $(this).parent().data("userid");
		var status = $(this).parent().data("status");
        
        var html_node=$('<div></div>').html(dlg_get_html_by_class('dlg-update_user_info'));
        html_node.find(".update_user_status").val(status);

        BootstrapDialog.show({
            title: '修改用户状态',
            message : html_node,
            closable: false, 
            buttons: [{
                label: '返回',
                action: function(dialog) {
                    dialog.close();
                }
            }, {
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
		            var status = html_node.find(".update_user_status").val();
		            $.ajax({
			            type     :"post",
			            url      :"/stu_manage/set_user_status",
			            dataType :"json",
			            data     :{
                            "userid":userid
                            ,"status":status
                        },
			            success  : function(result){
                            if(result['ret'] != 0){
                                alert(result['info']);
                            }else{
                                window.location.reload();
                            }
			            }
		            });
                }
            }]
        }); 
    });
});
