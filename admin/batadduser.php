<?php 
require_once '../include.php';
checkAdminLogined();
?>
<html>
<head>
<title>新增用户</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
<!--
table {  font-size: 12px}
.line {  line-height: 18px}
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

<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="90%" border="0" align="center" cellspacing="1" cellpadding="4" class="line">
  <form action=batadduser_do.php method=post name=thisform>
    <tr align="center"> 
      <td colspan="2" height="31" bgcolor="#FFFFFF"><b><font size="3">批 量 新 增 用 户</font></b></td>
    </tr>
    <tr> 
      <td width="26%" bgcolor="#E7E7EF" nowrap>随机插入多少用户数</td>
      <td bgcolor="#FFFFFF" width="74%"> 
        <input name="cid" type="text" value="1" >
        个      </td>
    </tr>
    <tr>
      <td bgcolor="#E7E7EF" nowrap>设置密码</td>
      <td bgcolor="#FFFFFF"><input name="pwd" type="text" id="pwd" size="12"></td>
    </tr>
    <tr> 
      <td width="26%" bgcolor="#E7E7EF" nowrap>是否有效</td>
      <td bgcolor="#FFFFFF" width="74%"> 
        <input type="radio" name="drmsts" value="1" checked>
        启用 
        <input type="radio" name="drmsts" value="0">
        禁用</td>
    </tr>
    <tr>
      <td bgcolor="#E7E7EF" nowrap>QQ号码（会显示为水印）</td>
      <td bgcolor="#FFFFFF"><input name="qqnum" type="text" id="qqnum" size="42"value="211342495"></td>
    </tr>
    <tr> 
      <td width="26%" bgcolor="#E7E7EF" nowrap>允许播放的文件</td>
      <td bgcolor="#FFFFFF" width="74%"> 
         <input type="text" name="fileidstr"type="text" id="fileidstr"  size="50"value="PO03|PO04|EN02|EN04|EN05|MA02|MA03|MA04">
        （“|”号分割文件编号，留空为全部可以播放） </td>
    </tr>
    <tr>
      <td bgcolor="#E7E7EF" nowrap>绑定用户登陆的电脑数</td>
      <td bgcolor="#FFFFFF"><input name="pcnum" type="text" id="pcnum" size="10" maxlength="2" value="2" >
（0为不限制）</td>
    </tr>
    <tr> 
      <td width="26%" bgcolor="#E7E7EF" nowrap>允许申请证书次数</td>
      <td bgcolor="#FFFFFF" width="74%"> 
        <input type="text" name="licnumbers" size="10" value="0">
        （0为不设置）</td>
    </tr>
    <tr> 
      <td width="26%">&nbsp;</td>
      <td bgcolor="#FFFFFF" width="74%"> 
        <input type="submit" name="Submit" value="批量添加">      </td>
    </tr>
  </form>
</table>
</body>
</html>
