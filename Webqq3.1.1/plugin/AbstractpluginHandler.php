<?php if (!defined('ITPK')) exit('You can not directly access the file.');

/**
 * 机器人插件抽象类
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

class AbstractpluginHandler extends WebDBConnection {

	protected $robot;
	protected $robotdat;
	protected $msg;
	protected $msg_type;
	protected $cookie;
	protected $object;
	protected $robotPer;
	protected $robotdatPer;
	protected $runlogPer;
	protected $groupmemberPer;
	protected $groupmemberinfoPer;
	protected $robotfriendsPer;
	protected $pluginPer;

	/**
	 * 初始化
	 * @param unknown $robot 机器人属性
	 * @param unknown $robotdat 机器人附加属性
	 * @param string $msg 接收到的消息
	 * @param string $msg_type 消息类型
	 * @param string $cookie 机器人cookie信息
	 * @param array $object 消息发送者对象
	 * @param WebrobotPer $robotPer 机器人操作类（数据库操作）
	 * @param WebrobotdatPer $robotdatPer 机器人附加属性操作类（数据库操作）
	 * @param WebrunlogPer $runlogPer 运行记录类
	 * @param WebgroupmemberPer $groupmemberPer 群成员操作类
	 * @param WebgroupmemberinfoPer $groupmemberinfoPer 群成员操作类
	 * @param WebrobotfriendsPer $robotfriendsPer 群成员操作类
	 * @param WebPluginPer $pluginPer 插件操作类
	 */
	public function init($robot, $robotdat, $msg, $msg_type, $cookie, $object, $robotPer, $robotdatPer, $runlogPer, $groupmemberPer, $groupmemberinfoPer, $robotfriendsPer, $pluginPer) {
		$this->robot = $robot;
		$this->robotdat = $robotdat;
		$this->msg = $msg;
		$this->msg_type = $msg_type;
		$this->cookie = $cookie;
		$this->object = $object;
		$this->robotPer = $robotPer;
		$this->robotdatPer = $robotdatPer;
		$this->runlogPer = $runlogPer;
		$this->groupmemberPer = $groupmemberPer;
		$this->groupmemberinfoPer = $groupmemberinfoPer;
		$this->robotfriendsPer = $robotfriendsPer;
		$this->pluginPer = $pluginPer;
	}

	/**
	 * 判断QQ是不是机器人创建者
	 * @param $uin 要判断的QQ号，如果为false，或者不传入此参数，那么判断的是消息发送者的QQ号
	 * @return boolean
	 */
	public function is_permissions($uin = false) {
		/**
		 * 获取机器人的创建者QQ号
		 */
		$createUin = $this->robot['create_uin'];
		if ($uin) {
			return DataUtil::is_equal($uin, $createUin);
		}
		if ($this->msg_type == MsgUtil::GROUP_MSG && $this->object['member_uin'] == $createUin) {
			return true;
		} elseif ($this->msg_type == MsgUtil::PERSONAL_MSG && $this->object['friend_uin'] == $createUin) {
			return true;
		} elseif ($this->msg_type == MsgUtil::GROUP_VERIFICATION && $this->object['from_uin'] == $createUin) {
			return true;
		}
		return false;
	}

	/**
	 * 判断QQ是不是机器人管理员
	 * @param $uin 要判断的QQ号，如果为false，或者不传入此参数，那么判断的是消息发送者的QQ号
	 * @return boolean
	 */
	public function is_manager($uin = false) {
		/**
		 * 获取机器人管理员
		 */
		$manager_uin = $this->robotdat['manager_uin'];
		if (!$manager_uin || DataUtil::is_empty(trim($manager_uin))) {
			return false;
		}
		if ($uin) {
			return DataUtil::is_equal(explode(",", $manager_uin), $uin, true);
		}
		if ($this->msg_type == MsgUtil::GROUP_MSG && DataUtil::is_equal(explode(",", $manager_uin), $this->object['member_uin'], true)) {
			return true;
		} elseif ($this->msg_type == MsgUtil::PERSONAL_MSG && DataUtil::is_equal(explode(",", $manager_uin), $this->object['friend_uin'], true)) {
			return true;
		} elseif ($this->msg_type == MsgUtil::GROUP_VERIFICATION && DataUtil::is_equal(explode(",", $manager_uin), $this->object['from_uin'], true)) {
			return true;
		}
		return false;
	}

	/**
	 * 获取群成员的称呼的名字
	 * @param $member_uin 要获取的QQ号，如果为false，或者不传入此参数，那么获取的是消息发送者的QQ号
	 */
	public function getNameForMsg($objectinfo = false, $member_uin = false) {
		$username = "";
		if ($this->msg_type == MsgUtil::GROUP_MSG) {
			$groupmemberinfo = $objectinfo ? $objectinfo : $this->groupmemberinfoPer->getMe($this->object['group_uin'], $this->object['member_uin']);
			if ($groupmemberinfo) {
				$username = trim($groupmemberinfo['card_name']) == "" ? trim($groupmemberinfo['nick_name']) : trim($groupmemberinfo['card_name']);
			}
		} elseif($this->msg_type == MsgUtil::PERSONAL_MSG) {
			$friendinfo = $objectinfo ? $objectinfo : $this->db->executeQuery("select * from web_friend_info where uin = " . $this->object['friend_uin'], true);
			if ($friendinfo) {
				$username = trim($friendinfo['name']);
			}
		}
		if ($username == "") {
			if ($member_uin) {
				$groupmember = $this->groupmemberPer->getMe($this->robot['id'], $this->object['group_uin'], $member_uin);
				$username = $groupmember ? $groupmember['nick_name'] : "神秘人";
			} else {
				$username = (trim($this->object['nick_name']) == "") ? "神秘人" : trim($this->object['nick_name']);
			}
		}
		$username = preg_replace("/\s/", "", $username);
		$username = preg_replace("/<br>/", "", $username);
		return $username;
	}

	/**
	 * 获取信息
	 * @param $uin 获取信息，如果为false，或者不传入此参数，那么获取的是消息发送者的信息
	 */
	public function getInfoForMsg($uin = false) {
		$objectinfo = false;
		if ($this->msg_type == MsgUtil::GROUP_MSG) {
			$uin = $uin ? $uin : $this->object['member_uin'];
			$objectinfo = $this->groupmemberinfoPer->getMe($this->object['group_uin'], $uin);
		} elseif($this->msg_type == MsgUtil::PERSONAL_MSG) {
			$uin = $uin ? $uin : $this->object['friend_uin'];
			$objectinfo = $this->db->executeQuery("select * from web_friend_info where uin = " . $uin, true);
		}
		return $objectinfo;
	}

	/**
	 * 获取群信息
	 * @param $uin 获取群信息，如果为false，或者不传入此参数，那么获取的是消息发送者的群信息
	 */
	public function getGroupInfo($group_uin = false) {
		$group_uin = $group_uin ? $group_uin : $this->object['group_uin'];
		return $this->db->executeQuery("select * from web_group_info where uin = {$group_uin}", true);
	}

	/**
	 * 获取群成员的头衔
	 */
	public function getLevelName($group_info, $level) {
		if (!$group_info) {
			return "";
		}
		$level_str = $group_info['level'];
		$levelArray = explode(",", $level_str);
		return @$levelArray[$level-1];
	}

	/**
	 * 根据QQ判断此人是不是在黑名单中
	 * @param int $uin
	 */
	public function is_black_list($uin) {
		$qq_black_list = $this->robotdat['qq_black_list'];
		if ($this->robotdat['is_qq_black_list'] == 1 && DataUtil::is_equal(explode(",", $qq_black_list), $uin, true)) {
			return true;
		}
		return false;
	}

	/**
	 * 更新消息发送者的插件定位
	 * @param int $plugin_id
	 */
	public function updatePluginId($plugin_id = 0) {
		if (isset($this->object['id']) && isset($this->object['plugin_id'])) {
			if ($this->msg_type == MsgUtil::GROUP_MSG) {
				$this->groupmemberPer->updatePluginId($this->object['id'], $plugin_id);
			} elseif ($this->msg_type == MsgUtil::PERSONAL_MSG) {
				$this->robotfriendsPer->updatePluginId($this->object['id'], $plugin_id);
			}
		}
	}

	/**
	 * 禁言,仅对群成员有效,而且机器人必须是群管理员或群主
	 * @param int $group_uin
	 * @param int $member_uin
	 * @param string $time_memo 禁言的时间 <p>单位可以是天,小时,分钟<p>比如禁言天 : 1天,1t,1day,1days<p>禁言小时 : 1小时,1h,1hour,1hours<p>禁言分钟 : 1分钟,1m,没有单位也默认为分钟
	 */
	public function setMemberSpeech($group_uin, $member_uin, $time_memo) {
		if ($this->msg_type == MsgUtil::GROUP_MSG) {
			$time = preg_replace('/^(\d{1,5})(.*)$/i', '\\1', $time_memo);
			if (is_numeric($time) && $time >= 0) {
				if (DataUtil::end_contain(array("天", "t", "d", "day", "days"), $time_memo, true, true)) {
					$time *= (24 * 60 * 60);
				} elseif (DataUtil::end_contain(array("小时", "h", "hour", "hours"), $time_memo, true, true)) {
					$time *= (60 * 60);
				} else {
					$time *= 60;
				}
				$time = $time > 2592000 ? 2592000 : $time;
				return WqCommon::setMemberSpeech($group_uin, $member_uin, $time, $this->robot['bkn'], $this->cookie);
			}
		}
		return false;
	}

	/**
	 * 禁言自己(此条消息发送者)
	 * @param string $time_memo
	 */
	public function setMemberSpeechBySelf($time_memo) {
		return $this->setMemberSpeech($this->object['group_uin'], $this->object['member_uin'], $time_memo);
	}

	/**
	 * 修改群名片
	 * @param int $group_uin
	 * @param int $member_uin
	 * @param string $name
	 */
	public function updateMemberCard($group_uin, $member_uin, $name) {
		if ($name != "" && strlen($name) <= 21) {
			return WqCommon::updateMemberCard($group_uin, $member_uin, $name, $this->robot['bkn'], $this->cookie);
		}
		return false;
	}

	/**
	 * 修改自己的名片(此条消息发送者)
	 * @param unknown $name
	 */
	public function updateMemberCardSelf($name) {
		return $this->updateMemberCard($this->object['group_uin'], $this->object['member_uin'], $name);
	}

	/**
	 * 删除群成员
	 * @param int $group_uin
	 * @param int $member_uin
	 */
	public function removeMember($group_uin, $member_uin) {
		return WqCommon::removeMember($group_uin, $member_uin, $this->robot['bkn'], $this->cookie);
	}

	/**
	 * 删除群成员(此条消息发送者)
	 */
	public function removeMemberSelf() {
		return $this->removeMember($this->object['group_uin'], $this->object['member_uin']);
	}

	/**
	 * 根据标记和Name获取存储的值
	 * @param string $mark
	 * @param string $name
	 * @param unknown $default_value
	 * @return unknown
	 */
	public function getValueByName($mark, $name, $default_value = null) {
		$meta = $this->db->executeQuery("SELECT value FROM web_system WHERE mark = '{$mark}' AND name = '{$name}' LIMIT 1", true);
		return $meta ? $meta['value'] : $default_value;
	}

	/**
	 * 保存标记的值
	 * @param string $mark
	 * @param string $name
	 * @param string $value
	 * @return boolean
	 */
	public function replaceSys($mark, $name, $value) {
		return $this->db->executeQuery("REPLACE INTO web_system(mark, name, value) VALUES('{$mark}', '{$name}', '{$value}')") > 0 ? true : false;
	}

}
?>