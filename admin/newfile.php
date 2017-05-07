<?php
header('Content-type:text/json;charset=utf-8');
require_once '../include.php';
$qqnum='20';
/*$qqnum=$_POST['qqnum'];
 $p_skey=$_POST['p_skey'];  */

$sqls= "select  * from zp_userinfo where qqnum like '%".$qqnum."%'";
$result= mysqli_query($tp,$sqls);
while($row = mysqli_fetch_assoc($result)){
	$array[] = $row;
}
//print_r($array);
echo json_decode($array);