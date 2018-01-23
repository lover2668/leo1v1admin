///<reference path="../common.d.ts" />
///<reference path="../g_args.d.ts/stu_manage-student_lesson_learning_record.d.ts" />
function load_data(){
	  if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		    order_by_str : g_args.order_by_str,
		    sid:	g_args.sid,
		    // date_type_config:	$('#id_date_type_config').val(),
		    // date_type:	$('#id_date_type').val(),
		    // opt_date_type:	$('#id_opt_date_type').val(),
		    // start_time:	$('#id_start_time').val(),
		    // end_time:	$('#id_end_time').val(),
		    subject:	$('#id_subject').val(),
		    grade:	$('#id_grade').val(),
	      cw_status:	$('#id_cw_status').val(),
		    preview_status:	$('#id_preview_status').val(),
		    current_id:	$(".current").data("id"),
        start_date:	$('#id_start_date').val(),
		    end_date:	$('#id_end_date').val(),
		});

}

$(function(){

    window["g_load_data_flag"]=1;
    // $('#id_date_range').select_date_range({
    //     'date_type' : g_args.date_type,
    //     'opt_date_type' : g_args.opt_date_type,
    //     'start_time'    : g_args.start_time,
    //     'end_time'      : g_args.end_time,
    //     date_type_config : JSON.parse( g_args.date_type_config),
    //     onQuery :function() {
    //         load_data();
    //     }
    // });

    var get_arr_from_obj = function(objj){
        var arr = []
        for (var i in objj) {
            arr.push(parseInt(objj[i])); //属性
            //arr.push(object[i]); //值
        }       
        return arr;
    };
    var subject_list_arr =get_arr_from_obj(window["g_subject_list"]);
    var grade_list_arr =get_arr_from_obj(window["g_grade_list"]);
    Enum_map.append_option_list("subject",$("#id_subject"),false,subject_list_arr);
    Enum_map.append_option_list("grade",$("#id_grade"),false,grade_list_arr);


	  $('#id_grade').val(g_args.grade);
	  $('#id_subject').val(g_args.subject);
    $('#id_cw_status').val(g_args.cw_status);
	  $('#id_preview_status').val(g_args.preview_status);
    $('#id_start_date').val(g_args.start_date);
	  $('#id_end_date').val(g_args.end_date);
    //时间控件
	  $('#id_start_date').datetimepicker({
		    lang:'ch',
		    timepicker:false,
		    format:'Y-m-d',
	      onChangeDateTime :function(){
		       load_data();
        }
	  });
    
	  $('#id_end_date').datetimepicker({
		    lang:'ch',
		    timepicker:false,
		    format:'Y-m-d',
		    onChangeDateTime :function(){
		       load_data();
        }
	  });



    $("#id_search").on("click",function(){
        window["g_load_data_flag"] = 0;
        load_data();
        
    });
      

   
    $('.stu_tab04 td').on('click', function() {
        $(this).addClass('current');
        $(this).siblings().removeClass('current');
        $(this).siblings().css({
            "background-color":"white",
        });
        $(this).siblings().find("a").css({
            "color":"#000",
        });
        $(".current").css({
            "background-color":"#00E5EE",
        });
        $(".current a").css({
            "color":"white",
        });
        var current_id =  $(".current").data("id");
        if(current_id==5){
            $("#id_add_stu_score").parent().show();
        }else{
            $("#id_add_stu_score").parent().hide();
        }
        window["g_load_data_flag"] = 0;
        load_data();
       



        // var show_id = $(this).attr('data-id');
        // $(show_id).removeClass('hide');
        // $(this).siblings().each(function(){
        //     var hide_id = $(this).attr('data-id');
        //     $(hide_id).addClass('hide');
        // });
    });
    $('.stu_tab04 td').each(function(){
        var current_id = $(this).data("id");
        if(current_id==g_args.current_id){
            $(this).addClass('current');
            $(this).siblings().removeClass('current');
            $(this).siblings().css({
                "background-color":"white",
            });
            $(this).siblings().find("a").css({
                "color":"#000",
            });
            $(".current").css({
                "background-color":"#00E5EE",
            });
            $(".current a").css({
                "color":"white",
            });
            if(current_id==5){
                $("#id_add_stu_score").parent().show();
            }else{
                $("#id_add_stu_score").parent().hide();
            }

        }
    });
    var current_id =  $(".current").data("id");
    if(current_id==5){
        $("#id_add_stu_score").parent().show();
    }else{
        $("#id_add_stu_score").parent().hide();
    }
    $(".preview_table_flag,.lesson_table_flag,.performance_table_flag,.homework_table_flag").each(function(){
        var class_id =$(this).data("class_id");
        if(current_id==class_id){
            $(this).show();
        }else{
            $(this).hide(); 
        }
    });
   



    $(".current").css({
        "background-color":"#00E5EE",
    });
    $(".current a").css({
        "color":"white",
    });
   
    $("#id_cw_status,#id_preview_status").change(function(){
        window["g_load_data_flag"] = 0;
        load_data();
    });
   
    $('.opt-change').set_input_change_event(load_data);
    $('#id_grade').change(function(){
        var grade=$(this).val();
        var vv = $(this).find("option:selected").text();
        var htm = "<label class=\"fa fa-times\"></label>"+vv;
        if(grade==-1){
            $("#id_grade_show").hide();
        }else{
            $("#id_grade_show").html(htm);
            $("#id_grade_show").show();
        }
    });
    $('#id_subject').change(function(){
        var subject=$(this).val();
        var vv = $(this).find("option:selected").text();
        var htm = "<label class=\"fa fa-times\"></label>"+vv;
        if(subject==-1){
            $("#id_subject_show").hide();
        }else{
            $("#id_subject_show").html(htm);
            $("#id_subject_show").show();
        }
    });
    $('#id_start_date,#id_end_date').change(function(){
        var start=$("#id_start_date").val();
        var end=$("#id_end_date").val();
        var vv = start+"~"+end;
        var htm = "<label class=\"fa fa-times\"></label>"+vv;
        if(start=="" && end==""){
            $("#id_date_show").hide();
        }else{            
            $("#id_date_show").html(htm);
            $("#id_date_show").show();
        }


    });

    if(g_args.grade==-1){
        $("#id_grade_show").hide();
    }else{
        var vv = $("#id_grade").find("option:selected").text();
        var htm = "<label class=\"fa fa-times\"></label>"+vv;
        $("#id_grade_show").html(htm);
        $("#id_grade_show").show();
    }
    if(g_args.subject==-1){
        $("#id_subject_show").hide();
    }else{
        var vv = $("#id_subject").find("option:selected").text();
        var htm = "<label class=\"fa fa-times\"></label>"+vv;
        $("#id_subject_show").html(htm);
        $("#id_subject_show").show();
    }
    if(g_args.start_date=="" && g_args.end_date==""){
        $("#id_date_show").hide();
    }else{
        var vv = $("#id_start_date").val()+"~"+$("#id_end_date").val();
        var htm = "<label class=\"fa fa-times\"></label>"+vv;
        $("#id_date_show").html(htm);
        $("#id_date_show").show();
    }
    if(g_args.subject==-1){
        $("#id_subject_show").hide();
    }else{
        var vv = $("#id_subject").find("option:selected").text();
        var htm = "<label class=\"fa fa-times\"></label>"+vv;
        $("#id_subject_show").html(htm);
        $("#id_subject_show").show();
    }


    $("#id_grade_show").on("click",function(){
        $(this).hide();
        $("#id_grade").val(-1);
        window["g_load_data_flag"] = 0;
        load_data();
        
    });

    $("#id_subject_show").on("click",function(){
        $(this).hide();
        $("#id_subject").val(-1);
        window["g_load_data_flag"] = 0;
        load_data();
    });
    $("#id_date_show").on("click",function(){
        $(this).hide();
        $("#id_start_date").val("");
        $("#id_end_date").val("");
        window["g_load_data_flag"] = 0;
        load_data();
    });


    $(".show_cw_content,.show_issue_content").on("click",function(){
        var url = $(this).data("url");
        $.wopen(url); 
    });
    $("#id_show_all").on("click",function(){
        alert(111);
    });
    $(".show_lesson_detail").on("click",function(){
        var lessonid = $(this).data("lessonid");
        alert(lessonid);
    });
    $(".show_login_info").on("click",function(){
         var lessonid = $(this).data("lessonid");
         var userid = $(this).data("userid");
         var role = $(this).data("role");
        var title = "登录日志";
        var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>角色</td><td>进出</td><td>时间</td></tr></table></div>");

        $.do_ajax('/ajax_deal2/get_lesson_opt_detail_info',{
            "lessonid" : lessonid,
            "userid"   : userid
        },function(resp) {
            var list = resp.data;
            $.each(list,function(i,item){              
                html_node.find("table").append("<tr><td>"+role+"</td><td>"+item["opt_type_str"]+"</td><td>"+item["opt_time_str"]+"</td></tr>");
            });
        });

        var dlg=BootstrapDialog.show({
            title:title, 
            message :  html_node   ,
            closable: true, 
            buttons:[{
                label: '返回',
                cssClass: 'btn',
                action: function(dialog) {
                    dialog.close();

                }
            }],
            onshown:function(){
                
            }

        });

        dlg.getModalDialog().css("width","600px");


    });

    $(".show_stu_score_detail").on("click",function(){
        var effect = $(this).data("effect"); 
        var quality = $(this).data("quality"); 
        var interact = $(this).data("interact"); 
        var stability = $(this).data("stability");
        var title = "打分详情";
        var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>类型</td><td>得分</td></tr><tr><td>上课效果</td><td>"+effect+"</td></tr><tr><td>课件质量</td><td>"+quality+"</td></tr><tr><td>课堂互动</td><td>"+interact+"</td></tr><tr><td>系统稳定性</td><td>"+stability+"</td></tr></table></div>");     

        var dlg=BootstrapDialog.show({
            title:title, 
            message :  html_node   ,
            closable: true, 
            buttons:[{
                label: '返回',
                cssClass: 'btn',
                action: function(dialog) {
                    dialog.close();

                }
            }],
            onshown:function(){
                
            }

        });

        dlg.getModalDialog().css("width","400px");


    });

    $(".show_lesson_video").on("click",function(){
        var lessonid = $(this).data("lessonid");
        $.do_ajax( "/common/encode_text",{
            "text" : lessonid
        }, function(ret){
            // BootstrapDialog.alert("对外链接 : http://"+ window.location.hostname + "/tea_manage/show_lesson_video?lessonid=" + ret.text  );
            $.wopen("http://"+ window.location.hostname + "/tea_manage/show_lesson_video?lessonid=" + ret.text);
        });
     
                         
    });





});
