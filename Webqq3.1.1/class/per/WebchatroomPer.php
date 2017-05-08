<?php if (!defined('ITPK')) exit('You can not directly access the file.');

/**
 * 聊天室操作类
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

class WebchatroomPer extends WebDBConnection {

	private $table = "web_chat_room";

	/**
	 * 获取所有的聊天室
	 * @return array
	 */
	public function getMeAll() {
		$selectSql = new SelectSql($this->table);
		$selectSql->setOrder("id", "ASC");
		return $selectSql->executeSelectSql($this->db, true);
	}

	/**
	 * 根据聊天室ID获取信息
	 * @param int $id
	 */
	public function getMeById($id) {
		$selectSql = new SelectSql($this->table);
		$selectSql->setWhere("id", $id);
		$selectSql->setLimitAndOffset();
		return $selectSql->executeSelectSql($this->db);
	}

	/**
	 * 添加聊天室
	 * @param string $name
	 * @return boolean
	 */
	public function insertMe($name) {
		$insertSql = new InsertSql($this->table);
		$colunmArray = array(
			"name", "createdate"
		);
		$colunmValueArray = array(
			$name, time()
		);
		$insertSql->setInsert($colunmArray, $colunmValueArray);
		return $insertSql->executeInsertSql($this->db);
	}

	/**
	 * 删除聊天室
	 * @param int $id
	 */
	public function deleteMe($id) {
		$deleteSql = new DeleteSql($this->table);
		$deleteSql->setWhere("id", $id);
		return $deleteSql->executeDeleteSql($this->db);
	}

	/**
	 * 修改聊天室
	 * @param int $id
	 * @param string $name
	 * @return boolean
	 */
	public function updateMe($id, $name) {
		$updateSql = new UpdateSql($this->table);
		$updateSql->setUpdateValue("name", $name);
		$updateSql->setWhere("id", $id);
		return $updateSql->executeUpdateSql($this->db);
	}
}
?>