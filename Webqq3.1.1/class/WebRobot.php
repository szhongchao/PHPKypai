<?php if (!defined('ITPK')) exit('You can not directly access the file.');

/**
 * Webqq操作类
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

class WebRobot {

	private $robot;
	private $robotdat;
	private $robotPer;
	private $robotdatPer;
	private $runlogPer;
	private $groupmemberPer;
	private $groupmemberinfoPer;
	private $robotfriendsPer;
	private $verificationPer;
	private $cookiePer;
	private $pluginPer;

	/**
	 * 实例化Webqq操作类
	 * @param unlome $robot
	 * @param unlome $robotdat
	 * @param WebrobotPer $robotPer
	 * @param WebrobotdatPer $robotdatPer
	 * @param WebrunlogPer $runlogPer
	 * @param WebgroupmemberPer $groupmemberPer
	 * @param WebrobotfriendsPer $robotfriendsPer
	 * @param WebverificationPer $verificationPer
	 * @param WebcookiePer $cookiePer
	 * @param WebpluginPer $pluginPer
	 */
	public function __construct( $robot, $robotdat, $robotPer, $robotdatPer, $runlogPer, $groupmemberPer, $groupmemberinfoPer, $robotfriendsPer, $verificationPer, $cookiePer, $pluginPer ) {
		$this->robot = $robot;
		$this->robotdat = $robotdat;
		$this->robotPer = $robotPer;
		$this->robotdatPer = $robotdatPer;
		$this->runlogPer = $runlogPer;
		$this->groupmemberPer = $groupmemberPer;
		$this->groupmemberinfoPer = $groupmemberinfoPer;
		$this->robotfriendsPer = $robotfriendsPer;
		$this->verificationPer = $verificationPer;
		$this->cookiePer = $cookiePer;
		$this->pluginPer = $pluginPer;
	}

	/**
	 * 检查是否需要验证码
	 * @return number
	 */
	public function checkVerify() {
		$check = WqCommon::web_curl(WqCommon::getCheckRequest($this->robot['uin']));
		$arr = WqCommon::pregs($check, 'checkvc');
		if (array_key_exists(0, $arr) && $arr[0] == 0) {
			$this->runlogPer->insertMe($this->robot, "不需要验证码");
			$carr = WqCommon::pregs($check, 'cookie');
			$cookie = WqCommon::cookies($carr);
			$verifysession = $arr[3];
			$status = StatusUtil::LOADING_RSA;
			$this->robotPer->updateProfile($this->robot, "code", trim($arr[1]));
		} else {
			$this->runlogPer->insertMe($this->robot, "需要验证码");
			$get = $check = WqCommon::web_curl(WqCommon::getImageRequest($this->robot['uin'], $arr[1]));;
			$arr = WqCommon::pregs($get, 'cookie');
			$verifysession = str_replace(array('verifysession='), array(''), $arr[0]);
			$cookie = trim($arr[0]);
			$code = WqCommon::pregs($get, 'code');
			$verifysession = trim($verifysession);
			$this->verificationPer->replaceMe($this->robot['id'], addslashes($code));
			$status = StatusUtil::LOADING_VERIFY;
		}
		$this->robotPer->updateProfile($this->robot, "status", $status);
		$this->robotPer->updateProfile($this->robot, "verifysession", $verifysession);
		$this->robotPer->updateProfile($this->robot, "cookie", $cookie);
		return $status;
	}

	/**
	 * 第一次登录
	 * @param unknown $robot
	 * @return number
	 */
	public function login($robot) {
		$get = WqCommon::web_curl(WqCommon::getFirstLoginRequest($robot['uin'], $robot['rsa_pass'], $robot['code'], $robot['verifysession']), $robot['cookie']);
		$arr = WqCommon::pregs($get, 'cookie');
		$cookie = WqCommon::cookies($arr);
		$cookiearr = WqCommon::cookies($arr, 1);
		$ptwebqq = $cookiearr['ptwebqq'];
		$arr = WqCommon::pregs($get, 'login1');
		$is_succes = false;
		if (array_key_exists(1, $arr) && $arr[1] == 0) {
			$this->runlogPer->insertMe($this->robot, "第一次登录成功");
			$get = WqCommon::web_curl(WqCommon::getOtherRequest($arr[3]), $cookie);
			$arr = WqCommon::pregs($get, 'cookie');
			$cookie = WqCommon::cookies($arr);
			$cookiearr = WqCommon::cookies($arr, 1, $cookiearr);
			$clientid = '5' . rand(100000, 999999);
			$is_succes = $this->login2($clientid, $ptwebqq, WqCommon::arrcookie($cookiearr), $cookie);
		} elseif (array_key_exists(1, $arr)) {
			$this->runlogPer->insertMe($this->robot, "第一次登录失败，错误信息:" . $arr[5]);
			if (DataUtil::is_contain("无法登录", $arr[5])) {
				$this->robotPer->setRobotInit($this->robot);
			}
		} else {
			$this->runlogPer->insertMe($this->robot, "第一次登录失败，接口请求错误");
		}
		$this->robotPer->removeLoginRecord($this->robot);
		if (!$is_succes) {
			$this->robotPer->updateProfile($this->robot, "status", StatusUtil::INIT);
		} else {
			return StatusUtil::ONLINE;
		}
		return StatusUtil::INIT;
	}

	/**
	 * 第二次登录或重新连接
	 * @param string $clientid
	 * @param string $ptwebqq
	 * @param string $cookiearr
	 * @param string $cookie
	 * @param string $is_reconnection
	 * @return boolean
	 */
	public function login2($clientid, $ptwebqq, $cookiearr, $cookie, $is_reconnection = false) {
		$get = WqCommon::web_curl(WqCommon::getSecondLoginRequest($ptwebqq, $clientid), $cookie, false);
		$arr = json_decode($get, true);
		$is_succes = false;
		$tip = $is_reconnection ? "机器人重连" : "第二次登录";
		if (array_key_exists('retcode', $arr) && $arr['retcode'] == 0) {
			$this->cookiePer->replaceMe($this->robot['id'], $cookiearr, $ptwebqq, $arr['result']['vfwebqq'], $arr['result']['psessionid'], $clientid);
			if (!$is_reconnection) {
				$this->robotPer->updateProfile($this->robot, "status", StatusUtil::ONLINE);
				$this->robotPer->updateProfile($this->robot, "cookie", $cookie);
			}
			$skey = preg_replace("/^(.*);skey=(.{0,12})(;.*)$/Uis", "\\2", $cookiearr);
			$this->robotPer->updateSkeyAndBkn($this->robot, $skey, WqCommon::get_bkn($skey));
			$this->robot = $this->robotPer->getMeByUinAndSecret($this->robot['uin'], $this->robot['secret']);
			$this->runlogPer->insertMe($this->robot, $tip . "成功");
			$is_succes = true;
		} elseif (array_key_exists('retcode', $arr) && $arr['retcode'] == 108) {
			$this->runlogPer->insertMe($this->robot, $tip . '失败，请在安全中心检查QQ是否开启了登录限制');
		} elseif (array_key_exists('retcode', $arr)) {
			$this->runlogPer->insertMe($this->robot, $tip . "失败，错误信息:" . $arr['retcode'] . $arr['errmsg']);
		} else {
			$this->runlogPer->insertMe($this->robot, $tip . '失败，接口请求错误');
		}
		return $is_succes;
	}

	/**
	 * 心跳包
	 * @param array $rows
	 * @return mixed
	 */
	public function poll($rows) {
		$get = WqCommon::web_curl(WqCommon::getPollRequest(trim($rows['ptwebqq']), trim($rows['clientid']), trim($rows['psessionid'])), trim($rows['cookie']), false);
		return json_decode($get, true);
	}

	/**
	 * 处理机器人收到的消息，并进行分类处理
	 * @param array $arr
	 */
	public function process_message($poll, $rows) {
		foreach ($poll['result'] as $news) {
			if ($news['poll_type'] == 'message' && ($news['value']['msg_type'] == 9 || $news['value']['msg_type'] == 10)) {
				//好友消息
				$this->personalMsg($news, $rows);
			} elseif ($news['poll_type'] == 'group_message') {
				//群消息
				$this->groupMsg($news, $rows);
			} elseif ($news['poll_type'] == 'sys_g_msg') {
				//加群验证消息
				$this->sysGroupMsg($news, $rows);
			} elseif ($news['poll_type'] == 'sess_message') {
				//临时会话消息
			} elseif ($news['poll_type'] == 'discu_message') {
				//讨论组消息
			} elseif ($news['poll_type'] == 'buddies_status_change') {
				//好友状态改变提醒
			}
		}
	}

	/**
	 * 是否处理这条消息
	 * @param int $from_uin 消息来源
	 * @param int $msg_type 消息类型
	 * @return boolean
	 */
	public function is_handler_msg($from_uin, $msg_type) {
		if ($msg_type == MsgUtil::GROUP_MSG || $msg_type == MsgUtil::GROUP_VERIFICATION) {
			if ($this->robotdat['is_group_black_list'] == 1) {
				if (DataUtil::is_equal(explode(",", $this->robotdat['group_black_list']), $from_uin, true)) {
					return false;
				}
			}
			if ($this->robotdat['is_group_white_list'] == 1) {
				if (!DataUtil::is_equal(explode(",", $this->robotdat['group_white_list']), $from_uin, true)) {
					return false;
				}
			}
			return true;
		} elseif ($msg_type == MsgUtil::PERSONAL_MSG) {
			if ($this->robotdat['is_qq_black_list'] == 1) {
				if (DataUtil::is_equal(explode(",", $this->robotdat['qq_black_list']), $from_uin, true)) {
					return false;
				}
			}
			if ($this->robotdat['is_qq_white_list'] == 1) {
				if (!DataUtil::is_equal(explode(",", $this->robotdat['qq_white_list']), $from_uin, true)) {
					return false;
				}
			}
			return true;
		}
		return false;
	}

	/**
	 * 群消息
	 * @param unknown $news
	 * @param unknown $rows
	 */
	public function groupMsg($news, $rows) {
		if ($this->robot['is_group_speech'] == 1) {
			$contentArray = $news['value']['content'];
			$msg = @MsgUtil::dealMsgArray($contentArray);
			$group_uin = $news['value']['info_seq'];
			$send_uin = $news['value']['send_uin'];
			$from_uin = $news['value']['from_uin'];
			$this->runlogPer->insertMe($this->robot, "群消息:" . $msg);
			$member_uin = WqCommon::getFriendUinBySendUin($send_uin, $rows);
			if ($member_uin) {
				if ($this->is_handler_msg($group_uin, MsgUtil::GROUP_MSG) && $this->is_handler_msg($member_uin, MsgUtil::PERSONAL_MSG)) {
					$webGroup = $this->setGroupMember($rows, $group_uin, $member_uin, $send_uin);
					$this->runPluginFunction($webGroup, $rows, $msg, MsgUtil::GROUP_MSG, $from_uin);
				}
			} else {
				$this->runlogPer->insertMe($this->robot, "消息发送者QQ号获取失败");
			}
		}
	}

	/**
	 * 好友消息
	 */
	public function personalMsg($news, $rows) {
		if ($this->robot['is_personal_speech'] == 1) {
			$from_uin = $news['value']['from_uin'];
			$contentArray = $news['value']['content'];
			$msg = @MsgUtil::dealMsgArray($contentArray);
			$this->runlogPer->insertMe($this->robot, "好友消息:" . $msg);
			$send_uin = WqCommon::getFriendUinBySendUin($from_uin, $rows);
			if ($send_uin) {
				if ($this->is_handler_msg($send_uin, MsgUtil::PERSONAL_MSG)) {
					$friend = $this->setFriendProfile($rows, $from_uin, $send_uin);
					$this->runPluginFunction($friend, $rows, $msg, MsgUtil::PERSONAL_MSG, $from_uin);
				}
			} else {
				$this->runlogPer->insertMe($this->robot, "消息发送者QQ号获取失败");
			}
		}
	}

	/**
	 * 加群验证
	 */
	public function sysGroupMsg($news, $rows) {
		if ($news['value']['type'] == 'group_request_join') {
			$from_uin = $news['value']['from_uin'];
			$to_uin = $news['value']['to_uin'];
			$request_uin = $news['value']['request_uin'];
			$ver_msg = $news['value']['msg'];
			$type = $news['value']['type'];
			$gcode = $news['value']['gcode'];
			$t_gcode = $news['value']['t_gcode'];
			if ($this->is_handler_msg($t_gcode, MsgUtil::GROUP_VERIFICATION)) {
				$nick = WqCommon::getUinVerProfile($request_uin, $type, $from_uin, $rows);
				$qunm = WqCommon::getQunVerProfile($gcode, $rows);
				$nick = !$nick ? "神秘人" : $nick;
				$qunm = !$qunm ? "本群" : $qunm;
				if ($this->robotdat['is_agree_add_group'] == 1) {
					WqCommon::setNewFriends($from_uin, $request_uin, $gcode, $rows, true);
					$agree_add_group_clues = $this->robotdat['agree_add_group_clues'];
					if (DataUtil::is_empty($agree_add_group_clues)) {
						$agree_add_group_clues = "{$nick}申请加入{$qunm}，验证消息：{$ver_msg}，" . $this->robot['name'] . "已经同意";
					} else {
						$agree_add_group_clues = preg_replace('/\[name\]/Uis', $nick, $agree_add_group_clues);
						$agree_add_group_clues = preg_replace('/\[qname\]/Uis', $qunm, $agree_add_group_clues);
						$agree_add_group_clues = preg_replace('/\[msg\]/Uis', $ver_msg, $agree_add_group_clues);
					}
					if ($this->robot['is_reply'] == 1) $this->sendmg($rows, $news['value']['from_uin'], $agree_add_group_clues, $t_gcode);
				} elseif($this->robotdat['is_refuse_add_group'] == 1) {
					WqCommon::setNewFriends($from_uin, $request_uin, $gcode, $rows, false);
					$refuse_add_group_clues = $this->robotdat['refuse_add_group_clues'];
					if (DataUtil::is_empty($refuse_add_group_clues)) {
						$refuse_add_group_clues = "{$nick}申请加入{$qunm}，验证消息：{$ver_msg}，" . $this->robot['name'] . "已经拒绝";
					} else {
						$refuse_add_group_clues = preg_replace('/\[name\]/Uis', $nick, $refuse_add_group_clues);
						$refuse_add_group_clues = preg_replace('/\[qname\]/Uis', $qunm, $refuse_add_group_clues);
						$refuse_add_group_clues = preg_replace('/\[msg\]/Uis', $ver_msg, $refuse_add_group_clues);
					}
					if ($this->robot['is_reply'] == 1) $this->sendmg($rows, $news['value']['from_uin'], $refuse_add_group_clues, $t_gcode);
				} else {
					$add_group_clues = $this->robotdat['add_group_clues'];
					if (DataUtil::is_empty($add_group_clues)) {
						$add_group_clues = "{$nick}申请加入{$qunm}，验证消息：{$ver_msg}";
					} else {
						$add_group_clues = preg_replace('/\[name\]/Uis', $nick, $add_group_clues);
						$add_group_clues = preg_replace('/\[qname\]/Uis', $qunm, $add_group_clues);
						$add_group_clues = preg_replace('/\[msg\]/Uis', $ver_msg, $add_group_clues);
					}
					if ($this->robot['is_reply'] == 1) $this->sendmg($rows, $news['value']['from_uin'], $add_group_clues, $t_gcode);
				}
			}
		}
	}

	public function runPluginFunction($object, $rows, $msg, $msg_type, $from_uin) {
		$reply = false;
		//开启了监控所有消息并启用状态的插件
		$plugins1 = $this->pluginPer->getMe(1, 1);
		if ($plugins1) {
			foreach ($plugins1 as $plugin) {
				$plugin_type = explode(PART, $plugin['plugin_type']);
				if (!DataUtil::is_equal($plugin_type, $msg_type, true)) {
					continue;
				}
				$reply = $this->runPluginMain($plugin['class_name'], $msg, $msg_type, $object, trim($rows['cookie']));
				if ($reply) {
					break;
				}
			}
		}
		if (!$reply && $msg_type != MsgUtil::GROUP_VERIFICATION) {
			//未开启监控所有消息并启用状态的插件
			$plugins2 = $this->pluginPer->getMe(1, 0);
			/**
			 * 如果有开启的插件，则进入判断
			 */
			if ($plugins2) {
				foreach ($plugins2 as $plugin) {
					$instruction = explode(PART, $plugin['instruction']);
					$instruction_type = explode(PART, $plugin['instruction_type']);
					$plugin_type = explode(PART, $plugin['plugin_type']);
					if ((count($instruction) != count($instruction_type)) || (!DataUtil::is_equal($plugin_type, $msg_type, true)) || ($object['plugin_id'] != 0 && $object['plugin_id'] != $plugin['id'])) {
						continue;
					}
					$is_meet_plugin = false;
					if ($object['plugin_id'] == 0) {
						for ($k = 0; $k < count($instruction); $k++) {
							if ($instruction_type[$k] != InstructionUtil::EQUAL && $instruction_type[$k] != InstructionUtil::START_CONTAIN && $instruction_type[$k] != InstructionUtil::END_CONTAIN && $instruction_type[$k] != InstructionUtil::IS_CONTAIN) {
								continue;
							}
							if (($instruction_type[$k] == InstructionUtil::EQUAL && DataUtil::is_equal($instruction[$k], $msg))
								|| ($instruction_type[$k] == InstructionUtil::START_CONTAIN && DataUtil::start_contain($instruction[$k], $msg))
								|| ($instruction_type[$k] == InstructionUtil::END_CONTAIN && DataUtil::end_contain($instruction[$k], $msg))
								|| ($instruction_type[$k] == InstructionUtil::IS_CONTAIN && DataUtil::is_contain($instruction[$k], $msg)))
							{
								$is_meet_plugin = true;
								break;
							}
						}
						if (!$is_meet_plugin) {
							continue;
						}
						if ($msg_type == MsgUtil::GROUP_MSG) {
							$this->groupmemberPer->updatePluginId($object['id'], $plugin['id']);
						} elseif ($msg_type == MsgUtil::PERSONAL_MSG) {
							$this->robotfriendsPer->updatePluginId($object['id'], $plugin['id']);
						}
					}
					$reply = $this->runPluginMain($plugin['class_name'], $msg, $msg_type, $object, trim($rows['cookie']));
					if ($reply) {
						break;
					}
				}
			}
		}

		if ($this->robot['is_reply'] == 1) {
			/**
			 * 如果$reply为false则调用茉莉机器人插件回复群成员的回答
			 */
			if (!$reply) $reply = $this->runPluginMain("WebapiHandler", $msg, $msg_type, $object, trim($rows['cookie']));

			$rj = json_decode($reply);
			$content = "";
			$number = 0;
			if ($rj) {
				$content = $rj->content;
				$number = $rj->number;
			}
			$number = $number > 3 ? 3 : $number;
			if ($number > 0 && !DataUtil::is_empty($content)) {
				for ($i = 0; $i < $number; $i++) {
					$this->sendmg($rows, $from_uin, $content, isset($object['group_uin']) ? $object['group_uin'] : 0);
				}
			}
		}
	}

	/**
	 * 发送消息
	 */
	public function sendmg($rows, $from_uin, $reply, $group_uin = 0) {
		$reply = MsgUtil::chuliMsg($reply);
		$msgid = rand(5000000, 5999999);
		if ($group_uin != 0) {
			$request = WqCommon::getSendGroupMsgRequest($rows, $from_uin, $reply, $msgid);
		} else {
			$request = WqCommon::getSendBuddyMsgRequest($rows, $from_uin, $reply, $msgid);
		}
		$get = WqCommon::web_curl($request, $rows['cookie'], false);
		$rerow = json_decode($get, true);
		if (@array_key_exists('retcode', $rerow) && $rerow['retcode'] == 0) {
			$this->runlogPer->insertMe($this->robot, "回复成功:" . $reply, $group_uin);
		} else {
			$this->runlogPer->insertMe($this->robot, "回复失败:" . $reply, $group_uin);
		}
	}

	/**
	 * 获取群成员信息，当没有记录时自动新增记录
	 */
	public function setGroupMember($rows, $group_uin, $member_uin, $send_uin) {
		$webGroup = $this->groupmemberPer->getMe($this->robot['id'], trim($group_uin), trim($member_uin));
		if (!$webGroup) {
			$nick_name = @WqCommon::getStrangerInfoBySendUin($send_uin, $rows);
			$nick_name = !$nick_name ? "神秘人" : $nick_name;
			@$this->groupmemberPer->insertMe($this->robot['id'], $group_uin, $member_uin, addslashes($nick_name));
			$webGroup = $this->groupmemberPer->getMe($this->robot['id'], $group_uin, $member_uin);
		}
		return $webGroup;
	}

	/**
	 * 获取好友信息，当没有记录时自动新增记录
	 */
	public function setFriendProfile($rows, $from_uin, $friend_uin) {
		$friend = $this->robotfriendsPer->getMeByUin($this->robot, $friend_uin);
		if (!$friend) {
			$nick_name = @WqCommon::getFriendInfoBySendUin($from_uin, $rows);
			$nick_name = !$nick_name ? "神秘人" : $nick_name;
			$this->robotfriendsPer->insertMe($this->robot, $friend_uin, addslashes($nick_name), 0);
		}
		return $friend;
	}

	/**
	 * 运行插件main方法
	 * @param string $plugin_class_name
	 * @param string $msg
	 * @param int $msg_type
	 * @param array $object
	 * @param string $cookie
	 * @return string 插件返回的字符
	 */
	public function runPluginMain($plugin_class_name, $msg, $msg_type, $object = null, $cookie = null) {
		$pluginHandler = null;
		eval("@\$pluginHandler = new " . $plugin_class_name . "();");
		if ($pluginHandler == null) return ReplyUtil::noReply();
		$pluginHandler->init($this->robot, $this->robotdat, $msg, $msg_type, $cookie, $object, $this->robotPer, $this->robotdatPer, $this->runlogPer, $this->groupmemberPer, $this->groupmemberinfoPer, $this->robotfriendsPer, $this->pluginPer);
		return @$pluginHandler->main();
	}

	/**
	 * 重新给机器人属性赋值
	 * @param unknown $robot
	 * @param unknown $robotdat
	 */
	public function setRobot($robot, $robotdat) {
		$this->robot = $robot;
		$this->robotdat = $robotdat;
	}

}

?>