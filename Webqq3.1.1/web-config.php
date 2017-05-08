<?php if (!defined('ITPK')) exit('You can not directly access the file.');

/**
 * Web配置文件
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

/**
 * 当SQL执行出错时，是否输入出错的SQL语句。true：输出 false：不输出
 * @var boolean
 */
define('IS_PRINT_ERROR_SQL', true);

/**
 * 网站域名
 */
define('DOMAIN',			$_SERVER['HTTP_HOST']);

/**
 * 生成网站根目录网址
 */
$domain_folder = "http://" . DOMAIN . "/";
$php_self = explode("/", $_SERVER['PHP_SELF']);
for ($i = 0; $i < count($php_self) - 1; $i++) {
	if ($php_self[$i] == "") continue;
	$domain_folder .= $php_self[$i] . "/";
}

/**
 * 网站根目录网址
 */
define('DOMAIN_FOLDER',		$domain_folder);
unset($domain_folder);
unset($php_self);

/**
 * 程序安装根目录
 * @var string
 */
define('ROOT',				dirname(__FILE__) . "/");

/**
 * 插件存放目录
 * @var string
 */
define('PLUGIN_FOLDER',		ROOT . 'plugin/');

/**
 * 类文件根目录
 * @var string
 */
define('CLASS_FOLDER',		ROOT . 'class/');

/**
 * 系统基本操作类存放目录
 */
define('PER_FOLDER',		CLASS_FOLDER . 'per/');

/**
 * 工具类存放目录
 * @var string
 */
define('TOOLS_FOLDER',		CLASS_FOLDER . 'tools/');

/**
 * 工具包存放目录
 * @var string
 */
define('COMMON_FOLDER',		CLASS_FOLDER . 'common/');

/**
 * sql工具类
 * @var string
 */
define('SQL_FOLDER',		CLASS_FOLDER . 'sql/');

/**
 * 公共HTML根目录
 * @var string
 */
define('WEB_FOLDER',		'public_html/');

/**
 * 配置文件目录
 * @var string
*/
define('CONFIG_FOLDER',		WEB_FOLDER . 'config/');

/**
 * CSS目录
 * @var string
*/
define('CSS_FOLDER',		WEB_FOLDER . 'css/');

/**
 * JavaScript目录
 * @var string
*/
define('JS_FOLDER',			WEB_FOLDER . 'js/');

/**
 * 图片目录
 * @var string
*/
define('IMAGES_FOLDER',		WEB_FOLDER . 'images/');

/**
 * 模板目录
 * @var string
 */
define('TEMPLATE_FOLDER',	WEB_FOLDER . 'template/');

/**
 * 网站公共模板目录
 * @var string
 */
define('DOCUMENTS_FOLDER',	TEMPLATE_FOLDER . 'documents/');

/**
 * 网站管理模板目录
 * @var string
 */
define('MANAGER_FOLDER',	TEMPLATE_FOLDER . 'manager/');

/**
 * 回车换行
 * @var string
 */
define('CR', "\r\n");

/**
 * QQ消息中的换行字符
 * @var string
 */
define('UCR', "%5C%5Cn");

/**
 * 插件指令的分割符，多个指令用这个符号隔开
 * @var string
 */
define('PART', '|');

?>