<?php if (!defined('ITPK')) exit('You can not directly access the file.');

/**
 * 错误信息输出类
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

class ErrorUtil {

	/**
	 * 输出错误信息
	 * @param unknown $msg
	 * @param string $type
	 * @param string $charset
	 * @param string $is_die
	 */
	static function put($msg, $type = "plain", $charset = "utf-8", $is_die = true) {
		header("content-type:text/{$type}; charset={$charset}");
		echo "温馨提示：\r\n";
		echo $msg;
		if ($is_die) die();
	}

}

?>