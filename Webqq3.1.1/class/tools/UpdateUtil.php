<?php if (!defined('ITPK')) exit('You can not directly access the file.');

/**
 * 系统更新操作类
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

class UpdateUtil extends WebDBConnection {

	private $sql_array = array();
	private $file_array = array();
	private $folder = "";
	private $is_continue = true;

	/**
	 * 初始化
	 * @param array $sql_array
	 * @param array $file_array
	 * @param string $folder
	 */
	public function setInit($sql_array, $file_array, $folder) {
		$this->sql_array = $sql_array;
		$this->file_array = $file_array;
		$this->folder = $folder;
	}

	/**
	 * 执行更新中的SQL语句
	 */
	public function executeUpdateWithSql() {
		$sql = implode(";", $this->sql_array) . ";";
		$this->db->executeMultiQuery($sql);
	}

	/**
	 * 更新系统文件
	 */
	public function executeUpdateWithFile() {
		foreach ($this->file_array as $file) {
			@file_put_contents($file, @file_get_contents("http://plugin.itpk.cn/update/{$this->folder}/{$file}"));
		}
	}

	/**
	 * 检查更新文件是否可以正常写入
	 */
	public function checkFile() {
		$files = array();
		foreach ($this->file_array as $file) {
			$is_writable = true;
			if (file_exists($file)) {
				if (!is_writable($file)) {
					$this->is_continue = false;
					$is_writable = false;
				}
			} else {
				$file_ex = explode("/", $file);
				$folder = ROOT;
				if (count($file_ex) > 1) {
					unset($file_ex[count($file_ex)-1]);
					$folder = implode("/", $file_ex);
				}
				if (!is_writable($folder)) {
					$this->is_continue = false;
					$is_writable = false;
				}
			}
			$info = array();
			$info['name'] = $file;
			$info['is_writable'] = $is_writable;
			array_push($files, $info);
		}
		return $files;
	}

	/**
	 * 是否可以继续安装
	 */
	public function is_continue() {
		return $this->is_continue;
	}
} 
?>