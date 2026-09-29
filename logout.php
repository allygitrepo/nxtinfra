<?php session_start();

include "dbcon.php";
$mobtab = $_SESSION['mob'];

$user   	= $_SESSION['user'];
$tday_date 	= date("Y-m-d");
$sql = "SELECT * FROM `user_login` where id = (SELECT max(id) FROM `user_login` where userid = '$user' and tdate = '$tday_date' );";
$rs = mysqli_query($con, $sql);
echo mysqli_error($con);
$rw = mysqli_fetch_array($rs);
$prev_login_id = $rw['id'];

$sql = " UPDATE user_login set logout_date = now() where id = '$prev_login_id' ";
mysqli_query($con, $sql);

unset($_SESSION['user']);

session_destroy();
if(empty($mobtab)){
	echo '<script>window.location.href="index.php";</script>';
}
else {
	echo '<script>window.location.href="indexm.php";</script>';
}	
?>