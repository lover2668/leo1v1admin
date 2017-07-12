<?php
//自动生成枚举类  不要手工修改
//source  file: config_sms_type.php
namespace  App\Enums;

class Esms_type extends \App\Enums\Enum_base
{
	static public $field_name = "sms_type"  ;
	static public $name = "sms_type"  ;
	 static $desc_map= array(
		7795923 => "用户注册验证码-old",
		7771547 => "通用验证-old",
		72765026 => "短信通知（价格调整）",
		66515139 => "家长预约5-9",
		63750218 => "预约完成4-28",
		55565027 => "老师注册通知3-15",
		51615035 => "老师注册通知",
		51410003 => "老师签单奖励通知",
		46725145 => "解除排课限制通知2-14",
		46835164 => "限制老师排课通知2-14",
		46785153 => "试听取消-不付工资2-14",
		46880148 => "第一次支付成功2-14",
		46800132 => "第二次支付成功2-14",
		46675251 => "注册老师账号通知2-14",
		46805178 => "晚8点 通知老师明天上课",
		46750146 => "老师反馈通知2-14",
		46670149 => "老师试讲可重申2-14",
		46705122 => "老师视频不完整 2",
		46375041 => "老师视频不完整",
		46745131 => "老师试讲未通过2-14",
		46790219 => "通知老师上传试讲2-14",
		46660108 => "老师冻结通知2-14",
		46720087 => "老师解冻通知2-14",
		46865086 => "老师试讲通过2-14",
		46680138 => "通知老师课程取消-付工资2-14",
		46690154 => "通知明天上课2-14",
		34775122 => "价格调整",
		33865292 => "通知老师试听课程取消-不付工资",
		34015213 => "老师试讲通过",
		30110108 => "通知老师课程取消",
		27725043 => "报名免费课程",
		27315033 => "预约完成3",
		18345084 => "通知明天上课",
		11051022 => "合同确认，重置家长密码",
		10671030 => "注册验证码",
		10671029 => "通用验证",
		10615821 => "早7点 通知家长1v1&小班上课",
		11035096 => "早7点 通知家长试听上课",
		10642098 => "晚8点 通知老师明天上课",
		12755231 => "注册老师账号通知",
		12040030 => "课件上传提醒",
		11865003 => "第二次支付成功",
		11275039 => "试听排课完成 ",
		11295014 => "预约完成 ",
		11565037 => "通知家长预约成功",
		15960017 => "销售回访后通知家长",
		16785465 => "通知家长预约成功2",
	);
	 static $simple_desc_map= array(
		7795923 => "",
		7771547 => "",
		72765026 => "",
		66515139 => "",
		63750218 => "",
		55565027 => "",
		51615035 => "",
		51410003 => "",
		46725145 => "",
		46835164 => "",
		46785153 => "",
		46880148 => "",
		46800132 => "",
		46675251 => "",
		46805178 => "",
		46750146 => "",
		46670149 => "",
		46705122 => "",
		46375041 => "",
		46745131 => "",
		46790219 => "",
		46660108 => "",
		46720087 => "",
		46865086 => "",
		46680138 => "",
		46690154 => "",
		34775122 => "",
		33865292 => "",
		34015213 => "",
		30110108 => "",
		27725043 => "",
		27315033 => "",
		18345084 => "",
		11051022 => "",
		10671030 => "",
		10671029 => "",
		10615821 => "",
		11035096 => "",
		10642098 => "",
		12755231 => "",
		12040030 => "",
		11865003 => "",
		11275039 => "",
		11295014 => "",
		11565037 => "",
		15960017 => "",
		16785465 => "",
	);
	 static $s2v_map= array(
		"register" => 7795923,
		"" => 7771547,
		"" => 72765026,
		"" => 66515139,
		"" => 63750218,
		"" => 55565027,
		"" => 51615035,
		"" => 51410003,
		"" => 46725145,
		"" => 46835164,
		"" => 46785153,
		"" => 46880148,
		"" => 46800132,
		"" => 46675251,
		"" => 46805178,
		"" => 46750146,
		"" => 46670149,
		"" => 46705122,
		"" => 46375041,
		"" => 46745131,
		"" => 46790219,
		"" => 46660108,
		"" => 46720087,
		"" => 46865086,
		"" => 46680138,
		"" => 46690154,
		"" => 34775122,
		"" => 33865292,
		"" => 34015213,
		"" => 30110108,
		"" => 27725043,
		"" => 27315033,
		"" => 18345084,
		"" => 11051022,
		"" => 10671030,
		"" => 10671029,
		"" => 10615821,
		"" => 11035096,
		"" => 10642098,
		"" => 12755231,
		"" => 12040030,
		"" => 11865003,
		"" => 11275039,
		"" => 11295014,
		"" => 11565037,
		"" => 15960017,
		"" => 16785465,
	);
	 static $v2s_map= array(
		 7795923=>  "register",
		 7771547=>  "",
		 72765026=>  "",
		 66515139=>  "",
		 63750218=>  "",
		 55565027=>  "",
		 51615035=>  "",
		 51410003=>  "",
		 46725145=>  "",
		 46835164=>  "",
		 46785153=>  "",
		 46880148=>  "",
		 46800132=>  "",
		 46675251=>  "",
		 46805178=>  "",
		 46750146=>  "",
		 46670149=>  "",
		 46705122=>  "",
		 46375041=>  "",
		 46745131=>  "",
		 46790219=>  "",
		 46660108=>  "",
		 46720087=>  "",
		 46865086=>  "",
		 46680138=>  "",
		 46690154=>  "",
		 34775122=>  "",
		 33865292=>  "",
		 34015213=>  "",
		 30110108=>  "",
		 27725043=>  "",
		 27315033=>  "",
		 18345084=>  "",
		 11051022=>  "",
		 10671030=>  "",
		 10671029=>  "",
		 10615821=>  "",
		 11035096=>  "",
		 10642098=>  "",
		 12755231=>  "",
		 12040030=>  "",
		 11865003=>  "",
		 11275039=>  "",
		 11295014=>  "",
		 11565037=>  "",
		 15960017=>  "",
		 16785465=>  "",
	);

	//用户注册验证码-old
	const V_7795923=7795923;
	//用户注册验证码-old
	const V_REGISTER=7795923;
	//通用验证-old
	const V_7771547=7771547;
	//短信通知（价格调整）
	const V_72765026=72765026;
	//家长预约5-9
	const V_66515139=66515139;
	//预约完成4-28
	const V_63750218=63750218;
	//老师注册通知3-15
	const V_55565027=55565027;
	//老师注册通知
	const V_51615035=51615035;
	//老师签单奖励通知
	const V_51410003=51410003;
	//解除排课限制通知2-14
	const V_46725145=46725145;
	//限制老师排课通知2-14
	const V_46835164=46835164;
	//试听取消-不付工资2-14
	const V_46785153=46785153;
	//第一次支付成功2-14
	const V_46880148=46880148;
	//第二次支付成功2-14
	const V_46800132=46800132;
	//注册老师账号通知2-14
	const V_46675251=46675251;
	//晚8点 通知老师明天上课
	const V_46805178=46805178;
	//老师反馈通知2-14
	const V_46750146=46750146;
	//老师试讲可重申2-14
	const V_46670149=46670149;
	//老师视频不完整 2
	const V_46705122=46705122;
	//老师视频不完整
	const V_46375041=46375041;
	//老师试讲未通过2-14
	const V_46745131=46745131;
	//通知老师上传试讲2-14
	const V_46790219=46790219;
	//老师冻结通知2-14
	const V_46660108=46660108;
	//老师解冻通知2-14
	const V_46720087=46720087;
	//老师试讲通过2-14
	const V_46865086=46865086;
	//通知老师课程取消-付工资2-14
	const V_46680138=46680138;
	//通知明天上课2-14
	const V_46690154=46690154;
	//价格调整
	const V_34775122=34775122;
	//通知老师试听课程取消-不付工资
	const V_33865292=33865292;
	//老师试讲通过
	const V_34015213=34015213;
	//通知老师课程取消
	const V_30110108=30110108;
	//报名免费课程
	const V_27725043=27725043;
	//预约完成3
	const V_27315033=27315033;
	//通知明天上课
	const V_18345084=18345084;
	//合同确认，重置家长密码
	const V_11051022=11051022;
	//注册验证码
	const V_10671030=10671030;
	//通用验证
	const V_10671029=10671029;
	//早7点 通知家长1v1&小班上课
	const V_10615821=10615821;
	//早7点 通知家长试听上课
	const V_11035096=11035096;
	//晚8点 通知老师明天上课
	const V_10642098=10642098;
	//注册老师账号通知
	const V_12755231=12755231;
	//课件上传提醒
	const V_12040030=12040030;
	//第二次支付成功
	const V_11865003=11865003;
	//试听排课完成 
	const V_11275039=11275039;
	//预约完成 
	const V_11295014=11295014;
	//通知家长预约成功
	const V_11565037=11565037;
	//销售回访后通知家长
	const V_15960017=15960017;
	//通知家长预约成功2
	const V_16785465=16785465;

	//用户注册验证码-old
	const S_7795923="register";
	//用户注册验证码-old
	const S_REGISTER="register";
	//通用验证-old
	const S_7771547="";
	//短信通知（价格调整）
	const S_72765026="";
	//家长预约5-9
	const S_66515139="";
	//预约完成4-28
	const S_63750218="";
	//老师注册通知3-15
	const S_55565027="";
	//老师注册通知
	const S_51615035="";
	//老师签单奖励通知
	const S_51410003="";
	//解除排课限制通知2-14
	const S_46725145="";
	//限制老师排课通知2-14
	const S_46835164="";
	//试听取消-不付工资2-14
	const S_46785153="";
	//第一次支付成功2-14
	const S_46880148="";
	//第二次支付成功2-14
	const S_46800132="";
	//注册老师账号通知2-14
	const S_46675251="";
	//晚8点 通知老师明天上课
	const S_46805178="";
	//老师反馈通知2-14
	const S_46750146="";
	//老师试讲可重申2-14
	const S_46670149="";
	//老师视频不完整 2
	const S_46705122="";
	//老师视频不完整
	const S_46375041="";
	//老师试讲未通过2-14
	const S_46745131="";
	//通知老师上传试讲2-14
	const S_46790219="";
	//老师冻结通知2-14
	const S_46660108="";
	//老师解冻通知2-14
	const S_46720087="";
	//老师试讲通过2-14
	const S_46865086="";
	//通知老师课程取消-付工资2-14
	const S_46680138="";
	//通知明天上课2-14
	const S_46690154="";
	//价格调整
	const S_34775122="";
	//通知老师试听课程取消-不付工资
	const S_33865292="";
	//老师试讲通过
	const S_34015213="";
	//通知老师课程取消
	const S_30110108="";
	//报名免费课程
	const S_27725043="";
	//预约完成3
	const S_27315033="";
	//通知明天上课
	const S_18345084="";
	//合同确认，重置家长密码
	const S_11051022="";
	//注册验证码
	const S_10671030="";
	//通用验证
	const S_10671029="";
	//早7点 通知家长1v1&小班上课
	const S_10615821="";
	//早7点 通知家长试听上课
	const S_11035096="";
	//晚8点 通知老师明天上课
	const S_10642098="";
	//注册老师账号通知
	const S_12755231="";
	//课件上传提醒
	const S_12040030="";
	//第二次支付成功
	const S_11865003="";
	//试听排课完成 
	const S_11275039="";
	//预约完成 
	const S_11295014="";
	//通知家长预约成功
	const S_11565037="";
	//销售回访后通知家长
	const S_15960017="";
	//通知家长预约成功2
	const S_16785465="";

	static public function check_register ($val){
		 return $val == 7795923;
	}


};
