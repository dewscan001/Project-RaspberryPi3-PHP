<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
//fetch.php
include('../connect.php');
include('../datethai.php');
$output = '';
if(isset($_POST["query"]))
{
 $search = mysqli_real_escape_string($link, $_POST["query"]);
  $sql = "SELECT user.uname,status.userid,book.bname,status.sdate,status.enddate , DATEDIFF(curdate(),status.enddate) FROM ((status INNER JOIN book ON status.rfid = book.rfid) INNER JOIN user ON user.userid = status.userid) WHERE status.wstatus='0' AND (uname LIKE '%".$search."%' OR bname LIKE '%".$search."%') ORDER BY status.statusid DESC
 ";
}
else
{
 $sql = "SELECT user.uname,status.userid,book.bname,status.sdate,status.enddate , DATEDIFF(curdate(),status.enddate) FROM ((status INNER JOIN book ON status.rfid = book.rfid) INNER JOIN user    ON user.userid = status.userid) WHERE status.wstatus='0'ORDER BY status.statusid DESC";

}
$result=mysqli_query($link,$sql);
if(mysqli_num_rows($result) > 0)
{
 $output .= ' 
  <div style="width:100%;" class="table-responsive">          
  <table class="table table-hover">
    <thead>
      <tr>
        <th width="250px">ชื่อปริญญานิพนธ์</th>
        <th width="200px">ชื่อผู้ยืม</th>
        <th width="160px">วันที่ยืม</th>
        <th width="160px">กำหนดส่งคืน</th>
      </tr>
    </thead>
 ';
 while($row = mysqli_fetch_array($result))
 {
	    $id = $row["userid"];
		$id=$row["userid"];
		$name=$row["uname"];
		date_default_timezone_set("Asia/Bangkok");
		if($row["DATEDIFF(curdate(),status.enddate)"]>0)
		{
			$style = 'color:red;';
		}
		else
		{
			$style = "color:black;";
		}
		$book=nl2br($row["bname"]);
		
		//แปลงให้เป็นวันที่แบบภาษาไทย
		$sdate = DateThaiFull($row["sdate"]);
		$enddate = DateThaiFull($row["enddate"]);
		 $output .= ' <tbody>
      <tr >
        <td style="text-align:left; '.$style.'">'.$book.'</td>
		<td style='.$style.'><a href="report.php?id='.$id.'&&name='.$name.'" style="color:blue;" title="คลิกเพื่อดูประวัติการยืม-คืน" data-toggle="tooltip">'.$row["uname"].'</a></td>
        <td style='.$style.'>'.$sdate.'</td>
        <td style='.$style.'>'.$enddate.'</td>
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
</body>
</html>
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>