<?php if (!defined('ITPK')) exit('You can not directly access the file.');

/**
 * 用户组操作类
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

class WebrolePer extends WebDBConnection {

	private $table = "web_role";

	/**
	 * 根据用户组ID获取信息
	 * @param int $role_id
	 * @return Ambigous <multitype:, boolean>
	 */
	public function getMeById($role_id) {
		$selectSql = new SelectSql($this->table);
		$selectSql->setWhere("id", $role_id);
		$selectSql->setOrder("id");
		$selectSql->setLimitAndOffset();
		return $selectSql->executeSelectSql($this->db);
	}

	/**
	 * 获取所有的用户组
	 * @return array
	 */
	public function getMeAll() {
		$selectSql = new SelectSql($this->table);
		$selectSql->setOrder("sort");
		return $selectSql->executeSelectSql($this->db, true);
	}

	/**
	 * 获取小于或等于当前权限的用户组
	 * @param int $jurisdiction
	 */
	public function getMeAllByJurisdiction($jurisdiction) {
		$selectSql = new SelectSql($this->table);
		$selectSql->setWhere("jurisdiction", $jurisdiction, "<=");
		$selectSql->setOrder("sort");
		return $selectSql->executeSelectSql($this->db, true);
	}

	/**
	 * 修改角色属性
	 * @param int $id
	 * @param string $name
	 * @param int $sort
	 * @param int $add_robot_max_number
	 * @param int $init_gold
	 * @param int $jurisdiction
	 * @return boolean
	 */
	public function updateMe($id, $name, $sort, $add_robot_max_number, $init_gold, $jurisdiction) {
		$updateSql = new UpdateSql($this->table);
		$updateSql->setUpdateValue("name", $name);
		$updateSql->setUpdateValue("sort", $sort);
		$updateSql->setUpdateValue("add_robot_max_number", $add_robot_max_number);
		$updateSql->setUpdateValue("init_gold", $init_gold);
		$updateSql->setUpdateValue("jurisdiction", $jurisdiction);
		$updateSql->setWhere("id", $id);
		return $updateSql->executeUpdateSql($this->db);
	}

	/**
	 * 根据角色ID删除角色信息
	 * @param int $id
	 * @return boolean
	 */
	public function deleteMe($id) {
		$deleteSql = new DeleteSql($this->table);
		$deleteSql->setWhere("id", $id);
		return $deleteSql->executeDeleteSql($this->db);
	}

	/**
	 * 添加新角色
	 * @param string $name
	 * @param int $sort
	 * @param int $add_robot_max_number
	 * @param int $init_gold
	 * @param int $jurisdiction
	 * @return int
	 */
	public function insertMe($name, $sort, $add_robot_max_number, $init_gold, $jurisdiction) {
		$insertSql = new InsertSql($this->table);
		$colunmArray = array(
			"name", "sort", "add_robot_max_number", "init_gold", "jurisdiction", "createdate"
		);
		$colunmValueArray = array(
			$name, $sort, $add_robot_max_number, $init_gold, $jurisdiction, time()
		);
		$insertSql->setInsert($colunmArray, $colunmValueArray);
		return $insertSql->executeInsertSql($this->db);
	} 
}
?>