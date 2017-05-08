<div id = "main">
	<div class = "container">
		<div class = "page_title">续期机器人<?php echo isset($buyResult) ? ($buyResult ? "<span class = \"tip-green\">成功</span>" : "<span class = \"tip-red\">失败</span>") : "";?></div>
		<div class = "bottom_title">
			<a class = "usual_button" href = "profile.php">个人中心</a>
			<a class = "usual_button" href = "updpro.php">修改资料</a>
			<a class = "usual_button" href = "renqq.php">续期账号</a>
			<a class = "usual_button" href = "admin.php">后台</a>
		</div>
		<?php if (isset($tip)) { ?>
		<div class = "form_title2"><span class = "tip-red"><?php echo htmlspecialchars($tip);?></span></div>
		<?php } ?>
		<form action = "renqq.php" method = "post">
			<table border = "0" cellpadding = "0" cellspacing = "0">
				<tr class = "odd_tr">
					<td class = "td_left">续期账号</td>
					<td class = "td_right">
						<select name = "ren_uin">
							<?php if ($robotArray) { ?>
							<?php foreach ($robotArray as $robot) { ?>
							<option value = "<?php echo $robot['id']; ?>" <?php echo $robot_id == $robot['id'] ? "selected = \"selected\"" : "";?>><?php echo $robot['uin']; ?></option>
							<?php } ?>
							<?php } else { ?>
							<option value = "0">暂无机器人</option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td class = "td_left">续期类型</td>
					<td class = "td_right">
						<select name = "ren_type">
							<?php if ($renewalArray) { ?>
							<?php foreach ($renewalArray as $renewal) { ?>
							<option value = "<?php echo $renewal['id']; ?>" <?php echo $renewal_id == $renewal['id'] ? "selected = \"selected\"" : "";?>><?php echo $renewal['name'] . "(" . $renewal['day_time'] . "天-" . $renewal['gold'] . "金币)"; ?></option>
							<?php } ?>
							<?php } else { ?>
							<option value = "0">暂无续期类型</option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr class = "odd_tr">
					<td colspan="2"><input type = "submit" class="white_button" value = "确认续期" /></td>
				</tr>
			</table>
		</form>
	</div>
</div>