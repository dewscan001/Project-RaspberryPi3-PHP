<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="../css/w3.css">
<link rel="stylesheet" href="../css/indewx.css">
<link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.4.2/css/all.css' integrity='sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns' crossorigin='anonymous'>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
@media print{
	#non{
		display:none;
	}
}
</style>
<title>พิมพ์รายละเอียดการชำระเงิน</title>
</head>

<body>
<?php 
include('../connect.php');
include('../datethai.php');
if(isset($_GET["id"]))
{	
 $sql1 = 'SELECT payment.*,user.uname,payment.pdate FROM payment INNER JOIN user ON payment.userid = user.userid WHERE id='.$_GET["id"].'';
$result1=mysqli_query($link,$sql1);
while($row1 = mysqli_fetch_array($result1)){
	$pdate = DateThaiFull($row1["pdate"]);
	echo '
	<center>
<div style="width:80%; padding-top:50px;">
<h2> รายละเอียดการชำระเงินของ '.$row1["uname"].' </h2> 
	<h4> ณ. วันที่ '.$pdate.'</h4>
	<br>
	
'.$row1["des"].'

  <br>
  <h4> ยอดเงินที่ชำระทั้งหมด '.$row1["pmoney"].' บาท</h4>
	<h4> รับเงินโดย '.$row1["username"].' </h4>
  	<br>
	<button style="position:fixed; right:0; top:0;" class=" w3-margin w3-padding" onclick="javascript:window.print()" type="button" title="คลิกเพื่อ Print หน้านี้" id="non"/><i class="fas fa-print" style="font-size:24px;"></i> คลิกเพื่อ Print หน้านี้</button></center>
';
}}
?>
</body>
</html>