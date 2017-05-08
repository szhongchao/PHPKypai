<?php if (!defined('ITPK')) exit('You can not directly access the file.');

/**
 * 机器人插件操作类
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

class WebpluginPer extends WebDBConnection {

	private $table = "web_plugin";

	/**
	 * 获取插件
	 * @param int $is_able 是否启用
	 * @param int $is_monitor_all_msg	是否监控所有消息
	 * @return array | boolean
	 */
	public function getMe($is_able = 1, $is_monitor_all_msg = 0) {
		$selectSql = new SelectSql($this->table);
		$selectSql->setWhere("is_able", $is_able);
		$selectSql->setWhere("is_monitor_all_msg", $is_monitor_all_msg);
		$selectSql->setOrder("id");
		return $selectSql->executeSelectSql($this->db, true);
	}

	/**
	 * 根据插件ID取插件信息
	 * @param int $id
	 * @return Ambigous <multitype:, boolean>
	 */
	public function getMeById($id) {
		$selectSql = new SelectSql($this->table);
		$selectSql->setWhere("id", $id);
		return $selectSql->executeSelectSql($this->db);
	}

	/**
	 * 根据插件类名获取插件信息
	 * @param string $class_name
	 * @return Ambigous <multitype:, boolean>
	 */
	public function getMeByClassname($class_name) {
		$selectSql = new SelectSql($this->table);
		$selectSql->setWhere("class_name", $class_name);
		return $selectSql->executeSelectSql($this->db);
	}

	/**
	 * 获取所有的插件
	 * @return array | boolean
	 */
	public function getMeAll() {
		$selectSql = new SelectSql($this->table);
		$selectSql->setOrder("id");
		return $selectSql->executeSelectSql($this->db, true);
	}

	/**
	 * 卸载插件
	 * @param int $id
	 * @return boolean
	 */
	public function deleteMe($id) {
		$deleteSql = new DeleteSql($this->table);
		$deleteSql->setWhere("id", $id);
		return $deleteSql->executeDeleteSql($this->db);
	}

	/**
	 * 更新或添加插件
	 * @param string $plugin_name
	 * @param string $class_name
	 * @param string $author
	 * @param string $author_url
	 * @param string $description
	 * @param string $instruction
	 * @param int $instruction_type
	 * @param string $version
	 * @param int $is_monitor_all_msg
	 * @param int $is_able
	 */
	public function replaceMe($plugin_name, $class_name, $author, $author_url, $description, $instruction, $instruction_type, $version, $is_monitor_all_msg, $is_able) {
		$colunmArray = array(
			"plugin_name", "class_name", "author", "author_url", "description", "instruction", "instruction_type", "version", "is_monitor_all_msg", "is_able"
		);
		$colunmValueArray = array(
			$plugin_name, $class_name, $author, $author_url, $description, $instruction, $instruction_type, $version, $is_monitor_all_msg, $is_able
		);
		$replaceSql = new ReplaceSql($this->table);
		$replaceSql->setReplace($colunmArray, $colunmValueArray);
		return $replaceSql->executeReplaceSql($this->db);
	}

	/**
	 * 修改插件指令和启用状态
	 * @param int $id
	 * @param string $instruction
	 * @param int $is_able
	 * @return boolean
	 */
	public function updateMe($id, $instruction, $is_able) {
		$updateSql = new UpdateSql($this->table);
		$updateSql->setUpdateValue("instruction", $instruction);
		$updateSql->setUpdateValue("is_able", $is_able);
		$updateSql->setWhere("id", $id);
		return $updateSql->executeUpdateSql($this->db);
	}

	/**
	 * 执行插件安装的SQL
	 * @param string $sql
	 * @return boolean
	 */
	public function installSql($sql) {
		if (DataUtil::is_empty($sql)) return false;
		return $this->db->executeMultiQuery($sql, true);
	}

	/**
	 * 执行插件卸载的SQL
	 * @param string $sql
	 * @return boolean
	 */
	public function uninstallSql($sql) {
		if (DataUtil::is_empty($sql)) return true;
		return $this->db->executeMultiQuery($sql, true);
	}
}
?>