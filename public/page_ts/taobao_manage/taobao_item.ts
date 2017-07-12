/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/taobao_manage-taobao_item.d.ts" />
$(function(){
    function load_data(){
        $.reload_self_page ( {
			parent_cid : $('#id_parent_cid').val(),
			cid        : $('#id_cid').val(),
			status     : $('#id_status').val()
        });
    }

    var taobao_type_select=function(obj,parent_cid,val){
        $.do_ajax("/taobao_manage/get_taobao_type_select",{
            "parent_cid" : parent_cid
        },function(result){
            var html="<option value=\"-1\">[全部]</option>";
            $.each(result.data,function(i,item){
                html+="<option value=\""+item['cid']+"\">"+item['name']+"</optin>";
            });
            obj.html(html);
            obj.val(val);
        });
    }

    taobao_type_select($("#id_parent_cid"),0,g_args.parent_cid);
    taobao_type_select($("#id_cid"),g_args.parent_cid,g_args.cid);

    $("#id_parent_cid").on("change",function(){
        $("#id_cid").val(-1);
        var val=$(this).val()
        if(val>0){
            taobao_type_select($("#id_cid"),val,-1);
        }else if(val==-1){
            load_data();
        }
    });

    $('#id_status').val(g_args.status);

    $("#id_update_taobao").on("click",function(){
        BootstrapDialog.alert("更新中,请勿重复点击....");
        $.do_ajax("/taobao_manage/update_taobao_item_list",{});
    });

    $(".opt-info").on("click",function(){
        var open_iid      = $(this).get_opt_data("open_iid");
        var id_product    = $("<input/>");
        var id_sort_order = $("<input/>");

        $.do_ajax("/taobao_manage/set_taobao_info",{
            "type"     : 1,
            "open_iid" : open_iid
        },function(result){
            var arr=[
                ["商品ID",id_product ],
                ["商品排序",id_sort_order],
            ];

            id_product.val(result.data.product_id);
            id_sort_order.val(result.data.sort_order);

            $.show_key_value_table("修改",arr,{
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    $.do_ajax( '/taobao_manage/set_taobao_info',{
                        "type"       : 2,
                        "open_iid"   : open_iid,
                        "product_id" : id_product.val(),
                        "sort_order" : id_sort_order.val()
                    },function(data){
                        if (data.ret!=0) {
                            alert(data.info);
                        }else{
                            window.location.reload();
                        }
                    });
                }
            });

        });
    });

    $('.opt-change').set_input_change_event(load_data);
});
