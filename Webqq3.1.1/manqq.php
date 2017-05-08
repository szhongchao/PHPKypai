<?php

/**
 * 管理机器人
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

/**
 * 根据QQ账号获取信息
 */
$robot = $robotPer->getMeByUin(USER_ID, DataUtil::param_mysql_filter("uin"));

if (!$robot) header("location:robot.php");

$robotdatPer = new WebrobotdatPer();

if (DataUtil::is_exits("manager_uin") && DataUtil::is_exits("group_black_list") && DataUtil::is_exits("group_white_list") && DataUtil::is_exits("qq_black_list") && DataUtil::is_exits("qq_white_list") && DataUtil::is_exits("add_group_clues") && DataUtil::is_exits("agree_add_group_clues") && DataUtil::is_exits("refuse_add_group_clues")) {
	$manager_uin = DataUtil::param_mysql_filter("manager_uin");
	$group_black_list = DataUtil::param_mysql_filter("group_black_list");
	$group_white_list = DataUtil::param_mysql_filter("group_white_list");
	$qq_black_list = DataUtil::param_mysql_filter("qq_black_list");
	$qq_white_list = DataUtil::param_mysql_filter("qq_white_list");
	$add_group_clues = DataUtil::param_mysql_filter("add_group_clues");
	$agree_add_group_clues = DataUtil::param_mysql_filter("agree_add_group_clues");
	$refuse_add_group_clues = DataUtil::param_mysql_filter("refuse_add_group_clues");
	$is_agree_add_group = DataUtil::param_mysql_filter("is_agree_add_group" ,false, true);
	$is_refuse_add_group = DataUtil::param_mysql_filter("is_refuse_add_group" ,false, true);
	$is_group_black_list = DataUtil::param_mysql_filter("is_group_black_list" ,false, true);
	$is_group_white_list = DataUtil::param_mysql_filter("is_group_white_list" ,false, true);
	$is_qq_black_list = DataUtil::param_mysql_filter("is_qq_black_list" ,false, true);
	$is_qq_white_list = DataUtil::param_mysql_filter("is_qq_white_list" ,false, true);
	$saveqqResult = $robotdatPer->updateMe($robot['id'], $manager_uin, $group_black_list, $group_white_list, $qq_black_list, $qq_white_list, $add_group_clues, $agree_add_group_clues, $refuse_add_group_clues, $is_agree_add_group, $is_refuse_add_group, $is_group_black_list, $is_group_white_list, $is_qq_black_list, $is_qq_white_list);
}

$robotdat = $robotdatPer->getMeByRobotId($robot['id']);

if (!$robotdat) {
	$robotdatPer->replaceMe($robot['id']);
	$robotdat = $robotdatPer->getMeByRobotId($robot['id']);
}

/**
 * 设置网页标题
 */
$_set_html_title = "管理机器人";

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
require_once MANAGER_FOLDER . "manqq.inc";

/**
 * 加载公共尾部文件
 */
require_once DOCUMENTS_FOLDER . 'footer.inc';

?>