<?php 
include("../connect.php"); 
include("../datethai.php"); 

session_start();
if(!isset($_SESSION["UserAdmin"]))
{
	echo "<meta http-equiv='refresh' content='0;url=index.php'>";
}
$id=$_GET["id"];?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="../javascript/jquery.min.js"></script>
  <script src="../javascript/bootstrap.min.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../css/w3.css">
<link rel="stylesheet" href="../css/indewx.css">
<link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.4.2/css/all.css' integrity='sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns' crossorigin='anonymous'>

  <style>
   @media (max-width:600px)
{
  #myBtn {
  display: none;
  position: fixed;
  bottom: 1%;
  right: 10px;
  z-index: 10;
  font-size: 18px;
  border: none;
  outline: none;
  background-color: red;
  color: white;
  cursor: pointer;
  padding: 10px;
  opacity:0.8;
  border-radius: 4px;
}
}
@media (min-width:600px)
{
  #myBtn {
  display: none;
  position: fixed;
  bottom: 5%;
  right: 30px;
  z-index: 99;
  font-size: 18px;
  border: none;
  outline: none;
  background-color: red;
  color: white;
  cursor: pointer;
  padding: 10px;
  opacity:0.8;
  border-radius: 4px;
}
}
#myBtn:hover {
  opacity:1;
}


@media print 
{ 
.cen { margin-top:50px !important;  }
.non-printable { display: none !important;} 
.printable { display: block; } 
} 
 </style>
<title>ประวัติการยืม-คืนปริญญานิพนธ์</title>
</head>

<body>
<button class="non-printable" onclick="topFunction()" id="myBtn" title="เลื่อนขึ้นด้านบน"><span class="glyphicon glyphicon-arrow-up"></span> Up</button>
<div class="w3-top non-printable">
  <div class="w3-hide-small w3-bar w3-wide w3-padding printable" style="background-image:url(../img/banner/banner.jpg); background-repeat:no-repeat; text-shadow:#FFF;">
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
  <a href="javascript:void(0);" class="icon" onclick="myFunction1()">
    <i class="fa fa-bars" style="margin-right:10px;"></i>Menu</a>
	<div class="hide-topnav"> <br /><br /> </div>
<a href="status.php" >สถานะการยืมปริญญานิพนธ์</a>
  <a href="member/index.php" class="active w3-red">จัดการข้อมูลสมาชิก</a>
  <a href="book/index.php" >จัดการข้อมูลปริญญานิพนธ์</a>
  <a href="adminupdate/index.php" >จัดการผู้ดูแลระบบ</a>
  <a href="payment.php">ประวัติการชำระเงิน</a>
  <a href="history.php">ประวัติการใช้งานเครื่อง</a>
    </div>
    </div>
    
    
<div class="cen printable" >
<div class="non-printable"> <br /><br> </div>
   <center> 
   <h2>ประวัติการยืม-คืนปริญญานิพนธ์</h2>
   <h3>ของ <?php echo $_GET["name"]; ?></h3>
<div class="non-printable"> <br /><br> </div>
<?php 
if(isset($_POST["query"])&&$_POST["query"]==1)
{
 $query = $_POST["query"];
 ?>
 <form action="report.php?id=<?php echo $id; ?>&&name=<?php echo $_GET["name"]; ?>" method="post">
<p style="font-size:16px;">ค้นหาปริญญานิพนธ์โดย :
<select class="form-control-static" style="border-radius:5px;" name="query" id="query" >
<option value="0"> แสดง 10 รายการล่าสุด  </option>
<option value="1" selected="selected"> แสดง 20 รายการล่าสุด  </option>
<option value="2" > แสดงรายการทั้งหมด </option>
<option value="3"> แสดงรายการค้างชำระ  </option>
</select>
<button class="btn btn-success" type="submit"> ค้นหา </button></p>
<button class="w3-hide-small w3-hide-medium" onclick="javascript:window.print()" type="button" style="float:right;" title="คลิกเพื่อ Print หน้านี้" id="non-printable"/><i class="fas fa-print" style="font-size:24px;"></i></button>
</form><?php
 $sql = "SELECT book.bname,status.sdate,status.enddate,status.wdate,DATEDIFF(status.wdate,status.enddate),DATEDIFF(curdate(),status.enddate),status.wstatus,status.muclt FROM status INNER JOIN book ON status.rfid = book.rfid WHERE status.userid = '$id' ORDER BY status.statusid DESC LIMIT 20"; 
}
else if(isset($_POST["query"])&&$_POST["query"]==2)
{
 $query = $_POST["query"];
 ?>
 <form action="report.php?id=<?php echo $id; ?>&&name=<?php echo $_GET["name"]; ?>" method="post">
<p style="font-size:16px;">ค้นหาปริญญานิพนธ์โดย :
<select class="form-control-static" style="border-radius:5px;" name="query" id="query" >
<option value="0"> แสดง 10 รายการล่าสุด  </option>
<option value="1" > แสดง 20 รายการล่าสุด  </option>
<option value="2" selected="selected"> แสดงรายการทั้งหมด </option>
<option value="3"> แสดงรายการค้างชำระ  </option>
</select>
<button class="btn btn-success" type="submit"> ค้นหา </button></p>
<button class="w3-hide-small w3-hide-medium" onclick="javascript:window.print()" type="button" style="float:right;" title="คลิกเพื่อ Print หน้านี้" id="non-printable"/><i class="fas fa-print" style="font-size:24px;"></i></button>
</form><?php
 $sql = "SELECT book.bname,status.sdate,status.enddate,status.wdate,DATEDIFF(status.wdate,status.enddate),DATEDIFF(curdate(),status.enddate),status.wstatus,status.muclt FROM status INNER JOIN book ON status.rfid = book.rfid WHERE status.userid = '$id' ORDER BY status.statusid DESC"; 
}
else if(isset($_POST["query"])&&$_POST["query"]==3)
{
 $query = $_POST["query"];
 ?>
 <form action="report.php?id=<?php echo $id; ?>&&name=<?php echo $_GET["name"]; ?>" method="post">
<p style="font-size:16px;">ค้นหาปริญญานิพนธ์โดย :
<select class="form-control-static" style="border-radius:5px;" name="query" id="query" >
<option value="0"> แสดง 10 รายการล่าสุด  </option>
<option value="1" > แสดง 20 รายการล่าสุด  </option>
<option value="2" > แสดงรายการทั้งหมด </option>
<option value="3" selected="selected"> แสดงรายการค้างชำระ  </option>
</select>
<button class="btn btn-success" type="submit"> ค้นหา </button></p>
<div style="float:right;">
<a href="reportpay.php?id=<?php echo $id; ?>&&name=<?php echo $_GET["name"]; ?>" title="คลิกเพื่อชำระเงิน"><button class="w3-hide-small w3-hide-medium w3-margin-right" type="button" id="non-printable"/><span class="fab fa-btc" style="font-size:24px;"></span> ชำระเงิน</button></a>
<button class="w3-hide-small w3-hide-medium" onclick="javascript:window.print()" type="button" title="คลิกเพื่อ Print หน้านี้" id="non-printable"/><i class="fas fa-print" style="font-size:24px;"></i></button></div>
</form>
<?php
 $sql = "SELECT book.bname,status.sdate,status.enddate,status.wdate,DATEDIFF(status.wdate,status.enddate),DATEDIFF(curdate(),status.enddate),status.wstatus,status.muclt FROM status INNER JOIN book ON status.rfid = book.rfid WHERE status.userid = '$id' AND mstatus = 0 AND wstatus = 1 ORDER BY status.statusid DESC"; }
else
{?>
	<form action="report.php?id=<?php echo $id; ?>&&name=<?php echo $_GET["name"]; ?>" method="post">
<p style="font-size:16px;"> ค้นหาปริญญานิพนธ์โดย :
<select class="form-control-static" style="border-radius:5px;" name="query" id="query" >
<option value="0" selected="selected"> แสดง 10 รายการล่าสุด  </option>
<option value="1" > แสดง 20 รายการล่าสุด  </option>
<option value="2" > แสดงรายการทั้งหมด </option>
<option value="3"> แสดงรายการค้างชำระ  </option>
</select>
<button class="btn btn-success" type="submit"> ค้นหา </button></p>
<button class="w3-hide-small w3-hide-medium" onclick="javascript:window.print()" type="button" style="float:right;" title="คลิกเพื่อ Print หน้านี้" id="non-printable"/><i class="fas fa-print" style="font-size:24px;"></i></button>
</form>
<?php
$sql = "SELECT book.bname,status.sdate,status.enddate,status.wdate,DATEDIFF(status.wdate,status.enddate),DATEDIFF(curdate(),status.enddate),status.wstatus,status.muclt,status.mstatus FROM status INNER JOIN book ON status.rfid = book.rfid WHERE status.userid = '$id' ORDER BY status.statusid DESC LIMIT 10"; 
}
$result=mysqli_query($link,$sql);
if($num=mysqli_num_rows($result) > 0)
{
?>
<br /><br />
<p style="float:right;"><span style="color:green;" class="glyphicon">&#xe013;</span> = ชำระเงินแล้ว </p>  
	<div style="width:100%;" class="table-responsive">        
  <table class="table table-hover">
    <thead>
      <tr>
        <th width="350px" >ชื่อปริญญานิพนธ์</th>
        <th width="200px">วันที่ยืม</th>
  		<th width="200px">วันที่คืน</th>
        <th width="170px">สถานะการยืม-คืน</th>
        <th width="100px">ค่าปรับ</th>
      </tr>
    </thead>
<?php
$sum = 0;
	while($row = mysqli_fetch_assoc($result)) {
		//ให้ขึ้นตัวอักษรสีแดง สำหรับหนังสือที่เกินกำหนดคืน
		if($row["DATEDIFF(curdate(),status.enddate)"]>0&&$row["wstatus"]==0)
				{      
					$style = 'color:red';
				}
		else
			{
				$style = 'color:black';
			}
			
			//ตรวจสอบสถานะการยืม-คืน
		if($row["wstatus"]==0)
			{
			$aa = "ยืม";
			}
		else{
			$aa = "คืนแล้ว";
		}
		
		//แปลงให้เป็นวันที่แบบภาษาไทย
		$sdate = DateThaiFull($row["sdate"]);
		if($row["wdate"]=='0000-00-00')
			{
				$wdate="-";
			}
		else
			{
				$wdate = DateThaiFull($row["wdate"]);
			}
			
		echo ' <tbody>
      <tr>
        <td style="text-align:left; '.$style.'">'.$row["bname"].'</td>
        <td style='.$style.'>'.$sdate.'</td>
		<td style='.$style.'>'.$wdate.'</td>
		<td style='.$style.'>'.$aa.'</td>';
		if($row["mstatus"]==1&&$row["wstatus"]==1){ echo '<td style='.$style.'>'.$row["muclt"].' บาท <a data-toggle="tooltip" title="ชำระเงินแล้ว">
          <span style="color:green;" class="glyphicon">&#xe013;</span></a></td>';   }
        else { echo '<td style='.$style.'> '.$row["muclt"].' บาท </td>';}?>
      </tr>
    </tbody>
  <?php
} 
echo '</table></div>';
}
else{
	echo "<br><br><br><br>";
	echo "<h3>ไม่มีข้อมูลแสดง</h3>";
	echo "<br><br><br><br>";
}
?></center>
<p class="non-printable" style='font-size:14px;'> 
*หมายเหตุ <br>
ตัวอักษรสีแดง คือ ปริญญานิพนธ์ที่ยืมและเลยกำหนดส่งคืนแล้ว <br>
ตัวอักษรสีดำ คือ ปริญญานิพนธ์ที่ถูกส่งคืนแล้วหรือปริญญานิพนธ์ที่ยังไม่ถึงกำหนดส่งคืน </p> <br>
<br><br>
<center>
</div>
</div>
</div>
</center>
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        document.getElementById("myBtn").style.display = "block";
    } else {
        document.getElementById("myBtn").style.display = "none";
    }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}

function openNav() {
    document.getElementById("mySidenav").style.width = "400px"
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
}

function myFunction1() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}
</script>
</body>
</html>