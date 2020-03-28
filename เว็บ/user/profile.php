<?php include("../connect.php"); session_start();
if(!isset($_SESSION["UserID"]))
{
	echo "<meta http-equiv='refresh' content='0;url=../index1.php'>";
}
$id=$_SESSION["UserID"];
?>
<!DOCTYPE html>
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
<title>ข้อมูลของผู้ใช้งาน</title>
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
  <a href="report.php" >ปริญญานิพนธ์ที่ยังไม่คืน</a>
  <a href="report-all.php" >ประวัติการยืม-คืนปริญญานิพนธ์ย้อนหลัง</a>
  <a href="" class="active w3-red"> ข้อมูลผู้ใช้งาน </a>
</div>
    </div>
    
<div class="cen">
<?php 
$sql = "SELECT * FROM user WHERE userid = '$id'"; 
$result=mysqli_query($link,$sql);
$row = mysqli_fetch_array($result);?>
 <center> 
 <br /><br />
 <h2>ข้อมูลผู้ใช้งาน</h2>
<br /><br />
 <div style="max-width:1000px; width:100%;" class="table-responsive"> 
<table style="border-style:hidden;" class="table-bordered">
<tr> <th style="text-align:left; height:50px; width:35%;"> รหัสนักศึกษา: </th><td style="text-align:left;"> <?php echo $row["userid"]; ?> </td></tr>
<tr> <th style="text-align:left; height:50px; width:35%;"> ชื่อ-สกุล: </th><td style="text-align:left;"> <?php echo $row["uname"]; ?></td></tr>
<tr> <th style="text-align:left; height:50px; width:35%;"> เบอร์โทรศัพท์: </th><td style="text-align:left;"> <?php echo $row["tel"]; ?></td></tr>
<tr> <th style="text-align:left; height:50px; width:35%;"> ที่อยู่: </th><td style="text-align:left;"> <?php echo $row["address"]; ?> </td></tr></table>
<br /><br>
<a href="edit.php"><button type="button" class="btn btn-default w3-margin w3-padding w3-margin-left" style="font-size:16px; color:blue;"><span class="glyphicon glyphicon-edit"></span> แก้ไขข้อมูลผู้ใช้งาน </button> </a>
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
</script>