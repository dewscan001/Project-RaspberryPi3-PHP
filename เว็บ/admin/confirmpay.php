<?php
//fetch.php
include('../connect.php'); session_start();
$admin = $_SESSION["UserAdmin"];
$id=$_GET["id"];
$book=$_SESSION["book"];
if(!isset($_SESSION["UserAdmin"]))
{
	echo "<meta http-equiv='refresh' content='0;url=index.php'>";
}
$output = '';?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php $sql = "SELECT * FROM status WHERE userid = '$id' AND mstatus = '0'"; 
$result=mysqli_query($link,$sql);
if(mysqli_num_rows($result) > 0)
{
 $output .= ' 
  <div style="width:100%;" class="table-responsive">
   <table class="table table-hover">
    <thead>
      <tr>
        <th width="350px">ชื่อเล่มปริญญานิพนธ์</th>
        <th width="200px">วันที่ยืม</th>
		<th width="200px">วันที่คืน</th>
		<th width="100px">ค้างชำระ</th>
      </tr>
    </thead>
 ';
 while($row = mysqli_fetch_array($result))
 {
	 $statusid=$row["statusid"];
	 $sum=$sum+$row["muclt"];
  $output .= '
		<tbody>
      <tr>
        <td>'.$row["bname"].'</td>
        <td>'.$row["sdate"].'</td>
		<td>'.$row["wdate"].'</td>
		<td>'.$row["muclt"].'  บาท</td>
      </tr>
    </tbody>';
	$sql1 = "UPDATE status SET mstatus = '1' WHERE statusid = '$statusid'";
	mysqli_query($link,$sql1);
}
$sql2 = "INSERT INTO payment (userid,pdate,pmoney,des,username) VALUES ('$id',curdate(),'$sum','$book','$admin')";
	mysqli_query($link,$sql2);
	}
	?>
    <script>
	alert('ชำระเงินเรียบร้อยแล้ว');
    </script>
    <meta http-equiv='refresh' content='0;url=payment.php'>
</body>
</html>