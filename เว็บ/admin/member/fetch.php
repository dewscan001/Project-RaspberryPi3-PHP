<?php
//fetch.php
include('../../connect.php');
$output = '';
if(isset($_POST["query"]))
{
 $search = mysqli_real_escape_string($link, $_POST["query"]);
 $sql = "
  SELECT * FROM user
  WHERE uname LIKE '%".$search."%' OR userid LIKE '%".$search."%' ORDER BY userid ASC 
 ";
}
else
{
$sql = "SELECT * FROM user ORDER BY userid ASC ";

}
$result=mysqli_query($link,$sql);
if(mysqli_num_rows($result) > 0)
{
 $output .= ' 
  <div style="width:100%; " class="table-responsive">
   <table class="table table-hover">
    <thead>
      <tr>
        <th width="200px">รหัสนักศึกษา</th>
        <th width="350px">ชื่อ-สกุล</th>
        <th width="50px">แก้ไข</th>
		<th width="50px">ลบ</th>
      </tr>
    </thead>
 ';
 while($row = mysqli_fetch_array($result))
 {
	 if($row["userid"]!=0)
	 {
	    $id = $row["userid"];
		$name = $row["uname"];
  $output .= '
		<tbody>
      <tr>
        <td>'.$row["userid"].'</td>
        <td><a href="../report.php?id='.$id.'&&name='.$name.'" style="color:blue;" data-toggle="tooltip" title="คลิกเพื่อดูประวัติการยืม-คืน"">'.$row["uname"].'</a></td>
		 <td><a href="edit.php?id='.$id.'" title="คลิกเพื่อแก้ไขข้อมูล""><button type="button" class="btn btn-default btn-sm" style="color:blue;">
          <span class="glyphicon glyphicon-edit"></span> แก้ไข
        </button></a></td>
		 <td><a onclick="return myFunction()" href="delete.php?id='.$id.'&&fid='.$row["fingerid"].'" title="คลิกเพื่อลบข้อมูล"><button type="button" class="btn btn-default btn-sm" style="color:red;">
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
    $('[data-toggle="tooltip"]').tooltip();   
});

function myFunction() {
	
    if (confirm("ต้องการลบข้อมูลหรือไม่!?")==true) {
      return true;
    } else {
       return false;
    }
}
</script>