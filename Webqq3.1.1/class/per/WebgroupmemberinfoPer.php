<?php if (!defined('ITPK')) exit('You can not directly access the file.');

/**
 * 群成员操作类
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

class WebgroupmemberinfoPer extends WebDBConnection {

	private $table = "web_group_member_info";

	/**
	 * 根据群成员ID获取资料
	 * @param id $group_member_id
	 * @return array | boolean
	 */
	public function getMeById($group_member_id) {
		$selectSql = new SelectSql($this->table);
		$selectSql->setWhere("id", $group_member_id);
		return $selectSql->executeSelectSql($this->db);
	}

	/**
	 * 根据群号和群成员账号获取资料
	 * @param int $robot_id
	 * @param string $group_uin
	 * @param string $member_uin
	 * @return array | boolean
	 */
	public function getMe($group_uin, $member_uin) {
		$selectSql = new SelectSql($this->table);
		$selectSql->setWhere("group_uin", $group_uin);
		$selectSql->setWhere("member_uin", $member_uin);
		return $selectSql->executeSelectSql($this->db);
	}

	/**
	 * 排行榜
	 * @param string $group_uin
	 * @param string $profile
	 * @param string $is_desc
	 * @param number $limit
	 */
	public function getMeByProfile($group_uin, $profile, $is_desc = "DESC", $limit = 10) {
		$selectSql = new SelectSql($this->table);
		$selectSql->setWhere("group_uin", $group_uin);
		$selectSql->setOrder($profile, $is_desc);
		$selectSql->setLimitAndOffset($limit);
		return $selectSql->executeSelectSql($this->db, true);
	}

	/**
	 * 更新群成员的信息
	 * @param int $group_uin
	 * @param array $mems
	 */
	public function replaceMe($group_uin, $mems) {
		$replaceSql = "REPLACE INTO " . $this->table . "(group_uin, member_uin, nick_name, card_name, role, qage, qsex, level, point, join_time, last_speak_time) VALUES";
		if (is_array($mems)) {
			$replace_values = array();
			foreach ($mems as $member) {
				array_push($replace_values, "('" . $group_uin . "', '" . $member['uin'] . "', '" . $member['nick'] . "', '" . $member['card'] . "', " . $member['role'] . ", " . $member['qage'] . ", " . $member['g'] . ", " . $member['lv']['level'] . ", " . $member['lv']['point'] . ", " . $member['join_time'] . ", " . $member['last_speak_time'] . ")");
			}
			$replace_values_str = implode(",", $replace_values);
			if ($replace_values_str != "") {
				return $this->db->executeQuery($replaceSql . $replace_values_str);
			}
		}
		return false;
	}
}
?>