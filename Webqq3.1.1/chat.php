<?php

/**
 * 聊天室
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

/**
 * 加载主体文件
 */
require_once 'web-init.php';

/**
 * 设置网页标题
 */
$_set_html_title = "聊天室";

/**
 * 实例化聊天室消息操作类
 */
$chatcontentPer = new WebchatcontentPer();

if (DataUtil::is_exits("content")) {
	/**
	 * 如果用户没有权限则给出提示
	 */
	RoleUtil::findRole($user_role['jurisdiction'], RoleUtil::CHATSPEECH, "抱歉,您暂时不能在聊天室里嗨");
	$chatCount = $chatcontentPer->getCountByUserIdAndTime(USER_ID, 5);
	if ($chatCount >= 3) {
		header("location:tip.php?msg=您是不是聊得太嗨了,请稍候重试");
		exit();
	}
	$content = DataUtil::param_mysql_filter("content");
	if (!DataUtil::is_empty(trim($content))) {
		$chatcontentPer->insertMe(USER_ID, $content, DataUtil::getIP());
	}
}

$limit = 10;
$count = intval($chatcontentPer->getCount());
$pageno = intval(DataUtil::param_mysql_filter("pageno"));
$total = ceil($count/$limit);
$pageno = $pageno > $total ? $total : $pageno;
$pageno = $pageno > 0 ? $pageno : 1;
$offset = ($pageno - 1) * $limit;

$contentArray = $chatcontentPer->getMe($limit, $offset);

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
require_once MANAGER_FOLDER . "chat.inc";

/**
 * 加载公共尾部文件
 */
require_once DOCUMENTS_FOLDER . 'footer.inc';

?>