<?php 

/**
 * 茉莉QQ机器人计划任务外部监控程序
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

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
 * 获取计划任务状态
 */
$cron_status = $systemPer->getValueByName("cron", "status", CronUtil::_EXIT);

if ($cron_status == CronUtil::_EXIT) {
	ErrorUtil::put("因为计划任务已经关闭，所以此次执行已经终止");
}

/**
 * 获取计划任务执行的最后一次时间戳
 */
$cron_last_time = intval($systemPer->getValueByName("cron", "last_time", 0));

/**
 * 如果现在的时间和计划任务执行的最后一次时间相差小于一分钟（这里判断的是61秒,允许1秒误差），则终止此次访问
 */
if (time() - $cron_last_time <= 61) {
	ErrorUtil::put("因为计划任务正常工作中，所以此次执行已经终止");
}

/**
 * 激活计划任务
 */
$ch = curl_init(DOMAIN_FOLDER . "task.php");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
curl_setopt($ch, CURLOPT_TIMEOUT, 3);
$result = curl_exec($ch);
curl_close($ch);

/**
 * 给出提示
 */
ErrorUtil::put(!DataUtil::is_empty($result) ? $result : "计划任务激活成功");

?>