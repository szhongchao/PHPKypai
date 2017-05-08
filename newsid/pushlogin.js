var interval1,interval2;
function trim(str){ //去掉头尾空格
	return str.replace(/(^\s*)|(\s*$)/g, "");
}
function getqrpic(uin){
	var getvcurl='pushlogin.php?do=getqrpic&uin='+uin+'&r='+Math.random(1);
	$.get(getvcurl, function(d) {
		if(d.saveOK ==0){
			$('#load').html('请在手机QQ上确认登录&nbsp;<span id="loginload" style="padding-left: 10px;color: #790909;">.</span>');
			$('#load').show();
			$('#submit').html('重新发送验证');
			$('#uin').attr('qrsig',d.qrsig);
			interval1=setInterval(loginload,1000);
			interval2=setInterval(loadScript,3000);
		}else{
			$('#load').hide();
			alert(d.msg);
		}
	});
}
function ptuiCB(code,uin,sid,skey,pskey,superkey,nick){
	var msg='';
	switch(code){
		case '0':
			msg='<div class="alert alert-success">登录成功！'+decodeURIComponent(nick)+'</div><div class="input-group"><span class="input-group-addon">QQ帐号</span><input id="uin" value="'+uin+'" class="form-control" /></div><br/><div class="input-group"><span class="input-group-addon">SKEY</span><input id="skey" value="'+skey+'" class="form-control"/></div><br/><div class="input-group"><span class="input-group-addon">P_skey</span><input id="pskey" value="'+pskey+'" class="form-control"/></div><br/><div class="input-group"><span class="input-group-addon">superkey</span><input id="superkey" value="'+superkey+'" class="form-control"/></div><br/><a href="./index3.html">返回重新获取</a>';
			$('#login').hide();
			$('#submit').hide();
			cleartime();
			break;
		case '1':
			msg='请重新点击发送验证';
			break;
		case '2':
			msg='请在手机QQ上确认登录&nbsp;<span id="loginload" style="padding-left: 10px;color: #790909;">.</span>';
			break;
		case '3':
			msg='发送验证成功，请在手机上确认授权登录';
			break;
		default:
			msg=sid;
			break;
	}
	$('#load').html(msg);
}
function loadScript(c) {
	if ($('#login').attr("data-lock") === "true") return;
	var qrsig=$('#uin').attr('qrsig');
	c = c || "pushlogin.php?do=qqlogin&qrsig="+decodeURIComponent(qrsig)+"&r=" + Math.random(1);
	var a = document.createElement("script");
	a.onload = a.onreadystatechange = function() {
		if (!this.readyState || this.readyState === "loaded" || this.readyState === "complete") {
			if (typeof d == "function") {
				d()
			}
			a.onload = a.onreadystatechange = null;
			if (a.parentNode) {
				a.parentNode.removeChild(a)
			}
		}
	};
	a.src = c;
	document.getElementsByTagName("head")[0].appendChild(a)
}
function loginload(){
	if ($('#login').attr("data-lock") === "true") return;
	var load=document.getElementById('loginload').innerHTML;
	var len=load.length;
	if(len>2){
		load='.';
	}else{
		load+='.';
	}
	document.getElementById('loginload').innerHTML=load;
}
function cleartime(){
	clearInterval(interval1);
	clearInterval(interval2);
}
$(document).ready(function(){
	$('#submit').click(function(){
		cleartime();
		var self=$(this);
		var uin=trim($('#uin').val());
		if(uin=='') {
			alert("请确保每项不能为空！");
			return false;
		}
		getqrpic(uin);
	});
});