<?php include("../../connect.php"); session_start();
if(!isset($_SESSION["UserAdmin"]))
{
	echo "<meta http-equiv='refresh' content='0;url=../index.php'>";
}
$id = $_SESSION["UserAdmin"];
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

<title>ข้อมูลของผู้ดูแลระบบ</title>
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
  <a href="../member/index.php">จัดการข้อมูลสมาชิก</a>
  <a href="../book/index.php" >จัดการข้อมูลปริญญานิพนธ์</a>
  <a href="index.php" class="active w3-red">จัดการผู้ดูแลระบบ</a>
  <a href="../payment.php">ประวัติการชำระเงิน</a>
  <a href="../history.php">ประวัติการใช้งานเครื่อง</a>
	</div>
    </div>
    
<div class="cen">
<?php $sql = "SELECT * FROM admin WHERE name = '$id' "; 
$result=mysqli_query($link,$sql);
$row = mysqli_fetch_array($result);?>
<br /><br />
   <center> 
   <h2>ข้อมูลของผู้ดูแลระบบ</h2>
   <br /><br />
 <div style="max-width:1000px; width:100%;" class="table-responsive"> 
<table style="border-style:hidden;" class="table-bordered">
<tr> <th style="text-align:left; height:50px; width:35%;"> Username: </th><td style="text-align:left;"> <?php echo $row["username"]; ?></td></tr>
<tr> <th style="text-align:left; height:50px; width:35%;"> ชื่อ-สกุล: </th><td style="text-align:left;"> <?php echo $row["name"]; ?></td></tr>
<tr> <th style="text-align:left; height:50px; width:35%;"> สถานะการเข้าถึง: </th><td style="text-align:left;"> Admin </td></tr></table>
<br />

<a href="edit.php?id=<?php echo $row["username"]; ?>"><button type="button" class="btn btn-default w3-padding w3-margin" style="color:blue;"><span class="glyphicon glyphicon-edit"></span> แก้ไขข้อมูล </button></a>
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
</script>