<?php
header('Content-type:text/json;charset=utf-8');

$qqnum="211342495";
$p_skey="mvkTspAMKWTj4qKOgab6QzboEkIcT-o5OPplb7IN8zA_"; 

/* $qqnum=$_POST['qqnum'];
$p_skey=$_POST['p_skey']; */

$data =shell_exec("python qqzone.py ".$qqnum." ".$p_skey);
$data= substr($data, 10);
$data= substr($data,0,strlen($data)-3);

echo $data;
