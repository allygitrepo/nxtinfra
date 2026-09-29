<?php
/**
 * Created by PhpStorm.
 * User: mustansir
 * Date: 05/08/18
 * Time: 3:17 PM
 */
include("../dbcon.php");
include("../baseurl.php");

$modulePath = "supp_invoice/";
$sql = "DELETE FROM file_uploads WHERE id = " . $_GET['id'];
if(mysqli_query($con, $sql)) {
	echo "<script type='text/javascript'> document.location = '" . $_GET['url'] . "&fupd=987'; </script>";
	die();
	$a='';
}
else {
	echo mysqli_error($con);
}
?>