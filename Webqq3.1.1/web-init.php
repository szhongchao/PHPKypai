<?php

/**
 * 茉莉机器人入口文件
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

/**
 * 设置报错级别（这里设置禁止输出任何错误信息）
 */
#error_reporting(0);

/**
 * 定义一个常量，用来防止别人直接访问程序内部文件
 */
define('ITPK', 'ITPK');

/**
 * 如果数据库配置文件不存在或者内容为空则跳转到安装页面
 */
if (!file_exists("db-config.php") || (file_exists("db-config.php") && file_get_contents("db-config.php") == "")) header("location:install/index.php");

/**
 * 加载文件
 */
require_once 'web-load.php';

/**
 * 加载版本号文件
 */
require_once 'version/v.php';

/**
 * 如果没有常量CONF_END，或者此常量不为true，则终止程序的运行
 */
if (!defined('LOAD_END') || !LOAD_END) ErrorUtil::put("程序还没有准备好");

?>