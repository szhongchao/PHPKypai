<?php
header('Content-type:text/json;charset=utf-8');
require_once '../include.php';
$qqnum='35';
/*$qqnum=$_POST['qqnum'];404984111
 $p_skey=$_POST['p_skey'];  */

$sqls= "select  * from zp_userinfo where qqnum like '%".$qqnum."%'";
$result= mysqli_query($tp,$sqls);
while($row = mysqli_fetch_assoc($result)){
	$array[] = $row;
}
//print_r($array);
echo json_encode($array);