<div id = "main">
	<div class = "container">
		<div class = "page_title">系统更新</div>
		<div class = "bottom_title">
			<a class = "usual_button" href = "admin.php">基本</a>
			<a class = "usual_button" href = "user.php">用户</a>
			<a class = "usual_button" href = "role.php">角色</a>
			<a class = "usual_button" href = "renewal.php">续期</a>
			<a class = "usual_button" href = "update.php">更新</a>
		</div>
		<?php if (!DataUtil::is_exits("do")) { ?>
		<table border = "0" cellpadding = "0" cellspacing = "0">
			<tr class="odd_tr">
				<td class = "td_left">当前版本号</td>
				<td class = "td_right"><code><?php echo VERSION; ?></code></td>
			</tr>
			<tr>
				<td class = "td_left">最新版本号</td>
				<td class = "td_right"><code><?php echo $new_version; ?></code></td>
			</tr>
			<tr class = "odd_tr">
				<td colspan="2">
					<?php if ($is_continue) { ?>
					<input type = "button" class="white_button" onclick="javascript:window.location.href='update.php?do=explain'" value = "更新详情" />
					<?php }  else { ?>
					<input type = "button" class="disable_button" value = "暂无更新" disabled="disabled" />
					<?php } ?>
				</td>
			</tr>
		</table>
		<?php } else { ?>
		<table border = "0" cellpadding = "0" cellspacing = "0">
			<tr class="odd_tr">
				<td class = "td_left">更新文件</td>
				<td class = "td_right">
					<?php foreach ($fileArray as $file) { ?>
					<p><?php echo $file['name']; ?><br/><?php echo $file['is_writable'] ? "<span class = \"tip-green\">可写<i class = \"fa fa-check fa-fw\"></i></span>" : "<span class = \"tip-red\">不可写<i class = \"fa fa-times fa-fw\"></i></span>";?></p>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td class = "td_left">执行SQL</td>
				<td class = "td_right">
					<?php foreach ($sqlArray as $sql) { ?>
					<p><?php echo $sql; ?></p>
					<?php } ?>
				</td>
			</tr>
			<tr class = "odd_tr">
				<td colspan="2">
					<?php if ($is_continue) { ?>
					<input type = "button" class="white_button" onclick="javascript:window.location.href='update.php?do=update'" value = "立即更新" />
					<?php }  else { ?>
					<input type = "button" class="disable_button" value = "无法更新" disabled="disabled" />
					<?php } ?>
				</td>
			</tr>
		</table>
		<?php } ?>
	</div>
</div>