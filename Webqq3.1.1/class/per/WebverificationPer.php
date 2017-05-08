<?php if (!defined('ITPK')) exit('You can not directly access the file.');

/**
 * 机器人登录验证码操作类
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

class WebverificationPer extends WebDBConnection {

	private $table = "web_verification";

	/**
	 * 获取验证码
	 * @param int $robot_id
	 * @return string | boolean
	 */
	public function getMeByRobotId($robot_id) {
		$selectSql = new SelectSql($this->table, "verification");
		$selectSql->setWhere("robot_id", $robot_id);
		$selectSql->setOrder("id");
		$selectSql->setLimitAndOffset();
		$verification = $selectSql->executeSelectSql($this->db);
		return $verification ? $verification['verification'] : false;
	}

	/**
	 * 更新或添加验证码
	 * @param int $robot_id
	 * @param string $verification
	 * @return boolean
	 */
	public function replaceMe($robot_id, $verification) {
		$replaceSql = new ReplaceSql($this->table);
		$colunmArray = array(
			"robot_id", "verification"
		);
		$colunmValueArray = array(
			$robot_id, $verification
		);
		$replaceSql->setReplace($colunmArray, $colunmValueArray);
		return $replaceSql->executeReplaceSql($this->db);
	}

	/**
	 * 删除机器人的验证码
	 * @param int $robot_id
	 * @return boolean
	 */
	public function deleteMeByRobotId($robot_id) {
		$deleteSql = new DeleteSql($this->table);
		$deleteSql->setWhere("robot_id", $robot_id);
		return $deleteSql->executeDeleteSql($this->db);
	}

	/**
	 * 根据机器人ID删除多个机器人的验证码
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