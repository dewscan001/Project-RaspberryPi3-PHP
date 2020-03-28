<?php
 include("../../connect.php"); session_start();
$id = $_GET["id"];
$oid = $_GET["oid"];
$uid = $_POST["uid"];
$uname = $_POST["uname"];
$upass = $_POST["upass"];
$utel = $_POST["tel"];
$uaddress = $_POST["address"];
$a=false;
if(!isset($_SESSION["UserAdmin"]))
{
	echo "<meta http-equiv='refresh' content='0;url=../index.php'>";
}
else{
	$sql = "SELECT * FROM user WHERE userid != '".$oid."' AND userid != '' ";
	$result = mysqli_query($link,$sql);
	while ($row = mysqli_fetch_array($result)){
		if($uid == $row["userid"]||$uname == $row["uname"])
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
			$sql = "UPDATE status SET userid = '".$uid."' WHERE userid = '".$oid."'";
			mysqli_query($link,$sql);
			$sql = "UPDATE memberhistory SET userid = '".$uid."' WHERE userid = '".$oid."'";
			mysqli_query($link,$sql);
			$sql = "UPDATE user SET userid = '".$uid."',uname = '".$uname."', upass = '".$upass."', tel ='".$utel."' , address = '".$uaddress."' WHERE fingerid = '".$id."'";
			mysqli_query($link,$sql);
			echo "<script>alert('แก้ไขข้อมูลสมาชิก #".$id." เรียบร้อยแล้ว')</script>";
			echo "<meta http-equiv='refresh' content='0;url=index.php'>";
		}
}
?>