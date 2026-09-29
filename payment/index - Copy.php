<!DOCTYPE html>
<?php

include("../header.php");
$modulePath = "payment/";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Payment Entry
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.$modulePath; ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Payment Entry</li>
      </ol>
    </section>

<div class="col-md-12">
	
		<div class="box box-info">
			<?php 
			
				if ($_POST['comp_id'] or $_POST['status'] or $_POST['approval_status']){
					$_SESSION['comp_id'] = $_POST['comp_id'];
					$_SESSION['statuss'] = $_POST['status'];
					$_SESSION['approval_status'] = $_POST['approval_status'];
					$_SESSION['reset'] = '';
				}
				
				if ($_SESSION['comp_id'] or $_SESSION['approval_status'] or $_SESSION['statuss']){
					$comp_id = $_SESSION['comp_id'];
					$status = $_SESSION['statuss'];
					$approval_status = $_SESSION['approval_status'];
				}
			
			if (!empty($_GET['reset']) || !empty($_SESSION['reset'])){
					$_SESSION['comp_id'] = '';
					$_SESSION['statuss'] = '';
					$_SESSION['reset'] = '';
					$_SESSION['approval_status'] = '';
					$comp_id = $_SESSION['comp_id'];
					$status = $_SESSION['statuss'];
					$approval_status = $_SESSION['approval_status'];
				}
			
			?>
					<form class="form-horizontal" action="index.php?sub=list" method="post">
                      
						<div class="form-group">
								
								<label class="col-lg-1 control-label">Company</label>
								<div class="col-md-3">
									<select class="form-control select2" name="comp_id" id="comp_id" >
										<option value=""> Select </option>
											<?php $sql = "select * from company order by comp_name ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['comp_id'];?>" <?php echo ($comp_id == $r2['comp_id'])?'selected="selected"':'';?>  ><?php echo $r2['comp_name'];?></option>
											<?php } ?>
									</select>
										
								</div>
								
								<label for="reqDate" class="col-lg-1 control-label">Status</label>
								<div class="col-md-2">
                                       <select class="form-control select2" name="status" id="status" >
										<option value=""> Select </option>
										<option value="Draft" <?php echo ($status == 'Draft')?'selected="selected"':'';?> > Draft </option>
										<option value="Submited" <?php echo ($status == 'Submited')?'selected="selected"':'';?>> Submited </option>
										<option value="Verified" <?php echo ($status == 'Verified')?'selected="selected"':'';?>> Verified </option>
										<option value="Completed" <?php echo ($status == 'Completed')?'selected="selected"':'';?>> Completed </option>
										</select>
								</div>

								<label for="reqDate" class="col-lg-1 control-label">Decision</label>
								<div class="col-md-2">
                                       <select class="form-control select2" name="approval_status" id="approval_status" >
										<option value=""> Select </option>
										<option value="Approved" <?php echo ($approval_status == 'Approved')?'selected="selected"':'';?>> Approved </option>
										<option value="Pending" <?php echo ($approval_status == 'Pending')?'selected="selected"':'';?>> Pending </option>
										<option value="Verified" <?php echo ($approval_status == 'Verified')?'selected="selected"':'';?>> Verified </option>
										<option value="Rejected" <?php echo ($approval_status == 'Rejected')?'selected="selected"':'';?>> Rejected </option>
										</select>
								</div>
								
							<div class="col-xs-2">
                                		
							<input class="btn btn-primary" type="submit" value="Search" name="Save">&nbsp;&nbsp;&nbsp;
							<a href="index.php?sub=list&reset=1" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
							</div>
							
						</div>
								
				</form>

            <div class="box-header with-border">
              <h3 class="box-title">Payment Entry List</h3>
            <div class="pull-right">
				<span class="sepV_c marginRight">
				
					<span class="pull-right"><a href="payment_export.php?sub=pdf" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp; Report </a></span>
				</span>
				<span class="sepV_c marginRight">
				<?php if($role=='Accountant'){ ?>
					<span class="pull-right"><a href="add.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create Payment </a>&nbsp;&nbsp;</span>
				<?php } ?>	
				</span>
			</div>
			</div>
		</div>	
	
    <div class="box">
    <table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th></th>
			<th>SrNo.</th>
			<th>Paid Date</th>
			<th>Paid via</th>
			<th>Paid To</th>
			<th>UTR.No.</th>
			<th>Dated.</th>
			<th>Supp.No.</td>
			<th style="text-align:right;">Amount Paid</th>
		    <th>By</th>
			<th>Status</th>
			<th>Decision</th>
			
<!--			<th style="text-align:right;">Action</th>-->
    
		</tr>
	</thead>
<tbody>
<?php	
	$user   = $_SESSION['user'];
	$role	= $_SESSION['role'];
	$comid = $_SESSION['comid'];
		//$sql="SELECT * from payment_header order by id desc";
	
	if ($role =='HOD - Account' || $role =='Project Manager'){
		$sql="SELECT * from payment_header where (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'PY') or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'PY')) or draft_by = '$user' ";
	}
	else if ($role =='Checker - Account' ){
		$sql="SELECT * from payment_header where (id in (SELECT doc_id FROM `workflow_history` where create_by = '$usrid' and doc_type = 'PY') or id in (SELECT doc_id FROM `workflow_history` where reviewed_by = '$usrid' and doc_type = 'PY')) or draft_by = '$user' ";
	}
	else if ($role =='Accountant'){
		$sql="SELECT * from payment_header where draft_by = '$user' and company_id in ($comid)  ";
	}
	else if ( $role == 'Checker'){
		$sql="SELECT * from payment_header where status = 'Completed' and company_id in ($comid) ";
	//	echo $sql;
	}
	else if ( $role =='Maker'){
		$sql = "SELECT * from payment_header where status = 'Completed' and company_id in ($comid) and id in (select payment_hdr_id from payment_details where supp_id in ( select id from sma_supplier_invoice where draft_by = '$user' )) ";
		//echo $sql;
	}
	else {
		$sql="SELECT * from payment_header where draft_by = '$user' ";
	}
	
	if($user=='Admin'){
		$sql="SELECT * from payment_header where id > 0 ";
	}
	
	//echo $_POST['comp_id']. ' <<>> '. $comp_id;
			
	if ($comp_id){
		$sql .= " and company_id = '$comp_id' ";
	}
	if ($status){
		$sql .= " and status = '$status' ";
	}
	if ($approval_status){
		$sql .= " and approval_status = '$approval_status' ";
	}
	
	$sql .= ' order by id desc ';
	
	//echo $sql;
	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){

		$cash_bank_name = $row['cash_bank_name'];
		$sql 	= "select * from account_mst where id = '$cash_bank_name' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$cash_bank_name = $r2['account_name'];
		
		$paid_to = $row['paid_to'];
		$st_flag = $row['st_flag'];
		if($st_flag =='A' || $st_flag =='T'){
			$sql = "SELECT * FROM `sma_user` where id = '$paid_to' ";
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$party_name  = $r2['username'];
		}
		else {
			$sql = "SELECT party_name FROM `sma_party_mst` where id = '$paid_to' ";
			$q2  = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($q2);
			$party_name  = $r2['party_name'];
		}
						
		
		$dated = date('d-m-Y', strtotime($row['dated']));
		if($dated =='01-01-1970'){
			$dated = '';
		}
		
		$rid = $row['id'];
		$sql = "SELECT supplier_invoice_no FROM `payment_details` where payment_hdr_id = '$rid' ";
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$supplier_invoice_no  = $r2['supplier_invoice_no'];
		
		$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$row["id"];
				
		?>
	<a href="<?php echo $baseurl . $modulePath . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="1%"><input type="hidden" value="<?php echo $row['id'];?>"></td>
		<td width="1%"><?php echo $row['id'];?></td>
		<td width="10%"><?php echo date('d-m-Y', strtotime($row['paid_date']));?></td>
		<td width="12%"><?php echo $cash_bank_name;?></td>
		<td width="12%"><?php echo $party_name;?></td>
		<td width="10%"><?php echo $row['utr_no'];?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%"><?php echo $supplier_invoice_no;?></td>
		<td width="10%" style="text-align:right;"><?php echo moneyFormatIndia($row['total_amount_paid']);?></td>
		<td width="08%"><?php echo $row['changed_by'];?></td>
		<td width="08%"><?php echo $row['status'];?></td>
		<td width="08%"><?php echo $row['approval_status'];?></td>

<!--	
		<td width="5%" style="text-align:right;">
		<a href="edit.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>

		<a href='#modalHistoryItem' data-id='<?php echo $rid;?>' data-mode='edit' data-toggle='modal' data-target='#modalHistoryItem<?php echo $rid;?>' title="History" > <i class='fa fa-history' ></i></a>
			<?php include "view_history.php"; ?>
		
		<!--<a href="edit.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
		
<!--		</td>-->
    </tr>
	</a>
	
	<?php }
	
	
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
			$thecash = $thecash.".".$nums[1];
		}
        
		return $thecash;
    }
}
?>
</tbody> 
</table>
	</div>
    </div>
</div>	

<!-- jQuery 2.2.3 -->
<script src="<?php echo $baseurl . "plugins/jQuery/jquery-2.2.3.min.js"?>"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo $baseurl . "bootstrap/js/bootstrap.min.js"?>"></script>
<!-- DataTables -->

<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"?>"></script>

<!-- SlimScroll -->
<script src=<?php echo $baseurl . "plugins/slimScroll/jquery.slimscroll.min.js"?>"></script>
<!-- FastClick -->
<script src="<?php echo $baseurl . "plugins/fastclick/fastclick.js"?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo $baseurl . "dist/js/app.min.js"?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo $baseurl . "dist/js/demo.js"?>"></script>
<!-- page script -->

<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>


<script>
    $(function () {
        $("#prtable").DataTable();
    });
</script>

<script>
  $(function () {
  //  $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
    $('#example1').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": false,
      "info": true,
      "autoWidth": true
    });
	
  });
</script>

