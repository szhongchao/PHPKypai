<?php 

/**
 * 回复工具类
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

class ReplyUtil {

	/**
	 * 把消息转换成JSON格式数据，例子：{"content":"123","number",1}
	 * @param string $content	要发送的消息
	 * @param int $number		此条消息发送的次数，默认为1次，如果为0则不发送
	 * @return string
	 */
	public static function getReply($content, $number = 1) {
		/**
		 * 如果$number不是一个数字或者小于0，则把$number重新赋值为0
		 */
		if (!is_numeric($number) || $number < 0) $number = 0;
		/**
		 * 为了账号安全和营造良好聊天环境，限制发送的次数最大为3
		 */
		if ($number > 3) $number = 3;

		$reply = array();
		$reply['content'] = $content;
		$reply['number'] = $number;

		/**
		 * 转换成JSON格式并返回
		 */
		return json_encode($reply);
	}

	/**
	 * 插件返回此方法内容将不回复消息（你也可以这样调用getReply("", 0)函数，也不会回复消息）
	 * @return string
	 */
	public static function noReply() {
		$reply = array();
		$reply['content'] = "";
		$reply['number'] = 0;
		return json_encode($reply);
	}

}

?>