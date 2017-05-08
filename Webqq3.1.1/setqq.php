<?php

/**
 * 茉莉机器人首页
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
 * 如果没有接收到uin参数，则跳转到机器人列表页面
 */
if (!DataUtil::is_exits("uin")) header("location:robot.php");

/**
 * 实例化QQ操作类
 */
$robotPer = new WebrobotPer();

if (DataUtil::is_exits("uin") && DataUtil::is_exits("pass") && DataUtil::is_exits("name") && DataUtil::is_exits("create_uin")) {
	$uin = DataUtil::param_mysql_filter("uin");
	$pass = DataUtil::param_mysql_filter("pass");
	$name = DataUtil::param_mysql_filter("name");
	$create_uin = DataUtil::param_mysql_filter("create_uin");
	$is_reply = DataUtil::param_mysql_filter("is_reply" ,false, true);
	$is_hook = DataUtil::param_mysql_filter("is_hook" ,false, true);
	$is_group_speech = DataUtil::param_mysql_filter("is_group_speech" ,false, true);
	$is_personal_speech = DataUtil::param_mysql_filter("is_personal_speech" ,false, true);
	$is_reconnection = DataUtil::param_mysql_filter("is_reconnection" ,false, true);
	$is_run = DataUtil::param_mysql_filter("is_run" ,false, true);
	$saveqqResult = false;
	if (!DataUtil::is_empty($uin) && !DataUtil::is_empty($name) && !DataUtil::is_empty($create_uin)) {
		$robotPer = new WebrobotPer();
		$saveqqResult = $robotPer->updateMe(USER_ID, $uin, $pass, $name, $create_uin, $is_reply, $is_hook, $is_group_speech, $is_personal_speech, $is_reconnection, $is_run);
	}
}

/**
 * 根据QQ账号获取信息
 */
$robot = $robotPer->getMeByUin(USER_ID, DataUtil::param_mysql_filter("uin"));

if (!$robot) header("location:robot.php");

/**
 * 设置网页标题
 */
$_set_html_title = "设置机器人";

/**
 * 需要加载的CSS文件
 */
$_link_file_array = array();

/**
 * 需要加载的JavaScript文件
 */
$_script_file_array = array();

/**
 * 加载公共头部文件
 */
require_once DOCUMENTS_FOLDER . "header.inc";

/**
 * 导入首页模板文件
 */
require_once MANAGER_FOLDER . "setqq.inc";

/**
 * 加载公共尾部文件
 */
require_once DOCUMENTS_FOLDER . 'footer.inc';

?>