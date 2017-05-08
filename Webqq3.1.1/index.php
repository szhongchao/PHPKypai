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
 * 公告标题
 */
$notice_title = $systemPer->getValueByName("notice", "title", "站点公告");

/**
 * 公告内容
 */
$notice_content = $systemPer->getValueByName("notice", "content", "欢迎使用茉莉QQ机器人，注册一个账号开始体验之旅~\(≧▽≦)/~啦啦啦");

/**
 * 设置网页标题
 */
$_set_html_title = "首页";

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
require_once MANAGER_FOLDER . "index.inc";

/**
 * 加载公共尾部文件
 */
require_once DOCUMENTS_FOLDER . 'footer.inc';

?>