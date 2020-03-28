<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" href="css/w3.css" />
<link type="text/css" href="css/indewx.css" />

<title>หน้าหลัก</title>
</head>

<body>
<div id="in1"></div>
<div id="index" style="position: relative;
    text-align: center;
    color: white;">
<img src="img/intro/intro.jpg" width="100%" /> 
<div   style="position: absolute;
    top: 80%;
    left: 50%;
    transform: translate(-50%, -50%);">
<center> <a href="index1.php"><button style= "font-size:16px; padding:15px; border-width:5px; background-color:orange; border-radius:20px; color:white; cursor:pointer;">เข้าสู่เว็บไซต์</button></a>  </center></div></div>
<div id="indexmo" style="display:none;"> <center><img src="img/intro/intro.jpg" width="100%" />  <a href="index1.php"><button style= "font-size:16px; padding:15px; border-width:5px; background-color:orange; border-radius:20px; color:white;">เข้าสู่เว็บไซต์</button></a>  </center></div>
</body>
</html>

<script>
if(screen.width<=400){
	document.getElementById("index").style.display = "none";
	document.getElementById("indexmo").style.display = "block";
}
</script>