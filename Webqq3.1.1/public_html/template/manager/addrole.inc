<div id = "main">
	<div class = "container">
		<div class = "page_title">添加角色<?php echo isset($saveResult) ? ($saveResult ? "<span class = \"tip-green\">成功</span>" : "<span class = \"tip-red\">失败</span>") : ""; ?></div>
		<div class = "bottom_title">
			<a class = "usual_button" href = "admin.php">基本</a>
			<a class = "usual_button" href = "user.php">用户</a>
			<a class = "usual_button" href = "role.php">角色</a>
			<a class = "usual_button" href = "renewal.php">续期</a>
			<a class = "usual_button" href = "update.php">更新</a>
		</div>
		<form action = "addrole.php" method = "post">
			<input type = "hidden" name = "save" value = "true" />
			<table border = "0" cellpadding = "0" cellspacing = "0">
				<tr class = "odd_tr">
					<td class = "td_left">角色名</td>
					<td class = "td_right"><input type = "text" name = "name" /></td>
				</tr>
				<tr>
					<td class = "td_left">排序</td>
					<td class = "td_right"><input type = "text" name = "sort" /></td>
				</tr>
				<tr class = "odd_tr">
					<td class = "td_left">机器人配额</td>
					<td class = "td_right"><input type = "text" name = "add_robot_max_number" /></td>
				</tr>
				<tr>
					<td class = "td_left">初始金币</td>
					<td class = "td_right"><input type = "text" name = "init_gold" /></td>
				</tr>
				<tr class = "odd_tr">
					<td class = "td_left">角色权限</td>
					<td class = "td_right">
						<?php if ($roleCheckBoxHtmlArray) { ?>
						<?php foreach ($roleCheckBoxHtmlArray as $roleCheckBoxHtml) { ?>
						<div><?php echo $roleCheckBoxHtml; ?></div>
						<?php } ?>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td colspan="2"><input type = "submit" class="white_button" value = "确认添加" /></td>
				</tr>
			</table>
		</form>
	</div>
</div>