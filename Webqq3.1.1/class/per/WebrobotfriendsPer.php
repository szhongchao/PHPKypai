<?php if (!defined('ITPK')) exit('You can not directly access the file.');

/**
 * 机器人好友操作类
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

class WebrobotfriendsPer extends WebDBConnection {

	private $table = "web_robot_friends";

	/**
	 * 根据QQ获取机器人属性
	 * @param unknown robot
	 * @param string $friend_uin
	 * @return array | boolean
	 */
	public function getMeByUin($robot, $friend_uin) {
		$selectSql = new SelectSql($this->table);
		$selectSql->setWhere("robot_id", $robot['id']);
		$selectSql->setWhere("friend_uin", $friend_uin);
		return $selectSql->executeSelectSql($this->db);
	}

	/**
	 * 保存好友信息
	 * @param unknown $robot
	 * @param int $friend_uin
	 * @param string $nick_name
	 * @param int $plugin_id
	 * @return boolean
	 */
	public function insertMe($robot, $friend_uin, $nick_name, $plugin_id = 0) {
		$insertSql = new InsertSql($this->table);
		$insertSql->setInsert(array("robot_id", "friend_uin", "nick_name", "plugin_id", "createdate"), array($robot['id'], $friend_uin, $nick_name, $plugin_id, time()));
		return $insertSql->executeInsertSql($this->db);
	}

	/**
	 * 更新机器人插件定位
	 * @param int $group_member_id
	 * @param int $plugin_id
	 * @return Ambigous <multitype:, boolean>
	 */
	public function updatePluginId($friend_id, $plugin_id) {
		$updateSql = new UpdateSql($this->table);
		$updateSql->setUpdateValue("plugin_id", $plugin_id);
		$updateSql->setWhere("id", $friend_id);
		return $updateSql->executeUpdateSql($this->db);
	}

	/**
	 * 删除机器人记录的所有好友
	 * @param int $robot_id
	 * @return boolean
	 */
	public function deleteMeByRobotId($robot_id) {
		$deleteSql = new DeleteSql($this->table);
		$deleteSql->setWhere("robot_id", $robot_id);
		return $deleteSql->executeDeleteSql($this->db);
	}

	/**
	 * 根据机器人ID删除多个机器人记录的所有好友
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