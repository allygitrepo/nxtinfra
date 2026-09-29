<?php

include("../dbcon.php");

$modulePath = "advance/";
$sql = "DELETE FROM file_uploads WHERE id = " . $_GET['id'];
if(mysqli_query($con, $sql)) {
	echo "<script type='text/javascript'> document.location = '" . $_GET['url'] . "'; </script>";
	die();
}
else {
	echo mysqli_error($con);
}
?>