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
			
				
			//echo $_POST['project'] .' or '. $_POST['budget_name'] .' or '. $_POST['budget_head'];
				
				if ($_POST['project'] or $_POST['budget_name'] or $_POST['budget_head'] ){
					$_SESSION['project_a'] = $_POST['project'];
					$_SESSION['budget_name_a'] = $_POST['budget_name'];
					$_SESSION['budget_head_a'] = $_POST['budget_head'];
					$from_date		= $_POST['from_date'];
					$to_date		= $_POST['to_date'];
					
				}
				
				if ($_SESSION['project_a'] or $_SESSION['budget_name_a'] or $_SESSION['budget_head_a']){
					
					$project_v 		= $_SESSION['project_a'];
					$budget_name_v 	= $_SESSION['budget_name_a'];
					$budget_head_v 	= $_SESSION['budget_head_a'];
					
				}
	
				if ( !empty($_GET['reset']) || !empty($_SESSION['reset']) ){
				
					$_SESSION['project_a'] = '';
					$_SESSION['budget_name_a'] = '';
					$_SESSION['budget_head_a'] = '';
					$_SESSION['reset'] = '';
					$project_v 		= $_SESSION['project_a'];
					$budget_name_v 	= $_SESSION['budget_name_a'];
					$budget_head_v 	= $_SESSION['budget_head_a'];
				
				}

//echo $budget_head_v. "<BR>";

			$sql = "SELECT * FROM company WHERE 1 "; // comp_id = '$company_id' ";
			$q3  = mysqli_query($con, $sql);
			$r3  = mysqli_fetch_array($q3);
			$comp_start_date = date('d-m-Y', strtotime($r3['comp_start_date']));
			$comp_end_date 	 = date('d-m-Y', strtotime($r3['comp_end_date']));
			
			
	//	echo $project_v. ' >< '. $account_year_v;
			?>
					<form class="form-horizontal" action="budget_trans_list.php?sub=list&view=Y" method="post">
                      
						<div class="form-group">
							
							<div class="col-sm-4">
								<label for="project" class="control-label ">Company</label>
								<select class="form-control select2" name="project" id="PROJECT" required >
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
						
							<div class="col-md-3">
								<label class=" control-label">CC Group</label>
								<select class="form-control" required name="budget_name" id="budget_name" onchange="getccgroup(this.value);" >
									<option value=""> Select </option>
									
										<?php $sql = "select * from sma_budget_name order by name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($budget_name_v == $r2['id'])?'selected="selected"':'';?>> <?php echo $r2['name'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<div class="col-md-4">
								<label class="control-label">CC Sub Group</label>
							<span id="getccgroup" >	
								<select class="form-control" required name="budget_head" id="budget_head" >
									
									<option value=""> Select </option>
										<?php $sql = "select * from sma_budget WHERE budget_name = '$budget_name_v'  order by budget_head ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($budget_head_v == $r2['id'])?'selected="selected"':'';?>> <?php echo $r2['budget_head'];?></option>
										<?php } ?>
									</select>
								</span>
								
							</div>
						</div>
												
												
						<div class="form-group">	
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
		
		$sql = "truncate budget_view";
		mysqli_query($con, $sql);
		
//Approval Memo Start
		$sql = " SELECT distinct(a.id) as id, a.dated, a.company, '' as 'check_var'
			FROM `sma_approval_memo` a, `sma_approval_items` b , sma_budget c 
			WHERE 1 and a.id = b.approval_hdr_id and b.budget_id = c.id and a.del !='Y'  and a.company = '$project_v' ";
	
//echo $sql."<BR>";	
	$project_v = $_POST['project'];
	if( !empty($budget_name_v) ){
		$sql .= " and c.budget_name = '$budget_name_v' ";
	}
	
	if( !empty($budget_head_v) ){
		$sql .= " and c.id = '$budget_head_v' ";
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
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$budget_control_gst = $r3['budget_control_gst'];
			
		$sql = " SELECT * FROM `sma_approval_items` WHERE approval_hdr_id = '$doc_no' and budget_id = '$budget_head_v' ";
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
			
			$sql = " INSERT INTO budget_view (sort_type, doc_type, doc_no, doc_date, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var) 
					VALUES( '$sort_type', '$doc_type', '$doc_no', '$doc_date', '$budget_id', '$product_name', '$amount', '0', '0', '0', '$check_var' ) ";
			mysqli_query($con, $sql);

//echo $sql. "<BR>";
			
		}

	}
	
//exit();
//Approval Memo End


//Purchase Order Start (only for PO cum Approval)
	
		$sql = " SELECT distinct(a.id) as id, a.dated, a.project as company , po_type as 'check_var'
			FROM `sma_purchase_order` a, `sma_po_items` b , sma_budget c 
				WHERE 1 and a.id = b.purchase_id 
					and b.budget_id = c.id and a.del !='Y'
					 and a.project = '$project_v' "; //and po_type = 'C'
	
//echo $sql."<BR>";	
	$project_v = $_POST['project'];
	if( !empty($budget_name_v) ){
		$sql .= " and c.budget_name = '$budget_name_v' ";
	}
	
	if( !empty($budget_head_v) ){
		$sql .= " and c.id = '$budget_head_v' ";
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
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$budget_control_gst = $r3['budget_control_gst'];
			
		$sql = " SELECT * FROM `sma_po_items` WHERE purchase_id = '$doc_no' and budget_id = '$budget_head_v' ";
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
			
			$sql = " INSERT INTO budget_view (sort_type, doc_type, doc_no, doc_date, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var) 
					VALUES( '$sort_type', '$doc_type', '$doc_no', '$doc_date', '$budget_id', '$product_name', '$amount', '0', '0', '0' , '$check_var' ) ";
			mysqli_query($con, $sql);

//echo $sql. "<BR>";
			
		}

	}		
			
//Purchase Order End



//Supplier Invoice Start
			
		$sql = " SELECT distinct(a.id) as id, a.created_date as dated, a.company_id as company, '' as 'check_var' 
			FROM `sma_supplier_invoice` a, `sma_supplier_invoice_details` b , sma_budget c 
				WHERE 1 and a.id = b.si_hdr_id 
					and b.budget_id = c.id and a.del !='Y'
					 and a.company_id = '$project_v' ";
	
//echo $sql."<BR>";	
	//$project_v = $_POST['project'];
	if( !empty($budget_name_v) ){
		$sql .= " and c.budget_name = '$budget_name_v' ";
	}
	
	if( !empty($budget_head_v) ){
		$sql .= " and c.id = '$budget_head_v' ";
	}

//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$sort_type 		= 'C';
		$doc_type		= 'SI';
		$doc_no 		= $row['id'];
		$doc_date		= $row['dated'];
		$company_id		= $row['company'];
		$check_var		= $row['check_var'];
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$budget_control_gst = $r3['budget_control_gst'];
			
		$sql = " SELECT * FROM `sma_supplier_invoice_details` WHERE si_hdr_id = '$doc_no' and budget_id = '$budget_head_v' ";
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
			
			$sql = " INSERT INTO budget_view (sort_type, doc_type, doc_no, doc_date, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var) 
					VALUES( '$sort_type', '$doc_type', '$doc_no', '$doc_date', '$budget_id', '$product_name', '0', '$amount', '0', '0', '$check_var' ) ";
			mysqli_query($con, $sql);

//echo $sql. "<BR>";
			
		}

	}		
						
//Supplier Invoice End



//Operating Expense Start
			
		$sql = " SELECT distinct(a.id) as id, a.dated as dated, a.exp_type, a.company_id as company , a.approval_number as 'check_var'
			FROM `sma_travel_expenses` a, `sma_expenses` b , sma_budget c 
				WHERE 1 and a.id = b.approval_ref_no 
					and b.budget_id = c.id and a.del !='Y'
					 and a.company_id = '$project_v' ";
	
//echo $sql."<BR>";	and a.company_id in ($comid)
	//$project_v = $_POST['project'];
	if( !empty($budget_name_v) ){
		$sql .= " and c.budget_name = '$budget_name_v' ";
	}
	
	if( !empty($budget_head_v) ){
		$sql .= " and c.id = '$budget_head_v' ";
	}

//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$sort_type 		= 'D';
		$exp_type 		= $row['exp_type'];
		if($exp_type=='T'){
			$doc_type		= 'TE';
		}
		else if($exp_type=='C'){
			$doc_type		= 'OP';
		}
		if($exp_type=='R'){
			$doc_type		= 'RE';
		}
		
		$doc_no 		= $row['id'];
		$doc_date		= $row['dated'];
		$company_id		= $row['company'];
		$check_var		= $row['check_var'];
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$budget_control_gst = $r3['budget_control_gst'];
			
		$sql = " SELECT * FROM `sma_expenses` WHERE approval_ref_no = '$doc_no' and budget_id = '$budget_head_v' ";
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
			
			$sql = " INSERT INTO budget_view (sort_type, doc_type, doc_no, doc_date, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var) 
					VALUES( '$sort_type', '$doc_type', '$doc_no', '$doc_date', '$budget_id', '$product_name', '0', '$amount', '0', '0' , '$check_var') ";
			mysqli_query($con, $sql);

//echo $sql. "<BR>";
			
		}

	}		
						
//Operating Expense End


	}
	
	
	if($_GET['view']=='Y' ){
?>

	<div class="box">
    <table id="prtable123" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>Doc Type.</th>
			<th>Doc.No.</th>
			<th  style="text-align:left;">Dated</th>
			<th>Party/Items/Expense</th>
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
	
	$_SESSION['sql'] = $sql;
	
	$res		 = mysqli_query($con, $sql);
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
					$sql .= " LIMIT $start, $limit ";
					
//echo $sql."<BR>";

	include "paginate.php";
	
	$budget_id_prev		= '';
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){
	
		$doc_type	 		= $row['doc_type'];
		$doc_no	 			= $row['doc_no'];
		
		$product_name 		= $row['items'];
		
		$budget_id 			= $row['budget_id'];
		$check_var 			= $row['check_var'];
		
		
		if($budget_id_prev	!= $budget_id){
			$sql = " SELECT * from sma_budget WHERE id = '$budget_id' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$total_budget 		= $r2['total_budget'];
			$running_balance_budget = $total_budget;
?>			
			<tr>
				<td width="10%" style="text-align:left;"></td>
				<td width="10%" style="text-align:left;"></td>
				<td width="10%" style="text-align:left;"></td>
				<td width="15%" style="text-align:left;"><b>Opening Balance</b></td>
				<td width="10%" style="text-align:left;"></td>
				<td width="10%" style="text-align:left;"></td>
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
		
			$running_balance_budget		= $running_balance_budget - $adjustment_budget - ( $blocked_budget ); //+ $used_budget
		
		}
		else if( $doc_type=='PO' ){
			
			$doc_type = 'Purchase Order';
			
			if($check_var=='C'){
				$running_balance_budget	= $running_balance_budget - $adjustment_budget - ( $blocked_budget ); //+ $used_budget
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
		
		
		$baseurl1 = $baseurl.$modulePath1.'budget_trans_list.php?sub=edit&id='.$row["id"];
					
	?>
	<tr>
		
		<td width="10%" style="text-align:left;"><?php echo $doc_type;?></td>
		<td width="10%" style="text-align:left;"><?php echo $doc_no;?></td>
		<td width="10%" style="text-align:left;"><?php echo $dated;?></td>
		<td width="15%" style="text-align:left;"><?php echo $product_name;?></td>
		<td width="10%" style="text-align:right;"><?php echo moneyFormatIndia($blocked_budget);?></td>
		<td width="10%" style="text-align:right;"><?php echo moneyFormatIndia($used_budget);?></td>
		<td width="10%" style="text-align:right;"><?php echo moneyFormatIndia($adjustment_budget);?></td>
		<td width="10%" style="text-align:right;"><?php echo moneyFormatIndia($running_balance_budget);?></td>
		</td>
    </tr>
	
	<?php }?>
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

    <?php }?>

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
	function getccgroup(id){

		var sub    		= 'sub11';
//alert(sub);		
		var company_id  = document.getElementById("PROJECT").value;
		
//alert(sub + ' ' + id + ' <<>> ' + company_id);
		var strURL 		= "app_func.php";
		$.post(strURL,{id:id,company_id:company_id,sub11:sub},function(result){
		      $('#getccgroup').html(result);
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
