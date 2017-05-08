<?php 

/**
 * 公共头部文件
 * @author 冬天的秘密
 * @link http://bbs.itpk.cn
 * @version 1.0
 */

global $_set_html_title;
global $_link_file_array;
global $styleDef;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="description" content="茉莉机器人官网，出生于2014年的新型机器人，广泛应用于各类网站客服、QQ机器人和微信公众平台。" />
<meta name="keywords" content="机器人,网页机器人,机器人api,机器人接口,茉莉机器人,QQ机器人,微信机器人" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0" />
<title><?php echo $_set_html_title . " - " . DOMAIN_NAME; ?></title>

<link rel="stylesheet" href="<?php echo DOMAIN_FOLDER . CSS_FOLDER; ?>animation.css" />
<link rel="stylesheet" href="http://libs.useso.com/js/font-awesome/4.2.0/css/font-awesome.min.css" />
<link rel = "stylesheet" href = "<?php echo DOMAIN_FOLDER . CSS_FOLDER . $styleDef; ?>?ver=<?php echo VERSION;?>" type = "text/css" media = "all" />
<?php if (isset($_link_file_array)) { ?>
<?php foreach ($_link_file_array as $link_file) { ?>
<link rel = "stylesheet" href = "<?php echo DOMAIN_FOLDER . CSS_FOLDER . $link_file; ?>?ver=<?php echo VERSION;?>" type = "text/css" media = "all" />
<?php } ?>
<?php } ?>
</head>

<body>
<div id = "header">
	<div class = "container">
		<ul id = "navbar" class = "navbar">
			<li class="navto-home"><a href = "<?php echo DOMAIN_FOLDER; ?>"><i class = "fa fa-home fa-fw"></i>首页</a><span class = "split-line">|</span></li>
			<li class="navto-robot"><a href = "<?php echo DOMAIN_FOLDER; ?>robot.php">机器人</a><span class = "split-line">|</span></li>
			<li class="navto-plugin"><a href = "<?php echo DOMAIN_FOLDER; ?>plugin.php">插件中心</a><span class = "split-line">|</span></li>
			<li class="navto-chat"><a href = "<?php echo DOMAIN_FOLDER; ?>chat.php">聊室</a></li>
		</ul>
		<div class = "login-set">
			<?php if (LOGIN_STATUS == UserLoginUtil::SUCCESS) { ?>
			<a href = "profile.php" title = "用户中心 -- 控制面板"><i class = "fa fa-desktop fa-fw"></i></a>
			<?php } else { ?>
			<a href = "login.php" title = "登录"><i class = "fa fa-key fa-fw"></i></a>
			<?php } ?>
		</div>
	</div>
</div>