<?php

/**
 * 用户管理
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
RoleUtil::findRole($user_role['jurisdiction'], RoleUtil::GOADMIN, "抱歉,您没有权限访问");

$limit = 20;
$count = intval($userPer->getAllUserCount());
$pageno = intval(DataUtil::param_mysql_filter("pageno"));
$total = ceil($count/$limit);
$pageno = $pageno > $total ? $total : $pageno;
$pageno = $pageno > 0 ? $pageno : 1;
$offset = ($pageno - 1) * $limit;

$userArray = $userPer->getMeAll($limit, $offset);

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
require_once MANAGER_FOLDER . "user.inc";

/**
 * 加载公共尾部文件
 */
require_once DOCUMENTS_FOLDER . 'footer.inc';

?>