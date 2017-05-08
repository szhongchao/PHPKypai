<?php 

/**
 * 检查用户的登录状态
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

if(isset($_COOKIE['user_check']) && !isset($_SESSION['user_check'])) {
	$_SESSION['user_check'] = $_COOKIE['user_check'];
}

$user_login_status = UserLoginUtil::NO_LOGIN;
$user_id = 0;
$user_name = "";
$role_id = 0;

/**
 * 销毁登录信息
 */
function destroy_profile() {
	$_SESSION = array();
	if(isset($_COOKIE[session_name()])){
		setcookie(session_name(), '', time()-3600);
	}
	session_destroy();
	setcookie('user_check', '', time()-3600);
}

/**
 * 实例化用户操作类
 */
$userPer = new WebuserPer();

/**
 * 判断SESSION中是否存在user_check
 */
if(isset($_SESSION['user_check'])) {
	$user_check = $_SESSION['user_check'];
	if (strlen($user_check) <= 64 && strlen($user_check) > 32) {
		$user = $userPer->checkUser($user_check);
		if ($user) {
			$user_id = $user['id'];
			$user_name = $user['username'];
			$user_login_status = UserLoginUtil::SUCCESS;
			$role_id = $user['role_id'];
		} else {
			$user_login_status = UserLoginUtil::COOKIE_FAIL;
			destroy_profile();
		}
	} else {
		$user_login_status = UserLoginUtil::WARNING;
		destroy_profile();
	}
}

?>