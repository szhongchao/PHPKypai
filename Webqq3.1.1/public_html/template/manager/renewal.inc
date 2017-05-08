<div id = "main">
	<div class = "container">
		<div class = "page_title">续期配置</div>
		<div class = "bottom_title">
			<a class = "usual_button" href = "admin.php">基本</a>
			<a class = "usual_button" href = "user.php">用户</a>
			<a class = "usual_button" href = "role.php">角色</a>
			<a class = "usual_button" href = "renewal.php">续期</a>
			<a class = "usual_button" href = "update.php">更新</a>
		</div>
		<table border = "0" cellpadding = "0" cellspacing = "0">
			<tr class="odd_tr">
				<th>配置名</th>
				<th>天数</th>
				<th>金币</th>
				<th>操作</th>
			</tr>
			<?php if ($renewalArray) { ?>
			<?php $index = 0;?>
			<?php foreach ($renewalArray as $renewal) { $index++ ?>
			<tr <?php echo $index % 2 == 0 ? "class=\"odd_tr\"":"";?>>
				<td><?php echo $renewal['name'];?></td>
				<td><?php echo $renewal['day_time'];?></td>
				<td><?php echo $renewal['gold'];?></td>
				<td>
					<a href = "updrenewal.php?renewal_id=<?php echo $renewal['id'];?>">编辑</a>
					<a href = "delrenewal.php?renewal_id=<?php echo $renewal['id'];?>">删除</a>
				</td>
			</tr>
			<?php } ?>
			<?php } else { ?>
			<tr>
				<td colspan="4">暂无续期配置</td>
			</tr>
			<?php } ?>
		</table>
		<div class = "center_title">
			<a class = "usual_button" href = "addrenewal.php">添加续期配置</a>
		</div>
	</div>
</div>