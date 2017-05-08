<?php if (!defined('ITPK')) exit('You can not directly access the file.');

/**
 * 消息处理工具
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

class MsgUtil {

	/**
	 * 群成员消息
	 * @var int
	 */
	const GROUP_MSG				= 1;

	/**
	 * 私人消息
	 * @var int
	 */
	const PERSONAL_MSG			= 2;

	/**
	 * 群验证系统消息
	 * @var int
	 */
	const GROUP_VERIFICATION	= 3;

	/**
	 * 组合消息，包括消息中的表情
	 * @param array $contentArray
	 * @return string
	 */
	public static function dealMsgArray($contentArray) {
		$msg = "";
		if (is_array($contentArray)) {
			for ($k = 1; $k < count($contentArray); $k++) {
				if (is_array($contentArray[$k])) {
					if (is_numeric($contentArray[$k][1]) && $contentArray[$k][1] != "") {
						$msg .= "[face".$contentArray[$k][1]."end]";
					}
					continue;
				}
				if (trim($contentArray[$k]) == "") {
					continue;
				}
				$msg .= $contentArray[$k];
			}
		}
		return addslashes(trim($msg));
	}

	/**
	 * 处理即将发送的消息
	 * @param string $reply
	 * @return string
	 */
	public static function chuliMsg($reply) {
		$reply = preg_replace("/\r\n/is", UCR, $reply);
		$reply = preg_replace('/"/is', '\\\\"', $reply);
		$reply = preg_replace("/&(.*?);/is", '', $reply);
		if (count(explode("[face", $reply)) > 1) {
			$reply = preg_replace('/\[face(\d{1,3})end\]/is', '%5C%22%2C%5B%5C%22face%5C%22%2C\\1%5D%2C%5C%22', $reply);
		}
		return addslashes(trim($reply));
	}
}

?>