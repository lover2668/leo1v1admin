
@extends('layouts.app')
@section('content')

    <link href="/ztree/zTreeStyle.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="/ztree/jquery.ztree.core.js"></script>
    <script type="text/javascript">
     var setting = {
         data: {
             simpleData: {
                 enable: true
             }
         }
     }
     alert('wel');

		 var zNodes =[
			   { id:1, pId:0, name:"普通的父节点", t:"我很普通，随便点我吧", open:true},
			   { id:11, pId:1, name:"叶子节点 - 1", t:"我很普通，随便点我吧"},
			   { id:12, pId:1, name:"叶子节点 - 2", t:"我很普通，随便点我吧"},
			   { id:13, pId:1, name:"叶子节点 - 3", t:"我很普通，随便点我吧"},
			   { id:2, pId:0, name:"NB的父节点", t:"点我可以，但是不能点我的子节点，有本事点一个你试试看？", open:true},
			   { id:21, pId:2, name:"叶子节点2 - 1", t:"你哪个单位的？敢随便点我？小心点儿..", click:false},
			   { id:22, pId:2, name:"叶子节点2 - 2", t:"我有老爸罩着呢，点击我的小心点儿..", click:false},
			   { id:23, pId:2, name:"叶子节点2 - 3", t:"好歹我也是个领导，别普通群众就来点击我..", click:false},
			   { id:3, pId:0, name:"郁闷的父节点", t:"别点我，我好害怕...我的子节点随便点吧...", open:true, click:false },
			   { id:31, pId:3, name:"叶子节点3 - 1", t:"唉，随便点我吧"},
			   { id:32, pId:3, name:"叶子节点3 - 2", t:"唉，随便点我吧"},
			   { id:33, pId:3, name:"叶子节点3 - 3", t:"唉，随便点我吧"}
		 ];

		 $(document).ready(function(){
         alert('kkk');
			   $.fn.zTree.init($("#treeDemo"), setting, zNodes);
		 });
		 
	  </script>
<section class='content'>
    <div> <!-- search ... -->
        <div class='row  row-query-list' >
            <div class='col-xs-12 col-md-5'>
                <div id='id_date_range' >
                </div>
            </div>
        </div>
    </div>
    <hr/>
    <div class="zTreeDemoBackground left">
		    <ul id="treeDemo" class="ztree"></ul>
	  </div>
    <table class="common-table">
    </table>

@include('layouts.page')
</section>
@endsection