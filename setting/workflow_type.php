<?php

include("../header.php");
$modulePath = "setting/workflow_type.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Workflow Type
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Workflow Type</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of Workflow Type</h3>
                <span class="pull-right"><a href="workflow_type.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create Workflow Type </a></span>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>Trans Type</th>
			<th>Workflow Type</th>
			<th>Status</th>
			<th style="text-align:right;">Action</th>
			
		</tr>
	</thead>
<tbody>
<?php

	$modulePath1 = "setting/";
	$sql="SELECT * from sma_workflow_type";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		$status		= $row['status'];
		if($status=='Y'){
			$status = 'Active';	
		}
		else if($status=='N'){
			$status = 'Inactive';	
		}
		
		$doc_type = $row['doc_type'];
		if($doc_type == 'AP'){
			$doc_type = 'Approval';
		}
		else if($doc_type == 'PR'){
			$doc_type = 'Material Requisition';
		}
		else if($doc_type == 'PO'){
			$doc_type = 'Purchase Order';
		}
		else if($doc_type == 'GR'){
			$doc_type = 'GRN';
		}
		else if($doc_type == 'SI'){
			$doc_type = 'Supplier Invoice';
		}
		else if($doc_type == 'PY'){
			$doc_type = 'Payment';
		}
		else if($doc_type == 'PC'){
			$doc_type = 'Petty Cash';
		}
		else if($doc_type == 'TN'){
			$doc_type = 'Tender';
		}
		else if($doc_type == 'TO'){
			$doc_type = 'Tender OTP';
		}
		else if($doc_type == 'CE'){
			$doc_type = 'Operating Expense';
		}
		else if($doc_type == 'TA'){
			$doc_type = 'Travel Request';
		}
		else if($doc_type == 'TE'){
			$doc_type = 'Travel Expenses';
		}
		else if($doc_type == 'RE'){
			$doc_type = 'Reimbursement';
		}
		else if($doc_type == 'BD'){
			$doc_type = 'Budget Adjustment';
		}
		else if($doc_type == 'VN'){
			$doc_type = 'KYC Vendor';
		}
		else if($doc_type == 'DJ'){
			$doc_type = 'Approval Memo Adjustment';
		}
		else if($doc_type == 'RT'){
			$doc_type = 'RTGS';
		}
		else if($doc_type == 'PD'){
			$doc_type = 'Product';
		}
		else if($doc_type == 'GI'){
			$doc_type = 'Goods Issued Notes';
		}
		else if($doc_type == 'RV'){
			$doc_type = 'Revenue JV';
		}
		else if($doc_type == 'AD'){
			$doc_type = 'Advance';
		}
		else if($doc_type == 'PV'){
			$doc_type = 'Provisional';
		}
		else if($doc_type == 'BP'){
			$doc_type = 'Budget Proposal';
		}
		else if($doc_type == 'DE'){
			$doc_type = 'Direct Payment';
		}
		else if($doc_type == 'IN'){
			$doc_type = 'Receipt/Sales';
		}
		
		$baseurl1 = $baseurl.$modulePath1.'workflow_type.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "workflow_type.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">

		<td width="10%"><?php echo $doc_type;?></td>
		<td width="60%"><?php echo $row['workflow_type'];?></td>
		<td width="10%"><?php echo $status;?></td>
		

		<td width="10%" style="text-align:right;">
		<a href="workflow_type.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
<!--<a href="workflow_type.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
		
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
		$sql	="Select * from sma_workflow_type where id ='$id'";
			$query 	= mysqli_query($con, $sql);
			$row 	= mysqli_fetch_array($query);
			$workflow_type 			= $row['workflow_type'];
			$doc_type 			= $row['doc_type'];
			
			$company_id 	= '';
			$pgname 		= "workflow_type.php";
			include "../viewonly.php";
			$description 	= $workflow_type.','.$doc_type;
		    $affect 		= 'Deleted';
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
			
		$sql="delete from sma_workflow_type where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="workflow_type.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){
?>

<?php
 
		if(isset($_POST['Save'])){
			$workflow_type		= $_POST['workflow_type'];
			$doc_type			= $_POST['doc_type'];
			
			$status				= 'Y';
			
  			$sql="insert into sma_workflow_type (doc_type , workflow_type, status) 
					Values('$doc_type', '$workflow_type', '$status' )";
					
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}

			$company_id 	= '';
			$pgname 		= "workflow_type.php";
			include "../viewonly.php";
			$description 	= $workflow_type.','.$doc_type;
		    $affect 		= 'Added';
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
			echo "Workflow Type successful added";
			echo '<script>window.location.href="workflow_type.php?sub=list";</script>';
		}
	

?>

    <section class="content-header">
        <h1>
            Workflow Type
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Workflow Type</a></li>
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
            <form class="form-horizontal" action="workflow_type.php?sub=add" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      
					  
					  <div class="form-group">
							
							<label for="company_id" class="control-label col-sm-2">Trasaction Type*</label>
							<div class="col-sm-4">
								<select class="form-control select2" name="doc_type" id="doc_type" >
									<option value=""> Select </option>
									<option value="PR" > Material Requisition</option>
									<option value="AP" > Approval Memo</option>
									<option value="PO"> Purchase Order </option>
									<option value="GR" > GRN</option>
									<option value="SI"> Supplier Invoice </option>	
									<option value="GI" > Goods Issued Notes</option>
									<option value="PY"> Payment </option>	
									<option value="PC"> Petty Cash </option>	
									<option value="TN"> Tender </option>	
									<option value="TO"> Tender OTP</option>	
									<option value="CE" > Operating Expense </option>
									<option value="TA"> Travel Request </option>
									<option value="TE"> Travel Expenses </option>
									<option value="RE"> Reimbursement </option>
									<option value="BD"> Budget Adjustment </option>
									<option value="VN"> KYC Vendor </option>
									<option value="DJ"> Approval Memo Adjustment</option>
									<option value="RT"> RTGS</option>
									<option value="PD"> Product</option>
									<option value="RV"> Revenue JV</option>
									<option value="AD"> Advance</option>
									<option value="PV"> Provisional JV</option>
									<option value="BP"> Budget Proposal </option>
									<option value="DE"> Direct Payment </option>
									<option value="IN"> Receipt/Sales </option>
									
								</select>
							</div>

						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Workflow Type</label>
							<div class="col-md-8">
								<input type="text" class="form-control" id="workflow_type" name="workflow_type" placeholder="" autocomplete="off" value="">
							</div>
						</div>
						
						<div class="form-group" >
							<label class="col-lg-2 control-label">Status </label>
							<div class="col-lg-3" style="padding-top: 6px;">
							<input type="radio" name='active'  checked="checked"  value='Y'> Active &nbsp;&nbsp;
							<input type="radio" name='active' value='N'> Inactive
							</div>
						</div>

                        <!--<div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="workflow_type.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
                        </div>-->
						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; ?>
								
							</div>
							<?php $baseurl1 = $baseurl.$modulePath;?>
							<div class="col-sm-6 text-right">
								<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Cancel</a>
								<span>&nbsp;&nbsp;</span>
								<input class="btn btn-primary" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
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
// echo $_POST['Save'];
 
	if(isset($_POST['Save'])){
			$id				= $_POST['id']; 
			$workflow_type			= $_POST['workflow_type'];
			$doc_type				= $_POST['doc_type'];
			$status					= $_POST['status'];
			
  			$sql = "UPDATE sma_workflow_type SET doc_type ='$doc_type',
						workflow_type ='$workflow_type',
						status = '$status'
				WHERE id='$id' ";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);

			if(!empty($error)){echo $error; exit();}
			
			$company_id 	= '';
			$pgname 		= "workflow_type.php";
			include "../viewonly.php";
			$description 	= $workflow_type.','.$doc_type;
		    $affect 		= 'Modified';
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
			echo '<script>window.location.href="workflow_type.php?sub=list";</script>';
	}
		
		$id = $_GET['id'];
		$sql="Select * from sma_workflow_type where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

?>

    <section class="content-header">
        <h1>
            Workflow Type
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Workflow Type</a></li>
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
            <form class="form-horizontal" action="workflow_type.php?sub=edit" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
						
						<?php $doc_type = $row['doc_type']; ?>
						
						<div class="form-group">
							
							<label for="doc_type" class="control-label col-sm-2">Transaction Type*</label>
							<div class="col-sm-4">
								<select class="form-control select2" name="doc_type" id="doc_type" >
									<option value=""> Select </option>
									<option value="PR"  <?php echo ($row['doc_type'] == 'PR')? "SELECTED":'';?> > Material Requisition</option>
									<option value="AP" <?php echo ($row['doc_type'] == 'AP')? "SELECTED":'';?> > Approval Memo</option>
									<option value="PO" <?php echo ($row['doc_type'] == 'PO')? "SELECTED":'';?> > Purchase Order </option>
									<option value="GR"  <?php echo ($row['doc_type'] == 'GR')? "SELECTED":'';?> > GRN</option>
									<option value="SI" <?php echo ($row['doc_type'] == 'SI')? "SELECTED":'';?> > Supplier Invoice </option>
									<option value="GI" <?php echo ($row['doc_type'] == 'GI')? "SELECTED":'';?> > Goods Issued Notes</option>
									<option value="PY" <?php echo ($row['doc_type'] == 'PY')? "SELECTED":'';?> > Payment </option>
									<option value="PC" <?php echo ($row['doc_type'] == 'PC')? "SELECTED":'';?> > Petty Cash </option>	
									<option value="TN" <?php echo ($row['doc_type'] == 'TN')? "SELECTED":'';?> > Tender </option>
									<option value="TO" <?php echo ($row['doc_type'] == 'TO')? "SELECTED":'';?> > Tender OTP</option>
									<option value="CE" <?php echo ($row['doc_type'] == 'CE')? "SELECTED":'';?> > Operating Expence </option>	
									<option value="TA" <?php echo ($row['doc_type'] == 'TA')? "SELECTED":'';?> > Travel Request </option>										
									<option value="TE" <?php echo ($row['doc_type'] == 'TE')? "SELECTED":'';?> > Travel Expenses </option>
									<option value="RE" <?php echo ($row['doc_type'] == 'RE')? "SELECTED":'';?> > Reimbursement </option>
									<option value="BD" <?php echo ($row['doc_type'] == 'BD')? "SELECTED":'';?> > Budget Adjustment </option>
									<option value="VN" <?php echo ($row['doc_type'] == 'VN')? "SELECTED":'';?> > KYC Vendor </option>
									<option value="DJ" <?php echo ($row['doc_type'] == 'DJ')? "SELECTED":'';?> > Approval Memo Adjustment</option>
									<option value="RT" <?php echo ($row['doc_type'] == 'RT')? "SELECTED":'';?> > RTGS</option>
									<option value="PD" <?php echo ($row['doc_type'] == 'PD')? "SELECTED":'';?> > Product</option>
									<option value="RV" <?php echo ($row['doc_type'] == 'RV')? "SELECTED":'';?> > Revenue JV</option>
									<option value="AD" <?php echo ($row['doc_type'] == 'AD')? "SELECTED":'';?> > Advance</option>
									<option value="PV" <?php echo ($row['doc_type'] == 'PV')? "SELECTED":'';?> > Provisional JV</option>
									<option value="BP" <?php echo ($row['doc_type'] == 'BP')? "SELECTED":'';?> > Budget Proposal </option>
									<option value="DE" <?php echo ($row['doc_type'] == 'DE')? "SELECTED":'';?>> Direct Payment </option>
									<option value="IN" <?php echo ($row['doc_type'] == 'IN')? "SELECTED":'';?>> Receipt/Sales </option>

								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Workflow Type</label>
							<div class="col-md-8">
								<input type="text" class="form-control" id="workflow_type" name="workflow_type" placeholder="" value="<?php echo $row['workflow_type'];?>" >
							</div>
						</div>
					
						
						<div class="form-group" >
							<label class="col-lg-2 control-label">Status </label>
							<div class="col-lg-3" style="padding-top: 6px;">
							<input type="radio" name='status' <?php $status=$row['status'];
									if ($status=="Y") echo "checked";?> value='Y'> Active &nbsp;&nbsp;
							<input type="radio" name='status' <?php $status=$row['status'];
									if ($status=="N") echo "checked";?> value='N'> Inactive
							</div>
						</div>
						
							
   						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; 
								//Mrunmayee started
								$sq2 = "SELECT COUNT(*) as total FROM `sma_workflow` where trans_type = '$did'";
								$q2  = mysqli_query($con, $sq2);
								$r2  = mysqli_fetch_assoc($q2);
								$mycount = $r2['total'];
								if ($mycount <= 0) {?>
								<a href="<?php echo $baseurl."setting/workflow_type.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a> 
								<?php } //Mrunmayee ended?> 
							</div>
							<?php $baseurl1 = $baseurl.$modulePath;?>
							<div class="col-sm-6 text-right">
								<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Cancel</a>
								<span>&nbsp;&nbsp;</span>
								<input class="btn btn-primary" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
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
