
@extends('layouts.app')
@section('content')
<section class='content'>
    <div> <!-- search ... -->
        <div class='row  row-query-list' >
            <div class='col-xs-12 col-md-5' data-title='时间段'>
            </div>
        <!-- 
             <div class='col-xs-2 col-md-5'>
             <div id='id_date_range' >
             </div>
             </div>
           -->
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <bttton id="id_add" class="btn btn-primary">添加</button>
            </div>
        </div>
        </div>
    </div>
    <hr/>
    <table class="common-table">
        <thead>
            <tr>
                <td>id </td>
                <td>权限组名 </td>
                <td>操作  </td>
            </tr>
        </thead>
        <tbody>
            @foreach($info as $item)
            <tr>
                <td class="role_id">{{$item['id']}}</td>
                <td class="role_name">{{$item['name']}}</td>
                <td>
                    <a class="btn  fa fa-cog td-info" title="竖向显示"></a>
                    <a class="fa opt-user" title="分配成员">成员授权</a>
                    <a class="fa opt-department" title="分配部门">部门授权</a>
                    <a class="fa opt-position" title="分配职位">职位授权</a>
                    <a class="fa fa-edit opt-edit" title="修改"></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@include('layouts.page')
</section>
@endsection