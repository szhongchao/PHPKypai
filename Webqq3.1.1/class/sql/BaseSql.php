<?php if (!defined('ITPK')) exit('You can not directly access the file.');

/**
 * Webqq数据库连接类
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

class BaseSql {

	protected $sql;
	protected $table;

	protected function setSelectTable($colunms = "*") {
		$this->sql = "SELECT " . $colunms . " FROM " . $this->table;
	}

	protected function setDeleteTable() {
		$this->sql = "DELETE FROM " . $this->table;
	}

	protected function setUpdateTable() {
		$this->sql = "UPDATE " . $this->table;
	}

	public function setUpdateValue($colunm, $colunmValue) {
		if (!DataUtil::is_contain("SET", $this->sql)) {
			$this->sql .= " SET ";
		} else {
			$this->sql .= ", ";
		}
		if (!is_numeric($colunmValue)) {
			$colunmValue = "'" . $colunmValue . "'";
		}
		$this->sql .= ($colunm . " = " . $colunmValue);
	}

	public function setUpdateSelfValue($colunm, $colunmValue, $symbol = "+") {
		if (!DataUtil::is_contain("SET", $this->sql)) {
			$this->sql .= " SET ";
		} else {
			$this->sql .= ", ";
		}
		if (!is_numeric($colunmValue)) {
			$colunmValue = "'" . $colunmValue . "'";
		}
		$this->sql .= ($colunm . " = " . $colunm . " " . $symbol . " " . $colunmValue);
	}

	private function setInsertOrReplace($colunmArray, $colunmValueArray, $is_insert = true) {
		$this->sql = ($is_insert ? "INSERT" : "REPLACE") . " INTO " . $this->table;
		$colunmSql = "";
		for ($i = 0; $i < count($colunmArray); $i++) {
			if ($colunmSql != "") {
				$colunmSql .= ", ";
			}
			$colunmSql .= $colunmArray[$i];
		}
		$colunmValueSql = "";
		for ($i = 0; $i < count($colunmValueArray); $i++) {
			if ($colunmValueSql != "") {
				$colunmValueSql .= ", ";
			}
			if (is_numeric($colunmValueArray[$i])) {
				$colunmValueSql .= $colunmValueArray[$i];
			} else {
				$colunmValueSql .= ("'" . $colunmValueArray[$i] . "'");
			}
		}
		$this->sql .= " (" . $colunmSql . ") VALUES (" . $colunmValueSql . ")";
	}

	public function setInsert($colunmArray, $colunmValueArray) {
		$this->setInsertOrReplace($colunmArray, $colunmValueArray, true);
	}

	public function setReplace($colunmArray, $colunmValueArray) {
		$this->setInsertOrReplace($colunmArray, $colunmValueArray, false);
	}

	private function setWhereForAndOr($colunm, $colunmValue, $term = "=", $is_and = true) {
		if (!DataUtil::is_contain("WHERE", $this->sql)) {
			$this->sql .= " WHERE ";
		} else {
			$this->sql .= ($is_and ? " AND " : " OR ");
		}
		if (!is_numeric($colunmValue)) {
			$colunmValue = "'" . $colunmValue . "'";
		}
		$this->sql .= ($colunm . " " . $term . " (" . $colunmValue . ")");
	}

	public function setWhere($colunm, $colunmValue, $term = "=") {
		$this->setWhereForAndOr($colunm, $colunmValue, $term, true);
	}

	public function setWhereOr($colunm, $colunmValue, $term = "=") {
		$this->setWhereForAndOr($colunm, $colunmValue, $term, false);
	}

	public function setOrder($order, $is_asc = "ASC") {
		$this->sql .= " ORDER BY " . $order . " " . $is_asc;
	}

	public function setLimitAndOffset($limit = 1, $offset = 0) {
		$this->sql .= " LIMIT " . $limit . " OFFSET " . $offset;
	}

	public function getSqlStr() {
		return $this->sql;
	}

}

?>