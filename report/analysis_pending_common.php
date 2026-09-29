<?php
		$approver_1			= $row['approver_1'];
		$approver_2			= $row['approver_2'];
		$approver_3			= $row['approver_3'];
		$approver_4			= $row['approver_4'];
		$approver_5			= $row['approver_5'];
		$approver_6			= $row['approver_6'];
		$approver_7			= $row['approver_7'];
		$approver_8			= $row['approver_8'];
		$approver_9			= $row['approver_9'];
				
		$approver_1_status	= $row['approver_1_status'];
		$approver_2_status	= $row['approver_2_status'];
		$approver_3_status	= $row['approver_3_status'];
		$approver_4_status	= $row['approver_4_status'];
		$approver_5_status	= $row['approver_5_status'];
		$approver_6_status	= $row['approver_6_status'];
		$approver_7_status	= $row['approver_7_status'];
		$approver_8_status	= $row['approver_8_status'];
		$approver_9_status	= $row['approver_9_status'];
				
		if($approver_1_status == 'Submitted'){
			$pending_with  = $approver_1;	
		}
		if($approver_2_status == 'Submitted'){
			$pending_with  = $approver_2;	
		}
		if($approver_3_status == 'Submitted'){
			$pending_with  = $approver_3;	
		}
		if($approver_4_status == 'Submitted'){
			$pending_with  = $approver_4;	
		}
		if($approver_5_status == 'Submitted'){
			$pending_with  = $approver_5;	
		}
		if($approver_6_status == 'Submitted'){
			$pending_with  = $approver_6;	
		}
		if($approver_7_status == 'Submitted'){
			$pending_with  = $approver_7;	
		}
		if($approver_8_status == 'Submitted'){
			$pending_with  = $approver_8;	
		}
		if($approver_9_status == 'Submitted'){
			$pending_with  = $approver_9;	
		}
		
		if($module=='GR'){
			$grn_approver			= $row['grn_approver'];
			$grn_approval_status	= $row['grn_approval_status'];			
			if($grn_approval_status == 'Submitted'){
				$pending_with  = $grn_approver;
			}
			else {
				//$pending_with ='';
			}	
		}
?>		