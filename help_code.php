<?php
	$sql = "SELECT * FROM `sma_menu` where 1 and source like '%".$help_code."%' ";
	$q2 	= mysqli_query($con, $sql);
	$r2 	= mysqli_fetch_array($q2);
	$help_link = $r2['help_link'];
?>	