<?php include("../../connect.php"); session_start();
if(!isset($_SESSION["UserAdmin"]))
{
	echo "<meta http-equiv='refresh' content='0;url=../index.php'>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
<title>จัดการข้อมูลปริญญานิพนธ์</title>
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
  <a href="javascript:void(0);" class="icon" onclick="myFunction1()">
    <i class="fa fa-bars" style="margin-right:10px;"></i>Menu</a>
	<div class="hide-topnav"> <br /><br /> </div>
  <a href="../status.php" >สถานะการยืมปริญญานิพนธ์</a>
  <a href="../member/index.php" >จัดการข้อมูลสมาชิก</a>
  <a href="" class="active w3-red">จัดการข้อมูลปริญญานิพนธ์</a>
  <a href="../adminupdate/index.php"> จัดการผู้ดูแลระบบ</a>
  <a href="../payment.php">ประวัติการชำระเงิน</a>
  <a href="../history.php">ประวัติการใช้งานเครื่อง</a>
</div>
 </div>


         
<div class="cen">
<br /><br />
   <center> 
   <h2>จัดการข้อมูลของปริญญานิพนธ์</h2>
<br /><br />
<div class="form-group">
    <div class="input-group">
     <span class="input-group-addon">Search</span>
     <input type="text" name="search_text" id="search_text" placeholder="ค้นหาโดยใช้ชื่อปริญญานิพนธ์" class="form-control" style="z-index:0;"/>
    </div>
   </div>
   <div id="result"></div>
  <br>
  <br>
   <a href="insert.php" title="คลิกเพื่อเพิ่มข้อมูล"><button type="button" class="btn btn-default btn-lg" style="color:green;"> เพิ่มข้อมูล </button></a>
</div>
</div>
</body>
</html>
<script>
$(document).ready(function(){

 load_data();

 function load_data(query)
 {
  $.ajax({
   url:"fetch.php",
   method:"POST",
   data:{query:query},
   success:function(data)
   {
    $('#result').html(data);
   }
  });
 }
 $('#search_text').keyup(function(){
  var search = $(this).val();
  if(search != '')
  {
   load_data(search);
  }
  else
  {
   load_data();
  }
 });
});

function myFunction1() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}
</script>