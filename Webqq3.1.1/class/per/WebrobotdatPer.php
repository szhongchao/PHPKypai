<?php if (!defined('ITPK')) exit('You can not directly access the file.');

/**
 * 机器人附加属性操作类
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

class WebrobotdatPer extends WebDBConnection {

	private $table = "web_robot_dat";

	/**
	 * 根据机器人ID获取附加属性
	 * @param int $robot_id
	 * @return unknown
	 */
	public function getMeByRobotId($robot_id) {
		$selectSql = new SelectSql($this->table);
		$selectSql->setWhere("robot_id", $robot_id);
		$selectSql->setLimitAndOffset();
		return $selectSql->executeSelectSql($this->db);
	}

	/**
	 * 根据机器人ID获取某个属性
	 * @param int $robot_id
	 * @param string $name
	 * @return Ambigous <boolean, unknown>
	 */
	public function getProfile($robot_id, $name) {
		$selectSql = new SelectSql($this->table);
		$selectSql->setWhere("robot_id", $robot_id);
		$selectSql->setLimitAndOffset();
		$robotdat = $selectSql->executeSelectSql($this->db);
		return $robotdat ? $robotdat["{$name}"] : false;
	}

	/**
	 * 初始化机器人属性
	 * @param unknown $robot_id
	 * @return boolean
	 */
	public function replaceMe($robot_id) {
		$colunmArray = array("robot_id");
		$colunmValueArray = array($robot_id);

		$replaceSql = new ReplaceSql($this->table);
		$replaceSql->setReplace($colunmArray, $colunmValueArray);
		return $replaceSql->executeReplaceSql($this->db);
	}

	/**
	 * 修改机器人附加属性
	 * @param string|int $robot_id_array
	 * @param string $manager_uin
	 * @param string $group_black_list
	 * @param string $group_white_list
	 * @param string $qq_black_list
	 * @param string $qq_white_list
	 * @param string $add_group_clues
	 * @param string $agree_add_group_clues
	 * @param string $refuse_add_group_clues
	 * @param int $is_agree_add_group
	 * @param int $is_group_black_list
	 * @param int $is_group_white_list
	 * @param int $is_qq_black_list
	 * @param int $is_qq_white_list
	 * @return boolean
	 */
	public function updateMe($robot_id_array, $manager_uin, $group_black_list, $group_white_list, $qq_black_list, $qq_white_list, $add_group_clues, $agree_add_group_clues, $refuse_add_group_clues, $is_agree_add_group, $is_refuse_add_group, $is_group_black_list, $is_group_white_list, $is_qq_black_list, $is_qq_white_list) {
		$updateSql = new UpdateSql($this->table);
		$updateSql->setUpdateValue("manager_uin", $manager_uin);
		$updateSql->setUpdateValue("group_black_list", $group_black_list);
		$updateSql->setUpdateValue("group_white_list", $group_white_list);
		$updateSql->setUpdateValue("qq_black_list", $qq_black_list);
		$updateSql->setUpdateValue("qq_white_list", $qq_white_list);
		$updateSql->setUpdateValue("add_group_clues", $add_group_clues);
		$updateSql->setUpdateValue("agree_add_group_clues", $agree_add_group_clues);
		$updateSql->setUpdateValue("refuse_add_group_clues", $refuse_add_group_clues);
		$updateSql->setUpdateValue("is_agree_add_group", $is_agree_add_group);
		$updateSql->setUpdateValue("is_refuse_add_group", $is_refuse_add_group);
		$updateSql->setUpdateValue("is_group_black_list", $is_group_black_list);
		$updateSql->setUpdateValue("is_group_white_list", $is_group_white_list);
		$updateSql->setUpdateValue("is_qq_black_list", $is_qq_black_list);
		$updateSql->setUpdateValue("is_qq_white_list", $is_qq_white_list);
		if (is_numeric($robot_id_array)) {
			$updateSql->setWhere("robot_id", $robot_id_array);
		} else {
			$updateSql->setWhere("robot_id", $robot_id_array, "in");
		}
		return $updateSql->executeUpdateSql($this->db);
	}

	/**
	 * 删除机器人附加属性
	 * @param int $robot_id
	 * @return boolean
	 */
	public function deleteMe($robot_id) {
		$deleteSql = new DeleteSql($this->table);
		$deleteSql->setWhere("robot_id", $robot_id);
		return $deleteSql->executeDeleteSql($this->db);
	}

	/**
	 * 根据机器人ID删除多个机器人
	 * @param string $robot_ids
	 * @return boolean
	 */
	public function deleteMeByIds($robot_ids) {
		$deleteSql = new DeleteSql($this->table);
		$deleteSql->setWhere("robot_id", $robot_ids, "in");
		return $deleteSql->executeDeleteSql($this->db);
	}
}
?>