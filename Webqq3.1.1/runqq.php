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
 * 如果没有接收到uin参数，则跳转到机器人列表页面
 */
if (!DataUtil::is_exits("uin")) header("location:robot.php");

/**
 * 实例化QQ操作类
 */
$robotPer = new WebrobotPer();

$uin = DataUtil::param_mysql_filter("uin");

/**
 * 如果获取到验证码参数，则修改验证码
 */
if (DataUtil::is_exits("verification")) {
	$verification = DataUtil::param_mysql_filter("verification");
	$robotPer->updateProfileByUin(USER_ID, $uin, "code", $verification);
}

if (DataUtil::is_exits("rsa_pass")) {
	$rsa_pass = DataUtil::param_mysql_filter("rsa_pass");
	$robotPer->updateProfileByUin(USER_ID, $uin, "rsa_pass", $rsa_pass);
	$robotPer->updateProfileByUin(USER_ID, $uin, "status", StatusUtil::LOADING_LOGIN);
}

/**
 * 根据QQ账号获取信息
 */
$robot = $robotPer->getMeByUin(USER_ID, $uin);

if (!$robot) header("location:robot.php");

/**
 * 账号状态说明
 */
$refresh = "<a href = \"runqq.php?uin=$uin\" title = \"刷新\"><i class = \"fa fa-spinner fa-fw fa-spin\"></i></a>";

if ($robot['status'] == StatusUtil::INIT) {
	$statusDes = "等待系统执行中" . $refresh;
} elseif ($robot['status'] == StatusUtil::LOADING_RSA) {
	$statusDes = "请点击登录按钮" . $refresh;
} elseif ($robot['status'] == StatusUtil::LOADING_VERIFY) {
	$statusDes = "请输入验证码并登录" . $refresh;
} elseif ($robot['status'] == StatusUtil::LOADING_LOGIN) {
	$statusDes = "账号登录中" . $refresh;
} elseif ($robot['status'] == StatusUtil::ONLINE) {
	$statusDes = "<font color = \"green\">正常运行中</font>";
} else {
	$statusDes = "未知情况";
}

/**
 * 实例化运行日志类
 */
$logPer = new WebrunlogPer();

$limit = 20;
$count = intval($logPer->getAllLogCount($robot));
$pageno = intval(DataUtil::param_mysql_filter("pageno"));
$total = ceil($count/$limit);
$pageno = $pageno > $total ? $total : $pageno;
$pageno = $pageno > 0 ? $pageno : 1;
$offset = ($pageno - 1) * $limit;

/**
 * 获取机器人运行记录
 */
$logs = $logPer->getMe($robot, $limit, $offset);

/**
 * 设置网页标题
 */
$_set_html_title = "运行记录";

/**
 * 需要加载的CSS文件
 */
$_link_file_array = array();

/**
 * 需要加载的JavaScript文件
 */
$_script_file_array = array("qq.js");

/**
 * 加载公共头部文件
 */
require_once DOCUMENTS_FOLDER . "header.inc";

/**
 * 导入首页模板文件
 */
require_once MANAGER_FOLDER . "runqq.inc";

/**
 * 加载公共尾部文件
 */
require_once DOCUMENTS_FOLDER . 'footer.inc';

?>