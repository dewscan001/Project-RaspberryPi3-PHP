<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<?php
include("../connect.php"); session_start(); session_cache_expire(20);
$user=$_POST["user"];
$pass=$_POST["pass"];
$sql = "SELECT * FROM admin WHERE username = '$user' AND password = '$pass' ";
$result = mysqli_query($link,$sql);
$objResult = mysqli_fetch_array($result);
if($objResult)
{
	$_SESSION["status"] = $objResult["astatus"];
	$_SESSION["UserAdmin"] = $objResult["name"];
   echo "<meta http-equiv='refresh' content='0;url=status.php'>";
 }
else
{
	echo "<script>";
    echo "alert('Username หรือ Password ไม่ถูกต้อง')";
	echo "</script>";
	echo "<meta http-equiv='refresh' content='0;url=index.php'>";
}
?>
</body>
</html>