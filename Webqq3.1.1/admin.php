<?php

/**
 * 后台管理
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

/**
 * 加载主体文件
 */
require_once 'web-init.php';

/**
 * 如果用户不是登录成功状态则跳转到登录页面
 */
if (LOGIN_STATUS != UserLoginUtil::SUCCESS) header("location:login.php");

/**
 * 如果用户没有权限则给出提示
 */
RoleUtil::findRole($user_role['jurisdiction'], RoleUtil::GOADMIN, "抱歉,您没有权限访问");

if (DataUtil::is_exits("style_default") || DataUtil::is_exits("cron_status") || DataUtil::is_exits("cron_pass") || DataUtil::is_exits("is_reg") || DataUtil::is_exits("domain_name") || DataUtil::is_exits("admin_role")) {
	/**
	 * 如果用户没有权限则给出提示
	 */
	RoleUtil::findRole($user_role['jurisdiction'], RoleUtil::UPDATEBASE, "抱歉,您没有权限操作");
}

/**
 * 设置网页标题
 */
$_set_html_title = "后台管理";

/**
 * 需要加载的CSS文件
 */
$_link_file_array = array();

/**
 * 需要加载的JavaScript文件
 */
$_script_file_array = array();

$rolePer = new WebrolePer();

/**
 * 获取所有用户组
 */
$roleArray = $rolePer->getMeAllByJurisdiction($user_role['jurisdiction']);

/**
 * 获取所有的CSS样式
 */
$styles = $systemPer->getMeByMark("style");

if (DataUtil::is_exits("style_default")) {
	$style_default = DataUtil::param_mysql_filter("style_default", "style.css");
	if ($systemPer->replaceSys("style_default", "style_default", $style_default)) {
		$styleDef = $style_default;
	}
}

if (DataUtil::is_exits("cron_status")) {
	$systemPer->replaceSys("cron", "status", DataUtil::param_mysql_filter("cron_status"));
}

if (DataUtil::is_exits("cron_pass")) {
	$systemPer->replaceSys("cron", "pass", DataUtil::param_mysql_filter("cron_pass"));
}

if (DataUtil::is_exits("invite_gold1")) {
	$invite_gold1 = DataUtil::param_mysql_filter("invite_gold1");
	if (!DataUtil::is_empty($invite_gold1) && is_numeric($invite_gold1)) {
		$systemPer->replaceSys("control", "invite_gold1", $invite_gold1);
	}
}

if (DataUtil::is_exits("invite_gold2")) {
	$invite_gold2 = DataUtil::param_mysql_filter("invite_gold2");
	if (!DataUtil::is_empty($invite_gold2) && is_numeric($invite_gold2)) {
		$systemPer->replaceSys("control", "invite_gold2", $invite_gold2);
	}
}

if (DataUtil::is_exits("is_stop_robot")) {
	$is_stop_robot = DataUtil::param_mysql_filter("is_stop_robot" ,false, true);
	if (!DataUtil::is_empty($is_stop_robot) && $is_stop_robot == 1) {
		$robotPer = new WebrobotPer();
		$robotPer->setRobotAllInit();
	}
	$systemPer->replaceSys("control", "is_stop_robot", $is_stop_robot);
}

if (DataUtil::is_exits("is_reg")) {
	$systemPer->replaceSys("control", "is_reg", DataUtil::param_mysql_filter("is_reg"));
}

if (DataUtil::is_exits("domain_name")) {
	$systemPer->replaceSys("control", "domain_name", DataUtil::param_mysql_filter("domain_name"));
}

if (DataUtil::is_exits("notice_title")) {
	$systemPer->replaceSys("notice", "title", DataUtil::param_mysql_filter("notice_title"));
}

if (DataUtil::is_exits("notice_content")) {
	$systemPer->replaceSys("notice", "content", DataUtil::param_mysql_filter("notice_content"));
}

if (DataUtil::is_exits("admin_role")) {
	$roles = array();
	if ($roleArray) {
		foreach ($roleArray as $role) {
			array_push($roles, $role['id']);
		}
	}
	if (!DataUtil::is_equal($roles, DataUtil::param_mysql_filter("admin_role"), true)) {
		header("location:tip.php?msg=非法操作");
		exit();
	}
	$systemPer->replaceSys("admin", "admin_role", DataUtil::param_mysql_filter("admin_role"));
}

/**
 * 获取机器人工作的入口密码
 */
$cron_pass = $systemPer->getValueByName("cron", "pass", "");

/**
 * 邀请别人注册获取的金币
 */
$invite_gold1 = $systemPer->getValueByName("control", "invite_gold1", "");

/**
 * 被邀请人获取的金币
 */
$invite_gold2 = $systemPer->getValueByName("control", "invite_gold2", "");

/**
 * 获取网络任务状态
 */
$cron_status = $systemPer->getValueByName("cron", "status", CronUtil::_EXIT);

/**
 * 是否停止运行系统所有的机器人
 */
$is_stop_robot = $systemPer->getValueByName("control", "is_stop_robot", 0);

/**
 * 是否开启注册
 */
$is_reg = $systemPer->getValueByName("control", "is_reg", 0);

/**
 * 网站名
 */
$domain_name = $systemPer->getValueByName("control", "domain_name");

/**
 * 公告标题
 */
$notice_title = $systemPer->getValueByName("notice", "title");

/**
 * 公告内容
 */
$notice_content = $systemPer->getValueByName("notice", "content");

/**
 * 默认用户组
 */
$admin_role = $systemPer->getValueByName("admin", "admin_role", 0);

/**
 * 获取计划任务执行的最后一次时间戳
 */
$cron_last_time = intval($systemPer->getValueByName("cron", "last_time", 0));

/**
 * 判断计划任务是否正常
 */
$cron_is_normal = (time() - $cron_last_time <= 61) ? true : false;

/**
 * 加载公共头部文件
 */
require_once DOCUMENTS_FOLDER . "header.inc";

/**
 * 导入首页模板文件
 */
require_once MANAGER_FOLDER . "admin.inc";

/**
 * 加载公共尾部文件
 */
require_once DOCUMENTS_FOLDER . 'footer.inc';

?>