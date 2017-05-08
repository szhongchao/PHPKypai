<?php 

/**
 * 茉莉QQ机器人工作运行文件
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

/**
 * 即使客户端断开连接也继续执行脚本
 */
ignore_user_abort(true);

/**
 * 不限制脚本的执行时间
 */
set_time_limit(0);

/**
 * 是否检查用户的登录情况
 * @var boolean
 */
define('IS_CHECK_LOGIN', false);

/**
 * 加载主体文件
 */
require_once 'web-init.php';

/**
 * 记录开始时间
 */
$start_time = time();

/**
 * 获取传入的机器人账号
 */
$uin = DataUtil::param_mysql_filter("uin", false);

/**
 * 获取传入的机器人密钥
 */
$secret = DataUtil::param_mysql_filter("secret", false);

/**
 * 获取传入的接口密码
 */
$cron_pass = DataUtil::param_mysql_filter("pass", false);

/**
 * 判断系统是否已经停止了所有机器人的运行
 */
$is_stop_robot = $systemPer->getValueByName("control", "is_stop_robot", 1);

if ($is_stop_robot == 1) {
	ErrorUtil::put("管理员已经关闭了所有机器人的运行");
}

/**
 * 判断接口密码是否正确
 */
if (!$cron_pass || $systemPer->getValueByName("cron", "pass") != $cron_pass) {
	ErrorUtil::put("接口密码不正确");
}

/**
 * 如果密码为空则退出程序
 */
if (!$uin || !$secret) ErrorUtil::put("您需要传入机器人账号和密钥（并非机器人密码）才能继续访问");

/**
 * 实例化QQ机器人操作类（对数据库的操作）
 */
$robotPer = new WebrobotPer();

/**
 * 根据机器人账号和机器人秘钥获取信息
 */
$robot = $robotPer->getMeByUinAndSecret($uin, $secret);

if (!$robot) {
	ErrorUtil::put("此账号不存在或机器人秘钥不正确");
}

/**
 * 机器人基本属性
 */
$uin = $robot['uin']; $pass = $robot['pass']; $rsa_pass = $robot['rsa_pass']; $status = $robot['status']; $verification = $robot['verification']; $is_run = $robot['is_run']; $run_last_time = $robot['run_last_time'];

if ($is_run == 0) {
	ErrorUtil::put("此账号未开启运行");
}

/**
 * 如果此进程已经在执行中，则退出此次执行
 */
if ($start_time - intval($run_last_time) < 60) {
	ErrorUtil::put("您此次的执行是没有必要的,因为该进程在执行中,或者该进程在一分钟之内已经执行过");
}

/**
 * 运行记录类
 */
$runlogPer = new WebrunlogPer();

/**
 * 判断机器人的使用期限
 */
if ($robot['limitdate'] <= time()) {
	$runlogPer->insertMe($robot, "机器人使用时间已到期，请续期");
	$robotPer->setRobotInit($robot);
	ErrorUtil::put("机器人使用时间已到期，请续期");
}

/**
 * 更新机器人cron的最后时间
 */
$robotPer->updateProfileByUinAndSecret($uin, $secret, "run_last_time", time());

/**
 * 群成员操作类
 */
$groupmemberPer = new WebgroupmemberPer();

/**
 * 群成员操作类
 */
$groupmemberinfoPer = new WebgroupmemberinfoPer();

/**
 * 好友操作类
 */
$robotfriendsPer = new WebrobotfriendsPer();

/**
 * 验证码操作
 */
$verificationPer = new WebverificationPer();

/**
 * COOKIE操作类
 */
$cookiePer = new WebcookiePer();

/**
 * 插件操作类
 */
$pluginPer = new WebpluginPer();

/**
 * 实例化QQ机器人附件属性操作类（对数据库的操作）
 */
$robotdatPer = new WebrobotdatPer();

/**
 * 获取机器人附加属性
 */
$robotdat = $robotdatPer->getMeByRobotId($robot['id']);

$runlogPer->insertMe($robot, '已开始机器人运行');

/**
 * 实例化机器人操作类(对QQ的操作)
 */
$webRobot = new WebRobot( $robot, $robotdat, $robotPer, $robotdatPer, $runlogPer, $groupmemberPer, $groupmemberinfoPer, $robotfriendsPer, $verificationPer, $cookiePer, $pluginPer );

/**
 * 如果机器人不是正常在线则进行相关登录操作
 */
if ($status != StatusUtil::ONLINE) {

	if ($status == StatusUtil::INIT) {
		$status = $webRobot->checkVerify();
	}

	if ($status == StatusUtil::LOADING_RSA || $status == StatusUtil::LOADING_VERIFY) {
		$runlogPer->insertMe($robot, "请进行登录操作");
		$rsa_pass = "";
		while ($rsa_pass == "") {
			if ((time() - $start_time) % 60 == 0) {
				$robotPer->updateProfileByUinAndSecret($uin, $secret, "run_last_time", time());
			}
			if ((time() - $start_time) >= 300) {
				$robotPer->removeLoginRecord($robot);
				$robotPer->setRobotInit($robot);
				$runlogPer->insertMe($robot, "登录超时，已经自动关闭");
				die();
			}
			$robot = $robotPer->getMeByUinAndSecret($uin, $secret);
			$rsa_pass = $robot['rsa_pass'];
			if ($robot['is_run'] == 0) {
				$runlogPer->insertMe($robot, "机器人运行已停止");
				$robotPer->updateProfileByUinAndSecret($uin, $secret, "status", StatusUtil::INIT);
				die();
			}
			@sleep(3);
		}
		$status = StatusUtil::LOADING_LOGIN;
		$robotPer->updateProfileByUinAndSecret($uin, $secret, "status", $status);
	}

	if ($status == StatusUtil::LOADING_LOGIN) {
		$status =  $webRobot->login($robot);
		if ($status == StatusUtil::ONLINE) {
			$robot = $robotPer->getMeByUinAndSecret($robot['uin'], $robot['secret']);
		}
	}
}

if ($status == StatusUtil::ONLINE) {
	$rows = $cookiePer->getMeByUin($robot['id']);
	if (!$rows) {
		$runlogPer->insertMe($robot, 'Cookie丢失，已经自动关闭，请重新登录');
		$robotPer->setRobotInit($robot);
	}
	$start_time = time();
	while ($robot['is_run'] == 1 && $robot['status'] == StatusUtil::ONLINE) {
		if ($robot['limitdate'] <= time()) {
			$runlogPer->insertMe($robot, "机器人使用时间已到期，请续期");
			$robotPer->setRobotInit($robot);
			break;
		}
		if ($is_stop_robot == 1) {
			$runlogPer->insertMe($robot, "管理员禁用了系统所有机器人");
			$robotPer->setRobotInit($robot);
			break;
		}
		$poll = $webRobot->poll($rows);
		if (@array_key_exists('retcode', $poll) && $poll['retcode'] == 0) {
			$runlogPer->insertMe($robot, '收到' . count($poll['result']) . "条新消息");
			if ($robot['is_hook'] != 1 && isset($poll['result']) && count($poll['result']) < 20) {
				@$webRobot->process_message($poll, $rows);
			}
		} elseif (@array_key_exists('retcode', $poll) && $poll['retcode'] == 103) {
			$runlogPer->insertMe($robot, '身份验证失效，请重新登录');
			$robotPer->setRobotInit($robot);
			break;
		} elseif (@array_key_exists('retcode', $poll)) {
			if (isset($poll['errmsg']) && !DataUtil::is_empty($poll['errmsg'])) {
				$runlogPer->insertMe($robot, "Error:".$poll['retcode'].$poll['errmsg']);
				$robotPer->setRobotInit($robot);
				break;
			} elseif ($poll['retcode'] == 116) {
				$runlogPer->insertMe($robot, "例行安全检测");
			} elseif ($poll['retcode'] == 121) {
				$is_success = false;
				if ($robot['is_reconnection'] == 1) {
					$runlogPer->insertMe($robot, "账号异常,重新连接中");
					if ($webRobot->login2(trim($rows['clientid']), trim($rows['ptwebqq']), trim($rows['cookie']), $robot['cookie'], true)) {
						//重连成功后重新获取COOKIE
						$rows = $cookiePer->getMeByUin($robot['id']);
						$is_success = true;
					}
				}
				if (!$is_success) {
					$runlogPer->insertMe($robot, "身份验证失效，请重新登录");
					$robotPer->setRobotInit($robot);
				}
			} else {
				$runlogPer->insertMe($robot, "CODE:[" . $poll['retcode'] . "]");
			}
		} elseif (!$poll && (time() - $start_time >= 60)) {
			//心跳包执行超时则表明没有接收到新消息
			$runlogPer->insertMe($robot, '暂无新消息');
		}
		$robotPer->updateProfileByUinAndSecret($robot['uin'], $robot['secret'], "run_last_time", time()+1);
		$robot = $robotPer->getMeByUinAndSecret($robot['uin'], $robot['secret']);
		$robotdat = $robotdatPer->getMeByRobotId($robot['id']);
		$webRobot->setRobot($robot, $robotdat);
		$is_stop_robot = $systemPer->getValueByName("control", "is_stop_robot", 1);
		$start_time = time();
	}
	$runlogPer->insertMe($robot, '已停止机器人运行');
}

?>