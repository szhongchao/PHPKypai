<?php 

require_once '../include.php';
$uid=$_GET['uid']; //获得用户名
//$pwd=$_GET['pwd']; //获得密码

$fileid=$_GET['pid']; //获得文件编号
$code=$_GET['code'];// 获得机器码

// 然后你判断用户名和密码是否正确，判断用户有无换电脑，判断用户有无权限播放等等业务处理代码
//从数据库选出机器码

$sqls= 'select  * from zp_userinfo where uid='.$uid;
$res= mysqli_query($tp,$sqls);
$rs = mysqli_fetch_array($res);

$pass = true;
if(!$rs){
	$pass = false;
	echo "Error:激活码错误 ";
}
if($fileid<>''and $pass){
	$fileidstr=$rs['fileidstr'];
	 preg_match_all('/([a-z]+)([0-9]+)/i', $fileid, $fileidAarr);
	 preg_match_all('/([a-z]+)([0-9]+)/i', $fileidstr, $fileidstrAarr);
	 $fileidKey = $fileidAarr[1][0];
	 for($i=0;$i<count($fileidstrAarr[1]);$i++){
	 	if($fileidKey== $fileidstrAarr[1][$i]){
	 		if($fileidstrAarr[2][$i] != '0' and preg_match(strtoupper("/".$fileidAarr[2][0]."/"),strtoupper($fileidstrAarr[2][$i]))=='0'){
	 			$pass = false;
	 			echo "Error:该帐号无权播放此文件！";
			}
			break;
		}
	}
	if($i ==count($fileidstrAarr[1])){
		$pass = false;
		echo "Error:该帐号无权播放此文件！";
	}
}
if($code<>"" and $pass){ //验证播放次数
	$pcstr = $rs['pcstr'];
	if(is_null($pcstr)){
		$sql = "update zp_userinfo set pcstr='".$code ."' where  uid='".$uid."'"; //第一次验证
		mysqli_query($tp,$sql);
		$pass = true;
	}elseif(preg_match(strtoupper("/".$code."/"),strtoupper($pcstr))>0){
		$pass = true; //第二次验证
	}elseif(sizeof(explode(',' ,strtoupper($rs['pcstr']))) < $rs['pcnum']){
		$sql = "update zp_userinfo set pcstr='".$pcstr.",".$code."' where  uid='".$uid."'"; //新增设备
		mysqli_query($tp,$sql);
		$pass = true;
	}else {
		$pass = false;
		echo "Error:已经超过授权的机器数！";
	}
}
if($pass){
	$ip = $_SERVER["REMOTE_ADDR"];
	$sqls = "insert into zp_log (uid,qqnum,fileid,ip) values ('{$uid}','{$rs['qqnum']}','{$fileid}','{$ip}')";
	mysqli_query($tp,$sqls);
	echo 'AAAAAA775BA57F13190638175B156A6619034D774B100F36097618|0|||'.md5($code);
}

// 处理完毕后，输出以下播放命令， md5(code) 表示计算机器码的md5标准值，32位，小写
//echo AAAAAA322B055A0349535D526B250A2619734D170E755FD61C034D|次数|有效期||md5(code)

?>