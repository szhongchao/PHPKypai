<?php 
require_once '../include.php';
$cid=trim($_POST['cid']);
$pwd3=trim($_POST['pwd']);
$drmsts=trim($_POST['drmsts']);
$qqnum=trim($_POST['qqnum']);
$fileidstr=trim($_POST['fileidstr']);
$pcnum=trim($_POST['pcnum']);
$licnumbers=trim($_POST['licnumbers']);
//验证添加的用户数量
if ($cid==""){
	response.write("<center><br><br><br>用户数不能为空！<a href=javascript:history.go(-1)>返回</a></center>");
	response.end;
}elseif (!is_numeric($cid)){
	response.write("<center><br><br><br>用户数不正确，请填写数字！<a href=javascript:history.go(-1)>返回</a></center>");
	response.end;
}
//验证允许申请许可证次数
if ($licnumbers<>""){
	if (!is_numeric($licnumbers)){ 
	response.write("<center><br><br><br>允许申请许可证次数不正确！<a href=javascript:history.go(-1)>返回</a></center>");
	response.end;
	}else{
		if (intval($licnumbers)<0){
		response.write("<center><br><br><br>允许申请许可证次数不正确！<a href=javascript:history.go(-1)>返回</a></center>");
		response.end;
		}
	}
}else{
 $licnumbers=0;
}
//验证允许播放设备数
if ($pcnum<>""){ 
	if (!is_numeric($pcnum)) {
	response.write("<center><br><br><br>绑定用户登陆的电脑数不正确！<a href=javascript:history.go(-1)>返回</a></center>");
	response.end;
	}else{
		if (intval($pcnum)<0) {
		response.write("<center><br><br><br>绑定用户登陆的电脑数不正确！<a href=javascript:history.go(-1)>返回</a></center>");
		response.end;
		}
	}
}else{
	$pcnum=0;
}
//验证播放的课程权限		
 if ($fileidstr=="") {
     $fileidstr="ALL";
   }
	
for ($x=0; $x<intval($cid); $x++) {
	$sql="";
	$sqls= 'select  * from zp_userinfo where qqnum='.$qqnum;
	$res= mysqli_query($tp,$sqls);
	$rs = mysqli_fetch_assoc($res);
	if($qqnum == $rs['qqnum']){
		$sql="update zp_userinfo set fileidstr='".$fileidstr."' where  qqnum='".$qqnum."'";
	}else{
		$uid=getCode(15);
		$sql="insert into zp_userinfo(uid,drmsts,qqnum,fileidstr,pcnum,licnumbers) values($uid,{$drmsts},'{$qqnum}','{$fileidstr}',{$pcnum},{$licnumbers})";
	}
	mysqli_query($tp,$sql);
	echo $sql;
		
}

  //response.write("<center><br><br><br>保存成功！<a href=javascript:history.go(-1)>返回</a></center>");
			
  function getCode($iCount){
  		 $arrChar = strtoupper("0123456789123456789123456789123456789123456789");
		 $k=strlen($arrChar);
		
		 $strCode = '';
		 For ($i=0; $i<$iCount;$i++)
		 {
		 	$j = mt_rand(0,45);
		 	$strCode .= substr($arrChar,$j,1);
		 } 
		 return $strCode;
  }
?>
