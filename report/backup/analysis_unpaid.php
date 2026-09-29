<?php
session_start();
include("../dbcon.php");
include("../baseurl.php");

if($_GET['sub']=='pro'){
	
	$comid  = $_SESSION['comid'];
	
	$sql = "truncate analysis_unpaid";
	mysqli_query($con, $sql);
	
//Supplier Invoice Start
	$sql 	= "SELECt a.*, DS.supp_id, DS.payment_hdr_id , DS.st_flag, DS.utr_no 
				FROM sma_supplier_invoice a
				LEFT JOIN 
					(SELECT a.id as supp_id, b.payment_hdr_id , c.st_flag, c.utr_no
					FROM sma_supplier_invoice  a
					LEFT JOIN  payment_details b
					ON b.supp_id = a.id  
					LEFT JOIN  payment_header c
					ON b.payment_hdr_id = c.id where c.st_flag = 'S' and c.del !='Y' ) as DS 
				ON a.id = DS.supp_id  and (DS.utr_no!='' OR DS.utr_no='' OR DS.utr_no is NULL)
				and a.del !='Y' and company_id in ($comid)
				ORDER BY `a`.`id` ASC";
//echo $sql."<BR>";	
//exit();
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
					
	while($row = mysqli_fetch_array($result)){
		$del				= $row['del'];	
		if($del =='Y'){
			continue;
		}
		include "analysis_pending_common.php";
		
		$doc_id				= $row['id'];		
		$company_id			= $row['company_id'];
		$amount				= $row['payable_amount'];
		$utr_no				= $row['utr_no'];
		$module				= 'SI';
		$status				= $row['status'];

		if($status=='Completed'){
			$pending_with='';
		}
		
			$sql  = " SELECT * from sma_supplier_invoice where id = '$doc_id' and del !='Y' ";
			$res  = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r1 = mysqli_fetch_array($res);
			$our_po_ref_no	= $r1['our_po_ref_no'];
					
			$sql  = " SELECT * from sma_purchase_order where advance_flag = 'Y' and del !='Y' and id = '$our_po_ref_no' ";
					
			$res  = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r1 = mysqli_fetch_array($res);
			$advance_paid_amount	= $r1['paid_amount'];
			$total_po_amount		= $r1['total_po_amount'];
			$paid_status			= $r1['paid_status'];
			if($paid_status=='Paid'){
				continue;
			}
					
		if(empty($utr_no) && $status=='Completed'){
			$sql = "INSERT INTO analysis_unpaid (company_with, amount, module, doc_id, utr_no, pending_with, status) 
					VALUES ('$company_id', '$amount', '$module','$doc_id','$utr_no','$pending_with', '$status')";
			mysqli_query($con, $sql);
			mysqli_error($con);

		}
		
	}
//Supplier Invoice End

//Operating Expense Start
		$sql 	= "SELECT a.*, DS.supp_id, DS.payment_hdr_id , DS.st_flag, DS.utr_no 
				FROM sma_travel_expenses a
				LEFT JOIN 
					(SELECT a.id as supp_id, b.payment_hdr_id , c.st_flag, c.utr_no
					FROM sma_travel_expenses  a
					LEFT JOIN  payment_details b
					ON b.supp_id = a.id  
					LEFT JOIN  payment_header c
					ON b.payment_hdr_id = c.id where c.st_flag = 'C' and c.del !='Y' ) as DS 
					ON a.id = DS.supp_id  and (DS.utr_no!='' OR DS.utr_no='' OR DS.utr_no is NULL)
					and a.del !='Y' and a.status = 'Completed' and company_id in ($comid)
				ORDER BY `a`.`id` ASC ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
					
	while($row = mysqli_fetch_array($result)){
		$del				= $row['del'];	
		if($del =='Y'){
			continue;
		}
		include "analysis_pending_common.php";
		
		$doc_id				= $row['id'];
		if($doc_id == 38){
			continue;
		}			
		$company_id			= $row['company_id'];
		
		$amount				= $row['total_amount'];
		$utr_no				= $row['utr_no'];
		//$module			= 'CE';
		$status				= $row['status'];
		$exp_type			= $row['exp_type'];
		if($exp_type=='C'){
			$module				= 'CE';
		}
		if($status=='Completed'){
			$pending_with='';
		}

		if(empty($utr_no) && $exp_type=='C' && $status=='Completed' ){
			$sql = "INSERT INTO analysis_unpaid (company_with, amount, module, doc_id, utr_no, pending_with, status) 
					VALUES ('$company_id', '$amount', '$module','$doc_id', '$utr_no', '$pending_with', '$status')";
			mysqli_query($con, $sql);
		}
		
	}
//Operating Expense End

//Travel/Regular Expense Start
		$sql 	= "SELECT a.*, DS.supp_id, DS.payment_hdr_id , DS.st_flag, DS.utr_no 
				FROM sma_travel_expenses a
				LEFT JOIN 
					(SELECT a.id as supp_id, b.payment_hdr_id , c.st_flag, c.utr_no
					FROM sma_travel_expenses  a
					LEFT JOIN  payment_details b
					ON b.supp_id = a.id  
					LEFT JOIN  payment_header c
					ON b.payment_hdr_id = c.id where c.st_flag = 'T' and c.del !='Y') as DS 
					ON a.id = DS.supp_id  and (DS.utr_no!='' OR DS.utr_no='' OR DS.utr_no is NULL)
					and a.del !='Y' and a.status = 'Completed' and company_id in ($comid)
				ORDER BY `a`.`id` ASC ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
					
	while($row = mysqli_fetch_array($result)){
		$del				= $row['del'];	
		if($del =='Y'){
			continue;
		}
		include "analysis_pending_common.php";
		
		
		$doc_id				= $row['id'];	
		$company_id			= $row['company_id'];
		$amount				= $row['total_amount'];
		$utr_no				= $row['utr_no'];
		$exp_type			= $row['exp_type'];
		if($exp_type=='C'){
			continue;
		}	
		if($exp_type=='T'){
			$module				= 'TE';
		}
		if($exp_type=='R'){
			$module				= 'RE';
		}
		
		$status				= $row['status'];
		if($status=='Completed'){
			$pending_with = '';
		}
	
		if(empty($utr_no) && $status=='Completed'){
			$sql = "INSERT INTO analysis_unpaid (company_with, amount, module, doc_id, utr_no, pending_with, status) 
					VALUES ('$company_id', '$amount', '$module','$doc_id','$utr_no','$pending_with', '$status')";
			mysqli_query($con, $sql);
		}
		
	}
//Travel /Regular Expense End

	echo "Process Over...";

//exit();

	$baseurl1= $baseurl."report/analysis_unpaid.php?sub=list";
	echo "<script>window.location.href='$baseurl1';</script>";
	
}

?>


<?php

if($_GET['sub']=='list'){
		
	include("../header.php");
	$modulePath = "report/";

	$userid   	= $_SESSION['usrid'];
	$help_code = $modulePath.'analysis_unpaid.php';
	include "../help_code.php";

	$pgname = $help_code;
	include("../viewonly.php");
	
?>
<!-- DataTables -->
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Unpaid Transactions<small></small>
		<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
		</small>
      </h1>
      <ol class="breadcrumb">
		
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Unpaid Transactions</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header123">
			</div>
		
            <!-- /.box-header -->
            <div class="box-body">
              
<?php
	
	$sql = " TRUNCATE analysis_unpaid_matrix";
	mysqli_query($con, $sql);
	
	$sql="SELECT company_with FROM `analysis_unpaid` where 1 and company_with in ($comid) group by  company_with ";
	$sql .= ' order by company_with, module ';
	$result = mysqli_query($con, $sql);
		echo mysqli_error($con);					
		while($row = mysqli_fetch_array($result)){
			
			$company_with		= $row['company_with'];
			
			$sql = " INSERT INTO analysis_unpaid_matrix ( company_with ) VALUE ('$company_with' )";
			mysqli_query($con, $sql);
			
	}
			
	$sql="SELECT module, company_with, count(*) as cnt, sum(amount) as amount  FROM `analysis_unpaid` where 1 and company_with in ($comid) group by module, company_with ";
	$sql .= ' order by company_with, module ';

	$result = mysqli_query($con, $sql);
		echo mysqli_error($con);					
		while($row = mysqli_fetch_array($result)){
			
			$count 				= $row['cnt'];
			$amount 			= $row['amount'];
			$module 			= $row['module'];
			$company_with		= $row['company_with'];
			
			
			if($module=='PO'){
				$sql = " UPDATE analysis_unpaid_matrix SET PO = '$count', PO_amount = '$amount' where company_with = '$company_with' ";
			}
			if($module=='SI'){
				$sql = " UPDATE analysis_unpaid_matrix SET SI = '$count', SI_amount = '$amount' where company_with = '$company_with' ";
			}
			if($module=='CE'){
				$sql = " UPDATE analysis_unpaid_matrix SET CE = '$count', CE_amount = '$amount' where company_with = '$company_with' ";
			}
			if($module=='TE'){
				$sql = " UPDATE analysis_unpaid_matrix SET TE = '$count', TE_amount = '$amount' where company_with = '$company_with' ";
			}
			if($module=='RE'){
				$sql = " UPDATE analysis_unpaid_matrix SET RE = '$count', RE_amount = '$amount' where company_with = '$company_with' ";
			}
			
			mysqli_query($con, $sql);
			echo mysqli_error($con);
			
		}	
		
	$module_hdr ='';	
	$sql="SELECT  sum(PO) as PO,sum(SI) as SI,sum(CE) as CE, sum(TE) as TE,sum(RE) as RE FROM `analysis_unpaid_matrix` where 1  ";

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){
				
			$PO 				= $row['PO'];
			$SI 				= $row['SI'];
			$CE 				= $row['CE'];
			$TE 				= $row['TE'];
			$RE 				= $row['RE'];
			
			$GPO += $PO ;
			$GSI += $SI ;
			$GCE += $CE ;
			$GTE += $TE ;
			$GRE += $RE ;
			
			if($PO>0){
				$module_name = 'Purchase Order';
				$module_hdr 			.= '<th>'.$module_name.'</th>';
			}
			 if($SI>0){
				$module_name = 'Invoice Against GRN ';
				$module_hdr 			.= '<th>'.$module_name.'</th>';
			}
			 if($CE>0){
				$module_name = 'Invoice Against OpEx';
				$module_hdr 			.= '<th>'.$module_name.'</th>';
			}
			 if($TE>0){
				$module_name = 'Travel Expense';
				$module_hdr 			.= '<th>'.$module_name.'</th>';
			}
			 if($RE>0){
				$module_name = 'Reimbursement';
				$module_hdr 			.= '<th>'.$module_name.'</th>';
			}
			 
	}

	
?>
	
	<table id="prtable123" class="table table-bordered table-striped">
                <thead>
                <tr style="background-color:#A4F5CA;">
                    <!--<th></th>-->
					<th>Company With</th>
                    <?= $module_hdr; ?>
					<th style="text-align:center;">Total</th>
					
				</tr>
                </thead>
                <tbody>
<?php				
	$sql="SELECT * FROM `analysis_unpaid_matrix` where 1 order by company_with ";

		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
					
		while($row = mysqli_fetch_array($result)){
				
			$PO 				= $row['PO'];
			$SI 				= $row['SI'];
			$CE 				= $row['CE'];
			$TE 				= $row['TE'];
			$RE 				= $row['RE'];
			
			$PO_amount 			= $row['PO_amount'];
			$SI_amount 			= $row['SI_amount'];
			$CE_amount 			= $row['CE_amount'];
			$TE_amount 			= $row['TE_amount'];
			$RE_amount 			= $row['RE_amount'];
			
			$GPO_amount += $PO_amount ;
			$GSI_amount += $SI_amount ;
			$GCE_amount += $CE_amount ;
			$GTE_amount += $TE_amount ;
			$GRE_amount += $RE_amount ;
			
			$GTOT = $PO + $SI + $CE + $TE + $RE ;
			$GPO += $PO ;
			$GSI += $SI ;
			$GCE += $CE ;
			$GTE += $TE ;
			$GRE += $RE ;
			
			if($GPO>0){
				$module 			= 'PO';
			}
			if($GSI>0){
				$module 			= 'SI';
			}
			if($GCE>0){
				$module 			= 'CE';
			}
			if($GTE>0){
				$module 			= 'TE';
			}
			if($GRE>0){
				$module 			= 'RE';
			}
			
			$company_with			= $row['company_with'];
				
			$sql 	= "select * from company where comp_id = '$company_with' ";
			$q22  	= mysqli_query($con, $sql);
			$r22 	= mysqli_fetch_array($q22);
			//$company_with_name  = $r22['comp_name'];
			$company_with_name  = $r22['comp_code'];
						
			$j 		= $j +1;			
			$company_with1		= $company_with;
			$module1			= $module;
			
		?>
	
		<?php if($GTOT>0){ ?>
		<tr>
			<!--<td width="1%" ><input type="hidden" value="<?php echo $j;?>" > </td>-->
			<td width="20%" style="background-color:#94EABD;color:black;" ><?php echo $company_with_name;?></td>
		<?php } ?>	
		<?php if($GPO>0){ $module1 ='PO';
					$disbtn = '';
					if($PO>0){
						$disbtn = 'btn btn-success';
					}
					else {
						$PO		= '';
					}
		?>
			<th width="10%" style="text-align:center;" ><a href="#" class="<?= $disbtn;?>" data-mode='Add' data-toggle="modal" data-target="#modalListUnpaid<?php echo $company_with1;?><?php echo $module1;?>" style="text-align:center;"><?php echo $PO;?></a>
				<?php include "modalListUnpaid.php"; ?>
			</th>
		<?php } ?>
		<?php if($GSI>0){ $module1 ='SI';
					$disbtn = '';
					if($SI>0){
						$disbtn = 'btn btn-success';
					}
					else {
						$SI		= '';
					}
		?>
			<th width="10%" style="text-align:center;"><a href="#" class="<?= $disbtn;?>" data-mode='Add' data-toggle="modal" data-target="#modalListUnpaid<?php echo $company_with1;?><?php echo $module1;?>"><?php echo $SI;?></a>
				<?php include "modalListUnpaid.php"; ?>
			</th>
		<?php } ?>
		<?php if($GCE>0){ $module1 ='CE';
					$disbtn = '';
					if($CE>0){
						$disbtn = 'btn btn-success';
					}
					else {
						$CE		= '';
					}		
			?>
			
			<th width="10%" style="text-align:center;"><a href="#" class="<?= $disbtn;?>" data-mode='Add' data-toggle="modal" data-target="#modalListUnpaid<?php echo $company_with1;?><?php echo $module1;?>"><?php echo $CE;?></a>
				<?php include "modalListUnpaid.php"; ?>
			</th>
		<?php } ?>
		<?php if($GTE>0){ $module1 ='TE';
					$disbtn = '';
					if($TE>0){
						$disbtn = 'btn btn-success';
					}
					else {
						$TE		= '';
					}
		?>
			<th width="10%" style="text-align:center;"><a href="#" class="<?= $disbtn;?>" data-mode='Add' data-toggle="modal" data-target="#modalListUnpaid<?php echo $company_with1;?><?php echo $module1;?>"><?php echo $TE;?></a>
				<?php include "modalListUnpaid.php"; ?>
			</th>
		<?php } ?>
		<?php if($GRE>0){ $module1 ='RE';
					$disbtn = '';
					if($RE>0){
						$disbtn = 'btn btn-success';
					}
					else {
						$RE		= '';
					}
		?>
			<th width="10%" style="text-align:center;"><a href="#" class="<?= $disbtn;?>" data-mode='Add' data-toggle="modal" data-target="#modalListUnpaid<?php echo $company_with1;?><?php echo $module1;?>"><?php echo $RE;?> </a>
				<?php include "modalListUnpaid.php"; ?>
			</th>
		<?php } ?>
		
		<?php 
			$comp_total = $SI_amount + $CE_amount + $TE_amount + $RE_amount ;
			
			$comp_grand_total = $comp_grand_total + $comp_total
		?>	
			
			
		</tr>
		
		<tr style="background-color:#F96009;color:white;">
			<!--<td width="1%" >&nbsp;</td>-->
				<td width="20%" style="text-align:right;">Total Amount in Rs.</td>
			<?php	if($PO_amount>0){ ?>
				<th width="10%" style="text-align:right;" ><?php echo moneyFormatIndiaa($PO_amount);?></th>
			<?php } 
				if($SI_amount==0){ 
					$SI_amount='';
				}
				if($CE_amount==0){ 
					$CE_amount='';
				}
				if($TE_amount==0){ 
					$TE_amount='';
				}
				if($RE_amount==0){ 
					$RE_amount='';
				}
				
			?>	
			
			<?php if($GSI>0){ ?>
				<th width="10%" style="text-align:right;" ><?php echo moneyFormatIndiaa($SI_amount);?></th>
			<?php } ?>
			<?php if($GCE>0){ ?>
				<th width="10%" style="text-align:right;" ><?php echo moneyFormatIndiaa($CE_amount);?></th>
			<?php } ?>	
			<?php if($GTE>0){ ?>
				<th width="10%" style="text-align:right;" ><?php echo moneyFormatIndiaa($TE_amount);?></th>
			<?php } ?>	
			<?php if($GRE>0){ ?>
				<th width="10%" style="text-align:right;" ><?php echo moneyFormatIndiaa($RE_amount);?></th>
			<?php } ?>
			<?php if($comp_total>0){ ?>
				<th width="10%" style="text-align:right;"><?php echo moneyFormatIndiaa(round($comp_total,0));?>
			<?php } ?>	
			</th>
				
		</tr>
		
		
<?php } 
?>
				
                </tbody>
                <tfoot>
					<tr style="background-color:#94EABD;color:black;">
						<!--<td></td>-->
						<th  style="text-align:right;">Total</td>
				<?php if($GSI_amount>0){ ?>		
						<th style="text-align:right;"><?= moneyFormatIndiaa(round($GSI_amount,0)); ?></th>
				<?php } ?>
				<?php if($GCE_amount>0){ ?>
						<th style="text-align:right;"><?= moneyFormatIndiaa(round($GCE_amount,0)); ?></th>
				<?php } ?>
				<?php if($GTE_amount>0){ ?>		
						<th style="text-align:right;"><?= moneyFormatIndiaa(round($GTE_amount,0)); ?></th>
				<?php } ?>
				<?php if($GRE_amount>0){ ?>		
						<th style="text-align:right;"><?= moneyFormatIndiaa(round($GRE_amount,0)); ?></th>
				<?php } ?>
				<?php if($comp_grand_total>0){ ?>		
						<th style="text-align:right;"><?= moneyFormatIndiaa(round($comp_grand_total,0)); ?></th>
				<?php } ?>		
						
					</tr>
                </tfoot>
              </table>			  

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>

</div>
<!-- ./wrapper -->
<?php
include("../footer.php");
?>

<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>

<script>
    $(function () {
        $("#prtable").DataTable();
    });

</script>

</body>
</html>

<?php } ?>

