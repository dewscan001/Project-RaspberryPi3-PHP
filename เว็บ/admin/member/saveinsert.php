<?php

 include("../../connect.php"); session_start();
$id = $_GET["id"];
$uid = $_POST["uid"];
$uname = $_POST["uname"];
$upass = $_POST["upass"];
$utel = $_POST["tel"];
$uaddress = $_POST["address"];
if(!isset($_SESSION["UserAdmin"]))
{
	echo "<meta http-equiv='refresh' content='0;url=../index.php'>";
}

else{
	$sql = "SELECT * FROM user WHERE userid = '".$uid."' OR uname = '".$uname."'";
	$result = mysqli_query($link,$sql);
	if(mysqli_num_rows($result) > 0)
	{
		echo "<meta http-equiv='refresh' content='0;url=insert.php'>";
		echo "<script>alert('ไม่สามารถเพิ่มข้อมูลได้ เนื่องจากมีข้อมูลนี้อยู่ในฐานข้อมูลแล้ว')</script>";
	}
	else
	{
		$sql = "UPDATE user SET userid = '".$uid."',uname = '".$uname."', upass = '".$upass."', tel ='".$utel."' , address = '".$uaddress."' WHERE fingerid = '".$id."'";
		mysqli_query($link,$sql);
		echo "<meta http-equiv='refresh' content='0;url=index.php'>";
		echo "<script>alert('เพิ่มข้อมูลสมาชิก #".$id." เรียบร้อยแล้ว')</script>";
	}
}
?>