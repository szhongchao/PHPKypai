<?php
header('Content-type:text/json;charset=utf-8');

$qqnum="211342495";
$p_skey="-H09Hg3p9iuaYM4ACkdzFTNOnoBGZotRnJ721LoiCt0_"; 

/* $qqnum=$_POST['qqnum'];
$p_skey=$_POST['p_skey']; */

$data =shell_exec("python qqzone.py ".$qqnum." ".$p_skey);
$data= substr($data, 10);
$data= substr($data,0,strlen($data)-3);
$json_string='{"id":1,"name":"jb51","email":"admin@jb51.net","interest":["wordpress","php"]} ';
$obj=json_decode($data); 
$postArray = $obj->data->items;

//print_r($postArray);

for ($i= 0;$i< count($postArray); $i++){
	//$postArray[$i];
	$user  =json_encode($postArray[$i]); 
	$user = json_decode($user,true);
	print_r($user["uin"].";");
} 


/* $de_json = json_decode($postArray,TRUE);
$count_json = count($de_json);

for ($i = 0; $i < $count_json; $i++)
{
	//echo var_dump($de_json);
	
	$uin= $de_json[$i]['uin'];
	$groupid= $de_json[$i]['groupid'];
	$remark= $de_json[$i]['remark'];
	echo $uin;
}  */


/* $dataArr = $data->data->items ;
echo $dataArr; */

//echo $data;
