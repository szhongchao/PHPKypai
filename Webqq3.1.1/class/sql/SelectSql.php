<?php if (!defined('ITPK')) exit('You can not directly access the file.');

/**
 * Webqq数据库连接类
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

class SelectSql extends BaseSql {

	public function __construct($table, $colunms = "*") {
		$this->table = $table;
		$this->setSelectTable($colunms);
	}

	public function executeSelectSql($db, $is_array = false) {
		return $db->executeQuery($this->getSqlStr(), true, $is_array);
	}

}

?>