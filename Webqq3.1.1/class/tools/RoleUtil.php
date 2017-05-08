<?php if (!defined('ITPK')) exit('You can not directly access the file.');

/**
 * 角色权限判断
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

class RoleUtil {

	/**
	 * 进入后台管理的权限
	 */
	const GOADMIN				= 16;

	/**
	 * 修改后台基本设置的权限
	 */
	const UPDATEBASE			= 15;

	/**
	 * 用户管理的权限
	 */
	const MANAGERUSER			= 14;

	/**
	 * 角色管理的权限
	 */
	const MANAGERROLE			= 13;

	/**
	 * 配置续期的权限
	 */
	const MANAGERRENEWAL		= 12;

	/**
	 * 进插件页面的权限
	 */
	const GOPLUGIN				= 4;

	/**
	 * 聊天室发言的权限
	 */
	const CHATSPEECH			= 3;

	/**
	 * 进入聊天室的权限
	 */
	const GOCHAT				= 2;

	/**
	 * 进入网站的权限
	 */
	const GOINDEX				= 1;

	/**
	 * 初始设定
	 */
	const INIT					= 0;

	/**
	 * 默认的角色权限设定
	 */
	const DEFAULTUSER			= 14;

	/**
	 * 游客的权限设定
	 * @var string
	 */
	const TOURISTS				= 6;

	/**
	 * 获取所有设定好的权限值和权限名称
	 * @return multitype:string
	 */
	public static function getRoleArray() {
		$roles = array(
			RoleUtil::GOADMIN			=> '访问后台管理',
			RoleUtil::UPDATEBASE		=> '后台基本设置',
			RoleUtil::MANAGERUSER		=> '用户管理',
			RoleUtil::MANAGERROLE		=> '角色管理',
			RoleUtil::MANAGERRENEWAL	=> '续期配置',
			RoleUtil::GOPLUGIN			=> '访问插件中心',
			RoleUtil::CHATSPEECH		=> '聊天室发言',
			RoleUtil::GOCHAT			=> '访问聊天室',
			RoleUtil::GOINDEX			=> '访问网站',
		);
		return $roles;
	}

	/**
	 * 判断用户是否有权限访问某内容
	 * @param string $jurisdiction 用户的角色权限
	 * @param string $power 用户要访问的内容权限设定
	 * @return boolean
	 */
	public static function getRolePower($jurisdiction, $power) {
		$power = pow(2, $power);
		return (($jurisdiction & $power) == $power) ? true : false;
	}

	/**
	 * 获取所有的权限值和权限名称，并且生成HTML代码
	 * @param string $jurisdiction
	 * @param string $name
	 * @param boolean $is_disabled
	 * @return multitype:string
	 */
	public static function getRoleCheckedHtml($jurisdiction, $name, $is_disabled = false) {
		$is_disabled = $is_disabled ? "disabled = \"disabled\"" : "";
		$checkedHtmlArray = array();
		$roleArray = RoleUtil::getRoleArray();
		foreach ($roleArray as $key=>$value) {
			if (RoleUtil::getRolePower($jurisdiction, $key)) {
				array_push($checkedHtmlArray, "<label class = \"label_checkbox\"><input class = \"form_checkbox\" type = \"checkbox\" name = \"{$name}\" value = \"{$key}\" checked = \"checked\" {$is_disabled} />{$value}</label>");
			} else {
				array_push($checkedHtmlArray, "<label class = \"label_checkbox\"><input class = \"form_checkbox\" type = \"checkbox\" name = \"{$name}\" value = \"{$key}\" {$is_disabled} />{$value}</label>");
			}
		}
		return $checkedHtmlArray;
	}

	/**
	 * 判断用户是否有权限访问某内容,如果没有权限则跳转页面给出相应的提示语
	 * @param int $jurisdiction
	 * @param int $power
	 * @param string $msg
	 */
	public static function findRole($jurisdiction, $power, $msg) {
		if (!RoleUtil::getRolePower($jurisdiction, $power)) {
			$msg = urlencode($msg);
			header("location:tip.php?msg={$msg}");
			exit();
		}
	}
}

?>