<?php if (!defined('ITPK')) exit('You can not directly access the file.');

/**
 * 加载程序文件
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

/**
 * 设置时区
 */
date_default_timezone_set('PRC');

/**
 * 开启SESSION
 */
session_start();

/**
 * 加载数据库配置文件
 */
require_once 'db-config.php';

/**
 * 加载配置文件
 */
require_once 'web-config.php';

/**
 * 加载字符操作工具
 */
require_once TOOLS_FOLDER . 'DataUtil.php';

/**
 * 根据类名自动加载文件
 * @param string $className 类名
 */
function __autoload($className) {
	$folder = CLASS_FOLDER;

	if (DataUtil::start_contain("Web", $className) && DataUtil::end_contain("Handler", $className)) {
		/**
		 * 以Web开头并且以Handler结尾的类名为插件类：插件根目录+类名（去Web和Handler的小写字符）
		 */
		$folder = PLUGIN_FOLDER . DataUtil::getPluginFolder($className) . "/";
	} elseif (DataUtil::start_contain("Abstract", $className) && DataUtil::end_contain("Handler", $className)) {
		/**
		 * 以Abstract开头并且以Handler结尾的类名为插件父类
		 */
		$folder = PLUGIN_FOLDER;
	} elseif (DataUtil::end_contain("Per", $className)) {
		/**
		 * Per结尾的类名为系统基本操作类
		 */
		$folder = PER_FOLDER;
	} elseif (DataUtil::end_contain("Util", $className)) {
		/**
		 * Util结尾的类名为工具类
		 */
		$folder = TOOLS_FOLDER;
	} elseif (DataUtil::end_contain("Common", $className)) {
		/**
		 * Common结尾的类名为工具包
		 */
		$folder = COMMON_FOLDER;
	} elseif (DataUtil::end_contain("Sql", $className)) {
		/**
		 * Sql结尾的类名为sql工具类
		 */
		$folder = SQL_FOLDER;
	}

	/**
	 * 类名的具体目录
	 */
	$file = $folder . $className . ".php";

	/**
	 * 如果文件存在则导入此文件
	 */
	if(file_exists($file)) require_once($file);
}

/**
 * 实例化标记操作类
 */
$systemPer = new WebsystemPer();

if (!defined('IS_CHECK_LOGIN') || IS_CHECK_LOGIN) {
	/**
	 * 检查用户的登录状态
	 */
	require_once 'check-login.php';

	/**
	 * 用户登录状态
	 * @var int
	 */
	define('LOGIN_STATUS', $user_login_status);

	/**
	 * 用户ID
	 * @var int
	 */
	define('USER_ID', $user_id);

	/**
	 * 用户名
	 * @var string
	 */
	define('USER_NAME', $user_name);

	/**
	 * 用户角色ID
	 * @var int
	 */
	define('ROLE_ID', $role_id);

	unset($user_login_status);
	unset($user_id);
	unset($user_name);
	unset($role_id);

	/**
	 * 实例化角色操作类
	 */
	$rolePer = new WebrolePer();

	/**
	 * 获取当前用户的角色
	 */
	$user_role = $rolePer->getMeById(ROLE_ID);

	/**
	 * 获取默认的CSS样式
	 */
	$styleDef = $systemPer->getValueByName("style_default", "style_default", "style.css");

	/**
	 * 获取网站名字
	 */
	define('DOMAIN_NAME', $systemPer->getValueByName("control", "domain_name", "茉莉QQ机器人"));
}

/**
 * 是否加载完成，如果设置为false，程序将停止运行（相当于关闭站点）
 * @var boolean
 */
define('LOAD_END', true);

?>