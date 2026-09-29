<?php

	session_start();
	include("../dbcon.php");
	$user_name		= $_SESSION['user'];
	
	$sql = " SELECT * FROM `sma_menu` where 1 and status = 'Y' and source like '%$pgname%' " ;
	//echo $sql. "<BR>";	
	$rs1 = mysqli_query($con, $sql);
	$r = mysqli_fetch_object($rs1);
 	$menuid 	= $r->id;
 	$main_menu_id    = $r->menu_id;
	
	$viewonly	= $readonly["$menuid"];
	$addonly	= $writeonly["$menuid"];
	$dashboardonly	= $dashboard["$menuid"];
//echo $viewonly. '<<viewonly>> '.$addonly. "<<< ADDonly>>";
//print_r($viewonly);

    $sql = "Select menu_name from sma_main_menu where id='$main_menu_id'";
    $result = mysqli_query($con,$sql);
    $r2=mysqli_fetch_assoc($result);
    $main_menu=$r2['menu_name'];
    
    $sql1 = "Select sub_menu_name from sma_menu where id='$menuid'";
    $result1 = mysqli_query($con,$sql1);
    $r3=mysqli_fetch_assoc($result1);
    $sub_menu=$r3['sub_menu_name'];

?>