<?php
	include("../dbcon.php");
	
	$con = mysqli_connect("localhost","athaangp2p","12345","athaangp2p");
	
	$company_id = $_POST["company_id"];
	$product_id = $_POST["product_id"];
	$column		= $_POST["column"];
	$editval	= $_POST["editval"];
	
	$sql = "UPDATE `sma_product_cost_center` set $column = $editval WHERE company_id = '$company_id' and product_id = '$product_id' ";
	$re = mysqli_query($sql, $con);
	$err = mysqli_error($con);
	
/* $val=$_POST["editval"];
	if($val>00){echo "<h1>Value should not be more than 100!!!!!</h1><br>Please try again.";}
 */
 
$flname = 'ravitext.txt';
$fp = fopen($flname, 'w');
fwrite($fp,$err."\n" );
fwrite($fp,$sql."\n" );
fclose($fp);

?>
