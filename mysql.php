<?php
$mysqli = new mysql("localhost", "root", "helloworld");
if(!$mysqli)  {
	echo"database error";
}else{
	echo"php env successful";
}
$mysqli->close();
?>  


