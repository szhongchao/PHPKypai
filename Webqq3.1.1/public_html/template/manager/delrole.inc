<div id = "main">
	<div class = "container">
		<div class = "page_title">删除角色<?php echo isset($removeResult) ? (!$removeResult ? "<span class = \"tip-red\">失败</span>" : "") : ""; ?></div>
		<div class = "bottom_title">
			<a class = "usual_button" href = "admin.php">基本</a>
			<a class = "usual_button" href = "user.php">用户</a>
			<a class = "usual_button" href = "role.php">角色</a>
			<a class = "usual_button" href = "renewal.php">续期</a>
			<a class = "usual_button" href = "update.php">更新</a>
		</div>
		<div class = "form_title2"><span class = "tip-red">如果此角色存在用户，则删除按钮被禁用</span></div>
		<form action = "delrole.php" method = "post">
			<input type = "hidden" name = "role_id" value = "<?php echo $role_id; ?>" />
			<input type = "hidden" name = "delete" value = "true" />
			<table border = "0" cellpadding = "0" cellspacing = "0">
				<tr class = "odd_tr">
					<td class = "td_left">角色名</td>
					<td class = "td_right"><?php echo $role['name'];?></td>
				</tr>
				<tr>
					<td class = "td_left">排序</td>
					<td class = "td_right"><?php echo $role['sort'];?></td>
				</tr>
				<tr class = "odd_tr">
					<td class = "td_left">机器人配额</td>
					<td class = "td_right"><?php echo $role['add_robot_max_number'];?></td>
				</tr>
				<tr>
					<td class = "td_left">初始金币</td>
					<td class = "td_right"><?php echo $role['init_gold'];?></td>
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
					<td class = "td_left">用户数量</td>
					<td class = "td_right"><code><?php echo $count; ?></code>个，大于0则无法删除</td>
				</tr>
				<tr class = "odd_tr">
					<td colspan="2"><input type = "submit" class="white_button" value = "确认删除" <?php echo $count > 0 ? "disabled=\"disabled\"" : "";?> /></td>
				</tr>
			</table>
		</form>
	</div>
</div>