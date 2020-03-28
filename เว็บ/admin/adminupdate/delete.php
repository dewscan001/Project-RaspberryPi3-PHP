<?php
 include("../../connect.php"); session_start();
$id = $_GET["id"];
if(!isset($_SESSION["UserAdmin"])||$_SESSION["status"]==1)
{
	echo "<meta http-equiv='refresh' content='0;url=../index.php'>";
}
else{
$sql = "DELETE FROM admin WHERE username = '".$id."'";
mysqli_query($link,$sql);
echo "<script>alert('ลบข้อมูลของผู้ดูแลระบบเรียบร้อย')</script>";
echo "<meta http-equiv='refresh' content='0;url=index.php'>";
}
?>