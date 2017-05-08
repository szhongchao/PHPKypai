<?php

/**
 * 删除角色
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

/**
 * 角色ID
 */
$role_id = DataUtil::param_mysql_filter("role_id");

/**
 * 属于此角色的用户数量
 */
$count = $userPer->getCountByRoleId($role_id);

if ($count <= 0 && DataUtil::is_exits("delete")) {
	$removeResult = $rolePer->deleteMe($role_id);
	if ($removeResult) {
		header("location:role.php");
	}
}

/**
 * 获取角色
 */
$role = $rolePer->getMeById($role_id);

/**
 * 获取角色权限的HTML代码
 */
$roleCheckBoxHtmlArray = RoleUtil::getRoleCheckedHtml($role['jurisdiction'], 'jurisdiction[]', true);

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
require_once MANAGER_FOLDER . "delrole.inc";

/**
 * 加载公共尾部文件
 */
require_once DOCUMENTS_FOLDER . 'footer.inc';

?>