<?php session_start();
	include('../dbcon.php');
	
	include('../baseurl.php');

	if(!isset($_SESSION['user'])){ 
		echo '<script>alert("Session is expired...");</script>';
		$baseurl1= $baseurl.'index.php';
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
	}
	
//	include('../header.php');

	$userid   			= $_SESSION['usrid'];		
	
	$finance_year = $_SESSION['finance_year'];
		
?>

<?php
	  if(isset($_POST['sub11'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value ='<select class="form-control" name="budget_name" id="budget_name" required="true" onchange="getbudgetheada(this.value)"  >
									<option value=""> Select </option>';

	    $sql = "select a.id, a.project, a.budget_category, b.name from sma_budget a, sma_budget_name b where project = '$id' and b.id = a.budget_name order by project";
		$q2  = mysqli_query($con, $sql);
		
		while($r2 = mysqli_fetch_object($q2)){
			$name = $r2->name;
            $id = $r2->id;
            $value .= "<option value='".$id."'>".$name."</option>";
        };
		$value .= '</select>';

		$value=$sql;

        echo $value;
    }
	
    if(isset($_POST['sub1'])){
    
        $id = $_POST['id'];
		//$company_id 	= $_POST['company_id'];
		//$comp_vertical 	= $_POST['comp_vertical'];
		if($_POST['id'] == ''){$id = '';}
	
		$value = '';
		$value = '<label for="itemName" class="control-label">Budget Group </label>';
		$value .='<select class="form-control" name="budget_name" id="budget_NAME" required="true" onchange="getcatbudget(this.value)"  >
			<option value="" selected > Select </option>';

		$sql = "SELECT * from sma_budget where budget_name = '$id' ";		//and project = '$company_id' 
		$q2  = mysqli_query($con, $sql);
		
		while($r2 = mysqli_fetch_object($q2)){
			$name = $r2->budget_head;
            $id = $r2->id;
            $value .= "<option value='".$id."'>".$name."</option>";
        };
		$value .= '</select>';

//$value=$sql;
		
        echo $value;
    }

    if(isset($_POST['sub1A'])){
    
        $id = $_POST['id'];
		$company_id 	= $_POST['company_id'];
		//$comp_vertical 	= $_POST['comp_vertical'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value = '<label for="itemName" class="control-label">Budget Group </label>';
		$value .='<select class="form-control" name="budget_name" id="budget_name" required="true" onchange="getcatbudgett(this.value)"  >
			<option value=""> Select </option>';

		$sql = "SELECT * from sma_budget where budget_name = '$id' and project = '$company_id' ";		
		$q2  = mysqli_query($con, $sql);
		
		while($r2 = mysqli_fetch_object($q2)){
			$name = $r2->budget_head;
            $id = $r2->id;
            $value .= "<option value='".$id."'>".$name. "</option>";
        };
		$value .= '</select>';

//$value=$sql;
		
        echo $value;
    }

	
  if(isset($_POST['sub2'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		
		$value = '';
		$value ='<select class="form-control" name="budget_head" id="budget_Head" >
									<option value=""> Select </option>';

	    $sql = "select a.id, a.project, a.budget_category, b.category from sma_budget a, sma_budget_category b where b.id = a.budget_category and budget_name = '$id' ";
		//in (select budget_name from sma_budget where id = '$id') group by project, category";
		$q2  = mysqli_query($con, $sql);
		
		while($r2 = mysqli_fetch_object($q2)){
			$name = $r2->category;
            $id = $r2->id;
            $value .= "<option value='".$id."'>".$id.' '.$name."</option>";
        };
		$value .= '</select>';
//$value = $sql;
		echo $value;
		
	}

    if(isset($_POST['sub3'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value ='<select class="form-control" name="itemName" id="itemName" required="true" onchange="getunit1(this.value)" >
									<option value=""> Select </option>';
	    $sql = "SELECT * FROM sma_product where `product_group` = '$id' ORDER BY name ASC";
//echo $sql. "<BR>";		
		$q2  = mysqli_query($con, $sql);
//		$rowcount=mysqli_num_rows($q2);
//$value .= $rowcount;
			while($r2 = mysqli_fetch_object($q2)){
			$name = $r2->name;
            $id = $r2->id;
            $value .= "<option value='".$id."'>".$name."</option>";
        };
		$value .= '</select>';

//$value=$sql;

        echo $value;
    }

   if(isset($_POST['sub33'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value ='<select class="form-control" name="itemName" id="itemName" required="true" onchange="getunit2(this.value)" >
									<option value=""> Select </option>';
	    $sql = "SELECT id, name FROM sma_product where `group` = '$id' ORDER BY name ASC";
		$q2  = mysqli_query($con, $sql);
//		$rowcount=mysqli_num_rows($q2);
//$value .= $rowcount;
			while($r2 = mysqli_fetch_object($q2)){
			$name = $r2->name;
            $id = $r2->id;
            $value .= "<option value='".$id."'>".$name."</option>";
        };
		$value .= '</select>';

//$value=$sql;

        echo $value;
    }
		
	if(isset($_POST['sub4'])){
    
        $id = $_POST['id'];
		
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$sql="SELECT * FROM sma_product where id = '$id' ";							

		$q2  = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_object($q2);
			$uom 		= $r2->uom;
			$gst_type   = $r2->gst_type;
			$tolerance_level = $r2->tolerance_level;
			$po_threashold 	 = $r2->po_threashold;
			//$account_name 	 = $r2->account_name;
			$account_name 	 = '';
 		
		
		$value = $uom.'##'.$account_name.'##'.$tolerance_level.'##'.$po_threashold;
		
		//$value = '<input type="text" class="form-control" name="itemunits" id="itemUnits" readonly value = "'.$uom.'" > ';
		
//$value.=$sql;
        echo $value;
    }

    if(isset($_POST['sub44'])){
    
        $id = $_POST['id'];
		$company_id    = $_POST['company_id'];
		
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value ='<select class="form-control" name="itemunits" id="itemUnits" readonly >
									<option value=""> Select </option>';

	    $sql = "SELECT * FROM sma_product where id = '$id' ORDER BY name ASC";
		$q2  = mysqli_query($con, $sql);
//		$rowcount=mysqli_num_rows($q2);
//$value .= $rowcount;
			$r2 = mysqli_fetch_object($q2);
			$uom = $r2->uom;
			$description = $r2->description;
			$gst_type = $r2->gst_type;
            
		$value = '<input type="text" class="form-control" name="itemunits" id="itemUnits" readonly value = "'.$uom.'" > ';
		
//$value.=$sql;

		$sql="SELECT * FROM `gst_mst` where id = '$gst_type' ";
//echo $sql;						
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$igst = $r2['igst'];
		$igst_id = $r2['id'];
		
        echo $uom. '-' . $description.'-'.$igst.'-'.$igst_id;
		
    }

    if(isset($_POST['sub5'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		$value ='<select class="form-control" name="location" id="LOCATION" required="true" onchange="getdelvaddr(this.value)" >
									<option value=""> Select </option>';

	    $sql = "select * from sma_location where loc_comp_id = '$id' order by loc_name";
		$q2  = mysqli_query($con, $sql);
//		$rowcount=mysqli_num_rows($q2);
//$value .= $rowcount;
			while($r2 = mysqli_fetch_object($q2)){
			$loc_name = $r2->loc_name;
            $id = $r2->id;
            $value .= "<option value='".$id."'>".$loc_name."</option>";
        };
		$value .= '</select>';

//$value=$sql;

        echo $value;
    }

    if(isset($_POST['sub6'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
	    $sql = "select * from sma_location where id = '$id' ";

		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_object($q2);
		$loc_addr = $r2->loc_addr1;
		if(!empty($r2->loc_addr2)){
			$loc_addr .= $r2->loc_addr2.',';
		}
		if(!empty($r2->loc_addr3)){
			$loc_addr .= $r2->loc_addr3.',';
		}
		if(!empty($r2->loc_city)){
			$loc_addr .= $r2->loc_city.',';
		}
		if(!empty($r2->loc_pincode)){
			$loc_addr .= '-'.$r2->loc_pincode;
		}
?>		
		<div class="col-md-6">
			<label class="control-label">Delivery Address</label>
			<textarea rows="2" class="form-control" id="delivery_address" name="delivery_address" <?php echo $readonly; ?> ><?= $loc_addr;?></textarea>
		</div>
							
<?php	
//$value=$sql;

//        echo $value;
    }
	
    if(isset($_POST['sub7'])){
    
        $id = $_POST['id'];
		$company_id = $_POST['id'];
		$po_type = $_POST['po_type'];
		if($_POST['id'] == ''){$id = '';}
//getqref(this.value);getloc(this.value);		
		$value = '';
		$sqla = '';
//echo $po_type."<BR>";
		
/* 		$sqla = "AND id in ( SELECT supplier_id 
					FROM sma_purchase_req a , sma_purchase_req_items b
					WHERE 1 AND a.id = b.purchase_req_id AND a.company_id = '$company_id' 
					AND a.status = 'Completed'
					AND b.po_quantity < b.quantity )";
		 */
		 //and party_kyc = 'Y'
$sql = " select * from sma_party_mst where 1  ". $sqla ;

$sql .= " order by party_name ";

//echo $sql; //getapproval(this.value);
		$value ='<select class="form-control" name="to_supplier" id="to_supplier" onchange="get_taxstatus(this.value);" required >
									<option value=""> Select ..</option>';

		$q2  = mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_object($q2)){
			$party_name = $r2->party_name;
            $id = $r2->id;
            $value .= "<option value='".$id."'>".$party_name."</option>";
        };
		
		$value .= '</select>';

//$value = $sql;

        echo $value;
		
    }
	
    if(isset($_POST['sub8'])){
    
        $id = $_POST['id'];
		$location = $_POST['location'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';

	    $sql = " select * from sma_location where id = '$location' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$loc_gst_no = $r2->loc_gst_no;
        $loc_gst_no_twodgt = substr($loc_gst_no,0,2);
		
        $sql = " select * from sma_party_mst where id = '$id' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$party_gst_number = $r2->party_gst_number;
		$party_gst_number_twodgt = substr($party_gst_number,0,2);
		
		if($party_gst_number_twodgt==$loc_gst_no_twodgt){
			$supplier_location_dis = 'Local';
			$supplier_location = 'L';
		}
		else {
			$supplier_location_dis = 'Out of State';
			$supplier_location = 'Out of State';
		}	
		$value .= '<input type="text" class="form-control" readonly value="'. $supplier_location_dis . '" >';
		$value .= '<input type="hidden" id="supplier_location" name="supplier_location" value="'. $supplier_location . '" >';
		
//$value = $sql;

        echo $value;
		
    }
		
	if(isset($_POST['sub9'])){

		$modulePath = "purchase_order_entry/";
	
		$value ='';
        $po_id = $_POST['po_id'];
		$srno  = $po_id;
		if($_POST['po_id'] == ''){$po_id = '';}
		
		$mode		 		= $_POST['mode'];
		$status 			= $_POST['status'];
		$statusap			= $_POST['statusap'];
		$remarks 			= $_POST['remarks'];
		$approved 			= $_POST['approved'];
		//RAVI $approverC			= $_POST['approver'];
		$approver 			= $_POST['approver'];

		$company			= $_POST['company'];		
		$user   			= $_SESSION['user'];
		$userid   			= $_SESSION['usrid'];
		$user_name_by 		= $_SESSION['user_name_by'];
 
		$sql = " select * from sma_purchase_order where id = '$po_id' "; 
		$q2	=	mysqli_query($con, $sql);
		$r2 =	mysqli_fetch_array($q2);
		$approval_memo_ref	= $r2['approval_memo_ref'];	
		$subject			= $r2['subject'];
		$po_type			= $r2['po_type'];
		$to_supplier		= $r2['to_supplier'];
		$company_id 		= $r2['project'];
		$draft_by 			= $r2['draft_by'];
		$approver_1 		= $r2['approver_1'];
		$approver_2 		= $r2['approver_2'];
		$approver_3 		= $r2['approver_3'];
		$approver_4 		= $r2['approver_4'];
		$approver_5 		= $r2['approver_5'];
		$approver_6 		= $r2['approver_6'];
		$approver_7 		= $r2['approver_7'];
		$approver_8 		= $r2['approver_8'];
		$approver_9 		= $r2['approver_9'];
		$approver_1_status 	= $r2['approver_1_status'];
		$approver_2_status 	= $r2['approver_2_status'];
		$approver_3_status 	= $r2['approver_3_status'];
		$approver_4_status 	= $r2['approver_4_status'];
		$approver_5_status 	= $r2['approver_5_status'];
		$approver_6_status 	= $r2['approver_6_status'];
		$approver_7_status 	= $r2['approver_7_status'];
		$approver_8_status 	= $r2['approver_8_status'];
		$approver_9_status 	= $r2['approver_9_status'];
		
		$sql = "SELECT * FROM company where comp_id = '$company_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$budget_control_gst = $r2['budget_control_gst'];
			
		$sql = " select * from sma_user where userid = '$draft_by' ";
//echo $sql."<BR>";		
		$q2 =mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$draft_by 			= $r2['userid'];
		$draft_by_id 		= $r2['id'];
		$draft_email 		= $r2['email'];
		$draft_username		= $r2['username'];
			
		$status_field_from ='';
		$decision_status	= '';
		//approver_1_status == 'Submitted' need to check for dupplicate approver 
		if( $approver_1== $approver && $approver_1_status == 'Submitted'){
			$to_approver 	 = $approver_2;
			$status_field_from = 'approver_1_status';
			$status_field	 = 'approver_2_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = 'Pending';
		}
		if( $approver_1== $approver && empty($approver_2) && $approver_1_status == 'Submitted'){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_1_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		if( $approver_2== $approver && $approver_2_status == 'Submitted'){
			$to_approver 	 = $approver_3;
			$status_field_from = 'approver_2_status';
			$status_field	 = 'approver_3_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = 'Pending';
		}
		if( $approver_2== $approver && empty($approver_3) && $approver_2_status == 'Submitted'){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_2_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		if( $approver_3== $approver && $approver_3_status == 'Submitted'){
			$to_approver 	 = $approver_4;
			$status_field_from = 'approver_3_status';
			$status_field	 = 'approver_4_status';
			$approval_status = 'Submitted';
			$status      	 = 'Submitted';
			$decision_status = 'Pending';
		}
		if( $approver_3== $approver && empty($approver_4) && $approver_3_status == 'Submitted'){
			$to_approver 	 = $draft_by_id;
			$status_field	 = 'approver_3_status';
			$approval_status = 'Approved';
			$status      	 = 'Completed';
			$decision_status = $approval_status;
		}
		if( $approver_4== $approver && $approver_4_status == 'Submitted'){
				$to_approver 	 = $approver_5;
				$status_field_from = 'approver_4_status';
				$status_field	 = 'approver_5_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
		}
			if( $approver_4== $approver && empty($approver_5) && $approver_4_status == 'Submitted'){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_4_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			if( $approver_5== $approver && $approver_5_status == 'Submitted'){
				$to_approver 	 = $approver_6;
				$status_field_from = 'approver_5_status';
				$status_field	 = 'approver_6_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
			}
			if( $approver_5== $approver && empty($approver_6) && $approver_5_status == 'Submitted' ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_5_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			if( $approver_6== $approver && $approver_6_status == 'Submitted'){
				$to_approver 	 = $approver_7;
				$status_field_from = 'approver_6_status';
				$status_field	 = 'approver_7_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
			}
			if( $approver_6== $approver && empty($approver_7) && $approver_6_status == 'Submitted' ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_6_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			if( $approver_7== $approver && $approver_7_status == 'Submitted'){
				$to_approver 	 = $approver_8;
				$status_field_from = 'approver_7_status';
				$status_field	 = 'approver_8_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
			}
			if( $approver_7== $approver && empty($approver_8) && $approver_7_status == 'Submitted' ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_7_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			/* if( $approver_8== $approver && $approver_8_status == 'Submitted'){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_8_status';
				$approval_status = 'Approved';
				$status 		 = 'Completed';
				$decision_status = $approval_status;
			} */
			if( $approver_8== $approver && $approver_8_status == 'Submitted'){
				$to_approver 	 = $approver_9;
				$status_field_from = 'approver_8_status';
				$status_field	 = 'approver_9_status';
				$approval_status = 'Submitted';
				$status      	 = 'Submitted';
				$decision_status = 'Pending';
			}
			if( $approver_8== $approver && empty($approver_9) && $approver_8_status == 'Submitted' ){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_8_status';
				$approval_status = 'Approved';
				$status      	 = 'Completed';
				$decision_status = $approval_status;
			}
			if( $approver_9== $approver && $approver_9_status == 'Submitted'){
				$to_approver 	 = $draft_by_id;
				$status_field	 = 'approver_9_status';
				$approval_status = 'Approved';
				$status 		 = 'Completed';
				$decision_status = $approval_status;
			}
//echo $approval_status."<BR>"; 
//exit();		
		//Budget calculation Start
		$sql="SELECT * from sma_po_items where purchase_id = '$po_id' ";
//echo $sql."<BR>"; 
//exit();		
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$gst_amt = 0;
		$amount = 0 ;
		while($r1 = mysqli_fetch_array($res1)){
			$qty 		= $r1['quantity'];
			$rate 		= $r1['unit_rate'];
			$gst		= $r1['gst'];
			$budget_id	= $r1['budget_id'];
			
			//$gst_amt = $gst_amt + (($qty * $rate) * $gst / 100);
			$gst_amt = round((($qty * $rate) * $gst / 100),0);
			
			$amount = $qty * $rate + $gst_amt;
			
			$sql = "select * from sma_budget where id = '$budget_id' ";
//echo $sql. "<BR>";			
			$q3  = mysqli_query($con, $sql);
			$r3  = mysqli_fetch_object($q3);

			if($approval_status =='Approved'){
				
				/* if($po_type=='C'{
					
					$sql = "select * from sma_budget where id = '$budget_id' ";
					$q4  = mysqli_query($con, $sql);
					$r4  = mysqli_fetch_object($q4);
					$block_budget   	= $r4->blocked_budget;
					if($block_budget<0){
						$sql = "update sma_budget set blocked_budget = 0 where id = '$budget_id' ";
						$q3  = mysqli_query($con, $sql);
					}
					
					$sql = "update sma_budget set blocked_budget = blocked_budget + $values where id = '$budget_id' ";
					$q3  = mysqli_query($con, $sql); 

				}
				 */
			}
			else if($statusap =='Reject'){
				
					$sql = "update sma_budget set blocked_budget = blocked_budget - $amount where id = '$budget_id' ";
					$q3  = mysqli_query($con, $sql);
					$sql = "select * from sma_budget where id = '$budget_id' ";
					$q4  = mysqli_query($con, $sql);
					$r4  = mysqli_fetch_object($q4);
					$block_budget   	= $r4->blocked_budget;
					if($block_budget<0){
						$sql = "update sma_budget set blocked_budget = 0 where id = '$budget_id' ";
						$q3  = mysqli_query($con, $sql);
					}
					
					
			}
		
		}

//exit();
		
	if($statusap=='Reject'){
		
		$approval_status	= 'Rejected';
		$status				= 'Draft';
		$flow_flag 			= 'R';
		
		$sql = "update sma_purchase_order set approver_1 = '', approver_2 = '', approver_3 = '', 	
			approver_4 = '', approver_5 = '', approver_6 = '', approver_7 = '', approver_8 = '', approver_9 = '',
			approver_1_status='', approver_2_status='', approver_3_status='', approver_4_status='', 
			approver_5_status='', approver_6_status='', approver_7_status='', approver_8_status='',approver_9_status='',
			approval_status = '$approval_status', status = '$status', changed_by = '$user', current_approver='$draft_by_id', changed_date = now() where id = '$po_id' ";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
//echo $sql. "<BR>";

		if($po_type=='A'){
			$sql = "UPDATE sma_approval_items SET po_amount = 0, po_quantity = 0 WHERE approval_hdr_id = '$approval_memo_ref' ";
			mysqli_query($con, $sql);
		}				
		
		$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) values( 'PO', '$po_id', '$userid', now(), '$approval_status', '$draft_by_id', '$remarks', now() )";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
//echo $sql. "<BR>";
		
		$to_approver = $draft_by_id;
		
	}
	else {
		$sqla = '';
		if(!empty( $status_field_from )){
			$sqla = ", $status_field_from = 'Approved' ";
		}
		$sql = "update sma_purchase_order set $status_field	= '$approval_status', approval_status = '$approval_status', status = '$status', changed_by = '$user', current_approver='$to_approver', changed_date = now(), no_budget = '' $sqla where id = '$po_id'";
//echo $sql."<BR>";		
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}

		$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date ) 
				values( 'PO', '$po_id', '$userid', now(), 'Approved', '$to_approver', '$remarks', now())";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
		
		
		if($status=='Completed'){
			$sql = " select * from sma_party_mst where 1 and id = '$to_supplier' " ;
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_object($q2);
			$party_name 	= $r2->party_name;
			$party_email 	= $r2->party_email;
		}

	}
	
//Send mail to next approver;
		$sql="select * from sma_user where id='$to_approver' ";
//echo $sql. "<BR>";
		$result = mysqli_query($con, $sql);
		while($r = mysqli_fetch_object($result)){
			$username 		= $r->userid;
			$id		 		= $r->id;
			$role	 		= $r->role;
			$company_id 	= $r->company_id;
			$user_category 	= $r->user_category;
			$user_email		= $r->email;
			$user_name		= $r->username;
		}

		$modulePath = "purchase_order_entry/"; 
		$baseurl1 =$baseurl.$modulePath.'edit.php?id='.$po_id;
		$msg = 'Purchase Order Number : '.$po_id . ' ' . 'Dated : ' . date("d-m-Y");
		
//echo $user_email . ' ' . $draft_email . "<BR>";
//exit("RAVINDRA STOPED...");

		$baseurl1 = $baseurl.$modulePath.'edit.php?id='.$po_id;
		$btn_var = '<a href="'. $baseurl1 .'" class="btn btn-danger" style = "background-color: #6699CC;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Click for more information </a>';
		
		$baseurl1A = $baseurl.$modulePath.'editm.php?id='.$po_id. '&status=A'.'&emid='.$user_email;
		$btn_varA = '<a href="'. $baseurl1A .'" class="btn btn-danger" style = "background-color: #00a65a;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Approve </a>';
		
		$baseurl1R = $baseurl.$modulePath.'editm.php?id='.$po_id. '&status=R'.'&emid='.$user_email;
		$btn_varR = '<a href="'. $baseurl1R .'" class="btn btn-danger" style = "background-color: #b53737;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Reject </a>';

		include "po_mail.php";
		
		echo "<script>alert('Thanks! Completed.. Please OK to Cont..')</script>";
		
		$baseurl1 = $baseurl."dashboard_athang.php?sub=dash&sopt=P";
//		$baseurl1 = $baseurl.$modulePath."index.php?sub=list";
		echo "<script>window.location.href='$baseurl1';</script>";
		
		//$baseurl1 = $baseurl.$modulePath;
		//echo "<script>window.location.href='$baseurl1';</script>";
		exit();
		
	}

	if(isset($_POST['sub10'])){

		$value ='';
        $po_id 			= $_POST['po_id'];
		$mode		 	= $_POST['mode'];
		$status 		= $_POST['status'];
		$statusap		= $_POST['statusap'];
		$remarks 		= $_POST['remarks'];
		$approver		= $_POST['approver'];
		
		$user   	= $_SESSION['user'];
		$userid   	= $_SESSION['usrid'];

		if($status =='Draft' || $status == ''){
			$approval_status = 'Pending';
			$status = 'Draft';
		}

		$sql = "update sma_purchase_order set flow_flag = 'P', approval_status = '$approval_status', status = '$status', changed_by = '$user', changed_date = now() where id = '$po_id'";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
//echo $sql;
		$sql = "insert into workflow_history (doc_type, doc_id, create_by, create_date, status, reviewed_by, approved, remarks, approved_date, flow_flag) values('PO', '$po_id', '$userid', now(), '$approval_status', '$approver', '$approved', '$remarks', now(), 'P' )";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
//echo $sql;
		
//Send mail to approver;
		$sql="select * from sma_user where id='$approver' ";
		$result = mysqli_query($con, $sql);
		while($r = mysqli_fetch_object($result)){
			$username 		= $r->userid;
			$id		 		= $r->id;
			$role	 		= $r->role;
			$company_id 	= $r->company_id;
			$user_category 	= $r->user_category;
			$user_email		= $r->email;
			$user_name		= $r->username;
		}

		$modulePath = "purchase_order_entry/"; 
		
		$baseurl1 =$baseurl.$modulePath.'edit.php?id='.$po_id;

		
		$msg = '<br> For Checker, Purchase Order Number : '.$po_id . ' ' . 'Dated : ' . date("d-m-Y");
//echo $msg;
//exit("Stoped by Ravindra...");

		include "po_mail.php";
	
		$baseurl1 = $baseurl.$modulePath;
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
	
	}
				
    if(isset($_POST['sub12'])){
    
        $supplier_id 		= $_POST['id'];
		$company_id = $_POST['company_id'];
		
		if($_POST['id'] == ''){$id = '';}
		

		$value = '';

					
		$sql = "select approval_memo_ref from sma_purchase_order where 1 and approval_memo_ref > 0 and project = '$company_id' and del !='Y' and approval_status != 'Rejected' ";
		$q2  = mysqli_query($con, $sql);
		while($r2 = mysqli_fetch_object($q2)){
			$approval_memo_ref .= $r2->approval_memo_ref. ',';
		}	
		$approval_memo_ref .= '0';
					
			$value .='<select class="form-control select2" required name="approval_memo_ref" id="approval_memo_Ref" onchange="getquoteref(this.value);" >
							<option value=""> Select.. </option>';
			 $sql = " SELECT distinct(a.id), a.ap_number as ap_number, a.dated as dated, c.username as username
				FROM sma_approval_memo a , sma_approval_items b, sma_user c
					WHERE 1  AND a.del !='Y' AND a.id = b.approval_hdr_id AND a.company = '$company_id' 
					AND a.status = 'Completed' and c.userid = a.draft_by 
					AND b.po_quantity < b.quantity 
					"; 
					//AND b.po_value < (b.quantity * b.rate) AND a.id not in ( $approval_memo_ref ) 
					
			$q2  = mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_object($q2)){
				$dated      = $r2->dated;
				$id         = $r2->id;
				$ap_number  = $r2->ap_number;
				$username   = $r2->username;
				$value .='<option value="'.$id.'" > '.$ap_number.' </option>';
			};
			$value .= '</select>';
			
			
        echo $value;
		
		
    }



//Blocked para	
	if(isset($_POST['sub13'])){

		$value ='';
        $po_id 			= $_POST['po_id'];
		$mode		 	= $_POST['mode'];
		$status 		= $_POST['status'];
		$remarks 		= $_POST['remarks'];
		
		$user   	= $_SESSION['user'];
		$userid   	= $_SESSION['usrid'];

		if($status=='Completed'){
			
			$sql="SELECT * from sma_purchase_order where id = '$po_id' ";
	//echo $sql."<br>";
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$row = mysqli_fetch_array($result);
			$po_number	 	= $row['po_number'];
			
			$sql="SELECT * from sma_po_items where purchase_id = '$po_id' ";
	//echo $sql."<br>";
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			
			while($row = mysqli_fetch_array($result)){
				$product_id 	= $row['product_id'];
				$qty 			= $row['quantity'];
													
				$rate 	= $row['unit_rate'];
				$gst	= $row['gst'];
				$po_amount = $qty * $rate + (($qty * $rate) * $gst / 100);
				
				$sql = "SELECT * FROM `sma_supplier_invoice_details` where material_id = '$product_id' and si_hdr_id in ( SELECT id FROM `sma_supplier_invoice` where our_po_ref_no = '$po_number' or our_po_ref_no = '$po_id' )";
	//echo $sql."<br>";

				$sires = mysqli_query($con, $sql);
				echo 	 mysqli_error($con);
				$sr    = mysqli_fetch_array($sires);
				$material_id 	= $sr['material_id'];
				$budget_head 	= $sr['budget_head'];
				$si_qty 			= $sr['qty'];
				$si_rate 			= $sr['rate'];
				$si_gst 			= $sr['gst'];
				
				$si_amount = $si_qty * $si_rate + (($si_qty * $si_rate) * $si_gst / 100);
				if($si_amount <= 0 ){
					$si_amount = 0;
				}
				
				if($budget_head>0){
					$sql = "update sma_budget set total_budget = total_budget + ($po_amount - $si_amount) where id = '$budget_head' ";
					mysqli_query($con, $sql);
				}
	//echo $sql."<br>";
				
			}
		}
		
		$sql = "update sma_purchase_order set close_flag = 'Y', flow_flag = 'B', approval_status = 'Blocked', changed_by = '$user', changed_date = now() where id = '$po_id'";
		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
//echo $sql."<br>";
		
//Send mail to approver;
		
		$modulePath = "purchase_order_entry/"; 
		
		$baseurl1 =$baseurl.$modulePath.'edit.php?id='.$po_id;

		
//		$msg = '<br> For Checker, Purchase Order Number : '.$po_id . ' ' . 'Dated : ' . date("d-m-Y");
//echo $msg;
//exit("Stoped by Ravindra...");

		$baseurl1 = $baseurl.$modulePath;
		echo "<script>window.location.href='$baseurl1';</script>";
		exit();
	
	}
	
	
    if(isset($_POST['sub14'])){
		
        $id = $_POST['id'];
		$company_id = $_POST['company_id'];
		$product_id = $_POST['product_id'];
		
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		
		$sql = "SELECT * FROM sma_product where id = '$id' ";
			$q2  = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_object($q2);
				$name = $r2->name;
				$product_id   = $r2->id;
				$budget_code   = $r2->budget_code;
				$budget_head_id= $r2->budget_head;
		
// 		$sql = "SELECT * FROM sma_product_cost_center where product_id = '$product_id' and company_id = '$company_id' ";
// //echo $sql."<BR>";
// 		$q2  = mysqli_query($con, $sql);
// 		$r2 = mysqli_fetch_object($q2);
// 		$budget_id 			= $r2->budget_id;
// 		$budget_head_id 	= $r2->budget_id;
		
		$sql = " SELECT * FROM sma_budget_subgroup where id = '$budget_head_id' ";
//echo $sql."<BR>";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$budget_name_id 	= $r2->budget_name;
		$budget_head	 	= $r2->budget_head;
		
		//$sql   = "SELECT * FROM sma_budget where id = '$budget_id' ";
		$sql   = "SELECT * FROM sma_budget where account_year = '$finance_year' and budget_head = '$budget_head_id' and budget_name = '$budget_name_id' and project = '$company_id '";
//echo $sql;		
		$q2  = mysqli_query($con, $sql);
		$row_affected  = mysqli_affected_rows($con);
			$r2 = mysqli_fetch_object($q2);
			$account_year   	= $r2->account_year;
			$budget_code   		= $r2->budget_code;
			$budget_name_id 	= $r2->budget_name;
			
			//$budget_head 	= $r2->budget_head;
			
			$total_budget 		= $r2->total_budget;
			$blocked_budget 	= $r2->blocked_budget;
			$used_budget 		= $r2->used_budget;
			$adjustment_budget	= $r2->adjustment_budget;
			$balance_budget		= ( $total_budget + $adjustment_budget ) - ( $blocked_budget + $used_budget );
			
            $budget_id 		 	= $r2->id;
            
			$sql = "SELECT * from company where comp_id = '$company_id' ";
			$res = mysqli_query($con, $sql);
			//echo mysqli_error($con);
			$r2 = mysqli_fetch_array($res);
								
			$project = $r2['comp_name'];
			
			$sql = " SELECT * FROM sma_budget_name where 1 and id = '$budget_name_id' ";			
			$q3  = mysqli_query($con, $sql);
			$r3 = mysqli_fetch_object($q3);
			$budget_name 	= $r3->name;
			
			if($row_affected==0){
?>				
				<div class="form-group">
					
					<input type="hidden" class="form-control" id="row_affected_a" value="<?php echo $row_affected ?>" >
					
					<div class="col-sm-6">
						<label class="control-label" style="color:red;">Budget not available for product !!!</label>
					</div>
				</div>
<?php				
				exit();
			}	
?>			

			<input type="hidden" class="form-control" id="company_id_a" readonly value="<?php echo $company_id ?>" >
			<input type="hidden" class="form-control" id="budget_id" readonly value="<?php echo $budget_id ?>" >
			<input type="hidden" class="form-control" id="total_budget" readonly value="<?php echo $total_budget ?>" >
			<input type="hidden" class="form-control" id="balance_budget" readonly value="<?php echo $balance_budget ?>" >
			
			<input type="hidden" class="form-control" id="budget_Name" readonly value="<?php echo $budget_name_id ?>" >
			
		<div class="well well-sm" >		
									
			<div class="form-group">
				
				<div class="col-sm-2">
					<label class="control-label">Fin.Year</label>
					<input type="text" class="form-control" readonly value="<?php echo $account_year ?>" >
				</div>	
				<div class="col-sm-4">
					<label class="control-label">Budget Group </label>
					<input type="text" class="form-control" id="budget_head_a" readonly value="<?php echo $budget_name ?>" >
				</div>
				
				<div class="col-sm-6">
					<label class="control-label">Budget Sub Group</label>
					<input type="text" class="form-control" id="budget_name_a" readonly value="<?php echo $budget_head; ?>" >
				</div>
				
			</div>
<!--			
			<div class="form-group">
				<div class="col-sm-6">
				<label class="control-label">Blocked Budget </label>
				<input type="text" class="form-control" id="total_budget_a" readonly value="<?php echo $blocked_budget ?>" >
				</div>
									
				<div class="col-sm-6">
				<label class="control-label">Used Budget </label>
				<input type="text" class="form-control" id="balance_budget_a" readonly value="<?php echo $used_budget ?>" >
				</div>
			</div>
-->			
			<div class="form-group">
				<label class="control-label col-sm-2">Total Budget </label>
				<div class="col-sm-3">
				<input type="text" class="form-control" id="total_budget_a" style="text-align:right;" readonly value="<?php echo $total_budget ?>" >
				</div>
									
				<label class="control-label col-sm-2">Balance Budget </label>
				<div class="col-sm-3">
				<input type="text" class="form-control" style="text-align:right;" id="balance_budget_a" readonly value="<?php echo $balance_budget; ?>" >
				</div>
			</div>
		</div>	
<?php 								
        
		//$value .= '</select>';

//$value=$sql;

       // echo $value;
    }


    if(isset($_POST['sub14A'])){
		
        $id = $_POST['id'];
		$company_id = $_POST['company_id'];
		$product_id = $_POST['product_id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
		
		$sql   = "SELECT * FROM sma_budget where id = '$id' ";
//echo $sql;
		$q2  = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_object($q2);
			
			$budget_name_id 	= $r2->budget_name;
			
			$budget_head 	= $r2->budget_head;
			
			$total_budget 	= $r2->total_budget;
			$blocked_budget = $r2->blocked_budget;
			$used_budget 	= $r2->used_budget;
			
			$balance_budget	= $total_budget - ($blocked_budget + $used_budget);
			
            $budget_id 		 	= $r2->id;
            $_SESSION['budget_id']= $budget_id ;
			
			$sql = "SELECT * from company where comp_id = '$company_id' ";
			$res = mysqli_query($con, $sql);
			//echo mysqli_error($con);
			$r2 = mysqli_fetch_array($res);
								
			$project = $r2['comp_name'];
			
			$sql = " SELECT * FROM sma_budget_name where 1 and id = '$budget_name_id' ";			
			$q3  = mysqli_query($con, $sql);
			$r3 = mysqli_fetch_object($q3);
			$budget_name 	= $r3->name;
			
?>			
			
			<input type="hidden" class="form-control" id="company_id_aa" readonly value="<?php echo $company_id ?>" >
			<input type="hidden" id="budget_ida" name="budget_ida" value="<?= $budget_id ?>" >
			<input type="hidden" class="form-control" id="total_budgeta" readonly value="<?php echo $total_budget ?>" >
			<input type="hidden" class="form-control" id="balance_budgeta" readonly value="<?php echo $balance_budget ?>" >
			
			<input type="hidden" class="form-control" id="budget_Namea" readonly value="<?php echo $budget_name_id ?>" >
			
		<div class="well well-sm" >		
			<!--<div class="form-group">
							
				<div class="col-md-8">
					<label class=" control-label">Company</label>
					<input type="text" class="form-control" id="project_aa" name="project_aa" readonly value="<?php echo $project ?>" >
					
					<input type="hidden" class="form-control" id="budget_ida" name="budget_ida" value="<?php echo $project ?>" >
					
				</div>
			</div>
											
			<div class="form-group">
				<div class="col-sm-6">
					<label class="control-label">Budget Group </label>
					<input type="text" class="form-control" id="budget_head_aa" readonly value="<?php echo $budget_name ?>" >
				</div>
				
				<div class="col-sm-6">
					<label class="control-label">Budget Sub Group</label>
					<input type="text" class="form-control" id="budget_name_aa" readonly value="<?php echo $budget_head ?>" >
				</div>
				
			</div>-->
			
			<!--<div class="form-group">
				<div class="col-sm-6">
				<label class="control-label">Blocked Budget </label>
				<input type="text" class="form-control" id="total_budget_aa" readonly value="<?php echo $blocked_budget ?>" >
				</div>
									
				<div class="col-sm-6">
				<label class="control-label">Used Budget </label>
				<input type="text" class="form-control" id="balance_budget_aa" readonly value="<?php echo $used_budget ?>" >
				</div>
			</div>-->
			
			<div class="form-group">
				<div class="col-sm-6">
				<label class="control-label">Total Budget </label>
				<input type="text" class="form-control" id="total_budget_aa" readonly value="<?php echo $total_budget ?>" >
				</div>
									
				<div class="col-sm-6">
				<label class="control-label">Balance Budget </label>
				<input type="text" class="form-control" id="balance_budget_aa" readonly value="<?php echo $balance_budget ?>" >
				</div>
			</div>
		</div>	
<?php 								

    }

	
	if(isset($_POST['sub23'])){
    
        $id = $_POST['id'];
		$party_id_doc 	 = $_POST['party_id_doc'];
		$company_idd_doc = $_POST['company_idd_doc'];
		
		if($_POST['id'] == ''){$id = '';}
?>			
			<table id="prtable123" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>Party Name</th>
					<th>Document Type</th>
					<th>File Path</th>
					<th>File Name</th>
					<th>Inward No</th>
					
					<th style="text-align:right;">Action</th>
				
				</tr>
                </thead>
                <tbody>

<?php				
		$sql = "SELECT a.id, a.reference_id as inward_no, a.doc_type, a.file_path, a.file_name, a.current_user_id, b.document as document_name, party_name 
				FROM `my_documents_files` a, sma_document_type b, sma_party_mst c, dms_inward d 
				where b.id = a.doc_Type and d.sent_by_user_type = 'P' and d.sent_by_user_vendor = c.id 
				and a.reference_Id = d.inward_no and c.id = '$party_id_doc' and d.company_for = '$company_idd_doc' "; //  limit 0,5
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			while($row = mysqli_fetch_array($result)){
?>				
				<tr>	
					<td width="1%"><input type="hidden" value="<?php echo $row['id'];?>" > </td>
					<td width="20%" ><?php echo $row['party_name'];?></td>
					<td width="10%" ><?php echo $row['document_name'];?></td>
					<td width="20%" ><?php echo $row['file_path'];?></td>
					<td width="20%" ><a href="<?php echo $baseurl.'dms/'.$row['file_path'].'/'.$row['file_name'];?>" target="_blank"><?php echo $row['file_name'];?></a> </td>
					<td width="10%" ><?php echo $row['inward_no'];?></td>
					
					<td width="05%">
						<input type="checkbox" name="party_doc[]" <?php echo $checked; ?> id="party_doc" value="<?php echo $row['id']; ?>" >
					</td>
			</tr>
<?php			
			}
			
			$value = '';
					
//$value=$sql;

        echo $value;
    }
		
	if(isset($_POST['sub244'])){

		$company_id 		= $_POST['company_id'];
		$po_id 				= $_POST['po_id'];
		
		$budget_err			= '';
		
		$sql = "SELECT * FROM company where comp_id = '$company_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$budget_control_gst = $r2['budget_control_gst'];
		
		$sql = "SELECT * from sma_po_items where purchase_id = '$po_id'";	
//echo $sql. "<BR>";
///exit();		
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$value="";
		while($row2 = mysqli_fetch_array($result)){
								
			$po_item_id 	= $row2['id'];
			$budget_err 	= $row2['budget_err'];
			$qty 			= $row2['quantity'];
			$pr_quantity 	= $row2['pr_quantity'];
			$bal_si_qty		= $row2['bal_si_qty'];
			$bal_si_amount	= $row2['bal_si_amount'];
			$budget_id 		= $row2['budget_id'];
			$product_id 	= $row2['product_id'];
			
			$sql  = "SELECT * FROM sma_budget where id = '$budget_id' ";
			$res2 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$cat  = mysqli_fetch_array($res2);
			$budget_id   		= $cat['id'];
			$cost_center 		= $cat['budget_head'];							
			$blocked_budget 	= $cat['blocked_budget'];
			$used_budget 		= $cat['used_budget'];
			$adjustment_budget 	= $cat['adjustment_budget'];
			$total_budget 		= $cat['total_budget'] + $adjustment_budget;
			$bal_budget			= $total_budget - ( $used_budget + $blocked_budget ) + $blocked_budget ;
			$balance_budget		= $total_budget - ( $used_budget + $blocked_budget );
										
			if($balance_budget<0){
				$budget_bal_error = 'Y';
			}
										
			$rate 	= $row2['unit_rate'];
			$gst	= $row2['gst'];
			$gstamt = round((($qty * $rate) * $gst / 100),2);
							
			$amount =  round($qty * $rate,2) ;
										
			$amount =  round($amount + $gstamt,0);
										
			$bal_amount = round($amount - $bal_si_amount,0);
										
			if($budget_control_gst=='N'){
				$gstamt=0;
			}
			$check_amount = round(($qty * $rate) + $gstamt,0);
			$tot_amount = $tot_amount + $amount;
					
			if($check_amount > $bal_budget || $budget_bal_error =='Y'){
				$budget_err  	= 'Y';		
				
				$sql = " UPDATE sma_purchase_order set no_budget = 'NO Budget' where id = '$po_id' ";
				mysqli_query($con, $sql);
			    //echo 'Insufficient Budget for Product Name : ' . $product_name . '  Budget Group : '. $cost_center;
			}
			
		}
		
		echo $budget_err;
		
//		exit('Exit Here..');
		
		/* $baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$purchase_id.'&888';
		echo "<meta http-equiv='refresh' content='0'>";    
		echo "<script>window.location.href='$baseurl1';</script>"; */
		
		
	}
	
	if(isset($_POST['sub24'])){

		$company_id 		= $_POST['company_id'];
		$checker_value 		= $_POST['checker_value'];
		$po_type	 		= $_POST['po_type'];
		
//echo $po_type;		
		$doc_type = 'PO';
		
		$sql = "select * from company where comp_id = '$company_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2     = mysqli_fetch_array($q2);
		
		$sql = " SELECT * FROM sma_workflow 
					where 1 
				    and doc_type = '$doc_type' 
					and '$checker_value' >= from_value and '$checker_value' <= to_value 
					and company_id = '$company_id'
					";	
//echo $sql. "<BR>"; //and company_id = '$company_id' 
echo '#';
		$q2 = mysqli_query($con, $sql);
		$r2     = mysqli_fetch_array($q2);
		$approval_role_1 = $r2['approval_role_1'];
		$approval_role_2 = $r2['approval_role_2'];
		$approval_role_3 = $r2['approval_role_3'];
		
		$row_affected = 0;
		if($approval_role_1>0){
			$row_affected = $row_affected + 1;
			$required1 = 'REQUIRED';
		}
		if($approval_role_2>0){
			$row_affected = $row_affected + 1;
			$required2 = 'REQUIRED';
		}
		if($approval_role_3>0){
			$row_affected = $row_affected + 1;
			$required3 = 'REQUIRED';
		}
//exit();			
?>    
		<div class="box-footer">
						<input type="hidden" id='row_affected' value="<?= $row_affected; ?>" >
				
						<?php if($approval_role_1>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 1 <span style="color:red;">**</span></label>
							<?php
								$sql = " select * from sma_user where 1 and active =1 and id =  ( SELECT distinct(approval_role_1) FROM sma_workflow 
											where 1  
											    and doc_type = '$doc_type' and '$checker_value' >= from_value 
											    and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and approval_role_1 >0 ) ";
												
										//	and FIND_IN_SET('$company_id', company_id ) $sql_dept1 ";
										
										//and id 			!= '$userid' 	
										$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}		
							?>
							
									<select class="form-control  approver_1" id="APPROVER_1" name="approver_1" required <?= $required1; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								
								</div>
						<?php } ?>
						<?php if($approval_role_2>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 2</label>
							<?php //and id != '$userid' 
								$sql = " select * from sma_user where 1 and active =1 and id =  ( SELECT distinct(approval_role_2) FROM sma_workflow 
											where 1 and doc_type = '$doc_type' and '$checker_value' >= from_value 
											    and '$checker_value' <= to_value 
											    and company_id = '$company_id' 
												and approval_role_2 >0 ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}		
							?>
							
									<select class="form-control  approver_2" id="APPROVER_2"  name="approver_2" <?= $required2; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
						<?php } ?>
						<?php if($approval_role_3>0){ ?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 3</label>
							<?php // and id != '$userid' 
								$sql = " select * from sma_user where 1 and active =1 and id =  ( SELECT distinct(approval_role_3) FROM sma_workflow 
											where 1 and doc_type = '$doc_type' and '$checker_value' >= from_value 
											    and '$checker_value' <= to_value 
											    and company_id = '$company_id' 
												and approval_role_3 >0 ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}		
							?>		
									<select class="form-control  approver_3" id="APPROVER_3"  name="approver_3" <?= $required3; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
						<?php } ?>
						<?php if($approval_role_4>0){ ?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 4</label>
							<?php // and id != '$userid' 
								$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT distinct(approval_role_4) FROM sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
												and approval_role_4 >0 ), role ) 
												
											and FIND_IN_SET('$company_id', company_id ) $sql_dept4 ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}		
							?>		
									<select class="form-control  approver_4" id="APPROVER_4"  name="approver_4" <?= $required4; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
						<?php } ?>
						<?php if($approval_role_5>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 5</label>
							<?php //and id != '$userid' 
								$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT distinct(approval_role_5) FROM sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
												and approval_role_5 >0 ), role ) 
											
											and FIND_IN_SET('$company_id', company_id ) $sql_dept5 ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}		
							?>		
									<select class="form-control  approver_5" name="approver_5" <?= $required5; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								
						<?php } ?>
						<?php if($approval_role_6>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 6</label>
							<?php // and id != '$userid' 
								$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT distinct(approval_role_6) FROM sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
												and approval_role_6 >0 ), role ) 
											
											and FIND_IN_SET('$company_id', company_id ) $sql_dept6 ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}		
							?>		
									<select class="form-control  approver_6" name="approver_6" <?= $required6; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								
						<?php } ?>
						<?php if($approval_role_7>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 7</label>
							<?php //and id != '$userid' 
								$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT distinct(approval_role_7) FROM sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
												and approval_role_7 >0 ), role ) 
											
											and FIND_IN_SET('$company_id', company_id ) $sql_dept7 ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}		
							?>		
									<select class="form-control  approver_7" name="approver_7" <?= $required7; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								
						<?php } ?>
						<?php if($approval_role_8>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 8</label>
							<?php // and id != '$userid' 
								$sql = " select * from sma_user 
											where 1 and active =1 and FIND_IN_SET( ( SELECT distinct(approval_role_8) FROM sma_workflow a , sma_workflow_type b 
											where 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
												and approval_role_8 >0 ), role ) 
											
											and FIND_IN_SET('$company_id', company_id ) $sql_dept8 ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}		
							?>		
									<select class="form-control  approver_8" name="approver_8" <?= $required8; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								
						<?php } ?>
						
						<?php if($approval_role_9>0){ ?>		
								<div class="col-sm-3">
									<label class="control-label">Approver 9</label>
							<?php // and id != '$userid' 
								$sql = " select * from sma_user 
											WHERE 1 and active =1 and FIND_IN_SET( ( SELECT distinct(approval_role_9) FROM sma_workflow a , sma_workflow_type b 
											WHERE 1 and b.id = a.trans_type and b.status = 'Y'
											and a.doc_type = '$doc_type' and '$checker_value' >= from_value and '$checker_value' <= to_value 
												and company_id = '$company_id' 
												and trans_type = '$trans_type' 
												and approval_role_9 >0 ), role ) 
											
											and FIND_IN_SET('$company_id', company_id )  ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}		
							?>		
									<select class="form-control  approver_9" name="approver_9" <?= $required9; ?> >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
								
						<?php } ?>



						
								<div class="col-sm-2">
									<label class="control-label">&nbsp;</label><br>
									<input type="submit" class="btn btn-primary" value="Submit" name="Save" class="form-control" onclick="getvalidate();getsubmit();" >
								</div>
							
						</div>
						<br>
<?php						
	
	}

	if(isset($_POST['sub25'])){
		$company_id = $_POST['id'];
?>
		
		<select class="form-control select2" id='sterms' onchange="getspecialterms(this.value);" >
			<option value=""> Select </option>
			<?php $sql = "select * from sma_term where company_id = '$company_id' ";
				$q2 	= mysqli_query($con, $sql);
				while($r2 = mysqli_fetch_array($q2)){ ?>
				<option value="<?php echo $r2['id'];?>" ><?php echo $r2['special_terms'];?></option>
			<?php } ?>
		</select>

<?php
	}


	if(isset($_POST['sub26'])){

		include('../header.php');
	
		$term_id = $_POST['id'];
		
		$sql = "select * from sma_term where id = '$term_id' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$special_terms = $r2['special_terms'];
?>
		<div class="box-body">
			<textarea class="form-control" id="reason" name="terms" ><?= $special_terms;?></textarea>
		</div>


<?php
//include("../footer1.php");
?>

<!-- Select2 -->
<script src="<?php echo $baseurl . "plugins/select2/select2.full.js" ?>"></script>
<!-- InputMask -->
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.date.extensions.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.extensions.js" ?>"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo $baseurl . "plugins/ckeditor/ckeditor.js" ?>"></script>

<script>
   
    $(document).ready(function () {
        $('.datepicker').datepicker({
            "format": 'd/M/Y',
            "autoclose": true
        });
        $('.select2').select2();
        CKEDITOR.replace('reason');
        CKEDITOR.replace('reason1');
        CKEDITOR.replace('reason2');
        CKEDITOR.replace('reason3');
		$("#prItemsTable").DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": false
        });
    });


</script>
  		
<?php 
	}
		
    if(isset($_POST['sub27'])){
    
        $company_id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		$sql = "select * from company where comp_id = '$company_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2     = mysqli_fetch_array($q2);
		
?>
		<select class="form-control select3" name="trans_type" id="trans_type" required >
			<option value=""> Select.. </option>
			<?php //$sql = "select * from sma_workflow where doc_type = 'PO' order by trans_type ";
			$sql = "SELECT distinct(b.id), b.workflow_type FROM `sma_workflow` a, sma_workflow_type  b where 1 and b.status = 'Y' and a.doc_type = 'PO' and a.doc_type = b.doc_type and b.id = a.trans_type and a.company_id = '$company_id'  order by trans_type " ;
			$q2 	= mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_array($q2)){ ?>
			<option value="<?php echo $r2['id'];?>" >  <?php echo $r2['workflow_type'];?></option>
			<?php } ?>
		</select>
	
<?php
	}
	
   if(isset($_POST['sub3A'])){
        $id 		= $_POST['id'];
		$company_id = $_POST['company_id'];
		if($_POST['id'] == ''){$id = '';}
				
		$value = '';	

//echo $sql = "SELECT a.* from sma_product a, sma_product_cost_center b where a.id = b.product_id and a.product_group = '$id' and b.company_id = '$company_id' and b.budget_id > 0 order by name";			
?>
			<select class="form-control itemName" name="itemName" id="itemName" required="true" onchange="getunit2(this.value);getcatbudget(this.value);" >
			<option value=""> Select </option>
<?php
			//$sql = "SELECT id, name FROM sma_product where `product_group` = '$id' ORDER BY name ASC";
		//	$sql = "SELECT a.* from sma_product a, sma_product_cost_center b where a.id = b.product_id and a.product_group = '$id' and b.company_id = '$company_id' and b.budget_id > 0 order by name";
			$sql = "SELECT a.* from sma_product a where 1 and a.product_group = '$id'  order by a.name";
			$q2  = mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_object($q2)){
				$name = $r2->name;
				$id   = $r2->id;
?>
				<option value='<?= $id; ?>'><?=$name;?></option>

<?php       };  ?>

			</select>
		
<?php
//        echo $value;
    }
	
	
	if(isset($_POST['sub4a'])){
	
		$value ='';
        $id = $_POST['id'];
		$po_approval_hdr_id = $_POST['po_approval_hdr_id'];
		
		if($_POST['id'] == ''){$id = '';}
	
		$sql="delete from sma_po_approval_details where approval_srno = '$id' ";
		$rt = mysqli_query($con, $sql);
		echo mysqli_error($con);
		
		echo "<meta http-equiv='refresh' content='0'>";
		$value .= "<script>window.location.href='edit.php?sub=edit&id=$po_approval_hdr_id';</script>";
		echo $value;
		
	}	
	
	
	if(isset($_POST['sub28'])){
	
		$value ='';
        $id = $_POST['id'];
		$approval_hdr_id = $_POST['id'];
			
		$sql = "SELECT * FROM sma_approval_memo 
						WHERE 1 and id = '$approval_hdr_id' ";		
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$pr_number   = $r2->pr_number;
		$department	    = $r2->department;
		$subject		= $r2->subject;
		$quote_ref_no = $r2->quote_ref_no;
		$trans_type     = $r2->trans_type;
		$advance_flag	= $r2->advance_flag;
		$company_id		= $r2->company_id;
		$against_indent_no = $r2->against_indent_no;
		$advance_checked ='';
			
		$company_id		= $_POST['company_id'];
		
		
		$sql = " SELECT * FROM `sma_purchase_req` where id = '$against_indent_no' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$department   = $r2->department_id;
		
		$sql = " SELECT quote_ref_no FROM `sma_approval_details` where approval_hdr_id = '$approval_hdr_id' and vendor_selected = 'Y' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_object($q2);
		$quote_ref_no   = $r2->quote_ref_no;
		
?>
	<div class="form-group">
		<div class="col-md-2">
			<label class="control-label">Department <span style="color:red;"> **</span></label>
			<select class="form-control" name="department" id="department" READONLY required >
			
			<?php $sql = "select * from sma_department where id = '$department' order by name ";
			$q2 	  = mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_array($q2)){ ?>
			<option value="<?php echo $r2['id'];?>" <?php echo ($department == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['name'];?></option>
			<?php } ?>
			</select>
		</div>
										
		<div class="col-md-3">
			<label class="control-label">Supplier Quote Ref.No.</label>
		    <span id="getqref">
			<input type="text" class="form-control" id="quotation_reference_no" name="quotation_reference_no" READONLY value="<?= $quote_ref_no ?>" >
			</span>							
		</div>
		
		<div class="col-md-3">
			<label class="control-label">Advance Payment Required?</label><br>&nbsp;&nbsp;&nbsp;
			<input type="checkbox" <?= $advance_checked; ?> id="advance_flag" name="advance_flag" value="Y" >
		</div>
		
	</div>
		
		<div class="form-group">					
			<div class="col-md-12">
			<label class="control-label">Subject</label>
			<input type="text" class="form-control" id="subject" name="subject" value="<?php echo $subject;?>" >
			</div>						
		</div>	
<?php										
//		echo $quote_ref_no;
		
	}			
?>	

<?php 
	if(isset($_POST['sub35'])){
		$modulePath = "purchase_order_entry//";
		$userid   	= $_SESSION['usrid'];
		
		$comment 			= $_POST['comment'];
		$po_id		 		= $_POST['po_id'];
		$doc_type	 		= $_POST['doc_type'];
		$comment_type	 	= $_POST['comment_type'];

		require '../PHPMailer-master/PHPMailerAutoload.php';
	
		$page				= $_POST['page']; 		
		$baseurl .=$modulePath.'edit.php?sub=edit&id='.$po_id.'&page='.$page.'&active8=active';
		
		$sql = "INSERT INTO sma_comment (doc_id, doc_type, comment_type, comment, comment_by, comment_datetime) 
					VALUES ( '$po_id', '$doc_type', '$comment_type', ". '"'.$comment.'"'. ", '$userid', now() )";
		mysqli_query($con, $sql);
		
		$sql  = "SELECT * FROM sma_purchase_order where id = '$po_id' ";
		$query= mysqli_query($con, $sql);
		$rw   = mysqli_fetch_array($query);
		$draft_by			= $rw['draft_by'];
		$subject			= $rw['subject'];
		$approver_2			= $rw['approver_2'];
		$approver_3			= $rw['approver_3'];
		$approver_4			= $rw['approver_4'];
		$approver_5			= $rw['approver_5'];
		$approver_6			= $rw['approver_6'];
		$approver_7			= $rw['approver_7'];
		$approver_8			= $rw['approver_8'];
		$approver_1_status	= $rw['approver_1_status'];
		$approver_2_status	= $rw['approver_2_status'];
		$approver_3_status	= $rw['approver_3_status'];
		$approver_4_status	= $rw['approver_4_status'];
		$approver_5_status	= $rw['approver_5_status'];
		$approver_6_status	= $rw['approver_6_status'];
		$approver_7_status	= $rw['approver_7_status'];
		$approver_8_status	= $rw['approver_8_status'];				
		
		$sql = " select * from sma_user where userid = '$draft_by' ";
		$q2 =mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$approver_id 			= $r2['id'];
		include("comment_mail.php");
		
		if(!empty($approver_1_status)){
			$approver_id		= $rw['approver_1'];	
			include("comment_mail.php");
		}		
		if(!empty($approver_2_status)){
			$approver_id		= $rw['approver_2'];	
			include("comment_mail.php");
		}
		if(!empty($approver_3_status)){
			$approver_id		= $rw['approver_3'];	
			include("comment_mail.php");
		}
		if(!empty($approver_4_status)){
			$approver_id		= $rw['approver_4'];	
			include("comment_mail.php");
		}
		if(!empty($approver_5_status)){
			$approver_id		= $rw['approver_5'];	
			include("comment_mail.php");
		}
		if(!empty($approver_6_status)){
			$approver_id		= $rw['approver_6'];	
			include("comment_mail.php");
		}
		if(!empty($approver_7_status)){
			$approver_id		= $rw['approver_7'];	
			include("comment_mail.php");
		}
		if(!empty($approver_8_status)){
			$approver_id		= $rw['approver_8'];	
			include("comment_mail.php");
		}
		

?>		
		
<?php 
		
		echo "<script>window.location.href='$baseurl';</script>";
} 


 if(isset($_POST['sub29'])){
    
        $doc_type = $_POST['doc_type'];
		if($_POST['id'] == ''){$id = '';}
		
		
?>
		<select class="form-control select3" name="trans_type" id="trans_type" required >
            <option value="">Select... </option>
			<?php $sql = "select * from sma_workflow_type where doc_type = '$doc_type' ";
			$q2 	= mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_array($q2)){ ?>
			<option value="<?php echo $r2['id'];?>" ><?php echo $r2['workflow_type'];?></option>
			<?php } ?>
		</select>
	
<?php

	}

if(isset($_POST['sub36'])){
	
?>
	
	<div class="col-md-2">
		<label class="control-label">Tender No.</label>
		<input type="text" class="form-control" id="tender_no" name="tender_no" value="<?php echo $row['tender_no'];?>" onchange="gettenderTitle(this.value)" >
	</div>
										
	<div class="col-md-3">
		<span id="gettenderTitle"> </span>
	</div>
<?php

}

										
 if(isset($_POST['sub37'])){
    
        $tender_no 		= $_POST['id'];
		$company_id = $_POST['company_id'];
		if($_POST['id'] == ''){$id = '';}
		
		$selected_amount = 0;
		$sql = " select * from sma_tender_supplier_quote where tender_hdr_id = '$tender_no' ";
		$q2 	= mysqli_query($con, $sql);
		while($r2 = mysqli_fetch_array($q2)){
								
			$supplier_var .= $r2['supplier_id'].',';
								
			$selected_vendor = $r2['selected_vendor'];
			if($selected_vendor == 'Y'){
				$sel_party_name  = $r2['supplier_id'];
				$selected_amount = $r2['selected_amount'];
				$selected_remarks= $r2['selected_remarks'];
			}
		}
							
		if($selected_amount > 0 ){				
			$sql 	= " SELECT * FROM `sma_tender_header` WHERE id = '$tender_no' and company_id = '$company_id' ";
	//echo $sql;		
			$q2 	= mysqli_query($con, $sql);
			$row_affected = mysqli_affected_rows($con);
			$r2 	= mysqli_fetch_array($q2);
			$company_id   = $r2['company_id'];
			$tender_title = $r2['tender_title'];
			if($row_affected >0){	
?>
				<textarea rows="3" class="form-control" readonly id="tender_title" name="tender_title" ><?php echo $tender_title;?></textarea>
	
<?php
			}
		
		}

    }	
	
	if(isset($_POST['sub38'])){
    
		$approval_memo_ref	= $_POST['id'];
		$dated				= date('Y-m-d', strtotime($_POST['dated']));
		if($_POST['id'] == ''){$id = '';}

		include("po_budget_check_routine.php");
		
	}

	
if(isset($_POST['sub99'])){
    
        $po_id 				= $_POST['po_id'];
		$mode 				= $_POST['mode'];
		$to_supplier 		= $_POST['to_supplier'];
		$approver 			= $_POST['approver'];
		$remarks 			= $_POST['remarks'];

		$sql="select * from sma_party_mst where id = '$to_supplier' ";			
		$result = mysqli_query($con, $sql);
		$r = mysqli_fetch_object($result);
		$id		 		= $r->id;
		$party_email	= $r->party_email;
		$user_name		= $r->party_name;
		$party_name		= $r->party_name;
		
		$sql = "SELECT * FROM sma_purchase_order WHERE id  = '$po_id' ";
		$qry = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($qry);
		$draft_by 		= $r2['draft_by'];
		$company_id		= $r2['project'];
		
		$sql = " select * from sma_user where userid = '$draft_by' ";
		$q2 =mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$to_approver 		= $r2['id'];
		$draft_email 		= $r2['email'];
		$draft_username		= $r2['username'];
		
		$user_email			= $draft_email;
		$user_name			= $draft_username;
		if($mode == 'Accept'){
			$status = 'Accepted';
		}	
		else if($mode == 'Reject'){
			$status = 'Rejected';
		}
		$remarks .= $status  . ' By '. $party_email . ' Name ' .$party_name; 
		$sql = " INSERT INTO workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, remarks, approved_date, vendor_flag ) VALUES( 'PO', '$po_id', '$id', now(), '$status', '$to_approver', '$remarks', now(), 'V' )";
		mysqli_query($con, $sql);
//		echo $sql. "<BR>";

		include "po_mail.php";
		
		session_destroy();
		
//exit('####1');
		
 }
 
?>	