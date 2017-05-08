<div id = "main">
	<div class = "container">
		<div class = "page_title">基本设置</div>
		<div class = "bottom_title">
			<a class = "usual_button" href = "admin.php">基本</a>
			<a class = "usual_button" href = "user.php">用户</a>
			<a class = "usual_button" href = "role.php">角色</a>
			<a class = "usual_button" href = "renewal.php">续期</a>
			<a class = "usual_button" href = "update.php">更新</a>
		</div>
		<form action = "" method = "post">
			<table border = "0" cellpadding = "0" cellspacing = "0">
				<tr class = "odd_tr">
					<td class = "td_left">网站名字</td>
					<td class = "td_right"><input type = "text" name = "domain_name" value = "<?php echo $domain_name;?>" /></td>
				</tr>
				<tr>
					<td class = "td_left">公告标题</td>
					<td class = "td_right"><input type = "text" name = "notice_title" value = "<?php echo $notice_title;?>" /></td>
				</tr>
				<tr class = "odd_tr">
					<td class = "td_left">公告内容</td>
					<td class = "td_right"><input type = "text" name = "notice_content" value = "<?php echo $notice_content;?>" /></td>
				</tr>
				<tr>
					<td class = "td_left">接口密码</td>
					<td class = "td_right"><input type = "text" name = "cron_pass" value = "<?php echo $cron_pass; ?>" /></td>
				</tr>
				<tr class = "odd_tr">
					<td class = "td_left">邀请注册奖励</td>
					<td class = "td_right"><input type = "text" name = "invite_gold1" value = "<?php echo $invite_gold1; ?>" /></td>
				</tr>
				<tr>
					<td class = "td_left">被邀请奖励</td>
					<td class = "td_right"><input type = "text" name = "invite_gold2" value = "<?php echo $invite_gold2; ?>" /></td>
				</tr>
				<tr class = "odd_tr">
					<td class = "td_left">计划任务状态</td>
					<td class = "td_right"><?php echo $cron_is_normal ? "<span class = 'tip-green'>正常</span>" : "<span class = 'tip-red'>异常</span>"; ?> <a href = "tip.php?do=activate">激活?</a></td>
				</tr>
				<tr>
					<td class = "td_left">禁用机器人</td>
					<td class = "td_right"><label><input type = "radio" name = "is_stop_robot" value = "true" <?php echo $is_stop_robot == 1 ? "checked=\"checked\"" : "";?> />是</label> <label><input type = "radio" name = "is_stop_robot" value = "false" <?php echo $is_stop_robot == 0 ? "checked=\"checked\"" : "";?> />否</label> (系统所有机器人)</td>
				</tr>
				<tr class = "odd_tr">
					<td class = "td_left">网站样式</td>
					<td class = "td_right">
						<select class = "table_select" name = "style_default">
							<?php foreach ($styles as $style) { ?>
							<option value = "<?php echo $style['value']; ?>" <?php echo $styleDef == $style['value'] ? "selected = \"selected\"" : "";?>><?php echo $style['name']; ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td class = "td_left">会员注册方式</td>
					<td class = "td_right">
						<select class = "table_select" name = "is_reg">
							<option value = "1" <?php echo $is_reg == 1 ? "selected = \"selected\"" : "";?>>普通注册</option>
							<option value = "2" <?php echo $is_reg == 2 ? "selected = \"selected\"" : "";?>>邀请注册</option>
							<option value = "0" <?php echo $is_reg == 0 ? "selected = \"selected\"" : "";?>>关闭注册</option>
						</select>
					</td>
				</tr>
				<tr class = "odd_tr">
					<td class = "td_left">默认用户组</td>
					<td class = "td_right">
						<select class = "table_select" name = "admin_role">
							<option value = "0" <?php echo $admin_role == 0 ? "selected = \"selected\"" : "";?>>请选择</option>
							<?php if ($roleArray) { ?>
							<?php foreach ($roleArray as $role) { ?>
							<option value = "<?php echo $role['id']; ?>" <?php echo $admin_role == $role['id'] ? "selected = \"selected\"" : "";?>><?php echo $role['name']; ?></option>
							<?php } ?>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td class = "td_left">计划任务设置</td>
					<td class = "td_right">
						<select class = "table_select" name = "cron_status">
							<option value = "<?php echo CronUtil::RUN; ?>" <?php echo $cron_status == CronUtil::RUN ? "selected = \"selected\"" : "";?>>正常运行</option>
							<option value = "<?php echo CronUtil::STOP; ?>" <?php echo $cron_status == CronUtil::STOP ? "selected = \"selected\"" : "";?>>暂停</option>
							<option value = "<?php echo CronUtil::_EXIT; ?>" <?php echo $cron_status == CronUtil::_EXIT ? "selected = \"selected\"" : "";?>>停止</option>
						</select>
					</td>
				</tr>
				<tr class = "odd_tr">
					<td colspan="2"><input type = "submit" class="white_button" value = "确认设置" /></td>
				</tr>
			</table>
		</form>
	</div>
</div>