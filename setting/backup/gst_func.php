<?php session_start();
	include('../dbcon.php');
	
	include('../baseurl.php');
	
?>

<?php
	
    if(isset($_POST['sub1'])){
    
        $vertical_type = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$value = '';
?>		
		<div class="form-group">
							<label for="user_category" class="control-label col-sm-2">SGST Account Name*</label>
								
							<div class="col-md-5">
								<select class="form-control" name="sgst_account_id" id="sgst_account_id" >
									<option value=""> Select </option>
										<?php $sql = "select * from account_mst where 1 and vertical_type = '$vertical_type' order by account_name ";
										$q22 	= mysqli_query($con, $sql);
										while($r22 = mysqli_fetch_array($q22)){ ?>
									<option value="<?php echo $r22['id'];?>"  <?php echo ($account_id == $r22['id'])?'selected="selected"':'';?> ><?php echo $r22['account_name'];?></option>
									<?php } ?>
								</select>
							</div>
		</div>
						
		<div class="form-group">
							
							<label for="user_category" class="control-label col-sm-2">CGST Account Name*</label>
								
							<div class="col-md-5">
								<select class="form-control" name="cgst_account_id" id="cgst_account_id" >
									<option value=""> Select </option>
										<?php $sql = "select * from account_mst where 1 and vertical_type = '$vertical_type' order by account_name ";
										$q22 	= mysqli_query($con, $sql);
										while($r22 = mysqli_fetch_array($q22)){ ?>
									<option value="<?php echo $r22['id'];?>"  <?php echo ($account_id == $r22['id'])?'selected="selected"':'';?> ><?php echo $r22['account_name'];?></option>
									<?php } ?>
								</select>
							</div>
		</div>
						
		<div class="form-group">
							
							<label for="user_category" class="control-label col-sm-2">IGST Account Name*</label>
								
							<div class="col-md-5">
								<select class="form-control" name="igst_account_id" id="igst_account_id" >
									<option value=""> Select </option>
										<?php $sql = "select * from account_mst where 1 and vertical_type = '$vertical_type' order by account_name ";
										$q22 	= mysqli_query($con, $sql);
										while($r22 = mysqli_fetch_array($q22)){ ?>
									<option value="<?php echo $r22['id'];?>"  <?php echo ($account_id == $r22['id'])?'selected="selected"':'';?> ><?php echo $r22['account_name'];?></option>
									<?php } ?>
								</select>
							</div>
		</div>
						
<?php
						
    }
	
?>	