<div id = "main">
	<div class = "container">
		<div class = "page_title">删除用户<?php echo isset($removeResult) ? ($removeResult ? "<span class = \"tip-green\">成功</span>" : "<span class = \"tip-red\">失败</span>") : ""; ?></div>
		<div class = "bottom_title">
			<a class = "usual_button" href = "admin.php">基本</a>
			<a class = "usual_button" href = "user.php">用户</a>
			<a class = "usual_button" href = "role.php">角色</a>
			<a class = "usual_button" href = "renewal.php">续期</a>
			<a class = "usual_button" href = "update.php">更新</a>
		</div>
		<div class = "form_title2"><span class = "tip-red">此操作会删除此用户的所有数据,包括Ta的机器人和机器人产生的数据,请谨慎操作</span></div>
		<form action = "deluser.php" method = "post">
			<input type = "hidden" name = "user_id" value = "<?php echo $user_id; ?>" />
			<input type = "hidden" name = "delete" value = "true" />
			<table border = "0" cellpadding = "0" cellspacing = "0">
				<tr class = "odd_tr">
					<td class = "td_left">ID</td>
					<td class = "td_right"><?php echo $user_info['id'];?></td>
				</tr>
				<tr>
					<td class = "td_left">用户名</td>
					<td class = "td_right"><?php echo $user_info['username'];?></td>
				</tr>
				<tr class = "odd_tr">
					<td class = "td_left">邮箱</td>
					<td class = "td_right"><?php echo $user_info['mail'];?></td>
				</tr>
				<tr>
					<td class = "td_left">手机号</td>
					<td class = "td_right"><?php echo $user_info['phone'];?></td>
				</tr>
				<tr class = "odd_tr">
					<td class = "td_left">QQ号</td>
					<td class = "td_right"><?php echo $user_info['qq'];?></td>
				</tr>
				<tr>
					<td class = "td_left">金币</td>
					<td class = "td_right"><?php echo $user_info['gold'];?></td>
				</tr>
				<tr class = "odd_tr">
					<td class = "td_left">邀请码</td>
					<td class = "td_right"><?php echo $user_info['invitation'];?></td>
				</tr>
				<tr>
					<td class = "td_left">Ta的机器人</td>
					<td class = "td_right">
						<?php if ($robotArray) { ?>
						<?php foreach ($robotArray as $robot) { ?>
						<div><label class = "qq_label"><?php echo $robot['uin'];?></label><span class = "tip-green"><?php echo $robot['is_run'] == 0 ? "未运行" : ($robot['status'] != StatusUtil::ONLINE ? "未上线" : "运行中");?></span></div>
						<?php } ?>
						<?php } else { ?>
						该账号暂无机器人
						<?php } ?>
					</td>
				</tr>
				<tr class = "odd_tr">
					<td class = "td_left">用户组</td>
					<td class = "td_right"><?php echo $role['name']; ?></td>
				</tr>
				<tr>
					<td colspan="2"><input type = "submit" class="white_button" value = "确认删除" /></td>
				</tr>
			</table>
		</form>
	</div>
</div>