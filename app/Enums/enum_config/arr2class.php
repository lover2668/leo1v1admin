#!/usr/bin/php
<?php

$arr = array(
    0 => '老师未上传',
    1 => '未上传',
    2 => '未批改',
    3 => '已批改',
    4 => '教研已评价',
    5 => '助教已评价',
);

$arr =array(
    2 => '数学',
);

//========================
$arr=array(
    "1" => "选择",
    "2" => "填空",
    "3" => "问答",
);

$arr=array(
    1=>"容易",
    2=>"较易",
    3=>"一般",
    4=>"较难",
    5=>"困难",
);
$arr=array(
    "0" => "否",
    "1" => "是",
);


$arr= array("未设置","男","女");

$arr = array(
    1 => '父亲',
    2 => '母亲',
    3 => '爷爷',
    4 => '奶奶',
    5 => '外公',
    6 => '外婆',
    7 => '其他',
);

$arr = array(
    0   =>  '常规',
    1   =>  '赠送',
    2   =>  '试听',
    3   =>  '续费',
    1001 => '普通公开课(A)',
    1002 => '普通公开课(B)',
    1003 => '高级公开课',
    2001 => '公开答疑'
);



$arr = array(
11=>"华师大版（华东师范大学出版社）",
10=>"华师大新版（华东师范大学出版社）",
2=>"人教五四版（人民教育出版社）",
1=>"人教五四新版（人民教育出版社）",
4=>"人教版（人民教育出版社）",
3=>"人教新版（人民教育出版社）",
9=>"北师大版（北京师范大学出版社）",
8=>"北京课改版（北京出版社）",
7=>"北京课改新版（北京出版社）",
6=>"冀教版（河北教育出版社）",
5=>"冀教新版（河北教育出版社）",
21=>"青岛版（青岛出版社）",
23=>"鲁教版（山东教育出版社）",
22=>"鲁教新版（山东教育出版社）",
20=>"苏科版（江苏科学技术出版社）",
19=>"苏科新版（江苏科学技术出版社）",
14=>"沪科版（上海科学技术出版社）",
13=>"沪科新版（上海科学技术出版社）",
12=>"沪教版（上海教育出版社）",
18=>"湘教版（湖南教育出版社）",
17=>"湘教新版（湖南教育出版社）",
16=>"浙教版（浙江教育出版社）",
15=>"浙教新版（浙江教育出版社）",

);

$arr = array(
    0 =>"正常上课",
    1=>"正常结课",
    2=>"退费锁定",
    3=>"退费成功",
    4=>"课程切换",
);

$field_name="course_status";

//==========================

$str="<?php\n";
$str.="return array(\n";
foreach ($arr as $key => $value) {
 $str.="\tarray($key,\"\",\"$value\" ),\n"; 
}
$str.=");\n";

file_put_contents("./config_$field_name.php", $str);
