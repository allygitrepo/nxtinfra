<?php
/**
 * Created by PhpStorm.
 * User: mustansir
 * Date: 05/08/18
 * Time: 3:17 PM
 */
include("../dbcon.php");

$modulePath = "dms/";
$sql = "DELETE FROM my_documents_files WHERE id = " . $_GET['id'];
if(mysqli_query($con, $sql)) {
	echo "<script type='text/javascript'> document.location = '" . $_GET['url'] . "'; </script>";
	die();
}
else {
	echo mysqli_error($con);
}
?>