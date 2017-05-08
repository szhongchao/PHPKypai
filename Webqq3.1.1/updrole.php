<?php

/**
 * 修改角色配置
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
RoleUtil::findRole($user_role['jurisdiction'], RoleUtil::MANAGERROLE, "抱歉,您没有权限访问");

/**
 * 如果没有接收到role_id参数，则跳转到角色管理页面
 */
if (!DataUtil::is_exits("role_id")) header("location:role.php");

/**
 * 实例化角色操作类
 */
$rolePer = new WebrolePer();

$role_id = DataUtil::param_mysql_filter("role_id");

if (DataUtil::is_exits("name") && DataUtil::is_exits("sort") && DataUtil::is_exits("add_robot_max_number") && DataUtil::is_exits("init_gold") && DataUtil::is_exits("jurisdiction")) {
	$name = DataUtil::param_mysql_filter("name");
	$sort = DataUtil::param_mysql_filter("sort");
	$add_robot_max_number = DataUtil::param_mysql_filter("add_robot_max_number");
	$init_gold = DataUtil::param_mysql_filter("init_gold");
	$jurisdiction = 0;
	$jurisdictions = DataUtil::param_mysql_filter_checkbox("jurisdiction");
	if (!DataUtil::is_empty($jurisdictions) && is_array($jurisdictions)) {
		foreach ($jurisdictions as $value) {
			if (!is_numeric($value)) continue;
			$jurisdiction += pow(2, intval($value));
		}
	}
	if (!DataUtil::is_empty($name) && !DataUtil::is_empty($sort) && !DataUtil::is_empty($add_robot_max_number)) {
		$updateResult = $rolePer->updateMe($role_id, $name, $sort, $add_robot_max_number, $init_gold, $jurisdiction);
	}
}

/**
 * 属于此角色的用户数量
 */
$count = $userPer->getCountByRoleId($role_id);

/**
 * 获取角色
 */
$role = $rolePer->getMeById($role_id);

/**
 * 获取角色权限的HTML代码
 */
$roleCheckBoxHtmlArray = RoleUtil::getRoleCheckedHtml($role['jurisdiction'], 'jurisdiction[]');

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
require_once MANAGER_FOLDER . "updrole.inc";

/**
 * 加载公共尾部文件
 */
require_once DOCUMENTS_FOLDER . 'footer.inc';

?>