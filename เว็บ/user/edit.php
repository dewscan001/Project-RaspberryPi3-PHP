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
<title>แก้ไขข้อมูลของสมาชิก</title>
</head>

<body>
<script language="javascript">
function fncSubmit()
{

	if(document.form1.upass.value != document.form1.ConPass.value)
	{
		window.alert('กรุณาตรวจสอบรหัสผ่านอีกครั้ง');
		document.form1.ConPass.focus();		
		return false;
	}	

	document.form1.submit();
}
</script>

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
  <a href="profile.php" class="active w3-red"> ข้อมูลผู้ใช้งาน </a>
</div>
    </div>
    
<div class="cen">
<?php 
$sql = "SELECT * FROM user WHERE userid = '$id'"; 
$result=mysqli_query($link,$sql);
$row = mysqli_fetch_array($result);?>
 <center> 
 <br /><br />
 <h2>แก้ไขข้อมูลผู้ใช้งาน</h2>
<br /><br />
<form name="form1" action="saveedit.php" method="post" OnSubmit="return fncSubmit();">
<div style="max-width:1000px; width:100%;" class="table-responsive"> 
<table style="border-style:hidden;" class="table-bordered">
<tr> <th style="text-align:left; height:50px; width:35%;"> รหัสนักศึกษา: </th><td> <input class="form-control-static" type="text" pattern="[0-9]+-[0-9]{1}$" title="กรุณากรอกรหัสนักศึกษาตามรูปแบบนี้ : xxxxxxxxxxx-x" data-toggle="tooltip" value="<?php echo $row["userid"]; ?>"  name="uid" id="uid" style="width:100%; border-radius:5px;" disabled required/></td></tr>
<tr> <th style="text-align:left; height:50px; width:35%;"> ชื่อ-สกุล: </th><td> <input class="form-control-static" type="text" value="<?php echo $row["uname"]; ?>" pattern="[^0-9_]{1,}" data-toggle="tooltip" title="กรุณากรอกชื่อ-สกุลเป็นภาษาไทยและภาษาอังกฤษเท่านั้น"  name="uname" id="uname" style="width:100%; border-radius:5px;" required/></td></tr>
<tr> <th style="text-align:left; height:50px; width:35%;"></th> <th> <input id="cpass" type="checkbox" onClick="myFunction1()" value="1"> เปลี่ยนรหัสผ่าน:</th>
</table></div>

<div id="password" style="display:none; max-width:1000px; width:100%;" class="table-responsive">
<table style="border-style:hidden;" class="table-bordered">
<tr > <th style="text-align:left; height:50px; width:35%;"> รหัสผ่านเดิม: </th><td> <input class="form-control-static" type="password"  pattern="[a-zA-Z0-9]{4,10}"  title="กรุณากรอก รหัสผ่านเดิม" data-toggle="tooltip" name="opass" id="opass" style="width:100%; border-radius:5px;" /></td></tr>
<tr > <th style="text-align:left; height:50px; width:35%;"> รหัสผ่านใหม่: </th><td> <input class="form-control-static" type="password"  pattern="[a-zA-Z0-9]{4,10}"  title="กรุณากรอก รหัสผ่าน โดยใช้ตัวอักษรภาษาอังกฤษหรือตัวเลขจำนวน 4-10 ตัวอักษรเท่านั้น" data-toggle="tooltip" name="upass" id="upass" style="width:100%; border-radius:5px; " /></td></tr>
<tr > <th style="text-align:left; height:50px; width:35%;"> ยืนยันรหัสผ่านใหม่: </th><td> <input class="form-control-static" type="password"  pattern="[a-zA-Z0-9]{4,10}"  title="กรุณากรอก รหัสผ่านใหม่ อีกครั้ง" data-toggle="tooltip" name="ConPass" id="ConPass" style="width:100%; border-radius:5px; " /></td></tr></table>
</div>

<div id="personal" style="max-width:1000px; width:100%;" class="table-responsive">
<table style="border-style:hidden;" class="table-bordered">
<tr> <th style="text-align:left; height:50px; width:35%;"> </th> <th> ข้อมูลการติดต่อ: </th></tr>
<tr> <th style="text-align:left; height:50px; width:35%;"> เบอร์โทรศัพท์: </th><td> <input class="form-control-static" type="text" value="<?php echo $row["tel"]; ?>" pattern="[0-9]{10}" title="เบอร์โทรศัพท์มือถือ 10 หลัก" data-toggle="tooltip" name="tel" id="tel" style="width:100%; border-radius:5px;" required/></td></tr>
<tr> <th style="text-align:left; height:50px; width:35%;"> ที่อยู่: </th><td> <textarea class="form-control-static"   title="ที่อยู่ปัจจุบัน" data-toggle="tooltip" name="address" id="address" style="width:100%; border-radius:5px;" required/><?php echo $row["address"]; ?> </textarea></td></tr></div></table></div>
<br />
<button type="submit" class="btn btn-success w3-margin w3-padding w3-margin-left" > ยืนยันข้อมูล </button>
<button type="reset" class="btn btn-danger w3-margin w3-padding" onClick="myFunction3()"> รีเซ็ตข้อมูล </button> 
<a href="profile.php"><button type="button" class="btn open-button w3-padding w3-margin"> ย้อนกลับ </button></a>
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

function myFunction1() {
    var x = document.getElementById("password");
	var y = document.getElementById("cpass");
    if (y.value === "1") {
        x.style.display = "block";
		y.value = "0";
    } else {
        x.style.display = "none";
		y.value = "1";
    }
}


function myFunction3() {
    var x = document.getElementById("password");
	var b = document.getElementById("cpass")
	x.style.display = "none";
	b.value = "1";
  }

</script>