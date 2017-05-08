<?php
header('Content-type:text/json;charset=utf-8');

$qqnum="2014336837";
$p_skey="T9j86OXt395PyK5Z*-zMizj8qdvmzFFGiiMApC9qghY_"; 

/* $qqnum=$_POST['qqnum'];
$p_skey=$_POST['p_skey']; */

/* $data =shell_exec("python qqzone.py ".$qqnum." ".$p_skey);
$data= substr($data, 10);
$data= substr($data,0,strlen($data)-3);
$dataJson=json_decode($data);
$code= $dataJson->code;

echo  '{"code":1,"subcode":-4001,"message":"请先登录","notice":0,"time":1494241617,"tips":"87F1-1263"}'; */



 $fileidstr= 'po123en0ma123'; 
 preg_match_all('/([a-z]+)([0-9]+)/i', $fileidstr, $fileidstrAarr);
 //$fileidstrAarr = array_combine($Aarray[1], $Aarray[2]);
 $fileid = '';
  for($i=0;$i<count($fileidstrAarr[1]);$i++){
  	for ($j=0;$j<strlen($fileidstrAarr[2][$i]);$j++) {
  		$fileid = $fileid.$fileidstrAarr[1][$i].$fileidstrAarr[2][$i][$j];
 	}
 } 
 echo $fileid;
//echo "123";
//print_r($Carray);

//echo $data;
