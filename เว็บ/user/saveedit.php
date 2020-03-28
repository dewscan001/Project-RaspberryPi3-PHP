<?php
 include("../connect.php"); session_start();
$id=$_SESSION["UserID"];
$uid = $_POST["uid"];
$uname = $_POST["uname"];
$opass = $_POST["opass"];
$upass = $_POST["upass"];
$utel = $_POST["tel"];
$uaddress = $_POST["address"];
$a=false;
if(!isset($_SESSION["Username"]))
{
	echo "<meta http-equiv='refresh' content='0;url=../index.php'>";
}
else{
	$sql1 = "SELECT * FROM user WHERE userid = '".$id."' ";
	$result = mysqli_query($link,$sql1);
	$row1 = mysqli_fetch_array($result);
	$sql = "SELECT * FROM user WHERE userid != '".$id."' AND userid != '' ";
	$result = mysqli_query($link,$sql);
	while ($row = mysqli_fetch_array($result)){
		if($uid == $row["userid"]||$uname == $row["uname"]||$row1["upass"]!=$opass)
		{
				$a=true;
				break;
		}
	}
	
	if($a==true)
	{
		echo "<script>alert('ไม่สามารถแก้ไขข้อมูลได้')</script>";
		echo "<meta http-equiv='refresh' content='0;url=edit.php>";
	}
	else{
			$sql = "UPDATE user SET uname = '".$uname."', upass = '".$upass."', tel ='".$utel."' , address = '".$uaddress."' WHERE userid = '".$id."'";
			mysqli_query($link,$sql);
			echo "<script>alert('แก้ไขข้อมูลเรียบร้อยแล้ว')</script>";
			echo "<meta http-equiv='refresh' content='0;url=profile.php?id=".$id."'>";
		}
}
?>  