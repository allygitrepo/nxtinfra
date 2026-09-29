<?php
/**
 * Created by PhpStorm.
 * User: mustansir
 * Date: 05/08/18
 * Time: 3:17 PM
 */
include("../dbcon.php");

$modulePath = "payment/";
$sql = "DELETE from sma_pending_task where id = " . $_GET['record_id'];
if(mysqli_query($con, $sql)) {
	echo "<script type='text/javascript'> document.location = '" . $_GET['url'] . "'; </script>";
	die();
}
else {
	echo mysqli_error($con);
}
?>