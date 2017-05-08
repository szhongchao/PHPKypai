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

if (DataUtil::is_exits("uin") && DataUtil::is_exits("pass") && DataUtil::is_exits("name") && DataUtil::is_exits("create_uin")) {
	$addqqResult = false;
	$addTip = "";
	$robotPer = new WebrobotPer();
	$add_robot_max_number = $user_role['add_robot_max_number'];
	$count = $robotPer->getCountByUserId(USER_ID);
	if ($count < $add_robot_max_number) {
		$uin = DataUtil::param_mysql_filter("uin");
		$pass = DataUtil::param_mysql_filter("pass");
		$name = DataUtil::param_mysql_filter("name");
		$create_uin = DataUtil::param_mysql_filter("create_uin");
		if (!$robotPer->findMeByUin($uin)) {
			if (!DataUtil::is_empty($uin) && !DataUtil::is_empty($pass) && !DataUtil::is_empty($name) && !DataUtil::is_empty($create_uin)) {
				$addqqResult = $robotPer->insertMe(USER_ID ,$uin, $pass, $name, $create_uin);
				if ($addqqResult) {
					$robot = $robotPer->getMeByUin(USER_ID, $uin);
					$robotdatPer = new WebrobotdatPer();
					$robotdatPer->replaceMe($robot['id']);
				} else {
					$addTip = "请检查是否填写正确";
				}
			} else {
				$addTip = "请填完所有的选项";
			}
		} else {
			$addTip = "此机器人账号已存在";
		}
	} else {
		$addTip = "您的机器人配额不足,无法添加";
	}
}

/**
 * 设置网页标题
 */
$_set_html_title = "添加机器人";

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
require_once MANAGER_FOLDER . "addqq.inc";

/**
 * 加载公共尾部文件
 */
require_once DOCUMENTS_FOLDER . 'footer.inc';

?>