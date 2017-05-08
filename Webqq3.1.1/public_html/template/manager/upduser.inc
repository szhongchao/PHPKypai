<div id = "main">
	<div class = "container">
		<div class = "page_title">编辑用户<?php echo isset($updateResult) ? ($updateResult ? "<span class = \"tip-green\">成功</span>" : "<span class = \"tip-red\">失败</span>") : ""; ?></div>
		<div class = "bottom_title">
			<a class = "usual_button" href = "admin.php">基本</a>
			<a class = "usual_button" href = "user.php">用户</a>
			<a class = "usual_button" href = "role.php">角色</a>
			<a class = "usual_button" href = "renewal.php">续期</a>
			<a class = "usual_button" href = "update.php">更新</a>
		</div>
		<form action = "upduser.php" method = "post">
			<input type = "hidden" name = "user_id" value = "<?php echo $user_id; ?>" />
			<table border = "0" cellpadding = "0" cellspacing = "0">
				<tr class = "odd_tr">
					<td class = "td_left">ID</td>
					<td class = "td_right"><input type = "text" name = "id" value = "<?php echo $user_info['id'];?>" /></td>
				</tr>
				<tr>
					<td class = "td_left">用户名</td>
					<td class = "td_right"><input type = "text" name = "username" value = "<?php echo $user_info['username'];?>" /></td>
				</tr>
				<tr class = "odd_tr">
					<td class = "td_left">邮箱</td>
					<td class = "td_right"><input type = "text" name = "mail" value = "<?php echo $user_info['mail'];?>" /></td>
				</tr>
				<tr>
					<td class = "td_left">手机号</td>
					<td class = "td_right"><input type = "text" name = "phone" value = "<?php echo $user_info['phone'];?>" /></td>
				</tr>
				<tr class = "odd_tr">
					<td class = "td_left">QQ号</td>
					<td class = "td_right"><input type = "text" name = "qq" value = "<?php echo $user_info['qq'];?>" /></td>
				</tr>
				<tr>
					<td class = "td_left">金币</td>
					<td class = "td_right"><input type = "text" name = "gold" value = "<?php echo $user_info['gold'];?>" /></td>
				</tr>
				<tr class = "odd_tr">
					<td class = "td_left">邀请码</td>
					<td class = "td_right"><input type = "text" name = "invitation" value = "<?php echo $user_info['invitation'];?>" /></td>
				</tr>
				<tr>
					<td class = "td_left">密码</td>
					<td class = "td_right"><input type = "text" name = "password" placeholder = "留空不修改" /></td>
				</tr>
				<tr class = "odd_tr">
					<td class = "td_left">Ta的机器人</td>
					<td class = "td_right">
						<?php if ($robotArray) { ?>
						<?php foreach ($robotArray as $robot) { ?>
						<div><label class = "qq_label"><?php echo $robot['uin'];?></label><span class = "tip-green"><?php echo $robot['is_run'] == 0 ? "未运行" : ($robot['status'] != StatusUtil::ONLINE ? "未上线" : "运行中");?></span><label class = "label_checkbox"><input class = "form_checkbox" type = "checkbox" name = "delqq[]" value = "<?php echo $robot['uin'];?>" />删除</label></div>
						<?php } ?>
						<?php } else { ?>
						该账号暂无机器人
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td class = "td_left">用户组</td>
					<td class = "td_right">
						<select class = "table_select" name = "role_id">
							<?php if ($roleArray) { ?>
							<?php foreach ($roleArray as $role) { ?>
							<option value = "<?php echo $role['id']; ?>" <?php echo $user_info['role_id'] == $role['id'] ? "selected = \"selected\"" : "";?>><?php echo $role['name']; ?></option>
							<?php } ?>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr class = "odd_tr">
					<td colspan="2"><input type = "submit" class="white_button" value = "确认编辑" /></td>
				</tr>
			</table>
		</form>
	</div>
</div>