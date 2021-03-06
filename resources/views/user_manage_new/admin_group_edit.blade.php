@extends('layouts.app')
@section('content')

    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <section class="content ">
        
        <div>
            <div class="row">

                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <span >主分类</span>
                        <select  id="id_main_type" class="opt-change"  >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-3">
                    <div class="input-group " >
                        <span >子分类</span>
                        <select  id="id_groupid" class="opt-change"  >
                            @foreach ( $group_list as $var ) 
                                <option value="{{$var["groupid"]}}"> {{$var["group_name"]}} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <span  id="id_group_master" data-master_adminid="{{$group_master_adminid}}" >助长:{{$group_master_nick}}</span>
                    </div>
                </div>

                <div class="col-xs-6 col-md-1">
                    <button class="btn btn-primary  " id="id_add_group"> 新增分组</button>
                </div>
                <div class="col-xs-6 col-md-1">
                    <button class="btn btn-warning " id="id_edit_group"> 修改当前</button>
                </div>
                <div class="col-xs-6 col-md-1">
                    <button class="btn btn-warning " id="id_del_group"> 删除当前</button>
                </div>

                <div class="col-xs-6 col-md-1">
                    <button class="btn btn-primary " id="id_add_group_user"> 新增人员</button>
                </div>





            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>adminid</td>
                    <td>昵称</td>
                    <td>分配比例</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["adminid"]}} </td>
                        <td>{{@$var["admin_nick"]}} </td>
                        <td>{{@$var["assign_percent"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit" title="配置比例"> </a>
                                <a class="fa fa-times opt-del" title="删除"> </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

