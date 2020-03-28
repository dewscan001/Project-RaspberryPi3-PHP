<html>
<head>
<link rel="stylesheet" href="../css/w3.css">
<link rel="stylesheet" href="../css/indewx.css">
<link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.4.2/css/all.css' integrity='sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns' crossorigin='anonymous'>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
 <script src="../javascript/jquery.min.js"></script>
  <script src="../javascript/bootstrap.min.js"></script>
<style>

/* Modal Content */
.modal-content {
    margin: auto;
	padding:20px;
    width: 80%;
	max-height:60%;
	overflow:auto;
}


</style>
</head>
<title>รายละเอียดการชำระเงิน</title>
<body>
<?php 
session_start();
if(!isset($_SESSION["UserAdmin"]))
{
	echo "<meta http-equiv='refresh' content='0;url=index.php'>";
}
include('../connect.php');
include('../datethai.php');
$id=$_GET["id"];
 echo '<script type="text/javascript">';
  echo "var data = '$id';"; // ส่งค่า $data จาก PHP ไปยังตัวแปร data ของ Javascript
  echo '</script>';
if(isset($_GET["id"]))
{	?>
<div class="w3-top">
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
<a style="float:right;" href="index.php" onClick="return confirm('คุณต้องการออกจากระบบหรือไม่?')"><i class="fas fa-power-off" style="color:red; margin-right:5px;"></i>ออกจากระบบ</a>
  <a href="javascript:void(0);" class="icon" onClick="myFunction1()">
    <i class="fa fa-bars" style="margin-right:10px;"></i>Menu</a>
	<div class="hide-topnav"> <br /><br /> </div>
<a href="status.php" >สถานะการยืมปริญญานิพนธ์</a>
  <a href="member/index.php">จัดการข้อมูลสมาชิก</a>
  <a href="book/index.php" >จัดการข้อมูลปริญญานิพนธ์</a>
  <a href="adminupdate/index.php" >จัดการผู้ดูแลระบบ</a>
  <a href="payment.php" class="active w3-red">ประวัติการชำระเงิน</a>
  <a href="history.php">ประวัติการใช้งานเครื่อง</a>
    </div>
    </div>

<div class="cen" >
<br><br>
<?php
 $sql1 = 'SELECT payment.*,user.uname,payment.pdate FROM payment INNER JOIN user ON payment.userid = user.userid WHERE id='.$_GET["id"].'';
$result1=mysqli_query($link,$sql1);
while($row1 = mysqli_fetch_array($result1)){
	$pdate = DateThaiFull($row1["pdate"]);
	echo '
	<center>

<h2> รายละเอียดการชำระเงินของ '.$row1["uname"].' </h2> 
	<h4> ณ. วันที่ '.$pdate.'</h4>
	<br>
  <!-- Modal content -->
  <div class="modal-content">
	
'.$row1["des"].'

  </div>
  <br>
  <h3> ยอดเงินที่ชำระทั้งหมด '.$row1["pmoney"].' บาท</h3>
	<h4> รับเงินโดย '.$row1["username"].' </h4>
  	<br>
	<button class="w3-hide-small w3-hide-medium btn btn-default w3-margin w3-padding" onclick="openWin()" type="button" title="คลิกเพื่อ Print หน้านี้" /><i class="fas fa-print" style="font-size:24px;"></i> คลิกเพื่อ Print หน้านี้</button>
	<a href="payment.php" ><button type="button" class="btn btn-danger w3-margin w3-padding"> ย้อนกลับ </button></a>
</center>
';
}}
?>
</div>
</body>
</html>
<script>
function openWin() {
    window.open("des1.php?id="+data,"_blank","toolbar=yes, location=yes, directories=no, status=no, menubar=yes, scrollbars=yes, resizable=no, copyhistory=yes, width=1200, height=1200");
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