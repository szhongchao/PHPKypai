<?php if (!defined('ITPK')) exit('You can not directly access the file.');

/**
 * 机器人QQ操作类
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

class WebrobotPer extends WebDBConnection {

	private $table = "web_robot";

	/**
	 * 根据是否运行获取数据库中所有机器人
	 * @param int $is_run
	 * @return array
	 */
	public function getMeAll($is_run = 1) {
		$selectSql = new SelectSql($this->table);
		$selectSql->setWhere("is_run", $is_run);
		$selectSql->setOrder("id");
		return $selectSql->executeSelectSql($this->db, true);
	}

	/**
	 * 根据UserId获取数据库中所有的机器人QQ
	 * @return array | boolean
	 */
	public function getMeAllByUserId($user_id) {
		$selectSql = new SelectSql($this->table);
		$selectSql->setWhere("user_id", $user_id);
		$selectSql->setOrder("id");
		return $selectSql->executeSelectSql($this->db, true);
	}

	/**
	 * 根据用户ID获取机器人数量
	 * @param int $user_id
	 * @return number
	 */
	public function getCountByUserId($user_id) {
		$selectSql = new SelectSql($this->table, "count(1) as count");
		$selectSql->setWhere("user_id", $user_id);
		$result = $selectSql->executeSelectSql($this->db);
		return $result ? $result['count'] : 0;
	}

	/**
	 * 获取机器人数量
	 * @param int $user_id
	 * @param string $id_array
	 * @return number
	 */
	public function getCountByIdAndUserId($user_id, $id_array) {
		$selectSql = new SelectSql($this->table, "count(1) as count");
		$selectSql->setWhere("user_id", $user_id);
		$selectSql->setWhere("id", $id_array, "in");
		$result = $selectSql->executeSelectSql($this->db);
		return $result ? $result['count'] : 0;
	}

	/**
	 * 根据QQ获取机器人属性
	 * @param string $uin
	 * @return array | boolean
	 */
	public function getMeByUin($user_id, $uin) {
		$selectSql = new SelectSql($this->table);
		$selectSql->setWhere("user_id", $user_id);
		$selectSql->setWhere("uin", $uin);
		return $selectSql->executeSelectSql($this->db);
	}

	/**
	 * 根据QQ获取机器人属性
	 * @param string $uin
	 * @return array | boolean
	 */
	public function findMeByUin($uin) {
		$selectSql = new SelectSql($this->table);
		$selectSql->setWhere("uin", $uin);
		return $selectSql->executeSelectSql($this->db);
	}

	/**
	 * 根据QQ获取机器人属性
	 * @param string $uins
	 * @return array | boolean
	 */
	public function getMeByUins($user_id, $uins) {
		$selectSql = new SelectSql($this->table);
		$selectSql->setWhere("user_id", $user_id);
		$selectSql->setWhere("uin", $uins, "in");
		return $selectSql->executeSelectSql($this->db, true);
	}

	/**
	 * 根据QQ获取机器人属性
	 * @param string $secret
	 * @return array | boolean
	 */
	public function getMeBySecret($user_id, $secret, $is_run = 1) {
		$selectSql = new SelectSql($this->table);
		$selectSql->setWhere("user_id", $user_id);
		$selectSql->setWhere("secret", $secret);
		$selectSql->setWhere("is_run", $is_run);
		$selectSql->setLimitAndOffset();
		return $selectSql->executeSelectSql($this->db);
	}

	/**
	 * 根据是否开启了机器人功能获取QQ
	 * @param int $is_run 是否开启了机器人，默认为1（开启）
	 * @param int $limit 获取的个数，默认获取1个
	 * @return array | boolean
	 */
	public function getMe($user_id, $is_run = 1, $limit = 1) {
		$selectSql = new SelectSql($this->table);
		$selectSql->setWhere("user_id", $user_id);
		$selectSql->setWhere("is_run", $is_run);
		$selectSql->setOrder("id");
		$selectSql->setLimitAndOffset($limit);
		return $selectSql->executeSelectSql($this->db);
	}

	/**
	 * 根据机器人账号获取属性
	 * @param string $uin
	 * @param string $profile
	 * @return string
	 */
	public function getProfileByUin($user_id, $uin, $profile = "name") {
		$selectSql = new SelectSql($this->table, $profile);
		$selectSql->setWhere("user_id", $user_id);
		$selectSql->setWhere("uin", $uin);
		$selectSql->setOrder("id");
		$selectSql->setLimitAndOffset(1);
		$qq = $selectSql->executeSelectSql($this->db);
		return $qq["{$profile}"];
	}

	/**
	 * 更新机器人属性
	 * @param string $uin
	 * @param string $profile
	 * @param string $value
	 * @param int
	 */
	public function updateProfileByUin($user_id, $uin, $profile, $value) {
		$updateSql = new UpdateSql($this->table);
		$updateSql->setUpdateValue($profile, $value);
		$updateSql->setWhere("uin", $uin);
		$updateSql->setWhere("user_id", $user_id);
		return $updateSql->executeUpdateSql($this->db);
	}

	/**
	 * 更新机器人属性
	 * @param string $uin
	 * @param string $secret
	 * @param string $profile
	 * @param string $value
	 */
	public function updateProfileByUinAndSecret($uin, $secret, $profile, $value) {
		$updateSql = new UpdateSql($this->table);
		$updateSql->setUpdateValue($profile, $value);
		$updateSql->setWhere("uin", $uin);
		$updateSql->setWhere("secret", $secret);
		return $updateSql->executeUpdateSql($this->db);
	}

	/**
	 * 更新机器人属性
	 * @param unknown $robot
	 * @param string $profile
	 * @param string $value
	 * @return boolean
	 */
	public function updateProfile($robot, $profile, $value) {
		$updateSql = new UpdateSql($this->table);
		$updateSql->setUpdateValue($profile, $value);
		$updateSql->setWhere("uin", $robot['uin']);
		$updateSql->setWhere("secret", $robot['secret']);
		return $updateSql->executeUpdateSql($this->db);
	}

	/**
	 * 获取机器人的某个属性
	 * @param unknown $robot
	 * @param string $profile
	 * @return unknown
	 */
	public function getProfile($robot, $profile = "name") {
		$selectSql = new SelectSql($this->table, $profile);
		$selectSql->setWhere("uin", $robot['uin']);
		$selectSql->setWhere("secret", $robot['secret']);
		$selectSql->setOrder("id");
		$selectSql->setLimitAndOffset(1);
		$qq = $selectSql->executeSelectSql($this->db);
		return $qq["{$profile}"];
	}

	/**
	 * 清除登录痕迹
	 * @param string $uin
	 * @return int
	 */
	public function removeLoginRecord($robot) {
		$updateSql = new UpdateSql($this->table);
		$updateSql->setUpdateValue("rsa_pass", "");
		$updateSql->setUpdateValue("code", "");
		$updateSql->setUpdateValue("verification", "");
		$updateSql->setWhere("uin", $robot['uin']);
		$updateSql->setWhere("secret", $robot['secret']);
		return $updateSql->executeUpdateSql($this->db);
	}

	/**
	 * 关闭机器人运行并且恢复初始状态
	 * @param unknown $robot
	 * @return boolean
	 */
	public function setRobotInit($robot) {
		$updateSql = new UpdateSql($this->table);
		$updateSql->setUpdateValue("is_run", 0);
		$updateSql->setUpdateValue("status", StatusUtil::INIT);
		$updateSql->setWhere("uin", $robot['uin']);
		$updateSql->setWhere("secret", $robot['secret']);
		return $updateSql->executeUpdateSql($this->db);
	}

	/**
	 * 关闭所有机器人运行并且恢复初始状态
	 * @return boolean
	 */
	public function setRobotAllInit() {
		$updateSql = new UpdateSql($this->table);
		$updateSql->setUpdateValue("is_run", 0);
		$updateSql->setUpdateValue("status", StatusUtil::INIT);
		return $updateSql->executeUpdateSql($this->db);
	}

	/**
	 * 根据QQ账号删除机器人
	 * @param string $uin
	 */
	public function deleteMe($user_id, $uin) {
		$deleteSql = new DeleteSql($this->table);
		$deleteSql->setWhere("uin", $uin);
		$deleteSql->setWhere("user_id", $user_id);
		return $deleteSql->executeDeleteSql($this->db);
	}

	/**
	 * 根据多个QQ账号删除机器人
	 * @param string $uin
	 */
	public function deleteMeByIds($user_id, $ids) {
		$deleteSql = new DeleteSql($this->table);
		$deleteSql->setWhere("id", $ids, "in");
		$deleteSql->setWhere("user_id", $user_id);
		return $deleteSql->executeDeleteSql($this->db);
	}

	/**
	 * 添加机器人账号
	 * @param string $user_id
	 * @param string $uin
	 * @param string $pass
	 * @param string $name
	 * @param string $create_uin
	 */
	public function insertMe($user_id, $uin, $pass, $name, $create_uin) {
		$insertSql = new InsertSql($this->table);
		$colunmArray = array(
			"user_id", "uin", "pass", "secret", "name", "create_uin", "createdate"
		);
		$colunmValueArray = array(
			$user_id, $uin, md5($pass), DataUtil::getRandString(8), $name, $create_uin, time()
		);
		$insertSql->setInsert($colunmArray, $colunmValueArray);
		return $insertSql->executeInsertSql($this->db);
	}

	/**
	 * 修改机器人账号
	 * @param string $uin
	 * @param string $pass
	 * @param string $name
	 * @param string $create_uin
	 * @param int $is_reply
	 * @param int $is_hook
	 * @param int $is_group_speech
	 * @param int $is_personal_speech
	 * @param int $is_reconnection
	 * @param int $is_run
	 */
	public function updateMe($user_id, $uin, $pass, $name, $create_uin, $is_reply, $is_hook, $is_group_speech, $is_personal_speech, $is_reconnection, $is_run) {
		$updateSql = new UpdateSql($this->table);
		$updateSql->setUpdateValue("name", $name);
		$updateSql->setUpdateValue("create_uin", $create_uin);
		$updateSql->setUpdateValue("is_reply", $is_reply);
		$updateSql->setUpdateValue("is_hook", $is_hook);
		$updateSql->setUpdateValue("is_group_speech", $is_group_speech);
		$updateSql->setUpdateValue("is_personal_speech", $is_personal_speech);
		$updateSql->setUpdateValue("is_reconnection", $is_reconnection);
		$updateSql->setUpdateValue("is_run", $is_run);
		if ($is_run == 0) {
			$updateSql->setUpdateValue("status", StatusUtil::INIT);
		}
		if (!DataUtil::is_empty($pass)) {
			$updateSql->setUpdateValue("pass", md5($pass));
		}
		$updateSql->setWhere("uin", $uin);
		$updateSql->setWhere("user_id", $user_id);
		return $updateSql->executeUpdateSql($this->db);
	}

	/**
	 * 批量修改机器人
	 * @param int $user_id
	 * @param string $id_array
	 * @param string $name
	 * @param string $create_uin
	 * @param int $is_reply
	 * @param int $is_hook
	 * @param int $is_group_speech
	 * @param int $is_personal_speech
	 * @param int $is_reconnection
	 * @param int $is_run
	 * @return boolean
	 */
	public function updateMeAll($user_id, $id_array, $name, $create_uin, $is_reply, $is_hook, $is_group_speech, $is_personal_speech, $is_reconnection, $is_run) {
		$updateSql = new UpdateSql($this->table);
		$updateSql->setUpdateValue("name", $name);
		$updateSql->setUpdateValue("create_uin", $create_uin);
		$updateSql->setUpdateValue("is_reply", $is_reply);
		$updateSql->setUpdateValue("is_hook", $is_hook);
		$updateSql->setUpdateValue("is_group_speech", $is_group_speech);
		$updateSql->setUpdateValue("is_personal_speech", $is_personal_speech);
		$updateSql->setUpdateValue("is_reconnection", $is_reconnection);
		$updateSql->setUpdateValue("is_run", $is_run);
		if ($is_run == 0) {
			$updateSql->setUpdateValue("status", StatusUtil::INIT);
		}
		$updateSql->setWhere("id", $id_array, "in");
		$updateSql->setWhere("user_id", $user_id);
		echo $updateSql->getSqlStr();
		return $updateSql->executeUpdateSql($this->db);
	}

	/**
	 * 根据机器人账号和机器人秘钥获取信息
	 * @param string $uin
	 * @param string $secret
	 */
	public function getMeByUinAndSecret($uin, $secret) {
		$selectSql = new SelectSql($this->table);
		$selectSql->setWhere("uin", $uin);
		$selectSql->setWhere("secret", $secret);
		$selectSql->setLimitAndOffset();
		return $selectSql->executeSelectSql($this->db);
	}

	/**
	 * 修改机器人的Skey和Bkn
	 * @param unknown $robot
	 * @param String $skey
	 * @param String $bkn
	 */
	public function updateSkeyAndBkn($robot, $skey, $bkn) {
		$updateSql = new UpdateSql($this->table);
		$updateSql->setUpdateValue("skey", $skey);
		$updateSql->setUpdateValue("bkn", $bkn);
		$updateSql->setWhere("uin", $robot['uin']);
		$updateSql->setWhere("secret", $robot['secret']);
		return $updateSql->executeUpdateSql($this->db);
	}

} 
?>