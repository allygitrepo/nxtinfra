<?php session_start();
	include('../dbcon.php');
	include "../baseurl.php";
	$comid  = $_SESSION['comid'];	
?>

<?php
	
    if(isset($_POST['sub1'])){
    
		$id = $_POST['id'];

		$sql="SELECT * FROM account_mst where account_type = 'B' and company_id = '$id' ORDER BY account_name ASC";

//echo $sql;

?>	
       <div class="col-md-5">
		
			<select class="form-control" id="cash_bank_name" name="cash_bank_name" required onchange="getvendor(this.value)" >
				<option value="">Select</option>	
				<?php
				$q2 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				while($r2 = mysqli_fetch_array($q2)){
				?>
				<option value="<?php echo $r2['id']?>" ><?php echo $r2['account_name'] ?></option>
				<?php } ?>
			</select>
							
		</div>
	
<?php
	}
	
//echo $_POST['id']. ' #123#' . $_POST['sub1'] . ' <<#W>> ' .$_POST['sub2']. "<< ###!23>>";	

if(isset($_POST['sub2'])){
    
		$id = $_POST['id'];
		
		$sql = "select * from sma_party_mst where 1 and id in (SELECT paid_to FROM `payment_header` where cash_bank_name = '$id' ) ";
		$q2 	= mysqli_query($con, $sql);
		$cnt = mysqli_affected_rows($con);

		if($cnt>0){
?>	
		<div class="col-md-5">
			<select class="form-control select2" name="party_id" id="party_id" >
			<option value=""> Select </option>
			<option value=""> All </option>
			<?php $sql = "select * from sma_party_mst where 1 and id in (SELECT paid_to FROM `payment_header` where cash_bank_name = '$id' ) order by party_name ";
			$q2 	= mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_array($q2)){ ?>
			<option value="<?php echo $r2['id'];?>" ><?php echo $r2['party_name'];?></option>
			<?php } ?>
			</select>
		</div>
<?php
		}
		else{
?>			
			<div class="col-md-5">
				<select class="form-control select2" name="party_id" id="party_id" >
					<option value=""> Select </option>
					<option value=""> All </option>
					<?php $sql = "select * from sma_party_mst where 1 order by party_name ";
					$q2 	= mysqli_query($con, $sql);
					while($r2 = mysqli_fetch_array($q2)){ ?>
					<option value="<?php echo $r2['id'];?>" ><?php echo $r2['party_name'];?></option>
					<?php } ?>
				</select>
			</div>
<?php
		}

}
	
	if(isset($_POST['sub3'])){
    
		$id = $_POST['id'];
		
		$sql = "SELECT * from rtgs_temp where selected = 'Y' and py_id = '$id' ";
		$q2 	= mysqli_query($con, $sql);
		$cnt = mysqli_affected_rows($con);
		
		if($cnt>0){
			$sql = "UPDATE rtgs_temp SET selected = '' where py_id = '$id' ";
			mysqli_query($con, $sql);
		}
		else {
			$sql = "UPDATE rtgs_temp SET selected = 'Y' where py_id = '$id' ";
			mysqli_query($con, $sql);
		}
//echo $sql."<BR>";
//exit();

	}
	
	if(isset($_POST['sub4'])){

		$company_id 	= $_POST['company_id'];
		$checker_value  = $_POST['tot_payment_adjusted'];
		$py_id			= $_POST['py_id'];
		
		$doc_type 		= 'RT';
		$sql 	= "select * from company where comp_id = '$company_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2     = mysqli_fetch_array($q2);
		$company_id = $r2['comp_id'];
		
		$sql = " SELECT a.* FROM sma_workflow a , sma_workflow_type b 
					where 1 and b.id = a.trans_type and b.status = 'Y'
				    and a.doc_type = '$doc_type' 
					and company_id = '$company_id' ";
//echo $sql;	//and '$checker_value' >= from_value and '$checker_value' <= to_value 	and '$checker_value' >= from_value and '$checker_value' <= to_value				
		$q2 	= mysqli_query($con, $sql);
		$r2     = mysqli_fetch_array($q2);
		$approval_role_1 = $r2['approval_role_1'];
		$approval_role_2 = $r2['approval_role_2'];
		
		$row_affected = 0;
		if($approval_role_1>0){
			$row_affected = $row_affected + 1;
			$required1 = 'REQUIRED';
		}
		if($approval_role_2>0){
			$row_affected = $row_affected + 1;
			$required2 = 'REQUIRED';
		}
		
?>    
				
				<div class="box-footer">
								<div class="col-sm-1">
									<label class="control-label"><span style="text-align:right;color:red;">**</span></label>
								</div>
							<?php if($approval_role_1>0){ ?>			
								<div class="col-sm-8">
									<label class="control-label">Approver 1 </label>
							<?php
							
								$sql = " select * from sma_user where 1 and active = '1' and FIND_IN_SET( ( SELECT distinct(approval_role_1) FROM sma_workflow a , sma_workflow_type b 
								where 1 and b.id = a.trans_type and b.status = 'Y'
								and a.doc_type = '$doc_type' 
								 and company_id = '$company_id' and approval_role_1 >0 ), role ) and FIND_IN_SET($company_id,company_id) ";//  and id != '$userid'
								$rs = mysqli_query($con, $sql);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}
							?>

									<select class="form-control  approver_1" name="approver_1" <?= $required1; ?>   >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								
								</div>
							<?php } ?>	
							
							<?php if($approval_role_2>0){ ?>			
								<div class="col-sm-8">
									<label class="control-label">Approver 2 </label>
							<?php
							
								$sql = " select * from sma_user where 1 and active = '1' and FIND_IN_SET( ( SELECT distinct(approval_role_2) FROM sma_workflow a , sma_workflow_type b 
								where 1 and b.id = a.trans_type and b.status = 'Y'
								and a.doc_type = '$doc_type' 
								 and company_id = '$company_id' and approval_role_2 >0 ), role ) and FIND_IN_SET($company_id,company_id) ";//  and id != '$userid'
								$rs = mysqli_query($con, $sql);
								$single_user = mysqli_affected_rows($con);
								echo mysqli_error($con);
								$selected1='';
								if($single_user==1){
									$selected1 = 'SELECTED';
								}
							?>

									<select class="form-control  approver_2" name="approver_2" <?= $required2; ?>   >
                                    <?php if(empty($selected1)){ ?>
                                        <option value="">Select</option>
									<?php	} ?>
										<?php
										
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> <?= $selected1 ?>  ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								
								</div>
							<?php } ?>	
							
							<div class="col-sm-2">
							<label class="control-label">&nbsp;</label><br>
								<input type="submit" class="btn btn-primary" value="Submit" name="Save" class="form-control" onclick="getsubmit();" >
								
							</div>
						
						</div>
						
						<br>
						
						
								
<?php						
	
	}
	
	if(isset($_POST['sub5'])){
    
		$approver_1 	= $_POST['approver_1'];
		$company_id 	= $_POST['company_id'];
		$rtgs_dd		= $_POST['rtgs_dd'];

		$sql="SELECT * FROM sma_user where id = '$approver_1' ";
		$q2 =mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$draft_by 			= $r2['userid'];
		$draft_by_id 		= $r2['id'];
		$user_email 		= $r2['email'];
		$username 			= $r2['username'];
		
//echo $sql;
		$msg = 'Payment Number : '.$srno . ' ' . 'Dated : ' . date("d-m-Y");
		include "rtgs_mail.php";


	}	

	
	if(isset($_POST['sub22'])){
		 $id = $_POST['id'];
		
?>
		<select class="form-control" name="party_name" id="party_name" >
        	<option value=""> Select. </option>
			<option value=""> All </option>
			<?php $sql = "select * from sma_party_mst where party_type = '$id' order by party_name ";
			$q2 	= mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_array($q2)){ ?>
			<option value="<?php echo $r2['id'];?>" >  <?php echo $r2['party_name'];?></option>
			<?php } ?>
		</select>
									
<?php		
		  
	}
	
?>	