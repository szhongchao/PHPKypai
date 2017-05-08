<div id = "main">
	<div class = "container">
		<form action = "" method = "post">
			<div class = "page_title"><?php echo (isset($tip) && !$tip) ? "<span class = \"tip-red\">登录失败</span>" : "登录"; ?></div>
			<ul class = "form_ul">
				<li title = "手机号"><label class = "form_label" for = "phone"><i class = "fa fa-phone fa-fw"></i></label><input class = "form_input" type = "text" id = "phone" name = "phone" placeholder = "手机号" /></li>
				<li title = "密码"><label class = "form_label" for = "password"><i class = "fa fa-lock fa-fw"></i></label><input class = "form_input" type = "password" id = "password" name = "password" placeholder = "密码" /></li>
				<li><input class = "form_button" type = "submit" value = "登录" /></li>
				<li><label class = "form_label"><i class = "fa fa-twitter fa-fw"></i></label><span class = "form_p_title">现在还没有账号 | <a href = "reg.php">前往注册</a></span></li>
				<li><label class = "form_label"><i class = "fa fa-twitter fa-fw"></i></label><span class = "form_p_title">悲剧，忘记密码 | <a href = "help.php">找回密码</a></span></li>
			</ul>
		</form>
	</div>
</div>