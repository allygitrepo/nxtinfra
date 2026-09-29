<?php
include("../header.php");
$modulePath = "provisional_jv/";

$_SESSION['reset'] = '1';

$user   = $_SESSION['user'];
$userid   	= $_SESSION['usrid'];

	$help_code = $modulePath.'index.php';
	include "../help_code.php";
	
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php  
	if($_GET['sub'] == 'delete'){
        $id  = $_GET['pv_id'];

			
//		$sql = "delete from sma_provisional_jv_hdr where id='$id' ";
		$sql = "update sma_provisional_jv_hdr set del = 'Y' where id='$id' ";
        mysqli_query($con, $sql);
		echo mysqli_error($con);
echo $sql . "<BR>";

/*		$sql = "delete from sma_provisional_jv_details where provisional_jv_hdr_id = '$id' ";
		$query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
//echo $sql;
		$sql="delete FROM `file_uploads` where module = 'PV' and reference_id = '$id' ";
		$rt = mysqli_query($con, $sql);
		echo mysqli_error($con);
*/		
//echo $sql;
//exit();
        //echo '<script>window.location.href="supplier_invoice.php?sub=list";</script>';
		$baseurl1 =$baseurl.$modulePath;
		echo "<script>window.location.href='$baseurl1';</script>";
			
	} 

?>

<?php
	if(isset($_POST['editPVCode'])){
		$record_id     				= $_POST['record_id'];
		$provisional_jv_hdr_id 		= $_POST['provisional_jv_hdr_id'];
		$doc_no						= $_POST['provisional_jv_hdr_id'];
		$effect						= $_POST['effect'];
		$account_id					= $_POST['account_id'];
		$amount						= $_POST['amount'];
		$narration						= $_POST['narration'];
		
		$sql = "UPDATE sma_provisional_jv_details SET account_id = '$account_id', amount = '$amount', narration = '$narration' WHERE id = '$record_id' ";
		$r2 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		
		//echo "<meta http-equiv='refresh' content='0'>";    
		$baseurl.=$modulePath.'edit.php?id='.$provisional_jv_hdr_id.'&IN=in';//'&active5=active&zyx'
		echo "<script>window.location.href='$baseurl';</script>";
		exit();
		
	}
	
	if(isset($_POST['editTally'])){
		 
		$record_id     		= $_POST['record_id'];
		$provisional_jv_hdr_id 			= $_POST['provisional_jv_hdr_id'];
		$doc_no				= $_POST['provisional_jv_hdr_id'];
		$account_type		= $_POST['account_type'];
		$account_id 		= $_POST['account_id'];
		$amount 			= $_POST['amount'];
		$prev_amount		= $_POST['prev_amount'];
		$narration 			= $_POST['narration'];
		$effect 			= $_POST['effect'];
		$sql = "update `tally_journal_entry` set 
					record_id     		= '$record_id',
					account_type 		= '$account_type',
					account_id 			= '$account_id',
					amount 				= '$amount',
					narration 			= '$narration',
					effect 				= '$effect'
				where doc_no = '$provisional_jv_hdr_id' and record_id = '$record_id' ";
		$r2 = mysqli_query($con, $sql);
		echo mysqli_error($con);

//echo $sql. "<BR>";
//exit();
	
		
		if( $amount > 0 && $effect =='Cr' ){
			$sql = " update tally_journal_entry set amount = amount - $amount + $prev_amount where doc_type = 'PV' and doc_no = '$doc_no' and effect = 'Cr' and account_type = 'V' and account_id != '$account_id' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
//	echo $sql. "<BR>";		
			$sql 	= " update sma_provisional_jv_hdr set payable_amount = payable_amount - '$amount' + $prev_amount, bal_amount = bal_amount - '$amount' + $prev_amount where id = '$provisional_jv_hdr_id' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
//echo $sql. "<BR>";			
		}
		else if( $amount > 0 && $effect =='Dr' ){
			$sql = " update tally_journal_entry set amount = amount + $amount - $prev_amount where doc_type = 'PV' and doc_no = '$doc_no' and effect = 'Cr' and account_type = 'V' and account_id != '$account_id' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
//echo $sql. "<BR>";			
			$sql 	= " update sma_provisional_jv_hdr set payable_amount = payable_amount + '$amount' - $prev_amount, bal_amount = bal_amount + '$amount' - $prev_amount where id = '$provisional_jv_hdr_id' ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
//echo $sql. "<BR>";			
		}
		

//echo $sql. "<BR>";
//exit();

		echo "<meta http-equiv='refresh' content='0'>";    
		$baseurl.=$modulePath.'edit.php?id='.$provisional_jv_hdr_id.'&IN=in';//'&active5=active&zyx'
		echo "<script>window.location.href='$baseurl';</script>";
		exit();
		
	}
	
?>

<?php
	if(isset($_POST['Save'])){
		
	
			$id				= $_POST['id']; 
			$pv_id			= $_POST['id'];
			$dated					= date('Y-m-d', strtotime($_POST['dated']));
			$company_id				= $_POST['company_id'];
			$trans_type				= $_POST['trans_type'];
			$provisional_account_name	= $_POST['provisional_account_name'];
			$provisional_jv_flag		= $_POST['provisional_jv_flag'];
			
			$tally_status			= $_POST['tally_status'];
			
			$tally_narration			= $_POST['tally_narration'];
			$tally_narration_reversal 	= $_POST['tally_narration_reversal'];
	//echo $tally_narration. "<<>>";		exit();

			$approver_1			= $_POST['approver_1'];
			$approver_2			= $_POST['approver_2'];
			$approver_3			= $_POST['approver_3'];
			$approver_4			= $_POST['approver_4'];
			$approver_5			= $_POST['approver_5'];
			$approver_6			= $_POST['approver_6'];
			$approver_7			= $_POST['approver_7'];
			$approver_8			= $_POST['approver_8'];
			
			$location_id		= $_POST['location_id'];
			$status				= $_POST['status'];
			if ( $status == 'Draft' ){	
				$sql="update sma_provisional_jv_hdr set dated	= '$dated',
						company_id					= '$company_id',
						trans_type					= '$trans_type',
						provisional_account_name	= '$provisional_account_name',
						tally_narration				= '$tally_narration',
						tally_narration_reversal	= '$tally_narration_reversal',
						provisional_jv_flag			= '$provisional_jv_flag'
				where id='$id'";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
			}
			
			if( !empty($approver_1) && $status == 'Draft' ){
				$status				= 'Submitted';
				$approver_1_status  = 'Submitted';
				$sql = "update sma_provisional_jv_hdr set current_approver = '$approver_1',
						approver_1			= '$approver_1',
						approver_2			= '$approver_2',
						approver_3			= '$approver_3',
						approver_4			= '$approver_4',
						approver_5			= '$approver_5',
						approver_6			= '$approver_6',
						approver_7			= '$approver_7',
						approver_8			= '$approver_8',
						approver_1_status	= '$approver_1_status',
						status				= '$status'
					where id='$id'";	
				$query=mysqli_query($con, $sql);	
				
				$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved_date ) 
					values( 'PV', '$pv_id', '$userid', now(), 'Submitted', '$approver_1', now() ) ";
					$query=mysqli_query($con, $sql);
					$error= mysqli_error($con);
					if(!empty($error)){echo $error; exit();}
					
				$modulePath = "provisional_jv/";
				
				$sql="select * from sma_user where id='$approver_1' and active='1' ";				
				$result = mysqli_query($con, $sql);
				while($r = mysqli_fetch_object($result)){
					$username 		= $r->userid;
					$id		 		= $r->id;
					$role	 		= $r->role;
					$company_id 	= $r->company_id;
					$user_email		= $r->email;
					$user_name		= $r->username;
				}
				
				$baseurl1 = $baseurl.$modulePath.'edit.php?id='.$srno;
		
				$baseurl1 = $baseurl.$modulePath.'edit.php?id='.$pv_id;
				$btn_var = '<a href="'. $baseurl1 .'" class="btn btn-danger" style = "background-color: #6699CC;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Click for more information </a>';
				
				$baseurl1A = $baseurl.$modulePath.'editm.php?id='.$pv_id. '&status=A'.'&emid='.$user_email;
				$btn_varA = '<a href="'. $baseurl1A .'" class="btn btn-danger" style = "background-color: #00a65a;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Approve </a>';
				
				$baseurl1R = $baseurl.$modulePath.'editm.php?id='.$pv_id. '&status=R'.'&emid='.$user_email;
				$btn_varR = '<a href="'. $baseurl1R .'" class="btn btn-danger" style = "background-color: #b53737;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Reject </a>';
				
				$msg = 'Provisional Journal Number : '.$srno . ' ' . 'Dated : ' . date("d-m-Y");

				include "pv_mail.php";		
					
			}
						
			if($tally_status=='C'){
				$sql="update sma_provisional_jv_hdr set tally_ticked_by = '$user', tally_status = '$tally_status', tally_updated_on	= now() where id='$id'";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
		//echo $sql. "<<<>>"; exit();		
			}
			
//TALLY STATUS UPDATE			
			$sql = "update `tally_journal_entry` set status = '$tally_status' where doc_no = '$pv_id' and doc_type = 'PV' ";
			$r2 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			
			//echo $sql; exit();
			
//TALLY STATUS UPDATE		

		$amount = 0;
		$amount = $additional_charges;
		$sql = "select sum(amount) as amount from sma_provisional_jv_details where provisional_jv_hdr_id = '$id'";
	//echo $sql;	
		$r2 = mysqli_query($con, $sql);
		while($r1 = mysqli_fetch_array($r2)){
			$amount = $amount + $r1['amount'];
		}
		
		if($amount > 0 && $status != 'Completed'){
			$sql = " update `sma_provisional_jv_hdr` set gross_amount = '$amount' where id = '$id' ";
			$r2 = mysqli_query($con, $sql);
		}
			
			// add attachments
			// file upload
			
			$arrDocType 		= $_POST["doctype"];
			$arrDocDesc 		= $_POST["docdesc"];
			$share_point_link 	= $_POST['share_point_link'];
			$arrFUDoc 			= $_FILES["fudoc"];
			for($i = 0; $i < sizeof($arrDocType); $i++) {
				$folder_path = "uploads/pv/" . $pv_id;
				if (!file_exists($folder_path)){
					mkdir($folder_path, 0755, true);
					
					$findex = $folder_path.'/index.php';
					fopen($findex,'w');
				} 
				$filename 		= $arrFUDoc['name'][$i];
				$tmpFileName 	= $arrFUDoc['tmp_name'][$i];
				if(!empty($filename)){
						
					$sql = "INSERT INTO file_uploads (module, file_name, file_path, doc_type, doc_desc, share_point_link, reference_id, date_uploaded) 
					VALUES( 'PV', '$filename', '$folder_path',  '$arrDocType[$i]', '$arrDocDesc[$i]', '$share_point_link[$i]', '$pv_id', now() )";
					if (mysqli_query($con, $sql)){
						move_uploaded_file($tmpFileName, $folder_path. "/" . $filename);
					}
					else {
						echo "Error: " . mysqli_error($con);
					}
		
				}

			}

			
			$page					= $_POST['page']; 		
			$baseurl.=$modulePath.'index.php?sub=list&same_page='.$page;
			
			echo "<script>window.location.href='$baseurl';</script>";
		
		}
		
		$page = $_GET['page'];
		$id	 		= $_GET['id'];
		$pv_id		= $_GET['id']; 
		
		$active_tab2 = '';
		$active_tab1 = 'active';
		if($_GET['active']){
			$active_tab2 = $_GET['active'];
			$active_tab1 = '';
//			header('Location: '.$_SERVER['REQUEST_URI']);
		}
		if($_GET['active5']){
			$active_tab1 ='';
			$active_tab2 = '';
			$active_tab5 = $_GET['active5'];
		}
		
		$sql="Select * from sma_provisional_jv_hdr where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	
	
		$status = $row['status'];
		$approval_status = $row['approval_status'];
		$del 	= $row['del'];
		$draft_by 		= $row['draft_by'];
		$tally_status 	= $row['tally_status'];
		$tally_updated_on	= $row['tally_updated_on'];
		$tally_ticked_by 	= $row['tally_ticked_by'];
		
		$dated  	= date('d-m-Y', strtotime($row['dated']));
		$fyr		= date('Y', strtotime($dated));
		$fmth		= date('m', strtotime($dated));
		$fin_year	= '';
		if($fmth>=1 && $fmth<=3){
			$styr = $fyr - 1;
			$fin_year = $styr . '-'. $fyr;
		}
		else {
			$ltyr = $fyr + 1;
			$fin_year = $fyr . '-'. $ltyr;
		}
		$_SESSION['finance_year'] = $fin_year;
		
		$company_id 	= $row['company_id'];		

		$approver_1 		= $row['approver_1'];
		$approver_2 		= $row['approver_2'];
		$approver_3 		= $row['approver_3'];
		$approver_4 		= $row['approver_4'];
										
		$approver_5 		= $row['approver_5'];
		$approver_6 		= $row['approver_6'];
		$approver_7 		= $row['approver_7'];
		$approver_8 		= $row['approver_8'];

		$approver_1_status 	= $row['approver_1_status'];
		$approver_2_status 	= $row['approver_2_status'];
		$approver_3_status 	= $row['approver_3_status'];
		$approver_4_status 	= $row['approver_4_status'];	
		$approver_5_status 	= $row['approver_5_status'];
		$approver_6_status 	= $row['approver_6_status'];
		$approver_7_status 	= $row['approver_7_status'];
		$approver_8_status 	= $row['approver_8_status'];
	
		$draft_by_supplier	= $row['draft_by_supplier'];
										
//echo $tally_status. "<<<>>>";
		
		$pv_id = $row['id'];
		$readonly = '';
		if ( $status == 'Submitted' || $status == 'Completed' ){
			$readonly = 'READONLY';
		}
		if (($status == 'Submitted' ) || $status == 'Completed' ){
			$disabled = 'DISABLED';
		}
	
		if ( $status == 'Draft' ){
			$readonly_draft = 'READONLY';
		}
		
		if($approval_status=='Rejected'){
			$readonly = 'READONLY';
		}		
	
		if($del == 'Y'){
			$readonly = 'READONLY';
		}
		
		$readonly1 = 'READONLY';
		if ( $accountant_role=='Y' && ($status=='Completed' || $status=='Submitted') ){
			$readonly1 = '';
		}
		else if ( $status=='Draft' ){
			$readonly1 = '';
		}
		
		//$readonly = '';
?>
	
    <!-- Content Header (Page header) -->
    <div class="col-md-12">
    
    <section class="content-header">
        <h1>
            Provisional Journal
            <small>Edit</small>
			<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
			</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Provisional Journal</a></li>
            
        </ol>
    </section>
		
        <!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box">
            <!-- form start -->

					
            <form class="form-horizontal" action="edit.php?sub=edit" method="post" enctype="multipart/form-data">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
			  
					<?php
						$status   = $row['status'];
						$del   	  = $row['del'];
						if($del=='Y'){
							$status = 'Deleted';
						}
						
						if ($_GET['active8']){
							$active8 = $_GET['active8'];
							$active_tab1 = '';
							$active_tab2 = '';
							$active_tab5 = '';
						}
					?>
					<?php if($approval_status=='Rejected'){ ?>
						<span class="pull-right"><h4 style="color:red;"><b><?= $approval_status;?></b></h4> </span>
					<?php }
					else {
					?>
						<span class="pull-right"><h4 style="color:red;"><b><?= $stats .' ' .$status;?></b></h4> </span>
					<?php } ?>
					
					<input type="hidden" name="status" id="statuS" value="<?php echo $status;?>" >
					
				<?php 
					$baseurl2 = $baseurl . $modulePath. 'index.php?sub=list&same_page='. $page;
				?>	
					<span class="pull-right"><a href="<?php echo $baseurl2; ?>" class="btn btn-danger" >Back</a>&nbsp;&nbsp;&nbsp;</span>
		      
                    <input type="hidden" name="id" value="<?php echo $row['id'];?>">
				
			<ul class="nav nav-tabs">
				  <li class="<?php echo $active_tab1; ?>"><a href="#tab_1" data-toggle="tab" id="first_tab" >Provisional Journal</a></li>
				
				  <li class="<?php echo $active_tab3; ?>"><a href="#tab_3" data-toggle="tab" id="third_tab" >Document</a></li>
				  <li><a href="#tab_4" data-toggle="tab" class="btn btn-info" id="forth_tab" >Workflow History</a></li>
			<?php if( $status != 'Draft' ){	?>	
					<li  class="<?php echo $active8;?>"><a href="#tab_8" data-toggle="tab" id="eight_tab" class="btn btn-danger">Comments</a></li>
			<?php } ?>
				
		<!--		  <li><a href="provision_voucher.php?sub=pdf&id=<?= $row['id']; ?>&company_id=<?= $company_id?>" class="btn btn-danger" target="_blank" >Voucher</a></li>
			-->	  
			</ul>
				
		<div class="tab-content">
				
			<div class="tab-pane <?php echo $active_tab1;?>" id="tab_1">
					
						<div class="form-group">
							<div class="col-md-2">
								<label class="control-label">Serial Number</label>
								<input type="text" class="form-control" id="id" name="id" readonly style="text-align:right;" <?php echo $readonly; ?> <?php echo $readonly_draft; ?> value="<?php echo $row['id'];?>" >
							</div>
							
							
							<div class="col-sm-4">
							<!--	<input type="hidden" name="company_id" id="company_id" readonly value="<?php echo $row['company_id'];?>" >-->
							
								<label for="company_id" class="control-label">Company <span style="color:red;"> **</span> </label>
								<select class="form-control select2" name="company_id" id="company_id"  required <?php echo $readonly. ' ' . $disabled; ?> <?php echo $readonly_draft; ?> >
								<?php if (!$readonly && !$readonly_draft){ ?>
									<option value=""> Select </option>
								<?php } ?>	
								<?php $sql = "select * from company where 1 $sqla and comp_id in ($comid) order by comp_name ";
								$q2 	= mysqli_query($con, $sql);
								while($r2 = mysqli_fetch_array($q2)){ ?>
								<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_id'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
								<?php } ?>
								</select>		
							</div>
					<?PHP
					//echo $row['mm_yyyy']. "<<>";
						$mm = substr($row['mm_yyyy'],0,2);
						$yyyy = substr($row['mm_yyyy'],3,4);
						
					?>	
							<div class="col-md-2">
								<label class="control-label">For Month </label>
								<select class="form-control" id="mm_yyyy" name="mm_yyyy" <?= $readonly;?> onchange="getDate(this.value);" >
									<option value="" >Select</option>
									<option value="01" <?php echo ($mm == '01')?'selected="selected"':'';?> >January</option>
									<option value="02" <?php echo ($mm == '02')?'selected="selected"':'';?> >February</option>
									<option value="03" <?php echo ($mm == '03')?'selected="selected"':'';?> >March</option>
									<option value="04" <?php echo ($mm == '04')?'selected="selected"':'';?> >April</option>
									<option value="05" <?php echo ($mm == '05')?'selected="selected"':'';?> >May</option>
									<option value="06" <?php echo ($mm == '06')?'selected="selected"':'';?> >June</option>
									<option value="07" <?php echo ($mm == '07')?'selected="selected"':'';?> >July</option>
									<option value="08" <?php echo ($mm == '08')?'selected="selected"':'';?> >August</option>
									<option value="09" <?php echo ($mm == '09')?'selected="selected"':'';?> >September</option>
									<option value="10" <?php echo ($mm == '10')?'selected="selected"':'';?> >October</option>
									<option value="11" <?php echo ($mm == '11')?'selected="selected"':'';?> >November</option>
									<option value="12" <?php echo ($mm == '12')?'selected="selected"':'';?> >December</option>
								</select>
							</div>
							<div class="col-md-2">
								<label class="control-label">Year</label>
								
								<select class="form-control" id="yyyy" name="yyyy" <?= $readonly;?> onchange="getDate(this.value);" >
									<option value="" >Select</option>
									<option value="2023" <?php echo ($yyyy == '2023')?'selected="selected"':'';?> >2023</option>
									<option value="2024" <?php echo ($yyyy == '2024')?'selected="selected"':'';?> >2024</option>
									<option value="2025" <?php echo ($yyyy == '2025')?'selected="selected"':'';?> >2025</option>
								<!--	<option value="2026" <?php echo ($yyyy == '2026')?'selected="selected"':'';?> >2026</option>-->
								</select>
							</div>
							
						<!--		<div class="input-group date" data-provide="datepicker" data-date-format="mm-yyyy">
									<input type="text" class="form-control" id="mm_yyyy" name="mm_yyyy" <?= $readonly;?> value="<?php echo $row['mm_yyyy']; ?>" onchange="getDate(this.value);" >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
								
							</div>	-->
							
							<div class="col-md-2">
								<label class="control-label">Dated</label>
							<span id ="getDate">	
									<input type="text" class="form-control" readonly id="dated" name="dated" <?= $readonly;?> value="<?php echo date('d-m-Y', strtotime($row['dated']));?>" >
							</span>		
							</div>	
							 
						</div>

						<?PHP
							$revenue_check = '';
							$expense_check = '';
							$provisional_jv_flag = $row['provisional_jv_flag'];
							if($provisional_jv_flag=='E'){
								$expense_check = 'CHECKED';
							}
							if($provisional_jv_flag=='R'){
								$revenue_check = 'CHECKED';
							}							
						?>
						
						<div class="form-group">
							
					<?php 
						if($status == 'Draft' ){
					?>	
							<div class="col-md-2">
								<label class="control-label">Provisional Expense JV</label><br>
								<input type="radio" id="provisional_jv_flag" name="provisional_jv_flag" <?= $expense_check ; ?> value="E" onchange="getprovName(this.value);" >
							</div>	
							
							<div class="col-md-2">	
								<label class="control-label">Provisional Revenue JV</label>	<br>
								<input type="radio" id="provisional_jv_flag" name="provisional_jv_flag" <?= $revenue_check ; ?> value="R" onchange="getprovName(this.value);" >
							</div>	
					<?php } ?>
					<?php
						if($status != 'Draft' ){
							if($provisional_jv_flag=='E'){
					?>
							<div class="col-md-2">
								<label class="control-label">Provisional Expense JV</label><br>
								<b>Yes</b>
							</div>	
						<?php }
						else if($provisional_jv_flag=='R'){
						?>
							<div class="col-md-2">	
								<label class="control-label">Provisional Revenue JV</label>	<br>
								<b>Yes</b>
							</div>	
						<?php }
						} ?>
					
						
						</div>
						
						<div class="form-group">
						
							
						<?php
							$sql = "select * from company where comp_id = '$company_id' ";
							$q2  = mysqli_query($con, $sql);
							$r2  = mysqli_fetch_array($q2);
							$provisional_account_name  	= $r2['provional_jv_name'];
							
							$provisional_account_name = $row['provisional_account_name'];
							
						?>
							
							
								<label class="col-md-1 control-label">Provisional&nbsp;A/c</label>
								<div class="col-md-4">
									<input type="text" class="form-control" id="provisional_account_name"  name="provisional_account_name" readonly autocomplete="off" value="<?php echo $provisional_account_name;?>" >
								</div>

						<?php 
							$trans_type = $row['trans_type'];
						?>
							<label for="company_id" class=" col-md-2 control-label ">Workflow Type *</label>
							<div class="col-sm-5">
								<span id="getworkflowtype">		
									<select class="form-control select3" name="trans_type" <?= $readonly;?> id="trans_type" required >
									<option value=""> Select </option>
									<?php $sql = "select * from sma_workflow_type where doc_type = 'PV' and status = 'Y' ";
									$q2 	= mysqli_query($con, $sql);
									while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['id'];?>" <?php echo ($row['trans_type'] == $r2['id'])?'selected="selected"':'';?> >  <?php echo $r2['workflow_type'];?></option>
									<?php } ?>
									</select>
								</span>										
							</div>
							
									
					</div>
		
			<div class="panel panel-default">
				
                <div id="step1" class="panel-collapse collapse in <?= $_GET['IN']?>">
					<div class="panel-body">
						<fieldset>
						<?php	
							$disabled = "";
							if( $status == 'Completed' ){
								$disabled = "DISABLED";    
							}
						?>		
						<div class="box-body">
								<div class="box-header">
                                    <h4 class="box-title">Journal Entry</h4>
                                </div>
								
                                <div class="box-body">
									
									<div id="tallyPVentry">
									</div>
									
									<!-- Enter Here -->
								<?php if (empty($disabled)){ ?>	
										<span class="pull-right"><a href="#addPVLine" class="btn btn-primary" data-toggle="modal" data-mode="add" data-target="#addPVLine">Add </a></span>
								<?php  } ?>	
										<table id="prtablea" class="table table-bordered table-striped" width="100%" >
											<thead>
												<tr>
													<th width="10%" style="text-align:right;">#</th>
													<th width="30%">Account Name</th>
													<th> Cost Center Sub Group</th>
													<th width="20%">Narration</th>
													
													<th width="10%">Effect</th>
													<th width="10%" style="text-align:right;">Amount</th>
													<th width="10%">Action</th>
												</tr>
											</thead>
											
											<tbody>
										
									<?php
										$errror_budget_head ='';
										$sql = "SELECT * from sma_provisional_jv_details where provisional_jv_hdr_id = '$pv_id' order by id ";	
//echo $sql;			
										$q2 	= mysqli_query($con, $sql);
										$i		= 1;
										while($r2 	= mysqli_fetch_array($q2)){
											
											$dtlid		  		= $r2['id'];
											$effect		  		= $r2['effect'];
											$tally_entry_date 	= $r2['tally_entry_date'];
											$account_id  		= $r2['account_id'];
											$account_name  		= $r2['account_name'];
											$budget_head  		= $r2['budget_head'];
											$amount		   		= round($r2['amount'],2);
											$narration		   	= $r2['narration'];
											
											$sql="SELECT * FROM sma_product where id  = '$account_id' ";
											$qry = mysqli_query($con, $sql);
											echo mysqli_error($con);
											$r2 = mysqli_fetch_array($qry);
											$account_name		= $r2['name'];
											
											$sql = "SELECT b.* from sma_product a, sma_product_cost_center b where a.id = b.product_id and b.company_id = '$company_id' and a.id  = '$account_id' ";											
											
										//	$sql="SELECT * FROM sma_budget where id  = '$account_id' ";
											$qry = mysqli_query($con, $sql);
											echo mysqli_error($con);
											$r2 = mysqli_fetch_array($qry);
											$budget_head_id		= $r2['budget_id'];
											//$budget_head_id		= $r2['budget_head'];
											//$account_name		= $r2['budget_code'];
											
											$sql = "select * from sma_budget_subgroup where id = '$budget_head_id'";
											$qry = mysqli_query($con, $sql);
											$r1 = mysqli_fetch_array($qry);
											$budget_head = $r1['budget_head'];
										
											if(empty($budget_head)){
												$errror_budget_head = 'ERROR : Tally code not available for product...';
												$budget_head = "<span style='color:red;'>".' ERROR : Tally code not available for product.'."</span>";
											}
											$pv_total_amount = $pv_total_amount +  $amount;
											
											$url_var = urlencode($_SERVER['REQUEST_URI']);
									?>
											
											<tr>
												<td style="text-align:right;"><?php echo $i++ ; ?> </td>
												<td><?php echo $account_name ?> </td>
												<td><?php echo $budget_head ?> </td>
												<td><?php echo $narration ?> </td>
												<td><?php echo $effect ?> </td>
												<td style="text-align:right;"><?php echo number_format($amount,2); ?> </td>
												<td>
									<?php if (empty($disabled) ){ ?>		
											<a href='#modalEditPVE' data-id='<?php echo $dtlid;?>' data-mode='edit' data-toggle='modal' data-target='#modalEditPVE<?php echo $dtlid;?>' <i class='fa fa-edit'></i></a>&nbsp;&nbsp; 
											
								<?php  		include "edit_pve.php"; ?>	
											
											<a href="delete_pve.php?sub=delete&record_id=<?php echo $dtlid;?>&url=<?php echo $url_var ?>&provisional_jv_hdr_id=<?= $pv_id;?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>
											
									<?php } ?>
												</td>
												
											</tr>
									<?php } ?>		
									
											<tr>
												<td>&nbsp; </td>
												<td>&nbsp; </td>
												<td>&nbsp; </td>
												<th>Total</th>
												<td style="text-align:right;"><?php echo number_format($pv_total_amount,2); ?> </td>
												<td></td>
												<td></td>
											</tr>	
									</table>
									
							<?php $checker_value = $pv_total_amount; ?>		
							<input type="hidden" name="checker_value" id="checker_value" value="<?= $checker_value;?>" >
									
						</div>
												
					</div>						
				</div>
			</div>
		</div>		

					
		<!--Tally Journal Start-->
		<?php 
	
		
		if ($accountant_role=='M' || $accountant_role=='Y' ){
			$disabled = "";
		}
			
		if( $tally_status == 'C' || $tally_status == 'U' || $status == 'Completed' ){
		    $disabled = "DISABLED";    
		}
		
		$tally_status = $row['tally_status'];
		
		if( ($accountant_role=='Y' || $accountant_role=='M') ){ 
		//&& ($status =='Completed' || $status=='Draft')
		?>
			<div class="panel panel-default">
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" class="btn btn-info" data-parent="#steps" href="#step1"><b style="color:white;"> Tally Journal</b></a></h4>
				</div>

                <div id="step1" class="panel-collapse collapse in <?= $_GET['IN']?>">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">
								<div class="box-header">
                                    <h4 class="box-title">Tally Journal Account</h4>
									<?php //if (empty($disabled)){ ?>
                                        <span class="pull-right">
                                            <a href="#modalAddTally"
                                               class="btn btn-primary" 
                                               data-toggle="modal" 
                                               data-mode="add"
                                               data-target="#modalAddTally">Create Journal
                                            </a>
										</span>	
								<?php // } ?>	
                                        
                                </div>
								
                                <div class="box-body">
									
									<div id="tallyentry">
									
									<!-- Enter Here -->
										<table id="prtablea" class="table table-bordered table-striped" width="100%" >
											<thead>
												<tr>
													<th width="10%" style="text-align:right;">#</th>
													<th width="30%">Account Name</th>
													
													<th width="10%">Date</th>
													<th width="10%">Effect</th>
													<th width="10%" style="text-align:right;">Amount</th>
													<th width="10%">Action</th>
												</tr>
											</thead>
											
											<tbody>
										
									<?php
									
										$amount_dr ='0';
										$amount_cr ='0';
										
										$sql = "select * from tally_journal_entry where doc_no = '$pv_id' and doc_type = 'PV' and reversal_flag != 'R' order by effect desc, record_id ";	
//echo $sql;			
										$q2 	= mysqli_query($con, $sql);
										$i		= 1;
										while($r2 	= mysqli_fetch_array($q2)){
											$record_id		  	= $r2['record_id'];
											$effect		  		= $r2['effect'];
											$record_type  		= $r2['record_type'];
											$doc_no		  		= $r2['doc_no'];
											$doc_date			= date('d-m-Y', strtotime($r2['doc_date']));
											
											$supp_invoice_no  	= $r2['supp_invoice_no'];
											$supp_invoice_date  = $r2['supp_invoice_date'];
											$account_type  		= $r2['account_type'];
											$account_id  		= $r2['account_id'];
											$account_name  		= $r2['account_name'];
											$budget_head  		= $r2['budget_head'];
											$amount		   		= round($r2['amount'],2);
											$narration		   	= $r2['narration'];
											$cheque_no		   	= $r2['cheque_no'];
											$address		   	= $r2['address'];
											$gst_no		   		= $r2['gst_no'];
											$state		   		= $r2['state'];
											$status_tally 		= $r2['status'];
											
											$sql = "select * from sma_budget_subgroup where id = '$budget_head'";
											$r2 = mysqli_query($con, $sql);
											$r1 = mysqli_fetch_array($r2);
											$budget_head = $r1['budget_head'];
											
										if($account_type=='D'){
											$budget_head ='';
										}	

											$url_var = urlencode($_SERVER['REQUEST_URI']);
									?>
											
											<tr>
												<td style="text-align:right;"><?php echo $i++ ; ?> </td>
												<td><?php echo $account_name ?> </td>
												
												<td><?php echo $doc_date ?> </td>
												
												<td><?php echo $effect ?> </td>
												<td style="text-align:right;"><?php echo number_format($amount,2); ?> </td>
												<td>
											
												</td>
												
											</tr>
											
									<?php	
									
											if($effect=='Dr'){
												$amount_dr = $amount_dr + $amount;
											}
											else if($effect=='Cr'){
												$amount_cr = $amount_cr + $amount;
											}

										}
										$emsg   ='';
										$stl	='';

										$amount_cr = round($amount_cr,0);
										$amount_dr = round($amount_dr,0);
										$diff_amt = round($amount_dr - $amount_cr,0) ;
										
										echo '<input type="hidden" id="Mismatch_id" value="Y" >';
										if($diff_amt>0 || $diff_amt<0 ){
											$emsg = "Debit & Credit Total Mismatch...";	
											$stl  = "color:red;";
											
											echo '<input type="hidden" id="Mismatch_id" value="Y" >';
											
										}
										
										$amount_cr = round($amount_cr,0);
										$amount_dr = round($amount_dr,0);
									?>
										<input type="hidden" id="amount_DR" value="<?php echo $amount_dr_without_gst;?>" >
										
											<tr>
												<td></td>
												<td>Total Debit</td>
												<td></td>
												<td></td>
												<td style="text-align:right; <?php echo $stl; ?>" ><?php echo number_format($amount_dr,2); ?> </td>
												<td></td>
											</tr>
											<tr>
												<td></td>
												<td>Total Credit</td>
												<td></td>
												<td></td>
												<td style="text-align:right; <?php echo $stl; ?>"><?php echo number_format($amount_cr,2); ?> </td>
												<td></td>
											</tr>
											
								<?php	if($diff_amt>0 && $diff_amt<0 ){ ?>	
											<tr>
												<td></td>
												<td>Difference</td>
												<td></td>
												<td style="text-align:right; <?php echo $stl; ?>"><?php echo number_format($diff_amt,2); ?> </td>
												<td></td>
											</tr>
								<?php 	}	?>

											<tr>
												<td></td>
												<td style="color:red;text-align:center;" colspan='4'><?php echo $emsg; ?></td>
												
											</tr>
											
										</tbody>
									</table>

								<div class="form-group">
									<?php 
									$tally_status = $row['tally_status'];
									//echo $tally_status. ' ' . $status;
									?>
									<div class="col-sm-5">
										<label for="tally_narration" class="control-label">&nbsp;</label>
									</div>
										
										<div class="col-sm-2">
											<label for="tally_narration" class="control-label">Tally Narration: </label>
										</div>	
										<div class="col-sm-5">	
											
											<textarea rows="4" cols="65" onBlur="saveToDatabase(this.value,'narration','<?php echo $pv_id; ?>')" onClick="showEdit(this);" name="tally_narration" id="tally_narration"  ><?php echo $row['tally_narration'];?></textarea>
											
										</div>
									</div>
									
								</div>
						
							</div>
								
								
<!-- Reversal Start-->
							<div class="box-body">
									<h4 class="box-title">Reversal JV</h4>
									
									<div id="tallyentry">
									
									<!-- Enter Here -->
										<table id="prtablea" class="table table-bordered table-striped" width="100%" >
											<thead>
												<tr>
													<th width="10%" style="text-align:right;">#</th>
													<th width="30%">Account Name</th>
													<th width="10%">Date</th>
													<th width="10%">Effect</th>
													<th width="10%" style="text-align:right;">Amount</th>
													<th width="10%">Action</th>
												</tr>
											</thead>
											
											<tbody>
										
									<?php
									
										$amount_dr ='0';
										$amount_cr ='0';
										
										$sql = "select * from tally_journal_entry where doc_no = '$pv_id' and doc_type = 'PV' and reversal_flag = 'R' order by effect desc, record_id ";	
//echo $sql;			
										$q2 	= mysqli_query($con, $sql);
										$i		= 1;
										while($r2 	= mysqli_fetch_array($q2)){
											$record_id		  	= $r2['record_id'];
											$effect		  		= $r2['effect'];
											$record_type  		= $r2['record_type'];
											$doc_no		  		= $r2['doc_no'];
											$doc_date			= date('d-m-Y', strtotime($r2['doc_date']));
											
											$supp_invoice_no  	= $r2['supp_invoice_no'];
											$supp_invoice_date  = $r2['supp_invoice_date'];
											$account_type  		= $r2['account_type'];
											$account_id  		= $r2['account_id'];
											$account_name  		= $r2['account_name'];
											$budget_head  		= $r2['budget_head'];
											$amount		   		= round($r2['amount'],2);
											$narration		   	= $r2['narration'];
											$cheque_no		   	= $r2['cheque_no'];
											$address		   	= $r2['address'];
											$gst_no		   		= $r2['gst_no'];
											$state		   		= $r2['state'];
											$status_tally 		= $r2['status'];
											
											$sql = "select * from sma_budget_subgroup where id = '$budget_head'";
											$r2 = mysqli_query($con, $sql);
											$r1 = mysqli_fetch_array($r2);
											$budget_head = $r1['budget_head'];
											
										if($account_type=='D'){
											$budget_head ='';
										}	

											$url_var = urlencode($_SERVER['REQUEST_URI']);
									?>
											
											<tr>
												<td style="text-align:right;"><?php echo $i++ ; ?> </td>
												<td><?php echo $account_name ?> </td>
												<td><?php echo $doc_date ?> </td>
												
												<td><?php echo $effect ?> </td>
												<td style="text-align:right;"><?php echo number_format($amount,2); ?> </td>
												<td>
											
												</td>
												
											</tr>
											
									<?php	
									
											if($effect=='Dr'){
												$amount_dr = $amount_dr + $amount;
											}
											else if($effect=='Cr'){
												$amount_cr = $amount_cr + $amount;
											}

										}
										$emsg   ='';
										$stl	='';

										$amount_cr = round($amount_cr,0);
										$amount_dr = round($amount_dr,0);
										$diff_amt = round($amount_dr - $amount_cr,0) ;
										
										echo '<input type="hidden" id="Mismatch_id" value="Y" >';
										if($diff_amt>0 || $diff_amt<0 ){
											$emsg = "Debit & Credit Total Mismatch...";	
											$stl  = "color:red;";
											
											echo '<input type="hidden" id="Mismatch_id" value="Y" >';
											
										}
										
										$amount_cr = round($amount_cr,0);
										$amount_dr = round($amount_dr,0);
									?>
										<input type="hidden" id="amount_DR" value="<?php echo $amount_dr_without_gst;?>" >
										
											<tr>
												<td></td>
												<td>Total Debit</td>
												<td></td>
												<td></td>
												<td style="text-align:right; <?php echo $stl; ?>" ><?php echo number_format($amount_dr,2); ?> </td>
												<td></td>
											</tr>
											<tr>
												<td></td>
												<td>Total Credit</td>
												<td></td>
												<td></td>
												<td style="text-align:right; <?php echo $stl; ?>"><?php echo number_format($amount_cr,2); ?> </td>
												<td></td>
											</tr>
											
								<?php	if($diff_amt>0 && $diff_amt<0 ){ ?>	
											<tr>
												<td></td>
												<td>Difference</td>
												<td></td>
												<td style="text-align:right; <?php echo $stl; ?>"><?php echo number_format($diff_amt,2); ?> </td>
												<td></td>
											</tr>
								<?php 	}	?>

											<tr>
												<td></td>
												<td style="color:red;text-align:center;" colspan='4'><?php echo $emsg; ?></td>
												
											</tr>
											
										</tbody>
									</table>

								<div class="form-group">
									<?php 
									
								//	echo $tally_status. ' <<>> ' . $status;
									if ( $status == 'Completed' || $status == 'Submitted' ){ ?>
										<div class="col-sm-3">
											
										<?php 
										
											if($tally_status == 'R'){
												echo '<label for="tally_status" style="position: relative;top: -4px;" class="control-label">JV Created : </label>';
											}
											else if($tally_status == 'U'  ){
												echo '<label for="tally_status" style="position: relative;top: -4px;" class="control-label">JV Synched :</label>';
											}
											else if($tally_status == 'C' ){
												echo '<label for="tally_status" style="position: relative;top: -4px;" class="control-label">JV Checked :</label>';
											}
										
										if($tally_status == 'R' && $status == 'Completed' ){ 
										?>
										    <label for="tally_status" style="position: relative;top: -4px;" class="control-label">Sync to Tally? &nbsp;&nbsp;: </label>
										<?php
										}
										
//echo $tally_status.' && '. $accountant_role .' && '.$emsg ;
				
										if($tally_status=='R' && ( $accountant_role=='M' ||  $accountant_role=='Y' ) && empty($emsg) && $status == 'Completed' ){
										?>
											<input type="checkbox" class="form-control123" <?php echo ($tally_status == 'C' || $tally_status == 'U' )?'CHECKED="CHECKED"':'';?> name="tally_status" id="tally_status" value="C" >
											
									<?php } 
											
										if( ($tally_status=='C' || $tally_status=='R' ) && $tally_access=='Y' ){ 
									?>
										    <br>
											<label for="tally_status" style="position: relative;top: -4px;" class="control-label">Do not sync to tally? &nbsp;&nbsp;: </label>
										
											<input type="checkbox" class="form-control123" <?php echo ($tally_status == 'N' )?'CHECKED="CHECKED"':'';?> name="tally_status" id="tally_status" value="N" >
											
									<?php 
										}
									?>
										
										</div>
								<?php } ?>
									
									<?php 
										$tally_created_date_chk = date('d-m-Y', strtotime($row['tally_created_date']));
										if($tally_created_date_chk=='01-01-1970' || $tally_created_date_chk== '30-11--0001' || $tally_created_date_chk== '31-12-1969'){
											$tally_created_date ='';
										}
										else {
											$tally_created_date = date('d-m-Y h:m i', strtotime($row['tally_created_date']));
										}
										
										$chk_date = date('d-m-Y', strtotime($tally_updated_on));
										if($chk_date=='01-01-1970' || $chk_date== '30-11--0001' || $chk_date== '31-12-1969'){
											$tally_updated_on ='';
										}
										else {
											$tally_updated_on = date('d-m-Y h:m i', strtotime($tally_updated_on));
										}	
									?>
										<div class="col-sm-2">
									<?php if(!empty($tally_updated_on)){ ?>
											<label for="tally_status" class="control-label">Tally updated on :<BR><?php echo $tally_updated_on; ?>
											<?php echo ' ' . $tally_ticked_by; ?>
											</label>
									<?php 	}
										else if(!empty($tally_created_date)){
									?>
											<label for="tally_status" class="control-label">Tally created on :<BR><?php echo $tally_created_date; ?>
											<?php echo ' ' . $tally_ticked_by; ?>
											</label>
									<?php 	} ?>
									
											
										</div>
										<div class="col-sm-2">
											<label for="tally_narration_reversal" class="control-label">Tally Narration: </label>
										</div>	
										<div class="col-sm-5">	
											
											<textarea rows="4" cols="65" name="tally_narration_reversal" id="tally_narration_reversal"  ><?php echo $row['tally_narration_reversal'];?></textarea>
											
										</div>
									</div>
									
								</div>
						
							</div>
<!-- Reversal End-->
							
						</div>
						
						</fieldset>
					</div>
				</div>
			</div>
		<?php } ?>
		
		<!--Tally Journal Start-->

							<div class="box-footer">
								<div class="col-sm-6">
									
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6 text-right">
									<span>&nbsp;&nbsp;</span>
									 <a href="#tab_3" class="btn btn-primary" data-toggle="tab" onclick="$('#third_tab').trigger('click');getvalidate();" >Next</a>
								</div>
							</div>	
							
		</div>	
					
						<!-- Attachments - Upload Panel -->
        <div class="tab-pane" id="tab_3">
                            <!-- Attachments company_idd -->
					<span style="margin: 0 auto;text-align:center" class="form-control-static pull-left" style="font-weight:bold;" ><b>PV Number : <?php echo $pv_id;?> 
					<?php echo ' Date: '. date('d-m-Y', strtotime($row['dated']));
						echo " Provisional JV Name : " . $provisional_account_name;

							?>
						</b>	
					</span>		
							 <?php
                              $sql = "SELECT * FROM file_uploads WHERE module = 'PV' AND reference_id = " . $pv_id;
							  
                              $docResults = mysqli_query($con, $sql);
	                          ?>
                                  <table id="prDocsTable" class="table table-bordered table-striped">
                                      <thead>
                                      <tr>
                                          <th width="20%" >Document Type</th>
                                          <th  width="20%" >Description</th>
										  <th  width="20%">Share Point Link
										  <a href="https://athaang.sharepoint.com/sites/AthaangDMS " class="btn btn-primary" target="_blank" >Click</a>
										  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										  <a href="https://athaang.in/img/Help_Link_Copy_DMS.pdf" class="btn btn-success" target="_blank" >Upload Help</a>
										  </th>
										  <th  width="30%" >File</th>
                                          <th  width="10%">Action</th>
                                          
                                      </tr>
                                      </thead>
                                      <tbody id="prDocsTableBody">
				                              <?php
				                              echo mysqli_error($con);
				                              while($docRow = mysqli_fetch_array($docResults)) {
				                                  $delDocUrl = "deldoc.php?id=" . $docRow['id'] . "&url=" . urlencode($_SERVER['REQUEST_URI']);
													
													$doc_desc = $docRow['doc_desc'];
													$doc_type = $docRow['doc_type'];
													$share_point_link = $docRow['share_point_link'];
													$sql="SELECT * FROM sma_document_type where id ='$doc_type' ";
													$rs = mysqli_query($con, $sql);
													$rw = mysqli_fetch_array($rs);
													$document = $rw['document'];
													
													$dms_path = '';
													$dms_module = $docRow['dms_module'];
													if($dms_module=='IN'){
														$dms_path = $baseurl.'dms/';
														$doc_desc = 'Inward No:'.$docRow['doc_invoice_no'];
													}
													
					                              ?>
                                          <tr>
                                             <td width="20%" ><?php echo $document ?></td>
                                              <td width="20%"><?php echo $doc_desc ?></td>
											  <td width="20%"><a target="_blank" href="<?php echo $share_point_link ?>"><?= $share_point_link; ?></a></td>
											  <td width="30%"><a target="_blank" href="<?php echo $dms_path . $docRow['file_path'] . '/' . $docRow['file_name']; ?>"><?php echo $docRow['file_name'] ?></a></td>
										<?php if (empty($readonly)){ ?>
                                              <td><a href="<?php echo $delDocUrl; ?>"><i class='fa fa-trash-alt'></i></a></td>
										<?php } ?>
                                          </tr>
				                              <?php
				                              }
				                              ?>
                                      </tbody>
                                      
                                  </table>
						<?php //if (empty($readonly)){ ?>
						
						
						<span id="gegpartyDoc">
							
						</span>
						
						
							<div class="table-responsive">  
                               <table class="table table-bordered" id="dynamic_field">  
                                    <tr> 
										    
										<td width="20%" >
                                            <select class="form-control select2 doctype" name="doctype[]"   >
                                                <option value="">Select</option>
											<?php
											$sql="SELECT * FROM sma_document_type where 1 ORDER BY document ASC";
											$rs = mysqli_query($con, $sql);
											echo mysqli_error($con);
											while($rw = mysqli_fetch_array($rs)){
											?>
                                                <option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['document'] ?></option>
											<?php } ?>	
                                            
                                            </select>
										</td>
										<td width="20%" >
											 <textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea>
										</td>
										<td width="20%" >
											 <textarea class="form-control docdesc" name="share_point_link[]" rows="2" placeholder="Enter share point link..."></textarea>
										</td>
										
										<td width="30%">
											<input type="file" name="fudoc[]" class="docfile">
										</td>
										
                                         <td width="10%" >
										<?php 
											if($status != 'Completed'){
										?>
											<button type="button" name="add" id="add" class="btn btn-success">Add More</button>
										<?php } ?>
										 </td>  
                                    </tr>  
                               </table>  
                            <!--   <input type="button" name="submit" id="submit" class="btn btn-info" value="Submit" />  -->
							</div>
						<?php
				          //  }
				        ?>	
							<div class="box-footer">
								<div class="col-sm-6">
									 <a href="#tab_1" class="btn btn-primary" data-toggle="tab" onclick="$('#first_tab').trigger('click')" >Previous </a>
									
									<span>&nbsp;&nbsp;</span>
								</div>
								
								<div class="col-sm-6 text-right">
									<span>&nbsp;&nbsp;</span>
								</div>
							</div>
							
					<div class="box-footer">
					
					<span id="predit"></span>
			
					<?php if($del != 'Y'){ ?>
							
						
							
						
							<div class="col-sm-6">
							
								<?php $baseurl1 = $baseurl.$modulePath.'edit.php?sub=delete&pv_id='.$pv_id ; ?>
							    
								
								<?php 
									
									$approval_status = $row['approval_status'];
									
									if($del=='Y' || ($user=='Admin' &&  $status!='Draft' ) || ( $approval_status=='Rejected' ) ) { ?>
										<a href="#makeDraftAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#makeDraftAuthority">Convert to Draft</a>	
													
								<?php
									}
									
//$status != 'Completed' && 							
								if ($status == 'Draft' ){
									
									$sql = "SELECT sma_invoice_no FROM `sma_ipc` where sma_invoice_no = '$pv_id' and del !='Y' ";
								//echo $sql;
									$res = mysqli_query($con,$sql);
									echo mysqli_error($con);
									$rowcount=mysqli_num_rows($res);
									if ( $rowcount=='0' ){
									
							?>
								<!--<a href="<?php echo $baseurl1; ?>"  class="btn btn-danger btn-inverse">Delete</a>-->
								<a href="#deleteAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#deleteAuthority">Delete</a>
								
							<?php	}
								} 
							?>
								<span>&nbsp;&nbsp;</span>
								
						</div>
							
							<div class="col-sm-6 text-right">
							
						<?php 
								$_SESSION['pv_id'] 	= $pv_id;
								$_SESSION['status']  = $status;
								$_SESSION['our_po_ref_no']  = $our_po_ref_no;
								
								$approver_flag='';
								if( $status != 'Draft' ){
									
								$approver_flag='';
								if( $userid == $approver_1 && $approver_1_status=='Submitted' || 
										$userid == $approver_2 && $approver_2_status=='Submitted' || 
										$userid == $approver_3 && $approver_3_status=='Submitted' ||
										$userid == $approver_4 && $approver_4_status=='Submitted' ||
										$userid == $approver_5 && $approver_5_status=='Submitted' ||
										$userid == $approver_6 && $approver_6_status=='Submitted' ||
										$userid == $approver_7 && $approver_7_status=='Submitted' ||
										$userid == $approver_8 && $approver_8_status=='Submitted' ){
					
									if($approver_1_status=='Submitted' 
										&& empty($approver_2_status) && empty($approver_3_status) 
										&& empty($approver_4_status) && empty($approver_5_status)
										&& empty($approver_6_status) && empty($approver_7_status)
										&& empty($approver_8_status) ){
										$approver_flag='Y';
									}

									if($approver_1_status=='Approved' && $approver_2_status=='Submitted'
										&& empty($approver_3_status) && empty($approver_4_status) 
										&& empty($approver_5_status) && empty($approver_6_status) 
										&& empty($approver_7_status) && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Submitted' && empty($approver_4_status)
										&& empty($approver_5_status) && empty($approver_6_status) 
										&& empty($approver_7_status) && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										&& $approver_4_status=='Submitted'
										&& empty($approver_5_status) && empty($approver_6_status) 
										&& empty($approver_7_status) && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										&& $approver_4_status=='Approved'
										&& $approver_5_status=='Submitted'
										&& empty($approver_6_status) 
										&& empty($approver_7_status) && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										&& $approver_4_status=='Approved'
										&& $approver_5_status=='Approved'
										&& $approver_6_status=='Submitted'
										&& empty($approver_7_status) && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										&& $approver_4_status=='Approved'
										&& $approver_5_status=='Approved'
										&& $approver_6_status=='Approved'
										&& $approver_7_status=='Submitted' && empty($approver_8_status) ){
										$approver_flag='Y';
									}
									if( $approver_1_status=='Approved' && $approver_2_status=='Approved' 	&& $approver_3_status=='Approved' 
										&& $approver_4_status=='Approved'
										&& $approver_5_status=='Approved'
										&& $approver_6_status=='Approved'
										&& $approver_7_status=='Approved'
										&& $approver_8_status=='Submitted'){
										$approver_flag='Y';
									}
									
									/* 
									$mode_status = 'Pending';
									if($userid==$approver_1 && empty($approver_2) && empty($approver_3) && empty($approver_4) ){
										$mode_status = 'Approve';
									}
									else if($userid==$approver_2 && empty($approver_3) && empty($approver_4) ){
										$mode_status = 'Approve';
									}
									else if($userid==$approver_3 && empty($approver_4)){
										$mode_status = 'Approve';
									}
									else if($userid==$approver_4 ){
										$mode_status = 'Approve';
									}
									 */
									 
								}

//ECHO $userid.  ' ' .$approver_2 . ' ' . $status. ' <2> '. $approver_1_status. ' <<> ' .$approver_2_status. ' <<> ' . $approver_3_status. ' << 22 >>' .$approver_flag."<BR>";
								
								echo "<span style='color:red;'>".$berrmsg ."</span><BR>";
							
								if($status!='Draft' && $status!='Completed' && $approver_flag=='Y'){
							?>
								<span class="hidden-div">
									<a href="#approvalAuthority" class="btn btn-info" data-toggle="modal" data-mode="Approve" data-target="#approvalAuthority">Approve </a>
								</span>
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
									
									<a href="#rejectAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Reject" data-target="#rejectAuthority">Reject </a>
							<?php }
							
								}
						?>			
					
						<?php	
						if( $trans_type==0 || empty($trans_type) ){
							echo "<span style='color:red;'>Wrokflow type should be select...</span>";
						}
						
						
						if( !empty($errror_budget_head) ){
							echo "<span style='color:red;'>$errror_budget_head...</span>";
						}
						
							$tally_status = $row['tally_status'];
							if($status=='Draft' && $approval_status!='Rejected' ){
								
								if( $trans_type>0 && empty($errror_budget_head) ){ 
								
						?>
								<span class='hidesend'>	
									<input type='button' class="btn btn-primary" onclick="getapprover()" value="Send " >
								</span>	
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
						<?php 	} 
							}
						?>
						<?php if( $approval_status!='Rejected' ){ ?>
							<span class='hidesend'>	
								<input type="submit" class="btn btn-primary" onclick="getvalidate();" value="Save" name="Save">
							</span>	
						<?php 	}  ?>	
								<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
							<?php 
								$baseurl2 = $baseurl . $modulePath. 'index.php?sub=list&same_page='. $page;
							?>		
								<span class="pull-right"><a href="<?php echo $baseurl2; ?>" class="btn btn-default" >Back</a>&nbsp;&nbsp;&nbsp;</span>
					
						<label class="col-lg-2 control-label" style="color:red;text-align:center;" colspan="4"><?php echo $emsg; ?></label>	
							<label style="color:red;text-align:center;" ><?php echo $emsg; ?></td>
							
						</div>
					<?php	
						}
					?>
								
						
					</div>
						
					<?php  
						if( $status == 'Submitted' ){
					?>
						<div class="box-footer">
							<?php	
							if(!empty($approver_1)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_1' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_1_name = $rw['username'];
								$approver_1_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label1">Approver 1</label><BR>
									<label class="control-label1"><?= $approver_1_name . " <BR> " . $approver_1_role;?>
									</label>
								</div>
					<?php	
							}
							
							if(!empty($approver_2)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_2' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_2_name = $rw['username'];
								$approver_2_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label1">Approver 2</label><BR>
									<label class="control-label1"><?= $approver_2_name . " <BR> " . $approver_2_role;?>
									</label>
								</div>
					<?php	
							}
							
							if(!empty($approver_3)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_3' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_3_name = $rw['username'];
								$approver_3_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label1">Approver 3</label><BR>
									<label class="control-label1"><?= $approver_3_name . " <BR> " . $approver_3_role;?>
									</label>
								</div>
					<?php	
							}
							if(!empty($approver_4)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_4' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_4_name = $rw['username'];
								$approver_4_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label1">Approver 4</label><BR>
									<label class="control-label1"><?= $approver_4_name . " <BR> " . $approver_4_role; ?>
									</label>
								</div>
					<?php	
							}
							
						if(!empty($approver_5)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_5' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_5_name = $rw['username'];
								$approver_5_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label1">Approver 5</label><BR>
									<label class="control-label1"><?= $approver_5_name . " <BR> " . $approver_5_role; ?>
									</label>
								</div>
					<?php	
							}
							if(!empty($approver_6)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_6' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_6_name = $rw['username'];
								$approver_6_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label1">Approver 6</label><BR>
									<label class="control-label1"><?= $approver_6_name . " <BR> " . $approver_6_role; ?>
									</label>
								</div>
					<?php	
							}
							if(!empty($approver_7)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_7' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_7_name = $rw['username'];
								$approver_7_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label1">Approver 7</label><BR>
									<label class="control-label1"><?= $approver_7_name . " <BR> " . $approver_7_role; ?>
									</label>
								</div>
					<?php	
							}
							if(!empty($approver_8)){
								$sql = " SELECT a.username, b.role FROM sma_user a, sma_role b 
									WHERE a.primary_role = b.id AND a.id = '$approver_8' ";
								$rs = mysqli_query($con, $sql);
								$rw = mysqli_fetch_array($rs);
								$approver_8_name = $rw['username'];
								$approver_8_role = $rw['role'];
							?>	
								<div class="col-sm-3">
									<label class="control-label1">Approver 8</label><BR>
									<label class="control-label1"><?= $approver_8_name . " <BR> " . $approver_8_role; ?>
									</label>
								</div>
					<?php	
							}
					?>		
						
						</div>
						
					<?php
						}
					?>
					
					<span id="getapprover">

						<?php 						
						if( $status == 'Draft' ){
						?>
						
								<div class="box-footer">
								<div class="col-sm-2">
									<label class="control-label">&nbsp;</label>
								</div>
							<?php	
								/*$approver_1 = $row['approver_1'];
								$approver_2 = $row['approver_2'];
								$approver_3 = $row['approver_3']; */
						
								if( (!empty($approver_1) && $status =='Submitted') ){
									$sql 	= " select b.* from sma_user a, sma_role b where b.id in (a.role) and a.id = $approver_1 ";
									$rs 	= mysqli_query($con, $sql);
									echo mysqli_error($con);
									while( $rw = mysqli_fetch_array($rs) ){
										$rolenm .= $rw['role'];
									}	
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 1</label><br>
									<?php echo 'Role :'. $rolenm;?>
									
									<select class="form-control  approver_1" name="approver_1"   >
                                        <?php
										$sql 	= " select * from sma_user where id = $approver_1 ";
										$rs 	= mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_1 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								
								</div>
							<?php }
							
								if(!empty($approver_2)){
									$sql 	= " select b.* from sma_user a, sma_role b where b.id in (a.role) and a.id = $approver_2 ";
									$rs 	= mysqli_query($con, $sql);
									echo mysqli_error($con);
									while( $rw = mysqli_fetch_array($rs) ){
										$rolenm .= $rw['role'];
									}	
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 2</label><br>
									<?php echo 'Role :'. $rolenm;?>
									<select class="form-control  approver_2" name="approver_2" >
                                        <?php
										$sql = " select * from sma_user where id = $approver_2 ";
										$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_2 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php }
							
								if(!empty($approver_3)){
										$sql 	= " select b.* from sma_user a, sma_role b where b.id in (a.role) and a.id = $approver_3 ";
									$rs 	= mysqli_query($con, $sql);
									echo mysqli_error($con);
									while( $rw = mysqli_fetch_array($rs) ){
										$rolenm .= $rw['role'];
									}
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 3</label><br>
									<?php echo 'Role :'. $rolenm;?>
									<select class="form-control  approver_3" name="approver_3" >
                                        <?php
										$sql = " select * from sma_user where id = $approver_3 ";
										$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_3 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } 	
								if(!empty($approver_4)){
									$sql 	= " select b.* from sma_user a, sma_role b where b.id in (a.role) and a.id = $approver_4 ";
									$rs 	= mysqli_query($con, $sql);
									echo mysqli_error($con);
									while( $rw = mysqli_fetch_array($rs) ){
										$rolenm .= $rw['role'];
									}	
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 4</label><br>
									<?php echo 'Role :'. $rolenm;?>
									<select class="form-control  approver_4" name="approver_4" >
                                        <?php
										$sql = " select * from sma_user where id = $approver_4 ";
										$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_4 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } ?>
							<?php if(!empty($approver_5)){ ?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 5</label><br>
									<?php echo 'Role :'. $rolenm;?>
									<select class="form-control  approver_5" name="approver_5" >
                                        <option value="">Select</option>
										<?php
										$sql = " select * from sma_user 
											where  FIND_IN_SET( $role, role ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_5 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } ?>
							<?php if(!empty($approver_6)){ ?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 6</label><br>
									<?php echo 'Role :'. $rolenm;?>
									<select class="form-control  approver_6" name="approver_6" >
                                        <option value="">Select</option>
										<?php
										$sql = " SELECT * FROM sma_user 
											WHERE  FIND_IN_SET( $role, role ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_6 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } ?>
							<?php if(!empty($approver_7)){ ?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 7</label><br>
									<?php echo 'Role :'. $rolenm;?>
									<select class="form-control  approver_7" name="approver_7" >
                                        <option value="">Select</option>
										<?php
										$sql = " SELECT * FROM sma_user 
											WHERE  FIND_IN_SET( $role, role ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_7 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } ?>
							<?php if(!empty($approver_8)){ ?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 8</label><br>
									<?php echo 'Role :'. $rolenm;?>
									<select class="form-control  approver_8" name="approver_8" >
                                        <option value="">Select</option>
										<?php
										$sql = " SELECT * FROM sma_user 
											WHERE  FIND_IN_SET( $role, role ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_8 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								</div>
							<?php } ?>
							
								<BR>
								
							</div>
						
						<?php } ?>		
						
						</span>
				

		</div>	
<?php
$opt = '';	
$sql="SELECT * FROM sma_document_type where 1 ORDER BY document ASC";
$rs = mysqli_query($con, $sql);
echo mysqli_error($con);
while($rw = mysqli_fetch_array($rs)){
	$did 		= $rw['id'];
	$ddocument 	= $rw['document'];
	$opt .= "<option value='" . $did ."' > ".$ddocument."</option>";
 }

?>	
 <input type="hidden"  value="<?php echo $opt ?>" name="opt" id="opt">
						  
		<div class="tab-pane" id="tab_4">
							
							<div class="modal-header" >
								
								<?php 
									
									
									$srno = $pv_id;
									$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'PV' order by id desc ";
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
								<span style="margin: 0 auto;text-align:center" class="form-control-static pull-left" >Document Number : <?php echo $srno;?> <?php echo "&nbsp;&nbsp; Created By: ".$create_by; ?> <?php echo "&nbsp;&nbsp; Dated: ".$create_date; ?>
							
							<!--<p><?php echo ' Date: '. date('d-m-Y', strtotime($row['dated']));
								echo " Provisional JV Name : " . $provisional_account_name;

							?></p>-->
							
						</b>	
					</span>
								</span>
												
												
								 
								 
							</div>
							<div class="modal-body1" >
								<section class="content2">
								<div class="row1">
								   
										<table id="prtable" class="table table-bordered table-striped">
											<thead>
											<tr>
											  <th></th>	
											  
											  <th>Dated</th>
											  <th>By User</th>
											  <th>Decision</th>
											  <th>Send Dated</th>
											  <th>To User</th>
											  <th>Role</th>
											  <th>remarks</th>
											  
											</tr>
											</thead>
											<tbody>
										<?php
										//style="position:relative;overflow-y:auto;min-width: 600px; max-height: 500px;padding: 15px;"
											//$srno = $rid;
											$s1  = "SELECT * from workflow_history where doc_id = '$srno' and doc_type = 'PV' order by id desc ";
									//	echo $s1;
											$res  = mysqli_query($con, $s1);
											echo mysqli_error($con);
											while($r1 = mysqli_fetch_array($res)){
												$id 				= $r1['id'];
												
												$status				= $r1['status'];
												$make_by_flag		= $r1['make_by_flag'];
												$reviewed_by 		= $r1['reviewed_by'];
												$approved 			= $r1['approved'];
												$approved_date		= date('d-m-Y h:i:sa', strtotime($r1['approved_date']));
												$remarks 			= $r1['remarks'];
												
												
												$s2="SELECT * FROM sma_user where id = '$reviewed_by' ";
										//echo $s2. "<BR>";
												$r3 = mysqli_query($con, $s2);
												$rw1 = mysqli_fetch_array($r3);
												$reviewed_by = $rw1['username'];
										//echo $reviewed_by . " <<<<<BR>";
												
												$role = $rw1['primary_role'];

												$sl="SELECT * FROM sma_role where id = '$role' ";
												$r3 = mysqli_query($con, $sl);
												$rw = mysqli_fetch_array($r3);
												$role = $rw['role'];
												
												$create_by		= $r1['create_by'];
												$create_date	= date('d-m-Y h:i:sa', strtotime($r1['create_date']));
												
												$sl="SELECT * FROM sma_user where id = '$create_by' ";
												$r3 = mysqli_query($con, $sl);
												$rw = mysqli_fetch_array($r3);
												$create_by = $rw['username'];
												
												if($make_by_flag=='V'){
													$create_by		= $r1['create_by'];	
													$sl="SELECT * FROM sma_party_mst where id = '$create_by' ";
													$r3 = mysqli_query($con, $sl);
													$rw = mysqli_fetch_array($r3);
													$create_by = $rw['party_name'];
												}	
												
										?>      
											<tr>
												<td width="1%"><input type="hidden" value="<?php echo $id; ?>" ></td>
												<td width="10%" style="text-align:left;"><?php echo $create_date; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $create_by; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $status; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $approved_date; ?></td>
												<td width="10%" style="text-align:left;"><?php echo $reviewed_by;?></td>
												<td width="10%" style="text-align:left;"><?php echo $role;?></td>
												<td width="10%" style="text-align:left;"><?php echo $remarks;?></td>
											</tr>
										<?php	}	?>	
											</tbody>
										</table>
										
							
										
									</div>
								</section>
							  </div>
						
		</div>

				
<!--Comment Section Start-->
	<?php //if($status!='Draft'){ 
	?>
		<div class="tab-pane <?php echo $active8;?>" id="tab_8" >
							
			<div class="modal-header" >
				<div class="modal-body" >
				<section class="content">
				<div class="row">			
					<p><?php echo ' Date: '. date('d-m-Y', strtotime($row['dated']));
								echo " Provisional JV Name : " . $provisional_account_name;

							?></p>
				<?php 
												
				$s1  = " SELECT * from sma_comment where doc_id = '$pv_id' and doc_type = 'PV' order by id desc ";
				//echo $s1;
				$res  = mysqli_query($con, $s1);
				echo mysqli_error($con);
				$r1 = mysqli_fetch_array($res);
				$comment_datetime		= $r1['comment_datetime'];
				$comment_type			= $r1['comment_type'];
				$comment				= $r1['comment'];
				$parent_comment_id		= $r1['parent_comment_id'];
				$comment_by				= $r1['comment_by'];
				$comment_datetime	    = date('d-m-Y', strtotime($r1['comment_datetime']));
				if($comment_datetime=='01-01-1970'){
					$comment_datetime='';
				}
				$sl="SELECT * FROM sma_user where id = '$comment_by' ";
				$r3 = mysqli_query($con, $sl);
				$rw = mysqli_fetch_array($r3);
				$create_by = $rw['username'];
					
				if(!empty($create_by)){	
					$tmp_var = "&nbsp;&nbsp; Created By: ".$create_by. "&nbsp;&nbsp; Dated: ".$comment_datetime; 
				}
				?>			
				<span style="margin: 0 auto;text-align:center" class="form-control-static pull-left" >Document Number : <?php echo $pv_id;?> 
								 
				</span>
				
					<!-- /.box-header -->
                    <!-- form start -->
                    <div class="form-group123">
							
						<div class="col-md-12">
							<label class="control-label">Comments </label><br>
							<textarea rows='02' cols="150" id="comment_A" name="comment" ></textarea> <br>
							<button type="button" class="btn btn-primary" onclick="getcomment(this.value,<?= $pv_id;?>,'PV','C',<?= $page;?>)" >Submit</button>		
						</div>
					</div>

			<span id="getcomment">	
			<?php
				$res  = mysqli_query($con, $s1);
				while($r1 = mysqli_fetch_array($res)){
					$comment_datetime		= $r1['comment_datetime'];
					$comment_type			= $r1['comment_type'];
					$comment				= $r1['comment'];
					$parent_comment_id		= $r1['parent_comment_id'];
					$comment_by				= $r1['comment_by'];
					$comment_datetime	    = date('d-m-Y h:i:s a', strtotime($r1['comment_datetime']));
					if($comment_datetime=='01-01-1970'){
						$comment_datetime='';
					}
					$sl="SELECT * FROM sma_user where id = '$comment_by' ";
					$r3 = mysqli_query($con, $sl);
					$rw = mysqli_fetch_array($r3);
					$create_by = $rw['username'];
			?>
					<div class="col-md-12">
					
						<label class="control-label">On <?php echo $comment_datetime ?> <?php echo $create_by ;?> : wrote</label><br>
						<?= $comment; ?>
					<!--	<textarea style="background-color:#F5F5F5;" readonly rows='02' cols="150" ><?= $comment; ?></textarea> -->
					</div>
			<?php	
				}
			?>	
			</span>
			
			</div>
					</section>
					
				</div>
				
			 </div>
						
		</div>

	<?php //} ?>
	
<!--Comment Section End-->				
								
                        <!--<div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<?php $baseurl1 = $baseurl.$modulePath;?>
								<a href="<?php echo $baseurl1;?>" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
                        </div>-->
						
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


<!--Add Line Popup-->

<div class="modal fade" id="addLine" role="dialog" aria-labelledby="addLine" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="addLine">Add </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$pv_id 	= $_SESSION['pv_id'];
											$status = $_SESSION['status'];
										?>
										
										<input type="hidden" name="pv_id" id="pv_idA" value="<?php echo $pv_id; ?>" >
										
										
										
										<div class="form-group">
											<div class="col-sm-12">
												<label for="approver" class="control-label">Type of A/c *</label><br>
												<input type="radio"  id="type_acA" name="type_ac" value='V' onchange="getaccount(this.value)" > Supplier
												
												<input type="radio"  id="type_acA" name="type_ac" value='A' checked onchange="getaccount(this.value)" > Account	
                                            	
												
                                            </div>
                                        </div>

									
										<div class="form-group">
											<span id ='getaccount' >
												<div class="col-sm-12">
													<label for="approver" class=" control-label">Account Name *</label>                                        
													<select class="form-control select2" id="account_idA" name="account_id" required="required" onchange="gettdsamt(this.value)">
														<option value="">Select</option>
													<?php
														$sql = "SELECT * FROM account_mst where 1 and account_type = 'D' or account_type = 'A' order by account_name ";
														$result = mysqli_query($con, $sql);
														echo mysqli_error($con);
														while($r3 = mysqli_fetch_array($result)){
													?>	
														<option value="<?php echo $r3['id']?>" ><?php echo $r3['account_name'].'-'.$r3['percentage'].'%'; ?></option>
													<?php } ?>
													</select>
												</div>	
											</span>
										</div>
										
										<div class="form-group">
											<div class="col-sm-6">
												<label for="approver" class="control-label">Effect *</label><br>
                                            	<input type="radio"  id="effectA" name="effect" value='Dr' > Debit
												<input type="radio"  id="effectA" name="effect" checked value='Cr' > Credit
											</div>
                                        
											<div class="col-sm-6">
												
												<label for="approver" class="control-label">Amount *</label>
												<span class="gettdsamt">
                                            	<input type="text" class="form-control amountA"  autocomplete="off" style="text-align:right;;" name="amount" id="amountA" >
												</span>
												
												<label for="approver" class="control-label">If Any changes in amount enter here</label>
												<input type="text" class="form-control " id="amountABC" autocomplete="off" style="text-align:right;;" name="amounta" value="" >
												
                                            </div>
                                        </div>
										
										
										<div class="form-group">
											<div class="col-sm-12">
												<label for="approver" class="control-label">Narration</label>
                                            	<textarea class="form-control" rows="2" name="narration" id="narrationA"></textarea>
											</div>
										</div>

								</form>

								</div>

							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitAccount">Submit</button>
							</div>
			
                            </div>
                        </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Add Line Popup End -->



<!--Add PV Line Popup-->

<div class="modal fade" id="addPVLine" role="dialog" aria-labelledby="addPVLine" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="addPVLine">Add </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$pv_id 	= $_SESSION['pv_id'];
											$status = $_SESSION['status'];
										?>
										
										<input type="hidden" name="pv_id" id="pv_idPVA" value="<?php echo $pv_id; ?>" >
										
										<div class="form-group">
											<div class="col-sm-12">
												<label for="approver" class="control-label">Type of A/c *</label><br>
												<input type="radio"  id="type_acPVA" name="type_ac" value='A' checked onchange="getaccount(this.value)" > Account
                                            </div>
                                        </div>

									
										<div class="form-group">
											<span id ='getaccount' >
												<div class="col-sm-12">
													<label for="approver" class=" control-label">Account Name *</label>                                        
													<select class="form-control select2" id="account_idPVA" name="account_id" required="required" onchange="gettdsamt(this.value)">
														<option value="">Select</option>
													<?php
													//	$sql = "SELECT * FROM sma_budget where 1 and project = '$company_id' order by budget_code ";
														$sql = "SELECT a.* from sma_product a, sma_product_cost_center b where a.id = b.product_id and b.budget_id >0 and b.company_id = '$company_id' order by name";
														$result = mysqli_query($con, $sql);
														echo mysqli_error($con);
														while($r3 = mysqli_fetch_array($result)){
													?>	
														<option value="<?php echo $r3['id']?>" ><?php echo $r3['name']; ?></option>
													<?php } ?>
													</select>
												</div>	
											</span>
										</div>
										
										<div class="form-group">
											<div class="col-sm-6">
												<label for="approver" class="control-label">Effect *</label><br>
                                            	<input type="radio"  id="effectPVA" checked name="effect" value='Dr' > Debit
												<!--<input type="radio"  id="effectPVA" name="effect"  value='Cr' > Credit-->
											</div>
                                        
											<div class="col-sm-6">
												
												<label for="approver" class="control-label">Amount *</label>
												<input type="text" class="form-control amountPVA"  autocomplete="off" style="text-align:right;;" name="amount" id="amountPVA" >
											</div>
                                        </div>
										
										<div class="form-group">
											<div class="col-sm-12">
												<label for="approver" class="control-label">Narration</label>
                                            	<textarea class="form-control" rows="2" name="narration" id="narrationPVA"></textarea>
											</div>
										</div>

								</form>

								</div>

							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitPVAccount">Submit</button>
							</div>
			
                            </div>
                        </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Add PV Line Popup End -->


<!--Make to DraftPopup-->

<div class="modal fade" id="makeDraftAuthority" role="dialog" aria-labelledby="makeDraftAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="makeDraftAuthority">Do you want to Make Draft? </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">                                        
										<?php
										
											$pv_id 	= $_SESSION['pv_id'];
											$status = $_SESSION['status'];
											$role 	= $_SESSION['role'];
											$company= $_SESSION['company'];
											
										?>
										<input type="hidden" name="pv_id" id="pv_idD" value="<?php echo $pv_id; ?>" >
										<input type="hidden" id="modeD" name="mode" value='Accept'>
										<input type="hidden" id="approverD" name="approver" value='<?php echo $approver;?>'>
										
										<div class="form-group">
											<label for="status" class="col-sm-2 control-label">Status</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" id="statusD" readonly name="status" value="<?php echo $status ?>" >
											</div>
										</div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksD"></textarea>
											</div>
										</div>
										
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitDraft">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Make to DraftPopup-->


<!--Delete  Popup-->

<div class="modal fade" id="deleteAuthority" role="dialog" aria-labelledby="deleteAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="deleteAuthority">Do you want to Delete? </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">                                        
										<?php
										
											$pv_id 	= $_SESSION['pv_id'];
											$status = $_SESSION['status'];
											$role 	= $_SESSION['role'];
											$company= $_SESSION['company'];
											
										?>
										<input type="hidden" name="pv_id" id="pv_idZ" value="<?php echo $pv_id; ?>" >
										<input type="hidden" id="modeZ" name="mode" value='Accept'>
										<input type="hidden" id="approverZ" name="approver" value='<?php echo $approver;?>'>
										
										<div class="form-group">
											<label for="status" class="col-sm-2 control-label">Status</label>
											<div class="col-sm-4">
												<input type="text" class="form-control" id="statusZ" readonly name="status" value="<?php echo $status ?>" >
											</div>
										</div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksZ"></textarea>
											</div>
										</div>
										
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitDelete">Submit</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Delete Popup End -->


<!--Approval Workflow Popup-->

<div class="modal fade" id="approvalAuthority" role="dialog" aria-labelledby="approvalAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="approvalAuthority">Send To...</h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   

											$pv_id 	= $_SESSION['pv_id'];
											$status = $_SESSION['status'];
											$our_po_ref_no = $_SESSION['our_po_ref_no'];
											$role = $_SESSION['role'];
							
										?>
										
										<input type="hidden" name="pv_id" id="pv_idE" value="<?php echo $pv_id; ?>" >
										
										<input type="hidden" id="our_po_ref_NO" name="our_po_ref_no" value="<?php echo $our_po_ref_no?>">
										
										<input type="hidden" id="approverC" name="approver" value='<?= $userid ?>' >
										<input type="hidden" id="modeE" name="mode" value='Accept'>
										
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
                <h4 class="modal-title" id="rejectAuthority">Send To... </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$pv_id 	= $_SESSION['pv_id'];
											$status = $_SESSION['status'];
											
										/*	$sql="SELECT * FROM sma_provisional_jv_hdr where id = '$pv_id' ";
											$rr = mysqli_query($con, $sql);
											echo mysqli_error($con);
											$rw = mysqli_fetch_array($rr);
											$department_id = $rw['department_id'];
										*/		
										
											$sql   = "SELECT * FROM `sma_user` where userid = '$draft_by' ";
											$query = mysqli_query($con, $sql);
											$r3   = mysqli_fetch_array($query);
											$approver = $r3['id'];

										?>
										
										<input type="hidden" name="pv_id" id="pv_idR" value="<?php echo $pv_id; ?>" >
										<input type="hidden" id="modeR" name="mode" value='Reject'>
										<input type="hidden" id="approverR" name="approver" value='<?php echo $approver;?>'>
																				
										<div class="form-group col-md-12">
											<label for="approver" class="col-sm-4 control-label">Status</label>
                                            <div class="col-sm-7">
												<input type="text" class="form-control" id="statusR" style="color:red;" readonly name="status" value="<?php echo $status ?>" >
<!--                                               	<select class="form-control select2123" id="statusE" name="status">
													    <option value="">Select</option>
														<option value="Draft">Draft</option>
														<option value="Submitted">Submitted</option>
														<option value="Reviewed">Reviewed</option>
													</select>
												-->
                                            </div>
                                        </div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksR"></textarea>
											</div>
										</div>
									
                                    
									</form>	
								
								</div>

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
	  
	  
	  
	  
<!-- Modal Add Item-->
<div class="modal fade" id="modalAddItem" role="dialog" aria-labelledby="modalAddItemLabel" data-keyboard="false" data-backdrop="static" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true" onclick="clearfld()" >&times;</span>
                </button>
                <h4 class="modal-title" id="modalAddItemLabel">Add Product </h4>
            </div>
            <div class="modal-body">
                <section class="content">
                    <div class="row">
                        <form class="form-horizontal" action="edit.php?id=<?php $_GET['id'];?>" method="POST" enctype="multipart/form-data">
                            <input type="hidden" id="mode" value='Add'>
                            <input type="hidden" id="tempId">
							<input type="hidden" id="pv_id" value="<?php echo $_GET['id'];?>">
							<?php
								$id = $_GET['id'];
								$sql="SELECT * FROM sma_provisional_jv_hdr where id = '$id' ";
                            //echo $sql;
								$rs1 = mysqli_query($con, $sql);
                                echo mysqli_error($con);
                                $rw1 = mysqli_fetch_array($rs1);
								$our_po_ref_no = $rw1['our_po_ref_no'];

							?>
							
						<?php 
							if (!empty($our_po_ref_no)){
								$sql = "SELECT * from sma_purchase_order where id = '$our_po_ref_no' ";
								$rs = mysqli_query($con, $sql);
                                $rw = mysqli_fetch_array($rs);
								$received_date = date('d-m-Y', strtotime($rw['dated']));	
						?>
					
						<div class="form-group">
                                <label for="itemCategory" class="control-label col-sm-2">Purchase Order</label>
								<div class="col-sm-10">	
                                    <select class="form-control" id="grn_No" name = "grn_no" onchange="getitemdetails(this.value);" >
										<option value="0">Select</option>
                                    <?php
										
										$sql = "SELECT a.purchase_id as pid, a.product_id as 'material_id', ( a.quantity - a.bal_si_qty ) as qty , c.name as product_name, c.name as pname, c.uom FROM `sma_po_items` a, sma_product c where a.product_id = c.id and purchase_id = '$our_po_ref_no' and ((quantity > bal_si_qty or 
										((quantity * unit_rate) + ((quantity * unit_rate) * gst / 100)) > bal_si_amount or 
										purchase_id = '$our_po_ref_no' ) )"; //a.purchase_id = 2136 || 
										
                                        $rs = mysqli_query($con, $sql);
                                        echo mysqli_error($con);
                                        while($rw = mysqli_fetch_array($rs)){
									?>
                                        <option value="<?php echo $rw['pid'].'-'.$rw['material_id'];?>" >
										<?php echo 'Product Name: '.$rw['product_name'].' Pending Qty:'. $rw['qty'] ; ?>
										</option>
                                     <?php } ?>
									 
                                    </select>
								</div>
							</div>
						<?php } 
								$readonlye = 'READONLY';
						?>
							
                        <?php if (empty($our_po_ref_no)){
								$readonlye = '';
						?>		
							<div class="form-group">
								<input type="hidden"id="grn_No" name = "grn_no" value=''>
								
								<label for="itemCategory" class="control-label col-sm-1"> Category</label>
								<div class="col-sm-3">
									<select class="form-control" id="categoryId" onchange="getmaterial1(this.value)">
										<option value="">Select</option>
                                    <?php
                                    	$sql="SELECT * FROM sma_product_group ORDER BY product_group ASC";
                                        $rs = mysqli_query($con, $sql);
                                        echo mysqli_error($con);
                                        while($rw = mysqli_fetch_array($rs)){
                                    ?>
                                        <option value="<?php echo $rw['id']?>" ><?php echo $rw['product_group'] ?></option>
                                        <?php } ?>
                                    </select>
								</div>
								
								<label for="itemName" class="control-label col-sm-2">Product Name</label>
                                <div class="col-sm-6">
									<span id="getmaterial1" ><span id="getgrnitem" >
										<select class="form-control" id="itemName">
											<option value="">Select</option>	
										<?php
											/* $sql="SELECT id, name FROM sma_product ORDER BY name ASC";
											$result = mysqli_query($con, $sql);
											echo mysqli_error($con);
											while($r2 = mysqli_fetch_array($result)){ */
										?>
										<!--	<option value="<?php echo $r2['id']?>"><?php echo $r2['name'] ?></option> -->
											<?php //} ?>
										</select>
									</span>
								</div>	
                                
                            </div>
							
							<div class="form-group">
                                <div class="col-sm-6">
								<label for="itemDescription" class="control-label ">Description</label>
                                    <input type="text" class="form-control" id="itemDescription" placeholder="Item Description...">
                                </div>
								
								<div class="col-sm-6">
									<label class="control-label">Posting Account (DR)</label>
									<input type="text" class="form-control" id="posting_ACCOUNT_A"   readonly value="" >
								</div>
								
                            </div>
						
							
								<div class="form-group">
									
									<div class="col-sm-6">
									<label for="itemName" class="control-label">Cost Center Group</label>
										<select class="form-control" id="costcenter_group" name="costcenter_group" required="true" onchange="getcostcenter(this.value);" >
											<option value="">Select</option>
										<?php
											$sql = " SELECT * FROM sma_budget_name where 1  ORDER BY name ASC ";
											$q2  = mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_object($q2)){
												$name = $r2->name;
												$id = $r2->id;
										?>		
											<option value='<?php echo $id ?>'><?php echo $name; ?></option>
										<?php }; ?>
										</select>
										
									</div>
								
									<div class="col-sm-6">
										<span id="getcostcenter" >
											
										<label for="itemName" class="control-label">Cost Center Name</label>
										
										</span>
									</div>
								</div>
									
								<div class="form-group">	
									<div class="col-sm-12">
										<span id="getcatbudget">
												
										</span>
									</div>
								</div>
								
                        <?php } ?>
							
						</div>
						
                        <div class="form-group col-md-12 ">
								
						<span id="getitemdetails">			
					
							<div class="col-sm-2">
								<label for="itemGST" class="control-label">GST Type</label>	
								<select class="form-control" name="itemg_id" id="itemg_id" onchange="getgst(this.value)" >
									<option value=""> Select </option>
									<?php 
									$sql = "select * from gst_mst where 1 order by gst_name ";
									$q22 	= mysqli_query($con, $sql);
									while($r22 = mysqli_fetch_array($q22)){ 
									?>
									<option value="<?php echo $r22['id'].'-'.$r22['igst'];?>" ><?php echo $r22['gst_name'].'-'.$r22['igst'];?></option>
										<?php } ?>
								</select>
							</div>
							
								<div class="col-sm-2">
									<label for="itemGST" class="control-label">GST%</label>
								
                                    <input type="text" class="form-control" id="itemGST"  style="text-align:right;" readonly onkeyup="calculateTotalAmount();">
									
									<input type="hidden" class="form-control" name="itemgst_id" id="itemGST_ID"  style="text-align:right;" readonly >
								
                                </div>

							
                                <div class="col-sm-2">
									<label for="itemQuantity" class="control-label">Qty.</label>
									<input type="text" class="form-control" id="itemQuantity"  style="text-align:right;" onkeyup="calculateTotalAmount();">
                                </div>
                            
							<span id="getunit123" > 
								<div class="col-sm-2">
									<label for="itemUnits" class="control-label">Units</label>
										<input type="text" class="form-control itemUNITS" id="itemUNITS" name="itemunits" readonly value='' >
								</div>
                            </span>	
                                
								<div class="col-sm-2">
									<label for="itemRate" class="control-label">Rate</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">&#8377</span>
                                        <input type="text" class="form-control" id="itemRate" style="text-align:right;" <?= $readonlye;?> onkeyup="calculateTotalAmount();">
                                    </div>
                                </div>
								
							
							
								<div class="col-sm-2">
									<label for="itemAmount" class="control-label">Total </label>
                                    <input type="text" class="form-control" id="itemAmount" style="text-align:right;" readonly>
                                </div>
                           </span>
							
                            </div>
						
							
					
					
                        <div style="color:red;font-weight:bold;" id="showmsg"> </div>
						</form>
                    </div>
					
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="clearfld()" >Close</button>
			<?PHP if ($against_po_flag=='Y'){ ?>	
                <button type="button" class="btn btn-primary" id="addItem" onclick="checkbudget();" >Save </button>
			<?php } 
			else { 
			?>
				<button type="button" class="btn btn-primary" id="addItemmanual" >Save</button>	
			<?php }  //onclick="checkbudget123();"
			?>
			
            </div>
			
                </section>
            </div>

        </div>
    </div>
</div>


<!-- Modal Add Tally-->
<div class="modal fade" id="modalAddTally" role="dialog" aria-labelledby="modalAddTallyLabel" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalAddTallyLabel">Do you want create tally journal?</h4>
            </div>
            <div class="modal-body123">
                <section class="content123">
                    <div class="row123">
                        <form class="form-horizontal" action="#" method="POST" enctype="multipart/form-data">
						
                            <input type="hidden" id="modeT" value='Tally'>
							<input type="hidden" id="pv_idT" value="<?php echo $_GET['id'];?>">
						
						</form>
                    </div>
					
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="addTallyEntry" >Submit</button>
            </div>
			<!--onclick="tallyentry123();"-->
                </section>
            </div>
        </div>
    </div>
</div>


<script>
	function checkbudget(){
		
	//	balance_budget_a
        var itemAmount 		= document.getElementById('itemAmount').value;
		
		var balance_budget 	= document.getElementById('balance_budget_a').value;
//alert(itemAmount + ' <<##1>>' + balance_budget );		
//return false;
		var check_balance	= parseInt(balance_budget) - parseInt(itemAmount);
//alert(check_balance  );			
		if ( check_balance < 0 ){
			alert('AOP / Budget 100% reached, please increase AOP !!!');
			return false;
		}

	}
	
	function getitemdetails(id){
		
        var sub    = 'sub44';
		var comp_id = document.getElementById('comp_id').value;
		//document.getElementById("Text1").value;
//alert(sub + ' ' + id + ' ' + grn_no);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,comp_id:comp_id,sub44:sub},function(result){
		      $('#getitemdetails').html(result);
		});

	}
	
	function gettotbudget(id){
		
        var sub    = 'sub45';
		
		var budget_name_id 	= document.getElementById('budget_name').value;
		var comp_id 		= document.getElementById('comp_id').value;
		//document.getElementById("Text1").value;
//alert(sub + ' <<>> ' + id + ' <<>> ' + budget_name_id + ' <<>> ' + comp_id);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,budget_name_id:budget_name_id,comp_id:comp_id,sub45:sub},function(result){
		      $('#gettotbudget').html(result);
		});

	}
	

</script>

<!-- Modal Add Item-->
<!-- For Document Attachment Start-->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>        


<script>  
 $(document).ready(function(){  
      var i=1;  
      $('#add').click(function(){
			var opt =  document.getElementById('opt').value;
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'"><td width="20%" ><select class="form-control select2 doctype" name="doctype[]"  ><option value="">Select</option>'+opt+'</select></td><td width="20%"><textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea></td><td width="20%"><textarea class="form-control share_point_link" name="share_point_link[]" rows="2" placeholder="Enter share point link..."></textarea></td><td width="30%"><input type="file" name="fudoc[]" class="docfile"></td><td  width="10%"><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
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

<script>

	function getporefno(id){
		
        var sub    = 'sub1';
//alert(sub);		
		var strURL = "si_func.php";
		$.post(strURL,{id:id,sub1:sub},function(result){
		      $('#getporefno').html(result);
		});

	}


	function getcreditdays(id){
		
        var sub    = 'sub4';
		var strURL = "si_func.php";
		$.post(strURL,{id:id,sub4:sub},function(result){
			var company_id = result;
			  var splitString = result.split("##");
			  var company_id =  splitString['1'];
			  
			  var result = splitString['0'];
			  $('#getcreditdays').html(result);
			  
			    /* var sub    = 'sub12';
			    var strURL = "app_func.php";
				$.post(strURL,{id:company_id,sub12:sub},function(result){
					  $('#getworkflowtype').html(result);
				}); */
				
		      //$('#getcreditdays').html(result);
		});

	}
	
</script>

<script>


   $("#submitDraft").on("click", function(e){
        var mode		 	=  $("#modeD").val();
		var pv_id		 	=  $("#pv_idD").val();
        var status 			=  $("#statusD").val();
		var remarks			=  $("#remarksD").val();
		
//alert(remarks +  ' ' + pv_id + ' ' + st_flag);
	
		$('#makeDraftAuthority').modal('hide');
		var strURL = "py_draft_func.php";
		$.post(strURL,{ pv_id:pv_id,
						mode:mode,
						status:status,
						remarks:remarks},
						function(result){
		      $('#predit').html(result);
		});
	});

   $("#submitDelete").on("click", function(e){
        var mode		 	=  $("#modeZ").val();
		var pv_id		 	=  $("#pv_idZ").val();
        var status 			=  $("#statusZ").val();
		var remarks			=  $("#remarksZ").val();
		
//alert(remarks +  ' ' + pv_id + ' ' + st_flag);
	
		$('#deleteAuthority').modal('hide');
		var strURL = "py_delete_func.php";
		$.post(strURL,{ pv_id:pv_id,
						mode:mode,
						status:status,
						remarks:remarks,
						mode:mode},
						function(result){
		      $('#predit').html(result);
		});
	});

  
    $("#submitApprove").on("click", function(e){
		$('.hidden-div').hide();
		$('#predit').html('Wait...');
        var sub = 'sub9';
		var mode		 	=  $("#modeE").val();
		
//		alert(sub + ' ' + mode);		 
		var pv_id		 	=  $("#pv_idE").val();
		var approver 		=  $("#approverC").val();
       var status 			=  $("#statusE").val();
		var remarks			=  $("#remarksE").val();
		
//alert( approver + ' ' + status + ' ' +  mode );
		 $('#approvalAuthority').modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ pv_id:pv_id,
						mode:mode,
						approver:approver,
						status:status,
						remarks:remarks,
						sub9:sub},
						function(result){
		      $('#predit').html(result);
		});
	});

		
    $("#submitReject").on("click", function(e){
        var sub = 'sub9';
		var mode		 	=  $("#modeR").val();
		
		//alert(sub + ' ' + mode);		 
		var pv_id		 	=  $("#pv_idR").val();

        var approver 		=  $("#approverR").val();
		//var approver		=  $("#approverR option:selected").val();
        var status 			=  $("#statusR").val();
		var remarks			=  $("#remarksR").val();
		if(remarks==''){
			alert('Remarks should be mandatory !!!');
			return false;
		}
//alert(status+ ' ' + ' ' + mode + ' ' + approver);
		 $('#rejectAuthority').modal('hide');
		var strURL = "app_func.php";
		$.post(strURL,{ pv_id:pv_id,
						mode:mode,
						approver:approver,
						statusap:mode,
						status:status,
						remarks:remarks,
						sub9:sub},
						function(result){
		      $('#predit').html(result);
		});
	});


    $("#addItemmanual").on("click", function(e){
        var sub = 'sub2';
//alert(sub); return false;
		
		var provisional_jv_hdr_id 	 =  $("#pv_id").val();
		var company_id   =  $("#comp_id").val();
		var id 			 =  $("#itemName").val();
		//var id 			 =  $(".itemName").val();
		//var name		 =  $("#itemName").html();
//alert('addItemmanual ' + id + ' <<#>>');
//return false;
			
		var description 	 =  $("#itemDescription").val();
        var qty 			 =  $("#itemQuantity").val();
        var units 			 =  $("#itemUnits").val();
		var po_threashold    =  $("#po_Threashold").val();
        var rate 			 =  $("#itemRate").val();
		var gst  			 =  $("#itemGST").val();
		var gst_id 			 =  $("#itemGST_ID").val();
        var amount 			 =  $("#itemAmount").val();
//alert(gst_id);
//return false;
		var budget_id  	 =  $("#budget_ID").val();
//alert('Budget ID : '+budget_id);
			var tot_amount  	 =  $("#TOT_AMOUNT").val();
			
		var itemamount 		= document.getElementById('itemAmount').value;
		var balance_budget 	= document.getElementById('balance_BUDGET').value;

		var check_balance	= parseInt(balance_budget) - parseInt(itemamount) - parseInt(tot_amount) ;
		
//alert( check_balance + ' <<#1>>' + itemamount + ' <<<#2>>> ' + balance_budget + ' <<<#3>>> ' + tot_amount + ' <<<##>>>' );

		if ( check_balance < 0 ){
			alert('AOP / Budget 100% reached, please increase AOP!!!');
			var shoid = 'AOP / Budget 100% reached, please increase AOP !!!';
			$("#showmsg").text(shoid);
			return false;
		}
		
		$('#modalAddItem').modal('hide');
 		var strURL = "si_func.php";
		$.post(strURL,{ id:id,provisional_jv_hdr_id:provisional_jv_hdr_id,
							description:description,
							company_id:company_id,
							budget_id:budget_id,
							qty:qty,
							units:units,
							rate:rate,
							gst:gst,
							gst_id:gst_id,
							amount:amount,
							sub2:sub},
							function(result){
		      $('#prItemsTableBody').html(result);
		});
		

	});

    $("#addItem").on("click", function(e){
        var sub = 'sub2';
	//	var mode = $("#mode").val();
			var grn_no		 =   $("#grn_No").val();
//alert(sub2);
//		if(grn_no != '0'){
			var provisional_jv_hdr_id 	 =  $("#pv_id").val();		
			var id 			 =  $("#itemName").val();
			var name		 =  $("#itemName").html();
			var company_id   =  $("#comp_id").val();
			var budget_name  =  $("#budget_name").val();
			var budget_head  =  $("#budget_head").val();
			var budget_id  	 =  $("#budget_id").val();
			var budget_head_b  	 =  $("#budget_head_b").val();
			var budget_name_b  	 =  $("#budget_name_b").val();
			
//alert('addItem '+ grn_no + ' <<#>> ' + company_id);
//return false;

		var tot_amount  	 =  $("#TOT_AMOUNT").val();
			
		var itemamount 		= document.getElementById('itemAmount').value;
		var balance_budget 	= document.getElementById('balance_budget_a').value;
		var balance_budget	=  $(".balance_budget_a").val();
//alert('Balance Budget ' + balance_budget + ' #####1');
		
		var check_balance	= parseInt(balance_budget) - parseInt(itemamount) - parseInt(tot_amount) ;
		
//alert( check_balance + ' ' + itemamount + ' <<<>>> ' + balance_budget + ' <<<>>> ' + tot_amount + ' <<<>>>' );
	
		var itemqtychk  	 =  $("#itemQtyChk").val();			
		var description 	 =  $("#itemDescription").val();
        var qty 			 =  $("#itemQuantity").val();
        var units 			 =  $("#itemUnits").val();
		var po_threashold    =  $("#po_Threashold").val();
        var rate 			 =  $("#itemRate").val();
		var gst  			 =  $("#itemGST").val();
        var amount 			 =  $("#itemAmount").val();

//alert(qty + ' ' + itemqtychk);

		 
		if ( parseInt(enter_amount) > parseInt(amount) && po_threashold=='V'){
			alert('Amount should not be greater then PO AMount...');
			var shoid = 'Amount should not be greater then PO AMount... !!!';
			$("#showmsg").text(shoid);
			return false;
		} 
		
		
		if ( parseInt(qty) > parseInt(itemqtychk) && po_threashold=='Q'){
			alert('Quantity should not be greater then PO QTY...');
			var shoid = 'Quantity should not be greater then PO QTY !!!';
			$("#showmsg").text(shoid);
			return false;
		}
		
//return false;		
		
		if ( check_balance < 0 ){
			alert(' Budget 100% reached, please increase !!!');
			var shoid = ' Budget 100% reached, please increase  !!!';
			$("#showmsg").text(shoid);
			return false;
		}
		
//alert(units + ' ' + qty);
        $('#modalAddItem').modal('hide');
 		var strURL = "si_func.php";
		$.post(strURL,{ id:id,provisional_jv_hdr_id:provisional_jv_hdr_id,
							name:name,
							description:description,
							company_id:company_id,
							grn_no:grn_no,
							budget_name:budget_name,
							budget_head:budget_head,
							budget_id:budget_id,
							budget_head_b:budget_head_b,
							budget_name_b:budget_name_b,
							qty:qty,
							units:units,
							rate:rate,
							gst:gst,
							amount:amount,
							sub2:sub},
							function(result){
		      $('#prItemsTableBody').html(result);
		});
		
//		location.reload();
//		window.location.href='supplier_invoice.php?sub=edit&id='+provisional_jv_hdr_id+'&active=active';
		
//        saveItem(mode);
		
    });


    $("#submitAccount").on("click", function(e){
		
        var sub 		= 'sub12';
		//var type_ac 	= $("#type_acA").val();
		var account_id 	= $("#account_idA").val();
		var account_name = $("#account_idA option:selected").html();
			
		//var effect 		= $("#effectA").val();
		var amount2 		= $("#amountABC").val();
		var amount 		= $(".amountA").val();
		var narration 	= $("#narrationA").val();
		var provisional_jv_hdr_id 	= $("#pv_idA").val();		

		var effect		=  $("#effectA:checked").val();
		var type_ac		=  $("#type_acA:checked").val();
		
		if(amount2>0){
			var amount = parseInt(amount2);
		}
		
//alert( amount2 + ' ' + amount + ' <<>> ' + provisional_jv_hdr_id + ' ' + account_name + ' ' + type_ac + ' ' + effect );

		$('#addLine').modal('hide');
		var strURL 		= "si_func.php";
		$.post(strURL,{ type_ac:type_ac,account_id:account_id,account_name:account_name,effect:effect,amount:amount,narration:narration,provisional_jv_hdr_id:provisional_jv_hdr_id,sub12:sub},
							function(result){
		      $('#tallyentry').html(result);
		});
		
		//$('#tallyentry').html('result');
			  
    });


    $("#submitPVAccount").on("click", function(e){
		
        var sub 		= 'sub2';
		var account_id 	= $("#account_idPVA").val();
		var account_name = $("#account_idPVA option:selected").html();
			
		var amount 		= $(".amountPVA").val();
		var narration 	= $("#narrationPVA").val();
		var provisional_jv_hdr_id 	= $("#pv_idPVA").val();		

		var effect		=  $("#effectPVA:checked").val();
		var type_ac		=  $("#type_acPVA:checked").val();
		
		$('#addPVLine').modal('hide');
		var strURL 		= "app_func.php";
		$.post(strURL,{ type_ac:type_ac,account_id:account_id,account_name:account_name,effect:effect,amount:amount,narration:narration,provisional_jv_hdr_id:provisional_jv_hdr_id,sub2:sub},
							function(result){
		      $('#tallyPVentry').html(result);
		});
		
    });


    $("#addTallyEntry").on("click", function(e){
		
        var sub 	= 'sub3';
		var mode 	= $("#modeT").val();
		var provisional_jv_hdr_id 	 =  $("#pv_idT").val();		

//alert(provisional_jv_hdr_id);

		$('#modalAddTally').modal('hide');
		var strURL 		= "app_func.php";
		$.post(strURL,{ mode:mode,provisional_jv_hdr_id:provisional_jv_hdr_id,sub3:sub},
							function(result){
		      $('#tallyentry').html(result);
		});
		
		//$('#tallyentry').html('result');
			  
    });

	function getaccount(id){
		
        var sub    		= 'sub11';
		
//alert(sub + ' ' + id);
		var strURL = "si_func.php";
		$.post(strURL,{id:id,sub11:sub},function(result){
		      $('#getaccount').html(result);
		});

	}

	function gettdsamt(id){
		
        var sub    		= 'sub13';
		var amount_dr 	= $("#amount_DR").val();
		
		var pv_id	 	= $("#pv_idA").val();
//alert(sub + ' ' + id + ' ' +  amount_dr);
		var strURL = "si_func.php";
		$.post(strURL,{id:id,pv_id:pv_id,amount_dr:amount_dr,sub13:sub},function(result){
		      $('.gettdsamt').html(result);
		});

	}
	
	function delete_siItem(pv_id, id ){
		var sub = 'sub3';
        var siid = pv_id;
		var id	 = id;
//alert(siid + ' ' + id);
		$('#modalDeleteItem'+siid+id).modal('hide');
		var strURL = "si_func.php";
		$.post(strURL,{ siid:siid,id:id,sub3:sub},
							function(result){
		      $('#prItemsTableBody').html(result);
			});
		window.location.href='edit.php?sub=edit&id='+siid+'&active=active';	
		//location.reload();
	}


	function getmaterial(id){
		
        var sub    = 'sub33';
		var dtl_id			= document.getElementById('dtl_id').value;
//alert(id + ' ' + sub + ' ' + ' ' + ' Edit Func' );
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub33:sub},function(result){
		      $('.getmaterial').html(result);
		});

	}

	function getmaterial1(id){
		
        var sub    = 'sub3';
		
//alert(sub + ' ' );
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub3:sub},function(result){
		      $('#getmaterial1').html(result);
		});

	}

	function getunit(id){
		
        var sub    = 'sub4';
		//var grn_no = document.getElementById('grn_No').value;
		//document.getElementById("Text1").value;
//alert(sub + ' ' + id);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub4:sub},function(result){
		      //$('#getunit').html(result);
			//alert(result);  
				var splitString = result.split("##");
		
				var uom 			=  splitString['0'];
				var account_name 	= splitString['1'];
				var tolerance_level = splitString['2'];
				var po_threashold 	= splitString['3'];
		
			  $("#itemUNITS").val(uom);
			  $("#posting_ACCOUNT_A").val(account_name);
			  //document.getElementById("itemUNITS").value = result;
			  
		});

	}

		function getgrnitem(id){
		
        var sub    = 'sub5';
//alert(sub + ' ' + id);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub5:sub},function(result){
		      $('#getgrnitem').html(result);
		});

	}
	
	function getgrnitem1(id){
		
        var sub    = 'sub5';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub5:sub},function(result){
		      $('#getgrnitem1').html(result);
		});

	}

	
	function getcompany(id){
		
        var sub    = 'sub8';
//	alert(sub + ' ' + id);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub8:sub},function(result){
		      $('#getcompany').html(result);
		});

	}
	
	function getcompany1(id){
		
        var sub    = 'sub88';
//	alert(sub + ' ' + id);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub88:sub},function(result){
		      $('#getcompany1').html(result);
		});
	}
		
	function getbudgetname(id){
		
        var sub    = 'sub7';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub7:sub},function(result){
		      $('#getbudgetname').html(result);
		});

	}

	function getbudgetname1(id){
		
        var sub    = 'sub77';
//alert(sub + ' ' + company_id + ' ' + account_year);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub77:sub},function(result){
		      $('#getbudgetname1').html(result);
		});

	}


	function getbudget(id){
		
        var sub    = 'sub6';
		var company_id   = document.getElementById('company_ID').value;
		var account_year = document.getElementById('account_YR').value;
//alert(sub + ' ' + company_id + ' ' + account_year);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,company_id:company_id,account_year:account_year,sub6:sub},function(result){
		      $('#getbudgethead').html(result);
		});

	}
	
	function getbudget1(id){
		
        var sub    = 'sub66';
		var company_id   = document.getElementById('company_iD').value;
		var account_year = document.getElementById('account_yR').value;
//alert(sub + ' ' + company_id + ' ' + account_year);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,company_id:company_id,account_year:account_year,sub66:sub},function(result){
		      $('#getbudgethead1').html(result);
		});

	}


	function getstate(id){
		
        var sub    = 'sub9';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub9:sub},function(result){
		      $('#getstate').html(result);
		});

	}


	function getuser(id){
		
        var sub    = 'sub11';
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub11:sub},function(result){
		      $('#getuser').html(result);
		});

	}
	function getuser1(id){
		
        var sub    = 'sub11';
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub11:sub},function(result){
		      $('#getuser1').html(result);
		});

	}


	function getpartydoc(id){

		var sub    = 'sub23';
		var id 	   = 'N';
		var checkBox = document.getElementById("partyDoc");
		if (checkBox.checked == true){
			var id	='Y';
		}	

		if(id=='Y'){
			var party_id_doc = document.getElementById("party_id_doc").value;
			var company_idd_doc = document.getElementById("company_idd_doc").value;
			
			
		//alert(id + ' ' + sub + ' ' + party_id_doc);
			var strURL = "search_func.php";
			$.post(strURL,{id:id,sub23:sub,party_id_doc:party_id_doc,company_idd_doc:company_idd_doc},function(result){
				  $('#gegpartyDoc').html(result);
			});
		}
		else {
			$('#gegpartyDoc').html("");
		}	

	}



	   function showEdit(editableObj) {
			$(editableObj).css("background","#FFF");
		}
		
		function saveToDatabase(editableObj,column,id) {
		    
	//		var rate = editableObj.innerHTML;
		
//		alert("UPDATE `enqdetail` set " + editableObj);
		
			//$(editableObj).css("background","#FFF  no-repeat right");
			$.ajax({
				url: "savetallynarration.php",
				type: "POST",
				data:'column='+column+'&editval='+editableObj+'&id='+id,
				success: function(data){
				    $(editableObj).css("background","#FDFDFD");
				}
				
		   });
	   }

	function getworkflowtype(id){
		
        var sub    = 'sub12';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub12:sub},function(result){
		      $('#getworkflowtype').html(result);
		});

	}

	function getapprover(){
		
		var company_id    	= document.getElementById("company_id").value;
		var checker_value   = document.getElementById("checker_value").value;
		var trans_type    	= document.getElementById("trans_type").value;
		
		var sub = 'sub5';
//alert(sub + ' ' + checker_value);		

		$('.hidesend').hide();
		var strURL = "app_func.php";
		$.post(strURL,{company_id:company_id,checker_value:checker_value,trans_type:trans_type,sub5:sub},function(result){
		      $('#getapprover').html(result);
		});
		
	}


	function getcostcenter(id){
		
        var sub    = 'sub1a';
		var company_id    = document.getElementById("comp_id").value;
		
//alert(sub + ' ' + company_id  );		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,company_id:company_id,sub1a:sub},function(result){
		      $('#getcostcenter').html(result);
		});

	}

	function getcostcenterC(id){
		
        var sub    = 'sub1a';
		var company_id    = document.getElementById("comp_id").value;
		
//alert(sub + ' ' + company_id  );		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,company_id:company_id,sub1a:sub},function(result){
		      $('.getcostcenter').html(result);
		});

	}
	
	function getcatbudget (id){
		var sub    = 'sub14a';
		var strURL = "app_func.php";
		var company_id    = document.getElementById("comp_id").value;
//alert(company_id);		
		var product_id 			 =  $("#itemName").val();	
//alert(id + ' ' + product_id);	
//alert(sub + ' ' + id + ' ' + company_id + ' ' + product_id + ' ' + strURL);
		$.post(strURL,{id:id,company_id:company_id,product_id:product_id,sub14a:sub},function(result){
		      $('#getcatbudget').html(result);
		});

	}

	function getcatbudgetC(id){
		var sub    = 'sub14a';
		var strURL = "app_func.php";
		var company_id    = document.getElementById("comp_id").value;
//alert(company_id);		
		var product_id 			 =  $("#itemName").val();	
//alert(id + ' ' + product_id);	
//alert(sub + ' ' + id + ' ' + company_id + ' ' + product_id + ' ' + strURL);
		$.post(strURL,{id:id,company_id:company_id,product_id:product_id,sub14a:sub},function(result){
		      $('.getcatbudget').html(result);
		});

	}
	
	

    function clearfld(){
		
		$('#itemDescription').html('');
		$('#itemQuantity').html('');
		$('#itemUnits').html('');
		$('#itemRate').html('');
		$('#itemGST').html('');
		$('#itemAmount').html('');
	
		//$baseurl1 = $baseurl . $modulePath;
		location.reload();
	
	}

	
	function getvalidate(){
		
		var project 	=  $("#comp_id").val();
		var location 	=  $("#location").val();
		var department 	=  $("#department").val();
		var quotation_reference_no 	=  $("#our_po_ref_NO").val();
		var to_supplier 	= $("#suplier_name").val();
		
		var against_po_flag  = '';
		var against_po_flag1 = '';
		
		if (document.getElementById('against_po_FLAG1').checked) {
		    against_po_flag1 = document.getElementById('against_po_FLAG1').value;
			var against_po_flag = against_po_flag1;
		}
		if (document.getElementById('against_po_FLAG').checked) {
		    against_po_flag = document.getElementById('against_po_FLAG').value;
		}
		
		if(to_supplier==''){
			alert('Supplier selection mandatory !!!');
			return;
		}
		
 		if(quotation_reference_no==''){
			alert('PO Ref. No. selection mandatory !!!');
			return;
		}

		if(project==''){
			alert('Company selection mandatory !!!');
			return;
		}
/* 		if(po_doc_type==''){
			alert('Workflow type selection mandatory !!!');
			return;
		}
 */		
		if(location==''){
			alert('Location selection mandatory !!!');
			return;
		}
		if(department==''){
			alert('Department selection mandatory !!!');
			return;
		}
		
	}	
	
	function getsubmit(){
		
		var row_affected 	=  $("#row_affected").val();
		var approval_role_1	=  $("#APPROVER_1").val();
		var approval_role_2	=  $("#APPROVER_2").val();
		var approval_role_3 =  $("#APPROVER_3").val();
		var approval_role_4 =  $("#APPROVER_4").val();
		var approval_role_5 =  $("#APPROVER_5").val();
		var approval_role_6 =  $("#APPROVER_6").val();
		var approval_role_7 =  $("#APPROVER_7").val();
		var approval_role_8 =  $("#APPROVER_8").val();

//alert(row_affected + ' ' + approval_role_1 + ' ' + approval_role_2 + ' ' + approval_role_3);

		if(row_affected==1 || row_affected==2 || row_affected==3 || row_affected==4 || row_affected==5 || row_affected==6 || row_affected==7 || row_affected==8){
			if(approval_role_1==''){
				alert('First Approval should select !!!');
				return false;
			}
		}	
		if(row_affected==2 || row_affected==3 || row_affected==4 || row_affected==5 || row_affected==6 || row_affected==7 || row_affected==8){
			if(approval_role_2==''){
				alert('Second Approval should select !!!');
				return false;
			}
		}
		if(row_affected==3 || row_affected==4 || row_affected==5 || row_affected==6 || row_affected==7 || row_affected==8){
			if(approval_role_3==''){
				alert('Third Approval should select !!!');
				return false;
			}
		}
		if(row_affected==4 || row_affected==5 || row_affected==6 || row_affected==7 || row_affected==8){
			if(approval_role_4==''){
				alert('Fourth Approval should select !!!');
				return false;
			}
		}
		if( row_affected==5 || row_affected==6 || row_affected==7 || row_affected==8){
			if(approval_role_5==''){
				alert('Fifth Approval should select !!!');
				return false;
			}
		}
		if( row_affected==6 || row_affected==7 || row_affected==8){
			if(approval_role_6==''){
				alert('Sixth Approval should select !!!');
				return false;
			}
		}
		if( row_affected==7 || row_affected==8){
			if(approval_role_7==''){
				alert('Seventh Approval should select !!!');
				return false;
			}
		}
		if( row_affected==8){
			if(approval_role_8==''){
				alert('Eighth Approval should select !!!');
				return false;
			}
		}
		
		return false;
		
	}
	
	function getgst(id){

//alert(id);		
		var splitString = id.split("-");
		
		var gst_id =  splitString['0'];
		var gst_perc = splitString['1'];
		
//alert(gst_id + ' ' + gst_perc);			
		$('#itemGST_ID').val(gst_id);
		$('#itemGST').val(gst_perc);
			  
	}	


	function gettdsperc1(id){
		var sub    = 'sub22';
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub22:sub},function(result){
			  $('#deduction1_amount').val(result);
		});
	}
	
	function gettdsperc2(id){
		var sub    = 'sub22';
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub22:sub},function(result){
			  $('#deduction2_amount').val(result);
		});
	}
	
	function gettdsperc3(id){
		var sub    = 'sub22';
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub22:sub},function(result){
			  $('#deduction3_amount').val(result);
		});
	}
	
	function getcomment(comment,pv_id,doc_type,comment_type,page){
		
		var sub = 'sub35';
		var comment = $('#comment_A').val();
		
//alert(sub + ' ' + comment + ' ' + ap_id + ' ' + doc_type+ ' ' + comment_type);
		//$('.hidesend').hide();
		
		var strURL = "app_func.php";
		$.post(strURL,{comment:comment,pv_id:pv_id,doc_type:doc_type,comment_type:comment_type,page:page,sub35:sub},function(result){
		      $('#getcomment').html(result);
		})
		
	}
			
	
	function getDate(id){
		   var sub = 'sub4';

		mm = document.getElementById('mm').value;
		yyyy = document.getElementById('yyyy').value;
		var mmyyyy = mm + '-' + yyyy;
	//alert(mmyyyy);	
		var strURL = "app_func.php";
		$.post(strURL,{ sub4:sub,mmyyyy:mmyyyy},function(result){
				  $('#getDate').html(result);
			});
	}
</script>


</body>
</html>
