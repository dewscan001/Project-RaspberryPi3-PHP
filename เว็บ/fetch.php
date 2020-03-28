<?php
//fetch.php
include("connect.php");
$output = '';
if(isset($_POST["query"]))
{
 $search = mysqli_real_escape_string($link, $_POST["query"]);
 $sql = "
  SELECT * FROM book
  WHERE book.bname LIKE '%".$search."%' OR book.wname LIKE '%".$search."%'  ORDER BY book.no ASC
 ";
}
else
{
$sql = "SELECT * FROM book ORDER BY no ASC";

}
$result=mysqli_query($link,$sql);
if(mysqli_num_rows($result) > 0)
{
 $output .= '
   <div style="width:100%;" class="table-responsive">    
  <table class="table table-hover">
    <thead>
      <tr>
        <th width="50px"> ลำดับที่ (No.) </th>
        <th width="250px"> ชื่อปริญญานิพนธ์</ th>
        <th width="200px"> ชื่อผู้จัดทำ </th>
		<th width="200px"> สถานะ </th>
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
		$data=nl2br($row["wname"]);
		$book=nl2br($row["bname"]);
	   	$i=$row["bstatus"];
		if($i==0)
		{
			$aa='ถูกยืมโดย <br>'.$row1["uname"].' ';
		}
		else if($i==1)
		{
			$aa="อยู่ในตู้เก็บปริญญานิพนธ์";
		}
   $output .= '
	 <tbody>
      <tr>
        <td>'.$row["no"].'</td>
        <td style="text-align:left;">'.$book.'</td>
		<td>'.$data.'</td>
		<td>'.$aa.'</td>
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