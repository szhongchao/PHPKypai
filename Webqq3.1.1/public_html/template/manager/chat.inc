<div id = "main">
	<div class = "container">
		<div class = "page_title">聊天室 <a href = "#hi"><i class = "fa fa-arrow-circle-o-down fa-fw"></i></a></div>
		<ul class = "ul_list">
			<?php if ($contentArray) { ?>
			<?php $index = 0; ?>
			<?php foreach ($contentArray as $content) { $index++; ?>
			<li <?php echo ($index % 2) != 0 ? "class = \"odd_li\"" : ""; ?>>
				<div class = "context"><?php echo preg_replace("/\r\n/Uis", "<br/>", htmlspecialchars($content['content']));?></div>
				<label class = "name" onclick="addcontent(this)"><?php echo htmlspecialchars($content['username']);?></label>
				<label class = "date"><i class = "fa fa-clock-o fa-fw"></i> <?php echo date('Y-m-d H:i:s', $content['createdate']);?></label>
			</li>
			<?php } ?>
			<?php } else { ?>
			<li>没有聊天记录!</li>
			<?php } ?>
		</ul>
		<div class = "paging">
			<a href = "chat.php?pageno=1"><i class = "fa fa-arrow-left fa-fw"></i></a>
			<a href = "chat.php?pageno=<?php echo $pageno - 1;?>"><i class = "fa fa-angle-double-left fa-fw"></i></a>
			<a href = "chat.php?pageno=<?php echo $pageno + 1;?>"><i class = "fa fa-angle-double-right fa-fw"></i></a>
			<a href = "chat.php?pageno=<?php echo $total;?>"><i class = "fa fa-arrow-right fa-fw"></i></a>
			<input type = "text" id = "pageno" value = "<?php echo $pageno; ?>" />
			<input type = "button" value = "跳页" onclick = "gopage()" />
		</div>
		<div class = "speech">
			<a id = "hi" name = "hi"></a>
			<h4><i class = "fa fa-paint-brush fa-fw"></i> 大家一起嗨</h4>
			<form action="chat.php" method = "post">
				<textarea id = "chatcontent" name = "content"></textarea>
				<input class = "ssubmit" type = "submit" value = "我要嗨" />
			</form>
		</div>
	</div>
</div>
<script type = "text/javascript">
function gopage() {
	var pageno = document.getElementById("pageno").value;
	window.location.href = "chat.php?pageno=" + pageno; 
}
function addcontent(lab) {
	document.getElementById("chatcontent").value += ("@" + lab.innerHTML + "，");
}
</script>