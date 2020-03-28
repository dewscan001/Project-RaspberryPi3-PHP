<?php
include("../connect.php");
$sql = 'DELETE FROM status WHERE statusid=$_GET["id"]';

if (mysqli_query($link, $sql)) {
    echo "Record deleted successfully";
} else {
    echo "Error deleting record: " . mysqli_error($link);
}