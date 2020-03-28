<?php 
include("../connect.php"); 
include("../datethai.php");

session_start();
if(!isset($_SESSION["UserAdmin"]))
{
	echo "<meta http-equiv='refresh' content='0;url=index.php'>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="../javascript/jquery.min.js"></script>
  <script src="../javascript/bootstrap.min.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../css/w3.css">
<link rel="stylesheet" href="../css/indewx.css">
<link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.4.2/css/all.css' integrity='sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns' crossorigin='anonymous'>


<title>ยืนยันการชำระเงิน</title>
</head>

<body>
<div class="w3-top">
  <div class="w3-hide-small w3-bar w3-wide w3-padding" style="background-image:url(../img/banner/banner.jpg); background-repeat:no-repeat; text-shadow:#FFF;">
  <div class="w3-right w3-white" style="border-radius:10px; padding:3px;">
    <h5> ผู้ดูแลระบบ : <?php echo $_SESSION["UserAdmin"]; ?>  </h5>
   </div>
   </div>
   
  	<div class="w3-hide-medium w3-hide-large w3-bar w3-wide w3-padding" style="background-image:url(../img/banner/bannerphone.jpg); background-repeat:no-repeat; text-shadow:#FFF;">
    <!-- Float links to the right. Hide them on small screens -->
    <div class="w3-right w3-white" style="border-radius:5px; padding:8px;" >
     <b style="font-size:14px;"> <?php echo $_SESSION["UserAdmin"]; ?></b>
  	</div>
	</div>
    
    <div class="topnav w3-bar w3-card" id="myTopnav">
<a style="float:right;" href="index.php" onclick="return confirm('คุณต้องการออกจากระบบหรือไม่?')"><i class="fas fa-power-off" style="color:red; margin-right:5px;"></i>ออกจากระบบ</a>
  <a href="javascript:void(0);" class="icon" onclick="myFunction()">
    <i class="fa fa-bars" style="margin-right:10px;"></i>Menu</a>
	<div class="hide-topnav"> <br /><br /> </div>
  <a href="status.php" >สถานะการยืมปริญญานิพนธ์</a>
  <a href="member/index.php" class="active w3-red">จัดการข้อมูลสมาชิก</a>
  <a href="book/index.php" >จัดการข้อมูลปริญญานิพนธ์</a>
  <a href="adminupdate/index.php"> จัดการผู้ดูแลระบบ</a>
  <a href="payment.php">ประวัติการชำระเงิน</a>
  <a href="history.php">ประวัติการใช้งานเครื่อง</a>
</div>
    </div>
    

 <div class="cen">
 <center>
 <br /><br />
 <h2>ชำระค่าปรับของ <?php echo $_GET["name"]; ?></h2>
 <br><br>
 <?php 
 date_default_timezone_set("Asia/Bangkok");
 
 $id = $_GET["id"];
 $name = $_GET["name"];
  $sql = "SELECT * FROM `status` INNER JOIN book ON book.rfid=status.rfid WHERE status.mstatus = '0' AND status.wstatus = '1' AND status.userid = '$id'";
 $result=mysqli_query($link,$sql);
if(mysqli_num_rows($result) > 0)
{
	$sum=0;
 $output .= ' 
   <div style="width:100%; " class="table-responsive">
   <table class="table table-hover">
    <thead>
      <tr>
        <th style="text-align:center;" width="350px">ชื่อปริญญานิพนธ์</th>
        <th style="text-align:center;" width="200px">วันที่ยืม</th>
		<th style="text-align:center;" width="200px">วันที่คืน</th>
		<th style="text-align:center;" width="100px">จำนวนเงิน</th>
      </tr>
    </thead>
 ';
 while($row = mysqli_fetch_array($result))
 {
	 $book=nl2br($row["bname"]);
	 $sum=$sum+$row["muclt"];
	 $sdate = DateThaiFull($row["sdate"]);
 	 $wdate = DateThaiFull($row["wdate"]);
  	$output .= '
		<tbody>
      <tr>
        <td style="text-align:left;">'.$book.'</td>
        <td>'.$sdate.'</td>
		<td>'.$wdate.'</td>
		<td>'.$row["muclt"].'  บาท</td>
      </tr>
    </tbody>';
 }
 $output .= "</table> </div> "; 
 echo $output;
echo '<br><div style="float:right;">
 <h3>หนี้ค้างชำระ '.$sum.' บาท </h3>
 <h4>รับเงินโดย '.$_SESSION["UserAdmin"].'</h4>
</div> ';
 
$_SESSION["book"] = $output;}
else
{
 echo '<center><br><br><br><h3>ไม่มีข้อมูลแสดง</h3><br><br></center>';
}
 ?>
 <center>
 <br><br><br /><br /><br /><br />
 <?php if(mysqli_num_rows($result) > 0){?>
 <a href="confirmpay.php?id=<?php echo $id; ?>"><button type="button" class="btn btn-success w3-margin w3-padding" style="width:100px; font-size:16px;"> ตกลง </button></a>
<a href="report.php?id=<?php echo $id; ?>&&name=<?php echo $_GET["name"]; ?>"><button type="button" class="btn btn-danger w3-margin w3-padding" style="width:100px; font-size:16px;"> ย้อนกลับ </button></a>
<?php } else { ?>
<a href="report.php?id=<?php echo $id; ?>&&name=<?php echo $_GET["name"]; ?>"><button type="button" class="btn btn-danger w3-margin w3-padding" style="width:100px; font-size:16px;"> ย้อนกลับ </button></a> <?php } ?>
</center>
 </div>
 </body>
 </html>
 
 <script>
function myFunction() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}
</script>