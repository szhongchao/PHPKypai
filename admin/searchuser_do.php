<!--#include file="conn.asp" -->
<html>
<head>
<title>搜索</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
<!--
 
.line {  line-height: 18px}
table {  font-size: 12px}
A:link {color:#000066;text-decoration: none;}
A:visited {color:000066;text-decoration: none; }
A:active {color:#DB6D00;text-decoration: none; }
A:hover {color:#FF9900;text-decoration: none;}
-->
</style>
<script>
function doit(p){
  if (p=="1") {
  lic.style.display='block';
  } 
  else{
  lic.style.display='none';
  }
}
</script>
</head>

<?php 
require_once '../include.php';
$uid=trim($_POST['uid']);
$qqnum=trim($_POST['qqnum']);
$fileidstr=trim($_POST['fileidstr']);
$bystr=trim($_POST['bystr']);

$keystr="uid=";
$ordertype = "";
		
/* 		& server.urlencode(uid) &""&_
"&drmdot="& server.urlencode(drmdot) &""&_
"&drmdot2="& server.urlencode(drmdot2) &""&_
"&drmenddate="& server.urlencode(drmenddate) &""&_
"&drmenddate2="& server.urlencode(drmenddate2) &""&_
"&fileidstr="& server.urlencode(fileidstr) &""&_
"&qqnum="& server.urlencode(qqnum) &""&_
"&bystr="& server.urlencode(bystr) */
 
$sql = "";
if ($uid<>"") {
	$sql="uid like '%".$uid."%'";
}
		
if ($fileidstr<>""){
	if ($sql=="") {
		$sql="fileidstr like '%".$fileidstr."%'";
	}else{
		$sql=$sql." and fileidstr like '%".$fileidstr."%'";
	}
}
	
if ($qqnum<>"") {
	if ($sql==""){
		$sql="qqnum like '%".$qqnum."%'";
	}else{
		$sql=$sql." and qqnum like '%".$qqnum."%'";
	}
}
			
if ($sql==""){ 
	$sql="1=1";
}
		
		$curpage = "";
		if (is_null($curpage) or $curpage=="") {
			$curpage=1;
		}else{
			if (intval($curpage)<=0){
				$curpage=1;
			}
		}
		$pagesize=30;
						
		$sqls="select count(*) as total from zp_userinfo where ".$sql;
		
		$rs= mysqli_query($tp,$sqls);
		$res = mysqli_fetch_assoc($rs);
		$totalcount= $res['total'];
		
		if ($totalcount==0){
			response.write("<center><br><br><br>没有记录！<br><br><a href=javascript:history.go(-1)>返回</a></center>");
			response.end;
		}
						
		$pagecount=intval($totalcount/$pagesize);
		if ($totalcount > $pagecount*$pagesize){
			$pagecount=$pagecount+1;
		}
						
		if (intval($curpage)>intval($pagecount)) {
		$curpage=$pagecount;
		}
						
						
		if ($curpage==1) {
		    $sqls= "select  * from zp_userinfo where ".$sql ." order by ".$bystr . " desc,id desc";
		}else{
			$sqls= "select top " .$pagesize ." * from zp_userinfo where ".$sql ." and id not in (select top ".($curpage-1)*$pagesize. " id from zp_userinfo where ". $sql ." order by ".$bystr ." desc,id desc) order by ".$bystr ." desc,id desc";
		}
		$result= mysqli_query($tp,$sqls);
?>
<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table width="96%" border="0" cellspacing="1" cellpadding="0" align="center">
  <tr>
    <td bgcolor="#FFFFFF" height="17"> 
      <table width="100%" border="0" align="center" cellspacing="0" cellpadding="6" class="line">
        <form action=searchuser_do.php method=post name=thisform>
          <tr align="center"> 
            <td colspan="2" height="32"><b><font size="4" face="隶书"> 搜 索 结 果</font></b></td>
          </tr>
        </form>
      </table>
    </td>
  </tr>
</table>
<br>     
<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr valign="top">
          <td width="36%" height="18">○ 共<?php echo $totalcount?>条，共<?php echo $pagecount?>页，第<font color="#CC0000"><?php echo $curpage?></font>页</td>
          <td height="18" align="right"><a href=searchuser_do.php<?php echo $keystr?>&curpage=<?php echo $curpage?>&ordertype=<?php echo $ordertype?>>[刷新]</a>&nbsp;<a href=searchuser_do.php?<?php echo $keystr?>&curpage=<?php echo$curpage-1?>&ordertype=<?php echo $ordertype?>>[上一页]</a>&nbsp;<a href=searchuser_do.php?<?php echo $keystr?>&curpage=<?php echo $curpage+1?>&ordertype=<?php echo $ordertype?>>[下一页]</a>&nbsp;<a href=searchuser_do.php?<?php echo $keystr?>&curpage=1&ordertype=<?php echo $ordertype?>>[首页]</a>&nbsp;<a href=searchuser_do.php?<?php echo $keystr?>&curpage=<?php echo $pagecount?>&ordertype=<?php echo $ordertype?>>[尾页]</a></td>
  </tr>
</table>
<table width="98%" border="0" align="center" height="17" bgcolor="#CCCCCC" cellspacing="1" cellpadding="4">
  <tr bgcolor="#CCCCCC" align="center"> 
    <td width="8%">用户名</td>
    <td width="8%">用户QQ</td>
    <td width="8%">加入日期</td>
    <td width="4%">帐户
      点数</td>
    <td width="4%">状态</td>
    <td width="8%">失效日期</td>
    <td width="6%">允许申请<br>
      证书次数</td>
    <td width="7%">已经申请<br>
      证书次数</td>
    <td width="11%">允许播放文件</td>
    <td width="11%">最后申请<br>
      时间</td>
    <td width="9%">最后申请<br>
      文件</td>
    <td width="9%">最后访问IP</td>
    <td width="7%">&nbsp;</td>
  </tr>
  
  <?php
  $lasttime="";
  $lastfileid="";
  $lastip="";
   while($row = mysqli_fetch_assoc($result)){
  ?>
  <tr bgcolor="#FFFFFF"> 
    <td width="9%">&nbsp;<a href=userlic_pro.asp?uid=<?php echo $row['uid']?>><?php echo $row['uid']?></a>
	<?php
	if (is_null($row['qqnum']) and $row['qqnum']<>""){
	  // response.write "("& rs("qqnum") &")";
	}
	?>
	</td>
    <td width="8%">&nbsp;<?php echo $row['addtime']?></td>
    <td width="8%">&nbsp;<?php echo $row['qqnum']?></td>
    <td width="4%">&nbsp;<?php echo $row['drmdot']?></td>
    <td width="4%">&nbsp;<?php echo $row['drmsts']?></td>
    <td width="8%">&nbsp;<?php echo $row['drmenddate']?></td>
    <td width="6%">&nbsp;<?php echo $row['licnumbers']?></td>
    <td width="7%">&nbsp;<?php echo $row['usedlicnumbers']?></td>
    <td width="11%">&nbsp;<?php echo $row['fileidstr']?></td>
    <td width="11%">&nbsp; <?php echo $lasttime?></td>
    <td width="9%">&nbsp; <?php echo $lastfileid?></td>
    <td width="9%">&nbsp;<?php echo $lastip?></td>
    <td width="7%" align="center"><a href=deluser.asp?uid=<?php echo $row['uid']?> onClick="javascript:return confirm('确信要删除吗？');">删除</a></td>
  </tr>
<?php 
   }
?>
<?php   
	 $StartPageNum=1;               
	 /*while (intval($StartPageNum+15)<=intval($curpage)){
		$StartPageNum=$StartPageNum+15;  
	
	  }  */
	  
	$EndPageNum=$StartPageNum+14;
	
	If ($EndPageNum>$pagecount){
		$EndPageNum=$pagecount;
	} 
?>
      </table>
<table width="98%" border="0" cellspacing="0" cellpadding="3" align="center">
  <tr>
          <td width="31%">○ 页次:<font color="#CC0000"><?php echo $curpage?></font>/<?php echo $pagecount?>,每页:<font color="#CC0000"><?php echo $pagesize?></font>条 </td>
          <td align="right" width="69%">页数：<a href="searchuser_do.php?<?php echo $keystr?>&ordertype=<?php echo $ordertype?>&curpage=<?php echo $StartPageNum-1?>"><<</a>
              <?php for ($i=$StartPageNum; $i< $EndPageNum;$i++ ){    
     			if ($i<>curpage) {
	      	?>
              <a href="searchuser_do.php?<?php echo $keystr?>&ordertype=<?php echo $ordertype?>&curpage=<?php echo $i?>">[<?php echo $i?>]</a>
              <?php }else{ ?>
              <font color="#CC0000"><b><?php echo $i?></b></font>
              <?php } ?>
              <?php if ($EndPageNum<$pagecount){ ?>
              <a href="searchuser_do.php?<?php echo $keystr?>&ordertype=<?php echo $ordertype?>&curpage=<?php echo $EndPageNum+1?>">[更多...]</a>
              <?php } }?>
</table>
</body>
</html>
