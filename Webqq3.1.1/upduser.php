<?php

/**
 * 修改用户资料
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
RoleUtil::findRole($user_role['jurisdiction'], RoleUtil::MANAGERUSER, "抱歉,您没有权限访问");

/**
 * 如果没有接收到user_id参数，则跳转到机器人列表页面
 */
if (!DataUtil::is_exits("user_id")) header("location:user.php");

/**
 * 实例化角色操作类
 */
$rolePer = new WebrolePer();

/**
 * 实例化机器人操作类
 */
$robotPer = new WebrobotPer();

$user_id = DataUtil::param_mysql_filter("user_id");

if (DataUtil::is_exits("username") && DataUtil::is_exits("phone") && DataUtil::is_exits("gold") && DataUtil::is_exits("invitation") && DataUtil::is_exits("role_id")) {
	$id = DataUtil::param_mysql_filter("id");
	$username = DataUtil::param_mysql_filter("username");
	$password = DataUtil::param_mysql_filter("password");
	$mail = DataUtil::param_mysql_filter("mail");
	$phone = DataUtil::param_mysql_filter("phone");
	$qq = DataUtil::param_mysql_filter("qq");
	$gold = DataUtil::param_mysql_filter("gold");
	$invitation = DataUtil::param_mysql_filter("invitation");
	$role_id = DataUtil::param_mysql_filter("role_id");
	$password = DataUtil::is_empty($password) ? "" : md5($password);
	if (!DataUtil::is_empty($username) && !DataUtil::is_empty($phone) && !DataUtil::is_empty($gold) && !DataUtil::is_empty($invitation) && !DataUtil::is_empty($role_id)) {
		$updateResult = $userPer->updateMeAll($user_id, $id, $role_id, $username, $password, $mail, $phone, $qq, $gold, $invitation);
		if ($updateResult) $user_id = $id;
	}
	$delqq = DataUtil::param_mysql_filter_checkbox("delqq");
	if (!DataUtil::is_empty($delqq) && is_array($delqq)) {
		$delqqArray = implode("','", $delqq);
		$robotArray = $robotPer->getMeByUins($user_id, $delqqArray);
		$ids = array();
		if ($robotArray) {
			foreach ($robotArray as $robot) {
				array_push($ids, $robot['id']);
			}
		}
		$ids = implode("','", $ids);
		if (!DataUtil::is_empty($ids)) {
			$updateResult = $robotPer->deleteMeByIds($user_id, $ids);
			if ($updateResult) {
				$cookiePer = new WebcookiePer();
				$groupmemberPer = new WebgroupmemberPer();
				$robotdatPer = new WebrobotdatPer();
				$robotfriendsPer = new WebrobotfriendsPer();
				$runlogPer = new WebrunlogPer();
				$verificationiPer = new WebverificationPer();
				
				$cookiePer->deleteMeByRobotIds($ids);
				$groupmemberPer->deleteMeByRobotIds($ids);
				$robotdatPer->deleteMeByIds($ids);
				$robotfriendsPer->deleteMeByRobotIds($ids);
				$runlogPer->deleteMeByRobotIds($ids);
				$verificationiPer->deleteMeByRobotIds($ids);
			}
		}
	}
}

/**
 * 获取要修改的用户信息
 */
$user_info = $userPer->getMe($user_id);

/**
 * 获取所有用户组
 */
$roleArray = $rolePer->getMeAll();

/**
 * 获取要编辑用户的所有机器人
 */
$robotArray = $robotPer->getMeAllByUserId($user_id);

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

/**
 * 加载公共头部文件
 */
require_once DOCUMENTS_FOLDER . "header.inc";

/**
 * 导入首页模板文件
 */
require_once MANAGER_FOLDER . "upduser.inc";

/**
 * 加载公共尾部文件
 */
require_once DOCUMENTS_FOLDER . 'footer.inc';

?>