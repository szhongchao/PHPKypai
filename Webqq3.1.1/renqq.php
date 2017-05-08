<?php

/**
 * 续期机器人
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

/**
 * 加载主体文件
 */
require_once 'web-init.php';

/**
 * 如果用户不是登录成功状态则跳转到登录页面
 */
if (LOGIN_STATUS != UserLoginUtil::SUCCESS) header("location:login.php");

$robot_id = DataUtil::param_mysql_filter("robot_id", 0);

$renewal_id = 0;

$renewalPer = new WebrenewalPer();

$robotPer = new WebrobotPer();

$renewalArray = $renewalPer->getMeAll();

$robotArray = $robotPer->getMeAllByUserId(USER_ID);

if (DataUtil::is_exits("ren_uin") && DataUtil::is_exits("ren_type")) {
	$buyResult = false;
	$ren_uin = DataUtil::param_mysql_filter("ren_uin");
	$ren_type = DataUtil::param_mysql_filter("ren_type");
	if ($renewalArray && $robotArray) {
		$sel_renewal = false;
		$sel_robot = false;
		foreach ($renewalArray as $renewal) {
			if ($renewal['id'] == $ren_type) {
				$sel_renewal = $renewal;
				break;
			}
		}
		foreach ($robotArray as $robot) {
			if ($robot['id'] == $ren_uin) {
				$sel_robot = $robot;
				break;
			}
		}
		if ($sel_renewal && $sel_robot) {
			$robot_id = $sel_robot['id'];
			$renewal_id = $sel_renewal['id'];
			$userGold = intval($user['gold']);
			$buyGold = intval($sel_renewal['gold']);
			if ($userGold >= $buyGold) {
				if ($userPer->updateProfile("id", USER_ID, "gold", $buyGold, true, "-")) {
					$limitdate = intval($sel_robot['limitdate']);
					$limitdate = time() - $limitdate > 0 ? time() : $limitdate;
					$limitdate += (intval($sel_renewal['day_time']) * 24 * 60 * 60);
					$buyResult = $robotPer->updateProfile($sel_robot, "limitdate", $limitdate);
					if (!$buyResult) {
						$userPer->updateProfile("id", USER_ID, "gold", $buyGold, true, "+");
						$tip = "续期失败，金币已经返还";
					}
				} else {
					$tip = "支付金币失败";
				}
			} else {
				$tip = "您的金币不足";
			}
		} else {
			$tip = "机器人或续期类型选择错误";
		}
	} else {
		$tip = "没有机器人或没有续期类型";
	}
}

/**
 * 设置网页标题
 */
$_set_html_title = "续期机器人";

/**
 * 需要加载的CSS文件
 */
$_link_file_array = array();

/**
 * 需要加载的JavaScript文件
 */
$_script_file_array = array();

/**
 * 加载公共头部文件
 */
require_once DOCUMENTS_FOLDER . "header.inc";

/**
 * 导入首页模板文件
 */
require_once MANAGER_FOLDER . "renqq.inc";

/**
 * 加载公共尾部文件
 */
require_once DOCUMENTS_FOLDER . 'footer.inc';

?>