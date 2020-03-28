
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
include("connect.php"); session_start(); session_cache_expire(20);
$sql = "SELECT * FROM user WHERE userid = '".$_POST["user"]."'";
$result = mysqli_query($link,$sql);
$objResult = mysqli_fetch_array($result);
if($objResult)
{
	$_SESSION["UserID"] = $objResult["userid"];
	$_SESSION["Username"] = $objResult["uname"];

   	echo "<meta http-equiv='refresh' content='0;url=user/report.php'>";	
	
}
else
{
	$user=$_POST["user"];
	$pass=$_POST["pass"];
	$sql = "SELECT * FROM admin WHERE username = '$user' AND password = '$pass' ";
	$result = mysqli_query($link,$sql);
	$objResult = mysqli_fetch_array($result);
	if($objResult)
	{
		$_SESSION["status"] = $objResult["astatus"];
		$_SESSION["UserAdmin"] = $objResult["name"];
   		echo "<meta http-equiv='refresh' content='0;url=admin/status.php'>";
 	}
	else
	{
		echo "<script>";
    	echo "alert('Username หรือ Password ไม่ถูกต้อง')";
		echo "</script>";
		echo "<meta http-equiv='refresh' content='0;url=index.php'>";
	}
}
?>
</body>
</html>
