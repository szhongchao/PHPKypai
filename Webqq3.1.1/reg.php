<?php

/**
 * 用户注册
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

/**
 * 加载主体文件
 */
require_once 'web-init.php';

/**
 * 注册类型
 */
$is_reg = $systemPer->getValueByName("control", "is_reg", 0);

$invite = DataUtil::param_mysql_filter("invite");
$inviteUser = false;
if (!DataUtil::is_empty($invite)) {
	$inviteUser = $userPer->getMeByInvite($invite);
}

if ($is_reg == 0) {
	header("location:tip.php?msg=管理员已经关闭了网站注册");
	exit();
} elseif ($is_reg == 2) {
	if (DataUtil::is_empty($invite)) {
		header("location:tip.php?msg=管理员开启了邀请注册，没有邀请链接，无法注册");
		exit();
	} elseif (!$inviteUser) {
		header("location:tip.php?msg=邀请链接不正确，无法注册");
		exit();
	}
}

if (DataUtil::is_exits("username") && DataUtil::is_exits("phone") && DataUtil::is_exits("password") && DataUtil::is_exits("npassword") && DataUtil::is_exits("verificate")) {
	$regResult = false;
	$regTip = "";
	$username = DataUtil::param_mysql_filter("username");
	$phone = DataUtil::param_mysql_filter("phone");
	$password = DataUtil::param_mysql_filter("password");
	$npassword = DataUtil::param_mysql_filter("npassword");
	$verificate = DataUtil::param_mysql_filter("verificate");
	if (!DataUtil::is_empty($username) && !DataUtil::is_empty($phone) && !DataUtil::is_empty($password) && !DataUtil::is_empty($npassword) && !DataUtil::is_empty($verificate)) {
		/**
		 * 获取新用户注册默认用户组
		 */
		$admin_role = $systemPer->getValueByName("admin", "admin_role", false);
		if (!DataUtil::is_equal(md5(strtolower($verificate) . "itpkverificate"), $_SESSION['yzm'])) {
			$regTip = "您输入的验证码不正确";
		} elseif (!DataUtil::is_equal($password, $npassword)) {
			$regTip = "您输入的密码和确认密码不相同";
		} elseif (!is_numeric($phone) || strlen($phone) != 11) {
			$regTip = "您输入的手机号格式不正确";
		} elseif (!$admin_role) {
			$regTip = "抱歉,管理员没有设置新用户默认角色，暂时不能注册";
		} elseif ($userPer->findProfile("username", $username)) {
			$regTip = "哎呀,用户名已经被注册过啦";
		} elseif ($userPer->findProfile("phone", $phone)) {
			$regTip = "哎呀,这个手机号已经注册过啦";
		}
		if ($regTip == "") {
			$regResult = $userPer->insertMe($admin_role, $username, md5($password), $phone);
			if ($regResult) {
				$defaultRole = $rolePer->getMeById($admin_role);
				$invite_gold2 = intval($defaultRole['init_gold']);
				if ($inviteUser) {
					$invite_gold1 = $systemPer->getValueByName("control", "invite_gold1", 0);
					$userPer->updateProfile("id", $inviteUser['id'], "gold", intval($invite_gold1), true, "+");
					$invite_gold2 += intval($systemPer->getValueByName("control", "invite_gold2", 0));
				}
				$userPer->updateProfile("username", $username, "gold", $invite_gold2, true, "+");
				header("location:login.php");
				exit();
			} else {
				$regTip = "请检查所有选项填写是否正确";
			}
		}
	} else {
		$regTip = "所有注册项内容都必填";
	}
}

/**
 * 设置网页标题
 */
$_set_html_title = "用户注册";

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
require_once MANAGER_FOLDER . "reg.inc";

/**
 * 加载公共尾部文件
 */
require_once DOCUMENTS_FOLDER . 'footer.inc';

?>