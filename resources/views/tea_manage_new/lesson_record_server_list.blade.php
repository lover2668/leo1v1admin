@extends('layouts.app')
@section('content')

    <section class="content ">
        
        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-6 col-md-4">
                    <div class="input-group ">
                        <span class="input-group-addon">声音服务器</span>
                        <input class="opt-change form-control" id="id_record_audio_server1" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <button class="btn btn-primary" id="id_set_select_list">批量分配声音服务器</button>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>

                    <td style="width:80px">
                        <a href="javascript:;" id="id_select_all" title="全选">全</a>
                        <a href="javascript:;" id="id_select_other" title="反选">反</a>
                    </td>

                    <td>声音服务器</td>
                    <td>上课时间</td>
                    <td>学生</td>
                    <td>老师</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>

                        <td> <input type="checkbox" class="opt-select-item" data-id="{{$var["lessonid"]}}"/>   {{$var["index"]}} </td>
                        <td>{{@$var["record_audio_server1"]}} </td>
                        <td>{{@$var["lesson_time"]}} </td>
                        <td>{{@$var["student_nick"]}} </td>
                        <td>{{@$var["teacher_nick"]}} </td>

                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection
