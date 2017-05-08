
<?php 
require_once '../include.php';
checkAdminLogined();
?>
<html>
<head>
<title>用户搜索</title>
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
<br>
<table width="75%" border="0" bgcolor="#CCCCCC" cellspacing="1" cellpadding="0" align="center">
  <tr>
    <td bgcolor="#FFFFFF"> 
      <table width="100%" border="0" align="center" cellspacing="0" cellpadding="6" class="line" bgcolor="#E7E7EF">
        <form action=searchuser_do.php method=post name=thisform>
          <tr align="center"> 
            <td colspan="2" height="31"><b><font size="4" face="隶书">用 户 搜 索</font></b></td>
          </tr>
          <tr> 
            <td width="26%" bgcolor="#E7E7EF" nowrap>用户名</td>
            <td width="74%"> 
              <input type="text" name="uid">
            </td>
          </tr>
          <tr>
            <td bgcolor="#E7E7EF" nowrap>QQ号码</td>
            <td><input name="qqnum" type="text" id="qqnum"></td>
          </tr>
          
          <tr> 
            <td width="26%" bgcolor="#E7E7EF" nowrap>有权播放文件</td>
            <td width="74%"> 
              <input type="text" name="fileidstr" size="20">
            </td>
          </tr>
          <tr> 
            <td width="26%" bgcolor="#E7E7EF">搜索结果排序</td>
            <td width="74%"> 
              <select name="bystr">
                <option value="addtime" selected>加入日期</option>
                <option value="qqnum">qq号码</option>
                <option value="uid">播放密码</option>
              </select>
            </td>
          </tr>
          <tr> 
            <td width="26%">&nbsp;</td>
            <td width="74%"> 
              <input type="submit" name="Submit" value="搜索">
            </td>
          </tr>
          <tr> 
            <td width="26%">&nbsp;</td>
            <td width="74%">&nbsp;</td>
          </tr>
        </form>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
