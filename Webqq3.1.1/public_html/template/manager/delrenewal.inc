<div id = "main">
	<div class = "container">
		<div class = "page_title">删除续期配置<?php echo isset($removeResult) ? (!$removeResult ? "<span class = \"tip-red\">失败</span>" : "") : ""; ?></div>
		<div class = "bottom_title">
			<a class = "usual_button" href = "admin.php">基本</a>
			<a class = "usual_button" href = "user.php">用户</a>
			<a class = "usual_button" href = "role.php">角色</a>
			<a class = "usual_button" href = "renewal.php">续期</a>
			<a class = "usual_button" href = "update.php">更新</a>
		</div>
		<form action = "delrenewal.php" method = "post">
			<input type = "hidden" name = "renewal_id" value = "<?php echo $renewal_id; ?>" />
			<input type = "hidden" name = "delete" value = "true" />
			<table border = "0" cellpadding = "0" cellspacing = "0">
				<tr class = "odd_tr">
					<td class = "td_left">配置名</td>
					<td class = "td_right"><?php echo $renewal['name'];?></td>
				</tr>
				<tr>
					<td class = "td_left">排序</td>
					<td class = "td_right"><?php echo $renewal['sort'];?></td>
				</tr>
				<tr class = "odd_tr">
					<td class = "td_left">续期天数</td>
					<td class = "td_right"><?php echo $renewal['day_time'];?></td>
				</tr>
				<tr>
					<td class = "td_left">购买金币</td>
					<td class = "td_right"><?php echo $renewal['gold'];?></td>
				</tr>
				<tr class = "odd_tr">
					<td colspan="2"><input type = "submit" class="white_button" value = "确认删除" /></td>
				</tr>
			</table>
		</form>
	</div>
</div>