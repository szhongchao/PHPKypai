<?php if (!defined('ITPK')) exit('You can not directly access the file.');

/**
 * 用户操作类
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

class WebuserPer extends WebDBConnection {

	private $table = "web_user";

	/**
	 * 用户登录
	 * @param string $username
	 * @param string $password
	 * @return Ambigous <multitype:, boolean>
	 */
	public function login($phone, $password) {
		$selectSql = new SelectSql($this->table);
		$selectSql->setWhere("phone", $phone);
		$selectSql->setWhere("password", md5($password));
		$selectSql->setOrder("id");
		$selectSql->setLimitAndOffset();
		return $selectSql->executeSelectSql($this->db);
	}

	/**
	 * 检查用户的Cookie是否正确
	 * @param string $user_check
	 * @return Ambigous <multitype:, boolean>
	 */
	public function checkUser($user_check) {
		$selectSql = new SelectSql($this->table);
		$selectSql->setWhere("user_check", $user_check);
		$selectSql->setOrder("id");
		$selectSql->setLimitAndOffset();
		return $selectSql->executeSelectSql($this->db);
	}

	/**
	 * 更换Cookie
	 * @param int $id
	 * @param string $user_check
	 * @return Ambigous <multitype:, boolean>
	 */
	public function updateUserCheck($id, $user_check) {
		return $this->updateProfile("id", $id, "user_check", $user_check);
	}

	/**
	 * 根据列名修改它的值
	 * @param string $where
	 * @param unknown $where_value
	 * @param string $column
	 * @param unknown $column_value
	 * @param boolean $is_self
	 * @param string $symbol
	 */
	public function updateProfile($where, $where_value, $column, $column_value, $is_self = false, $symbol = "+") {
		$updateSql = new UpdateSql($this->table);
		if ($is_self) {
			$updateSql->setUpdateSelfValue($column, $column_value, $symbol);
		} else {
			$updateSql->setUpdateValue($column, $column_value);
		}
		$updateSql->setWhere($where, $where_value);
		return $updateSql->executeUpdateSql($this->db);
	}

	/**
	 * 根据ID获取用户资料
	 * @param unknown $user_id
	 */
	public function getMe($user_id) {
		$selectSql = new SelectSql($this->table);
		$selectSql->setWhere("id", $user_id);
		$selectSql->setLimitAndOffset();
		return $selectSql->executeSelectSql($this->db);
	}

	/**
	 * 根据邀请码获取用户资料
	 * @param unknown $user_id
	 */
	public function getMeByInvite($invite) {
		$selectSql = new SelectSql($this->table);
		$selectSql->setWhere("invitation", $invite);
		$selectSql->setLimitAndOffset();
		return $selectSql->executeSelectSql($this->db);
	}

	/**
	 * 获取所有的用户
	 * @param int $limit
	 * @param int $offset
	 * @return array
	 */
	public function getMeAll($limit, $offset) {
		$selectSql = new SelectSql($this->table);
		$selectSql->setOrder("id", "DESC");
		$selectSql->setLimitAndOffset($limit, $offset);
		return $selectSql->executeSelectSql($this->db, true);
	}

	/**
	 * 获取所有用户的总记录数
	 * @return int
	 */
	public function getAllUserCount() {
		$selectSql = new SelectSql($this->table, "count(1) as count");
		$result = $selectSql->executeSelectSql($this->db);
		return $result ? $result['count'] : 0;
	}

	/**
	 * 修改用户信息
	 * @param int $user_id
	 * @param string $username
	 * @param string $mail
	 * @param int $phone
	 * @param int $qq
	 * @return boolean
	 */
	public function updateMe($user_id, $username, $password, $mail, $phone, $qq) {
		$updateSql = new UpdateSql($this->table);
		$updateSql->setUpdateValue("username", $username);
		$updateSql->setUpdateValue("mail", $mail);
		$updateSql->setUpdateValue("qq", $qq);
		if (!DataUtil::is_empty($phone)) {
			$updateSql->setUpdateValue("phone", $phone);
		}
		if (!DataUtil::is_empty($password)) {
			$updateSql->setUpdateValue("password", $password);
		}
		$updateSql->setWhere("id", $user_id);
		return $updateSql->executeUpdateSql($this->db);
	}

	/**
	 * 管理员修改用户信息
	 * @param int $user_id
	 * @param int $id
	 * @param int $role_id
	 * @param string $username
	 * @param string $password
	 * @param string $mail
	 * @param int $phone
	 * @param int $qq
	 * @param int $gold
	 * @param string $invitation
	 * @return boolean
	 */
	public function updateMeAll($user_id, $id, $role_id, $username, $password, $mail, $phone, $qq, $gold, $invitation) {
		$updateSql = new UpdateSql($this->table);
		$updateSql->setUpdateValue("role_id", $role_id);
		$updateSql->setUpdateValue("username", $username);
		$updateSql->setUpdateValue("phone", $phone);
		$updateSql->setUpdateValue("gold", $gold);
		$updateSql->setUpdateValue("invitation", $invitation);
		if ($user_id != $id) {
			$updateSql->setUpdateValue("id", $id);
		}
		if (!DataUtil::is_empty($mail)) {
			$updateSql->setUpdateValue("mail", $mail);
		}
		if (!DataUtil::is_empty($qq)) {
			$updateSql->setUpdateValue("qq", $qq);
		}
		if (!DataUtil::is_empty($password)) {
			$updateSql->setUpdateValue("password", $password);
		}
		$updateSql->setWhere("id", $user_id);
		return $updateSql->executeUpdateSql($this->db);
	}

	/**
	 * 根据用户ID删除用户
	 * @param int $user_id
	 * @return boolean
	 */
	public function deleteMe($user_id) {
		$deleteSql = new DeleteSql($this->table);
		$deleteSql->setWhere("id", $user_id);
		return $deleteSql->executeDeleteSql($this->db);
	}

	/**
	 * 根据角色ID获取此角色所有的用户数量
	 * @param int $role_id
	 * @return int
	 */
	public function getCountByRoleId($role_id) {
		$selectSql = new SelectSql($this->table, "count(1) as count");
		$selectSql->setWhere("role_id", $role_id);
		$result = $selectSql->executeSelectSql($this->db);
		return $result ? $result['count'] : 0;
	}

	/**
	 * 根据用户资料查找用户
	 * @param string $name
	 * @param unknown $value
	 */
	public function findProfile($name, $value) {
		$selectSql = new SelectSql($this->table);
		$selectSql->setWhere($name, $value);
		$selectSql->setLimitAndOffset();
		return $selectSql->executeSelectSql($this->db);
	}

	/**
	 * 注册新用户
	 * @param int $role_id
	 * @param string $username
	 * @param string $password
	 * @param int $phone
	 */
	public function insertMe($role_id, $username, $password, $phone) {
		$insertSql = new InsertSql($this->table);
		$colunmArray = array(
			"role_id", "username", "password", "phone", "invitation", "reg_ip", "createdate"
		);
		$colunmValueArray = array(
			$role_id, $username, $password, $phone, DataUtil::getRandString(8, 1), DataUtil::getIP(), time()
		);
		$insertSql->setInsert($colunmArray, $colunmValueArray);
		return $insertSql->executeInsertSql($this->db);
	}

	/**
	 * 根据手机号更新用户密码
	 * @param int $phone
	 * @param string $password
	 */
	public function updatePassword($phone, $password) {
		if (DataUtil::is_empty($password)) {
			return false;
		}
		$updateSql = new UpdateSql($this->table);
		$updateSql->setUpdateValue("password", $password);
		$updateSql->setUpdateValue("user_check", "");
		$updateSql->setWhere("phone", $phone);
		return $updateSql->executeUpdateSql($this->db);
	}
}
?>