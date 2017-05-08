<div id = "main">
	<div class = "container">
		<div class = "page_title">
			<?php if($do == "self") { ?>
			已安装插件
			<?php } elseif ($do == "all") { ?>
			插件大全
			<?php } elseif ($do == "folder") { ?>
			检测插件
			<?php } elseif ($do == "detail") { ?>
			插件详情
			<?php } elseif ($do == "install") { ?>
			插件安装结果
			<?php } elseif ($do == "delete") { ?>
			卸载结果
			<?php } elseif ($do == "zip") { ?>
			本地安装
			<?php } elseif ($do == "set") { ?>
			插件设置
			<?php if (isset($updateResult)) {
				echo $updateResult ? '<font color = "green">成功</font>' : '<font color = "red">失败</font>';
			} 
			?>
			<?php } ?>
		</div>
		<div class = "bottom_title">
			<a class = "usual_button" href = "plugin.php">插件</a>
			<a class = "usual_button" href = "plugin.php?do=all">插件大全</a>
			<a class = "usual_button" href = "plugin.php?do=zip">本地安装</a>
			<a class = "usual_button" href = "plugin.php?do=folder">检测插件</a>
		</div>
		<?php if ($do != "set") { ?>
		<table border = "0" cellpadding = "0" cellspacing = "0">
			<?php if ($do == "self") { ?>
				<tr class = "odd_tr">
					<th>插件名</th>
					<th>作者</th>
					<th>操作</th>
				</tr>
				<?php if ($pluginArray) { ?>
				<?php $index = 0; ?>
				<?php foreach ($pluginArray as $plugin) { $index ++; ?>
				<tr <?php echo $index % 2 == 0 ? "class=\"odd_tr\"":"";?>>
					<td><?php echo $plugin['plugin_name'];?></td>
					<td><?php echo $plugin['author'];?></td>
					<td><a href = "plugin.php?do=detail&id=<?php echo $plugin['id'];?>">详情</a><a href = "plugin.php?do=set&id=<?php echo $plugin['id'];?>">开关设置</a></td>
				</tr>
				<?php } ?>
				<?php } else { ?>
				<tr><td colspan="3"><code>暂无，去插件大全看看吧</code></td></tr>
				<?php } ?>
			<?php } elseif ($do == "all") { ?>
				<tr class = "odd_tr">
					<th>插件名</th>
					<th>作者</th>
					<th>操作</th>
				</tr>
				<?php if ($itpkPluginArray != null) { ?>
				<?php $index = 0; ?>
				<?php foreach ($itpkPluginArray as $plugin) { $index ++; ?>
				<tr <?php echo $index % 2 == 0 ? "class=\"odd_tr\"":"";?>>
					<td><?php echo $plugin['plugin_name'];?></td>
					<td><?php echo $plugin['author'];?></td>
					<td>
						<a href = "plugin.php?do=detail&name=<?php echo $plugin['class_name'];?>">详情</a>
						<?php if (DataUtil::is_equal($classNames, $plugin['class_name'], true)) { ?>
						<a class = "tip-red" href = "javascript:void(0)">已经安装</a>
						<?php } else { ?>
						<a href = "plugin.php?do=install&name=<?php echo $plugin['class_name'];?>">立即安装</a>
						<?php } ?>
					</td>
				</tr>
				<?php } ?>
				<tr>
					<td colspan = "3">
						<div class = "paging center_auto">
							<a href = "plugin.php?do=all&pageno=1"><i class = "fa fa-arrow-left fa-fw"></i></a>
							<a href = "plugin.php?do=all&pageno=<?php echo $pageno - 1;?>"><i class = "fa fa-angle-double-left fa-fw"></i></a>
							<a href = "plugin.php?do=all&pageno=<?php echo $pageno + 1;?>"><i class = "fa fa-angle-double-right fa-fw"></i></a>
							<a href = "plugin.php?do=all&pageno=<?php echo $pluginJson['count'];?>"><i class = "fa fa-arrow-right fa-fw"></i></a>
							<input type = "text" id = "pageno_down" value = "<?php echo $pluginJson['pageno']; ?>" />
							<input type = "button" value = "跳页" onclick = "gopage('pageno_down')" />
						</div>
					</td>
				</tr>
				<?php } else { ?>
				<tr><td colspan="3"><code>没有可用插件</code></td></tr>
				<?php } ?>
			<?php } elseif ($do == "detail") { ?>
				<tr class = "odd_tr">
					<td class = "td_left">插件名</td>
					<td colspan="2" class = "td_right"><?php echo $itpkPlugin['plugin_name']; ?></td>
				</tr>
				<tr>
					<td class = "td_left">插件类名</td>
					<td colspan="2" class = "td_right"><?php echo $itpkPlugin['class_name']; ?></td>
				</tr>
				<tr class = "odd_tr">
					<td class = "td_left">作者</td>
					<td colspan="2" class = "td_right"><a href = "<?php echo $itpkPlugin['author_url']; ?>" target = "_blank"><?php echo $itpkPlugin['author']; ?></a></td>
				</tr>
				<tr>
					<td class = "td_left">版本</td>
					<td colspan="2" class = "td_right"><?php echo $itpkPlugin['version']; ?></td>
				</tr>
				<tr class = "odd_tr">
					<td class = "td_left">监控所有消息</td>
					<td colspan="2" class = "td_right"><?php echo $itpkPlugin['is_monitor_all_msg'] == 1 ? "是" : "否"; ?></td>
				</tr>
				<tr>
					<td class = "td_left">拥有指令</td>
					<td colspan="2" class = "td_right"><?php echo $itpkPlugin['instruction']; ?></td>
				</tr>
				<tr class = "odd_tr">
					<td class = "td_left">插件说明</td>
					<td colspan="2" class = "td_right"><?php echo $itpkPlugin['description']; ?></td>
				</tr>
				<?php if (isset($name)) { ?>
				<tr>
					<td class = "td_left">下载地址</td>
					<td colspan="2" class = "td_right"><a href = "<?php echo $itpkPlugin['download_url']; ?>" target = "_blank">点击下载</a></td>
				</tr>
				<tr class = "odd_tr">
					<td class = "td_left">发布时间</td>
					<td colspan="2" class = "td_right"><?php echo date('Y-m-d H:i:s', $itpkPlugin['createdate']); ?></td>
				</tr>
				
				<tr><td colspan="3">
					<form action = "plugin.php?do=install&name=<?php echo $itpkPlugin['class_name'];?>" method = "post">
						<?php if (DataUtil::is_equal($classNames, $itpkPlugin['class_name'], true)) { ?>
						<input type = "button" class = "disable_button" value = "已经安装" disabled="disabled" />
						<?php } else { ?>
						<input type = "submit" class = "white_button" value = "在线安装" />
						<?php } ?>
					</form>
				</td></tr>
				<?php } elseif (isset($id)) { ?>
				<tr><td colspan="3"><form action = "plugin.php?do=delete&id=<?php echo $itpkPlugin['id'];?>" method = "post"><input type = "submit" class = "white_button" value = "卸载插件" /></form></td></tr>
				<?php } ?>
			<?php } elseif ($do == "install") { ?>
				<tr><td colspan="3"><code><?php echo $installResult; ?></code></td></tr>
			<?php } elseif ($do == "delete") { ?>
				<tr><td colspan="3"><code><?php echo $folderDelResult ? "插件文件删除完毕" : "插件文件删除失败"; ?></code></td></tr>
				<tr><td colspan="3"><code><?php echo $dbDelResult ? "安装记录删除完毕" : "安装记录删除失败"; ?></code></td></tr>
			<?php } elseif ($do == "zip") { ?>	
				<?php if ($m == "list") { ?>
					<tr class = "odd_tr">
						<th>未安装压缩包</th>
						<th colspan="2">操作</th>
					</tr>
					<?php if (count($files) > 0) { ?>
					<?php for ($i = 0; $i < count($files); $i++) { ?>
					<tr <?php echo $i % 2 != 0 ? "class=\"odd_tr\"":"";?>>
						<td><span class = "tip-green"><i class = "fa fa-file-zip-o fa-fw"></i><?php echo $files[$i]; ?></span></td>
						<td colspan="2"><a href = "plugin.php?do=zip&m=delete&name=<?php echo $files[$i]; ?>">删除</a><a href = "plugin.php?do=zip&m=install&name=<?php echo $files[$i]; ?>">安装</a></td>
					</tr>
					<?php } ?>
					<?php } else { ?>
					<tr><td colspan="3"><code>没有符合条件的未安装压缩包</code></td></tr>
					<?php } ?>
				<?php } elseif ($m == "delete") { ?>
				<tr><td colspan="3"><code><?php echo $deleteResult ? "删除成功" : "删除失败,没有写入权限"; ?></code></td></tr>
				<?php } elseif ($m == "install") { ?>
				<tr><td colspan="3"><code><?php echo $installResult; ?></code></td></tr>
				<?php } ?>
			<?php } elseif ($do == "folder") { ?>	
				<?php if ($m == "list") { ?>
					<tr class = "odd_tr">
						<th>未安装文件夹</th>
						<th colspan="2">操作</th>
					</tr>
					<?php if (count($files) > 0) { ?>
					<?php for ($i = 0; $i < count($files); $i++) { ?>
					<tr <?php echo $i % 2 != 0 ? "class=\"odd_tr\"":"";?>>
						<td><span class = "tip-green"><i class = "fa fa-folder-open-o fa-fw"></i><?php echo $files[$i]; ?></span></td>
						<td colspan="2"><a href = "plugin.php?do=folder&m=delete&name=<?php echo $files[$i]; ?>">删除</a><a href = "plugin.php?do=folder&m=install&name=<?php echo $files[$i]; ?>">安装</a></td>
					</tr>
					<?php } ?>
					<?php } else { ?>
					<tr><td colspan="3"><code>没有符合条件的未安装文件夹</code></td></tr>
					<?php } ?>
				<?php } elseif ($m == "delete") { ?>
				<tr><td colspan="3"><code><?php echo $deleteResult ? "删除成功" : "删除失败,没有写入权限"; ?></code></td></tr>
				<?php } elseif ($m == "install") { ?>
				<tr><td colspan="3"><code><?php echo $installResult; ?></code></td></tr>
				<?php } ?>
			<?php } ?>
		</table>
		<?php } ?>
		<?php if ($do == "set") { ?>
		<form action = "plugin.php" method = "post">
		<input type = "hidden" name = "do" value = "set" />
		<input type = "hidden" name = "m" value = "save" />
		<input type = "hidden" name = "id" value = "<?php echo $plugin['id']; ?>" />
		<div class = "form_title"><span class = "tip-green">如无必要，请勿修改指令，否则可能导致插件不可用</span></div>
		<ul class = "form_ul">
			<li><label class = "form_label" for = "plugin_name" title = "插件名"><i class = "fa fa-th-large fa-fw"></i></label><input class = "form_input" type = "text" id = "plugin_name" name = "plugin_name" placeholder = "插件名" value = "<?php echo $plugin['plugin_name']; ?>" readonly = "readonly" /></li>
			<li><label class = "form_label" for = "instruction" title = "指令设置"><i class = "fa fa-cogs fa-fw"></i></label><input class = "form_input" type = "text" id = "instruction" name = "instruction" placeholder = "指令" value = "<?php echo $plugin['instruction']; ?>" /></li>
			<li><label class = "form_label" for = "is_able_true" title = "启用插件"><i class = "fa fa-toggle-on fa-fw"></i></label><span class = "form_i_title">是否启用</span><input class = "form_radio" type = "radio" id = "is_able_true" name = "is_able" value = "true" <?php echo $plugin['is_able'] == 1 ? "checked=\"checked\"" : "";?> />是<input class = "form_radio" type = "radio" id = "is_able_false" name = "is_able" value = "false" <?php echo $plugin['is_able'] == 0 ? "checked=\"checked\"" : "";?> />否<label class = "form_label form_label_right" for = "is_able_false" title = "关闭插件"><i class = "fa fa-toggle-off fa-fw"></i></label></li>
			<li><input class = "form_button" type = "submit" value = "确认修改" /></li>
		</ul>
		</form>
		<?php } ?>
	</div>
</div>

<script type = "text/javascript">
function gopage(id) {
	var pageno = document.getElementById(id).value;
	window.location.href = "plugin.php?do=all&pageno=" + pageno; 
}
</script>