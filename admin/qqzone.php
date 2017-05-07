<?php
header('Content-type:text/json;charset=utf-8');
require_once '../include.php';
/* $qqnum=$_POST['qqnum'];
 $p_skey=$_POST['p_skey']; */

/* $qqnum="211342495";
$p_skey="80vc3fuXbItjJIVxKQS3viENiInivhI0feXWa9JHFcc_"; 
$data =shell_exec("python qqzone.py ".$qqnum." ".$p_skey);
$data= substr($data, 10);
$data= substr($data,0,strlen($data)-3); */

$data = '{
	"code":0,
	"subcode":0,
	"message":"",
	"default":0,
	"data":
	{"items":[
    {"uin":450648,
	"groupid":4,
	"name":"金牌咕噜",
	"remark":"po123",
	"img":"http://qlogo1.store.qq.com/qzone/450648/450648/30",
	"yellow":8,
	"online":1,
	"v6":1},
	{"uin":2025653,
	"groupid":4,
	"name":"....",
	"remark":"po4en4",
	"img":"http://qlogo2.store.qq.com/qzone/2025653/2025653/30",
	"yellow":-1,
	"online":0,
	"v6":1},
	{"uin":7440146,
	"groupid":4,
	"name":"秋歌",
	"remark":"po13po123ma1",
	"img":"http://qlogo3.store.qq.com/qzone/7440146/7440146/30",
	"yellow":-1,
	"online":0,
	"v6":1},
	{"uin":10950160,
	"groupid":4,
	"name":"我们再靠近点吧",
	"remark":"z2y25s2",
	"img":"http://qlogo1.store.qq.com/qzone/10950160/10950160/30",
	"yellow":-1,
	"online":0,
	"v6":1},
	{"uin":20279256,
	"groupid":4,
	"name":"BO~",
	"remark":"z37y257",
	"img":"http://qlogo1.store.qq.com/qzone/20279256/20279256/30",
	"yellow":-1,
	"online":0,
	"v6":1},
	{"uin":25751756,
	"groupid":4,
	"name":"Alex",
	"remark":"s7",
	"img":"http://qlogo1.store.qq.com/qzone/25751756/25751756/30",
	"yellow":-1,
	"online":0,
	"v6":1},
	{"uin":46483656,
	"groupid":4,
	"name":"蓝山。",
	"remark":"z7",
	"img":"http://qlogo1.store.qq.com/qzone/46483656/46483656/30",
	"yellow":-1,
	"online":0,
	"v6":1},
	{"uin":50257955,
	"groupid":4,
	"name":"show you faceペ",
	"remark":"z1239y1247x3s",
	"img":"http://qlogo4.store.qq.com/qzone/50257955/50257955/30",
	"yellow":-1,
	"online":0,
	"v6":1},
	{"uin":70967712,
	"groupid":4,
	"name":"呵呵哒~",
	"remark":"m2m7c2",
	"img":"http://qlogo1.store.qq.com/qzone/70967712/70967712/30",
	"yellow":-1,
	"online":0,
	"v6":1},
	{"uin":83851486,
	"groupid":4,
	"name":"拭目以待、",
	"remark":"ma123ma123,",
	"img":"http://qlogo3.store.qq.com/qzone/83851486/83851486/30",
	"yellow":-1,
	"online":0,
	"v6":1},
	{"uin":3532328743,
	"groupid":4,
	"name":"梦语晨烟",
	"remark":"pe\/all,c4",
	"img":"http://qlogo4.store.qq.com/qzone/3532328743/3532328743/30",
	"yellow":-1,
	"online":0,
	"v6":1},
	{"uin":3576023425,
	"groupid":4,
	"name":"cannibal",
	"remark":"po123en123ma123",
	"img":"http://qlogo2.store.qq.com/qzone/3576023425/3576023425/30",
	"yellow":-1,
	"online":0,
	"v6":0}],
	"gpnames":[{"gpid":0,
	"gpname":"我的好友01"},
	{"gpid":1,
	"gpname":"常用联系人"},
	{"gpid":2,
	"gpname":"2016.12.12"},
	{"gpid":3,
	"gpname":"我的qq号"},
	{"gpid":4,
	"gpname":"17-TfpgSchool"},
	{"gpid":5,
	"gpname":"同行好友"},
	{"gpid":7,
	"gpname":"18-TfpgSchool"},
	{"gpid":8,
	"gpname":"17-06CET"},
	{"gpid":9,
	"gpname":"我的好友"},
	{"gpid":20,
	"gpname":"认证空间"}]}}';
$dataJson=json_decode($data); 
$itemsArray = $dataJson->data->items;
//从数据库选出机器码
$sqls= 'select  * from zp_userinfo';
$result= mysqli_query($tp,$sqls);
$itemsrows =  array();
while($itemsrow = mysqli_fetch_array($result)){
	$itemsrows[]= $itemsrow;
}
for ($i= 0;$i< count($itemsArray); $i++){
	$items=json_encode($itemsArray[$i]); 
	$itemArray= json_decode($items,true);
	if($itemArray["groupid"] == 4){
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
			send_post('http://192.168.2.11:81/admin/batadduser_do.php', $post_data);
		}
	}
} 
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
