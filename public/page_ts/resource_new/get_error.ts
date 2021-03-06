/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/resource_new-get_error.d.ts" />

function load_data(){
  if ( window["g_load_data_flag"]) {return;}
  var res_type = 0;
    if($('#id_use_type').val() == 1){
        if( $('#id_resource_type').val() >7) {
            res_type = 1;
        } else {
            res_type = $('#id_resource_type').val();
        }
    } else if ($('#id_use_type').val() == 2){
        res_type = 9;
    } else {
        res_type = 8;
    }
    $.reload_self_page ( {
    order_by_str : g_args.order_by_str,
    use_type:	$('#id_use_type').val(),
    resource_type:	$('#id_resource_type').val(),
    subject:	$('#id_subject').val(),
    grade:	$('#id_grade').val(),
    tag_one:	$('#id_tag_one').val(),
    tag_two:	$('#id_tag_two').val(),
    tag_three:	$('#id_tag_three').val(),
    tag_four:	$('#id_tag_four').val(),
    tag_five:	$('#id_tag_five').val(),
    file_title:	$('#id_file_title').val(),
    date_type_config:   $('#id_date_type_config').val(),
        date_type:  $('#id_date_type').val(),
        opt_date_type:  $('#id_opt_date_type').val(),
        start_time: $('#id_start_time').val(),
        end_time:   $('#id_end_time').val(),
        error_type: $('#id_error_type').val(),
    sub_error_type: $('#id_sub_error_type').val(),
    file_id:    $('#id_file_id').val(),
    });
}
$(function(){
	  $('#id_date_range').select_date_range({
        'date_type'     : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });

    //获取学科化标签
    var get_sub_grade_tag = function(subject,grade,booid,resource_type,season_id,obj,opt_type){
        obj.empty();
        //console.log(season_id);
        $.ajax({
            type     : "post",
            url      : "/resource/get_sub_grade_book_tag",
            dataType : "json",
            data : {
                'subject' : subject,
                'grade'   : grade,
                'bookid'  : booid,
                'resource_type' : resource_type,
                'season_id'  : season_id
            } ,
            success : function(result){
                if(result.ret == 0){
                    obj.empty();
                    obj.parent().find('span.tag_warn').remove();
                    //console.log(result);
                    var tag_info = result.tag;

                    if($(tag_info).length == 0) {
                        if(opt_type == 1){
                            if( subject > 0 && grade > 0){
                                obj.append('<option value="-1">暂无标签</option>');
                            }else{
                                obj.append('<option value="-1">资源类型、科目和年级是必选</option>');
                                $('#id_tag_four').css({'color':"#a2a2a2"});
                            }
                        } else {
                            obj.after('<span class="tag_warn" style="color:red;margin-left:8px">暂时未添加标签!</span>');
                        }
                    } else {
                        if(opt_type == 1){
                           var tag_str = '<option value="-1">全部</option>';
                        }else{
                            var tag_str = '';
                        }

                        $.each($(tag_info),function(i,item){
                            tag_str = tag_str + '<option value='+item.id+'>'+item.tag+'</option>';
                        });
                        obj.append(tag_str);
                        if(opt_type == 1){
                            obj.val(g_args.tag_four);
                        }
                    }
                } else {
                    alert(result.info);
                }
            }
        });
    }

    var get_province = function(obj,is_true){
        if (is_true == true){
            var pro = '<option value="0">[全部]</option>';
        } else {
            var pro = '<option value="-1">[全部]</option>';
        }
        $.each(ChineseDistricts[86],function(i,val){
            pro = pro + '<option value='+i+'>'+val+'</option>'
        });
        $(obj).empty();
        $(obj).append(pro);

    }

    var get_city = function(obj,city_num, is_true){
        if (is_true == true){
            var pro = '<option value="0">[全部]</option>';
        } else {
            var pro = '<option value="-1">[全部]</option>';
        }
        if(city_num > 0){
            $.each(ChineseDistricts[city_num],function(i,val){
                pro = pro + '<option value='+i+'>'+val+'</option>'
            });
        }
        $(obj).empty();
        $(obj).append(pro);
    }
    Enum_map.append_option_list("resource_error",$('#id_error_type'));
    Enum_map.append_option_list("use_type", $("#id_use_type"),true,[1,2]);
    $('#id_use_type').val(g_args.use_type);



    if(g_args.use_type == 1){
        Enum_map.append_option_list("resource_type", $("#id_resource_type"),true,[1,2,3,4,5,6,7]);
        $('#id_resource_type').val(g_args.resource_type);
    } else if (g_args.use_type == 2){
        Enum_map.append_option_list("resource_type", $("#id_resource_type"),true,[9]);
        $('#id_resource_type').val(9);
    } else {
        Enum_map.append_option_list("resource_type", $("#id_resource_type"),true,[8]);
        $('#id_resource_type').val(8);
    }

    if(tag_one == 'region_version'){
        Enum_map.append_option_list(tag_one, $("#id_tag_one"), false, book);
    } else if(tag_one != ''){
        Enum_map.append_option_list(tag_one, $("#id_tag_one"));
    }else{
        $("#id_tag_one").append('<option value="-1">全部</option>');
    }

    if(tag_two != ''){
        Enum_map.append_option_list(tag_two, $("#id_tag_two"));
    } else {
        $("#id_tag_two").append('<option value="-1">全部</option>');
    }
    if(tag_three != ''){
        Enum_map.append_option_list(tag_three, $("#id_tag_three"));
    } else {
        $("#id_tag_three").append('<option value="-1">全部</option>');
    }

    if(tag_four != ''){
        Enum_map.append_option_list(tag_four, $("#id_tag_four"));
    } else {
        if( parseInt(g_args.resource_type) == '' || parseInt(g_args.resource_type) == 1 || parseInt(g_args.resource_type) == 3 ){
            //console.log(g_args.resource_type);
            $("#id_tag_four").append('<option value="-1">先选择科目和年级</option>');
        }else{
            $("#id_tag_four").append('<option value="-1">全部</option>');
        }
    }

    if(tag_five != ''){
        Enum_map.append_option_list(tag_five, $("#id_tag_five"));
    } else {
        $("#id_tag_five").append('<option value="-1">全部</option>');
    }

    if(is_teacher == 1){
        Enum_map.append_option_list("subject", $("#id_subject"),true, my_subject);
        Enum_map.append_option_list("grade", $("#id_grade"),true, my_grade);
        if( g_args.subject == -1 || g_args.subject == ''){
            $("#id_subject").val(my_subject[0]);
        }else{
            $('#id_subject').val(g_args.subject);
        }
        if( g_args.grade == -1 || g_args.grade == ''){
            $("#id_grade").val(my_grade[0]);
        }else{
            $("#id_grade").val(g_args.grade);
        }
    }else{
        Enum_map.append_option_list("subject", $("#id_subject"),false, my_subject);
        Enum_map.append_option_list("grade", $("#id_grade"),false, my_grade);
        $('#id_subject').val(g_args.subject);
        $('#id_grade').val(g_args.grade);
    }

    $('#id_tag_one').val(g_args.tag_one);

    if($('#id_resource_type').val() == 3 || $('#id_resource_type').val() == 1 ){
        var season_default = -1;
        if( g_args.tag_two > 0 ){
            season_default = g_args.tag_two
        }
        get_sub_grade_tag($('#id_subject').val(), $('#id_grade').val(),$('#id_tag_one').val(),$('#id_resource_type').val(),season_default,$('#id_tag_four'), 1);
    } else if($('#id_resource_type').val() == 6) {
        get_province($('#id_tag_three'));
        if($('.right-menu').length>0){
            $('.right-menu').each(function(){

                var province_id = parseInt($(this).find('.province').text());
                if( parseInt(province_id) != 0 ){
                    var province = ChineseDistricts['86'][province_id];
                    $(this).find('.province').text(province);
                }else{
                    $(this).find('.province').text('全部');
                }

                var city_id = parseInt($(this).find('.city').text());
                if( parseInt(city_id) != 0 ){
                    var city = ChineseDistricts[province_id][city_id];
                    $(this).find('.city').text(city);
                }else{
                    $(this).find('.city').text('全部');
                }

            })
        }
    } else {
        //$("#id_tag_four").append('<option value="-1">全部</option>');
    }

    $('#id_tag_three').val(g_args.tag_three);

    var city_num = $('#id_tag_three').val();
    if($('#id_resource_type').val() == 6 && city_num != -1){
        get_city($('#id_tag_four'), city_num);
    }

    $('#id_tag_two').val(g_args.tag_two);
    $('#id_tag_four').val(g_args.tag_four);
    $('#id_tag_five').val(g_args.tag_five);
    $('#id_file_title').val(g_args.file_title);
    $('#id_error_type').val(g_args.error_type);
    $('#id_file_id').val(g_args.file_id);
    $("#id_select_all").on("click",function(){
        $(".opt-select-item").iCheck("check");
    });

    $("#id_select_other").on("click",function(){
        $(".opt-select-item").each(function(){
            var $item=$(this);
            if ($item.iCheckValue() ) {
                $item.iCheck("uncheck");
            }else{
                $item.iCheck("check");
            }
        } );
    });

    var get_book = function(bookid,obj){

        var resource_type = $('.resource').val();
        var subject = $('.subject').val();
        var grade = $('.grade').val();

        $.ajax({
            type     : "post",
            url      : "/resource/get_resource_type_js",
            dataType : "json",
            data : {
                'resource_type' : resource_type,
                'subject'       : subject,
                'grade'         : grade,
            } ,
            success   : function(result){
                if(result.ret == 0){
                    obj.empty();
                    obj.next('p').remove();
                    var agree_book = result.book;
                    if(agree_book.length == 0) {
                        obj.after('<p style="color:red;">该资源类型、科目、年级下暂无开放的教材版本!</p>');
                    } else {
                        //console.log(bookid);
                        Enum_map.append_option_list("region_version",obj,true,agree_book);
                        if(bookid != 0 && bookid != -1){
                            obj.val(bookid);
                        }else{
                            obj.val(agree_book[0]);
                        }
                    }
                } else {
                    alert(result.info);
                }
            }
        });
    }

    //预览讲义
    $('.opt-look').click(function(){
        var id = $(this).data('file_id');
        console.log(id);
        var newTab=window.open('about:blank');
        do_ajax('/resource/tea_look_resource',{'tea_res_id':id,'tea_flag':0},function(ret){
            console.log(ret);
            if(ret.ret == 0){
                $('.look-pdf').show();
                $('.look-pdf-son').mousedown(function(e){
                    if(e.which == 3){
                        return false;
                    }
                });
                console.log(ret.url);
                newTab.location.href = ret.url;
            } else {
                BootstrapDialog.alert(ret.info);
            }
        });
    })

    var opt_look = function(data_obj){
        var id = data_obj.data('file_id');
        var newTab=window.open('about:blank');
        do_ajax('/resource/tea_look_resource',{'tea_res_id':id,'tea_flag':0},function(ret){
            if(ret.ret == 0){
                $('.look-pdf').show();
                $('.look-pdf-son').mousedown(function(e){
                    if(e.which == 3){
                        return false;
                    }
                });
                console.log(ret.url);
                newTab.location.href = ret.url;
            } else {
                BootstrapDialog.alert(ret.info);
            }
        });
    };

    $('.opt-del').on('click', function(){
        do_del();
    });

    var add_file = function (resource_id, file, res, use_type){
        $.ajax({
            type     : "post",
            url      : "/resource/add_file",
            dataType : "json",
            data : {
                'resource_id'   : resource_id,
                'file_title'    : file.name,
                'file_type'     : file.type,
                'file_size'     : file.size,
                'file_hash'     : res.hash,
                'file_link'     : res.key,
                'file_use_type' : use_type,
            } ,

            success   : function(result){
                if(result.ret == 0){
                    window.onbeforeunload=function(){};
                    //window.location.reload();
                } else {
                    alert(result.info);
                }
            }
        });
    };

    var add_multi_file = function (data){
        $.ajax({
            type     : "post",
            url      : "/resource/add_multi_file",
            dataType : "json",
            data : data,
            success   : function(result){
                if(result.ret == 0){
                    window.onbeforeunload=function(){};
                    //window.location.reload();
                } else {
                    alert(result.info);
                }
            }
        });
    };

    $('.opt-select-item').on('click',function(){
        if( $(this).iCheckValue()){
            var resource_id = $(this).data('id');
            $('.common-table tbody tr').each(function(){
                var other_id = $(this).find('.opt-select-item').data('id');
                if( other_id == resource_id ){
                    //console.log(other_id);
                    $(this).find('.opt-select-item').iCheck("check");
                }
            });
        }else{
            var resource_id = $(this).data('id');
            $('.common-table tbody tr').each(function(){
                var other_id = $(this).find('.opt-select-item').data('id');
                if( other_id == resource_id ){
                    //console.log(other_id);
                    $(this).find('.opt-select-item').iCheck("uncheck");
                }
            });
        }
    });

    var do_del = function(){
        var res_id_list = [],file_id_list = [];
        $('.opt-select-item').each(function(){
            if( $(this).iCheckValue()){
                res_id_list.push( $(this).data('id') );
                file_id_list.push( $(this).data('file_id') );
            }
        });

        if(res_id_list.length == 0) {
            BootstrapDialog.alert('请先选择文件！');
        } else {

            var res_id_info  = JSON.stringify(res_id_list);
            var file_id_info = JSON.stringify(file_id_list);
            if( confirm('若删除，则会同时删除与之相关联的其他文件,确定要删除？') ){
                $.ajax({
                    type    : "post",
                    url     : "/resource/del_or_restore_resource",
                    dataType: "json",
                    data    : {
                        "type"        : 3,
                        "res_id_str"  : res_id_info,
                        "file_id_str" : file_id_info,
                    },
                    success : function(result){
                        if(result.ret == 0){
                            window.location.reload();
                        }
                    }
                });
            };
        }
    };

    var re_name = function(obj){
        var id_file_title = $("<input />");
        id_file_title.val(obj.data('file_title'));
        var arr= [
            ["文件名称", id_file_title],
        ];

        $.show_key_value_table('重命名', arr,{
            label    : '确认',
            cssClass : 'btn-info',
            action   : function() {
                $.ajax({
                    type     : "post",
                    url      : "/resource/rename_resource",
                    dataType : "json",
                    data : {
                        'file_id'     : obj.data('file_id'),
                        'resource_id' : obj.data('resource_id'),
                        'file_title'  : id_file_title.val(),
                    } ,
                    success  : function(result){
                        if(result.ret == 0){
                            window.location.reload();
                        } else {
                            alert(result.info);
                        }
                    }
                });

            }
        },null,false,600);
    };

    var re_upload = function(resource_id,file_id, file_use_type, ex_num,eid){

        if(file_use_type == 3){
            var allow_str = 'mp4,pdf,mp3,MP3,MP4,PDF';
        } else {
            var allow_str = 'pdf,PDF';
        }
        //重新上传
        multi_upload_file('',false,true,'upload_flag',1,'',function(up,file) {
            $('.opt_process').show();
        },function(up, file, info) {
            var res = $.parseJSON(info.response);
            if( info.status == 200){
                $.ajax({
                    type     : "post",
                    url      : "/resource/reupload_resource",
                    dataType : "json",
                    data : {
                        'resource_id'   : resource_id,
                        'file_id'       : file_id,
                        'file_title'    : file.name,
                        'file_type'     : file.type,
                        'file_size'     : file.size,
                        'file_hash'     : res.hash,
                        'file_link'     : res.key,
                        'file_use_type' : file_use_type,
                        'ex_num'        : ex_num,
                        "is_wx"         : 1,
                        "id"            : eid,
                    } ,
                    success   : function(result){
                        if(result.ret == 0){
                            // window.location.reload();
                        } else {
                            alert(result.info);
                        }
                    }
                });
            }
        }, allow_str,'fsUploadProgress');
    };

    var menu_hide = function(){
        $('#contextify-menu').hide();
        return $('#contextify-menu');
    };

    var get_edit_list = function(obj){
        $("<div></div>").admin_select_dlg_ajax({
            "opt_type" :  "list", // or "list"
            "url"      : "/resource/get_list_by_resource_id_js",
            //其他参数
            "args_ex" : {
                resource_id   : obj.data('resource_id'),
                file_use_type : obj.data('file_use_type'),
                ex_num        : obj.data('ex_num'),
            },
            //字段列表
            'field_list' :[
                {
                title:"时间",
                render:function(val,item) {
                    return item.create_time;
                }
            },{
                title:"操作人",
                render:function(val,item) {
                    return item.nick ;
                }
            },{
                title:"类型",
                render:function(val,item) {
                    return item.visit_type_str;
                }
            }
            ],
            filter_list: [],
            "auto_close"       : true,
            //选择
            "onChange"         : null,
            //加载数据后，其它的设置
            "onLoadData"       : null,
        });

    };

    var resource_detail = function(obj){

        var arr= [
            ["角色", obj.data('use_type_str')],
            ["资源类型", obj.data('resource_type_str')],
            ["科目", obj.data('subject_str')],
            ["年级", obj.data('grade_str')],
            [obj.data('tag_one_name'), obj.data('tag_one_str')],
            [obj.data('tag_two_name'), obj.data('tag_two_str')],
            [obj.data('tag_three_name'), obj.data('tag_three_str')],
            [obj.data('tag_four_name'), obj.data('tag_four_str')],
            ["文件名称", obj.data('file_title')],
            ["文件信息",obj.data('file_use_type_str')],
            ["文件大小", obj.data('file_size')+'M'],
        ];
        $.show_key_value_table('文件详情', arr,false,function(){
            $('.bootstrap-dialog-message td').each(function(i){
                if($(this).text() == ''){
                    $(this).parent().hide();
                }
            });
        },false,600);
    };

    //右键自定义
    var options = {items:[

        {text: '上传新版本',onclick: function() {
            menu_hide();
        }, id:'upload_flag'},
        {text: '操作记录', onclick: function() {
            var data_obj = menu_hide();
            get_edit_list(data_obj);
        }},
        {text: '文件详情', onclick: function() {
            var data_obj = menu_hide();
            resource_detail(data_obj);
        }},
        {text: '预览讲义', onclick: function() {
            var data_obj = menu_hide();
            opt_look(data_obj);
        }},
    ],before:function(){
        var resource_id   = $(this).attr('resource_id');
        var file_id       = $(this).attr('file_id');
        var ex_num        = $(this).attr('ex_num');
        var file_use_type = $(this).attr('file_use_type');
        var eid           = $(this).attr('eid');
        re_upload(resource_id, file_id, file_use_type, ex_num,eid);
        //选中标记
        $(".opt-select-item").each(function(){
            var $item=$(this);
            if ( file_id == $(this).data('file_id') ) {
                $item.iCheck("check");
            }else{
                $item.iCheck("uncheck");
            }
        } );

    },onshow:function(){}};
    $('.right-menu').contextify(options);

    $('body').click(function(){
        menu_hide();
    });

    $('#id_resource_type').change(function(){
        $('#id_tag_one').val(-1);
        $('#id_tag_two').val(-1);
        $('#id_tag_three').val(-1);
        $('#id_tag_four').val(-1);
        $('#id_tag_five').val(-1);
    });
    var color_id = 0,color_res = 0,color_flag = 0;
    $('.common-table tr').each(function(i){
        if(i>0){
            if($(this).data('file_id') == color_res){
                $(this).css('background',color_id );
            } else {
                color_res = $(this).data('file_id');
                (color_flag == 0) ? color_flag = 1: color_flag = 0;
                (color_flag == 0) ? color_id = '#e6e6e6' : color_id = '#bfbfbf';
                $(this).css('background',color_id);
            }
        }
    });
    $('.opt-change').set_input_change_event(load_data);

    $(".opt-sub-tag").click(function(){
        var subject = $('#id_subject').val();
        var grade = $('#id_grade').val();
        var bookid = $('#id_tag_one').val();
        var resource_type = $('#id_resource_type').val();
        if( resource_type != 1){
            //标准试听课
            var season_id = -1;
        }else{
            //1对1精品课程
            var season_id = parseInt($('#id_tag_two').val());
        }
        window.open("/resource/sub_grade_book_tag?subject="+subject+"&grade="+grade+
                    "&bookid="+bookid+"&resource_type="+resource_type+"&season_id="+season_id);
    })

    //同意修改
    $('.opt-agree').click(function(){
        var data = $(this).parents('tr').get_self_opt_data();

        var change = {
            "id"  : data.id,
            "file_id" : data.file_id,
        };
        var $this = $(this);
        console.log($this);
        //return false;
        var obj = "<span style='color:#2d2828'>已同意</span>";
        var info = "<span style='color:#e81616'>待修改</span>";

        $.ajax({
            type    : "post",
            url     : "/resource_new/file_err_agree",
            dataType: "json",
            data    : change,
            success : function(result){
                console.log(result)
                if(result.ret == 0 && result.status == 200){
                    $this.next().removeClass('hide');
                    $this.after(obj);
                    $this.parent().prev().html(info);
                    $this.remove();
                }else{
                    BootstrapDialog.alert('网络错误！');
                }
            }
        });

    })

    //初审驳回
    $(".opt-first-look").click(function(){
        var data = $(this).parents('tr').get_self_opt_data();

        var change = {
            "id"  : data.id,
            "file_id" : data.file_id,
            "status"  : 3,
        };

        var $this = $(this);

        var obj = "<span style='color:#e81616'>初审已驳回</span>";
        var info = "<span style='color:#e81616'>初审驳回</span>";

        $.ajax({
            type    : "post",
            url     : "/resource_new/file_err_refuse",
            dataType: "json",
            data    : change,
            success : function(result){
                console.log(result)
                if(result.ret == 0 && result.status == 200){
                    $this.parent().find('.opt-upload').text('重传');
                    $this.after(obj);
                    $this.parent().prev().html(info);
                    $this.remove();
                }else{
                    BootstrapDialog.alert(result.info);
                }
            }
        });

    })

    //复审驳回
    $(".opt-sec-look").click(function(){
        var data = $(this).parents('tr').get_self_opt_data();

        var change = {
            "id"  : data.id,
            "file_id" : data.file_id,
            "status"  : 4,
        };

        var $this = $(this);

        var obj = "<span style='color:#e81616'>复审已驳回</span>";
        var info = "<span style='color:#e81616'>复审驳回</span>";


        $.ajax({
            type    : "post",
            url     : "/resource_new/file_err_refuse",
            dataType: "json",
            data    : change,
            success : function(result){
                console.log(result)
                if(result.ret == 0 && result.status == 200){
                    $this.parent().find('.opt-upload').text('重传');
                    $this.after(obj);
                    $this.parent().prev().html(info);
                    $this.remove();
                }else{
                    BootstrapDialog.alert(result.info);
                }
            }
        });

    })

});
