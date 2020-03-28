<?php
 include("../../connect.php"); session_start();
$id = $_GET["id"];
$no=$_POST["no"];
$bname = $_POST["bname"];
$wname = $_POST["wname"];
$a=false;
if(!isset($_SESSION["UserAdmin"]))
{
	echo "<meta http-equiv='refresh' content='0;url=../index.php'>";
}
else{
	$sql = "SELECT * FROM book WHERE bname = '".$bname."'";
	$result = mysqli_query($link,$sql);
	if(mysqli_num_rows($result) > 0)
		{
			echo "<script>alert('ไม่สามารถแก้ไขข้อมูลได้ เนื่องจากมีข้อมูลนี้อยู่ในฐานข้อมูลแล้ว')</script>";
			echo "<meta http-equiv='refresh' content='0;url=edit.php?id=".$id."'>";
			break;
		}
		else{
			$sql = "UPDATE book SET bname = '".$bname."' , wname = '".$wname."' WHERE rfid  = '".$id."'";
			mysqli_query($link,$sql);
			echo "<script>alert('เพิ่มข้อมูลของปริญญานิพนธ์เรียบร้อย')</script>";
			echo "<meta http-equiv='refresh' content='0;url=index.php'>";
		}
}

?>