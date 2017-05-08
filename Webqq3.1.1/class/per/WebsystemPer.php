<?php if (!defined('ITPK')) exit('You can not directly access the file.');

/**
 * 标记操作类
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

class WebsystemPer extends WebDBConnection {

	private $table = "web_system";

	/**
	 * 根据标记和Name获取存储的值
	 * @param string $mark
	 * @param string $name
	 * @param unknown $default_value
	 * @return unknown
	 */
	public function getValueByName($mark, $name, $default_value = null) {
		$selectSql = new SelectSql($this->table, "value");
		$selectSql->setWhere("mark", $mark);
		$selectSql->setWhere("name", $name);
		$selectSql->setLimitAndOffset();
		$meta = $selectSql->executeSelectSql($this->db);
		return $meta ? $meta['value'] : $default_value;
	}

	/**
	 * 根据标记获取所有的值
	 * @param string $mark
	 * @return array | boolean
	 */
	public function getMeByMark($mark) {
		$selectSql = new SelectSql($this->table);
		$selectSql->setWhere("mark", $mark);
		return $selectSql->executeSelectSql($this->db, true);
	}

	/**
	 * 保存标记的值
	 * @param string $mark
	 * @param string $name
	 * @param string $value
	 * @return boolean
	 */
	public function replaceSys($mark, $name, $value) {
		$replaceSql = new ReplaceSql($this->table);
		$colunmArray = array(
			"mark", "name", "value"
		);
		$colunmValueArray = array(
			$mark, $name, $value
		);
		$replaceSql->setReplace($colunmArray, $colunmValueArray);
		return $replaceSql->executeReplaceSql($this->db);
	}

}
?>