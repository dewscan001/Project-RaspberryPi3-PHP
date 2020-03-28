<?php
//fetch.php
include('../../connect.php');
$output = '';
if(isset($_POST["query"]))
{
 $search = mysqli_real_escape_string($link, $_POST["query"]);
 $sql = "
  SELECT * FROM book
  WHERE bname LIKE '%".$search."%' OR wname LIKE '%".$search."%'
 ";
}
else
{
$sql = "SELECT * FROM book";

}
$result=mysqli_query($link,$sql);
if(mysqli_num_rows($result) > 0)
{
 $output .= '
   <div style="width:100%;" class="table-responsive">    
  <table class="table table-hover">
    <thead>
      <tr>
        <th width="200px">รหัส RFID </th>
        <th width="300px">ชื่อปริญญานิพนธ์</th>
        <th width="200px">ชื่อผู้จัดทำ</th>
		<th width="50px"> แก้ไข </th>
		<th width="50px"> ลบ </th>
      </tr>
    </thead>
 ';
 while($row = mysqli_fetch_array($result)){
	 if($row["bname"]!="")
	 {
 $id=$row["rfid"];
  $sql1="SELECT * FROM status INNER JOIN user ON user.userid = status.userid WHERE wstatus = '0' AND rfid = '".$id."'";
  	$result1=mysqli_query($link,$sql1);
 	$row1 = mysqli_fetch_array($result1);
 	$userid=$row1["userid"];
	$uname=$row1["uname"];
	   $i=$row["bstatus"];
		if($i==0)
		{
			$aa='ถูกยืมโดย '.$row1["uname"].' ';
		}
		else if($i==1)
		{
			$aa="อยู่ในตู้เก็บปริญญานิพนธ์";
		}
		$book=nl2br($row["bname"]);
		$data=nl2br($row["wname"]);
   $output .= '
	 <tbody>
      <tr>
        <td>'.$row["rfid"].'</td>
        <td style="text-align:left;"><a style="color:blue;" href="#" title="สถานะการยืม-คืนปริญญานิพนธ์" data-toggle="popover" data-content="'.$aa.'">'.$book.'</a></td>
		<td>'.$data.'</td>
		<td><a href="edit.php?id='.$row["rfid"].'" title="คลิกเพื่อแก้ไขข้อมูล"><button type="button" class="btn btn-default btn-sm" style="color:blue;">
          <span class="glyphicon glyphicon-edit"></span> แก้ไข
        </button></a></td>
		 <td><a onclick="return myFunction()" href="delete.php?id='.$row["rfid"].'" title="คลิกเพื่อลบข้อมูล"><button type="button" class="btn btn-default btn-sm" style="color:red;">
          <span class="glyphicon glyphicon-trash"></span> ลบ
        </button></a></td>
      </tr>
    </tbody>';
 }}
 echo $output;?>
 </table>
 <?php
}
else
{
 echo '<center><br><br><h3>ไม่มีข้อมูลแสดง</h3><br><br></center>';
}
?>
<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover(); 
});

function myFunction() {
    if (confirm("ต้องการลบข้อมูลหรือไม่!?")==true) {
      return true;
    } else {
       return false;
    }
}
</script>