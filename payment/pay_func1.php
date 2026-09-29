<?php session_start();
	include('../dbcon.php');
	$comid  = $_SESSION['comid'];	
?>

<?php
	
    if(isset($_POST['sub11'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value = "<select class='form-control' id='account_Name' name='account_name' >
						<option value=''>Select</option>";
	
		if ($id == 'S'){
			$sql = "SELECT * FROM sma_party_mst ORDER BY party_name ASC ";
			$q2  = mysqli_query($con, $sql);
				while($r2 = mysqli_fetch_object($q2)){
				$party_name = $r2->party_name;
				$id = $r2->id;
				$value .= "<option value='".$id."'>".$party_name."</option>";
			};
		}
		else if ($id == 'A'){
			$sql = "SELECT * FROM account_mst ORDER BY account_name ASC ";
			$q2  = mysqli_query($con, $sql);
				while($r2 = mysqli_fetch_object($q2)){
				$account_name = $r2->account_name;
				$id = $r2->id;
				$value .= "<option value='".$id."'>".$account_name."</option>";
			};
		}
		
		$value .= "</select>";
		
//$value = $sql;
		echo $value;
	
	}
	
?>