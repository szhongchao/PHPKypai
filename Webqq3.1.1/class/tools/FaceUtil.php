<?php if (!defined('ITPK')) exit('You can not directly access the file.');

/**
 * QQ表情
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

class FaceUtil {

	/**
	 * 根据表情代码获取相应的QQ表情（代码范围是0-170，否则返回空字符）
	 * @param int $code
	 * @return string
	 */
	static function get($code) {
		if (!is_numeric($code) || $code < 0 || $code > 170) {
			return "";
		}
		return "[face" . $code . "end]";
	}

}

?>