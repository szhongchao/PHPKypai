<div id = "main">
	<div class = "container">
		<div class = "page_title">角色管理</div>
		<div class = "bottom_title">
			<a class = "usual_button" href = "admin.php">基本</a>
			<a class = "usual_button" href = "user.php">用户</a>
			<a class = "usual_button" href = "role.php">角色</a>
			<a class = "usual_button" href = "renewal.php">续期</a>
			<a class = "usual_button" href = "update.php">更新</a>
		</div>
		<table border = "0" cellpadding = "0" cellspacing = "0">
			<tr class="odd_tr">
				<th>角色名</th>
				<th>机器人配额</th>
				<th>初始金币</th>
				<th>操作</th>
			</tr>
			<?php if ($roleArray) { ?>
			<?php $index = 0;?>
			<?php foreach ($roleArray as $role) { $index++ ?>
			<tr <?php echo $index % 2 == 0 ? "class=\"odd_tr\"":"";?>>
				<td><?php echo $role['name'];?></td>
				<td><?php echo $role['add_robot_max_number'];?></td>
				<td><?php echo $role['init_gold'];?></td>
				<td>
					<a href = "updrole.php?role_id=<?php echo $role['id'];?>">编辑</a>
					<a href = "delrole.php?role_id=<?php echo $role['id'];?>">删除</a>
				</td>
			</tr>
			<?php } ?>
			<?php } ?>
		</table>
		<div class = "center_title">
			<a class = "usual_button" href = "addrole.php">添加一个新角色</a>
		</div>
	</div>
</div>