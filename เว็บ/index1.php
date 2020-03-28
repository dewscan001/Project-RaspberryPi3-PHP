<?php
session_start();
session_unset(); 
session_destroy(); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="javascript/jquery.min.js"></script>
  <script src="javascript/bootstrap.min.js"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">
<link rel="stylesheet" href="css/indewx.css">
<style>
.login
{
	opacity:0.8;
}
</style>
<title>หน้าแรก</title>
</head>

<body>
<div class="w3-top" > 
  <div class="w3-bar w3-padding w3-hide-small" style="background-image:url(img/banner/banner.jpg); background-repeat:no-repeat;">
    <!-- Float links to the right. Hide them on small screens -->
    <div class="w3-right w3-hide-medium" style="height:200px; color:black;">
  <button type="button" class="login btn w3-green" style="heght:30px; font-size:16px; cursor:pointer; margin-left:10px; opacity:1.0;"onclick="openForm()">เข้าสู่ระบบ</button>
</div>
</div>
<div class="w3-bar w3-padding  w3-hide-large" style="background-image:url(img/banner/bannerphone.jpg); background-repeat:no-repeat;">
    <div class="w3-right  dropdown" style="max-width:40%; height:200px; color:black;">
  <button class="login btn w3-green" style="opacity:1.0;">เข้าสู่ระบบ</button>
</div>
</div>
    </div>
    </div>
 
 <div class="form-popup" id="myForm" style="width:500px;">
  <form action="confirm.php" class="form-container" method="post">
    <br /><center><h2 class="w3-hide-small">เข้าสู่ระบบ</h2><h3 class="w3-hide-medium w3-hide-large">เข้าสู่ระบบ</h3></center><br />
Username หรือ รหัสนักศึกษา
   <input type="text" placeholder="Username หรือ รหัสนักศึกษา"  name="user" required>
รหัสผ่าน
    <input type="password" placeholder="รหัสผ่าน" pattern="[a-zA-Z0-9]{1,}" name="pass" required>
<br /><br />
    <button type="submit" style="float:left;" class="btn">Login</button>
    <button type="button" class="btn cancel" style="float:right;" onclick="closeForm()">Close</button>
  </form>
</div>

         
<div class="cen">
   <center> <h2 class="w3-hide-small">รายชื่อปริญญานิพนธ์ในระบบ</h2><h3 class="w3-hide-medium w3-hide-large">รายชื่อปริญญานิพนธ์ในระบบ</h3></center>
<center>
<br /><br />

<div class="form-group">
    <div class="input-group">
     <span class="input-group-addon">Search</span>
     <input type="text" name="search_text" id="search_text" placeholder="ค้นหาโดยใช้ชื่อเล่มปริญญานิพนธ์" class="form-control" style="z-index:0;"/>
    </div>
   </div>
   <div id="result"></div>
  <br>
  <br>
</div>
</div>

</center>
</body>
</html>

<script>
function openForm() {
    document.getElementById("myForm").style.display = "block";
}

function closeForm() {
    document.getElementById("myForm").style.display = "none";
}

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
 setInterval(load_data, 500);
});


</script>