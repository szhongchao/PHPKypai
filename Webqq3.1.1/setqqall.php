<?php

/**
 * 批量设置QQ机器人
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
 * 如果没有接收到type参数，则跳转到机器人列表页面
 */
if (!DataUtil::is_exits("type") || !DataUtil::is_exits("id_array")) header("location:robot.php");

$type = DataUtil::param_mysql_filter("type");
$id_array = DataUtil::param_mysql_filter("id_array");
$id_str = implode("','", explode(",", $id_array));

/**
 * 实例化QQ操作类
 */
$robotPer = new WebrobotPer();

$count = $robotPer->getCountByIdAndUserId(USER_ID, $id_str);

/**
 * 判断要操作的机器人是不是全部属于此用户
 */
if ($count != count(explode(",", $id_array))) {
	header("location:robot.php");
	exit();
}

if (DataUtil::is_exits("sub")) {

	$saveqqResult = false;

	if ($type == "update") {
		if (DataUtil::is_exits("name") && DataUtil::is_exits("create_uin")) {
			$name = DataUtil::param_mysql_filter("name");
			$create_uin = DataUtil::param_mysql_filter("create_uin");
			$is_reply = DataUtil::param_mysql_filter("is_reply" ,false, true);
			$is_hook = DataUtil::param_mysql_filter("is_hook" ,false, true);
			$is_group_speech = DataUtil::param_mysql_filter("is_group_speech" ,false, true);
			$is_personal_speech = DataUtil::param_mysql_filter("is_personal_speech" ,false, true);
			$is_reconnection = DataUtil::param_mysql_filter("is_reconnection" ,false, true);
			$is_run = DataUtil::param_mysql_filter("is_run" ,false, true);
			if (!DataUtil::is_empty($name) && !DataUtil::is_empty($create_uin)) {
				$robotPer = new WebrobotPer();
				$saveqqResult = $robotPer->updateMeAll(USER_ID, $id_str, $name, $create_uin, $is_reply, $is_hook, $is_group_speech, $is_personal_speech, $is_reconnection, $is_run);
			}
		}
	} elseif ($type == "manager") {
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
			$robotdatPer = new WebrobotdatPer();
			$saveqqResult = $robotdatPer->updateMe($id_str, $manager_uin, $group_black_list, $group_white_list, $qq_black_list, $qq_white_list, $add_group_clues, $agree_add_group_clues, $refuse_add_group_clues, $is_agree_add_group, $is_refuse_add_group, $is_group_black_list, $is_group_white_list, $is_qq_black_list, $is_qq_white_list);
		}
	} elseif ($type == "remove") {
		$saveqqResult = $robotPer->deleteMeByIds(USER_ID, $id_str);
		if ($saveqqResult) {
			$cookiePer = new WebcookiePer();
			$groupmemberPer = new WebgroupmemberPer();
			$robotdatPer = new WebrobotdatPer();
			$robotfriendsPer = new WebrobotfriendsPer();
			$runlogPer = new WebrunlogPer();
			$verificationiPer = new WebverificationPer();

			$cookiePer->deleteMeByRobotIds($id_str);
			$groupmemberPer->deleteMeByRobotIds($id_str);
			$robotdatPer->deleteMeByIds($id_str);
			$robotfriendsPer->deleteMeByRobotIds($id_str);
			$runlogPer->deleteMeByRobotIds($id_str);
			$verificationiPer->deleteMeByRobotIds($id_str);
			header("location:robot.php");
			exit();
		}
	}
}

/**
 * 设置网页标题
 */
$_set_html_title = "批量操作机器人";

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
require_once MANAGER_FOLDER . "setqqall.inc";

/**
 * 加载公共尾部文件
 */
require_once DOCUMENTS_FOLDER . 'footer.inc';

?>