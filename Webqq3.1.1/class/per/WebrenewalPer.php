<?php if (!defined('ITPK')) exit('You can not directly access the file.');

/**
 * 续费操作类
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

class WebrenewalPer extends WebDBConnection {

	private $table = "web_renewal";

	/**
	 * 根据ID获取信息
	 * @param int $renewal_id
	 * @return Ambigous <multitype:, boolean>
	 */
	public function getMeById($renewal_id) {
		$selectSql = new SelectSql($this->table);
		$selectSql->setWhere("id", $renewal_id);
		$selectSql->setOrder("id");
		$selectSql->setLimitAndOffset();
		return $selectSql->executeSelectSql($this->db);
	}

	/**
	 * 获取所有的续费设置
	 * @return array
	 */
	public function getMeAll() {
		$selectSql = new SelectSql($this->table);
		$selectSql->setOrder("sort");
		return $selectSql->executeSelectSql($this->db, true);
	}

	/**
	 * 修改续费设置
	 * @param int $id
	 * @param string $name
	 * @param int $sort
	 * @param int $day_time
	 * @param int $gold
	 * @return boolean
	 */
	public function updateMe($id, $name, $sort, $day_time, $gold) {
		$updateSql = new UpdateSql($this->table);
		$updateSql->setUpdateValue("name", $name);
		$updateSql->setUpdateValue("sort", $sort);
		$updateSql->setUpdateValue("day_time", $day_time);
		$updateSql->setUpdateValue("gold", $gold);
		$updateSql->setWhere("id", $id);
		return $updateSql->executeUpdateSql($this->db);
	}

	/**
	 * 根据ID删除续费设置
	 * @param int $id
	 * @return boolean
	 */
	public function deleteMe($id) {
		$deleteSql = new DeleteSql($this->table);
		$deleteSql->setWhere("id", $id);
		return $deleteSql->executeDeleteSql($this->db);
	}

	/**
	 * 添加续费设置
	 * @param string $name
	 * @param int $sort
	 * @param int $add_robot_max_number
	 * @param int $init_gold
	 * @param int $jurisdiction
	 * @return int
	 */
	public function insertMe($name, $sort, $day_time, $gold) {
		$insertSql = new InsertSql($this->table);
		$colunmArray = array(
			"name", "sort", "day_time", "gold", "createdate"
		);
		$colunmValueArray = array(
			$name, $sort, $day_time, $gold, time()
		);
		$insertSql->setInsert($colunmArray, $colunmValueArray);
		return $insertSql->executeInsertSql($this->db);
	} 
}
?>