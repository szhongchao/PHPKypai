<?php if (!defined('ITPK')) exit('You can not directly access the file.');

/**
 * 数据操作类（一般为字符串的操作）
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

class DataUtil {

	/**
	 * 处理页面接收的参数，防止SQL注入
	 * @param string $param 接收的参数
	 * @param unknown $defVal 当没有此参数时的默认值
	 * @param boolean $is_bool 接收的参数是否转换为0/1（一般当参数的值为true、false、0、1时使用）
	 * @return string 返回经过处理的参数值
	 */
	public static function param_mysql_filter($param, $defVal = null, $is_bool = false) {
		$return_param = $defVal;
		if (isset($_GET["$param"])) $return_param = $_GET["$param"];
		elseif (isset($_POST["$param"])) $return_param = $_POST["$param"];
		if ($is_bool) return ($return_param == null || $return_param == "false" || $return_param === "0") ? 0 : 1;
		return ($return_param == null) ? null : trim(addslashes($return_param));
	}

	/**
	 * 处理通过POST表单的checkbox值
	 * @param string $param
	 * @return array
	 */
	public static function param_mysql_filter_checkbox($param, $defVal = null) {
		$return_array = $defVal;
		if (isset($_POST["$param"])) {
			$return_params = $_POST["$param"];
			if (is_array($return_params)) {
				$return_array = array();
				foreach ($return_params as $return_param) {
					array_push($return_array, trim(addslashes($return_param)));
				}
			}
		}
		return $return_array;
	}

	/**
	 * 判断是否接收到某参数
	 * @param string $param
	 * @return boolean
	 */
	public static function is_exits($param) {
		return isset($_GET["$param"]) ? true : (isset($_POST["$param"]) ? true : false);
	}

	public static function transformation($str, $isarray, $is_ignore) {
		$new_str = $str;
		if ($is_ignore) {
			if ($isarray) {
				$new_str = array();
				for ($i = 0; $i < count($str); $i++) {
					array_push($new_str, strtolower($str[$i]));
				}
			} else {
				$new_str = strtolower($str);
			}
		}
		return $new_str;
	}

	/**
	 * 判断某个字符串是否包含某个字符或字符串
	 * @param string $input 要判断的原字符串
	 * @param array|string $contain 要判断包含的字符或字符串
	 * @param boolean $isarray 设置$contain是否为数组，默认为false，$contain为字符
	 * @param boolean $is_ignore 是否忽略大小写的比较
	 * @return boolean 如果包含返回true，不包含返回false
	 */
	public static function is_contain($contain, $input, $isarray = false, $is_ignore = false) {
		$contain = DataUtil::transformation($contain, $isarray, $is_ignore);
		$input = DataUtil::transformation($input, false, $is_ignore);
		if ($isarray) {
			for ($i = 0; $i < count($contain); $i++) {
				if (count(explode($contain[$i], $input)) > 1) {
					return true;
				}
			}
			return false;
		}
		return count(explode($contain, $input)) > 1 ? true : false;
	}

	/**
	 * 判断某个字符串的开头是否为某个字符或字符串
	 * @param string $char 字符
	 * @param string $input 要判断的原字符串
	 * @param boolean $isarray 设置$char是否为数组，默认为false
	 * @param boolean $is_ignore 是否忽略大小写的比较
	 * @return boolean 如果是返回true，不是返回false
	 */
	public static function start_contain($char, $input, $isarray = false, $is_ignore = false) {
		$char = DataUtil::transformation($char, $isarray, $is_ignore);
		$input = DataUtil::transformation($input, false, $is_ignore);
		if ($isarray) {
			for ($i = 0; $i < count($char); $i++) {
				$strArr = explode($char[$i], $input);
				if (count($strArr) > 1 && ($strArr[0] == "" || strlen($strArr[0]) == 0)) {
					return true;
				}
			}
			return false;
		}
		$strArr = explode($char, $input);
		if (count($strArr) > 1 && ($strArr[0] == "" || strlen($strArr[0]) == 0)) {
			return true;
		}
		return false;
	}
	
	/**
	 * 判断某个字符串的结尾是否为某个字符或字符串
	 * @param string $char 字符
	 * @param string $input 要判断的原字符串
	 * @param boolean $isarray 设置$char是否为数组，默认为false
	 * @param boolean $is_ignore 是否忽略大小写的比较
	 * @return boolean 如果是返回true，不是返回false
	 */
	public static function end_contain($char, $input, $isarray = false, $is_ignore = false) {
		$char = DataUtil::transformation($char, $isarray, $is_ignore);
		$input = DataUtil::transformation($input, false, $is_ignore);
		if ($isarray) {
			for ($i = 0; $i < count($char); $i++) {
				$strArr = explode($char[$i], $input);
				if (count($strArr) > 1 && ($strArr[1] == "" || strlen($strArr[1]) == 0)) {
					return true;
				}
			}
			return false;
		}
		$strArr = explode($char, $input);
		if (count($strArr) > 1 && ($strArr[1] == "" || strlen($strArr[1]) == 0)) {
			return true;
		}
		return false;
	}

	public static function is_equal($char, $input, $isarray = false, $is_ignore = false) {
		$char = DataUtil::transformation($char, $isarray, $is_ignore);
		$input = DataUtil::transformation($input, false, $is_ignore);
		if ($isarray) {
			for ($i = 0; $i < count($char); $i++) {
				if ($char[$i] == $input) {
					return true;
				}
			}
			return false;
		}
		return $char == $input ? true : false;
	}

	/**
	 * 判断参数是否为空
	 * @param string $param 变量
	 * @return boolean 为空返回true，否则返回false
	 */
	public static function is_empty($param) {
		return ($param == null || $param == "") ? true : false;
	}

	/**
	 * 根据传入的数组，随机返回该数组的一个元素
	 * @param array $arraySrc		原数组
	 * @param boolean $is_shuffle	是否重新给数组排序
	 * @return unknown
	 */
	public static function random_array($arraySrc, $is_shuffle = false) {
		if ($is_shuffle) shuffle($arraySrc);
		$rowCount = count($arraySrc);
		$rowRand = rand(1, $rowCount) - 1;
		return $arraySrc[$rowRand];
	}

	/**
	 * 把字符串的首字母变成小写，因为PHP5.3之前不支持lcfirst函数，所有重写了此函数
	 * @param string $input
	 * @return string
	 */
	public static function lcfirst($input) {
		$first = mb_strtolower(mb_substr($input, 0, 1, "utf8"), "utf8");
		$other = mb_substr($input, 1, mb_strlen($input, "utf8") - 1, "utf8");
		return $first . $other;
	}

	/**
	 * 根据插件的类名获得该插件的目录名（并不是绝对路径，而只是目录名）
	 * @param string $className
	 * @return string
	 */
	public static function getPluginFolder($className) {
		if (!DataUtil::start_contain("Web", $className) || !DataUtil::end_contain("Handler", $className)) {
			return "";
		}
		$folder = explode("Web", $className);
		$folder = explode("Handler", $folder[1]);
		return strtolower($folder[0]);
	}

	/**
	 * 删除指定目录以及目录下的所有文件
	 * @param string $folder
	 * @return boolean 成功返回true，失败返回false
	 */
	public static function delFolder($folder) {
		//打开指定目录
		$dh = opendir($folder);
		while ($file = readdir($dh)) {
			if ($file != "." && $file != "..") {
				$fullpath = $folder . "/" . $file;
				//先删除目录下的文件
				if (is_dir($fullpath)) {
					DataUtil::delFolder($fullpath);
				} else {
					unlink($fullpath);
				}
			}
		}
		closedir($dh);

		//删除当前文件夹
		return rmdir($folder) ? true : false;
	}

	/**
	 * 获取指定目录下某种类型的所有文件名
	 * @param string $folder		指定目录名
	 * @param string $type			文件类型（文件后缀）
	 * @param boolean $is_plugin	是否为插件安装包（插件安装包命名规则：Web+插件自定义目录名+Handler）
	 * @return array
	 */
	public static function getFolderFiles($folder, $type = ".zip", $is_plugin = true) {
		//打开指定目录
		$dh = opendir($folder);
		$files = array();
		while ($file = readdir($dh)) {
			if ($file != "." && $file != "..") {
				$fullpath = $folder . $file;
				if (is_dir($fullpath) || !DataUtil::end_contain($type, $file)) {
					continue;
				}
				$name = explode($type, $file);
				if ($is_plugin && DataUtil::is_empty(DataUtil::getPluginFolder($name[0]))) {
					continue;
				}
				array_push($files, $file);
			}
		}
		closedir($dh);
		return $files;
	}

	/**
	 * 获取未安装的插件目录
	 * @param string $folder
	 * @param array $classNames
	 * @param string $is_plugin
	 * @return array
	 */
	public static function getFolders($folder, $classNames) {
		//打开指定目录
		$dh = opendir($folder);
		$folders = array();
		while ($file = readdir($dh)) {
			if ($file != "." && $file != "..") {
				$fullpath = $folder . $file;
				if (is_dir($fullpath)) {
					$classCount = count(DataUtil::getFolderFiles($fullpath, ".php", true));
					$sqlCount = count(DataUtil::getFolderFiles($fullpath, ".sql", false));
					if ($classCount >= 1 && $sqlCount >= 1 && !DataUtil::is_equal($classNames, "Web" . $file . "Handler", true)) {
						array_push($folders, $file);
					}
				}
			}
		}
		closedir($dh);
		return $folders;
	}

	/**
	 * 生成随机字符
	 * @param int $length
	 * @param int $type
	 * @return string | int
	 */
	public static function getRandString($length = 12, $type = 0) {
		$lower	= range('a', 'z');
		$upper	= range('A', 'Z');
		$number	= range(0, 9);

		if($type == 0) {
			$chars = array_merge($lower, $upper, $number);
		} elseif($type == 1) {
			$chars = $lower;
		} elseif($type == 2) {
			$chars = $upper;
		} elseif($type == 3) {
			$chars = array_merge($lower, $upper);
		} elseif($type == 4) {
			$chars = $number;
		}

		shuffle($chars);
		$char_keys	= array_rand($chars, $length);
		shuffle($char_keys);

		$rand = '';
		foreach($char_keys as $key) {
			$rand .= $chars[$key];
		}
		return $rand;
	}

	/**
	 * 把数组的VALUE拼成字符串，用port隔开
	 * @param array $array
	 * @param string $port
	 * @return string
	 */
	public static function arrayToString($array, $port) {
		$str = "";
		foreach ($array as $key=>$value) {
			$str .= ($value . $port); 
		}
		return rtrim($str, $port);
	}

	/**
	 * 获取访问者IP
	 * @return Ambigous <string, unknown>
	 */
	public static function getIP() {
		$ip = "";
		if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
			$ip = getenv("HTTP_CLIENT_IP");
		} else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		} else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {
			$ip = getenv("REMOTE_ADDR");
		} else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) {
			$ip = $_SERVER['REMOTE_ADDR'];
		} else {
			$ip = "unknown";
		}
		if (strpos($ip, ',')) {
			$ipArr = explode(',', $ip);
			$ip = $ipArr[0];
		}
		return $ip;
	}
}

?>