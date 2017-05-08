<?php

/**
 * 删除用户,包括此用户产生的所有数据
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

$user_id = DataUtil::param_mysql_filter("user_id");

if ($user_id == 1) {
	header("location:tip.php?msg=初始账号无法删除");
	exit();
}

/**
 * 实例化角色操作类
 */
$rolePer = new WebrolePer();

/**
 * 实例化机器人操作类
 */
$robotPer = new WebrobotPer();

/**
 * 获取要删除的用户信息
 */
$user_info = $userPer->getMe($user_id);

/**
 * 获取要删除用户的所有机器人
 */
$robotArray = $robotPer->getMeAllByUserId($user_id);

$role = $rolePer->getMeById($user_info['role_id']);

if (DataUtil::is_exits("delete")) {
	$removeResult = $userPer->deleteMe($user_id);
	if ($removeResult) {
		$idArray = array();
		if ($robotArray) {
			foreach ($robotArray as $robot) {
				array_push($idArray, $robot['id']);
			}
		}
		$ids = implode("','", $idArray);
		if (!DataUtil::is_empty($ids)) {
			$removeResult = $robotPer->deleteMeByIds($user_id, $ids);
			if ($removeResult) {
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
				header("location:user.php");
				exit();
			}
		}
	}
}

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
require_once MANAGER_FOLDER . "deluser.inc";

/**
 * 加载公共尾部文件
 */
require_once DOCUMENTS_FOLDER . 'footer.inc';

?>