<?php

include("../header.php");
$modulePath = "setting/workflow_config.php?sub=list";
//background-color: lightblue;
?>

<style>
div.ex1 {
  
  width: 1280px;
  height: 550px;
  overflow: scroll;
}
</style>
  
  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">


<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Workflow
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Workflow</li>
      </ol>
    </section>

<div class="col-md-12">
	
		<div class="box box-info">
            <div class="box-header with-border">
            <?php	
				$targetpage = "workflow_config.php?sub=list"; 
				$limit = 10; 
				$start = 0;	
				$comid  = $_SESSION['comid'];

				if ($_POST['company_id'] or $_POST['doc_type'] or $_POST['search'] or $_POST['trans_type'] ){
					$_SESSION['company_id'] 	= $_POST['company_id'];
					$_SESSION['trans_type'] 	= $_POST['trans_type'];
					$_SESSION['doc_type'] 		= $_POST['doc_type'];
					$_SESSION['search'] 		= $_POST['search'];
					$_SESSION['reset'] ='';
				}
				
				if ($_SESSION['company_id'] or $_SESSION['doc_type'] or $_SESSION['search'] or $_SESSION['trans_type'] ){
					$trans_type 	= $_SESSION['trans_type'];
					$company_id 	= $_SESSION['company_id'];
					$doc_type 		= $_SESSION['doc_type'];
					$search 		= $_SESSION['search'];
				}
				
				if (!empty($_GET['reset']) || !empty($_SESSION['reset'])){
					$_SESSION['company_id'] 	= '';
					$_SESSION['doc_type'] 		= '';
					$_SESSION['trans_type'] 	= '';
					$_SESSION['search'] 		= '';
					$trans_type 	= $_SESSION['trans_type'];
					$company_id 	= $_SESSION['company_id'];
					$doc_type 		= $_SESSION['doc_type'];
					$search 		= $_SESSION['search'];
				}
		
				/* if(empty($company_id)){
					$company_id = '6';
				} */	
			?>
			<form class="form-horizontal" action="workflow_config.php?sub=list" method="post">
						<div class="form-group">
								<div class="col-md-2">
									<label class=" control-label">Trans&nbsp;Type</label>
									<select class="form-control select2" name="doc_type" id="doc_type" onchange="getworkflowtype(this.value);" >
									<option value=""> Select </option>
								
									<?php $sql = "select * from sma_doc_type where 1 and status = 'Y' order by doc_description ";
            								$q2 	= mysqli_query($con, $sql);
            								while($r2 = mysqli_fetch_array($q2)){ ?>
            								<option value="<?php echo $r2['doc_type'];?>"  <?php echo ($doc_type == $r2['doc_type'])?'selected="selected"':'';?>  ><?php echo $r2['doc_description'];?></option>
            						<?php } ?>
            							
									<!--<option value="PR" <?php echo ($doc_type == 'PR')? "SELECTED":'';?> > Purchase Requisition </option>-->
									<!--<option value="AP" <?php echo ($doc_type == 'AP')? "SELECTED":'';?> > Note for Approval(NOA) </option>-->
									<!--<option value="PO" <?php echo ($doc_type == 'PO')? "SELECTED":'';?> > Purchase Order </option>-->
									<!--<option value="GR" <?php echo ($doc_type == 'GR')? "SELECTED":'';?> > GRN </option>-->
									<!--<option value="SI" <?php echo ($doc_type == 'SI')? "SELECTED":'';?> > Supplier Invoice </option>-->
									<!--<option value="GI" <?php echo ($doc_type == 'GI')? "SELECTED":'';?> > Goods Issued Notes </option>-->
									<!--<option value="PY" <?php echo ($doc_type == 'PY')? "SELECTED":'';?> > Payment </option>										-->
									<!--<option value="TN" <?php echo ($doc_type == 'TN')? "SELECTED":'';?> > Tender </option>	-->
									<!--<option value="TO" <?php echo ($doc_type == 'TO')? "SELECTED":'';?> > Tender OTP</option>	-->
									
									<!--<option value="PC" <?php echo ($doc_type == 'PC')? "SELECTED":'';?> > Petty Cash</option>
															
									<option value="CE" <?php echo ($doc_type == 'CE')? "SELECTED":'';?> > Invoice Against OpEx </option>-->	
									<!--<option value="TA" <?php echo ($doc_type == 'TA')? "SELECTED":'';?> > Travel Request </option>										-->
									<!--<option value="TE" <?php echo ($doc_type == 'TE')? "SELECTED":'';?> > Travel Expenses </option>-->
									<!--<option value="RE" <?php echo ($doc_type == 'RE')? "SELECTED":'';?> > Reimbursement </option>-->
									<!--<option value="BD" <?php echo ($doc_type == 'BD')? "SELECTED":'';?> > Budget Adjustment </option>-->
									
									<!--<option value="DJ" <?php echo ($doc_type == 'DJ')? "SELECTED":'';?> > Approval Memo Adjustment</option>-->
									<!--<option value="RT" <?php echo ($doc_type == 'RT')? "SELECTED":'';?> > RTGS</option>-->
									<!--<option value="PD" <?php echo ($doc_type == 'PD')? "SELECTED":'';?> > Product</option>-->
									<!--<option value="AD" <?php echo ($doc_type == 'AD')? "SELECTED":'';?> > Advance</option>
									<!--<option value="BP" <?php echo ($doc_type == 'BP')? "SELECTED":'';?> > Budget Proposal</option>-->
									<!--<option value="DE" <?php echo ($doc_type == 'DE')? "SELECTED":'';?>> Direct Payment </option>-->
									<!--<option value="IN" <?php echo ($doc_type == 'IN')? "SELECTED":'';?>> Receipt/Sales </option>-->
									<!--<option value="RI" <?php echo ($doc_type == 'RI')? "SELECTED":'';?>> Retention </option>-->
									</select>
								</div>
								
							<div class="col-md-4" class="input-append">
								<label class="control-label">Company* </label>
								<select class="form-control" name="company_id" id="company_id" >
									<option value="">Select</option>	
									<?php
										$sql="SELECT * FROM company ORDER BY comp_name ASC";
										$q2 = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while($r2 = mysqli_fetch_array($q2)){
									?>
									<option value="<?php echo $r2['comp_id']?>" <?php echo ($company_id == $r2['comp_id'])?'selected="selected"':'';?> ><?php echo $r2['comp_name'];?></option>
									<?php } ?>
								</select>
							</div>
							
						
							 
							<div class="col-md-2">
                                <label class="control-label">&nbsp;</label><BR>	
								<input class="btn btn-success" type="submit" value="Search" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="workflow_config.php?sub=list&reset=1" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
							</div>
							
						</div>
						
							<span id="getsearchf">
							<?php if($searchf=='N' || $searchf=='S'){ ?>	
								<div class="col-md-3">
								<?php if($searchf=='N'){ ?>
									<input type="text" class="form-control" id="search_data" name="search_data" autocomplete="off" value="<?php echo $search_data?>" >
								<?php } ?>
								
								</div>
							<?php } ?>
							
							</span>
											   
				</form>
				
			
            <div class="pull-right">
				<span class="sepV_c marginRight">
					<a href="workflow_config.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Add </a>
					<a href="user_export_func.php?sub=workflow" target="_blank" name="btnAdd" class="btn btn-primary">&nbsp;&nbsp;Export </a>
				</span>
			</div>
			</div>
		</div>	
    <div class="box">
	<div class="ex1">
    <table id="prtable" class="table table-bordered table-striped" style="overflow: scroll;">

	<thead>
		<tr>
			<th>Company</th>
			<th>Trans.Type</th>
			
			<th>Approver  1</th>
			<th>Approver  2</th>
			<th>Approver  3</th>
			<th>Approver  4</th>
			
			<th>Email Nofication 1</th>
			<th>Email Nofication 2</th>
			<th>Email Nofication 3</th>
			
		</tr>
	</thead>
<tbody>
<?php

	$sql	="SELECT * from sma_workflow where 1 ";
	$query	="SELECT count(*) as num from sma_workflow where 1 ";
		if(!empty($company_id)){
			$sql   .= " and company_id = '$company_id' ";
			$query .= " and company_id = '$company_id' ";
		}

		if(!empty($doc_type)){
			$sql   .= " and doc_type = '$doc_type' ";
			$query .= " and doc_type = '$doc_type' ";
		}	
		
		if(!empty($trans_type)){
			$sql   .= " and trans_type = '$trans_type' ";
			$query .= " and trans_type = '$trans_type' ";
		}	
		
		
//echo $sql. "<BR>";		
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
	
	    $doc_type = $row['doc_type'];
	
	    $sql= "SELECT * FROM sma_doc_type where doc_type = '$doc_type' ";
		$q2 = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$doc_type = $r2['doc_description'];
		
		
	$company_id = $row['company_id'];
	$sql="SELECT * from company where comp_id = '$company_id' ";
	$q1 = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$r1 = mysqli_fetch_array($q1);
	$company_name = $r1['comp_name'];
	$company_code = $r1['comp_code'];
	
	$trans_type = $row['trans_type'];
	$sql="SELECT * from sma_workflow_type where id = '$trans_type' ";
	$q1 = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$r1 = mysqli_fetch_array($q1);
	$workflow_type = $r1['workflow_type'];
	$active_status			= $r1['status'];
	
	if($active_status=='N'){
		continue;
	}
	
	$approval_role_1_id = $row['approval_role_1'];
	$sql="SELECT * from sma_user where id = '$approval_role_1_id' ";
	$q1 = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$r1 = mysqli_fetch_array($q1);
	$approval_role_1 = $r1['username'];
	
	$approval_role_2_id = $row['approval_role_2'];
	$sql="SELECT * from sma_user where id = '$approval_role_2_id' ";
	$q1 = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$r1 = mysqli_fetch_array($q1);
	$approval_role_2 = $r1['username'];
	
	$approval_role_3_id = $row['approval_role_3'];
	$sql="SELECT * from sma_user where id = '$approval_role_3_id' ";
	$q1 = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$r1 = mysqli_fetch_array($q1);
	$approval_role_3 = $r1['username'];
	
	$approval_role_4_id = $row['approval_role_4'];
	$sql="SELECT * from sma_user where id = '$approval_role_4_id' ";
	$q1 = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$r1 = mysqli_fetch_array($q1);
	$approval_role_4 = $r1['username'];

		$email_one = $row['email_one'];
		$sql="SELECT * from sma_user where id = '$email_one' ";
		$q1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($q1);
		$email_one = $r1['username'];
//echo $sql ."<BR>";		
		
		$email_two = $row['email_two'];
		$sql="SELECT * from sma_user where id = '$email_two' ";
		$q1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($q1);
		$email_two = $r1['username'];
		
		$email_three = $row['email_three'];
		$sql="SELECT * from sma_user where id = '$email_three' ";
		$q1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($q1);
		$email_three = $r1['username'];
		
	$baseurl1 = "workflow_config.php?sub=edit&id=". $row['id'];
	
?>

	<a href="<?php echo "workflow_config.php?sub=edit&id=". $row['id'];?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="5%"><?php echo $company_code;?></td>
		<td width="08%"><?php echo $doc_type;?></td>
		
		<td width="10%"><?php echo $approval_role_1. ' ';?></td>
		<td width="10%"><?php echo $approval_role_2. ' ' ;?></td>
		<td width="10%"><?php echo $approval_role_3. ' ' ;?></td>
		<td width="10%"><?php echo $approval_role_4. ' ' ;?></td>
		
		<td width="10%"><?php echo $email_one;?></td>
		<td width="10%"><?php echo $email_two;?></td>
		<td width="10%"><?php echo $email_three;?></td>
		
		
		<!--<td width="5%" style="text-align:right;">
		<a href="workflow_config.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		-->
		
<!--<a href="workflow_config.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
		
		</td>
    </tr>
	</a>
	
	<?php }?>
</tbody> 
</table>
		</div>
	  </div>
    </div>
</div>

    <?php }?>


<?php  
	if($_GET['sub'] == 'delete'){ 
        $id = $_GET['id'];
		
			$sql	="Select * from sma_workflow where id ='$id'";
			$query 	= mysqli_query($con, $sql);
			$row 	= mysqli_fetch_array($query);
			$doc_type 			= $row['doc_type'];
			$trans_type 			= $row['trans_type'];
			$company_id 			= $row['company_id'];
			
			$pgname 		= "workflow_config.php";
			include "../viewonly.php";
			$description 	= $doc_type.','.$trans_type.','.$company_id;
		    $affect 		= 'Deleted';
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
		$sql="delete from sma_workflow where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="workflow_config.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){
?>

<?php
	if(isset($_POST['Save'])){

			$doc_type			= $_POST['doc_type'];
			$from_value			= $_POST['from_value'];
			$to_value			= $_POST['to_value'];
			$company_id			= $_POST['company_id'];
			$trans_type			= $_POST['trans_type'];
			$approval_role_1	= $_POST['approval_role_1'];
			$approval_role_2	= $_POST['approval_role_2'];
			$approval_role_3	= $_POST['approval_role_3'];
			$approval_role_4	= $_POST['approval_role_4'];
			
			$email_one			= $_POST['email_one'];
			$email_two			= $_POST['email_two'];
			$email_three		= $_POST['email_three'];
			$email_four			= $_POST['email_four'];
			
			$email_final_approval = $_POST['email_final_approval'];
			
			
  			$sql="insert into sma_workflow (doc_type, trans_type, from_value, to_value,  company_id,  approval_role_1, approval_role_2, approval_role_3, email_one, email_two, email_three, email_four, email_final_approval) 
				Values('$doc_type', '$trans_type', '$from_value', '$to_value', '$company_id', '$approval_role_1', '$approval_role_2', '$approval_role_3', '$email_one', '$email_two', '$email_three', '$email_four', '$email_final_approval' )";
					
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			$pgname 		= "workflow_config.php";
			include "../viewonly.php";
			$description 	= $doc_type.','.$trans_type.','.$company_id. ','. $from_value. ' To '. $to_value ;
		    $affect 		= 'Added';
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
//			echo "Workflow successful added";
			echo '<script>window.location.href="workflow_config.php?sub=list";</script>';
		}
	

?>
   <section class="content-header">
        <h1>
            Workflow
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Workflow</a></li>
            <li class="active">Create</li>
        </ol>
    </section>

    <!-- Content Header (Page header) -->
    <div class="col-md-12">
    
		
        <!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box">
            <!-- form start -->
            <form class="form-horizontal" action="workflow_config.php?sub=add" method="post">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
						
						<div class="form-group">
						
							<label for="company_id" class="control-label col-sm-2">For Company *</label>
							<div class="col-sm-6">
								<select class="form-control select3" name="company_id" id="company_id" required >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_id'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>
						
						<div class="form-group">
							
							<label for="company_id" class="control-label col-sm-2">Transaction Type *</label>
							<div class="col-sm-4">
								<select class="form-control select2" name="doc_type" id="doc_type" onchange="getworkflowtype(this.value);" >
									<option value=""> Select </option>
									
            							<?php $sql = "select * from sma_doc_type where 1  and status = 'Y' order by doc_description ";
            								$q2 	= mysqli_query($con, $sql);
            								while($r2 = mysqli_fetch_array($q2)){ ?>
            								<option value="<?php echo $r2['doc_type'];?>" ><?php echo $r2['doc_description'];?></option>
            							<?php } ?>
            							
								</select>
							</div>

						</div>
						
						<!--<div class="form-group">-->
						
						<!--	<label for="company_id" class="control-label col-sm-2">Prefix (For PO type of form)</label>-->
						<!--	<div class="col-sm-2">-->
						<!--		<input type="text" class="form-control" name="prefix" id="prefix" value="" >-->
						<!--	</div>-->
							
						<!--	<label for="company_id" class="control-label col-sm-1">Suffix</label>-->
						<!--	<div class="col-sm-2">-->
						<!--		<input type="text" class="form-control" name="suffix" id="suffix" value="" >-->
						<!--	</div>-->
							

						<!--</div>-->
						
						<div class="form-group">
							<label class="col-lg-2 control-label">From Value*</label>
							<div class="col-md-2">
								<input type="text" class="form-control" name="from_value" id="from_value" value="" required >
							</div>
							
							<label class="col-lg-1 control-label">To Value*</label>
							<div class="col-md-2">
								<input type="text" class="form-control" name="to_value" id="to_value" value="" required >
							</div>
						</div>
						
						
							<?php 
								$selected_ho 	= '';
								$selected_site 	= '';
								$company_id = $row['company_id'];
								
							?>
						<div class="form-group">
							<label class="control-label col-sm-2">&nbsp;</label>
							<label for="company_id" class="control-label col-sm-4">Approver Role - To be provided Sequentially, No gaps</label>
						</div>
						
						<div class="form-group">
						
							<label for="company_id" class="control-label col-sm-2">Approver Level 1*</label>
							<div class="col-sm-4">
								<select class="form-control select3" name="approval_role_1" id="approval_role_1" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_user where 1 and active = '1' order by username";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  >  <?php echo $r2['username'];?></option>
										<?php } ?>
								</select>
							</div>
						
							
						</div>
						<div class="form-group">
						
							<label for="company_id" class="control-label col-sm-2">Approver Level 2</label>
							<div class="col-sm-4">
								<select class="form-control select3" name="approval_role_2" id="approval_role_2" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_user where 1 and active = '1' order by username ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  >  <?php echo $r2['username'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>
						<div class="form-group">
						
							<label for="company_id" class="control-label col-sm-2">Approver Level 3</label>
							<div class="col-sm-4">
								<select class="form-control select3" name="approval_role_3" id="approval_role_3" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_user where 1 and active = '1' order by username ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  >  <?php echo $r2['username'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>
						
						<div class="form-group">
						
							<label for="company_id" class="control-label col-sm-2">Approver Level 4</label>
							<div class="col-sm-4">
								<select class="form-control select3" name="approval_role_4" id="approval_role_4" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_user where 1 and active = '1' order by username ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  >  <?php echo $r2['username'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<label for="company_id" class="control-label col-sm-2">Send Email on Final Approval<span data-toggle="tooltip" title="Multiple email put comma seperated" class="badge bg-light-blue">?</span></label>
							<div class="col-sm-4">
							    <input type="text" class="form-control" name="email_final_approval" id="email_final_approval" value="<?php echo $row['email_final_approval'];?>" >
								
							</div>
					
					
						</div>
						
						
					
					    <div class="form-group">
							<label for="company_id" class="control-label col-sm-5">Send Email Notification on each Approval</label>
						</div>
						<div class="form-group">
							<div class="col-sm-1">&nbsp;
							</div>    
							<div class="col-sm-3">
								<select class="form-control select2" name="email_one" id="email_one" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_user where 1 order by username ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['email_one'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['username'];?></option>
										<?php } ?>
								</select>
							</div>
						
							   
							<div class="col-sm-3">
								<select class="form-control select2" name="email_two" id="email_two" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_user where 1 order by username ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['email_two'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['username'];?></option>
										<?php } ?>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-sm-1">&nbsp;
							</div>    
							<div class="col-sm-3">
								<select class="form-control select2" name="email_three" id="email_three" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_user where 1 order by username ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['email_three'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['username'];?></option>
										<?php } ?>
								</select>
							</div>
						
							   
							<div class="col-sm-3">
								<select class="form-control select2" name="email_four" id="email_four" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_user where 1 order by username ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['email_four'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['username'];?></option>
										<?php } ?>
								</select>
							</div>
						</div>
						
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
<?php } 	?>


<?php if($_GET['sub'] == 'edit'){
?>

<?php
	if(isset($_POST['Save'])){
			$id			= $_POST['id']; 
			
			$doc_type			= $_POST['doc_type'];
			$company_id			= $_POST['company_id'];
			$from_value			= $_POST['from_value'];
			$to_value			= $_POST['to_value'];
			$company_id			= $_POST['company_id'];
			$trans_type			= $_POST['trans_type'];
			$mobile_reimbure_flag= $_POST['mobile_reimbure_flag'];
			$doa_desc			= $_POST['doa_desc'];
			
 			$approval_role_1	= $_POST['approval_role_1'];
			$approval_role_2	= $_POST['approval_role_2'];
			$approval_role_3	= $_POST['approval_role_3'];
			$approval_role_4	= $_POST['approval_role_4'];
			
			$email_one			= $_POST['email_one'];
			$email_two			= $_POST['email_two'];
			$email_three		= $_POST['email_three'];
			$email_four			= $_POST['email_four'];
			
			$email_final_approval = $_POST['email_final_approval'];
			
  			$sql="update sma_workflow set doc_type = '$doc_type',
						company_id			= '$company_id',
						from_value			= '$from_value',
						to_value			= '$to_value',
						company_id			= '$company_id',
						trans_type			= '$trans_type',
						mobile_reimbure_flag= '$mobile_reimbure_flag',
						doa_desc			= '$doa_desc',
						approval_role_1		= '$approval_role_1',
						approval_role_2		= '$approval_role_2',
						approval_role_3		= '$approval_role_3',
						approval_role_4		= '$approval_role_4',
						email_one				= '$email_one',
						email_two				= '$email_two',
						email_three				= '$email_three',
						email_four				= '$email_four',
						email_final_approval    = '$email_final_approval'
				where id='$id'";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			$pgname 		= "workflow_config.php";
			include "../viewonly.php";
			$description 	= $doc_type.','.$trans_type.','.$company_id. ','. $from_value. ' To '. $to_value ;
		    $affect 		= 'Modified';
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);


			echo '<script>window.location.href="workflow_config.php?sub=list";</script>';
			exit();
			
		}
		
		$id = $_GET['id'];
		$sql="Select * from sma_workflow where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

?>
	
    <!-- Content Header (Page header) -->
    <div class="col-md-12">
    
   <section class="content-header">
        <h1>
            Workflow
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Workflow</a></li>
            
        </ol>
    </section>
	
        <!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box">
            <!-- form start -->
            <form class="form-horizontal" action="workflow_config.php?sub=edit" method="post">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
							
					<div class="row">		
					<div class="col-sm-6">		
						<div class="form-group">
						
							<label for="company_id" class="control-label col-sm-4">For Company*</label>
							<div class="col-sm-8">
								<select class="form-control select3" name="company_id" id="company_id" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_id'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>
						
						<?php $doc_type = $row['doc_type']; ?>
						
						<div class="form-group">
							
							<label for="doc_type" class="control-label col-sm-4">Transaction Type*</label>
							<div class="col-sm-8">
								<select class="form-control select2" name="doc_type" id="doc_type" onchange="getworkflowtype(this.value);" >
									<option value=""> Select </option>
									<?php $sql = "select * from sma_doc_type where 1 and status = 'Y' order by doc_description ";
            								$q2 	= mysqli_query($con, $sql);
            								while($r2 = mysqli_fetch_array($q2)){ ?>
            								<option value="<?php echo $r2['doc_type'];?>"  <?php echo ($row['doc_type'] == $r2['doc_type'])?'selected="selected"':'';?>  ><?php echo $r2['doc_description'];?></option>
            						<?php } ?>
            							
								</select>
							</div>
						</div>
					</div>	
					
							
					</div>
						
						<?php 
						
							$doc_type 	= $row['doc_type'];
						?>
						
						
						<!--<div class="form-group">-->
						
						<!--	<label for="company_id" class="control-label col-sm-2">Prefix (For PO type of form)</label>-->
						<!--	<div class="col-sm-2">-->
						<!--		<input type="text" class="form-control " name="prefix" id="prefix" value="<?= $row['prefix']; ?>" >-->
						<!--	</div>-->
							
						<!--	<label for="company_id" class="control-label col-sm-1">Suffix</label>-->
						<!--	<div class="col-sm-2">-->
						<!--		<input type="text" class="form-control " name="suffix" id="suffix" value="<?= $row['suffix']; ?>" >-->
						<!--	</div>-->
							
						<!--</div>-->
						
						<div class="form-group">
							<label class="col-lg-2 control-label">From Value*</label>
							<div class="col-md-2">
								<input type="text" class="form-control" name="from_value" id="from_value" value="<?php echo $row['from_value'];?>" required >
							</div> 
							
							<label class="col-lg-1 control-label">To Value*</label>
							<div class="col-md-2">
								<input type="text" class="form-control" name="to_value" id="to_value" value="<?php echo $row['to_value'];?>" required >
							</div>
						</div>
						
							<?php 	
								$selected_ho 	= '';
								$selected_site 	= '';
								$company_id = $row['company_id'];
								
							?>
						<div class="form-group">
							<label class="control-label col-sm-2">&nbsp;</label>
							<label for="company_id" class="control-label col-sm-4">Approver Role - To be provided Sequentially,No gaps</label>
							
						</div>
						
						<div class="form-group">
						
							<label for="company_id" class="control-label col-sm-2">Approver Level 1*</label>
							<div class="col-sm-4">
								<select class="form-control select2" name="approval_role_1" id="approval_role_1" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_user where 1 and active = '1' order by username";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['approval_role_1'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['username'];?></option>
										<?php } ?>
								</select>
							</div>
						
						</div>
						
						<div class="form-group">
						
							<label for="company_id" class="control-label col-sm-2">Approver Level 2</label>
							<div class="col-sm-4">
								<select class="form-control select3" name="approval_role_2" id="approval_role_2" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_user where 1 and active = '1' order by username";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['approval_role_2'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['username'];?></option>
										<?php } ?>
								</select>
							
							</div>	
							
						</div>
						
						<div class="form-group">
						
							<label for="company_id" class="control-label col-sm-2">Approver Level 3</label>
							<div class="col-sm-4">
								<select class="form-control select3" name="approval_role_3" id="approval_role_3" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_user where 1 and active = '1' order by username ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['approval_role_3'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['username'];?></option>
										<?php } ?>
								</select>
							</div>
							
						</div>	
					
					    <div class="form-group">
						
							<label for="company_id" class="control-label col-sm-2">Approver Level 4</label>
							<div class="col-sm-4">
								<select class="form-control select3" name="approval_role_4" id="approval_role_4" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_user where 1 and active = '1' order by username ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['approval_role_4'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['username'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<label for="company_id" class="control-label col-sm-2">Send Email on Final Approval<span data-toggle="tooltip" title="Multiple email put comma seperated" class="badge bg-light-blue">?</span></label>
							<div class="col-sm-4">
							    <input type="text" class="form-control" name="email_final_approval" id="email_final_approval" value="<?php echo $row['email_final_approval'];?>" >
							</div>
					
						</div>
						
					    <div class="form-group">
							<label for="company_id" class="control-label col-sm-5">Send Email Notification on each Approval</label>
						</div>
						<div class="form-group">
							<div class="col-sm-1">&nbsp;
							</div>    
							<div class="col-sm-3">
								<select class="form-control select2" name="email_one" id="email_one" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_user where 1 order by username ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['email_one'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['username'];?></option>
										<?php } ?>
								</select>
							</div>
						
							   
							<div class="col-sm-3">
								<select class="form-control select2" name="email_two" id="email_two" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_user where 1 order by username ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['email_two'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['username'];?></option>
										<?php } ?>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-sm-1">&nbsp;
							</div>    
							<div class="col-sm-3">
								<select class="form-control select2" name="email_three" id="email_three" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_user where 1 order by username ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['email_three'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['username'];?></option>
										<?php } ?>
								</select>
							</div>
						
							   
							<div class="col-sm-3">
								<select class="form-control select2" name="email_four" id="email_four" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_user where 1 order by username ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['email_four'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['username'];?></option>
										<?php } ?>
								</select>
							</div>
						</div>
						
						
						
   						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; 
								//Mrunmayee started
								$sq2 = "SELECT COUNT(*) as total FROM `sma_purchase_order` where po_doc_type = '$did'";
								$q2  = mysqli_query($con, $sq2);
				
								$r2  = mysqli_fetch_assoc($q2);
								$mycount = $r2['total'];

								$sq3 = "SELECT COUNT(*) as total FROM `sma_supplier_invoice` where trans_type = '$did'";
								$q3  = mysqli_query($con, $sq3);
				
								$r3  = mysqli_fetch_assoc($q3);
								$mycount1 = $r3['total'];
								if ($mycount <= 0 and $mycount1 <= 0) { ?>
								<a href="<?php echo $baseurl."setting/workflow_config.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a> 
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
      
<?php } 	?>

<?php 	
		include("../footer.php");	
?>
<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/iCheck/icheck.min.js" ?>"></script>
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
            "paging": true,
            "ordering": false,
            "info": false,
            "searching": false
        });
    });


	function getlocation(id){
		
        var sub    = 'sub1';
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub1:sub},function(result){
		      $('#getlocation').html(result);
		});

	}
	
	function getworkflowtype(id){
		
        var sub    = 'sub5';
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub5:sub},function(result){
		      $('#getworkflowtype').html(result);
		});

	}


	function getbalbugdget(){
		var trans_type =  document.getElementById('trans_type').value;
		var approval_role_1 =  document.getElementById('approval_role_1').value;
		
		var balance_budget = trans_type - approval_role_1;
		
		//$('#balance_budget').attr('readonly', true);
		document.getElementById('balance_budget').value=balance_budget;
        
		//alert(balance_budget);
		if (balance_budget < 0){
			alert("Used Workflow should be less then total budget...");
			//var approval_role_1 = 0;
			document.getElementById('approval_role_1').value=0;
			
		}
		
	}	
	
//iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass: 'iradio_minimal-red'
    });
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });	
</script>


<!-- iCheck 1.0.1 -->


</body>
</html>

