<div id = "main">
	<div class = "container">
		<div class = "page_title">机器人列表</div>
		<div class = "bottom_title">
			<a class = "usual_button" href = "addqq.php">添加</a>
			<a class = "usual_button" href = "http://zc.qq.com" target = "_blank">注册扣扣</a>
			<a class = "usual_button" href = "http://aq.qq.com" target = "_blank">安全中心</a>
			<a class = "usual_button" href = "http://aq.qq.com/cn2/login_limit/login_limit_index" target = "_blank">解封账号</a>
		</div>
		<table border = "0" cellpadding = "0" cellspacing = "0">
			<tr class = "odd_tr">
				<th><input type = "checkbox" id = "checkedAll" onclick = "checkedAll(this)" /></th>
				<th>扣扣账号</th>
				<th>状态</th>
				<th>操作</th>
			</tr>
			<?php if ($robotArray) { ?>
			<?php $index = 0; ?>
			<?php foreach ($robotArray as $robot) { $index ++; ?>
			<tr <?php echo $index % 2 == 0 ? "class=\"odd_tr\"":"";?>>
				<td><input type = "checkbox" name = "checked_id[]" value = "<?php echo $robot['id'];?>" onclick = "checkedOff(this)" /></td>
				<td><?php echo $robot['uin'];?></td>
				<td><?php echo $robot['is_run'] == 0 ? "未运行" : ($robot['status'] != StatusUtil::ONLINE ? "未上线" : "运行中");?></td>
				<td><a class = "setbtn" href = "delqq.php?uin=<?php echo $robot['uin'];?>">删除</a><a class = "setbtn" href = "setqq.php?uin=<?php echo $robot['uin'];?>">设置</a><a class = "setbtn" href = "manqq.php?uin=<?php echo $robot['uin'];?>">管理</a><a class = "setbtn" href = "runqq.php?uin=<?php echo $robot['uin'];?>"><?php echo $robot['is_run'] == 0 ? "记录" : ($robot['status'] != StatusUtil::ONLINE ? "登录" : "记录");?></a></td>
			</tr>
			<?php } ?>
			<tr <?php echo $index % 2 == 0 ? "":"class=\"odd_tr\"";?>>
				<td colspan="4">
					<input type = "button" class = "white_button" value = "批删除" onclick = "robotSetting('remove')" />
					<input type = "button" class = "white_button" value = "批设置" onclick = "robotSetting('update')" />
					<input type = "button" class = "white_button" value = "批管理" onclick = "robotSetting('manager')" />
				</td>
			</tr>
			<?php } else { ?>
			<tr><td colspan="4">暂无账号，请添加！</td></tr>
			<?php } ?>
		</table>
	</div>
</div>

<form id = "robotSettingForm" action = "setqqall.php" method="post">
	<input type = "hidden" id = "type" name = "type" />
	<input type = "hidden" id = "id_array" name = "id_array" />
</form>