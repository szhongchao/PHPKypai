<?php

/**
 * 修改个人资料
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

$rolePer = new WebrolePer();

$role = $rolePer->getMeById($user['role_id']);

if (DataUtil::is_exits("username") && DataUtil::is_exits("mail") && DataUtil::is_exits("qq")) {
	$username = DataUtil::param_mysql_filter("username");
	$mail = DataUtil::param_mysql_filter("mail");
	$qq = DataUtil::param_mysql_filter("qq");
	$new_pass = DataUtil::param_mysql_filter("new_pass");
	$new_pass2 = DataUtil::param_mysql_filter("new_pass2");
	$updateResult = false;
	$tip = "";
	if ($new_pass != $new_pass2) {
		$tip = "新密码和确认新密码不相同";
	} elseif (DataUtil::is_empty($username)) {
		$tip = "用户名不能为空";
	} else {
		$password = DataUtil::is_empty($new_pass) ? "" : md5($new_pass);
		if ($userPer->updateMe(USER_ID, $username, $password, $mail, null, $qq)) {
			$updateResult = true;
			$user = $userPer->getMe(USER_ID);
		} else {
			$tip = "您没有进行任何修改";
		}
	}
}

/**
 * 设置网页标题
 */
$_set_html_title = "修改资料";

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
require_once MANAGER_FOLDER . "updpro.inc";

/**
 * 加载公共尾部文件
 */
require_once DOCUMENTS_FOLDER . 'footer.inc';

?>