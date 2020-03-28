<?php
session_start();
 include("../../connect.php"); session_start();
$oid= $_GET["id"];
$id = $_POST["id"];
$name = $_POST["name"];
$pass = $_POST["txtPassword"];
$status = $_POST["status"];
$a=false;
if(!isset($_SESSION["UserAdmin"]))
{
	echo "<meta http-equiv='refresh' content='0;url=../index.php'>";
}
else{
	$sql = "SELECT * FROM admin WHERE username != '".$oid."'";
	$result = mysqli_query($link,$sql);
	while ($row = mysqli_fetch_array($result)){
		if($id == $row["username"]||$name == $row["name"])
		{
			$a=true;
			break;
		}
	}
	if($a==true)
	{
		echo "<script>alert('ไม่สามารถแก้ไขข้อมูลได้ เนื่องจากมีข้อมูลนี้อยู่ในฐานข้อมูลแล้ว')</script>";
		echo "<meta http-equiv='refresh' content='0;url=edit.php?id=".$oid."'>";
	}
	else{ 
		if($_SESSION["status"]==0)
		{
			$sql = "UPDATE admin SET username = '".$id."' , name = '".$name."' , password = '".$pass."' , astatus = '".$status."' WHERE username = '".$oid."'";
		}
		else 
		{
			$sql = "UPDATE admin SET username = '".$id."' , name = '".$name."' , password = '".$pass."' , astatus = 1 WHERE username = '".$oid."'";
		}
		mysqli_query($link,$sql);
		echo "<script>alert('แก้ไขข้อมูลของผู้ดูแลระบบ ".$name." เรียบร้อย')</script>";
		echo "<meta http-equiv='refresh' content='0;url=index.php'>";
		}
}

?>