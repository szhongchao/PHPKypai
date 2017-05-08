<?php if (!defined('ITPK')) exit('You can not directly access the file.');

/**
 * 运行记录操作类
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

class WebrunlogPer extends WebDBConnection {

	private $table = "web_run_log";

	/**
	 * 获取最新的运行记录
	 * @param unknown $robot
	 * @param int $limit
	 * @param int $offset
	 * @param int $group_uin
	 * @return Ambigous <array, boolean>
	 */
	public function getMe($robot, $limit, $offset, $group_uin = false) {
		$selectSql = new SelectSql($this->table);
		$selectSql->setWhere("robot_id", $robot['id']);
		if ($group_uin) {
			$selectSql->setWhere("group_uin", $group_uin);
		}
		$selectSql->setOrder("id", "DESC");
		$selectSql->setLimitAndOffset($limit, $offset);
		return $selectSql->executeSelectSql($this->db, true);
	}

	/**
	 * 获取所有用户的总记录数
	 * @return int
	 */
	public function getAllLogCount($robot) {
		$selectSql = new SelectSql($this->table, "count(1) as count");
		$selectSql->setWhere("robot_id", $robot['id']);
		$result = $selectSql->executeSelectSql($this->db);
		return $result ? $result['count'] : 0;
	}

	/**
	 * 增加运行记录
	 * @param unknown $robot
	 * @param string $msg 记录的消息
	 * @param string $group_uin 群号码,为0为系统运行记录
	 * @return boolean
	 */
	public function insertMe($robot, $msg, $group_uin = 0) {
		$insertSql = new InsertSql($this->table);
		$colunmArray = array(
			"robot_id", "group_uin", "msg", "createdate"
		);
		$colunmValueArray = array(
			$robot['id'], $group_uin, $msg, time()
		);
		$insertSql->setInsert($colunmArray, $colunmValueArray);
		return $insertSql->executeInsertSql($this->db);
	}

	/**
	 * 根据机器人ID删除此机器人的运行记录
	 * @param int $robot_id
	 * @return boolean
	 */
	public function deleteMeByRobotId($robot_id) {
		$deleteSql = new DeleteSql($this->table);
		$deleteSql->setWhere("robot_id", $robot_id);
		return $deleteSql->executeDeleteSql($this->db);
	}

	/**
	 * 根据多个机器人ID删除这些机器人的运行记录
	 * @param unknown $robot_ids
	 * @return boolean
	 */
	public function deleteMeByRobotIds($robot_ids) {
		$deleteSql = new DeleteSql($this->table);
		$deleteSql->setWhere("robot_id", $robot_ids, "in");
		return $deleteSql->executeDeleteSql($this->db);
	}
} 
?>