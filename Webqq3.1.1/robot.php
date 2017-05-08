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

/**
 * 如果用户不是登录成功状态则跳转到登录页面
 */
if (LOGIN_STATUS != UserLoginUtil::SUCCESS) header("location:login.php");

/**
 * 设置网页标题
 */
$_set_html_title = "机器人管理";

/**
 * 需要加载的CSS文件
 */
$_link_file_array = array();

/**
 * 需要加载的JavaScript文件
 */
$_script_file_array = array("robot.js");

/**
 * 加载公共头部文件
 */
require_once DOCUMENTS_FOLDER . "header.inc";

/**
 * 实例化QQ操作类
 */
$robotPer = new WebrobotPer();

/**
 * 获取数据库中所有的QQ账号
 */
$robotArray = $robotPer->getMeAllByUserId(USER_ID);

/**
 * 导入首页模板文件
 */
require_once MANAGER_FOLDER . "robot.inc";

/**
 * 加载公共尾部文件
 */
require_once DOCUMENTS_FOLDER . 'footer.inc';

?>