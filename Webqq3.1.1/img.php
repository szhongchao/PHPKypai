<?php

/**
 * Webqq验证码图片
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.2
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
if (!DataUtil::is_exits("robot_id")) header("location:robot.php");

/**
 * 实例化验证码操作类
 */
$verificationPer = new WebverificationPer();

/**
 * 获取验证码
 */
$verification = $verificationPer->getMeByRobotId(DataUtil::param_mysql_filter("robot_id"));

/**
 * 如果没有获取到验证码则返回no img
 */
if (!$verification) exit("no img");

/**
 * 定义图片输出
 */
header("Content-type: image/png");

/**
 * 判断是不是windows主机
 */
if (DataUtil::start_contain("WIN", PHP_OS)) {
	/**
	 * 防止php将utf8的bom头输出
	 */
	ob_clean();
}

/**
 * 输出图片
 */
echo $verification;

?>