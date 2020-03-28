<?php
 include("../../connect.php"); session_start();
$id = $_GET["id"];
if(!isset($_SESSION["UserAdmin"]))
{
	echo "<meta http-equiv='refresh' content='0;url=../index.php'>";
}
else{
$sql = "SELECT * FROM book INNER JOIN status ON status.rfid = book.rfid WHERE status.rfid = '".$id."'";
$result=mysqli_query($link,$sql);
$row = mysqli_fetch_array($result);
if(($row["bstatus"]!=0&&$row["mstatus"]!=0)||mysqli_num_rows($result) == 0)
{
$sql1 = "DELETE FROM status WHERE rfid = '".$id."'";
mysqli_query($link,$sql1);
$sql = "UPDATE book SET rfid = '' , bname = '' , wname = '' WHERE rfid = '".$id."'";
mysqli_query($link,$sql);
echo "<script>alert('คุณลบข้อมูลของปริญญานิพนธ์นี้ เรียบร้อยแล้ว')</script>";
echo "<meta http-equiv='refresh' content='0;url=index.php'>";
}
else{
echo "<script>alert('ไม่สามารถลบได้ เนื่องจากยังดำเนินการไม่สมบูรณ์')</script>";
echo "<meta http-equiv='refresh' content='0;url=index.php'>";
}
}
?>