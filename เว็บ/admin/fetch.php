<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>
<body>
<?php
//fetch.php
include('../connect.php');
include('../datethai.php');

if(isset($_POST["query"]))
{
 $search = mysqli_real_escape_string($link, $_POST["query"]);
  $sql = "SELECT payment.*,user.uname,payment.pdate  FROM payment INNER JOIN user ON payment.userid = user.userid WHERE user.uname LIKE '%".$search."%' OR payment.username LIKE '%".$search."%' ORDER BY payment.id DESC 
 ";
}
else
{
 $sql = "SELECT payment.*,user.uname,payment.pdate FROM payment INNER JOIN user ON payment.userid = user.userid ORDER BY payment.id DESC";
}
$result=mysqli_query($link,$sql);
if(mysqli_num_rows($result) > 0)
{
 echo ' 
   <div style="width:100%; " class="table-responsive">
   <table class="table table-hover">
    <thead>
      <tr>
	 	<th width="200px">วันที่จ่าย</th>
        <th width="250px">ชื่อ-สกุล</th>
		<th width="170px">ยอดเงินที่ชำระ</th>
		<th width="200px">ผู้รับเงิน</th>
		<th width="100px">รายละเอียด </th
      </tr>
    </thead>
 ';
 while($row = mysqli_fetch_array($result))
 {
	$id = $row["userid"];
	$name = $row["uname"];
	$pdate = DateThaiFull($row["pdate"]);
  echo '
		<tbody>
      <tr>
		 <td>'.$pdate.'</td>
        <td>'.$row["uname"].'</td>
		<td>'.$row["pmoney"].' บาท</td>
		<td>'.$row["username"].'</td>
		<td><a style="color:blue;" href="des.php?id='.$row["id"].'" ><button class="btn btn-default w3-padding " style="color:blue;">รายละเอียด</button></a></td>
      </tr>
    </tbody>';
  }
 echo "</table> </div> ";
}
else
{
 echo '<center><br><br><h3>ไม่มีข้อมูลแสดง</h3><br><br></center>';
}
?>



</body>
</html>

