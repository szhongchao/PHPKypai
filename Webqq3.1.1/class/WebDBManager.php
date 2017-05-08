<?php if (!defined('ITPK')) exit('You can not directly access the file.');

/**
 * Webqq数据库操作类
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

class WebDBManager extends AbstractDatabaseManager {

	public static function &getInstance(){
		static $manager_;

		if ($manager_ == null) {
			$manager_ = new WebDBManager();
			$manager_->init(DBHOST, DBUSER, DBPASS, DBBASE, DBPORT, DBCODE);
		}

		return $manager_;
	}

}

?>