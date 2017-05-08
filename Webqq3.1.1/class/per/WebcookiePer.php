<?php if (!defined('ITPK')) exit('You can not directly access the file.');

/**
 * COOKIE操作类
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

class WebcookiePer extends WebDBConnection {
	
	private $table = "web_cookie";

	/**
	 * 获取COOKIE
	 * @param int $robot_id
	 * @return array | boolean
	 */
	public function getMeByUin($robot_id) {
		$selectSql = new SelectSql($this->table);
		$selectSql->setWhere("robot_id", $robot_id);
		$selectSql->setOrder("id");
		$selectSql->setLimitAndOffset(1, 0);
		return $selectSql->executeSelectSql($this->db);
	}

	/**
	 * 更新或添加COOKIE
	 * @param int $robot_id
	 * @param string $cookie
	 * @param string $ptwebqq
	 * @param string $vfwebqq
	 * @param string $psessionid
	 * @param string $clientid
	 * @return boolean
	 */
	public function replaceMe($robot_id, $cookie, $ptwebqq, $vfwebqq, $psessionid, $clientid) {
		$replaceSql = new ReplaceSql($this->table);
		$replaceSql->setReplace(array("robot_id", "cookie", "ptwebqq", "vfwebqq", "psessionid", "clientid"), array($robot_id, $cookie, $ptwebqq, $vfwebqq, $psessionid, $clientid));
		return $replaceSql->executeReplaceSql($this->db);
	}

	/**
	 * 删除机器人Cookie
	 * @param int $robot_id
	 * @return boolean
	 */
	public function deleteMeByRobotId($robot_id) {
		$deleteSql = new DeleteSql($this->table);
		$deleteSql->setWhere("robot_id", $robot_id);
		return $deleteSql->executeDeleteSql($this->db);
	}

	/**
	 * 根据机器人ID删除多个机器人Cookie
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