<?php
	include "dbcon.php";
	
	$sql = mysqli_query($con, $s);
	while($r = mysqli_fetch_object($sql)){
		$readY 	= $r->readonly;
		$writeY = $r->writeonly;
		$editY 	= $r->editonly;
		$printY = $r->printonly;
		$menuY  = $r->menuonly;
	}
?>	