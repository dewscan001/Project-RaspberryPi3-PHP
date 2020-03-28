<?php
 include("../../connect.php"); session_start();
 $fid = $_GET["fid"];
$id = $_GET["id"];
if(!isset($_SESSION["UserAdmin"]))
{
	echo "<meta http-equiv='refresh' content='0;url=../index.php'>";
}
else{
$sql = "SELECT * FROM status INNER JOIN user ON status.userid = user.userid WHERE status.userid = '".$id."'";
$result=mysqli_query($link,$sql);
$row = mysqli_fetch_array($result);
$uname=$row["uname"];
if($row["mstatus"]!=0||mysqli_num_rows($result) == 0)
{
$sql1 = "DELETE FROM status WHERE userid = '".$id."'";
mysqli_query($link,$sql1);
$sql2 = "DELETE FROM memberhistory WHERE userid = '".$id."'";
mysqli_query($link,$sql2);
$sql3 = "DELETE FROM payment WHERE userid = '".$id."'";
mysqli_query($link,$sql3);
$sql4 = "UPDATE user SET userid='', uname='' WHERE userid = '".$id."' ";
mysqli_query($link,$sql4);
echo "<script>alert('คุณลบข้อมูล #".$fid." เรียบร้อยแล้ว')</script>";
echo "<meta http-equiv='refresh' content='0;url=index.php'>";
}
else{
	echo "<script>alert('ไม่สามารถลบได้ เนื่องจากยังดำเนินการไม่สมบูรณ์')</script>";
	echo "<meta http-equiv='refresh' content='0;url=index.php'>";
}
}
?>