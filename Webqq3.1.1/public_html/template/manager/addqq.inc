<div id = "main">
	<div class = "container">
		<form action = "" method = "post">
			<div class = "page_title">添加机器人</div>
			<?php if (isset($addqqResult)) { ?>
			<div class = "form_title"><span class = "tip-red"><?php echo ($addqqResult ? "添加成功" : "添加失败,") . $addTip; ?></span></div>
			<?php } ?>
			<ul class = "form_ul">
				<li><label class = "form_label" for = "uin"><i class = "fa fa-qq fa-fw"></i></label><input class = "form_input" type = "text" id = "uin" name = "uin" placeholder = "QQ账号" value = "<?php echo isset($uin) ? $uin : "";?>" /></li>
				<li><label class = "form_label" for = "pass"><i class = "fa fa-lock fa-fw"></i></label><input class = "form_input" type = "password" id = "pass" name = "pass" placeholder = "QQ密码" value = "<?php echo isset($pass) ? $pass : "";?>" /></li>
				<li><label class = "form_label" for = "name"><i class = "fa fa-paper-plane-o fa-fw"></i></label><input class = "form_input" type = "text" id = "name" name = "name" placeholder = "机器人名字" value = "<?php echo isset($name) ? $name : "";?>" /></li>
				<li><label class = "form_label" for = "create_uin"><i class = "fa fa-flag-o fa-fw"></i></label><input class = "form_input" type = "text" id = "create_uin" name = "create_uin" placeholder = "创建者QQ" value = "<?php echo isset($create_uin) ? $create_uin : "";?>" /></li>
				<li><input class = "form_button" type = "submit" value = "添加" /></li>
			</ul>
		</form>
	</div>
</div>