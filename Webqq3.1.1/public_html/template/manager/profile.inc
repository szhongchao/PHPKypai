<div id = "main">
	<div class = "container">
		<div class = "page_title">个人中心</div>
		<div class = "bottom_title">
			<a class = "usual_button" href = "profile.php">个人中心</a>
			<a class = "usual_button" href = "updpro.php">修改资料</a>
			<a class = "usual_button" href = "renqq.php">续期账号</a>
			<a class = "usual_button" href = "admin.php">后台</a>
		</div>
		<table border = "0" cellpadding = "0" cellspacing = "0">
			<tr class = "odd_tr">
				<td class = "td_left">用户名</td>
				<td class = "td_right"><?php echo htmlspecialchars($user['username']);?></td>
			</tr>
			<tr>
				<td class = "td_left">邮箱</td>
				<td class = "td_right"><?php echo htmlspecialchars($user['mail']);?></td>
			</tr>
			<tr class = "odd_tr">
				<td class = "td_left">手机号</td>
				<td class = "td_right"><?php echo htmlspecialchars(substr($user['phone'], 0, 5) . "******");?></td>
			</tr>
			<tr>
				<td class = "td_left">QQ号</td>
				<td class = "td_right"><?php echo htmlspecialchars($user['qq']);?></td>
			</tr>
			<tr class = "odd_tr">
				<td class = "td_left">金币</td>
				<td class = "td_right"><code><?php echo $user['gold'];?></code>个</td>
			</tr>
			<tr>
				<td class = "td_left">机器人配额</td>
				<td class = "td_right"><code><?php echo $user_role['add_robot_max_number'];?></code>个</td>
			</tr>
			<tr class = "odd_tr">
				<td class = "td_left">用户组</td>
				<td class = "td_right"><?php echo $role['name'];?></td>
			</tr>
			<tr>
				<td class = "td_left">邀请链接</td>
				<td class = "td_right"><?php echo "http://" . DOMAIN . "/reg.php?invite=" . $user['invitation']; ?></td>
			</tr>
			<tr class = "odd_tr">
				<td class = "td_left">注销</td>
				<td class = "td_right"><a href = "login.php?action=logout">退出登录</a></td>
			</tr>
		</table>
	</div>
</div>