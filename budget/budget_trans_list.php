<?php

include("../header.php");
$modulePath = "budget/budget_trans_list.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">


<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Budget Transactions
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Budget</li>
      </ol>
    </section>

<div class="col-md-12">
	
		<div class="box box-info">
            <div class="box-header with-border">
       		<?php 
				
				if ( $_GET['budget_id'] ){
					$budget_id 			= $_GET['budget_id'];	
					$sql="SELECT * from sma_budget where id = '$budget_id' ";
					$result = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$row = mysqli_fetch_array($result);
					$project 		= $row['project'];
					$account_year			= $row['account_year'];
					$_SESSION['project_a']  = $row['project'];
					$_SESSION['location_a'] = $row['location'];													   
					$_SESSION['budget_name_a'] = $row['budget_name'];
					$_SESSION['budget_head_a'] = $row['budget_head'];
					
					$sql = "SELECT * FROM sma_financial_year WHERE 1 and  short_fy_code = '$account_year' ";
					$q3  = mysqli_query($con, $sql);
					$r3  = mysqli_fetch_array($q3);
					$from_date 	 = date('d-m-Y', strtotime($r3['from_date']));
					$to_date 	 = date('d-m-Y', strtotime($r3['to_date']));
					$_SESSION['from_date']		= $from_date;
					$_SESSION['to_date']		= $to_date;
					
				}
				
				if ( $_POST['project'] or $_POST['budget_name'] or $_POST['budget_head'] or $_POST['account_year'] || $_POST['from_date'] || $_POST['to_date'] ){
					$account_year			= $_POST['account_year'];
					$_SESSION['project_a']  = $_POST['project'];
					$_SESSION['location_a'] = $_POST['location'];													   
					$_SESSION['budget_name_a'] = $_POST['budget_name'];
					$_SESSION['budget_head_a'] = $_POST['budget_head'];
					$_SESSION['from_date']		= $_POST['from_date'];
					$_SESSION['to_date']		= $_POST['to_date'];
					
				}
				
				if ($_SESSION['project_a'] or $_SESSION['budget_name_a'] or $_SESSION['budget_head_a']){
					
					$project_v 		= $_SESSION['project_a'];
					$location_v 	= $_SESSION['location_a'];											  
					$budget_name_v 	= $_SESSION['budget_name_a'];
					$budget_head_v 	= $_SESSION['budget_head_a'];
					$from_date 		= $_SESSION['from_date'];
					$to_date 		= $_SESSION['to_date'];
					
				}
	
				if ( !empty($_GET['reset']) || !empty($_SESSION['reset']) ){
				
					$_SESSION['project_a'] = '';
					$_SESSION['location_a'] 	= '';			   
					$_SESSION['budget_name_a'] = '';
					$_SESSION['budget_head_a'] = '';
					$_SESSION['reset'] = '';
					$_SESSION['from_date'] = '';
					$_SESSION['to_date'] = '';
					$project_v 		= $_SESSION['project_a'];
					$budget_name_v 	= $_SESSION['budget_name_a'];
					$budget_head_v 	= $_SESSION['budget_head_a'];
					$from_date 		= $_SESSION['from_date'];
					$to_date 		= $_SESSION['to_date'];
				
				}

//echo $budget_head_v. ' <<>> ' .$project_v. ' >< '. $account_year_v; "<BR>";

			if(empty($from_date) && empty($to_date)){
				$sql = "SELECT * FROM sma_financial_year WHERE 1 and  short_fy_code = '$account_year' ";
				$q3  = mysqli_query($con, $sql);
				$r3  = mysqli_fetch_array($q3);
				$from_date 	 = date('d-m-Y', strtotime($r3['from_date']));
				$to_date 	 = date('d-m-Y', strtotime($r3['to_date']));
			}
			
			?>
			<form class="form-horizontal" action="budget_trans_list.php?sub=list&view=Y" method="post">
                      
						<div class="form-group">
							<div class="col-md-2">
								<label class="control-label">Fin.Year</label>
								<select class="form-control" name="account_year" id="account_year" required onchange="getfindate(this.value)" >
									<option value=""> Select </option>
									<?php 
										$sql = " select * from sma_financial_year WHERE 1  order by short_fy_code desc ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ 
											
									?>
									<option value="<?php echo $r2['short_fy_code'];?>" <?php echo ($account_year == $r2['short_fy_code'] || $r2['status']=='Y')?'selected="selected"':'';?> > <?php echo $r2['short_fy_code'];?></option>
										<?php } ?>	
									
								</select>
						</div>
							
							<div class="col-sm-4">
								<label for="project" class="control-label ">Company</label>
								<select class="form-control select2" name="project" id="PROJECT" required onchange="getccname123(this.value);getlocation(this.value);"  >
                             		<option value=""> Select </option>
									<?php 
										$sql = " select * from company WHERE comp_id in ( $comid ) order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ 
									?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($project_v == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
								</select>		
							</div>
						
							<div class="col-sm-2">
								<label for="location" class="control-label ">Location</label>
								<span id="getlocation">
									<select class="form-control" name="location" id="location" required onchange="getccname(this.value);" >
									<option value=""> Select </option>
									<option value="" <?= (empty($location_v))?'selected="selected"':'';?> > All </option>
									<?php $sql = "select * from sma_location where loc_comp_id = '$project_v' order by loc_name ";
									$q2 	= mysqli_query($con, $sql);
									while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($location_v == $r2['id'])?'selected="selected"':'';?>  ><?php echo $r2['loc_name'];?></option>
									<?php } ?>
									</select>	
								</span>
                            </div>
							
							<div class="col-md-4">
								<label class=" control-label">CC Group</label>
							  <span id ="getccname">	
								<select class="form-control" required name="budget_name" id="budget_name" onchange="getccgroup(this.value);" >
									<option value=""> Select </option>
									<option value="" <?= (empty($budget_name_v))?'selected="selected"':'';?> > All </option>
										<?php $sql = "select * from sma_budget_name order by name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($budget_name_v == $r2['id'])?'selected="selected"':'';?>> <?php echo $r2['name'];?></option>
										<?php } ?>
								</select>
							  </span>	
							</div>
				<?php		
					if(!empty($budget_name_v)){
						$sqla = " AND budget_name = '$budget_name_v' ";
					}
					$sqlb = " AND account_year = '$account_year' ";
		
					$sql = " SELECT * from sma_budget_subgroup where 1 $sqla 
					AND id in ( SELECT distinct(budget_head) FROM sma_budget WHERE 1 $sqlb $sqla AND project = '$project_v' ) order by budget_head ";
//echo $sql."<BR>";					
				?>
							<div class="col-md-5">
								<label class="control-label">CC Sub Group</label>
							<span id="getccgroup" >	
								<select class="form-control" required name="budget_head" id="budget_head" >
									<option value=""> Select </option>
									<option value="" <?= (empty($budget_head_v))?'selected="selected"':'';?> > All </option>
										<?php //$sql = "select * from sma_budget_subgroup WHERE budget_name = '$budget_name_v'  order by budget_head ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($budget_head_v == $r2['id'])?'selected="selected"':'';?>> <?php echo $r2['budget_head'];?></option>
										<?php } ?>
									</select>
								</span>
							</div>
						</div>
						
						<?php //. ' - ' . $r2['budget_code'];
							if($from_date=='01-01-1970'){
								$from_date='';
							}
							if($to_date=='01-01-1970'){
								$to_date='';
							}							
						?>	
												
						<div class="form-group">	
							<span id="getfindate">
								<div class="col-md-2">
									<label class="control-label">From Date</label>
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="from_date" name="from_date" placeholder="" value="<?php echo $from_date; ?>" >
										<div class="input-group-addon">
											<i class="fa fa-calendar-alt"></i>
										</div>
									</div>	
								</div>
								
								<div class="col-md-2">
									<label class="control-label">To Date</label>
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="to_date" name="to_date" placeholder="" value="<?php echo $to_date;; ?>" >
										<div class="input-group-addon">
											<i class="fa fa-calendar-alt"></i>
										</div>
									</div>	
								</div>
							</span>	
						
							<div class="pull-right col-xs-1">	
								<span class="sepV_c marginRight">
									<a href="budget_trans_export.php?sub=pdf&sql=<?php echo $_SESSION['sql'];?>" target ="_blank" class="btn btn-primary">Export</a>
									&nbsp;&nbsp;&nbsp;
										
								</span>
							</div>							
							
							<div class="pull-right col-xs-1">	
								<a href="budget_trans_list.php?sub=list&reset=1" name="btnCancel" class="btn btn-danger btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
								
							</div>
							
							<div class="pull-right col-xs-1">
								<input class="btn btn-success" type="submit" value="Search" name="Save">&nbsp;&nbsp;&nbsp;
							</div>
							
						</div>
						
				</form>

			</div>
			
		</div>	
    
<?php
	$modulePath1 = "budget/";
//echo $from_date. ' <<>> ' . $to_date;	
	if($_GET['view']=='Y' && !$_GET['page'] ){
		
		$account_year	= $_POST['account_year'];
		$project_v 		= $_POST['project'];
		$from_date = date('Y-m-d', strtotime($from_date));
		$to_date   = date('Y-m-d', strtotime($to_date));
//echo $from_date  . ' <<>> ' . $to_date."<BR>";
		if(empty($from_date) && empty($to_date)){
				$sql = "SELECT * FROM sma_financial_year WHERE 1 and  short_fy_code = '$account_year' ";
				$q3  = mysqli_query($con, $sql);
				$r3  = mysqli_fetch_array($q3);
				$from_date 	 = date('Y-m-d', strtotime($r3['from_date']));
				$to_date 	 = date('Y-m-d', strtotime($r3['to_date']));
		}
		
		$budget_id_v = '';
		if( !empty($budget_name_v) && !empty($budget_head_v) ){
				
			$sql = " SELECT * FROM `sma_budget` where project = '$project_v'  
						AND account_year = '$account_year' and budget_name = '$budget_name_v' and budget_head = '$budget_head_v' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$budget_id_v = $r2['id'];
//echo $sql."<BR>";			
		}
//echo $budget_id_v. "<BR>";
		
		$sql = "truncate table budget_view";
		mysqli_query($con, $sql);
		
		
//Approval Memo Start

// 		$sql = " SELECT distinct(a.id) as id, a.dated, a.company, '' as 'check_var', b.budget_id, a.approval_status as status, a.doctype, a.changed_date, a.overhead_exp
// 			FROM `sma_approval_memo` a, `sma_approval_items` b , sma_budget c 
// 			WHERE 1 and approval_status not in (  'Amend','Rejected' ) AND a.id = b.approval_hdr_id AND b.budget_id = c.id AND a.del !='Y'  
// 				AND c.account_year = '$account_year'
// 				AND a.company 		= '$project_v' 
// 				AND dated 	>= '$from_date' 
// 				AND dated 	<= '$to_date' ";
// //echo $sql."<BR>";	//'Suspend','Amend',
// 	if( !empty($budget_name_v) && !empty($budget_head_v) ){
// 		$sql .= " and c.id = '$budget_id_v' ";
// 	}
// 	else  {
// 		if( !empty($budget_name_v) ){
// 			$sql .= " and c.budget_name = '$budget_name_v' ";
// 		}
		
// 		if( !empty($budget_head_v) ){
// 			$sql .= " and c.budget_head  = '$budget_head_v' ";
// 		}
// 	}
// //echo $sql."<BR>";	
// 	$result = mysqli_query($con, $sql);
	
// 	echo mysqli_error($con);
// 	while($row = mysqli_fetch_array($result)){

// 		$sort_type 		= 'B';
// 		$doc_type		= 'AP';
// 		$doc_no 		= $row['id'];
// 		$doc_date		= $row['dated'];
// 		$company_id		= $row['company'];
// 		$budget_id		= $row['budget_id'];
// 		$doctype		= $row['doctype'];
// 		$status			= $row['status'];
// 		$changed_date	= $row['changed_date'];
// 		$overhead_exp	= $row['overhead_exp'];
		
// 		$sql = "SELECT * FROM sma_approval_details WHERE approval_hdr_id = '$doc_no' and vendor_selected = 'Y' ";
// 		$q3  = mysqli_query($con, $sql);
// 		$r3  = mysqli_fetch_array($q3);
// 		$party_id 		= $r3['supplier_name'];
		
// 		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
// 		$q3  = mysqli_query($con, $sql);
// 		$r3  = mysqli_fetch_array($q3);
// 		$budget_control_gst = $r3['budget_control_gst'];
			
// 		$sql = " SELECT * FROM `sma_approval_items` WHERE approval_hdr_id = '$doc_no' and budget_id = '$budget_id' ";
// 		$res = mysqli_query($con, $sql);
// 		echo mysqli_error($con);
// 		while($r2 = mysqli_fetch_array($res)){

// 			$product_id			= $r2['product_id'];
// 			$budget_id			= $r2['budget_id'];
// 			$quantity			= $r2['quantity'];
// 			$unit_rate			= $r2['unit_rate'];
// 			$gst				= $r2['gst'];
			
// 			$amount = round(($quantity * $unit_rate) + ((($quantity * $unit_rate) * $gst) / 100),0);
// 			if($budget_control_gst =='N'){
// 				$amount = $quantity * $unit_rate;
// 			}
		
// 			$sql  = " SELECT * FROM sma_product WHERE id = '$product_id' ";
// 			$res3 = mysqli_query($con, $sql);
// 			echo mysqli_error($con);
// 			$r3   = mysqli_fetch_array($res3);
// 			$product_name	=$r3['name'];
			
// 			$statuss = $status;
// 			if($status=='Suspend' || $status=='Amend' || $status=='Closed'){
// 				$statuss = 'Created';
// 			}
			
// 			$sql = " INSERT INTO budget_view (sort_type, doc_type, doc_no, doc_date, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var , party_id , doctype, status, changed_date) 
// 					VALUES( '$sort_type', '$doc_type', '$doc_no', '$doc_date', '$budget_id', '$product_name', '$amount', '0', '0', '0', '$check_var', '$party_id' , '$doctype', '$statuss', '$changed_date' ) ";
// 			mysqli_query($con, $sql);

// 			if( $status=='Suspend' || $status=='Closed' || $status=='Amend' ){
// 				if($overhead_exp=='Y'){
// 					$sql  = "SELECT sum(b.amount) as amount_tot, sum(gst_amount) as gst_amount_tot, reference FROM `sma_travel_expenses` a, sma_expenses b  WHERE 1 and a.del !='Y' and a.exp_type ='C' and a.id = b.approval_ref_no  and approval_number = '$doc_no' and reference = '$product_id' ";
// 					$res  = mysqli_query($con, $sql);
// 					echo mysqli_error($con);
// 					$r2 = mysqli_fetch_array($res);
// 					$amount_totp		=  $r2['amount_tot'];
// 					$gst_amount 		=  $r2['gst_amount_tot'];
// 					$reference_id 		=  $r2['reference'];
// 					$bal_amount = round($amount - ($amount_totp + $gst_amount) ,2) ;
// 					/* if($bal_amount<1){
// 						$bal_amount = 0;
// 					} */
					
// 					$amount = $bal_amount;
					
// 				}
													
// 				$sql = " INSERT INTO budget_view (sort_type, doc_type, doc_no, doc_date, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var , party_id , doctype, status, changed_date) 
// 						VALUES( '$sort_type', '$doc_type', '$doc_no', '$doc_date', '$budget_id', '$product_name', '$amount', '0', '0', '0', '$check_var', '$party_id' , '$doctype', '$status', '$changed_date' ) ";
// 				mysqli_query($con, $sql);
// 			}
			
			
// //echo $sql. "<BR>";
			
// 		}

// 	}
	
//exit();
//Approval Memo End


//Purchase Order Start
	
		$sql = " SELECT distinct(a.id) as id, a.dated, a.project as company , po_type as 'check_var', b.budget_id, a.approval_status as status, approval_memo_ref, a.to_supplier as party_id, a.changed_date, a.status as statuss, a.po_number
			FROM `sma_purchase_order` a, `sma_po_items` b , sma_budget c 
				WHERE 1 
					and a.id = b.purchase_id and approval_status not in (  'Suspend', 'Rejected' )
					and b.budget_id = c.id and a.del !='Y'
					AND c.account_year = '$account_year'
					 AND a.project = '$project_v' 
					 AND dated 	>= '$from_date' 
					 AND dated 	<= '$to_date' "; 
	
//echo $sql."<BR>";	//'Amend', ( 'Draft', 'Amend', 'Suspend', 'Rejected') 
	$project_v = $_POST['project'];
	if( !empty($budget_name_v) && !empty($budget_head_v) ){
		$sql .= " and c.id = '$budget_id_v' ";
	}
	else  {
		if( !empty($budget_name_v) ){
			$sql .= " and c.budget_name = '$budget_name_v' ";
		}
		
		if( !empty($budget_head_v) ){
			$sql .= " and c.budget_head  = '$budget_head_v' ";
		}
	}

//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$sort_type 		= 'B';
		$doc_type		= 'PO';
		$doc_no 		= $row['id'];
		$doc_date		= $row['dated'];
		$company_id		= $row['company'];
		$check_var		= $row['check_var'];
		$budget_id		= $row['budget_id'];
		$party_id		= $row['party_id'];
		$status			= $row['status'];
		$statuss		= $row['statuss'];
		$approval_memo_ref	= $row['approval_memo_ref'];
		$changed_date		= $row['changed_date'];
		$po_number		= $row['po_number'];
		
		if($statuss	=='Draft'){
	//		continue;
		}
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$budget_control_gst = $r3['budget_control_gst'];
			
		$sql = " SELECT * FROM `sma_po_items` WHERE purchase_id = '$doc_no' and budget_id = '$budget_id' ";
//echo $sql."<BR>";		
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r2 = mysqli_fetch_array($res)){

			$product_id			= $r2['product_id'];
			$budget_id			= $r2['budget_id'];
			$quantity			= $r2['quantity'];
			$unit_rate			= $r2['unit_rate'];
			$gst				= $r2['gst'];
			
			$bal_si_qty			= $r2['bal_si_qty'];
			$bal_si_amount		= $r2['bal_si_amount'];
										  
			if($status=='Closed'){
				if($quantity == $bal_si_qty){
					$a='';
				}
				else {
					$quantity = $quantity - $bal_si_qty;
				}
			}	
			$amount = round(($quantity * $unit_rate) + ((($quantity * $unit_rate) * $gst) / 100),0);
			if($budget_control_gst =='N'){
				$amount = $quantity * $unit_rate;
			}
		
			$sql  = " SELECT * FROM sma_product WHERE id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name	=$r3['name'];
			$category	=$r3['category'];
			
			$amount_closed = 0;
			if($status=='Closed'){
				/* 
				if($quantity 	== $bal_si_qty){
					$amount_closed 	= $bal_si_amount;
					if($budget_control_gst =='N' ){
						$amount_closed 	= $quantity * $unit_rate;
					}
				
					if( $category=='S'){
						$amount_closed 	= $bal_si_amount;
					}	
				}
				else {
					$a='';
				}
				$amount_closed = $amount - $amount_closed;
				 26-11-2024 */
				$sql = "SELECT a.id , our_po_ref_no, round(sum((qty * rate)),2) as si_amount, round( sum(((qty * rate) * gst) / 100),2) as si_gst, sum(credit_note_value) as credit_note_value  
						FROM sma_supplier_invoice a, sma_supplier_invoice_details b 
							WHERE a.id = si_hdr_id AND a.our_po_ref_no = '$doc_no' 
								AND b.material_id = '$product_id'
								 AND a.del !='Y' 
								AND a.approval_Status != 'Rejected' " ;
				$res3 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r3   = mysqli_fetch_array($res3);
				$credit_note_value	= $r3['credit_note_value'];	
				$si_amount			= $r3['si_amount'] - $credit_note_value;
				$si_gst				= $r3['si_gst'];		
					
				$amount_closed = $amount - ($si_amount + $si_gst );
				if($budget_control_gst =='N' ){
					$amount_closed = $amount - $si_amount ;
				}		
				
			}
			
			
			$sql = " INSERT INTO budget_view (sort_type, doc_type, doc_no, doc_date, po_srno, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var, status, party_id, changed_date, po_number) 
					VALUES( '$sort_type', '$doc_type', '$doc_no', '$doc_date', '$approval_memo_ref', '$budget_id', '$product_name', '$amount', '0', '0', '0' , '$check_var', '$statuss', '$party_id', '$changed_date', '$po_number' ) ";
			mysqli_query($con, $sql);

			if($status=='Closed'){
				$amount_closed	= $amount_closed * -1;
				$sql = " INSERT INTO budget_view (sort_type, doc_type, doc_no, doc_date, po_srno, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var, status, party_id, changed_date, po_number) 
					VALUES( '$sort_type', '$doc_type', '$doc_no', '$doc_date', '$approval_memo_ref', '$budget_id', '$product_name', '$amount_closed', '0', '0', '0' , '$check_var', '$status', '$party_id', '$changed_date' ,'$po_number' ) ";
				mysqli_query($con, $sql);
			}
			
			$bal_amount = 0;
			if($status=='Suspend' || $status=='Amend'){
				//$amount = $amount * -1;
				$bal_si_qty			= $r2['bal_si_qty'];
				$bal_si_amount		= $r2['bal_si_amount'];
				
				if($budget_control_gst =='N'){
					$bal_si_amount = round(($bal_si_amount / ($gst+100) ) * 100,2);
				}	
				$bal_amount			= ($amount - $bal_si_amount ) * -1;
				
				if($bal_amount>0){
					$bal_amount = $bal_amount * -1;
				}
				
			//echo $amount . ' ' . $bal_si_amount. ' ' .$bal_amount. "<BR>";
				$sql = " INSERT INTO budget_view (sort_type, doc_type, doc_no, doc_date, po_srno, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var, status, party_id, changed_date, po_number) 
					VALUES( '$sort_type', '$doc_type', '$doc_no', '$doc_date', '$approval_memo_ref', '$budget_id', '$product_name', '$bal_amount', '0', '0', '0' , '$check_var', '$statuss', '$party_id', '$changed_date', '$po_number' ) ";
				mysqli_query($con, $sql);
				
				//19-03-2024 continue;
				
			}
			
//echo $sql. "<BR>";
			
		}

	}		
			
//Purchase Order End



//Supplier Invoice Start
			
		$sql = " SELECT distinct(a.id) as id, a.created_date as dated, a.company_id as company, '' as 'check_var' ,b.budget_id, a.our_po_ref_no, suplier_name as party_id, a.changed_date, a.status
			FROM `sma_supplier_invoice` a, `sma_supplier_invoice_details` b , sma_budget c 
				WHERE 1 AND a.approval_status !='Rejected' and a.id = b.si_hdr_id 
					and b.budget_id = c.id and a.del !='Y'
					AND c.account_year = '$account_year'
					 and a.company_id = '$project_v' 
					 AND created_date 	>= '$from_date' 
					 AND created_date 	<= '$to_date' ";
	
//echo $sql."<BR>";	
	//$project_v = $_POST['project'];
    if( !empty($location_v) ){
		$sql .= " and a.location = '$location_v' ";
	}
	if( !empty($budget_name_v) && !empty($budget_head_v) ){
		$sql .= " and c.id = '$budget_id_v' ";
	}
	else  {
		if( !empty($budget_name_v) ){
			$sql .= " and c.budget_name = '$budget_name_v' ";
		}
		
		if( !empty($budget_head_v) ){
			$sql .= " and c.budget_head  = '$budget_head_v' ";
		}
	}

//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$sort_type 		= 'C';
		$doc_type		= 'SI';
		$doc_no 		= $row['id'];
		$our_po_ref_no	= $row['our_po_ref_no'];
		$doc_date		= $row['dated'];
		$company_id		= $row['company'];
		$check_var		= $row['check_var'];
		$budget_id		= $row['budget_id'];
		$party_id		= $row['party_id'];
		$changed_date	= $row['changed_date'];
		
		$status			= $row['status'];
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$budget_control_gst = $r3['budget_control_gst'];
			
		$sql = " SELECT * FROM `sma_supplier_invoice_details` WHERE si_hdr_id = '$doc_no' and budget_id = '$budget_id' ";
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r2 = mysqli_fetch_array($res)){

			$product_id			= $r2['material_id'];
			$budget_id			= $r2['budget_id'];
			$quantity			= $r2['qty'];
			$unit_rate			= $r2['rate'];
			$gst				= $r2['gst'];
			$credit_note_value	= $r2['credit_note_value'];
			
			$amount = round(($quantity * $unit_rate) + ((($quantity * $unit_rate) * $gst) / 100),0);
			if($budget_control_gst =='N'){
				$amount = $quantity * $unit_rate;
			}
		
			$amount = $amount - $credit_note_value;
			
			$sql  = " SELECT * FROM sma_product WHERE id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name	=$r3['name'];
			
			if($amount>0){

				$sql = " INSERT INTO budget_view (sort_type, doc_type, doc_no,  po_srno, doc_date, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var, party_id, changed_date, status) 
						VALUES( '$sort_type', '$doc_type', '$doc_no', '$our_po_ref_no', '$doc_date', '$budget_id', '$product_name', '0', '$amount', '0', '0', '$check_var', '$party_id', '$changed_date', '$status' ) ";
				mysqli_query($con, $sql);
				
			}

//echo $sql. "<BR>";
			
		}

	}		
						
//Supplier Invoice End



//Operating Expense Start
			
		$sql = " SELECT distinct(a.id) as id, a.dated as dated, a.exp_type, a.company_id as company , a.approval_number as 'check_var', b.budget_id, approval_number, emp_id as party_id, onbehalf_emp_id, a.changed_date, a.status
			FROM `sma_travel_expenses` a, `sma_expenses` b , sma_budget c 
				WHERE 1 AND a.approval_status not in ('Amend','Rejected') and a.id = b.approval_ref_no 
					and b.budget_id = c.id and a.del !='Y'
					AND c.account_year = '$account_year'
					 and a.company_id = '$project_v'
					 AND a.dated 		>= '$from_date' 
					 AND a.dated 		<= '$to_date'
					 ";
	
//echo $sql."<BR>";	and a.company_id in ($comid)
	//$project_v = $_POST['project'];
	if( !empty($location_v) ){
		$sql .= " and a.location = '$location_v' ";
	}						  
	if( !empty($budget_name_v) && !empty($budget_head_v) ){
		$sql .= " and c.id = '$budget_id_v' ";
	}
	else  {
		if( !empty($budget_name_v) ){
			$sql .= " and c.budget_name = '$budget_name_v' ";
		}
		
		if( !empty($budget_head_v) ){
			$sql .= " and c.budget_head  = '$budget_head_v' ";
		}
	}

//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){
		
		$onbehalf_emp_id 	= $row['onbehalf_emp_id'];
		
		
		$sort_type 		= 'D';
		$exp_type 		= $row['exp_type'];
		if($exp_type=='T'){
			$doc_type		= 'TE';
			$party_id 		= $onbehalf_emp_id;
		}
		else if($exp_type=='C'){
			$doc_type		= 'OP';
			$party_id 			= $row['party_id'];
		}
		if($exp_type=='R'){
			$doc_type		= 'RE';
			$party_id 		= $onbehalf_emp_id;
		}
		
		$doc_no 		= $row['id'];
		$doc_date		= $row['dated'];
		$company_id		= $row['company'];
		$check_var		= $row['check_var'];
		$budget_id		= $row['budget_id'];
		$approval_number	= $row['approval_number'];
		$changed_date		= $row['changed_date'];
		$status				= $row['status'];
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$budget_control_gst = $r3['budget_control_gst'];
			
		$sql = " SELECT * FROM `sma_expenses` WHERE approval_ref_no = '$doc_no' and budget_id = '$budget_id' ";
//echo $sql. "<BR>";		
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r2 = mysqli_fetch_array($res)){

			$product_id			= $r2['reference'];
			$budget_id			= $r2['budget_id'];
			$amount			    = $r2['amount'];
			$gst_amount		    = $r2['gst_amount'];
			
			if($budget_control_gst =='Y'){
				$amount = $amount + $gst_amount;
			}
		
			$sql  = " SELECT * FROM sma_product WHERE id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name	=$r3['name'];
			
			$sql = " INSERT INTO budget_view (sort_type, doc_type, doc_no, doc_date, po_srno, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var, party_id , changed_date, status) 
					VALUES( '$sort_type', '$doc_type', '$doc_no', '$doc_date', '$approval_number', '$budget_id', '$product_name', '0', '$amount', '0', '0' , '$check_var', '$party_id', '$changed_date', '$status' ) ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
//echo $sql. "<BR>";

//echo $sql. "<BR>";
			
		}

	}		
						
//Operating Expense End



//Budget Adjustment Start
	
		$sql = " SELECT id, dated, project, budget_name, budget_head, budget_code, budget_id, effect, amount, last_year_cf_block FROM `budget_adjust`  where 1  and status = 'Completed' ";
		//and status = 'Completed'
		
		$sql .= " AND project = '$project_v' 
				  AND dated   >= '$from_date' 
				  AND dated   <= '$to_date' 
				  AND budget_id = '$budget_id_v' ";

//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$sort_type 		= 'A';
		$doc_type		= 'BD';
		$doc_no 		= $row['id'];
		$doc_date		= $row['dated'];
		$company_id		= $row['project'];
		$check_var 		= $row['effect'];
		$budget_id		= $row['budget_id'];
		$amount			= $row['amount'];
		$last_year_cf_block = $row['last_year_cf_block'];
		
		$blocked_budget = 0;
		if($last_year_cf_block=='Y'){
			$blocked_budget 	= $amount;
			
		}	
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$comp_code 			= $r3['comp_code'];
			
			$sql = " INSERT INTO budget_view (sort_type, comp_code, doc_type, doc_no, doc_date, budget_id, adjustment_budget, blocked_budget, check_var) 
			VALUES( '$sort_type', '$comp_code', '$doc_type', '$doc_no', '$doc_date',  '$budget_id', '$amount', '$blocked_budget', '$check_var' ) ";
			mysqli_query($con, $sql);

//echo $sql. "<BR>";

	}		
	
//Budget Adjustment End


//Budget Adjustment From To Start
	
		$sql = " SELECT id, dated, project, budget_id_from, budget_id_to, amount FROM `budget_adjust_from_to`  where 1  and status = 'Completed' ";
		
		$sql .= " AND project = '$project_v' 
				  AND dated   >= '$from_date' 
				  AND dated   <= '$to_date' 
				  AND (budget_id_from = '$budget_id_v' OR budget_id_to = '$budget_id_v' ) ";

//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$sort_type 		= 'A';
		$doc_type		= 'BT';
		$doc_no 		= $row['id'];
		$doc_date		= $row['dated'];
		$company_id		= $row['project'];
		$budget_id_from 	= $row['budget_id_from'];
		$budget_id_to		= $row['budget_id_to'];
		$amount				= $row['amount'];
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$comp_code 			= $r3['comp_code'];
			
		$amount_v = $amount * -1;
		$sql = " INSERT INTO budget_view (sort_type, comp_code, doc_type, doc_no, doc_date, budget_id, adjustment_budget, blocked_budget, check_var, budget_id_transfer) 
			VALUES( '$sort_type', '$comp_code', '$doc_type', '$doc_no', '$doc_date',  '$budget_id_from', '$amount_v', 0, 'From' , '$budget_id_to' ) ";
		mysqli_query($con, $sql);
				
		$sql = " INSERT INTO budget_view (sort_type, comp_code, doc_type, doc_no, doc_date, budget_id, adjustment_budget, blocked_budget, check_var, budget_id_transfer) 
			VALUES( '$sort_type', '$comp_code', '$doc_type', '$doc_no', '$doc_date',  '$budget_id_to', '$amount', 0, 'To', '$budget_id_from'  ) ";
		mysqli_query($con, $sql);
		
//echo $sql. "<BR>";

	}
	
//Budget Adjustment From To End


}
	}
	
	
	if($_GET['view']=='Y' ){
?>

	<div class="box">
    <table id="prtable123" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>Doc Type.</th>
			<th>Doc.No.</th>
			<th>PO.SrNo.</th>
			<th  style="text-align:left;">Dated</th>
			<th  style="text-align:left;">Approved Dated</th>
			<th>Party/Items/Expense</th>
			<th>Blocked By PO.</th>
			<th  style="text-align:right;">Blocked By Approval</th>
			<th  style="text-align:right;">Consumed</th>
			<th  style="text-align:right;">Adjustment</th>
			<th  style="text-align:right;">Balance</th>

		</tr>
	</thead>
<tbody>

<?php		
		$targetpage = "budget_trans_list.php?sub=list&view=Y"; 
		$limit = 20; 
		$start = 0;	
				
	$sql = " SELECT * FROM budget_view WHERE 1 order by doc_date, doc_type, doc_no "; //sort_type, doc_type, budget_id,
	
	$sql = " SELECT a.doc_type, a.doc_no, a.items, a.budget_id, a.check_var, a.po_srno , a.doc_date, 
			a.party_name, a.invoice_no, a.approval_no, a.po_number, a.comp_code,
			a.blocked_budget, a.used_budget, a.adjustment_budget, b.total_budget ,
			a.location, a.department, a.status, a.party_id, a.doctype, budget_id_transfer, a.changed_date, a.sort_type
				FROM budget_view a, sma_budget b 
					WHERE  1 and b.id = a.budget_id ";
				
	$project_v 		= $_POST['project'];
	$budget_name_v 	= $_POST['budget_name'];
	$budget_head_v 	= $_POST['budget_head'];
	
	$sql .= "and b.project = '$project_v' ";
//	and b.budget_name = '$budget_name_v' and b.budget_head = '$budget_head_v' 
	if( !empty($budget_name_v) && !empty($budget_head_v) ){
		$sql .= " and b.id = '$budget_id_v' ";
	}
	else  {
		if( !empty($budget_name_v) ){
			$sql .= " and b.budget_name = '$budget_name_v' ";
		}
		
		if( !empty($budget_head_v) ){
			$sql .= " and b.budget_head  = '$budget_head_v' ";
		}
	}	
	if( empty($budget_name_v) ){
		$sql .= " ORDER BY budget_name, budget_head, budget_id, doc_date, doc_type, doc_no ";
	}
	else {
		$sql .= " ORDER BY budget_id,doc_date , sort_type,  doc_no ";
	}
//echo $sql;																					  
	$_SESSION['sql'] = $sql;
	
	$res		 = mysqli_query($con, $sql);
	echo mysqli_error($con);					 
	$total_pages = mysqli_affected_rows($con);
	//$total_pages = $total_pages['num'];
					
	$stages = 3;
					//$page = mysqli_real_escape_string($_GET['page']);
					
					$page = ($_GET['page']);
					if($page){
						$start = ($page - 1) * $limit; 
					}
					else{
						$start = 0;	
					}	
					
					//$sql .= ' order by id desc ';
					//$sql .= " LIMIT $start, $limit ";
					
//echo $sql."<BR>";

//	include "paginate.php";
	
	$budget_id_prev		= '';
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){
	
		$doc_type	 		= $row['doc_type'];
		$doc_type_v	 		= $row['doc_type'];
		$doc_no	 			= $row['doc_no'];
		$po_srno			= $row['po_srno'];
		$po_number          = $row['po_number'];
		
		$product_name 		= $row['items'];
		$status		 		= $row['status'];
		
		$budget_id 			= $row['budget_id'];
		$party_id 			= $row['party_id'];
		$check_var 			= $row['check_var'];
		$doctype 			= $row['doctype'];
		$budget_id_transfer	= $row['budget_id_transfer'];
		$changed_date		= date('d-m-Y', strtotime($row['changed_date']));
		
		if($doc_type =='PO'){
			$po_srno		= $doc_no;
		}
		
		$baseurl_v = "";
		if($doc_type=='PO'){
			$baseurl_v = $baseurl."purchase_order/edit.php?sub=edit&id=$doc_no";
		}
		else if($doc_type=='AP'){
			$baseurl_v = $baseurl."approval/edit.php?sub=edit&id=$doc_no";
		}
		else if($doc_type=='SI'){
			$baseurl_v = $baseurl."supp_invoice/edit.php?sub=edit&id=$doc_no";
		}
		else if($doc_type=='OP'){
			$baseurl_v = $baseurl."travel_approval/company_expense.php?sub=edit&id=$doc_no";
		}
		else if($doc_type=='TE'){
			$baseurl_v = $baseurl."travel_approval/travel_expence.php?sub=edit&id=$doc_no";
		}
		else if($doc_type=='RE'){
			$baseurl_v = $baseurl."travel_approval/regular_expense.php?sub=edit&id=$doc_no";
		}
		else if($doc_type=='BA'){
			$baseurl_v = $baseurl."budget/budget_adjust.php?sub=edit&id=$doc_no";
		}
		else if($doc_type=='BT'){
			$baseurl_v = $baseurl."budget/budget_adjust_from_to.php?sub=edit&id=$doc_no";
		}
		
		
		if($changed_date=='01-01-1970' || $changed_date=='30-01-0001'){
			$changed_date = '';
		}
		
		$po_amount ='';
		$party_name = '';
		if($doc_type =='AP' || $doc_type =='PO' || $doc_type =='SI'  || $doc_type =='CE' || $doc_type =='OP' ){
			$sql = " SELECT * from sma_party_mst WHERE id = '$party_id' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$party_name 		= $r2['party_name'];
		}
		else if($doc_type =='BT' ){
			$sql = " SELECT * from sma_budget WHERE id = '$budget_id' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$budget_name 		= $r2['budget_name'];
			$budget_head 		= $r2['budget_head'];
			
			$sql = " SELECT * from sma_budget_subgroup WHERE id = '$budget_head' and budget_name= '$budget_name' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$budget_name 		= $r2['budget_name'];
			$budget_head 		= $r2['budget_head'];
			
			$sql = " SELECT * from sma_budget_name WHERE id = '$budget_name' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$budget_name 		= $r2['name'];
			$product_name 		= $budget_name . ' ' . $budget_head;
			
//Transfer Budget			
			$sql = " SELECT * from sma_budget WHERE id = '$budget_id_transfer' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$budget_name 		= $r2['budget_name'];
			$budget_head 		= $r2['budget_head'];
			
			$sql = " SELECT * from sma_budget_subgroup WHERE id = '$budget_head' and budget_name= '$budget_name' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$budget_name 		= $r2['budget_name'];
			$budget_head 		= $r2['budget_head'];
			
			$sql = " SELECT * from sma_budget_name WHERE id = '$budget_name' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$budget_name 		= $r2['name'];
			if($check_var=='From'){
				$product_name 		= $check_var.' ' .$product_name. '<br> To ' .$budget_name . ' ' . $budget_head;
			}
			else if($check_var=='To'){
				$product_name 		= $check_var.' ' .$product_name. '<br> From ' .$budget_name . ' ' . $budget_head;
			}
			
			
		}	
		else {
			$sql = " SELECT * from sma_user WHERE id = '$party_id' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$party_name 		= $r2['username'];
		}	
		
		if($budget_id_prev	!= $budget_id){
			$sql = " SELECT * from sma_budget WHERE id = '$budget_id' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$total_budget 		= $r2['total_budget'];
			$budget_name 		= $r2['budget_name'];
			$budget_head 		= $r2['budget_head'];
			$budget_code 		= $r2['budget_code'];
			//$adjustment_budget 	= $r2['adjustment_budget'];
			$running_balance_budget = $total_budget ;
			
			
			$sql = " SELECT * from sma_budget_name WHERE id = '$budget_name' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$budget_name 		= $r2['name'];
			
			$sql = " SELECT * from sma_budget_subgroup WHERE id = '$budget_head' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$budget_head 		= $r2['budget_head'];
			
?>			
			<tr>
				<td width="10%" style="text-align:left;"></td>
				<td width="10%" style="text-align:left;"></td>
				<td width="15%" style="text-align:left;"><b>Opening Balance</b></td>
				<th width="10%" style="text-align:left;"><?= $budget_name; ?></th>
				<th width="10%" style="text-align:left;" colspan="4"><?= $budget_head; ?></th>
				<td width="10%" style="text-align:left;"></td>
				<td width="10%" style="text-align:right;"><?php echo moneyFormatIndia($total_budget);?></td>
		  
				</td>
			</tr>
<?php	
		}
		
		$budget_id_prev		= $budget_id;
		
		$dated 				= date('d-m-Y', strtotime($row['doc_date']));
		
		if($dated == '01-01-1970'){
			$dated = '';
		}
		
		$blocked_budget 	= $row['blocked_budget'];
		$used_budget 		= $row['used_budget'];
		$adjustment_budget 	= $row['adjustment_budget'];
		//$balance_budget 	= $row['balance_budget'];
		
		if( $doc_type=='AP' ){
			
			$doc_type = 'Approval Memo';
			
			if($doctype=='AP-ADJ'){
				$doc_type = 'Approval Memo Reversal';
				if($blocked_budget==0){
					continue;	
				}	
				$adjustment_budget	= $blocked_budget * -1;
				$blocked_budget = '';
			}
			else if($status=='Suspend' || $status=='Closed' || $status=='Amend' ){
					
				$blocked_budget	= $blocked_budget * -1;
				$running_balance_budget		= $running_balance_budget - $adjustment_budget - ( $blocked_budget ); 
				$blocked_budget_var = $blocked_budget_var + $blocked_budget;
				
			}
			else {
				$running_balance_budget		= $running_balance_budget - $adjustment_budget - ( $blocked_budget ); //+ $used_budget
				$blocked_budget_var = $blocked_budget_var + $blocked_budget;
			}
			
			if($blocked_budget<0){
				//$doc_type .= ' - '.$status;
			}
		
		}
		else if( $doc_type=='PO' ){
			
			$doc_type = 'Purchase Order';

//echo $running_balance_budget. "<BR>";					
			$running_balance_budget	= $running_balance_budget - $adjustment_budget - ( $blocked_budget ); //+ $used_budget
//echo $running_balance_budget. "<BR>";						
			if($blocked_budget<0){
//echo $running_balance_budget. "<BR>";				
//				$running_balance_budget	= $running_balance_budget - ( $blocked_budget ); 
				$po_amount 		= $blocked_budget;
				$blocked_budget_var = $blocked_budget_var + $blocked_budget;
				$blocked_budget	= '';
				//$doc_type .= ' '.$status;
//echo $blocked_budget_var. "<BR>";				
			}
			else{
				$po_amount 		= $blocked_budget;
				$blocked_budget_var = $blocked_budget_var + $blocked_budget;
				$blocked_budget	= '';	
			}
				
		}
		else if( $doc_type=='SI' ){
			$doc_type = 'Supplier Invoice';
			$used_budget_upd_si	= $used_budget_upd_si + $used_budget;
			
		}
		else if( $doc_type=='OP' ){
			
			$doc_type = 'Operating Expense';
			
			if(empty($check_var) && $running_balance_budget >0){
				$running_balance_budget	= $running_balance_budget - $adjustment_budget - ( $used_budget  ); 
			//	$used_budget_var = $used_budget_var + $used_budget;
			}
			
		}
		else if( $doc_type=='TE' ){
			
			$doc_type = 'Travel Expense';
			//&& $running_balance_budget >0
			if(empty($check_var) ){
				$running_balance_budget	= $running_balance_budget - $adjustment_budget - ( $used_budget  ); 
				//$used_budget_var = $used_budget_var + $used_budget;
			}
			
		}
		else if( $doc_type=='RE' ){
			
			$doc_type = 'Regular Expense';
			
			if(empty($check_var) ){
				$running_balance_budget	= $running_balance_budget - $adjustment_budget - ( $used_budget  ); 
				//$used_budget_var = $used_budget_var + $used_budget;
			}
		}
		else if( $doc_type=='BD' ){
			$doc_type = 'Budget Adjustment';
			
			if($check_var=='I'){
				$running_balance_budget	= $running_balance_budget + $adjustment_budget ; 
			}
			else if($check_var=='D'){
				$running_balance_budget	= $running_balance_budget - $adjustment_budget ; 
				$adjustment_budget 		= $adjustment_budget * -1;
			}

		}
		else if( $doc_type=='BT' && $check_var == 'From'){
			$doc_type = 'Budget Adjustment From';
			$running_balance_budget = $running_balance_budget + $adjustment_budget;
		}
		else if( $doc_type=='BT' && $check_var == 'To'){
			$doc_type = 'Budget Adjustment To';
			$running_balance_budget = $running_balance_budget + $adjustment_budget;
		}
		
		
		$baseurl1 = $baseurl.$modulePath1.'budget_trans_list.php?sub=edit&id='.$row["id"];
					
		if($blocked_budget>0){
			$blocked_budget = moneyFormatIndia($blocked_budget);
		}
		else if($blocked_budget<0){
			//echo $blocked_budget. "<BR>";
			$blocked_budget = number_format($blocked_budget,2);
		}
		
		//$blocked_budget_upd = $blocked_budget_upd + $po_amount;
		$used_budget_upd 	= $used_budget_upd + $used_budget;
		
		
		
		if($po_amount>0){
			$po_amount = moneyFormatIndia($po_amount);
		}
		else if ($po_amount<0){
			$blocked_budget_revert = $blocked_budget_revert  + $po_amount;
			$blocked_budget = number_format($po_amount,2);
			
			$po_amount ='';
		}
		
		if($adjustment_budget==0){
			$adjustment_budget ='';
		}
		else {
			//$adjustment_budget = number_format($adjustment_budget,2);
			if($adjustment_budget>0){
			$adjustment_budget = moneyFormatIndia($adjustment_budget);
			}
			else {
			$adjustment_budget = number_format($adjustment_budget,2);
			
			}
		}												  
		
		if($party_id> 0){
			$party_name = "<BR>($party_name) ";
		}	
				
		$running_balance_budget = round($running_balance_budget,2);
		
		if($running_balance_budget>0){
			$running_balance_budget_v = moneyFormatIndia($running_balance_budget);
		}
		else {
			$running_balance_budget_v = number_format($running_balance_budget,2);
			
		}
		
		$used_budget_var = $used_budget_var + $used_budget;
		
		if($used_budget>0){
			$used_budget_v = moneyFormatIndia($used_budget);
		}
		else {
			$used_budget_v = number_format($used_budget,2);
			
		}
		
		if($changed_date=='30-11--0001'){
			$changed_date='';
		}
		
		$doc_type .= ' '.$status;
		
		if($doc_type_v == 'PO'){
		    
		   $doc_no=  $po_number;
		}
	?>
	<tr>
		
		<td width="10%" style="text-align:left;"><?php echo $doc_type ;?></td>
		<td width="10%" style="text-align:left;"><a href="<?= $baseurl_v; ?>" target="_blank" ><?php echo $doc_no;?></a></td>
		<td width="10%" style="text-align:left;"><?php echo $po_srno;?></td>
		<td width="10%" style="text-align:left;"><?php echo $dated;?></td>
		<td width="10%" style="text-align:left;"><?php echo $changed_date;?></td>
		
		<td width="15%" style="text-align:left;"><?php echo $product_name. $party_name;?></td>
		<td width="10%" style="text-align:right;"><?php echo $po_amount;?></td>
		<td width="10%" style="text-align:right;"><?php echo ($blocked_budget);?></td>
		<td width="10%" style="text-align:right;"><?php echo $used_budget_v;?></td>
		<td width="10%" style="text-align:right;"><?php echo ($adjustment_budget);?></td>
		<td width="10%" style="text-align:right;"><?php echo $running_balance_budget_v;?></td>
		</td>
    </tr>
	
	<?php 
		$po_amount ='';
	}?>
	 <tr>
		
		<td width="15%" style="text-align:left;">&nbsp;</td>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		<td width="15%" style="text-align:left;">&nbsp;</td>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		</td>
    </tr>
	
</tbody> 
</table>
	
<?php
  $end  =$start+10;
  $begin=$start+1;
  
  if ($end>$total_pages){ $end=$total_pages;}
	echo 'Showing ' . $begin .' to ' . $end . ' of ' . $total_pages . ' entries ';
	echo $paginate;
   echo "<BR>"; 
//  echo $sql = "UPDATE sma_budget set blocked_budget = '$blocked_budget_var' where id = '$budget_id' ";
//  echo "<BR>";
//  echo $sql = "UPDATE sma_budget set blocked_budget = blocked_budget - '$used_budget_var', used_budget = '$used_budget_var' where id = '$budget_id' ";
//    echo "<BR>";
//  echo 'Blocked : '.$blocked_budget_var .' <<>> Used : ' . $used_budget_var ;
   echo $blocked_budget_revert. "<BR>";  
   
   $blocked_budget_revert = ($blocked_budget_revert*-1);
   
   echo "Blocked : " .$blocked_budget_upd.' ' .$blocked_budget_revert . ' SI Used : ' . $used_budget_upd_si. ' Used : ' . $used_budget_upd;		
   echo "<BR>";  
 
	/* select sum(blocked_budget) - sum(used_budget), doc_type from (
SELECT sum(blocked_budget) as blocked_budget, sum(used_budget) as used_budget, doc_type  FROM `budget_view` where doc_type in ('PO', 'SI') group by doc_type ) DS; */

 //	$sql = " UPDATE sma_budget set blocked_budget = ( $blocked_budget_upd ) - $used_budget_upd_si, used_budget = $used_budget_upd WHERE id = '$budget_id' ";
//	mysqli_query($con, $sql);

//if nagative value	
	$sql = " select * from budget_view  WHERE 1 and doc_type not in ( 'BD', 'BT' ) and id = '$budget_id' ";
	mysqli_query($con, $sql);
	$rowaffect = mysqli_affected_rows($con);
	if($rowaffect ==0){
//		$sql = " UPDATE sma_budget set blocked_budget = 0, used_budget = 0 WHERE id = '$budget_id' and (total_budget + adjustment_budget) - (blocked_budget + used_budget) < 0 ";
//		mysqli_query($con, $sql);
	}	
//if nagative value
	
//echo $sql ;			
echo "<BR>";
 
//	$sql = " UPDATE sma_budget set blocked_budget = 0 WHERE 1 and blocked_budget < 0 and id = '$budget_id' ";
//	mysqli_query($con, $sql);
	
?>
	
<?php }?>

	</div>
    </div>
</div>	


<?php 	
		include("../footer.php");	
?>

<!-- Select2 -->
<script src="<?php echo $baseurl . "plugins/select2/select2.full.js" ?>"></script>
<!-- InputMask -->
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.date.extensions.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.extensions.js" ?>"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo $baseurl . "plugins/ckeditor/ckeditor.js" ?>"></script>

<!-- DataTables 
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js" ?>"></script>
-->

<script>
	function getccname(id){

		var sub    		= 'sub14';
//alert(sub);		
		var company_id  = document.getElementById("PROJECT").value;
		
//alert(sub + ' ' + id + ' <<>> ' + company_id);
		var strURL 		= "app_func.php";
		$.post(strURL,{id:id,company_id:company_id,sub14:sub},function(result){
		      $('#getccname').html(result);
		});
		
	}
	
	function getccgroup(id){

		var sub    		= 'sub11';
//alert(sub);		
		var company_id  = document.getElementById("PROJECT").value;
		var account_year  = document.getElementById("account_year").value;
		
//alert(sub + ' ' + id + ' <<>> ' + company_id + ' ' + account_year);
		var strURL 		= "app_func.php";
		$.post(strURL,{id:id,account_year:account_year,company_id:company_id,sub11:sub},function(result){
		      $('#getccgroup').html(result);
		});
		
	}
	
  function getlocation(id){
		
        var sub    = 'sub15';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub15:sub},function(result){
		      $('#getlocation').html(result);
		});

	}	
		
	function getfindate(id){
			
        var sub    = 'sub16';
//alert(sub + ' ' + id);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub16:sub},function(result){
		      $('#getfindate').html(result);
		});

	}		
</script>

<?php
		
function moneyFormatIndia($num){
        $nums = explode(".",$num);
        if(count($nums)>2){
            return "0";
        }else{
        if(count($nums)==1){
            $nums[1]="00";
        }
        $num = $nums[0];
        $explrestunits = "" ;
        if(strlen($num)>3){
            $lastthree = substr($num, strlen($num)-3, strlen($num));
            $restunits = substr($num, 0, strlen($num)-3); 
            $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; 
            $expunit = str_split($restunits, 2);
            for($i=0; $i<sizeof($expunit); $i++){

                if($i==0)
                {
                    $explrestunits .= (int)$expunit[$i].","; 
                }else{
                    $explrestunits .= $expunit[$i].",";
                }
            }
            $thecash = $explrestunits.$lastthree;
        } else {
            $thecash = $num;
        }

		if($thecash==0){
			$nums = $nums[1];
			if($nums==0){
				$nums[1] = '';
			}
			$thecash = '';
		}
		else{
			$thecash = $thecash.".".$nums[1]; // with decimal eg. 123.12
			//$thecash = $thecash; // without decimal eg. 123
		}
        
		return $thecash;
    }
}

?>
<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>

<script>
    $(function () {
        $("#prtable").DataTable();
    });

    $(document).ready(function () {
        $('.datepicker').datepicker({
            "format": 'd/M/Y',
            "autoclose": true
        });
        $('.select2').select2();
        CKEDITOR.replace('reason');
        $("#prItemsTable").DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": false
        });
    });



</script>


</body>
</html>
