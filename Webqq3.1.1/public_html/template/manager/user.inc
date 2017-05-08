<div id = "main">
	<div class = "container">
		<div class = "page_title">用户管理</div>
		<div class = "bottom_title">
			<a class = "usual_button" href = "admin.php">基本</a>
			<a class = "usual_button" href = "user.php">用户</a>
			<a class = "usual_button" href = "role.php">角色</a>
			<a class = "usual_button" href = "renewal.php">续期</a>
			<a class = "usual_button" href = "update.php">更新</a>
		</div>
		<div class = "paging">
			<a href = "user.php?pageno=1"><i class = "fa fa-arrow-left fa-fw"></i></a>
			<a href = "user.php?pageno=<?php echo $pageno - 1;?>"><i class = "fa fa-angle-double-left fa-fw"></i></a>
			<a href = "user.php?pageno=<?php echo $pageno + 1;?>"><i class = "fa fa-angle-double-right fa-fw"></i></a>
			<a href = "user.php?pageno=<?php echo $total;?>"><i class = "fa fa-arrow-right fa-fw"></i></a>
			<input type = "text" id = "pageno_up" value = "<?php echo $pageno; ?>" />
			<input type = "button" value = "跳页" onclick = "gopage('pageno_up')" />
		</div>
		<table border = "0" cellpadding = "0" cellspacing = "0">
			<tr class="odd_tr">
				<th>ID</th>
				<th>用户名</th>
				<th>操作</th>
			</tr>
			<?php if ($userArray) { ?>
			<?php $index = 0;?>
			<?php foreach ($userArray as $user) { $index++ ?>
			<tr <?php echo $index % 2 == 0 ? "class=\"odd_tr\"":"";?>>
				<td><?php echo $user['id'];?></td>
				<td><?php echo htmlspecialchars($user['username']);?></td>
				<td>
					<a href = "upduser.php?user_id=<?php echo $user['id'];?>">编辑</a>
					<a href = "deluser.php?user_id=<?php echo $user['id'];?>">删除</a>
				</td>
			</tr>
			<?php } ?>
			<?php } ?>
		</table>
		<div class = "paging">
			<a href = "user.php?pageno=1"><i class = "fa fa-arrow-left fa-fw"></i></a>
			<a href = "user.php?pageno=<?php echo $pageno - 1;?>"><i class = "fa fa-angle-double-left fa-fw"></i></a>
			<a href = "user.php?pageno=<?php echo $pageno + 1;?>"><i class = "fa fa-angle-double-right fa-fw"></i></a>
			<a href = "user.php?pageno=<?php echo $total;?>"><i class = "fa fa-arrow-right fa-fw"></i></a>
			<input type = "text" id = "pageno_down" value = "<?php echo $pageno; ?>" />
			<input type = "button" value = "跳页" onclick = "gopage('pageno_down')" />
		</div>
	</div>
</div>

<script type = "text/javascript">
function gopage(id) {
	var pageno = document.getElementById(id).value;
	window.location.href = "user.php?pageno=" + pageno; 
}
</script>