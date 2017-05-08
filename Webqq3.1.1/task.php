<?php 

/**
 * 茉莉QQ机器人自用计划任务
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
 * 获取计划任务执行的最后一次时间戳
 */
$cron_last_time = intval($systemPer->getValueByName("cron", "last_time", 0));

/**
 * 获取机器人工作的入口密码
 */
$cron_pass = $systemPer->getValueByName("cron", "pass");

/**
 * 获取计划任务状态
 */
$cron_status = $systemPer->getValueByName("cron", "status", CronUtil::_EXIT);

/**
 * 判断计划任务是不是终止状态
 */
if ($cron_status == CronUtil::_EXIT) {
	ErrorUtil::put("计划任务已经关闭，如需使用，请在网站后台开启");
}

/**
 * 如果现在的时间和计划任务执行的最后一次时间相差小于半分钟，则终止此次访问
 */
if (time() - $cron_last_time <= 30) {
	ErrorUtil::put("您此次的执行是没有必要的,因为该进程在执行中,或者该进程在短时间之内已经执行过,请稍后重试。");
}

/**
 * 记录此次运行的时间
 */
$systemPer->replaceSys("cron", "last_time", time());

/**
 * 是否禁用了sleep
 */
$is_support_sleep = function_exists('sleep');

do {
	/**
	 * 判断计划任务是不是终止状态
	 */
	if ($cron_status == CronUtil::_EXIT) {
		ErrorUtil::put("计划任务已经关闭，如需使用，请在网站后台开启");
	}

	/**
	 * 记录此次循环的开始时间戳
	 */
	$start_time = time();

	if ($is_support_sleep) {
		/**
		 * 记录此次运行的时间
		 */
		$systemPer->replaceSys("cron", "last_time", $start_time);
	}

	if ($cron_status == CronUtil::RUN) {
		/**
		 * 实例化QQ机器人操作类（对数据库的操作）
		 */
		$robotPer = new WebrobotPer();

		/**
		 * 获取所有开启了运行状态的机器人
		 */
		$robotArray = $robotPer->getMeAll();

		if (is_array($robotArray) && count($robotArray) > 0) {
			$data    = array();
			$handle  = array();
			$i = 0;
			$running = 0;
			/**
			 * 初始化一个curl批处理句柄资源
			 */
			$mh = curl_multi_init();
			/**
			 * 向curl批处理会话中添加单独的curl句柄资源
			 */
			foreach($robotArray as $robot) {
				$url = DOMAIN_FOLDER . "run.php?uin=" . $robot['uin'] . "&secret=" . $robot['secret'] . "&pass=" . $cron_pass;
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_TIMEOUT, 10);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
				curl_setopt($ch, CURLOPT_USERAGENT, WqCommon::USERAGENT);
				curl_multi_add_handle($mh, $ch);
				$handle[$i++] = $ch;
			}
			/**
			 * 批处理机器人任务
			 */
			do {
				curl_multi_exec($mh, $running);
			} while ($running > 0);
			/**
			 * 读取数据
			 */
			foreach($handle as $i => $ch) {
				$content  = curl_multi_getcontent($ch);
				$data[$i] = (curl_errno($ch) == 0) ? $content : false;
			}
			/**
			 * 移除handle
			 */
			foreach($handle as $ch) {
				curl_multi_remove_handle($mh, $ch);
			}
			/**
			 * 关闭批处理句柄资源
			 */
			curl_multi_close($mh);
		}
	}

	/**
	 * 进程睡眠一段时间后继续执行
	 */
	@sleep($start_time + 30 - time());

	/**
	 * 记录此次运行的时间
	 */
	$systemPer->replaceSys("cron", "last_time", $start_time);

	/**
	 * 重新获取计划任务的状态
	 */
	$cron_status = $systemPer->getValueByName("cron", "status", CronUtil::_EXIT);
} while(true);

?>