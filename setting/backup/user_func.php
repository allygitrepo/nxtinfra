<?php session_start();
	include('../dbcon.php');
//	$baseurl = "http://localhost:80/hc_template/";    //dev url
	
	include "../baseurl.php";
	
?>

<?php
		
  if(isset($_POST['sub1'])){
		$days = $_POST['id'];
		$value  = '';
		
		$expired_date = Date('Y-m-d', strtotime("+$days days"));
		
		if($expired_date=='01-01-1970'){
			$expired_date='';
		}
		
		$value = '<input type="text" class="form-control" readonly name="password_expired_date" value="'. date('d-m-Y', strtotime($expired_date)) . '" >';
		
		echo $value;
		
	}

?>