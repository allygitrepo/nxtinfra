<?php 

	session_start();
	include('../dbcon.php');	
	include "../baseurl.php";

	$userid   	= $_SESSION['usrid'];
	$comid  = $_SESSION['comid'];
	
?>


<?php

	if(isset($_POST['sub2'])){

		$modulePath = "revenue_jv/";
		$rev_id 		= $_POST['rev_id'];
		//$doc_type 		= $_POST['doc_type'];
		$doc_type 		= 'RV';
		
		$sql 	= "select * from tally_journal_entry where doc_no = '$rev_id' and doc_type = '$doc_type' ";
		$q2 	= mysqli_query($con, $sql);
		$row_affected = mysqli_affected_rows($con);
		if($row_affected>0){
			$sql 	= "delete from tally_journal_entry where doc_no = '$rev_id' and doc_type = '$doc_type' ";
			$q2 	= mysqli_query($con, $sql);
		}	
		
		$sql 	= "select * from p2p_revenue_hdr where 1 and id = '$rev_id' ";
//echo $sql. "<BR>";	
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$comp_code			= $r2['project_code'];
		$invoice_date   	= date('d-m-Y', strtotime($r2['dated']));
		//$tally_narration 	 = $r2['tally_narration'];
		
		$tally_narration = " Being toll collection data as per ". $invoice_date . " data of toll";
		 
		$sql 	= "select * from company where 1 and comp_code = '$comp_code' ";
//echo $sql. "<BR>";	
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$company_id 		= $r2['comp_id'];
			
		$tot_amount = 0;
		$prev_budget_head = '';
		$prev_account_name = '';
		
		$sql = " SELECT * FROM `p2p_revenue_data` WHERE 1 and revenue> 0 and revenue_hdr_id = '$rev_id' ";
//echo $sql. "<BR>";
		$result1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$value="";
		$i = 1;
		while($row = mysqli_fetch_array($result1)){
			
			$amount 			= $row['revenue'] + $row['adjustment'];
			$tot_amount			= $tot_amount + $amount;
			$revenue_group_name = $row['revenue_group_name'];
			$dated				= $row['dated'];
						
			$sql = "SELECT * from `account_mst` where account_type = 'R' and company_id = '$company_id' and account_name = '$revenue_group_name' ";
//echo $sql. "<BR>";			
			$qry = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r2  = mysqli_fetch_array($qry);
			//$cash_account_name 		= $r2['account_name'];
			$account_name 		= $r2['account_tally_name'];
			$account_id  		= $r2['id'];
			
			$effect 			= "Dr";
			$record_type 		= "Revenue-P2P";
			$doc_no				= $rev_id;		
			//$doc_date			= date('d-m-Y', strtotime(date("Y-m-d")));
			$doc_date			= date('d-m-Y', strtotime($dated));
			$invoice_date		= date('d-m-Y', strtotime($dated));
			
			$effect				= $effect;
			$cheque_no			= '';

			if($revenue_group_name=='Cash'){
				$amount_half = $amount / 2;
				$tot_amount = $tot_amount - $amount_half;
				$account_name_new = 'Double Toll Collection AC';
				$sql 	= "select * from tally_journal_entry where doc_no = '$doc_no' AND doc_type = '$doc_type' 
								AND effect = 'Cr' AND account_name = '$account_name_new' ";
				$q2 	= mysqli_query($con, $sql);
				echo mysqli_error($con);
				$row_affected = mysqli_affected_rows($con);
				if($row_affected>0){
					$sql 	= "UPDATE tally_journal_entry SET amount = amount + '$amount_half' WHERE doc_no = '$doc_no' 
									AND doc_type = '$doc_type' 
									AND effect = 'Cr' AND account_name = '$account_name_new' ";
					 mysqli_query($con, $sql);	
					echo mysqli_error($con);
				}
				else {
					$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, account_id, account_name, budget_head, effect, amount, narration, cheque_no, address, gst_no, state, company_id, pan_no, mobile_no, status) 
					VALUES('$record_type', '$doc_type', '$doc_no', '$doc_date', '', '$invoice_date', '$account_type', '$account_id', '$account_name_new', '$account_name_new', 'Cr', '$amount_half', '$tally_narration', '', '$address', '', '$state', '$company_id', '', '', 'C') ";
					mysqli_query($con, $sql);
					echo mysqli_error($con);
				}
				
			}
			
//Changed on 23-08-2024			
			if($revenue_group_name=='e-Payment'){
				$amount_half = $amount / 2;
				$tot_amount = $tot_amount - $amount_half;
				$account_name_new = 'Double Toll Collection AC';
				
				$sql 	= "select * from tally_journal_entry where doc_no = '$doc_no' AND doc_type = '$doc_type' 
								AND effect = 'Cr' AND account_name = '$account_name_new' ";
				$q2 	= mysqli_query($con, $sql);
				echo mysqli_error($con);
				$row_affected = mysqli_affected_rows($con);
				if($row_affected>0){
					$sql 	= "UPDATE tally_journal_entry SET amount = amount + '$amount_half' WHERE doc_no = '$doc_no' 
									AND doc_type = '$doc_type' 
									AND effect = 'Cr' AND account_name = '$account_name_new' ";
					mysqli_query($con, $sql);	
					echo mysqli_error($con);
				}
				else {
					$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, account_id, account_name, budget_head, effect, amount, narration, cheque_no, address, gst_no, state, company_id, pan_no, mobile_no, status) 
					VALUES('$record_type', '$doc_type', '$doc_no', '$doc_date', '', '$invoice_date', '$account_type', '$account_id', '$account_name_new', '$account_name_new', 'Cr', '$amount_half', '$tally_narration', '', '$address', '', '$state', '$company_id', '', '', 'C') ";
					mysqli_query($con, $sql);
					echo mysqli_error($con);
				}
				
			}	
//Changed on 23-08-2024
			
			$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, account_id, account_name, budget_head, effect, amount, narration, cheque_no, address, gst_no, state, company_id, pan_no, mobile_no, status) 
				VALUES('$record_type', '$doc_type', '$doc_no', '$doc_date', '', '$invoice_date', '$account_type', '$account_id', '$account_name', '$account_name', '$effect', '$amount', '$tally_narration', '', '$address', '', '$state', '$company_id', '', '', 'C') ";
				mysqli_query($con, $sql);
				//$last_insert_id = mysqli_insert_id($con);
				echo mysqli_error($con);
			
 			
//echo $sql. "<BR>";				
		}

		$amount             = $tot_amount ;
		$record_type 		= "Revenue-P2P";
		$doc_no				= $rev_id;		
		$doc_date			= date('d-m-Y', strtotime($dated));
			
		$account_type_a	    = 'V';
		$val_type_a 	    = 'V';
		$account_type		= $account_type_a;
		$val_type			= $val_type_a;
		
		$sql = "select * from `account_mst` where account_type = 'S' and  company_id = '$company_id' ";
//echo $sql. "<BR>";				
		$q2 	  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$sales_name = $r2['account_tally_name'];
		$sales_id   = $r2['id'];
			
		$account_id			= $sales_id;
		$account_name       = $sales_name;
		$effect				= 'Cr';
		 
		$sql = "INSERT INTO tally_journal_entry(record_type, doc_type, doc_no, doc_date, supp_invoice_no, supp_invoice_date, account_type, val_type, account_id, account_name, budget_head, effect, amount, narration, cheque_no, address, gst_no, state, company_id, status) 
		VALUES('$record_type', '$doc_type', '$doc_no', '$doc_date', '$invoice_no', '$invoice_date', '$account_type', '$val_type', '$account_id', '$account_name', '', '$effect', '$amount', '$tally_narration', '', '', '', '$state', '$company_id' , 'C' ) ";
//echo $sql. "<BR>";		
		mysqli_query($con, $sql);
		echo mysqli_error($con);

		$sql = " UPDATE p2p_revenue_hdr SET tally_status='C', tally_created_by = '$userid', tally_created_date = now() , tally_narration = '$tally_narration' WHERE id = '$rev_id' ";
		mysqli_query($con, $sql);
//echo $sql. "<BR>";
//exit();
		
		$value = "<script>window.location.href='revenue_jv.php?sub=edit&revenue_hdr_id=$rev_id&IN=in';</script>";
		
		echo $value;
		
	}

?>

