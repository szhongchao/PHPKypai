<?php

/**
 * 提示页面
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

/**
 * 加载主体文件
 */
require_once 'web-init.php';

if (!DataUtil::is_exits("do") && !DataUtil::is_exits("msg")) {
	header("location:index.php");
}

$msg = DataUtil::param_mysql_filter("msg");

$do = DataUtil::param_mysql_filter("do");

if ($do == "activate") {
	$ch = curl_init(DOMAIN_FOLDER . "task.php");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
	curl_setopt($ch, CURLOPT_TIMEOUT, 3);
	$result = curl_exec($ch);
	curl_close($ch);
	$msg = !DataUtil::is_empty($result) ? $result : "计划任务激活成功";
}

/**
 * 设置网页标题
 */
$_set_html_title = "温馨提示";

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
require_once MANAGER_FOLDER . "tip.inc";

/**
 * 加载公共尾部文件
 */
require_once DOCUMENTS_FOLDER . 'footer.inc';

?>