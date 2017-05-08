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

$robotPer = new WebrobotPer();

if (DataUtil::is_exits("del_uin")) {
	$del_uin = DataUtil::param_mysql_filter("del_uin");
	$robot = $robotPer->getMeByUin(USER_ID, $del_uin);
	if ($robot) {
		$delResult = $robotPer->deleteMe(USER_ID, $del_uin);
		if ($delResult) {
			$cookiePer = new WebcookiePer();
			$groupmemberPer = new WebgroupmemberPer();
			$robotdatPer = new WebrobotdatPer();
			$robotfriendsPer = new WebrobotfriendsPer();
			$runlogPer = new WebrunlogPer();
			$verificationiPer = new WebverificationPer();

			$cookiePer->deleteMeByRobotId($robot['id']);
			$groupmemberPer->deleteMeByRobotId($robot['id']);
			$robotdatPer->deleteMe($robot['id']);
			$robotfriendsPer->deleteMeByRobotId($robot['id']);
			$runlogPer->deleteMeByRobotId($robot['id']);
			$verificationiPer->deleteMeByRobotId($robot['id']);
			header("location:robot.php");
			exit();
		} else {
			header("location:tip.php?msg=机器人删除失败");
			exit();
		}
	} else {
		header("location:robot.php");
		exit();
	}
} else {
	$robot = $robotPer->getMeByUin(USER_ID, DataUtil::param_mysql_filter("uin"));
	if (!$robot) {
		header("location:robot.php");
		exit();
	}
}

/**
 * 设置网页标题
 */
$_set_html_title = "删除机器人";

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
require_once MANAGER_FOLDER . "delqq.inc";

/**
 * 加载公共尾部文件
 */
require_once DOCUMENTS_FOLDER . 'footer.inc';

?>