<?php
error_reporting(0);

require 'qqlogin.class.php';
$login=new qq_login();
if($_GET['do']=='checkvc'){
	$array=$login->checkvc($_POST['uin']);
}
if($_GET['do']=='dovc'){
	$array=$login->dovc($_POST['uin'],$_POST['sig'],$_POST['ans'],$_POST['cap_cd'],$_POST['sess'],$_POST['websig']);
}
if($_GET['do']=='getvc'){
	$array=$login->getvc($_POST['uin'],$_POST['sig'],$_POST['sess']);
}
if($_GET['do']=='qqlogin'){
	$array=$login->qqlogin($_POST['uin'],$_POST['pwd'],$_POST['p'],$_POST['vcode'],$_POST['pt_verifysession']);
}
if($_GET['do']=='getvcpic'){
	header('content-type:image/jpeg');
	echo $login->getvcpic($_GET['uin'],$_GET['sig'],$_GET['cap_cd'],$_GET['sess']);
	exit;
}
echo json_encode($array);