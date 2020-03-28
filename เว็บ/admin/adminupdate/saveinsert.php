<?php
 include("../../connect.php"); session_start();
$id = $_POST["id"];
$name = $_POST["name"];
$pass = $_POST["txtPassword"];
$status = $_POST["status"];
if(!isset($_SESSION["UserAdmin"])||$_SESSION["status"]==1)
{
	echo "<meta http-equiv='refresh' content='0;url=../index.php'>";
}
else{
	$sql = "SELECT * FROM admin WHERE username = '".$id."' OR name = '".$name."'";
	$result = mysqli_query($link,$sql);
	if(mysqli_num_rows($result) > 0)
	{
		echo "<meta http-equiv='refresh' content='0;url=insert.php'>";
		echo "<script>alert('ไม่สามารถเพิ่มข้อมูลได้ เนื่องจากมีข้อมูลนี้อยู่ในฐานข้อมูลแล้ว')</script>";
	}
	else
	{
		$sql = "INSERT INTO  admin (username,name,password,astatus) VALUES ('".$id."' , '".$name."' ,'".$pass."' , '".$status."')";
		mysqli_query($link,$sql);
		echo "<script>alert('เพิ่มข้อมูลดูแลระบบเรียบร้อย')</script>";
		echo "<meta http-equiv='refresh' content='0;url=index.php'>";
	}
}
?>