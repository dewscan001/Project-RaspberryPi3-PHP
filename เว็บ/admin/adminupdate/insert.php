<?php include("../../connect.php"); session_start();
if(!isset($_SESSION["UserAdmin"])||$_SESSION["status"]==1)
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

<style>
@media (min-width:600px){
 .cen{margin-top:80px; margin-left:10%; margin-right:10%; margin-bottom:50px;}}
@media (max-width:600px) {
	.cen{margin-top:120px; margin-bottom:10px; margin-left:5%; margin-right:5%;}
}
table, td, th {
    border: 1px solid black;
    padding: 5px;
	font-size:18px;
	text-align:center;}
	.button{
		background-color: #4CAF50; /* Green */
    color: white;
	width:200px; height:40px; font-size:16px; cursor:pointer;}
	.button:hover {
	background-color: #4CAF50; /* Green */
    color: white;
	width:200px; height:40px; font-size:16px; cursor:pointer;
    box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24), 0 17px 50px 0 rgba(0,0,0,0.19);
}
</style>
<title>เพิ่มข้อมูลของผู้ดูแลระบบ</title>
</head>

<body>
<script language="javascript">
function fncSubmit()
{

	if(document.form1.txtPassword.value != document.form1.txtConPassword.value)
	{
		window.alert('กรุณาตรวจสอบรหัสผ่านอีกครั้ง');
		document.form1.txtConPassword.focus();		
		return false;
	}	

	document.form1.submit();
}
</script>
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
<center> 
<br /><br />
<h2>เพิ่มข้อมูลผู้ดูแลระบบ</h2>
<br /><br />
 <div style="max-width:1000px; width:100%;" class="table-responsive"> 
<form name="form1" action="saveinsert.php" method="post" OnSubmit="return fncSubmit();">
<table style="border-style:hidden;" class="table-bordered">
<tr> <th style="text-align:left; height:50px; width:35%;"> Username หรือ ชื่อผู้ใช้: </th><td> <input class="form-control-static" type="text" pattern="[a-zA-Z0-9]{5,12}"  title="กรุณากรอก Username โดยใช้ตัวอักษรภาษาอังกฤษหรือตัวเลขจำนวน 5-12 ตัวอักษรเท่านั้น" data-toggle="tooltip" required name="id" id="id" style="width:100%; border-radius:5px;"/></td></tr>
<tr> <th style="text-align:left; height:50px; width:35%;"> รหัสผ่าน: </th><td> <input class="form-control-static" type="password" value="<?php echo $row["upass"]; ?>" pattern="[a-zA-Z0-9]{4,10}"  title="กรุณากรอก รหัสผ่าน โดยใช้ตัวอักษรภาษาอังกฤษหรือตัวเลขจำนวน 4-10 ตัวอักษรเท่านั้น" data-toggle="tooltip" name="upass" id="upass" style="width:100%; border-radius:5px;" required/>
<p style="float:left;"> <input type="checkbox" onclick="myFunction2()" > แสดงรหัสผ่าน </p> </td></tr>  
<tr> <th style="text-align:left; height:50px; width:35%;"> ชื่อ-สกุล: </th><td> <input class="form-control-static" type="text" equired="required" pattern="[^0-9_]{1,}" title="กรุณากรอกชื่อ-สกุลเป็นภาษาไทยและภาษาอังกฤษเท่านั้น" data-toggle="tooltip" name="name" id="name" style="width:100%; border-radius:5px;  "/></td></tr>
<tr> <th style="text-align:left; height:50px; width:35%;"> สถานะการเข้าถึง: </th><td> 
<select class="form-control-static" type="text" name="status" id="status" style="float:left; width:200px; border-radius:5px;"/>
<option value="0"> Root </option>
<option value="1"> Admin </option></select></td></tr>
</table>
<br />
<button type="submit" class="btn btn-success w3-margin w3-padding w3-margin-left"> ยืนยันข้อมูล </button>
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