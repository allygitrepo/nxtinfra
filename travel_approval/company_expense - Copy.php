<!DOCTYPE html>
<?php

include("../header.php");
$modulePath = "travel_approval/";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Operating Expenses
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Operating Expenses</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of Operating Expenses</h3>
                <span class="pull-right"><a href="company_expense.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create Operating Expenses </a></span>
				<span class="pull-right"><a href="#modalExport"
                                               class="btn btn-primary"
                                               data-toggle="modal" 
                                               data-mode="add"
                                               data-target="#modalExport" class="btn btn-primary">Report</a> &nbsp;&nbsp;&nbsp;
				</span>
				
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>#</th>
			<th>SrNo.</th>
			<th>Name</th>
			<th>Company</th>
			<th>Date</th>
			<th>Total Amount</th>
			<th>By</th>
			<th>Status</th>
			<th>Decision</th>
			
		</tr>
	</thead>
<tbody>
<?php

	$modulePath1 = "travel_approval/";
	
	$department = $_SESSION['department'];
	
		$sql = " SELECT * from sma_travel_expenses where exp_type = 'C' and company_id in ( $comid ) and draft_by = '$user' and status != 'Withdraw' ";
		if($department=='12' || $role=='accountant' ){
			
			$sql .= " union SELECT * from sma_travel_expenses where exp_type = 'C' and company_id in ( $comid ) and  status not in ( 'Withdraw') ";
			
		}
	
	if($department=='12' || $role=='accountant' ){
		
		$sql = " SELECT * from sma_travel_expenses where exp_type = 'C' and company_id in ( $comid ) and status not in ( 'Withdraw') ";
		
	}
	else {
		$sql = " SELECT * from sma_travel_expenses where exp_type = 'C' and company_id in ( $comid ) and draft_by = '$user' and status != 'Withdraw' 
		         union  
				 SELECT * from sma_travel_expenses where exp_type = 'C' and company_id in ( $comid ) and level_1 = '$usrid' ";
	}
	
	if ($user=='Admin'  || $role =='CXO' ){
		
		$sql = " SELECT * from sma_travel_expenses where exp_type = 'C' ";
		
	}

//status = 'Submited' and

	$sql.=" order by id desc ";
//echo $department . ' ' ;
//echo $sql;

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){

		$company_id  = $row['company_id'];
		$sql  = "SELECT * from company where comp_id = '$company_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 	= mysqli_fetch_array($res1);
		$comp_name 	= $r1['comp_name'];

		$sma_vendor_id = $row['emp_id'];
		$sql  = "SELECT * from sma_party_mst where id = '$sma_vendor_id' ";
//echo $sql;		
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$party_name		= $r1['party_name'];
		
		$re_id = $row["id"];
		$sql  = "SELECT sum(amount) as amount FROM `sma_expenses` where exp_type = 'C' and approval_ref_no = '$re_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$amount		= $r1['amount'];
		 
		$dated 		=  date('d-m-Y', strtotime($row['dated']));
		if( $dated=='01-01-1970' ){ $dated='';}
		
		$baseurl1 	= $baseurl.$modulePath1.'company_expense.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "company_expense.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>" ></td>
		<td width="4%"><?php echo $row['id'];?></td>
		<td width="20%"><?php echo $party_name;?></td>
		<td width="25%"><?php echo $comp_name;?></td>
		<td width="10%"><?php echo $dated;?></td>
		<td width="10%" style="text-align:right;" ><?php echo $amount;?></td>
		<td width="10%"><?php echo $row['changed_by'];?></td>
		<td width="10%"><?php echo $row['status'];?></td>
		<td width="10%"><?php echo $row['approval_status'];?></td>
		
<!--		<td width="5%" style="text-align:right;">
		<a href="company_expense.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>-->
		
		</td>
    </tr>
	</a>
	<?php }?>
</tbody> 
</table>
	</div>
    </div>
</div>	

    <?php }?>


<?php  
	if($_GET['sub'] == 'delete'){
        $id = $_GET['id'];
		$sql="delete from sma_travel_expenses where exp_type = 'C' and id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo 	mysqli_error($con);
        echo 	'<script>window.location.href="company_expense.php?sub=list";</script>';
	}
?>

<?php

	if(isset($_POST['editItem'])){

		$rid     		= $_POST['rid'];
		$dated			= date('Y-m-d', strtotime($_POST['dated']));
		$approval_ref_no= $_POST['approval_ref_no'];
		$reference 		= $_POST['reference'];
		$invoice_no 	= $_POST['invoice_no'];
		$amount 		= $_POST['amount'];
		$note 			= $_POST['remarks'];
		$gst_flag 		= $_POST['gst_flag'];
		
		$sql = "update `sma_expenses` set dated	= '$dated',
						reference 		= '$reference',
						invoice_no 		= '$invoice_no',
						amount 			= '$amount',
						note 			= '$note',
						gst_flag 		= '$gst_flag'
				where id = '$rid' ";
		$r2 = mysqli_query($con, $sql);
	
	echo "<meta http-equiv='refresh' content='0'>";    
	//$baseurl.=$modulePath.'edit.php?approval_ref_no='.$approval_ref_no.'&active=active&987';
	//echo "<script>window.location.href='$baseurl';</script>";
	echo "<script>window.location.href='company_expense.php?sub=edit&id=$rid';</script>";
}
 
?>
 
<?php if($_GET['sub'] == 'add'){
?>


<?php
	if(isset($_POST['Save'])){
			
  			$emp_id 			= $_POST['sma_vendor_id'];
			$company_id 		= $_POST['company_id'];
			$dated				= date('d-m-Y', strtotime($_POST['dated']));
			$approval_ref_no	= $_POST['approval_ref_no'];
			$total_amount		= $_POST['total_amount'];
			$datedd				= date('Y-m-d', strtotime($_POST['dated']));
			$remarks	 	 	= $_POST['remarks'];
			$exp_type			= 'C';
			$purchase_requisition =	$_POST['purchase_requisition'];
			
			$status 			= 'Draft';

			$user=$_SESSION['user'];

  			$sql="Insert into sma_travel_expenses (id, exp_type, emp_id, company_id, dated, approval_ref_no, status, draft_by, draft_dated , purchase_requisition ) values ('$approval_ref_no', '$exp_type', '$emp_id', '$company_id', '$datedd', '$approval_ref_no',  'Draft', '$user', now(), '$purchase_requisition' ) ";
			
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			$re_id = mysqli_insert_id($con);
			
//			echo "Operating Expenses successful added";
			
			echo "<script>window.location.href='company_expense.php?sub=edit&id=$re_id&next=active';</script>";
			
			echo '<script>window.location.href="company_expense.php?sub=list";</script>';
		}
	

?>

    <section class="content-header">
        <h1>
            Operating Expenses
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Operating Expenses</a></li>
            <li class="active">Create</li>
        </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            
            <!-- /.box-header -->
            <div class="box-body">
            <!-- form start -->
            <form class="form-horizontal" action="company_expense.php?sub=add" method="post" enctype="multipart/form-data" >
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
						<?php $sql = "select max(id) as id from sma_travel_expenses ";
								$q2 = mysqli_query($con, $sql);
								$r2 = mysqli_fetch_array($q2);
								$re_id	=	$r2['id'] + 1;
						?>				
						<input type="hidden" class="form-control" id="id" name="id" value="<?php echo $re_id; ?>">
						
						<div class="form-group">
						
							<label class="col-lg-2 control-label">SrNo.</label>
							<div class="col-md-2">
							
								<input type="text" class="form-control" id="approval_ref_No" name="approval_ref_no" value="<?php echo $re_id; ?>">
								
							</div>
							
							<label class="col-lg-1 control-label">Date</label>
							<div class="col-md-2">
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
								<input type="text" class="form-control"  id="dp1" name="dated" autocomplete="off" readonly <?php echo $readonly; ?> value="<?php echo date('d-m-Y');?>" > 
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
							</div>
							
							<label class="col-lg-1 control-label">Company </label>
							<div class="col-md-4">
								<select class="form-control" name="company_id" id="company_id" autocomplete="off" required  >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_id'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
								</select>
							</div>
						
						</div>	
												
						<div class="form-group">
						
							<label class="col-lg-2 control-label">Vendor</label>
							<div class="col-md-4">
								<select class="form-control" name="sma_vendor_id" id="sma_vendor_id" autocomplete="off" required  >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_party_mst where party_kyc = 'Y' order by party_name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" > <?php echo $r2['party_name'];?></option>
										<?php } ?>
								</select>
							</div>
						
							<label class="col-lg-2 control-label">Purchase&nbsp;Requisition&nbsp;No.</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="purchase_requisition" name="purchase_requisition"  value="">
							</div>
							
						</div>
						
						<div class="form-group">
							
							<label class="col-lg-2 control-label">Remarks</label>
							<div class="col-md-10">
								<textarea class="form-control" rows="2" name="remarks"  autocomplete="off" ><?php echo $row['remarks']?></textarea>
							</div>
						
						</div>
						
					<div class="panel-group" id="steps">
                        
							<!-- Step 1 -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepTwo" class="btn btn-info dropdown-toggle"><i class="fa fa-expand "></i>&nbsp;&nbsp; Expenses <span class="caret"></span></a>
								
								</h4>
                            </div>
                            <div id="stepTwo" class="panel-collapse collapse in">
								<div class="panel-body">
								<?php if(!$readonly) { ?>
								<span class="pull-right">
									<a href="#addExpenses" class="btn btn-info" data-toggle="modal" data-mode="Approve" data-target="#addExpenses" style="text-align:right;" >Add </a>
								</span>
								<?php } ?>
						<div class="form-group">
							<div class="col-md-12">
								
								<span id="te_exp_edit">
								
								</span>
							</div>	
						</div>
						
						</div>
					</div>
						
				</div>
			</div>	
								
						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; ?>
								
							</div>
							<?php $baseurl1 = $baseurl.$modulePath.'company_expense.php?sub=list';?>
							<div class="col-sm-6 text-right">
								<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Cancel</a>
								<span>&nbsp;&nbsp;</span>
								<input class="btn btn-primary" type="submit" value="Next" name="Save">&nbsp;&nbsp;&nbsp;
							</div>
						</div>
                        
                    </fieldset>
				</div>	
            </form>
          </div>
          
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
      </div>
  <!-- /.content-wrapper -->
</section>  
<?php } 	?>


<?php if($_GET['sub'] == 'edit'){
?>

<?php
	if(isset($_POST['Save'])){
			$id			    = $_POST['id']; 
			$re_id			= $_POST['id']; 
			$company_id 	= $_POST['company_id'];
			$emp_id		 	= $_POST['sma_vendor_id'];
			$total_amount 	= $_POST['total_amount'];
			$dated		 	= date('Y-m-d', strtotime($_POST['dated']));
			$approval_ref_no= $_POST['approval_ref_no'];
			$remarks	 	= $_POST['remarks'];
			$purchase_requisition =	$_POST['purchase_requisition'];
						
  			$sql="update sma_travel_expenses set company_id ='$company_id',
						approval_ref_no	= '$approval_ref_no',
						emp_id			= '$emp_id',
						purchase_requisition = '$purchase_requisition',
						remarks 		= '$remarks'
					where id='$id'";
//echo $sql;
//exit();

			$query = mysqli_query($con, $sql);
			$error = mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			
			// add attachments
			// file upload
			$doc_invoice_no = $_POST["doc_invoice_no"];
			$arrDocType = $_POST["doctype"];
			$arrDocDesc = $_POST["docdesc"];
			$arrFUDoc = $_FILES["fudoc"];

//echo $arrDocType;	
//print_r($arrFUDoc);
//exit();			
	
			for($i = 0; $i < sizeof($arrDocType); $i++) {
				$folder_path = "uploads/re/" . $re_id;
				if (!file_exists($folder_path)){
					mkdir($folder_path, 0755, true);
					
					$findex = $folder_path.'/index.php';
					fopen($findex,'w');
				}
				$filename = $arrFUDoc['name'][$i];
				$tmpFileName = $arrFUDoc['tmp_name'][$i];
				
				if( !empty($filename) ){
					$sql = "INSERT INTO file_uploads (module, file_name, file_path, doc_type, doc_desc, reference_id, date_uploaded, doc_invoice_no) VALUES('CE', '" . $filename . "', '" . $folder_path . "', '" . $arrDocType[$i] . "', '" . $arrDocDesc[$i] . "', " . $re_id . ", now(), '$doc_invoice_no[$i]' )";
					if (mysqli_query($con, $sql)) {
						move_uploaded_file($tmpFileName, "uploads/re/" . $re_id . "/" . $filename);
					}
					else {
						echo "Error: " . mysqli_error($con);
					}
				}
			}
//echo $sql;			
//exit();
			
			echo '<script>window.location.href="company_expense.php?sub=list";</script>';
			
		}
		
		$id = $_GET['id'];
		$approval_ref_no = $_GET['approval_ref_no'];
		
		$sql="Select * from sma_travel_expenses where exp_type = 'C' and id ='$id' ";

//echo $sql;
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	
		$re_id = $row['id'];
		$_SESSION['re_id'] = $row['id'];
		$status = $row['status'];
		$_SESSION['status'] = $status;
		
		$draft_by = $row['draft_by'];		
		$readonly = '';
		if ($status != 'Draft'){
			$readonly = 'READONLY';
		}
		
		if ($user == 'Admin'){
			$readonly = '';
		}

?>

    <section class="content-header">
        <h1>
            Operating Expenses
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Operating Expenses</a></li>
        </ol>
    </section>
		
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            
		<!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box-body">
            <!-- form start -->
            <form class="form-horizontal" action="company_expense.php?sub=edit" method="post" enctype="multipart/form-data" >
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
						
						<span class="pull-right"><h4 style="color:red;"><b><?php echo $row['status'];?></b></h4> </span>
						
					<?php
						
						if ($_GET['active']){
							$active = $_GET['active'];
							$active_1 = ' ';
						}
						else
						{
							$active_1 = 'active';
						}
						
						if ($_GET['next']){
							$active = $_GET['next'];
							$active_1 = ' ';
						}
						
					?>
                   
				   <ul class="nav nav-tabs">
                     
						<li class="<?php echo $active_1;?>"><a href="#tab_1" data-toggle="tab" id="first_tab" >Operating Expenses</a></li>
                        <li><a href="#tab_2" data-toggle="tab" id="second_tab">Documents</a></li>
						<li><a href="#tab_3" data-toggle="tab" class="btn btn-info" id="three_tab" >Workflow History</a></li>
						<li><a href="company_exp_repo.php?sub=pdf&id=<?php echo $re_id;?>&r=1" class="btn btn-success"  target="_blank" >View </a></li>
						
                    </ul>
					<div class="tab-content">
						<div class="tab-pane <?php echo $active_1;?>" id="tab_1">
						
						<div class="form-group">
						</div>
						
						<input type="hidden" class="form-control" id="id" name="id" value="<?php echo $re_id; ?>">
						<div class="form-group">
							<label class="col-lg-2 control-label">SrNo.</label>
							<div class="col-md-1">
								<input type="text" class="form-control" id="approval_ref_No" readonly  name="approval_ref_no" value="<?php echo $re_id; ?>">
							</div>
						<?php
							$dated				= date('d-m-Y', strtotime($row['dated']));
							if($dated=='01-01-1970'){ $dated='';}
							$approval_ref_no	= $re_id;
						?>						
						
							
							<label class="col-lg-1 control-label">Date </label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="dated" name="dated" readonly placeholder="dd/mm/yyyy" value="<?php echo $dated; ?>">
							</div>
							
							<label class="col-lg-1 control-label">Company </label>
							<div class="col-md-4">
								<select class="form-control" name="company_id" id="company_id"  readonly="readonly" required >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_id'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>
						
						<div class="form-group">
						
						<label class="col-lg-2 control-label">Vendor</label>
							<div class="col-md-4">
								<select class="form-control" name="sma_vendor_id" id="sma_vendor_id" autocomplete="off" required  >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_party_mst where party_kyc = 'Y' order by party_name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['emp_id'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['party_name'];?></option>
										<?php } ?>
								</select>
							</div>
							
							
							<label class="col-lg-2 control-label">Purchase&nbsp;Requisition&nbsp;No.</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="purchase_requisition" name="purchase_requisition"  value="<?php echo $r2['purchase_requisition'];?>">
							</div>
							
						</div>
						
						
						<div class="form-group">
							
							
							<label class="col-lg-2 control-label">Remarks</label>
							<div class="col-md-8">
								<textarea class="form-control" rows="2" name="remarks"  autocomplete="off" ><?php echo $row['remarks']?></textarea>
							</div>
						</div>
						
						<input type="hidden" name="total_amount" id="total_amount"  value="<?php echo $row['total_amount'] ?>">
						
						<div class="panel-group" id="steps">
                        
							<!-- Step 1 -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#stepTwo" class="btn btn-info dropdown-toggle"> <i class="fa fa-expand"></i>&nbsp;&nbsp; Expenses <span class="caret"></span></a></h4>
                            </div>
                            <div id="stepTwo" class="panel-collapse collapse in">
							
								<div class="panel-body">
								<?php if(!$readonly){ ?>	
									<span class="pull-right">
										<a href="#addExpenses" class="btn btn-info" data-toggle="modal" data-mode="Approve" data-target="#addExpenses" style="text-align:right;" >Add </a>
									</span>
								<?php } ?>	
						<div class="form-group">
							<div class="col-md-12">
							
								<span id="te_exp_edit">
									<table id="prtable" class="table table-bordered table-striped">
										 <tr>
												<th> SrNo.</th>
												<th> Expense Type</th>
												<th> Invoice No.</th>
												<th> Date</th>
												<th style="text-align:right;"> Amount</th>
												<th> Narration</th>
												<th> GST</th>
												<th style="text-align:right;"> Action</th>
										 </tr>
										
									<tbody>
									<?php
										$j = 0;
										$modulePath1 = "travel_approval/";
										$sql="SELECT * from sma_expenses where approval_ref_no = '$approval_ref_no' and exp_type = 'C' ";
									
										$result = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while($row1 = mysqli_fetch_array($result)){
											$j = $j + 1;
											
											
											$reference = $row1['reference'];
											$sql="SELECT * from account_mst where id = '$reference'";
											$q2 = mysqli_query($con, $sql);
											echo mysqli_error($con);
											$r2 = mysqli_fetch_array($q2);
											$reference = $r2['account_name'];
											
											$tot_amount += $row1['amount'];

									?>
										<tr>
											<td width="2%"><?php echo $j;?></td>
											<td width="25%"><?php echo $reference;?></td>
											<td width="10%"><?php echo $row1['invoice_no'];?></td>
											<td width="10%"><?php echo date('d-m-Y', strtotime($row1['dated']));?></td>
											<td width="10%" style="text-align:right;"><?php echo number_format($row1['amount'],2);?></td>
											<td width="45%"><?php echo $row1['note'];?></td>
											<td width="05%" style="text-align:left;"><?php echo $row1['gst_flag'];?></td>
											<td width="08%" style="text-align:right;">
											<?php $rid = $row1['id']; ?>
											<a href='#modalEditItem' data-id='<?php echo $rid;?>' data-mode='edit' data-toggle='modal' data-target='#modalEditItem<?php echo $rid;?>' > <i class='fa fa-edit'></i></a>&nbsp;&nbsp;
<!-- Modal Edit Item-->								
												<?php include "edit_reg_func.php"; ?>							
<!-- Modal Edit Item-->						
												<!--<a href="company_expense.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>-->
												<a href="delete_expenses.php?sub=delete&id=<?php echo $row1['id'];?>&approval_ref_no=<?php echo $approval_ref_no;?>&exp_type=C" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>
											</td>
											</td>
										</tr>

										<?php }?>
									</tbody> 
										<?php $tot_amount = $tot_amount  ?>
										<tr> <th colspan="4" style="text-align:right;"> Total </th><th style="text-align:right;"> <?php echo number_format($tot_amount,2); ?> </th><td colspan="3"></td></tr>
										
									</table> 
									
									
								</span>
							</div>	
						</div>
						
						</div>
						
				    </div>
								
				</div>

			  </div>
			  
							<div class="box-footer">
								<div class="col-sm-6">
									
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6 text-right">
									<span>&nbsp;&nbsp;</span>
									 <a href="#tab_2" class="btn btn-primary" data-toggle="tab" onclick="$('#second_tab').trigger('click')" >Next</a>
								</div>
							</div>
							
			</div>
				
			<div class="tab-pane <?php echo $active;?>" id="tab_2">
			                <!-- Attachments -->
							
							 <?php
                              $sql = "SELECT * FROM file_uploads WHERE module = 'CE' AND reference_id = " . $re_id;
                              $docResults = mysqli_query($con, $sql);
	                          ?>
                                  <table id="prDocsTable" class="table table-bordered table-striped">
                                      <thead>
                                      <tr>
                                          <th>Document Type</th>
										  <th>Invoice</th>
                                          <th>Description</th>
										  <th>Document Name</th>
                                          <th>Action</th>
                                          
                                      </tr>
                                      </thead>
                                      <tbody id="prDocsTableBody">
				                              <?php
				                              echo mysqli_error($con);
				                              while($docRow = mysqli_fetch_array($docResults)) {
				                                  $delDocUrl = "deldoc.php?id=" . $docRow['id'] . "&url=" . urlencode($_SERVER['REQUEST_URI']);
													
													$doc_type = $docRow['doc_type'];
													$sql="SELECT * FROM sma_document_type where id ='$doc_type' ";
													$rs = mysqli_query($con, $sql);
													$rw = mysqli_fetch_array($rs);
													$document = $rw['document'];
													
												  ?>
                                          <tr>
                                              <td><?php echo $document; ?></td>
											  <td><?php echo $docRow['doc_invoice_no'] ?></td>
                                              <td><?php echo $docRow['doc_desc'] ?></td>
                                              <td><a target="_blank" href="<?php echo $docRow['file_path'] . '/' . $docRow['file_name']; ?>"><?php echo $docRow['file_name'] ?></a></td>
											  <?php if(!$readonly){ ?>
													<td><a href="<?php echo $delDocUrl; ?>"><i class='fa fa-trash-alt'></i></a></td>
											  <?php } ?>  	
                                          
                                          </tr>
				                              <?php
				                              }
				                              ?>
                                      </tbody>
                                      
                                  </table>
    						<div class="table-responsive">  
                               <table class="table table-bordered" id="dynamic_field">  
                                    <tr> 
										<td></td>
										<td><label class="col-sm-1 control-label">Document</label>
                                            <select class="form-control col-sm-2 doctype" name="doctype[]" required="true"  <?php echo $readonly; ?>>
                                                <option value="0">Select</option>
												<?php
												$sql="SELECT * FROM sma_document_type ORDER BY document ASC";
												$rs = mysqli_query($con, $sql);
												echo mysqli_error($con);
												while($rw = mysqli_fetch_array($rs)){
												?>
													<option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['document'] ?></option>
												<?php } ?>	
												
                                            </select>
										</td>
										
										<td><label class="col-sm-2 control-label">Invoice.No.</label>
                                            <select class="form-control col-sm-2 doctype" name="doc_invoice_no[]" required="true"  <?php echo $readonly; ?>>
                                                <option value="0">Select</option>
												<?php
												$sql = "select id as id, invoice_no from sma_expenses  where approval_ref_no = '$approval_ref_no' and exp_type  = 'C' ";
												$rst = mysqli_query($con, $sql);
												echo mysqli_error($con);
												while($rs = mysqli_fetch_array($rst)){
												?>
													<option value="<?php echo $rs['invoice_no']?>" ><?php echo $rs['invoice_no'] ?></option>
												<?php } ?>	
												
                                            </select>
										</td>
										<td><label class="col-sm-1 control-label">Description</label>
											 <textarea class="form-control docdesc" name="docdesc[]" rows="2" <?php echo $readonly; ?> placeholder="Enter document description..."></textarea>
										</td>
										<td><label class="control-label col-sm-3">Attachment</label><br>
											<input type="file" name="fudoc[]" <?php echo $readonly; ?> class="docfile">
										</td>
										 <?php if(!$readonly){ ?>
                                         <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>  
										 <?php } ?>
                                    </tr>  
                               </table>  
                            <!--   <input type="button" name="submit" id="submit" class="btn btn-info" value="Submit" />  -->
							</div>							
								
								<span id="predit">
								</span>
								
<?php
$opt = '';	
$sql="SELECT * FROM sma_document_type ORDER BY document ASC";
$rs = mysqli_query($con, $sql);
echo mysqli_error($con);
while($rw = mysqli_fetch_array($rs)){
	$did 		= $rw['id'];
	$ddocument 	= $rw['document'];
	$opt .= "<option value='" . $did ."' > ".$ddocument."</option>";
 }

 $opt_invoice_no = '';	
$sql="SELECT id as id, invoice_no FROM `sma_departure`  where approval_ref_no = '$approval_ref_no'
		union
		select id as id, invoice_no from sma_expenses  where approval_ref_no = '$approval_ref_no' and exp_type = 'T' ";
$rs = mysqli_query($con, $sql);
echo mysqli_error($con);
while($rw = mysqli_fetch_array($rs)){
	$invoice_no 		= $rw['invoice_no'];
	$opt_invoice_no .= "<option value='" . $invoice_no ."' > ".$invoice_no."</option>";
 }
 
?>

<input type="hidden"  value="<?php echo $opt ?>" name="opt" id="opt">
<input type="hidden"  value="<?php echo $opt_invoice_no ?>" name="opt_invoice_no" id="opt_invoice_no">
												
						
							<div class="box-footer">
								<div class="col-sm-6">
									 <a href="#tab_1" class="btn btn-primary" data-toggle="tab" onclick="$('#first_tab').trigger('click')" >Previous</a>					
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6 text-right">
									<span>&nbsp;&nbsp;</span>
								</div>
							</div>

							
							<div class="box-footer">
								
								<div class="col-sm-6">
									<?php $did = $_GET['id']; ?>
								
							<?php		
								if ($status != 'Submited' && $user=='Admin' ){
							?>		
									<a href="<?php echo $baseurl.$modulePath."company_expense.php?sub=delete&id=$did";?>" class="btn btn-danger" >Delete</a>
									<span>&nbsp;&nbsp;</span>
									
							<?php } ?>		
									
								</div>
								
								<div class="col-sm-6 text-right">
									<?php		
										$role		= $_SESSION['role'];
										$user   	= $_SESSION['user'];
										$userid   	= $_SESSION['usrid'];
										$send_to   	= $row['send_to'];
										$level_1   	= $row['level_1'];
										$baseurl1 = $baseurl.$modulePath."company_expense.php?sub=list";
										
								//echo $send_to. ' <<>> ' . $userid. ' => ' . $rw['level_2']. ' => ' . $rw['level_3']. ' ' . $status;
										if ($status == 'Submited' && $level_1 == $userid){
										?>
											<span>&nbsp;&nbsp;</span>
											<a href="#approvalAuthority" class="btn btn-primary" data-toggle="modal" data-mode="Submit" data-target="#approvalAuthority">Approve </a>
											<a href="#rejectAuthority" class="btn btn-primary" data-toggle="modal" data-mode="Submit" data-target="#rejectAuthority">Reject </a>
									<?php }
										if ( $status != 'Draft' and $user == 'Admin' ){
									?>
										<input class="btn btn-primary" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
										
									<?php }
										
										if ($status == 'Draft' ){
									?>
										<input class="btn btn-primary" type="submit" value="Save Draft" name="Save">&nbsp;&nbsp;&nbsp;
										<a href="#checkerAuthority" class="btn btn-primary" data-toggle="modal" data-mode="add" data-target="#checkerAuthority">Send</a>
									<?php } ?>
									
									<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Back</a>
								
								</div>
								
							</div>
							
						</div>
						
						<div class="tab-pane" id="tab_3">
							
							<div class="modal-header" >
								
								<?php 
									
									$srno = $re_id;
									$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'CE' order by id ";
							//echo $s1;		
									$res  = mysqli_query($con, $s1);
									echo mysqli_error($con);
									$r1 = mysqli_fetch_array($res);
									$create_by		= $r1['create_by'];
									$create_date	= date('d-m-Y', strtotime($r1['create_date']));
									
									$sl="SELECT * FROM sma_user where id = '$create_by' ";
									$r3 = mysqli_query($con, $sl);
									$rw = mysqli_fetch_array($r3);
									$create_by = $rw['first_name'].' '.$rw['last_name'];
					
								?>			
								<span style="margin: 0 auto;text-align:center" class="form-control-static pull-left" >Document Number : <?php echo $srno;?> <?php echo "&nbsp;&nbsp; Created By: ".$create_by; ?> <?php echo "&nbsp;&nbsp; Date: ".$create_date; ?>
								 
								</span>
									
							</div>
							<div class="modal-body1" >
								<section class="content2">
								<div class="row1">
								   
										<table id="prtable" class="table table-bordered table-striped">
											<thead>
											<tr>
											  <th></th>	
											  
											  <th>Date</th>
											  <th>By User</th>
											  <th>Decision</th>
											  <th>Send Date</th>
											  <th>To User</th>
											  <th>Role</th>
											  <th>Remarks</th>
											  
											</tr>
											</thead>
											<tbody>
										<?php
										//style="position:relative;overflow-y:auto;min-width: 600px; max-height: 500px;padding: 15px;"
											//$srno = $rid;
											$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'CE' order by id desc";
										//echo $s1;
											$res  = mysqli_query($con, $s1);
											//echo mysqli_error($con);
											while($r1 = mysqli_fetch_array($res)){
												$id 				= $r1['id'];
												
												$status				= $r1['status'];
												$reviewed_by 		= $r1['reviewed_by'];
												$approved 			= $r1['approved'];
												$approved_date		= date('d-m-Y h:i:sa', strtotime($r1['approved_date']));
												$remarks 			= $r1['remarks'];
												
												if(empty($reviewed_by)){
													$reviewed_by = '0';
												}
												
												$s2="SELECT * FROM sma_user where id in ($reviewed_by) ";
										//echo $s2. "<BR>";
												$r3 = mysqli_query($con, $s2);
												$reviewed_by_name = '';
												$role_name = '';
												while ($rw1 = mysqli_fetch_array($r3)){
													$reviewed_by_name .= $rw1['username'].', ';
													$role1 = $rw1['role'];

													$sl="SELECT * FROM sma_role where id = '$role1' ";
													$r4 = mysqli_query($con, $sl);
													$rw = mysqli_fetch_array($r4);
													$role_name .= $rw['role'].', ';
												}
										//echo $reviewed_by . " <<<<<BR>";
												
												$create_by		= $r1['create_by'];
												$create_date	= date('d-m-Y h:i:sa', strtotime($r1['create_date']));
												
												$sl="SELECT * FROM sma_user where id = '$create_by' ";
												$r3 = mysqli_query($con, $sl);
												$rw = mysqli_fetch_array($r3);
												$create_by = $rw['username'];
												
										?>      
											<tr>
												<td width="1%"><input type="hidden" value="<?php echo $id; ?>" ></td>
												<td width="10%" style="text-align:left;"><?php echo $create_date; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $create_by; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $status; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $approved_date; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $reviewed_by_name;?></td>
												<td width="10%" style="text-align:left;"><?php echo $role_name;?></td>
												<td width="10%" style="text-align:left;"><?php echo $remarks;?></td>
											</tr>
										<?php	}	?>	
											</tbody>
										</table>
										
									</div>
								</section>
							  </div>
						
						</div>
						
                    </fieldset>
				</div>	
            </form>
   					
          </div>
          
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
    </div>
  </div>
</section>
      
<?php } 	?>



<!--Add Expenses Popup-->

<div class="modal fade" id="addExpenses" role="dialog" aria-labelledby="addExpenses">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="addExpenses">Expenses </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <form class="form-horizontal">
                                        
										<?php   
										
											$re_id 		= $_SESSION['re_id'];
											$status 	= $_SESSION['status'];
											$role		= $_SESSION['role']; //Maker
											$user_category = $_SESSION['user_category'];
											
										?>
										<input type="hidden" name="re_id" id="re_idE" value="<?php echo $re_id; ?>" >
										<input type="hidden" id="modeE" name="mode" value='Approve'>
									
									<div class="form-group">
										
										<div class="col-md-6">
											<label class="control-label">Expense Type </label>
										
											<select class="form-control" name="expence_name" id="expence_Name" autocomplete="off" >
												<option value=""> Select </option>
												<?php 
													$sql = "SELECT * from account_mst where account_type = 'E' ";
													$q2  = mysqli_query($con, $sql);
													echo mysqli_error($con);
													while($r2 = mysqli_fetch_array($q2)){
												?>
												<option value="<?php echo $r2['id'] ?>"> <?php echo $r2['account_name'] ?> </option>	
												<?php } ?>
											</select>
										</div>
										
										<div class="col-md-4">
											<label for="approver" class="control-label">Invoice No.</label>
											<input type="text" class="form-control" name="invoice_no" id="Invoice_nm" value="" >
										</div>
										
									</div>

									<div class="form-group">
									
										<div class="col-md-4">
											<label for="approver" class="control-label">On Date</label>
											<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
												<div class="input-group-addon">
													<i class="fa fa-calendar-alt"></i>
												</div>
												<input type="text" class="form-control" id="Dated" name="dated" autocomplete="off" placeholder="dd/mm/yyyy" value="">
											</div>
										</div>
										
										<div class="col-md-4">
											<label for="approver" class="control-label">Amount </label>
									<!--<span data-toggle="tooltip" title="Maximum 50,000 Allowed." class="badge bg-light-blue">!</span>-->
											<input type="text" class="form-control" name="amount" id="Amount"  value="" >
										</div>
										
										<div class="col-md-4" style="text-align:left;" >
											<label for="approver" class="control-label" >GST Applicable</label><br>
											<input type="radio" name="gst_flag" id="Gst_flag_A"  value="Y" > Yes &nbsp;
											<input type="radio" name="gst_flag" id="Gst_flag_A" value="N" > No
										</div>
									
									</div>
									
									<div class="form-group">
																			
										<div class="col-md-10">
											<label for="approver" class="control-label">Narration</label>
											<textarea rows="4" class="form-control" name="remarks" id="Remarks" ></textarea>
										</div>
										
									</div>
										
								</form>
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="saveExp">Save</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Add Expenses  Popup End -->
	  

<!--Checker Workflow Popup-->

<div class="modal fade" id="checkerAuthority" role="dialog" aria-labelledby="checkerAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="checkerAuthority">Send To... </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$re_id	= $_SESSION['re_id'];
											$status = $_SESSION['status'];
											$role 	= $_SESSION['role'];
											
										?>
										
										<input type="hidden" name="re_id" id="re_idC" value="<?php echo $re_id; ?>" >
										<input type="hidden" id="modeC" name="mode" value='Checker'>
										
										<div class="form-group col-md-12">
                                        	<label for="approver" class="col-sm-4 control-label">Approver Name</label>
                                            <div class="col-sm-7">
                                                <span id="getuser">
													<select class="form-control select2123" id="approverE" name="approver" required>
													    <option value="">Select</option>
														<?php
														    $sql = "SELECT * FROM `sma_user`  where regular_exp_approver = 'Y' ORDER BY username ASC";
														    $result1 = mysqli_query($con, $sql);
														    echo mysqli_error($con);
														    while($row1 = mysqli_fetch_array($result1)){
														?>
														<option value="<?php echo $row1['id']?>" ><?php echo $row1['username'];?></option>
													    <?php } ?>
													</select>
												</span>
                                            </div>
										</div>
										
										<div class="form-group col-md-12">
											<label for="approver" class="col-sm-4 control-label">Status</label>
                                            <div class="col-sm-7">
												<input type="text" class="form-control" id="statusE" style="color:red;" readonly name="status" value="<?php echo $status ?>" >

                                            </div>
                                        </div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksE"></textarea>
											</div>
										</div>
										
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitChecker">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Checker Workflow Popup End -->


<!--Submit Workflow Popup-->

<div class="modal fade" id="submitAuthority" role="dialog" aria-labelledby="submitAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="submitAuthority">Submit To... </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$re_id	= $_SESSION['re_id'];
											$status = $_SESSION['status'];
											$role 	= $_SESSION['role'];
											
										?>
										
										<input type="hidden" name="re_id" id="re_idS" value="<?php echo $re_id; ?>" >
										<input type="hidden" id="modeS" name="mode" value='Submit'>
										
										<div class="form-group col-md-12">
											<label for="approver" class="col-sm-4 control-label">Status</label>
                                            <div class="col-sm-7">
												<input type="text" class="form-control" id="statusS" style="color:red;" readonly name="status" value="<?php echo $status ?>" >

                                            </div>
                                        </div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksS"></textarea>
											</div>
										</div>
										
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitNext">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Submit Workflow Popup End -->

<!--Approval Workflow Popup-->

<div class="modal fade" id="approvalAuthority" role="dialog" aria-labelledby="approvalAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="approvalAuthority">Remarks </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
										
											$re_id = $_SESSION['re_id'];
											$status = $_SESSION['status'];
										
										?>
										
										<input type="hidden" name="re_id" id="re_idP" value="<?php echo $re_id; ?>" >
										<input type="hidden" id="modeP" name="mode" value='Approve'>
										
										<div class="form-group col-md-12">
											<label for="approver" class="col-sm-4 control-label">Status</label>
                                            <div class="col-sm-7">
												<input type="text" class="form-control" id="statusP" style="color:red;" readonly name="status" value="<?php echo $status ?>" >

                                            </div>
                                        </div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksP"></textarea>
											</div>
										</div>
										
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitApprove">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Approval Workflow Popup End -->
	  

<!--Reject Workflow Popup-->

<div class="modal fade" id="rejectAuthority" role="dialog" aria-labelledby="rejectAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="rejectAuthority">Remarks </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$re_id 	= $_SESSION['re_id'];
											$status = $_SESSION['status'];
											
										?>
										
										<input type="hidden" name="re_id" id="re_idR" value="<?php echo $re_id; ?>" >
										<input type="hidden" id="modeR" name="mode" value='Reject'>
										
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksR"></textarea>
											</div>
										</div>
									
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitReject">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Reject Workflow Popup End -->	  
	  

  <!-- Modal Report-->
<div class="modal fade" id="modalExport" role="dialog" aria-labelledby="modalExportLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalExportLabel">Export Travel Request data</h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                    <div class="row123">
                        <form class="form-horizontal" action="regular_exp_export_func.php?sub=pdf" target="_blank" method="POST" >
                            <input type="hidden" id="mode" value='Export'>
                            <input type="hidden" id="tempId">
<!--							<input type="hidden" id="purchaseId" value="<?php echo $_GET['id'];?>">-->
							
							<div class="form-group">
                                
								<div class="col-sm-4">
									<label class="control-label">From Date</label>
							        <div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy" required="required">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                        <input type="text" class="form-control" id="fromDate" name="from_date" required="required" >
									</div>
								</div>
								
								<div class="col-sm-4">
									<label class="control-label">To Date</label>
							        <div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                        <input type="text" class="form-control" id="toDate" name="to_date" >
									</div>
								</div>
							</div>
								
							<div class="form-group">
                                <div class="col-sm-12">
									<label for="itemCategory" class="control-label"> Company</label>
									<select class="form-control" name="company_id" id="companyId" >
									<option value=""> Select </option>
									<option value=""> All </option>
										<?php $sql = "select * from company where comp_id in ( $comid )order by comp_name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" > <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>
								</div>
                            </div>
                            
							
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" name="submit" id="exportItem12" onclick="exportItem123()" value="Submit">
            </div>                
                        </form>
                    </div>
                </section>
            </div>
            
        </div>
    </div>
</div>
<!-- Modal Report-->	  
	  
	 	  
<!-- For Document Attachment Start-->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>        
<script>  
 $(document).ready(function(){  
      var i=1;  
      $('#add').click(function(){
			var opt =  document.getElementById('opt').value;
			var opt_invoice_no =  document.getElementById('opt_invoice_no').value;
			
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'"><td><label class="col-sm-1 control-label">&nbsp;</label></td><td><select class="form-control select2 doctype" name="doctype[]"><option value="0">Select</option>'+opt+'</select></td><td><select class="form-control select2 doc_invoice_no" name="doc_invoice_no[]"><option value="0">Select</option>'+opt_invoice_no+'</select></td><td><textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea></td><td><input type="file" name="fudoc[]" class="docfile"></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
      });
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      });  
      $('#submit').click(function(){
           $.ajax({  
                url:"name.php",  
                method:"POST",  
                data:$('#add_name').serialize(),  
                success:function(data)  
                {  
                     alert(data);  
                     $('#add_name')[0].reset();  
                }  
           });  
      });  
 });  
</script>
 <!-- For Document Attachment End-->	  
	  
	  
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


<script>

    $("#saveExp").on("click", function(e){
        var sub = 'sub10';
				
//		alert(sub + ' ' + mode);		 
	    var status 			=  $("#statuS").val();
		var approval_ref_no = $("#approval_ref_No").val();
		var expence_name		= $("#expence_Name").val();
		var dated			= $("#Dated").val();
		var amount			= $("#Amount").val();
		var invoice_nm		= $("#Invoice_nm").val();
		var gst_flag		= $("#Gst_flag_A").val();
		
		var remarks			= $("#Remarks").val();
		var exp_type		= 'C';
//	alert(expence_name + amount);
		
//		if (amount>50000){
//			alert("Maximum 50,000 amount allowd...");
//			return;
//		}
		
		
		$('#addExpenses').modal('hide');
		
		var strURL = "app_func.php";
		$.post(strURL,{ approval_ref_no:approval_ref_no,
						expence_name:expence_name,
						dated:dated,
						amount:amount,
						exp_type:exp_type,
						invoice_nm:invoice_nm,
						remarks:remarks,
						gst_flag:gst_flag,
						sub10:sub},
						function(result){
		      $('#te_exp_edit').html(result);
		});
		
	});


 
   $("#submitChecker").on("click", function(e){
        var sub = 'sub14';
		var mode		 	=  $("#modeC").val();
		
		var re_id		 	=  $("#re_idC").val();
		//var approver		=  $("#approverE option:selected").val();
        var status 			=  $("#statusE").val();
		var remarks			=  $("#remarksE").val();
		var approver		=  $("#approverE").val();
		
//	alert(approver + ' ' + re_id );		 

//		if(approver==''){
//			alert("User Name should select...");
//			return;
//		}
	
		 $('#checkerAuthority').modal('hide');
				 
		var strURL = "app_func.php";
		$.post(strURL,{ re_id:re_id,
						mode:mode,
						status:status,
						remarks:remarks,
						approver:approver,
						sub14:sub},
						function(result){
		      $('#predit').html(result);
		});
	});
	
   $("#submitNext").on("click", function(e){
        var sub = 'sub5';
		var mode		 	=  $("#modeS").val();
		
		var re_id		 	=  $("#re_idS").val();
		//var approver		=  $("#approverE option:selected").val();
        var status 			=  $("#statusS").val();
		var remarks			=  $("#remarksS").val();

//	alert(sub + ' ' + mode + ' ' + approver + ' ' + status );		 

		$('#submitAuthority').modal('hide');
				 
		var strURL = "app_func.php";
		$.post(strURL,{ re_id:re_id,
						mode:mode,
						status:status,
						remarks:remarks,
						sub5:sub},
						function(result){
		      $('#predit').html(result);
		});
	});
	
	
    $("#submitApprove").on("click", function(e){
        var sub = 'sub15';
		var mode		 	=  $("#modeP").val();
//		alert(sub + ' ' + mode);		 
		var re_id		 	=  $("#re_idP").val();
	    var status 			=  $("#statusP").val();
		
        var remarks			=  $("#remarksP").val();
		
//alert(statusap+' #0# '+status+' #1# '+account_year+' #2# '+company+' #3# '+budget_head_id+' #4# '+budget_name+' #5# '+re_id);
		 
		var strURL = "app_func.php";
		$.post(strURL,{ re_id:re_id,
						mode:mode,
						status:status,
						remarks:remarks,
						sub15:sub},
						function(result){
		      $('#predit').html(result);
		});
		
		$('#approvalAuthority').modal('hide');
		
	});

		
    $("#submitReject").on("click", function(e){
        var sub = 'sub16';
		var mode		 	=  $("#modeR").val();
		
		var re_id		 	=  $("#re_idR").val();
		var status 			=  $("#statuS").val();
		var remarks			=  $("#remarksR").val();
		
//alert(statusap+' #0# '+status+' #1# '+account_year+' #2# '+company+' #3# '+budget_head_id+' #4# '+budget_name+' #5# '+re_id);

		$('#rejectAuthority').modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ re_id:re_id,
						mode:mode,
						status:status,
						remarks:remarks,
						sub16:sub},
						function(result){
		      $('#predit').html(result);
		});
		
	});
	
	
function getempname(id){
    var sub = 'sub4';
//alert(id);
	var strURL = "ta_func.php";
	$.post(strURL,{ sub4:sub,id:id},function(result){
			  $('#getempname').html(result);
		});
}

</script>


</body>
</html>
