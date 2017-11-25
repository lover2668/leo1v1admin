@extends('layouts.app_new2')
@section('content')
    <section class="content">
        <div class="row  row-query-list" >
            <div class="col-xs-12 col-md-4"  data-title="时间段">
                <div  id="id_date_range" >
                </div>
            </div>


            <div class="col-xs-6 col-md-3">
                <div class="input-group ">
                    <span class="input-group-addon">反馈人</span>
                    <input class="opt-change form-control" id="id_feedback_adminid"/>
                </div>
            </div>

            <div class="col-xs-6 col-md-3">
                <div class="input-group ">
                    <span class="input-group-addon">是否解决</span>
                    <select class="opt-change form-control " id="id_deal_flag" >
                    </select>
                </div>
            </div>

            <div class="col-xs-6 col-md-3">
                <div class="input-group ">
                    <button type="button" class="btn btn-warning" id="id_submit">提交</button>
                </div>
            </div>

        </div>
        <hr/>
        <table   class="common-table"   >
            <thead>
                <tr>
                    <td>问题提出人</td>
                    <td>问题收集人</td>
                    <td>问题录入时间</td>
                    <td>问题描述</td>
                    <td>上课链接</td>
                    <td>原因</td>
                    <td>解决方案</td>
                    <td>学生姓名</td>
                    <td>学生手机</td>
                    <td>学生设备信息</td>
                    <td>老师信息</td>
                    <td>老师手机</td>
                    <td>老师设备信息</td>
                    <td>解决状态</td>
                    <td>备注</td>
               </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
                    <tr>
                        <td >{{@$var["feedback_nick"]}} </td>
                        <td >{{@$var["record_nick"]}}</td>
                        <td >{{@$var["create_time"]}}</td>
                        <td >{{@$var["describe"]}}</td>
                        <td >{{@$var["lesson_url"]}}</td>
                        <td >{{@$var["reason"]}}</td>
                        <td >{{@$var["solution"]}}</td>
                        <td >{{@$var["stu_nick"]}}</td>
                        <td >{{@$var["stu_phone"]}}</td>
                        <td >{{@$var["stu_agent_simple"]}}</td>
                        <td >{{@$var["tea_nick"]}}</td>
                        <td >{{@$var["tea_phone"]}}</td>
                        <td >{{@$var["tea_agent_simple"]}}</td>
                        <td >{!!@$var["deal_flag_str"]!!}</td>
                        <td >{{@$var["remark"]}}</td>
                        <td >
                            <div class="btn-group"
                                 {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa-user opt-user " title="个人信息" ></a>
                            </div>
                        </td>
            </tr>
                @endforeach
            </tbody>
        </table>

        <div style="display:none;" >
            <div id="id_assign_log">
                <table   class="table table-bordered "   >
                    <tr>  <th> 合同id <th>驳回人 <th>驳回原因 <th>驳回时间  </tr>
                        <tbody class="data-body">
                        </tbody>
                </table>
            </div>
        </div>



        @include("layouts.page")
    </section>

    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>


@endsection