<?php include("../../connect.php"); session_start();
$id=$_GET["id"];
if(!isset($_SESSION["UserAdmin"]))
{
	echo "<meta http-equiv='refresh' content='0;url=../index.php'>";
}
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="../../javascript/jquery.min.js"></script>
  <script src="../../javascript/bootstrap.min.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../../css/w3.css">
<link rel="stylesheet" href="../../css/indewx.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.4.2/css/all.css' integrity='sha384-/rXc/GQVaYpyDdyxK+ecHPVYJSN9bmVFBvjA/9eOB+pb3F2w2N6fc5qB9Ew5yIns' crossorigin='anonymous'>
<title>แก้ไขข้อมูลของสมาชิก</title>
</head>

<body>

<div class="w3-top">
  <div class="w3-hide-small w3-bar w3-wide w3-padding" style="background-image:url(../../img/banner/banner.jpg); background-repeat:no-repeat; text-shadow:#FFF;">
  <div class="w3-right w3-white" style="border-radius:10px; padding:3px;">
    <h5> ผู้ดูแลระบบ : <?php echo $_SESSION["UserAdmin"]; ?>  </h5>
   </div>
   </div>
   
  	<div class="w3-hide-medium w3-hide-large w3-bar w3-wide w3-padding" style="background-image:url(../../img/banner/bannerphone.jpg); background-repeat:no-repeat; text-shadow:#FFF;">
    <!-- Float links to the right. Hide them on small screens -->
    <div class="w3-right w3-white" style="border-radius:5px; padding:8px;" >
     <b style="font-size:14px;"> <?php echo $_SESSION["UserAdmin"]; ?></b>
  	</div>
	</div>
    
<div class="topnav w3-bar w3-card" id="myTopnav">
<a style="float:right;" href="../index.php" onclick="return confirm('คุณต้องการออกจากระบบหรือไม่?')"><i class="fas fa-power-off" style="color:red; margin-right:5px;"></i>ออกจากระบบ</a>
  <a href="javascript:void(0);" class="icon" onclick="myFunction()">
    <i class="fa fa-bars" style="margin-right:10px;"></i>Menu</a>
	<div class="hide-topnav"> <br /><br /> </div>
  <a href="../status.php" >สถานะการยืมปริญญานิพนธ์</a>
  <a href="index.php" class="active w3-red">จัดการข้อมูลสมาชิก</a>
  <a href="../book/index.php" >จัดการข้อมูลปริญญานิพนธ์</a>
  <a href="../adminupdate/index.php"> จัดการผู้ดูแลระบบ</a>
  <a href="../payment.php">ประวัติการชำระเงิน</a>
  <a href="../history.php">ประวัติการใช้งานเครื่อง</a>
</div>
    </div>
    
<div class="cen">
<?php 
$sql = "SELECT * FROM user WHERE userid = '$id'"; 
$result=mysqli_query($link,$sql);
$row = mysqli_fetch_array($result);?>
 <center> 
 <br /><br />
 <h2>แก้ไขข้อมูลของ <?php echo $row["uname"]; ?></h2>
<br /><br />
 <div style="max-width:1000px; width:100%;" class="table-responsive"> 
<form action="saveedit.php?id=<?php echo $row["fingerid"]; ?>&&oid=<?php echo $row["userid"];?>" method="post">
<table style="border-style:hidden;" class="table-bordered">
<tr> <th style="text-align:left; height:50px; width:35%;"> FingerID: </th><td> <input class="form-control-static" type="text" value="<?php echo $row["fingerid"]; ?>" disabled="disabled" name="id" id="id" style="width:100px; float:left; border-radius:5px;"/></td></tr>
<tr> <th style="text-align:left; height:50px; width:35%;"> รหัสนักศึกษา: </th><td> <input class="form-control-static" type="text" pattern="[0-9]+-[0-9]{1}$" title="กรุณากรอกรหัสนักศึกษาตามรูปแบบนี้ : xxxxxxxxxxx-x" data-toggle="tooltip" value="<?php echo $row["userid"]; ?>"  name="uid" id="uid" style="width:100%; border-radius:5px;" required/></td></tr>
<tr> <th style="text-align:left; height:50px; width:35%;"> ชื่อ-สกุล: </th><td> <input class="form-control-static" type="text" value="<?php echo $row["uname"]; ?>" pattern="[^0-9_]{1,}" data-toggle="tooltip" title="กรุณากรอกชื่อ-สกุลเป็นภาษาไทยและภาษาอังกฤษเท่านั้น"  name="uname" id="uname" style="width:100%; border-radius:5px;" required/></td></tr>
<tr> <th style="text-align:left; height:50px; width:35%;"> รหัสผ่าน: </th><td> <input class="form-control-static" type="password" value="<?php echo $row["upass"]; ?>" pattern="[a-zA-Z0-9]{4,10}"  title="กรุณากรอก รหัสผ่าน โดยใช้ตัวอักษรภาษาอังกฤษหรือตัวเลขจำนวน 4-10 ตัวอักษรเท่านั้น" data-toggle="tooltip" name="upass" id="upass" style="width:100%; border-radius:5px;" required/>
<p style="float:left;"> <input type="checkbox" onclick="myFunction2()" > แสดงรหัสผ่าน </p> </td></tr>
<tr> <th style="text-align:left; height:50px; width:35%;"> เบอร์โทรศัพท์: </th><td> <input class="form-control-static" type="text" value="<?php echo $row["tel"]; ?>" pattern="[0-9]{10}" title="เบอร์โทรศัพท์มือถือ 10 หลัก" data-toggle="tooltip" name="tel" id="tel" style="width:100%; border-radius:5px;" /></td></tr>
<tr> <th style="text-align:left; height:50px; width:35%;"> ที่อยู่: </th><td> <textarea class="form-control-static" title="ที่อยู่ปัจจุบัน" data-toggle="tooltip" name="address" id="address" style="width:100%; border-radius:5px;" /><?php echo $row["address"]; ?> </textarea></td></tr></table>
<br />
<button type="submit" class="btn btn-success w3-margin w3-padding w3-margin-left" > ยืนยันข้อมูล </button>
<button type="reset" class="btn btn-danger w3-margin w3-padding"> รีเซ็ตข้อมูล </button> 
<a href="index.php"><button type="button" class="btn open-button w3-padding w3-margin"> ย้อนกลับ </button></a>
</form>
</div>
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
$(document).ready(function(){
     $('[data-toggle="tooltip"]').tooltip({placement: "right"});   
});

function myFunction2() {
    var x = document.getElementById("upass");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}
</script>