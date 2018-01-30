@extends('layouts.app')
@section('content')

       
   
   

    
    <section class="content ">

        <div>
            <div class="row" >
                <div class="col-xs-6 col-md-2">
                    <button id="id_get_money" class="btn btn-primary">刷新</button>
                </div >
               

            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>学生姓名</td>
                    <td>学生id</td>
                    <td>常规课科目</td>
                    <td>常规课年级</td>
                    <td>类型</td>
                  
                                                                     

                    <td> 操作</td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $list as $k=>$var )
                    <tr>
                        
                        <td>{{ @$var["nick"] }}</td>
                        <td>{{ @$var["userid"] }}</td>
                        <td>{{ @$var["subject_str"] }}</td>
                        <td>{{ @$var["grade_str"] }}</td>
                        
                        <td>{{ @$var["type"] }}</td>
                       

                        <td>
                            <div class="row-data"  data-start="{{ @$var["start"] }}" >
                                <a class="fa fa-list course_plan" title="按课程包排课"> </a>
                            </div>

                        </td>


                    </tr>

                @endforeach



            </tbody>
        </table>


        @include("layouts.page")
    </section>

  


@endsection
