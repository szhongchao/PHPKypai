<?php
header('Content-type:text/json;charset=utf-8');
require_once '../include.php';
checkAdminLogined();
$qqnum=$_POST['qqnum'];
$p_skey=$_POST['p_skey']; 
$gpname = $_POST['gpname']; 
/* $qqnum="211342495";
$p_skey="80vc3fuXbItjJIVxKQS3viENiInivhI0feXWa9JHFcc_";  */
$data =shell_exec("python qqzone.py ".$qqnum." ".$p_skey);
$data= substr($data, 10);
$data= substr($data,0,strlen($data)-3);
$dataJson=json_decode($data); 
/* if($dataJson->code == '0'){
	
} */
$itemsArray = $dataJson->data->items;
$gpnamesArray = $dataJson->data->gpnames;
$TfpgId ="-1";
for ($i= 0;$i< count($gpnamesArray); $i++){
	$gpnames=json_encode($gpnamesArray[$i]);
	$gpnameArray= json_decode($gpnames,true);
	if($gpnameArray["gpname"]==$gpname){
	 $TfpgId= $gpnameArray["gpid"];
	}
}
//从数据库选出机器码
$sqls= 'select  * from zp_userinfo';
$result= mysqli_query($tp,$sqls);
$itemsrows =  array();
while($itemsrow = mysqli_fetch_array($result)){
	$itemsrows[]= $itemsrow;
}
$arrysub =  array();
for ($i= 0;$i< count($itemsArray); $i++){
	$items=json_encode($itemsArray[$i]); 
	$itemArray= json_decode($items,true);
	if($itemArray["groupid"] == $TfpgId){
		$submit = true;
		for($j=0;$j<count($itemsrows);$j++){
			$row=$itemsrows[$j];
			if($itemArray["uin"] == $row['qqnum']){
				if($itemArray["remark"] == $row['fileidstr']){
					$submit = false;//为第二次购买课程的用户修改权限
				}
				break;
			}
		}
		if($submit){
			$post_data = array(
					'cid' => '1',
					'pwd'=> '',
					'drmsts'=> '1',
					'qqnum' => $itemArray["uin"],
					'fileidstr'=> $itemArray["remark"],
					'pcnum'=>'2',
					'licnumbers'=> '0'
			);
			$arrysub[] = $post_data;
			send_post('http://192.168.2.11:81/admin/batadduser_do.php', $post_data);
		}
	}
}
//返回操作的用户
echo json_encode($arrysub);

function send_post($url, $post_data) {
	$postdata = http_build_query($post_data);
	$options = array(
			'http' => array(
					'method' => 'POST',
					'header' => 'Content-type:application/x-www-form-urlencoded',
					'content' => $postdata,
					'timeout' => 15 * 60 // 超时时间（单位:s）
			)
	);
	$context = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	return $result;
}
