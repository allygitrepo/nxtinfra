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
				
				if ( $_POST['project'] or $_POST['budget_name'] or $_POST['budget_head'] or $_POST['account_year'] ){
					$account_year			= $_POST['account_year'];
					$_SESSION['project_a']  = $_POST['project'];
					$_SESSION['location_a'] = $_POST['location'];													   
					$_SESSION['budget_name_a'] = $_POST['budget_name'];
					$_SESSION['budget_head_a'] = $_POST['budget_head'];
					$from_date		= $_POST['from_date'];
					$to_date		= $_POST['to_date'];
					
				}
				
				if ($_SESSION['project_a'] or $_SESSION['budget_name_a'] or $_SESSION['budget_head_a']){
					
					$project_v 		= $_SESSION['project_a'];
					$location_v 	= $_SESSION['location_a'];											  
					$budget_name_v 	= $_SESSION['budget_name_a'];
					$budget_head_v 	= $_SESSION['budget_head_a'];
					
				}
	
				if ( !empty($_GET['reset']) || !empty($_SESSION['reset']) ){
				
					$_SESSION['project_a'] = '';
					$_SESSION['location_a'] 	= '';			   
					$_SESSION['budget_name_a'] = '';
					$_SESSION['budget_head_a'] = '';
					$_SESSION['reset'] = '';
					$project_v 		= $_SESSION['project_a'];
					$budget_name_v 	= $_SESSION['budget_name_a'];
					$budget_head_v 	= $_SESSION['budget_head_a'];
				
				}

//echo $budget_head_v. ' <<>> ' .$project_v. ' >< '. $account_year_v; "<BR>";

			$sql = "SELECT * FROM sma_financial_year WHERE 1 and  short_fy_code = '$account_year' ";
			$q3  = mysqli_query($con, $sql);
			$r3  = mysqli_fetch_array($q3);
			$comp_start_date = date('d-m-Y', strtotime($r3['from_date']));
			$comp_end_date 	 = date('d-m-Y', strtotime($r3['to_date']));
			
			?>
			<form class="form-horizontal" action="budget_all_trans_list.php?sub=list&view=Y" method="post">
                      
						<div class="form-group">
							<div class="col-md-2">
								<label class="control-label">Fin.Year</label>
								<select class="form-control" name="account_year" id="account_year" required onchange="getfindate(this.value)" >
									<option value=""> Select </option>
									<?php 
										$sql = " select * from sma_financial_year WHERE 1 order by short_fy_code ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ 
									?>
									<option value="<?php echo $r2['short_fy_code'];?>" <?php echo ($account_year == $r2['short_fy_code'])?'selected="selected"':'';?> > <?php echo $r2['short_fy_code'];?></option>
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
							if($comp_start_date=='01-01-1970'){
								$comp_start_date='';
							}
							if($comp_end_date=='01-01-1970'){
								$comp_end_date='';
							}							
						?>	
												
						<div class="form-group">	
							<span id="getfindate">
								<div class="col-md-2">
									<label class="control-label">From Date</label>
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="from_date" name="from_date" placeholder="" value="<?php echo $comp_start_date; ?>" >
										<div class="input-group-addon">
											<i class="fa fa-calendar-alt"></i>
										</div>
									</div>	
								</div>
								
								<div class="col-md-2">
									<label class="control-label">To Date</label>
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="to_date" name="to_date" placeholder="" value="<?php echo $comp_end_date;; ?>" >
										<div class="input-group-addon">
											<i class="fa fa-calendar-alt"></i>
										</div>
									</div>	
								</div>
							</span>	
						
							<div class="pull-right col-xs-1">	
								<span class="sepV_c marginRight">
									<a href="budget_all_trans_export.php?sub=pdf&sql=<?php echo $_SESSION['sql'];?>" target ="_blank" class="btn btn-primary">Export</a>
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
		
		$sql = " SELECT * FROM `sma_financial_year` where short_fy_code = '$account_year' ";
		$res = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($res);
		$from_date = $r2['from_date'];
		$to_date   = $r2['to_date'];
		
		$budget_id_v = '';
		if( !empty($budget_name_v) && !empty($budget_head_v) ){
				
			$sql = " SELECT * FROM `sma_budget` where account_year = '$account_year' and budget_name = '$budget_name_v' and budget_head = '$budget_head_v' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$budget_id_v = $r2['id'];
			
		}
//echo $budget_id_v. "<BR>";
		
		$sql = "truncate table budget_view";
		mysqli_query($con, $sql);
		
		
//Approval Memo Start
		$sql = " SELECT distinct(a.id) as id, a.dated, a.company, '' as 'check_var', b.budget_id, a.approval_status as status, a.doctype
			FROM `sma_approval_memo` a, `sma_approval_items` b , sma_budget c 
			WHERE 1 and approval_status not in ( 'Amend','Rejected' ) AND a.id = b.approval_hdr_id AND b.budget_id = c.id AND a.del !='Y'  
				AND c.account_year = '$account_year'
				AND a.company 		= '$project_v' 
				AND dated 	>= '$from_date' 
				AND dated 	<= '$to_date' 
				";
//echo $sql."<BR>";	
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

		$sort_type 		= 'A';
		$doc_type		= 'AP';
		$doc_no 		= $row['id'];
		$doc_date		= $row['dated'];
		$company_id		= $row['company'];
		$budget_id		= $row['budget_id'];
		$doctype		= $row['doctype'];
		$status			= $row['status'];
		
		$sql = "SELECT * FROM sma_approval_details WHERE approval_hdr_id = '$doc_no' and vendor_selected = 'Y' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$party_id 		= $r3['supplier_name'];
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$budget_control_gst = $r3['budget_control_gst'];
			
		$sql = " SELECT * FROM `sma_approval_items` WHERE approval_hdr_id = '$doc_no' and budget_id = '$budget_id' ";
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r2 = mysqli_fetch_array($res)){

			$product_id			= $r2['product_id'];
			$budget_id			= $r2['budget_id'];
			$quantity			= $r2['quantity'];
			$unit_rate			= $r2['unit_rate'];
			$gst				= $r2['gst'];
			
			$amount = round(($quantity * $unit_rate) + ((($quantity * $unit_rate) * $gst) / 100),0);
			if($budget_control_gst =='N'){
				$amount = $quantity * $unit_rate;
			}
		
			$sql  = " SELECT * FROM sma_product WHERE id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name	=$r3['name'];
			
			if($status=='Suspend' || $status=='Amend'){
				continue;
			}
			
			$sql = " INSERT INTO budget_view (sort_type, doc_type, doc_no, doc_date, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var , party_id , doctype, status) 
					VALUES( '$sort_type', '$doc_type', '$doc_no', '$doc_date', '$budget_id', '$product_name', '$amount', '0', '0', '0', '$check_var', '$party_id' , '$doctype', '$status' ) ";
			mysqli_query($con, $sql);

//echo $sql. "<BR>";
			
		}

	}
	
//exit();
//Approval Memo End


//Purchase Order Start (only for PO cum Approval)
	
		$sql = " SELECT distinct(a.id) as id, a.dated, a.project as company , po_type as 'check_var', b.budget_id, a.approval_status as status, a.status as po_status, approval_memo_ref, a.to_supplier as party_id
			FROM `sma_purchase_order` a, `sma_po_items` b , sma_budget c 
				WHERE 1 
					and a.id = b.purchase_id and approval_status not in ( 'Amend','Rejected' )
					and b.budget_id = c.id and a.del !='Y'
					AND c.account_year = '$account_year'
					 AND a.project = '$project_v' 
					 AND dated 	>= '$from_date' 
					 AND dated 	<= '$to_date' 
				"; //and po_type = 'C'
	
//echo $sql."<BR>";	
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
		$po_status		= $row['po_status'];
		$approval_memo_ref	= $row['approval_memo_ref'];
		
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
										  
			$amount = round(($quantity * $unit_rate) + ((($quantity * $unit_rate) * $gst) / 100),0);
			if($budget_control_gst =='N'){
				$amount = $quantity * $unit_rate;
			}
		
			if($po_status=='Closed'){
				$sql="SELECT qty, rate, gst , tds, sum((qty * rate) + (((qty * rate) * gst) / 100) ) as po_value FROM `sma_supplier_invoice` a, `sma_supplier_invoice_details` b where 1 and a.del !='Y' and a.id = b.si_hdr_id and a.our_po_ref_no  = '$purchase_id' ";
				$res1 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				while($r1 = mysqli_fetch_array($res1)){
					$amount 	= round($r1['po_value'],0);
				}	
			}
			
			$sql  = " SELECT * FROM sma_product WHERE id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name	=$r3['name'];
			
			$bal_amount = 0;
			if($status=='Suspend' || $status=='Amend'){
				//$amount = $amount * -1;
				$bal_si_qty			= $r2['bal_si_qty'];
				$bal_si_amount		= $r2['bal_si_amount'];
				
				$bal_amount			= ($amount - $bal_si_amount ) * -1;
				
				continue;
				
			}
			
			$sql = " INSERT INTO budget_view (sort_type, doc_type, doc_no, doc_date, po_srno, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var, status, party_id) 
					VALUES( '$sort_type', '$doc_type', '$doc_no', '$doc_date', '$approval_memo_ref', '$budget_id', '$product_name', '$amount', '0', '0', '0' , '$check_var', '$status', '$party_id' ) ";
			mysqli_query($con, $sql);

//echo $sql. "<BR>";
			
		}

	}		
			
//Purchase Order End



//Supplier Invoice Start
			
		$sql = " SELECT distinct(a.id) as id, a.created_date as dated, a.company_id as company, '' as 'check_var' ,b.budget_id, a.our_po_ref_no, suplier_name as party_id
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
			
			$amount = round(($quantity * $unit_rate) + ((($quantity * $unit_rate) * $gst) / 100),0);
			if($budget_control_gst =='N'){
				$amount = $quantity * $unit_rate;
			}
		
			$sql  = " SELECT * FROM sma_product WHERE id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name	=$r3['name'];
			
			if($amount>0){

				$sql = " INSERT INTO budget_view (sort_type, doc_type, doc_no,  po_srno, doc_date, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var, party_id) 
						VALUES( '$sort_type', '$doc_type', '$doc_no', '$our_po_ref_no', '$doc_date', '$budget_id', '$product_name', '0', '$amount', '0', '0', '$check_var', '$party_id' ) ";
				mysqli_query($con, $sql);
				
			}

//echo $sql. "<BR>";
			
		}

	}		
						
//Supplier Invoice End



//Operating Expense Start
			
		$sql = " SELECT distinct(a.id) as id, a.dated as dated, a.exp_type, a.company_id as company , a.approval_number as 'check_var', b.budget_id, approval_number, emp_id as party_id, onbehalf_emp_id
			FROM `sma_travel_expenses` a, `sma_expenses` b , sma_budget c 
				WHERE 1 AND a.approval_status !='Rejected'	 and a.id = b.approval_ref_no 
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
			
			$sql = " INSERT INTO budget_view (sort_type, doc_type, doc_no, doc_date, po_srno, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var, party_id ) 
					VALUES( '$sort_type', '$doc_type', '$doc_no', '$doc_date', '$approval_number', '$budget_id', '$product_name', '0', '$amount', '0', '0' , '$check_var', '$party_id' ) ";
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
				  AND dated   <= '$to_date' ";

//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$sort_type 		= 'E';
		$doc_type		= 'BD';
		$doc_no 		= $row['id'];
		$doc_date		= $row['dated'];
		$company_id		= $row['project'];
		$check_var 		= $row['effect'];
		$budget_id		= $row['budget_id'];
		$amount			= $row['amount'];
		$last_year_cf_block = $row['last_year_cf_block'];
//TEST RAVI				
//		if($last_year_cf_block=='Y'){
//			continue;	
//		}	
//TEST RAVI		
		$blocked_budget = 0;
		if($last_year_cf_block=='Y'){
			$blocked_budget 	= $amount;
			
			//$amount         = 0;
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
			<th>Party/Items/Expense</th>
			<th>PO.Value</th>
			<th  style="text-align:right;">Blocked</th>
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
			a.location, a.department, a.status, a.party_id, a.doctype
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
		$sql .= " ORDER BY budget_id, doc_date, sort_type, doc_no ";
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
		$doc_no	 			= $row['doc_no'];
		$po_srno			= $row['po_srno'];
		
		$product_name 		= $row['items'];
		$status		 		= $row['status'];
		
		$budget_id 			= $row['budget_id'];
		$party_id 			= $row['party_id'];
		$check_var 			= $row['check_var'];
		$doctype 			= $row['doctype'];
		
		$po_amount ='';
		$party_name = '';
		if($doc_type =='AP' || $doc_type =='PO' || $doc_type =='SI'  || $doc_type =='CE' || $doc_type =='OP' ){
			$sql = " SELECT * from sma_party_mst WHERE id = '$party_id' ";
				
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$party_name 		= $r2['party_name'];
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
		$blocked_budget 	= $row['blocked_budget'];
		$used_budget 		= $row['used_budget'];
		$adjustment_budget 	= $row['adjustment_budget'];
		//$balance_budget 	= $row['balance_budget'];
		if($check_var=='C' && $doc_type=='PO' ){
			
		}
		
		
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
			else {
				$running_balance_budget		= $running_balance_budget - $adjustment_budget - ( $blocked_budget ); //+ $used_budget
			}
			
			if($blocked_budget<0){
				$doc_type .= ' - '.$status;
			}
		
		}
		else if( $doc_type=='PO' ){
			
			$doc_type = 'Purchase Order';
			
			if($check_var=='C' && $blocked_budget >0 ){
				$running_balance_budget	= $running_balance_budget - $adjustment_budget - ( $blocked_budget ); //+ $used_budget
			}
				
				if($blocked_budget<0){
					$running_balance_budget	= $running_balance_budget  - ( $blocked_budget ); 
					$po_amount = $blocked_budget;
					$blocked_budget	='';
					$doc_type = 'PO Against Approval'. ' - '. $status ;
					
				}
				else{
					$po_amount = $blocked_budget;
					$blocked_budget	='';
					$doc_type = 'PO Against Approval' . ' - '. $status;
				}
				
				if($check_var=='C'){
					$doc_type = 'PO Cum Approval'. ' - '. $status ;
					$po_srno ='';
				}
				
			
		}
		else if( $doc_type=='SI' ){
			$doc_type = 'Supplier Invoice';
		}
		else if( $doc_type=='OP' ){
			
			$doc_type = 'Operating Expense';
			
			if(empty($check_var)){
				$running_balance_budget	= $running_balance_budget - $adjustment_budget - ( $used_budget  ); //+ $blocked_budget
			}
			
		}
		else if( $doc_type=='TE' ){
			
			$doc_type = 'Travel Expense';
			
			if(empty($check_var)){
				$running_balance_budget	= $running_balance_budget - $adjustment_budget - ( $used_budget  ); //+ $blocked_budget
			}
			
		}
		else if( $doc_type=='RE' ){
			
			$doc_type = 'Regular Expense';
			
			if(empty($check_var)){
				$running_balance_budget	= $running_balance_budget - $adjustment_budget - ( $used_budget  ); //+ $blocked_budget
			
			}
		}
		else if( $doc_type=='BD' ){
			//$adjustment_budget = $blocked_budget;
			$doc_type = 'Budget Adjustment';
			
			if($check_var=='I'){
				$running_balance_budget	= $running_balance_budget + $adjustment_budget ; 
			}
			else if($check_var=='D'){
				$running_balance_budget	= $running_balance_budget - $adjustment_budget ; 
				$adjustment_budget 		= $adjustment_budget * -1;
			}
			//$blocked_budget ='';
		}
		
		
		$baseurl1 = $baseurl.$modulePath1.'budget_trans_list.php?sub=edit&id='.$row["id"];
					
		$blocked_budget = moneyFormatIndia($blocked_budget);
		
		if($po_amount>0){
			$po_amount = moneyFormatIndia($po_amount);
		}
		else if ($po_amount<0){
			$blocked_budget = number_format($po_amount,2);
			$po_amount ='';
		}
		
		if($adjustment_budget==0){
			$adjustment_budget ='';
		}
		else {
			$adjustment_budget = number_format($adjustment_budget,2);
		}												  
		
		if($party_id> 0){
			$party_name = "<BR>($party_name) ";
		}	
		
		/* if($doc_type == 'OP'){
			$po_srno = $check_var;
		
		} */
		
		$running_balance_budget = round($running_balance_budget,2);
		
		if($running_balance_budget>0){
			$running_balance_budget_v = moneyFormatIndia($running_balance_budget);
		}
		else {
			$running_balance_budget_v = number_format($running_balance_budget,2);
			
		}
		
		
		if($used_budget>0){
			$used_budget_v = moneyFormatIndia($used_budget);
		}
		else {
			$used_budget_v = number_format($used_budget,2);
			
		}
		
		
	?>
	<tr>
		
		<td width="10%" style="text-align:left;"><?php echo $doc_type . ' - ' . $status;?></td>
		<td width="10%" style="text-align:left;"><?php echo $doc_no;?></td>
		<td width="10%" style="text-align:left;"><?php echo $po_srno;?></td>
		<td width="10%" style="text-align:left;"><?php echo $dated;?></td>
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
