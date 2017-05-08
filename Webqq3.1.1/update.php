<?php

/**
 * 系统更新
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

if (DataUtil::is_exits("do")) {
	$do = DataUtil::param_mysql_filter("do");
	$result = file_get_contents("http://addon.itpk.cn/update.php?do=webqq&type=update&version=" . VERSION);
	$json_array = json_decode($result, true);
	if ($json_array && $json_array['status'] == "success") {
		$fileArray = $json_array['file_array'];
		$sqlArray = $json_array['sql_array'];
		$count = $json_array['count'];
		$folder = $json_array['folder'];
		$updateUtil = new UpdateUtil();
		$updateUtil->setInit($sqlArray, $fileArray, $folder);
		$fileArray = $updateUtil->checkFile();
		$is_continue = $updateUtil->is_continue();
		if ($do == "update") {
			$updateUtil->executeUpdateWithSql();
			$updateUtil->executeUpdateWithFile();
			header("location:update.php");
			exit();
		}
	}
} else {
	$result = file_get_contents("http://addon.itpk.cn/update.php?do=webqq&type=new_version&version=" . VERSION);
	$json_array = json_decode($result, true);
	$new_version = 0;
	if ($json_array && $json_array['status'] == "success") {
		$new_version = $json_array['version'];
	}
	$is_continue = strnatcasecmp($new_version, VERSION) > 0 ? true : false;
}

/**
 * 设置网页标题
 */
$_set_html_title = "系统更新";

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
require_once MANAGER_FOLDER . "update.inc";

/**
 * 加载公共尾部文件
 */
require_once DOCUMENTS_FOLDER . 'footer.inc';

?>