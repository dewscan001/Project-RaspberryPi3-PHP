<?php 
include("../connect.php");
include("../datethai.php");
session_start();

if(!isset($_SESSION["UserID"]))
{
	echo "<meta http-equiv='refresh' content='0;url=../index1.php'>";
}
$id=$_SESSION["UserID"];
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.4.2/css/all.css' integrity='sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns' crossorigin='anonymous'>


<title>ปริญญานิพนธ์ที่ยังไม่คืน</title>
</head>

<body>
<div class="w3-top">
  <div class="w3-hide-small w3-bar w3-wide w3-padding" style="background-image:url(../img/banner/banner.jpg); background-repeat:no-repeat; text-shadow:#FFF;">
  <div class="w3-right w3-white" style="border-radius:10px; padding:3px;">
    <h5> ชื่อผู้ใช้งาน : <?php echo $_SESSION["Username"]; ?>  </h5>
   </div>
   </div>
   
  	<div class="w3-hide-medium w3-hide-large w3-bar w3-wide w3-padding" style="background-image:url(../img/banner/bannerphone.jpg); background-repeat:no-repeat; text-shadow:#FFF;">
    <!-- Float links to the right. Hide them on small screens -->
    <div class="w3-right w3-white" style="border-radius:5px; padding:8px;" >
     <b style="font-size:14px;"> <?php echo $_SESSION["Username"]; ?></b>
  	</div>
	</div>
    
   <div class="topnav w3-bar w3-card" id="myTopnav">
  <a style="float:right;" href="../index1.php" onclick="return confirm('คุณต้องการออกจากระบบหรือไม่?')"><i class="fas fa-power-off" style="color:red; margin-right:5px;"></i>ออกจากระบบ</a>
  <a href="javascript:void(0);" class="icon" onclick="myFunction()">
    <i class="fa fa-bars" style="margin-right:10px;"></i>Menu</a>
	<div class="hide-topnav"> <br /><br /> </div>
  <a href="" class="active w3-red">ปริญญานิพนธ์ที่ยังไม่คืน</a>
  <a href="report-all.php" >ประวัติการยืม-คืนปริญญานิพนธ์ย้อนหลัง</a>
  <a href="profile.php"> ข้อมูลผู้ใช้งาน </a>
    </div>
    </div>

         
<div class="cen">
<br /><br />
   <center> <h2>ปริญญานิพนธ์ที่ยังไม่คืน</h2>
<?php
echo "<br><br>";
$sql = "SELECT book.bname,status.sdate,status.enddate , DATEDIFF(curdate(),status.enddate) FROM status INNER JOIN book ON status.rfid = book.rfid WHERE status.userid = '$id' AND status.wstatus='0' ORDER BY status.sdate ASC"  ; 
$result=mysqli_query($link,$sql);

if($num=mysqli_num_rows($result) > 0)
{
	?>
    <br>
    <div style="width:100%; margin-bottom:0px;" class="table-responsive">          
  <table class="table table-hover">
    <thead>
      <tr>
        <th width="350px">ชื่อปริญญานิพนธ์</th>
        <th width="200px">วันที่ยืม</th>
        <th width="200px">กำหนดส่งคืน</th>
        <th width="200px">สถานะ</th>
      </tr>
    </thead>
  <?php
	while($row = mysqli_fetch_assoc($result)) {
		date_default_timezone_set("Asia/Bangkok");
		if($row["DATEDIFF(curdate(),status.enddate)"]>0)
		{
			$a = "เกินวันกำหนดส่งคืน";?>
			<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header w3-red">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h3>ข้อความแจ้งเตือน!!</h3>
        </div>
        <div class="modal-body" style="margin:10px;">
      		<h5> คุณมีปริญญานิพนธ์ที่เกินระยะเวลาในการยืม!!  </h5>
           <h5>  กรุณานำปริญญานิพนธ์ส่งคืนโดยด่วน </h5>
      </div>
        <div class="modal-footer w3-red">
          <button type="button" id="myBtn" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      
    </div>
    <?php
			$style = 'color:red';
		}
		else
		{
			$a = "ยังไม่ถึงวันกำหนดส่งคืน";
			$style = "color:black";
		}
		$book = nl2br($row["bname"]);
		
		//แปลงให้เป็นวันที่แบบภาษาไทย
		$sdate = DateThaiFull($row["sdate"]);
		$enddate = DateThaiFull($row["enddate"]);
		echo ' <tbody>
      <tr >
        <td style="text-align:left; '.$style.'" >'.$book.'</td>
        <td style='.$style.'>'.$sdate.'</td>
        <td style='.$style.'>'.$enddate.'</td>
        <td style='.$style.'>'.$a.'</td>
      </tr>
    </tbody>';

}
echo "  </table></div>  ";
}
else{
	echo "<br><br><br><br>";
	echo "<h3>คุณคืนเล่มปริญญานิพนธ์ครบแล้ว</h3>";
	echo "<br><br><br><br>";
}

?></p></center>
<p style='font-size:14px;'> 
*หมายเหตุ <br>
ตัวอักษรสีแดง คือ ปริญญานิพนธ์ที่ถูกยืมและเลยกำหนดส่งคืนแล้ว <br>
ตัวอักษรสีดำ คือ ปริญญานิพนธ์ที่ถูกยืมและยังไม่ถึงกำหนดส่งคืน </p> <br>
  <br>
</div>
<script>
$(document).ready(function(){
    // Show the Modal on load
    $("#myModal").modal("show");
    
    // Hide the Modal
    $("#myBtn").click(function(){
        $("#myModal").modal("hide");
    });
});

function myFunction() {
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