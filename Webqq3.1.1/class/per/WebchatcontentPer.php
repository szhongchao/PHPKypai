<?php if (!defined('ITPK')) exit('You can not directly access the file.');

/**
 * 聊天室发言操作类
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

class WebchatcontentPer extends WebDBConnection {

	private $table = "web_chat_content";

	/**
	 * 分页显示聊天室的发言
	 * @param int $limit
	 * @param int $offset
	 * @return array
	 */
	public function getMe($limit, $offset) {
		$findsql = "SELECT c.*, u.username FROM web_chat_content c LEFT JOIN web_user u ON c.user_id = u.id ORDER BY c.id DESC LIMIT {$limit} OFFSET {$offset}";
		return $this->db->executeQuery($findsql, true, true);
	}

	/**
	 * 获取聊天室的发言总数
	 * @return Ambigous <number, unknown>
	 */
	public function getCount() {
		$selectSql = new SelectSql($this->table, "count(1) as count");
		$result = $selectSql->executeSelectSql($this->db);
		return $result ? $result['count'] : 0;
	}

	/**
	 * 增加聊天室发言
	 * @param int $user_id
	 * @param string $content
	 * @param string $ip
	 * @return boolean
	 */
	public function insertMe($user_id, $content, $ip) {
		$insertSql = new InsertSql($this->table);
		$colunmArray = array(
			"user_id", "content", "ip", "createdate"
		);
		$colunmValueArray = array(
			$user_id, $content, $ip, time()
		);
		$insertSql->setInsert($colunmArray, $colunmValueArray);
		return $insertSql->executeInsertSql($this->db);
	}

	/**
	 * 获取某个用户在最近时间断的发言总数
	 * @param int $user_id
	 * @param int $time
	 * @return Ambigous <number, unknown>
	 */
	public function getCountByUserIdAndTime($user_id, $time) {
		$selectSql = new SelectSql($this->table, "count(1) as count");
		$selectSql->setWhere("user_id", $user_id);
		$selectSql->setWhere("createdate", time() - $time, ">=");
		$result = $selectSql->executeSelectSql($this->db);
		return $result ? $result['count'] : 0;
	}
}
?>