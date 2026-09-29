<?php session_start();
	include('../dbcon.php');
	include "../baseurl.php";

	echo "  ";

?>

<?php

	if(isset($_POST['sub13'])){
		
		$modulePath 	= "supp_invoice/";

		$account_id 	= $_POST['id'];
		$amount_dr 		= $_POST['amount_dr'];
		
		$sql = "SELECT * FROM account_mst where (account_type = 'E' or account_type = 'D' or account_type = 'A') and id = '$account_id' ";
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r3 	= mysqli_fetch_array($result);
		$tds_percentage = $r3['tds_percentage'];
		if($tds_percentage > 0){
			
			$tds_amount = $amount_dr * $tds_percentage / 100;
			//echo $tds_amount;
			echo '';
?>			
			<input type="text" class="form-control amountA " id="amountA" autocomplete="off" style="text-align:right;" name="amount" value="<?php echo $tds_amount ?>" >
		
			<input type="text" class="form-control amountAB " id ="amountAB" style="text-align:right;" name="amount" value="0" >
<?php			
		}
		else {
?>			
			<input type="text" class="form-control amountA" id="amountAB" style="text-align:right;" name="amount" value="123" >
<?php
		}	//id="amountA"

	}

?>		