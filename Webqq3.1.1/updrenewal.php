<?php

/**
 * 修改续费配置
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
RoleUtil::findRole($user_role['jurisdiction'], RoleUtil::MANAGERRENEWAL, "抱歉,您没有权限访问");

/**
 * 如果没有接收到renewal_id参数，则跳转到续费配置页面
 */
if (!DataUtil::is_exits("renewal_id")) header("location:renewal.php");

/**
 * 实例化续费操作类
 */
$renewalPer = new WebrenewalPer();

$renewal_id = DataUtil::param_mysql_filter("renewal_id");

if (DataUtil::is_exits("name") && DataUtil::is_exits("sort") && DataUtil::is_exits("day_time") && DataUtil::is_exits("gold")) {
	$name = DataUtil::param_mysql_filter("name");
	$sort = DataUtil::param_mysql_filter("sort");
	$day_time = DataUtil::param_mysql_filter("day_time");
	$gold = DataUtil::param_mysql_filter("gold");
	if (!DataUtil::is_empty($name) && !DataUtil::is_empty($sort) && !DataUtil::is_empty($day_time) && !DataUtil::is_empty($gold)) {
		$updateResult = $renewalPer->updateMe($renewal_id, $name, $sort, $day_time, $gold);
	}
}

$renewal = $renewalPer->getMeById($renewal_id);

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
require_once MANAGER_FOLDER . "updrenewal.inc";

/**
 * 加载公共尾部文件
 */
require_once DOCUMENTS_FOLDER . 'footer.inc';

?>