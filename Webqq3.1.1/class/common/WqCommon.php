<?php if (!defined('ITPK')) exit('You can not directly access the file.');

/**
 * WebQQ协议操作类公用包
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.1
 */

class WqCommon {

	/**
	 * 伪造发送请求时的来路地址
	 * @var string
	 */
	const REFERER_DEFAULT	= "http://qq.com";

	const REFERER_MEMBER	= "http://qun.qq.com/member.html";

	const REFERER_GETINFO	= "http://s.web2.qq.com/proxy.html?v=20130916001&callback=1&id=1";

	const REFERER_SEND		= "http://d.web2.qq.com/proxy.html?v=20130916001&callback=1&id=2";

	const REFERER_CHECK		= "https://ui.ptlogin2.qq.com/cgi-bin/login?daid=164&target=self&style=16&mibao_css=m_webqq&appid=501004106&enable_qlogin=0&no_verifyimg=1&s_url=http%3A%2F%2Fw.qq.com%2Fproxy.html&f_url=loginerroralert&strong_login=1&login_state=10&t=20131024001";

	/**
	 * 伪造用户发送请求的工具
	 * @var string
	 */
	const USERAGENT			= "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:37.0) Gecko/20100101 Firefox/37.0";

	/**
	 * 检查账号登录时是否需要验证码的请求信息
	 * @param string $uin QQ账号
	 * @return array
	 */
	public static function getCheckRequest($uin) {
		$request = array();
		$request['url'] = "https://ssl.ptlogin2.qq.com/check?pt_tea=1&uin={$uin}&appid=501004106&js_ver=10118&js_type=0&login_sig=wDvZ7LOkRd658*eeX74jKbxb05VNOnp7keIsEA2OIvWxDEMaiI1LP5dKiH3ehU9M&u1=http%3A%2F%2Fw.qq.com%2Fproxy.html&r=0.00760257832689426";
		$request['referer'] = WqCommon::REFERER_CHECK;
		return $request;
	}

	/**
	 * 获取验证码的请求信息
	 * @param string $uin QQ账号
	 * @param string $cap_cd 检查是否需要验证码时生成的某个数据
	 * @return array
	 */
	public static function getImageRequest($uin, $cap_cd) {
		$request = array();
		$request['url'] = "https://ssl.captcha.qq.com/getimage?aid=501004106&r=0.5919335373130703&uin={$uin}&cap_cd={$cap_cd}";
		$request['referer'] = WqCommon::REFERER_CHECK;
		return $request;
	}

	/**
	 * 握手登录的请求信息（第一次登录）
	 * @param string $uin QQ账号
	 * @param string $rsa_pw 经过各种加密的密码
	 * @param string $code 验证码
	 * @param string $verifysession 检查账号是否需要验证码时生成的某个值，或者获取验证码图片时生成的Cookie
	 * @return array
	 */
	public static function getFirstLoginRequest($uin, $rsa_pw, $code, $verifysession) {
		$request = array();
		$request['url'] = "https://ssl.ptlogin2.qq.com/login?u={$uin}&p={$rsa_pw}&verifycode={$code}&webqq_type=10&remember_uin=1&login2qq=1&aid=501004106&u1=http%3A%2F%2Fw.qq.com%2Fproxy.html%3Flogin2qq%3D1%26webqq_type%3D10&h=1&ptredirect=0&ptlang=2052&daid=164&from_ui=1&pttype=1&dumy=&fp=loginerroralert&action=0-25-668794&mibao_css=m_webqq&t=1&g=1&js_type=0&js_ver=10118&login_sig=wDvZ7LOkRd658*eeX74jKbxb05VNOnp7keIsEA2OIvWxDEMaiI1LP5dKiH3ehU9M&pt_randsalt=0&pt_vcode_v1=0&pt_verifysession_v1={$verifysession}";
		$request['referer'] = WqCommon::REFERER_CHECK;
		return $request;
	}

	/**
	 * 登录上线的请求信息（第二次登录）
	 * @param string $ptwebqq 第一次登录时生成的某个Cookie
	 * @param string $clientid 一个7位数的随机数字，开头一般是5
	 * @return array
	 */
	public static function getSecondLoginRequest($ptwebqq, $clientid) {
		$request = array();
		$request['url'] = "http://d.web2.qq.com/channel/login2";
		$request['param'] = "r=%7B%22ptwebqq%22%3A%22{$ptwebqq}%22%2C%22clientid%22%3A{$clientid}%2C%22psessionid%22%3A%22%22%2C%22status%22%3A%22online%22%7D";
		$request['referer'] = WqCommon::REFERER_SEND;
		return $request;
	}

	/**
	 * 获取所有消息的请求信息（心跳包）
	 * @param string $ptwebqq
	 * @param string $clientid
	 * @param string $psessionid
	 * @return array
	 */
	public static function getPollRequest($ptwebqq, $clientid, $psessionid) {
		$request = array();
		$request['url'] = "http://d.web2.qq.com/channel/poll2";
		$request['param'] = "r=%7B%22ptwebqq%22%3A%22{$ptwebqq}%22%2C%22clientid%22%3A{$clientid}%2C%22psessionid%22%3A%22{$psessionid}%22%2C%22key%22%3A%22%22%7D";
		$request['referer'] = WqCommon::REFERER_SEND;
		return $request;
	}

	/**
	 * 发送群消息
	 * @param array $rows
	 * @param string $from_uin
	 * @param string $reply
	 * @param int $msgid
	 */
	public static function getSendGroupMsgRequest($rows, $from_uin, $reply, $msgid) {
		$request = array();
		$request['url'] = "http://d.web2.qq.com/channel/send_qun_msg2";
		$request['param'] = "r=%7B%22group_uin%22%3A{$from_uin}%2C%22content%22%3A%22%5B%5C%22{$reply}%5C%22%2C%5B%5C%22font%5C%22%2C%7B%5C%22name%5C%22%3A%5C%22%E5%AE%8B%E4%BD%93%5C%22%2C%5C%22size%5C%22%3A10%2C%5C%22style%5C%22%3A%5B0%2C0%2C0%5D%2C%5C%22color%5C%22%3A%5C%22000000%5C%22%7D%5D%5D%22%2C%22face%22%3A522%2C%22clientid%22%3A" . $rows['clientid'] . "%2C%22msg_id%22%3A" . $msgid . "%2C%22psessionid%22%3A%22" . $rows['psessionid'] . "%22%7D";
		$request['referer'] = WqCommon::REFERER_SEND;
		return $request;
	}

	/**
	 * 发送私人消息
	 * @param array $rows
	 * @param string $from_uin
	 * @param string $reply
	 * @param int $msgid
	 */
	public static function getSendBuddyMsgRequest($rows, $from_uin, $reply, $msgid) {
		$request = array();
		$request['url'] = "http://d.web2.qq.com/channel/send_buddy_msg2";
		$request['param'] = "r=%7B%22to%22%3A{$from_uin}%2C%22content%22%3A%22%5B%5C%22{$reply}%5C%22%2C%5B%5C%22font%5C%22%2C%7B%5C%22name%5C%22%3A%5C%22%E5%AE%8B%E4%BD%93%5C%22%2C%5C%22size%5C%22%3A10%2C%5C%22style%5C%22%3A%5B0%2C0%2C0%5D%2C%5C%22color%5C%22%3A%5C%22000000%5C%22%7D%5D%5D%22%2C%22face%22%3A540%2C%22clientid%22%3A" . $rows['clientid'] . "%2C%22msg_id%22%3A" . $msgid . "%2C%22psessionid%22%3A%22" . $rows['psessionid'] . "%22%7D";
		$request['referer'] = WqCommon::REFERER_SEND;
		return $request;
	}

	/**
	 * 发送其他请求
	 * @param string $url 发送请求的链接地址
	 * @param string $param	发送请求的参数，如果此值不为空，那么发送的是POST请求
	 * @param string $referer 发送请求的来路地址
	 * @return array
	 */
	public static function getOtherRequest($url, $param = null, $referer = WqCommon::REFERER_DEFAULT) {
		$request = array();
		$request['url'] = trim($url);
		$request['param'] = trim($param);
		$request['referer'] = trim($referer);
		return $request;
	}

	public static function getUinVerProfile($uin, $type, $request, $rows) {
		$url = "http://s.web2.qq.com/api/get_stranger_info2";
		$post = "tuin=" . $uin . "&verifysession=&gid=0&code=" . $type . "-" . $request . "&vfwebqq=" . $rows['vfwebqq'] . "&t=" . time() . "559";
		$get = WqCommon::web_curl(WqCommon::getOtherRequest($url, $post, WqCommon::REFERER_GETINFO), $rows['cookie'], false);
		$arr = @json_decode($get, true);
		return ($arr && isset($arr['result']['nick'])) ? $arr['result']['nick'] : false;
	}

	public static function getQunVerProfile($gcode, $rows) {
		$url = "http://s.web2.qq.com/api/get_group_public_info2";
		$post = "gcode=" . $gcode . "&vfwebqq=" . $rows['vfwebqq'] . "&t=" . time() . "559";
		$get = WqCommon::web_curl(WqCommon::getOtherRequest($url, $post, WqCommon::REFERER_GETINFO), $rows['cookie'], false);
		$arr = @json_decode($get, true);
		return ($arr && isset($arr['result']['ginfo']['name'])) ? $arr['result']['ginfo']['name'] : false;
	}

	public static function setNewFriends($uin, $request, $gcode, $rows, $is_agree = true) {
		$op_type = $is_agree ? 2 : 3;
		$url1 = "http://d.web2.qq.com/channel/op_group_join_req?group_uin=" . $uin . "&req_uin=" . $request . "&msg=&op_type=" . $op_type . "&clientid=" . $rows['clientid'] . "&psessionid=" . $rows['psessionid'] . "&t=" . time() . "559";
		$url2 = "http://s.web2.qq.com/api/get_group_info_ext2?gcode=" . $gcode . "&vfwebqq=" . $rows['vfwebqq'] . "&t=" . time() . "559";
		WqCommon::web_curl(WqCommon::getOtherRequest($url1, null, WqCommon::REFERER_SEND), $rows['cookie'], false);
		WqCommon::web_curl(WqCommon::getOtherRequest($url2, null, WqCommon::REFERER_GETINFO), $rows['cookie'], false);
	}

	public static function getFriendInfoBySendUin($send_uin, $rows) {
		$url = "http://s.web2.qq.com/api/get_friend_info2?tuin=" . $send_uin . "&verifysession=&code=&vfwebqq=" . $rows['vfwebqq'] . "&t=" . time() . "559";
		$get = WqCommon::web_curl(WqCommon::getOtherRequest($url, null, WqCommon::REFERER_GETINFO), $rows['cookie'], false);
		$arr = @json_decode($get, true);
		return ($arr && isset($arr['result']['nick'])) ? $arr['result']['nick'] : false;
	}

	public static function getFriendUinBySendUin($send_uin, $rows) {
		$url = "http://s.web2.qq.com/api/get_friend_uin2?tuin=" . $send_uin . "&verifysession=&type=1&code=&vfwebqq=" . $rows['vfwebqq'] . "&t=" . time() . "559";
		$get = WqCommon::web_curl(WqCommon::getOtherRequest($url, null, WqCommon::REFERER_GETINFO), $rows['cookie'], false);
		$arr = @json_decode($get, true);
		return ($arr && isset($arr['result']['account'])) ? $arr['result']['account'] : false;
	}

	public static function getStrangerInfoBySendUin($send_uin, $rows) {
		$url = "http://s.web2.qq.com/api/get_stranger_info2?tuin=" . $send_uin . "&verifysession=&gid=0&code=&vfwebqq=" . $rows['vfwebqq'] . "&t=" . time() . "559";
		$get = WqCommon::web_curl(WqCommon::getOtherRequest($url, null, WqCommon::REFERER_GETINFO), $rows['cookie'], false);
		$arr = @json_decode($get, true);
		return ($arr && isset($arr['result']['nick'])) ? $arr['result']['nick'] : false;
	}

	/**
	 * 禁止某个人发言，也就是禁言
	 * @param int $group_uin
	 * @param int $member_uin
	 * @param string $time
	 * @param string $bkn
	 * @param string $cookie
	 * @return boolean
	 */
	public static function setMemberSpeech($group_uin, $member_uin, $time, $bkn, $cookie) {
		$url = "http://qinfo.clt.qq.com/cgi-bin/qun_info/set_group_shutup";
		$post = "gc=" . $group_uin . "&shutup_list=%5B%7B%22uin%22%3A" . $member_uin . "%2C%22t%22%3A" . $time . "%7D%5D&bkn=" . $bkn . "&src=qinfo_v2";
		$result = WqCommon::web_curl(WqCommon::getOtherRequest($url, $post), $cookie, false);
		$obj = @json_decode($result);
		return @$obj->ec == 0 ? true : false;
	}

	/**
	 * 修改群名片
	 * @param int $group_uin
	 * @param int $member_uin
	 * @param string $name
	 * @param string $bkn
	 * @param string $cookie
	 * @return boolean
	 */
	public static function updateMemberCard($group_uin, $member_uin, $name, $bkn, $cookie) {
		$url = "http://qun.qq.com/cgi-bin/qun_mgr/set_group_card";
		$post = "gc=" . $group_uin . "&u=" . $member_uin . "&name=" . urlencode($name) . "&bkn=" . $bkn;
		$result = WqCommon::web_curl(WqCommon::getOtherRequest($url, $post, WqCommon::REFERER_MEMBER), $cookie, false);
		$obj = @json_decode($result);
		return @$obj->ec == 0 ? true : false;
	}

	/**
	 * 移除群成员，也就是踢人
	 * @param int $group_uin
	 * @param int $member_uin
	 * @param string $bkn
	 * @param string $cookie
	 * @return boolean
	 */
	public static function removeMember($group_uin, $member_uin, $bkn, $cookie) {
		$url = "http://qun.qq.com/cgi-bin/qun_mgr/delete_group_member";
		$post = "gc=" . $group_uin . "&ul=" . $member_uin . "&flag=0&bkn=" . $bkn;
		$result = WqCommon::web_curl(WqCommon::getOtherRequest($url, $post), $cookie, false);
		$obj = @json_decode($result);
		return @$obj->ec == 0 ? true : false;
	}

	/**
	 * 获取群列表
	 * @param string $bkn
	 * @param string $cookie
	 * @return unknown
	 */
	public static function getGroupList($bkn, $cookie) {
		$url = "http://qun.qq.com/cgi-bin/qun_mgr/get_group_list";
		$post = "bkn=" . $bkn;
		$result = WqCommon::web_curl(WqCommon::getOtherRequest($url, $post), $cookie, false);
		$groupListObj = @json_decode($result);
		return $groupListObj;
	}

	/**
	 * 获取好友列表
	 * @param string $bkn
	 * @param string $cookie
	 * @return unknown
	 */
	public static function getFriendList($bkn, $cookie) {
		$url = "http://qun.qq.com/cgi-bin/qun_mgr/get_friend_list";
		$post = "bkn=" . $bkn;
		$result = WqCommon::web_curl(WqCommon::getOtherRequest($url, $post), $cookie, false);
		$friendListArray = @json_decode($result, true);
		return $friendListArray;
	}

	/**
	 * 查询群成员的信息
	 * @param int $group_uin
	 * @param int $start
	 * @param int $end
	 * @param int $sort
	 * @param string $bkn
	 * @param string $cookie
	 * @param string $key
	 * @return unknown
	 */
	public static function searchGroupMembers($group_uin, $start, $end, $sort, $bkn, $cookie, $key = "") {
		$url = "http://qun.qq.com/cgi-bin/qun_mgr/search_group_members";
		$key = DataUtil::is_empty($key) ? "" : ("&key=" . $key);
		$post = "gc=" . $group_uin . "&st=" . $start . "&end=" . $end . "&sort=" . $sort . $key . "&bkn=" . $bkn;
		$result = WqCommon::web_curl(WqCommon::getOtherRequest($url, $post), $cookie, false);
		$groupInfoObj = @json_decode($result, true);
		return $groupInfoObj;
	}

	/**
	 * 邀请别人加入QQ群，被邀请的人必须是自己的好友
	 * @param int $group_uin 群号码
	 * @param int $member_uin 被邀请的人
	 * @param string $bkn 机器人的bkn
	 * @param string $cookie 机器人的cookie
	 */
	public static function addGroupMember($group_uin, $member_uin, $bkn, $cookie) {
		$url = "http://qun.qq.com/cgi-bin/qun_mgr/add_group_member";
		$post = "gc=" . $group_uin . "&ul=" . $member_uin . "&bkn=" . $bkn;
		$result = WqCommon::web_curl(WqCommon::getOtherRequest($url, $post), $cookie, false);
		$obj = @json_decode($result);
		return @$obj->ec == 0 ? true : false;
	}

	/**
	 * 添加/取消群管理员，需要群主权限，也就是机器人必须是群主才行
	 * @param int $group_uin 群号码
	 * @param int $member_uin 需要添加/取消群管理员的QQ号
	 * @param int $option 添加/取消选项，当值为1时是添加，为0时是取消
	 * @param string $bkn 机器人的bkn
	 * @param string $cookie 机器人的cookie
	 * @return boolean
	 */
	public static function setGroupAdmin($group_uin, $member_uin, $option, $bkn, $cookie) {
		$url = "http://qun.qq.com/cgi-bin/qun_mgr/set_group_admin";
		$post = "gc=" . $group_uin . "&ul=" . $member_uin . "&op=" . $option . "&bkn=" . $bkn;
		$result = WqCommon::web_curl(WqCommon::getOtherRequest($url, $post), $cookie, false);
		$obj = @json_decode($result);
		return @$obj->ec == 0 ? true : false;
	}

	/**
	 * WebQQ发送请求工具
	 * @param array $request 请求信息数组
	 * @param string $sendcookie 发送的Cookie，当值为false时将不发送Cookie，默认值为false
	 * @param string $getcookie 是否获取所有的头部信息，一般用作获取Cookie时使用，默认值为true
	 * @param int $time_out 发送请求时的超时秒数,默认为60秒
	 * @return mixed
	 */
	public static function web_curl($request, $sendcookie = false, $getcookie = true, $time_out = 60) {
		$ch = curl_init($request['url']);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, $getcookie);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_REFERER, $request['referer']);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt($ch, CURLOPT_USERAGENT, WqCommon::USERAGENT);
		curl_setopt($ch, CURLOPT_TIMEOUT, $time_out);
		if ($request['param'] != null) {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $request['param']);
		}
		if ($sendcookie) {
			curl_setopt($ch, CURLOPT_COOKIE, $sendcookie);
		}
		$content = curl_exec($ch);
		curl_close($ch);
		return $content;
	}

	/**
	 * 从给出的数据中匹配出所需要的值
	 * @param string $data 原数据
	 * @param string $do 匹配类型
	 * @return string or array
	 */
	public static function pregs($data, $do){
		if ($do == 'login1') {
			preg_match("/ptuiCB\('(.*)','(.*)','(.*)','(.*)','(.*)',\s'(.*)'\);/U", $data, $array);
			return $array;
		} elseif ($do == 'login2') {
			preg_match("/ptuiCB\('(.*)','(.*)','(.*)','(.*)','(.*)',\s'(.*)'\);/U", $data, $array);
			return $array;
		} elseif ($do == 'cookie') {
			preg_match_all('/Set-Cookie:\s(.+);/iU', $data, $array);
			$return_cookie = array();
			foreach ($array[1] as $cookie) {
				$cookie_split = explode("=", $cookie);
				if (count($cookie_split) < 1 || $cookie_split[1] == "") {
					continue;
				}
				array_push($return_cookie, $cookie);
			}
			return $return_cookie;
		} elseif ($do == 'code') {
			$start = stripos($data, 'DOMAIN=qq.com;');
			$code = substr($data, $start);
			$code = str_replace('DOMAIN=qq.com;', '', $code);
			return trim($code);
		} elseif ($do == 'checkvc') {
			$arr2 = explode('ptui_checkVC', $data);
			$data = str_replace(array('\'', '(',');'), array('','',''), $arr2[1]);
			$arr = explode(',', $data);
			return $arr;
		}
	}

	/**
	 * 对Cookie值或携带Cookie的数组进行处理并返回处理后的值
	 * @param unknown $arr
	 * @param string $do
	 * @param unknown $cookies
	 * @return string or array
	 */
	public static function cookies($arr, $do = false, $cookies = array()) {
		if ($do) {
			foreach ($arr as $str) {
				if ($x = explode('=', $str) and trim($x[1]) != null) {
					$cookies[trim($x[0])] = trim($x[1]);
				}
			}
			return $cookies;
		} else {
			$cookie = '';
			foreach ($arr as $str) {
				$cookie .= $str . ';';
			}
			return $cookie;
		}
	}

	/**
	 * 处理数据
	 * @param unknown $arr
	 * @return string
	 */
	public static function arrcookie($arr) {
		$keys = array_keys($arr);
		$cookie = '';
		foreach ($keys as $key) {
			$cookie .= $key . '=' . $arr[$key] . ';';
		}
		return $cookie;
	}

	/**
	 * 根据获取的cookie提取skey
	 * @param string $cookie
	 * @return string
	 */
	public static function get_skey($cookie) {
		return preg_replace("/^(.*);skey=(.{0,12})(;.*)$/Uis", "\\2", $cookie);
	}

	/**
	 * 根据skey计算出bkn的值
	 * @param string $skey
	 * @return int
	 */
	public static function get_bkn($skey) {
		$hash = 5381;
		for($i=0; $i<strlen($skey); ++$i){
			$hash += ($hash << 5) + WqCommon::utf8_unicode($skey[$i]);
		}
		return $hash & 0x7fffffff;
	}

	/**
	 * 用于bkn的计算
	 * @param number|string $c
	 * @return number|boolean
	 */
	public static function utf8_unicode($c) {
		switch(strlen($c)) {
			case 1:
				return ord($c);
			case 2:
				$n = (ord($c[0]) & 0x3f) << 6;
				$n += ord($c[1]) & 0x3f;
				return $n;
			case 3:
				$n = (ord($c[0]) & 0x1f) << 12;
				$n += (ord($c[1]) & 0x3f) << 6;
				$n += ord($c[2]) & 0x3f;
				return $n;
			case 4:
				$n = (ord($c[0]) & 0x0f) << 18;
				$n += (ord($c[1]) & 0x3f) << 12;
				$n += (ord($c[2]) & 0x3f) << 6;
				$n += ord($c[3]) & 0x3f;
				return $n;
		}
	}

	/**
	 * 用来解析程序运行时的日志记录
	 * @param string $str
	 * @return string
	 */
	public static function encode_json($str) {
		return urldecode(json_encode($this->url_encode($str)));
	}

	/**
	 * 用来解析程序运行时的日志记录
	 * @param string $str
	 * @return string
	 */
	public static function url_encode($str) {
		if (is_array($str)) {
			foreach ($str as $key => $value) {
				$str[urlencode($key) ] = $this->url_encode($value);
			}
		} else {
			$str = urlencode($str);
		}
		return $str;
	}

}

?>