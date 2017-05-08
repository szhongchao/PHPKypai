<div id = "main">
	<div class = "container">
		<div class = "page_title">角色配置<?php echo isset($updateResult) ? ($updateResult ? "<span class = \"tip-green\">成功</span>" : "<span class = \"tip-red\">失败</span>") : ""; ?></div>
		<div class = "bottom_title">
			<a class = "usual_button" href = "admin.php">基本</a>
			<a class = "usual_button" href = "user.php">用户</a>
			<a class = "usual_button" href = "role.php">角色</a>
			<a class = "usual_button" href = "renewal.php">续期</a>
			<a class = "usual_button" href = "update.php">更新</a>
		</div>
		<div class = "form_title2"><span class = "tip-red">此操作会影响所有属于这个角色的用户，请谨慎操作</span></div>
		<form action = "updrole.php" method = "post">
			<input type = "hidden" name = "role_id" value = "<?php echo $role_id; ?>" />
			<table border = "0" cellpadding = "0" cellspacing = "0">
				<tr class = "odd_tr">
					<td class = "td_left">角色名</td>
					<td class = "td_right"><input type = "text" name = "name" value = "<?php echo $role['name'];?>" /></td>
				</tr>
				<tr>
					<td class = "td_left">排序</td>
					<td class = "td_right"><input type = "text" name = "sort" value = "<?php echo $role['sort'];?>" /></td>
				</tr>
				<tr class = "odd_tr">
					<td class = "td_left">机器人配额</td>
					<td class = "td_right"><input type = "text" name = "add_robot_max_number" value = "<?php echo $role['add_robot_max_number'];?>" /></td>
				</tr>
				<tr>
					<td class = "td_left">初始金币</td>
					<td class = "td_right"><input type = "text" name = "init_gold" value = "<?php echo $role['init_gold'];?>" /></td>
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
					<td class = "td_left">受影响用户</td>
					<td class = "td_right"><code><?php echo $count; ?></code>个</td>
				</tr>
				<tr class = "odd_tr">
					<td colspan="2"><input type = "submit" class="white_button" value = "确认修改" /></td>
				</tr>
			</table>
		</form>
	</div>
</div>