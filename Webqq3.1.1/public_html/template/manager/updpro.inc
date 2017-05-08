<div id = "main">
	<div class = "container">
		<form action = "" method = "post">
			<div class = "page_title">修改资料</div>
			<div class = "bottom_title">
				<a class = "usual_button" href = "profile.php">个人中心</a>
				<a class = "usual_button" href = "updpro.php">修改资料</a>
				<a class = "usual_button" href = "renqq.php">续期账号</a>
				<a class = "usual_button" href = "admin.php">后台</a>
			</div>
			<?php if (isset($updateResult)) { ?>
			<div class = "form_title"><span class = "tip-red"><?php echo ($updateResult ? "修改成功" : "修改失败,") . $tip; ?></span></div>
			<?php } ?>
			<ul class = "form_ul">
				<li title = "用户名"><label class = "form_label" for = "username"><i class = "fa fa-desktop fa-fw"></i></label><input class = "form_input" type = "text" id = "username" name = "username" placeholder = "用户名" value = "<?php echo $user['username']; ?>" /></li>
				<li title = "电子邮箱"><label class = "form_label" for = "mail"><i class = "fa fa-envelope-o fa-fw"></i></label><input class = "form_input" type = "text" id = "mail" name = "mail" placeholder = "电子邮箱" value = "<?php echo $user['mail']; ?>" /></li>
				<li title = "QQ号"><label class = "form_label" for = "qq"><i class = "fa fa-qq fa-fw"></i></label><input class = "form_input" type = "text" id = "qq" name = "qq" placeholder = "QQ号" value = "<?php echo $user['qq']; ?>" /></li>
				<li title = "新密码"><label class = "form_label" for = "new_pass"><i class = "fa fa-unlock-alt fa-fw"></i></label><input class = "form_input" type = "text" id = "new_pass" name = "new_pass" placeholder = "新密码,留空不修改" /></li>
				<li title = "确认新密码"><label class = "form_label" for = "new_pass2"><i class = "fa fa-unlock-alt fa-fw"></i></label><input class = "form_input" type = "text" id = "new_pass2" name = "new_pass2" placeholder = "确认新密码,留空不修改" /></li>
				<li><input class = "form_button" type = "submit" value = "确认修改" /></li>
			</ul>
		</form>
	</div>
</div>