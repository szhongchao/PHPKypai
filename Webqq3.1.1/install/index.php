<?php 

/**
 * 茉莉QQ机器人安装向导
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

/**
 * Webqq安装根目录
 * @var string
 */
define('WEB_ROOT', "../");

/**
 * 定义一个常量，用来防止别人直接访问程序内部文件
 */
define('ITPK', 'ITPK');

require_once WEB_ROOT . 'class/tools/DataUtil.php';
require_once WEB_ROOT . 'class/tools/ErrorUtil.php';

$config_file = WEB_ROOT . 'db-config.php';

$step = DataUtil::param_mysql_filter("step", 1);
$step = is_numeric($step) ? $step : 1;

if (file_exists($config_file) && !DataUtil::is_empty(file_get_contents($config_file))) {
	/**
	 * 如果存在db-config.php文件，并且内容不为空，则提示已经安装成功
	 */
	ErrorUtil::put("您已经安装成功，重新安装请清空db-config.php文件的内容！");
} elseif (!file_exists($config_file)) {
	/**
	 * 如果不存在db-config.php文件则给出提示
	 */
	ErrorUtil::put("缺失db-config.php文件，请上传（重写安装请直接上传空文件）。");
}

switch($step) {
	case 1:
		$is_support_curl = function_exists('curl_init');
		$is_support_mysqli = class_exists('mysqli');
		$is_support_zipArchive = class_exists('ZipArchive');
		$is_support_sleep = function_exists('sleep');
		$is_support_config_writable = is_writable($config_file);
		$is_support_plugin_writable = is_writable(WEB_ROOT . "plugin");
		$php_version = explode('-', phpversion());
        $php_version = $php_version[0];
        $php_version_ge530 = strnatcasecmp($php_version, '5.3.0') >= 0 ? true : false;
		$is_support_step = ($is_support_curl && $is_support_mysqli && $is_support_config_writable && $is_support_plugin_writable) ? true : false;
		break;
	case 2:
		$tip = DataUtil::param_mysql_filter("tip");
		break;
	case 3:
		$db_host = DataUtil::param_mysql_filter("db_host");
		$db_user = DataUtil::param_mysql_filter("db_user");
		$db_pass = DataUtil::param_mysql_filter("db_pass");
		$db_base = DataUtil::param_mysql_filter("db_base");
		$db_port = DataUtil::param_mysql_filter("db_port");
		if (DataUtil::is_empty($db_host) || DataUtil::is_empty($db_user) || DataUtil::is_empty($db_pass) || DataUtil::is_empty($db_base) || DataUtil::is_empty($db_port)) {
			$step = 2;
			$tip = "请填写完数据库配置";
			break;
		}
		$username = trim(DataUtil::param_mysql_filter("username"));
		$password = trim(DataUtil::param_mysql_filter("password"));
		$phone = trim(DataUtil::param_mysql_filter("phone"));
		if (DataUtil::is_empty($username) || DataUtil::is_empty($password) || DataUtil::is_empty($phone)) {
			$step = 2;
			$tip = "请填写完管理员设置";
			break;
		}
		@$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_base, $db_port);
		@$mysqli->set_charset("utf8");
		if (mysqli_connect_errno()) {
			$step = 2;
			$tip = "数据库连接出错，请检查数据库配置是否正确";
			break;
		}
		$db_config_content = "<?php if (!defined('ITPK')) exit('You can not directly access the file.');\r\n\r\n/**\r\n * 数据库配置文件\r\n * @author 冬天的秘密\r\n * @link http://bbs.itpk.cn\r\n * @version 1.0\r\n */\r\n\r\n/**\r\n * 数据库配置（编码统一为UTF-8）\r\n * DBHOST	数据库主机\r\n * DBUSER	数据库用户名\r\n * DBPASS	数据库密码\r\n * DBBASE	数据库名字\r\n * DBPORT	数据库端口\r\n * DBCODE	数据库编码\r\n */\r\ndefine('DBHOST', '{$db_host}');\r\ndefine('DBUSER', '{$db_user}');\r\ndefine('DBPASS', '{$db_pass}');\r\ndefine('DBBASE', '{$db_base}');\r\ndefine('DBPORT', '{$db_port}');\r\ndefine('DBCODE', 'utf8');\r\n\r\n?>";
		if (!@file_put_contents($config_file, $db_config_content)) {
			$step = 2;
			$tip = "数据库配置写入db-config.php文件失败!";
			break;
		}
		if (!file_exists("install.sql")) ErrorUtil::put("缺少程序安装文件（install/install.sql）！");
		$sql = file_get_contents("install.sql");
		$mysqli->multi_query($sql . " insert into web_user(role_id, username, password, phone, invitation, reg_ip, createdate) values(1, '{$username}', '" . md5($password) . "', '{$phone}', '" . DataUtil::getRandString(8, 1) . "', '" . DataUtil::getIP() . "', " . time() . ");");
		break;
	default:
		break;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="description" content="茉莉QQ机器人，新一代智能机器人。" />
<meta name="keywords" content="茉莉QQ机器人,QQ机器人" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0" />
<title>茉莉QQ机器人 - 安装向导</title>
<link rel = "stylesheet" href = "http://libs.useso.com/js/font-awesome/4.2.0/css/font-awesome.min.css" />
<link rel = "stylesheet" href = "../public_html/css/style.css" />
<style type = "text/css">
.td_left{min-width:160px !important;}
.form_button{margin-top:20px;}
</style>
</head>
<body>
<div id = "main">
	<div class = "container">
		<div class = "page_title">茉莉QQ机器人-安装向导</div>
		<?php if ($step == 1) { ?>
		<?php if (getenv('OPENSHIFT_APP_NAME')) { ?>
		<div class = "form_title"><span class = "tip-red">检测到为OPENSHIFT环境，请直接进入<a href = "index.php?step=2">下一步</a>续</span></div>
		<?php } elseif (defined('SAE_ACCESSKEY')) { ?>
		<div class = "form_title"><span class = "tip-red">检测到为SAE环境，请直接进入<a href = "index.php?step=2">下一步</a>续</span></div>
		<?php } else { ?>
		<div class = "form_title"><span class = "tip-color3">请保证<span class = "tip-red">*</span>标注的项目都支持，否则不能继续</span></div>
		<form action = "index.php">
			<input type = "hidden" name = "step" value = "2" />
			<table border = "0" cellpadding = "0" cellspacing = "0">
				<tr class = "odd_tr">
					<th class = "td_left">项目</th>
					<th class = "td_right">检测结果</th>
				</tr>
				<tr>
					<td class = "td_left">curl函数 <span class = "tip-red">*</span></td>
					<td class = "td_right"><?php echo $is_support_curl ? "<span class = \"tip-green\">支持<i class = \"fa fa-check fa-fw\"></i></span>" : "<span class = \"tip-red\">不支持<i class = \"fa fa-times fa-fw\"></i></span>";?></td>
				</tr>
				<tr class = "odd_tr">
					<td class = "td_left">mysqli扩展 <span class = "tip-red">*</span></td>
					<td class = "td_right"><?php echo $is_support_mysqli ? "<span class = \"tip-green\">支持<i class = \"fa fa-check fa-fw\"></i></span>" : "<span class = \"tip-red\">不支持<i class = \"fa fa-times fa-fw\"></i></span>";?></td>
				</tr>
				<tr>
					<td class = "td_left">db-config.php文件 <span class = "tip-red">*</span></td>
					<td class = "td_right"><?php echo $is_support_config_writable ? "<span class = \"tip-green\">可写<i class = \"fa fa-check fa-fw\"></i></span>" : "<span class = \"tip-red\">不可写<i class = \"fa fa-times fa-fw\"></i></span>";?></td>
				</tr>
				<tr class = "odd_tr">
					<td class = "td_left">plugin目录 <span class = "tip-red">*</span></td>
					<td class = "td_right"><?php echo $is_support_plugin_writable ? "<span class = \"tip-green\">可写<i class = \"fa fa-check fa-fw\"></i></span>" : "<span class = \"tip-red\">不可写<i class = \"fa fa-times fa-fw\"></i></span>";?></td>
				</tr>
				<tr>
					<td class = "td_left">PHP 5.3.0或以上 <span class = "tip-color4">*</span></td>
					<td class = "td_right"><?php echo $php_version_ge530 ? "<span class = \"tip-green\">可用<i class = \"fa fa-check fa-fw\"></i></span>" : "<span class = \"tip-color4\">不可用<i class = \"fa fa-exclamation fa-fw\"></i>  这意味着程序可能出现意想不到的错误</span>";?></td>
				</tr>
				<tr class = "odd_tr">
					<td class = "td_left">解压类ZipArchive <span class = "tip-color4">*</span></td>
					<td class = "td_right"><?php echo $is_support_zipArchive ? "<span class = \"tip-green\">可用<i class = \"fa fa-check fa-fw\"></i></span>" : "<span class = \"tip-color4\">不可用<i class = \"fa fa-exclamation fa-fw\"></i> 这意味着你不能在线安装插件</span>";?></td>
				</tr>
				<tr>
					<td class = "td_left">sleep函数 <span class = "tip-color4">*</span></td>
					<td class = "td_right"><?php echo $is_support_sleep ? "<span class = \"tip-green\">可用<i class = \"fa fa-check fa-fw\"></i></span>" : "<span class = \"tip-color4\">不可用<i class = \"fa fa-exclamation fa-fw\"></i> 这意味着你的计划任务可能不可以正常工作</span>";?></td>
				</tr>
				<?php if ($is_support_step) { ?>
				<tr class = "odd_tr">
					<td colspan = "2"><input type = "submit" value = "下一步" class = "white_button" /></td>
				</tr>
				<?php } ?>
			</table>
		</form>
		<?php } ?>
		<?php } elseif ($step == 2) { ?>
		<?php if (!DataUtil::is_empty($step)) { ?>
		<div class = "form_title"><span class = "tip-red"><?php echo $tip; ?></span></div>
		<?php } ?>
		<div class = "form_title2">数据库配置</div>
		<form action = "index.php" method = "post">
			<input type = "hidden" name = "step" value = "3" />
			<table border = "0" cellpadding = "0" cellspacing = "0">
				<tr class = "odd_tr">
					<td class = "td_left">数据库地址</td>
					<td class = "td_right"><input type = "text" id = "db_host" name = "db_host" value = "<?php echo isset($db_host) ? $db_host : "localhost";?>" /></td>
				</tr>
				<tr>
					<td class = "td_left">数据库用户名</td>
					<td class = "td_right"><input type = "text" id = "db_user" name = "db_user" value = "<?php echo isset($db_user) ? $db_user : "";?>" /></td>
				</tr>
				<tr class = "odd_tr">
					<td class = "td_left">数据库密码</td>
					<td class = "td_right"><input type = "text" id = "db_pass" name = "db_pass" value = "<?php echo isset($db_pass) ? $db_pass : "";?>" /></td>
				</tr>
				<tr>
					<td class = "td_left">数据库名</td>
					<td class = "td_right"><input type = "text" id = "db_base" name = "db_base" value = "<?php echo isset($db_base) ? $db_base : "";?>" /></td>
				</tr>
				<tr class = "odd_tr">
					<td class = "td_left">数据库端口</td>
					<td class = "td_right"><input type = "text" id = "db_port" name = "db_port" value = "<?php echo isset($db_port) ? $db_port : "3306";?>" /></td>
				</tr>
			</table>
			<div class = "form_title2">管理员设置</div>
			<table border = "0" cellpadding = "0" cellspacing = "0">
				<tr class = "odd_tr">
					<td class = "td_left">用户名</td>
					<td class = "td_right"><input type = "text" id = "username" name = "username" value = "<?php echo isset($username) ? $username : "";?>" /></td>
				</tr>
				<tr>
					<td class = "td_left">登录手机号</td>
					<td class = "td_right"><input type = "text" id = "phone" name = "phone" value = "<?php echo isset($phone) ? $phone : "";?>" /></td>
				</tr>
				<tr class = "odd_tr">
					<td class = "td_left">登录密码</td>
					<td class = "td_right"><input type = "text" id = "password" name = "password" value = "<?php echo isset($password) ? $password : "";?>" /></td>
				</tr>
			</table>
			<input class = "form_button" type = "submit" value = "下一步" />
		</form>
		<?php } elseif ($step == 3) { ?>
		<div class = "bottom_title">安装结果</div>
		<table border = "0" cellpadding = "0" cellspacing = "0">
			<tr class = "odd_tr">
				<td colspan = "2">恭喜您安装成功，请牢记账号和密码</td>
			</tr>
			<tr>
				<td class = "td_left">登录手机号</td>
				<td class = "td_right"><?php echo $phone; ?></td>
			</tr>
			<tr class = "odd_tr">
				<td class = "td_left">登录密码</td>
				<td class = "td_right"><?php echo $password; ?></td>
			</tr>
			<tr>
				<td colspan = "2">前往<a href = "<?php echo WEB_ROOT . "index.php"; ?>">首页</a>或<a href = "<?php echo WEB_ROOT . "login.php"; ?>">登录</a></td>
			</tr>
		</table>
		<?php } ?>
	</div>
</div>
</body>
</html>