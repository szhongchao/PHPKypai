<?php if (!defined('ITPK')) exit('You can not directly access the file.');

/**
 * 群成员操作类
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

class WebgroupmemberPer extends WebDBConnection {

	private $table = "web_group_member";

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
	public function getMe($robot_id, $group_uin, $member_uin) {
		$selectSql = new SelectSql($this->table);
		$selectSql->setWhere("robot_id", $robot_id);
		$selectSql->setWhere("group_uin", $group_uin);
		$selectSql->setWhere("member_uin", $member_uin);
		return $selectSql->executeSelectSql($this->db);
	}

	/**
	 * 排行榜
	 * @param int $robot_id, 
	 * @param string $group_uin
	 * @param string $profile
	 * @param string $is_desc
	 * @param number $limit
	 */
	public function getMeByProfile($robot_id, $group_uin, $profile, $is_desc = "DESC", $limit = 10) {
		$selectSql = new SelectSql($this->table);
		$selectSql->setWhere("robot_id", $robot_id);
		$selectSql->setWhere("group_uin", $group_uin);
		$selectSql->setOrder($profile, $is_desc);
		$selectSql->setLimitAndOffset($limit);
		return $selectSql->executeSelectSql($this->db, true);
	}

	/**
	 * 新增群成员记录
	 * @param string $uin
	 * @param string $group_uin
	 * @param string $member_uin
	 * @param string $nick_name
	 * @param int $is_reply
	 */
	public function insertMe($robot_id, $group_uin, $member_uin, $nick_name = "") {
		$insertSql = new InsertSql($this->table);
		$insertSql->setInsert(array("robot_id", "group_uin", "member_uin", "nick_name", "createdate"), array($robot_id, $group_uin, $member_uin, $nick_name, time()));
		return $insertSql->executeInsertSql($this->db);
	}

	/**
	 * 扣除或增加群成员的金币
	 * @param int $group_member_id
	 * @param int $gold
	 * @param boolean $is_add 是否为增加，默认为false
	 * @return int | boolean
	 */
	public function updateGold($group_member_id, $gold, $is_add = false) {
		$symbol = $is_add ? "+" : "-";
		$updateSql = new UpdateSql($this->table);
		$updateSql->setUpdateSelfValue("gold", $gold, $symbol);
		$updateSql->setWhere("id", $group_member_id);
		return $updateSql->executeUpdateSql($this->db);
	}

	/**
	 * 根据多个信息确定群成员，然后修改金币
	 * @param int $robot_id
	 * @param int $group_uin
	 * @param int $member_uin
	 * @param int $gold
	 * @param $is_add $is_add
	 * @return boolean
	 */
	public function updateGoldByManager($robot_id, $group_uin, $member_uin, $gold, $is_add = false) {
		$symbol = $is_add ? "+" : "-";
		$updateSql = new UpdateSql($this->table);
		$updateSql->setUpdateSelfValue("gold", $gold, $symbol);
		$updateSql->setWhere("robot_id", $robot_id);
		$updateSql->setWhere("group_uin", $group_uin);
		$updateSql->setWhere("member_uin", $member_uin);
		return $updateSql->executeUpdateSql($this->db);
	}

	/**
	 * 扣除或增加群成员的经验
	 * @param int $group_member_id
	 * @param int $experience
	 * @param boolean $is_add 是否为增加，默认为false
	 * @return int | boolean
	 */
	public function updateExperience($group_member_id, $experience, $is_add = false) {
		$symbol = $is_add ? "+" : "-";
		$updateSql = new UpdateSql($this->table);
		$updateSql->setUpdateSelfValue("experience", $experience, $symbol);
		$updateSql->setWhere("id", $group_member_id);
		return $updateSql->executeUpdateSql($this->db);
	}

	/**
	 * 根据多个信息确定群成员，然后修改经验
	 * @param int $robot_id
	 * @param int $group_uin
	 * @param int $member_uin
	 * @param int $experience
	 * @param $is_add $is_add
	 * @return boolean
	 */
	public function updateExperienceByManager($robot_id, $group_uin, $member_uin, $experience, $is_add = false) {
		$symbol = $is_add ? "+" : "-";
		$updateSql = new UpdateSql($this->table);
		$updateSql->setUpdateSelfValue("experience", $experience, $symbol);
		$updateSql->setWhere("robot_id", $robot_id);
		$updateSql->setWhere("group_uin", $group_uin);
		$updateSql->setWhere("member_uin", $member_uin);
		return $updateSql->executeUpdateSql($this->db);
	}

	/**
	 * 根据群成员ID删除群成员信息
	 * @param int $group_member_id
	 */
	public function deleteMe($group_member_id) {
		$deleteSql = new DeleteSql($this->table);
		$deleteSql->setWhere("id", $group_member_id);
		return $deleteSql->executeDeleteSql($this->db);
	}

	/**
	 * 更新机器人插件定位
	 * @param int $group_member_id
	 * @param int $plugin_id
	 * @return Ambigous <multitype:, boolean>
	 */
	public function updatePluginId($group_member_id, $plugin_id) {
		$updateSql = new UpdateSql($this->table);
		$updateSql->setUpdateValue("plugin_id", $plugin_id);
		$updateSql->setWhere("id", $group_member_id);
		return $updateSql->executeUpdateSql($this->db);
	}

	/**
	 * 删除机器人记录的所有群成员
	 * @param int $robot_id
	 * @return boolean
	 */
	public function deleteMeByRobotId($robot_id) {
		$deleteSql = new DeleteSql($this->table);
		$deleteSql->setWhere("robot_id", $robot_id);
		return $deleteSql->executeDeleteSql($this->db);
	}

	/**
	 * 根据机器人ID删除多个机器人的所有群成员
	 * @param string $robot_ids
	 * @return boolean
	 */
	public function deleteMeByRobotIds($robot_ids) {
		$deleteSql = new DeleteSql($this->table);
		$deleteSql->setWhere("robot_id", $robot_ids, "in");
		return $deleteSql->executeDeleteSql($this->db);
	}
}
?>