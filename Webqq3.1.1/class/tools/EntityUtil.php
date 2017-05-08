<?php if (!defined('ITPK')) exit('You can not directly access the file.');

/**
 * 对象存储类
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

class EntityUtil {

	private $entity = null;

	public function setKeyAndValue($key, $value) {
		$this->entity[$key] = $value;
	}

	public function setMultiKeyAndValue($keys, $values) {
		$keyCount = count($keys);
		$valueCount = count($values);
		if ($keyCount == $valueCount) {
			for ($i = 0; $i < $keyCount; $i++) {
				$this->entity[$keys[$i]] = $values[$i];
			}
		}
	}

	public function getMe() {
		return $this->entity;
	}
}

?>