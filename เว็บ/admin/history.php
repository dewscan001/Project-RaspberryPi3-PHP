<?php 
include("../connect.php");
include('../datethai.php');
 session_start();
if(!isset($_SESSION["UserAdmin"]))
{
	echo "<meta http-equiv='refresh' content='0;url=index.php'>";
}
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


<title>ประวัติการใช้งานเครื่อง</title>
<style>
   @media (max-width:600px)
{
  #myBtn {
  display: none;
  position: fixed;
  bottom: 1%;
  right: 10px;
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
#non-printable { display: none; } 
#printable { display: block; } 
} 
</style>
</head>

<body>
<div class="w3-top">
 <div class="w3-hide-small w3-bar w3-wide w3-padding" style="background-image:url(../img/banner/banner.jpg); background-repeat:no-repeat; text-shadow:#FFF;">
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
  <a href="javascript:void(0);" class="icon" onclick="myFunction()">
    <i class="fa fa-bars" style="margin-right:10px;"></i>Menu</a>
	<div class="hide-topnav"> <br /><br /> </div>
  <a href="status.php" >สถานะการยืมปริญญานิพนธ์</a>
  <a href="member/index.php" >จัดการข้อมูลสมาชิก</a>
  <a href="book/index.php" >จัดการข้อมูลปริญญานิพนธ์</a>
  <a href="adminupdate/index.php"> จัดการผู้ดูแลระบบ</a>
  <a href="payment.php">ประวัติการชำระเงิน</a>
  <a href="" class="active w3-red w3-card">ประวัติการใช้งานเครื่อง</a>
</div>
</div>
         
<div class="cen">
<button onclick="topFunction()" id="myBtn" title="เลื่อนขึ้นด้านบน"><span class="glyphicon glyphicon-arrow-up"></span> Up</button>
<br /><br />
<?php 
include('../connect.php');
if(isset($_GET["date"])and($_GET["date"]!='')){
$output = '';
echo '<center>
	<h2>ประวัติการใช้งานเครื่อง (แสดงข้อมูลตามวันที่)</h2><br />
	<br />
  <form action="history.php" method="get">
   <p style="font-size:16px;"> ค้นหาตามวันที่ : <input class="form-control-static"  name="date" id="date" type="date" value="'.$_GET["date"].'" style="height:10px; border-radius:5px;"/>
  <button class="btn btn-success" type="submit"> ค้นหา </button> /
  <span style="margin-left:10px;"><a href="history.php"><button class="btn open-button" type="button"> แสดงข้อมูลทั้งหมด </button></a></span></p>
  </form>
  </center>
  <br /><br />'; 
 $sql = "SELECT DATE_FORMAT(memberhistory.hidate,'%d-%m-%Y'), memberhistory.*,user.uname FROM memberhistory INNER JOIN user ON user.userid = memberhistory.userid WHERE hidate='".$_GET["date"]."' ORDER BY memberhistory.hid DESC";
 }
else{
	$output = '';
	date_default_timezone_set("Asia/Bangkok");
	echo '<center>
	<h2>ประวัติการใช้งานเครื่อง (แสดงข้อมูลทั้งหมด) </h2> <br /><br />
  <form action="history.php" method="get">
  <p style="font-size:16px;">ค้นหาตามวันที่ : <input class="form-control-static" style="border-radius:5px; height:10px;" name="date" id="date" type="date"/>
  <button class="btn btn-success" type="submit"> ค้นหา </button></p>
  </form>
  </center>
  <br /><br />';
 $sql = "SELECT DATE_FORMAT(memberhistory.hidate,'%d-%m-%Y'), memberhistory.*,user.uname FROM memberhistory INNER JOIN user ON user.userid = memberhistory.userid ORDER BY memberhistory.hid DESC";}
$result=mysqli_query($link,$sql);
if(mysqli_num_rows($result) > 0)
{
 $output .= ' 
  <div style="width:100%;" class="table-responsive">          
  <table class="table table-hover">
    <thead>
      <tr>
        <th>วันเวลาที่ใช้งาน</th>
        <th>ชื่อผู้ใช้</th>
		<th>สถานะการทำงาน</th> 
		<th>รายละเอียด</th>
      </tr>
    </thead>
 ';
 while($row = mysqli_fetch_array($result))
 {
	 	$id=$row["hid"];
  		$sql1="SELECT * FROM memberhistory INNER JOIN book ON memberhistory.bookno = book.no WHERE hid = '".$id."'";
  		$result1=mysqli_query($link,$sql1);
 		$row1 = mysqli_fetch_array($result1);
	 	if($row["histatus"]!=0)
		{
			$a='คืน';
		}
		else if ($row["histatus"]!=1)
		{
			$a='ยืม';
		}
		if($row["bookno"]==NULL)
		{
			$style="red";
			$b="การทำงานไม่เสร็จสิ้น/การทำงานผิดพลาด";
		}
		else
		{
			$style="black";
			$b=nl2br($row1["bname"]);
		}
 		date_default_timezone_set("Asia/Bangkok");
		$hidate = DateThaiCut($row["hidate"]);
		 $output .= ' <tbody>
      <tr >
        <td style="width:200px; color:'.$style.';"> วันที่ '.$hidate.'<br>เวลา '.$row["hitime"].'</td>
        <td style="width:200px; color:'.$style.';">'.$row["uname"].'</td>
		<td style="width:100px; color:'.$style.';">'.$a.'</td>
		<td style="width:300px; color:'.$style.';text-align:left;">'.$b.'</td>
      </tr>
    </tbody>';
 }
 echo $output;
 echo "  </table> </div> ";
}
else
{
 echo '<center><br><br><h3>ไม่มีข้อมูลแสดง</h3><br><br></center>';
}
?>
     
     </div>
</body>
</html>

<script>
function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        document.getElementById("myBtn").style.display = "block";
    } else {
        document.getElementById("myBtn").style.display = "none";
    }
}

window.onscroll = function() {scrollFunction()};

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}

function myFunction() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}

</script>