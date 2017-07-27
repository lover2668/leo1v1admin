@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>

    <section class="content ">
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <div id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">调课原因 </span>
                        <select class="opt-change form-control" id="id_lesson_cancel_reason_type" >
                        </select>
                    </div>
                </div>

            </div>


        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>编号</td>
                    <td>老师名称</td>
                    <td>课时数</td>
                    <td>课时确认</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $index => $var )
                    <tr>
                        <td>{{@$index}}</td>
                        <td>{{@$var["teacher_nick"]}}</td>
                        <td><a>{{@$var["lesson_count"]}}</a></td>
                        <td>{!!@$var["lesson_cancel_reason_type_str"]!!}</td>

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
