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

if (DataUtil::is_exits("phone") && DataUtil::is_exits("password")) {
	if (LOGIN_STATUS == UserLoginUtil::SUCCESS) {
		header("location:index.php");
	}
	$phone = DataUtil::param_mysql_filter("phone");
	$password = DataUtil::param_mysql_filter("password");
	$userPer = new WebuserPer();
	$user = $userPer->login($phone, $password);
	if ($user) {
		$user_id = $user['id'];
		$user_name = $user['username'];
		$user_check = $user['user_check'];
		if (DataUtil::is_empty($user_check)) {
			$date = time();
			$nonce = "";
			$chars = "abcdefghijklmnopqrstuvwxyz0123456789";
			for ( $i = 0; $i < 12; $i++) $nonce .= $chars[ mt_rand(0, strlen($chars) - 1) ];
			$user_check = base64_encode(md5("ITPK" . $nonce . $date));
			$userPer->updateUserCheck($user_id, $user_check);
		}
		setcookie('user_check', $user_check, time()+(60*60*24*30));
		$_SESSION['user_check'] = $user_check;
		header("location:index.php");
	} else {
		$tip = false;
	}
} elseif (DataUtil::is_exits("action")) {
	if (LOGIN_STATUS != UserLoginUtil::SUCCESS) {
		header("location:login.php");
	}
	$action = DataUtil::param_mysql_filter("action");
	if ($action == "logout") {
		$userPer = new WebuserPer();
		$userPer->updateUserCheck(USER_ID, "");
		$_SESSION = array();
		if(isset($_COOKIE[session_name()])){
			setcookie(session_name(), '', time()-3600);
		}
		session_destroy();
		setcookie('user_check','',time()-3600);
		$url = DataUtil::param_mysql_filter("url", "index");
		header("location:$url.php");
	}
}

/**
 * 设置网页标题
 */
$_set_html_title = "登录";

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
require_once MANAGER_FOLDER . "login.inc";

/**
 * 加载公共尾部文件
 */
require_once DOCUMENTS_FOLDER . 'footer.inc';

?>