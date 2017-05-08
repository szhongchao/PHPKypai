<div id = "main">
	<div class = "container">
		<div class = "page_title">注册中心</div>
		<?php if (isset($regResult)) { ?>
		<div class = "form_title"><span class = "tip-red"><?php echo ($regResult ? "注册成功" : "注册失败,") . $regTip; ?></span></div>
		<?php } ?>
		<form action = "reg.php" method = "post">
			<?php if ($inviteUser) { ?>
			<input type = "hidden" name = "invite" value = "<?php echo $inviteUser['invitation'];?>" />
			<?php } ?>
			<ul class = "form_ul">
				<li title = "用户名"><label class = "form_label" for = "username"><i class = "fa fa-user fa-fw"></i></label><input class = "form_input" type = "text" id = "username" name = "username" value = "<?php echo isset($regResult) ? htmlspecialchars($username) : '' ?>" placeholder = "用户名" /></li>
				<li title = "手机号"><label class = "form_label" for = "phone"><i class = "fa fa-phone fa-fw"></i></label><input class = "form_input" type = "text" id = "phone" name = "phone" value = "<?php echo isset($regResult) ? htmlspecialchars($phone) : '' ?>" placeholder = "手机号" /></li>
				<li title = "密码"><label class = "form_label" for = "password"><i class = "fa fa-lock fa-fw"></i></label><input class = "form_input" type = "password" id = "password" name = "password" value = "<?php echo isset($regResult) ? htmlspecialchars($password) : '' ?>" placeholder = "密码" /></li>
				<li title = "确认密码"><label class = "form_label" for = "npassword"><i class = "fa fa-lock fa-fw"></i></label><input class = "form_input" type = "password" id = "npassword" name = "npassword" value = "<?php echo isset($regResult) ? htmlspecialchars($npassword) : '' ?>" placeholder = "确认密码" /></li>
				<li title = "验证码图片"><label class = "form_label"><i class = "fa fa-image fa-fw"></i></label><img id = "verificateImg" src = "<?php echo DOMAIN_FOLDER;?>verificate/index.php" /> <span class = "form_p_title"><a href = "javascript:void(0)" onclick = "see()">看不清?换图</a></span></li>
				<li title = "验证码"><label class = "form_label" for = "verificate"><i class = "fa fa-file-text fa-fw"></i></label><input class = "form_input" type = "text" id = "verificate" name = "verificate" placeholder = "验证码" /></li>
				<?php if ($inviteUser) { ?>
				<li><label class = "form_label"><i class = "fa fa-gift fa-fw"></i></label><span class = "form_p_title">此邀请码发布者 | <a href = "javascript:void(0)"><?php echo htmlspecialchars($inviteUser['username']);?></a></span></li>
				<?php } ?>
				<li><input class = "form_button" type = "submit" value = "注册" /></li>
				<li><label class = "form_label"><i class = "fa fa-twitter fa-fw"></i></label><span class = "form_p_title">我已注册过账号 | <a href = "login.php">前往登录</a></span></li>
				<li><label class = "form_label"><i class = "fa fa-twitter fa-fw"></i></label><span class = "form_p_title">悲剧，忘记密码 | <a href = "help.php">找回密码</a></span></li>
			</ul>
		</form>
	</div>
</div>

<script type = "text/javascript">
function see() {
	document.getElementById("verificateImg").src = "verificate/index.php?t=" + new Date().getTime();;
}
</script>