var xiha={
	postData: function(url, parameter, callback, dataType, ajaxType) {
		if(!dataType) dataType='json';
		$.ajax({
			type: "POST",
			url: url,
			async: true,
			dataType: dataType,
			json: "callback",
			data: parameter,
			success: function(data) {
				if (callback == null) {
					return;
				} 
				callback(data);
			},
			error: function(error) {
				alert('创建连接失败');
			}
		});
	}
}
function trim(str){ //去掉头尾空格
	return str.replace(/(^\s*)|(\s*$)/g, "");
}

function login(uin,pwd,vcode,pt_verifysession){
	$('#load').html('正在登录，请稍等...');
	var isMd5=$("input:radio[name='ismd5']:checked").val() || 0;
	alert(isMd5);
	p=getmd5(uin,pwd,vcode,isMd5);
	var loginurl="login.php?do=qqlogin";
	xiha.postData(loginurl,"uin="+uin+"&pwd="+pwd+"&p="+p+"&vcode="+vcode+"&pt_verifysession="+pt_verifysession+"&r="+Math.random(1), function(d) {
		if(d.saveOK ==0){
			$('#login').hide();
			$('.code').hide();
			$('#submit').hide();
			$('#load').html('<div class="alert alert-success">登录成功！'+decodeURIComponent(d.nick)+'</div><div class="input-group"><span class="input-group-addon">QQ帐号</span><input id="uin" value="'+d.uin+'" class="form-control" /></div><br/><div class="input-group"><span class="input-group-addon">SID</span><input id="sid" value="'+d.sid+'" class="form-control"/></div><br/><div class="input-group"><span class="input-group-addon">SKEY</span><input id="skey" value="'+d.skey+'" class="form-control"/></div><br/><div class="input-group"><span class="input-group-addon">P_skey</span><input id="pskey" value="'+d.pskey+'" class="form-control"/></div><br/><div class="input-group"><span class="input-group-addon">superkey</span><input id="superkey" value="'+d.superkey+'" class="form-control"/></div><br/><a href="./">返回重新获取</a>');
		}else if(d.saveOK ==4){
			$('#load').html('验证码错误，请重新登录。');
			$('#submit').attr('do','submit');
			$('.code').hide();
			$('#code').val("");
		}else if(d.saveOK ==3){
			$('#load').html('您输入的帐号或密码不正确，请重新输入密码！');
			$('#submit').attr('do','submit');
			$('.code').hide();
			$('#login').show();
		}else if(d.msg =='pwd不能为空'){
			$('#load').html('请输入密码！');
			$('#submit').attr('do','submit');
			$('.code').hide();
			$('#login').show();
		}else{
			$('#load').html(d.msg);
			$('#submit').attr('do','submit');
		}
	});
	
}
function getvc(uin,sig,sess){
	$('#load').html('获取验证码，请稍等...');
	sess = sess||0;
	var getvcurl="login.php?do=getvc";
	xiha.postData(getvcurl,'uin='+uin+'&sig='+sig+'&sess='+sess+'&r='+Math.random(1), function(d) {
		if(d.saveOK ==0){
			$('#load').html('请输入验证码');
			$('#codeimg').attr('vc',d.vc);
			$('#codeimg').attr('sess',d.sess);
			if(typeof d.websig != "undefined"){
				$('#codeimg').attr('websig',d.websig);
			}
			$('#codeimg').html('<img onclick="getvc(\''+uin+'\',\''+d.vc+'\',\''+d.sess+'\')" src="login.php?do=getvcpic&uin='+uin+'&cap_cd='+sig+'&sig='+d.vc+'&sess='+d.sess+'&r='+Math.random(1)+'" title="点击刷新">');
			$('#submit').attr('do','code');
			$('#code').val('');
			$('.code').show();
		}else if(d.saveOK ==2){
			$('#codeimg').attr('vc',d.vc);
			$('#codeimg').attr('sess',d.sess);
			dovc(uin,d.ans,d.vc);
		}else{
			alert(d.msg);
		}
	});

}
function dovc(uin,code,vc){
	$('#load').html('验证验证码，请稍等...');
	var cap_cd=$('#uin').attr('cap_cd');
	var sess=$('#codeimg').attr('sess');
	var websig=$('#codeimg').attr('websig');
	var getvcurl="login.php?do=dovc";
	xiha.postData(getvcurl,'uin='+uin+'&ans='+code+'&sig='+vc+'&cap_cd='+cap_cd+'&sess='+sess+'&websig='+websig+'&r='+Math.random(1), function(d) {
		if(d.rcode == 0){
			var pwd=$('#pwd').val();
			login(uin,pwd,d.randstr.toUpperCase(),d.sig);
		}else if(d.rcode == 50){
			$('#load').html('验证码错误，重新生成验证码，请稍等...');
			getvc(uin,cap_cd,sess);
		}else if(d.rcode == 12){
			$('#load').html('验证失败，请重试。');
		}else{
			$('#load').html('验证失败，请重试。');
			getvc(uin,cap_cd,sess);
		}
	});

}
function checkvc(){
	var uin=trim($('#uin').val()),
		pwd=trim($('#pwd').val());
	if(uin==''||pwd=='') {
		$('#load').html('请输入密码！');
		$('#login').show();
		return false;
	}
	$('#load').html('登录中，请稍候...');
	var getvcurl="login.php?do=checkvc";
	xiha.postData(getvcurl,'uin='+uin+'&r='+Math.random(1), function(d) {
		if(d.saveOK ==0){
			login(uin,pwd,d.vcode,d.pt_verifysession);
		}else if(d.saveOK ==1){
			$('#uin').attr('cap_cd',d.sig);
			getvc(uin,d.sig,0);
		}else{
			alert(d.msg);
			$('#load').html('');
		}
	});
}
$(document).ready(function(){
	$('#submit').click(function(){
		var self=$(this);
		var uin=trim($('#uin').val()),
			pwd=trim($('#pwd').val());
		if(uin==''||pwd=='') {
			alert("请确保每项不能为空！");
			return false;
		}
		$('#load').show();
		if(self.attr('do') == 'code'){
			var vcode=trim($('#code').val()),
				vc=$('#codeimg').attr('vc');
			dovc(uin,vcode,vc);
		}else{
		if (self.attr("data-lock") === "true") return;
			else self.attr("data-lock", "true");
			checkvc();
			self.attr("data-lock", "false");
		}
	});
});