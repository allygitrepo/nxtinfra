<?php 

include("../header.php");
$modulePath = "setting/account_mst.php?sub=list";

?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
         Account Master
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active"> Account Master</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
			<?php	
				$targetpage = "account_mst.php?sub=list"; 
				$limit = 10; 
				$start = 0;	
				$comid  = $_SESSION['comid'];
				
				
				if ($_POST['percentage'] or $_POST['account_type'] or $_POST['search']){
					$_SESSION['percentage'] 	= $_POST['percentage'];
					$_SESSION['account_type'] 	= $_POST['account_type'];
					$_SESSION['search'] 		= $_POST['search'];
					$_SESSION['reset'] ='';
				}
				
				if ($_SESSION['percentage'] or $_SESSION['account_type'] or $_SESSION['search']){
					$percentage 	= $_SESSION['percentage'];
					$account_type 	= $_SESSION['account_type'];
					$search 		= $_SESSION['search'];
				}
				
				if (!empty($_GET['reset']) || !empty($_SESSION['reset'])){
					$_SESSION['percentage'] 	= '';
					$_SESSION['account_type'] 	= '';
					$_SESSION['search'] 		= '';
					$percentage 	= $_SESSION['percentage'];
					$account_type 	= $_SESSION['account_type'];
					$search 		= $_SESSION['search'];
				}
		
			?>
			<form class="form-horizontal" action="account_mst.php?sub=list" method="post">
						<div class="form-group">
								<div class="col-md-2">
									<label class=" control-label">Account&nbsp;Type</label>
									<select class="form-control" name="account_type" id="account_type" >
									<option value=""> Select </option>
							<!--	<option value="A" <?php echo ($account_type == 'A')?'selected="selected"':'';?> > Purchase</option>-->
									<option value="B" <?php echo ($account_type == 'B')?'selected="selected"':'';?>> Bank </option>
									<option value="D" <?php echo ($account_type == 'D')?'selected="selected"':'';?>> Deduction</option>
									<option value="E" <?php echo ($account_type == 'E')?'selected="selected"':'';?>> Expense</option>
									<option value="R" <?php echo ($account_type == 'R')?'selected="selected"':'';?>> Revenue</option>
									<option value="I" <?php echo ($account_type == 'I')?'selected="selected"':'';?>> Receipt</option>
									<option value="S" <?php echo ($account_type == 'S')?'selected="selected"':'';?>> Sales</option>
									</select>
								</div>
							
							<div class="col-md-3">
								<label class="control-label">Text</label>
								<input type="text" class="form-control " name="search" id="search" value="<?= $search; ?>" >
										
							</div>
								
							<div class="col-md-2">
                                <label class="control-label">&nbsp;</label><BR>	
								<input class="btn btn-success" type="submit" value="Search" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="account_mst.php?sub=list&reset=1" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
							</div>
							
						</div>
						
							<span id="getsearchf">
							<?php if($searchf=='N' || $searchf=='S'){ ?>	
								<div class="col-md-3">
								<?php if($searchf=='N'){ ?>
									<input type="text" class="form-control" id="search_data" name="search_data" autocomplete="off" value="<?php echo $search_data?>" >
								<?php } ?>
								
							<?php if($searchf=='S'){ ?>
									<select class="form-control select2-123" name="search_data">
									<option value=""> Select </option>
										<?php $sql = "select * from sma_party_mst order by party_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($search_data == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['party_name'];?></option>
										<?php } ?>
                                    </select>
							<?php } ?>
							
								</div>
							<?php } ?>
							
									
							</span>
											   
				</form>
				
				
              <h3 class="box-title">List of Account Master</h3>
				<span class="pull-right"><a href="account_mst.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>Create </a></span>
				
				<span class="pull-right" >&nbsp;&nbsp;&nbsp;&nbsp;</span>
                
				<span class="pull-right"><a href="account_export.php?sub=exp" name="btnAdd" class="btn btn-primary"><i class="splashy-document_letter_add"></i>Export </a></span>&nbsp;&nbsp;
				
				
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable123" class="table table-bordered table-striped">

	<thead>
		<tr>
			
			<th>Account</th>
			<th>Account Group</th>
			<th>Company</th>
			<th>Account Type</th>
			<th>Percentage</th>
			<th>CC Code</th>
			<th>Status</th>
			<th style="text-align:right;">Action</th>	
			
		</tr>
	</thead>
<tbody>
<?php
	
	
		$sql="SELECT * from account_mst where 1  "; //and status != 'N'
		$query="SELECT count(*) as num from account_mst where 1  "; //and status != 'N'
		
		
		if(!empty($account_type)){
			$sql   .= " and account_type = '$account_type' ";
			$query .= " and account_type = '$account_type' ";
		}		
	
	
		if(!empty($search)){
			$sql 	.= " and (account_name like '%$search%' || account_group in (SELECT id from sma_account_group where account_group like '%$search%' ))";
			$query 	.= " and (account_name like '%$search%' || account_group in (SELECT id from sma_account_group where account_group like '%$search%' )) ";
		}

	 	$qresult = mysqli_query($con,$query);
					echo mysqli_error($con);
					$total_pages = mysqli_fetch_array($qresult);
					$total_pages = $total_pages['num'];
					
					$stages = 3;
					//$page = mysqli_real_escape_string($_GET['page']);
					$page = ($_GET['page']);

					if($_GET['same_page']){
						$page = $_GET['same_page'];
					}

					if($page){
						$start = ($page - 1) * $limit; 
					}
					else{
						$start = 0;	
					}	
			
		$sql .= ' order by account_name asc ';
		
		$sql .= " LIMIT $start, $limit ";

	// Initial page num setup
	if ($page == 0){$page = 1;}
	$prev = $page - 1;	
	$next = $page + 1;							
	$lastpage = ceil($total_pages/$limit);		
	$LastPagem1 = $lastpage - 1;					
	
	$paginate = '';
	//echo $lastpage;
	//echo $paginate;
	if($lastpage > 1)
	{	
		$paginate .= '<div style="float:right"><ul class="pagination pagination-lg">';
		// Previous
		if ($page > 1){
			$paginate.= "<li><a href='$targetpage&page=$prev'>previous</a></li>";
		}else{
			$paginate.= "<li class='disabled'><a>previous</a></li>";	}
			
		// Pages	
		if ($lastpage < 7 + ($stages * 2))	// Not enough pages to breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page){
					$paginate.= "<li class='active'><a>$counter</a></li></span>";
				}else{
					$paginate.= "<li><a href='$targetpage&page=$counter'>$counter</a></li>";}
			}
		}
		elseif($lastpage > 5 + ($stages * 2))	// Enough pages to hide a few?
		{
			// Beginning only hide later pages
			if($page < 1 + ($stages * 2))		
			{
				for ($counter = 1; $counter < 4 + ($stages * 2); $counter++)
				{
					if ($counter == $page){
						$paginate.= "<li class='active'><a>$counter</a></li>";
					}else{
						$paginate.= "<li><a href='$targetpage&page=$counter'>$counter</a></li>";}
				}
				$paginate.= "<li><a>...</a></li>";
				$paginate.= "<li><a href='$targetpage&page=$LastPagem1'>$LastPagem1</a></li>";
				$paginate.= "<li><a href='$targetpage&page=$lastpage'>$lastpage</a></li>";		
			}
			// Middle hide some front and some back
			elseif($lastpage - ($stages * 2) > $page && $page > ($stages * 2))
			{
				$paginate.= "<li><a href='$targetpage&page=1'>1</a></li>";
				$paginate.= "<li><a href='$targetpage&page=2'>2</a></li>";
				$paginate.= "<li><a>...</a></li>";
				for ($counter = $page - $stages; $counter <= $page + $stages; $counter++)
				{
					if ($counter == $page){
						$paginate.= "<li class='active'><a>$counter</a></li>";
					}else{
						$paginate.= "<li><a href='$targetpage&page=$counter'>$counter</a></li>";}					
				}
				$paginate.= "<li><a>...</a></li>";
				$paginate.= "<li><a href='$targetpage&page=$LastPagem1'>$LastPagem1</a></li>";
				$paginate.= "<li><a href='$targetpage&page=$lastpage'>$lastpage</a></li>";
			}
			// End only hide early pages
			else
			{
				$paginate.= "<li><a href='$targetpage&page=1'>1</a></li>";
				$paginate.= "<li><a href='$targetpage&page=2'>2</a></li>";
				$paginate.= "<li><a>...</a></li>";
				for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page){
						$paginate.= "<li class='active'><a>$counter</a></li>";
					}else{
						$paginate.= "<li><a href='$targetpage&page=$counter'>$counter</a></li>";}
				}
			}
		}
					
				// Next
		if ($page < $counter - 1){ 
			$paginate.= "<li><a href='$targetpage&page=$next'>next</a></li>";
		}else{
			$paginate.= "<li class='disabled'><a>next</a></li>";
			}
			
		$paginate.= "</ul></div>";
}
//end page
//echo $sql;		
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		$status		  = $row['status'];
		
		$company_id = $row['company_id'];
		$sql = "select * from company where comp_id = '$company_id' ";	
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$comp_code = $r2['comp_code'];
		
		$account_type = $row['account_type'];
		if($account_type =='A'){
			$account_type ='Purchase';
		}
		else if ($account_type =='B'){
			$account_type ='Bank';		
		}
		else if ($account_type =='D'){
			$account_type ='Deduction';		
		}
		else if ($account_type =='E'){
			$account_type ='Expense';		
		}
		else if ($account_type =='R'){
			$account_type ='Revenue';		
		}
		else if ($account_type =='I'){
			$account_type ='Receipt';		
		}
		else if ($account_type =='S'){
			$account_type ='Sales';		
		}
		
		$account_group = $row['account_group'];
		$sql = "SELECT * from sma_account_group where id = '$account_group' ";
		$res = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($res);
		$account_group = $r2['account_group'];
		
		$budget_code 	= $row['budget_code'];
		
		$status 		= $row['status'];
		$percentage		= $row['percentage'];
		
		if($status=='N'){
			$status = 'Inactive';
		}
		else {
			$status = 'Active';
		}	
?>

	<tr>

		<td width="25%"><?php echo $row['account_name'];?></td>
		<td width="15%"><?php echo $account_group;?></td>
		<td width="10%"><?php echo $comp_code;?></td>
		<td width="10%"><?php echo $account_type;?></td>
		<td width="10%"><?php echo $percentage;?></td>
		<td width="10%"><?php echo $budget_code;?></td>
		<td width="10%"><?php echo $status;?></td>
		
		<td width="10%" style="text-align:right;">
		<a href="account_mst.php?sub=edit&id=<?= $row['id'];?>&page=<?= $page;?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
<!--<a href="account_mst.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
		
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
	
	</div>
    </div>
</div>	

    <?php }?>


<?php  
	if($_GET['sub'] == 'delete'){ 
        $id = $_GET['id'];
		$sql="Select * from account_mst where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	
		
		$account_name = $row['account_name'];
		$account_type = $row['account_type'];
		$budget_name = $row['budget_name'];
		$budget_code = $row['budget_code'];
		
		$sql="delete from account_mst where id='$id' ";
        //$sql="update account_mst set status = 'N' where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		
			$pgname = "account_mst.php";
			include "../viewonly.php";
			$description = $account_name.', '. $account_type. ','. $budget_name . ', '. $budget_code;
		    $user_name= $_SESSION['user']; 
		    $affect = 'Deleted';
			
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
        echo '<script>window.location.href="account_mst.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){
?>

<?php
 
	if(isset($_POST['Save'])){
			$account_name		= $_POST['account_name'];
			$account_type		= $_POST['account_type'];
			$account_group		= $_POST['account_group'];
			$percentage			= $_POST['percentage'];
			$deduction_from		= $_POST['deduction_from'];
			$retention_flag		= $_POST['retention_flag'];
			$tds_flag			= $_POST['tds_flag'];
			$budget_name		= $_POST['budget_name'];
			$budget_code		= $_POST['budget_code'];
			$status				= $_POST['status'];
			$branch				= $_POST['branch'];
			$address		 	= $_POST['address'];
			$account_number		= $_POST['account_number'];
			$isfc_code			= $_POST['isfc_code'];
			$company_id			= $_POST['company_id'];
			$account_tally_name	= $_POST['account_tally_name'];
			$bank_id				= $_POST['bank_id'];
			
			$sql="SELECT * FROM account_mst where 1 and account_name = '$account_name' ";
    		mysqli_query($con, $sql);
    		$rowaffect = mysqli_affected_rows($con);
    		if($rowaffect>0){
    		    echo "<script>alert('Error: Account Already available ...');</script>";
    		    echo '<script>window.location.href="account_mst.php?sub=add";</script>';
    		    exit();
    		}
    		
  			$sql = " INSERT INTO account_mst ( account_name, percentage, account_type, status, account_group, tds_flag, budget_name, budget_code, company_id, branch, address, account_number, isfc_code, deduction_from, retention_flag, account_tally_name, bank_id ) 
				   Values( '$account_name', '$percentage', '$account_type', '$status', '$account_group', '$tds_flag', '$budget_name', '$budget_code', '$company_id', '$branch','$address','$account_number','$isfc_code', '$deduction_from' , '$retention_flag', '$account_tally_name', '$bank_id' )";
					
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			//$help_code = $modulePath.'add.php';
			//include "../help_code.php";
			$pgname = "account_mst.php";
			include "../viewonly.php";
			$description = $account_name.', '. $percentage. ','. $account_type. ','. $budget_name . ', '. $budget_code;
		    $user_name= $_SESSION['user']; 
		    $affect = 'Added';
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
			echo "Account successful added";
			echo '<script>window.location.href="account_mst.php?sub=list";</script>';
		}
	
?>

    <section class="content-header">
        <h1>
            Account
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Account</a></li>
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
            <form class="form-horizontal" action="account_mst.php?sub=add" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      
					  
						<div class="form-group">	
							
							<label class="col-lg-2 control-label">Account</label>
							<div class="col-md-7">
								<input type="text" class="form-control" id="account_name" name="account_name" placeholder="" autocomplete="off" value="" onchange="getDuplicate(this.value);">
								<span id="getDuplicate" style="color:red;" ></span>
							</div>
						
						<span class="tds_flag_hide">	
							<label class="col-lg-2 control-label">Is This TDS Account? </label>
							<div class="col-lg-1" style="padding-top: 4px;">
								<input type="checkbox" name='tds_flag' id='tds_flag' value='Y' > 
							</div>
						</span>
						
						</div>
						
						 
						<div class="form-group">
							<label class="col-lg-2 control-label">Account Type</label>
							<div class="col-md-3">
								<select class="form-control" name="account_type" id="account_type" required onchange="getrevenue(this.value)" >
									<option value=""> Select </option>
								<!--	<option value="A"> Purchase</option>-->
									<option value="B"> Bank </option>
									<option value="D"> Deduction</option>
									<option value="E"> Expense</option>
									<option value="R"> Revenue</option>
									<option value="I"> Receipt</option>
									<option value="S"> Sales</option>
									
								</select>	
							</div>
							
							<label class="col-md-2 control-label">Company</label>
								<div class="col-md-5">
									
									<select class="form-control" name="company_id" id="company_id" onchange="gettallylinkac(this.value);getBankName(this.value);" >
										<option value=""> Select </option>
											<?php $sql = "select * from company order by comp_name ";
											
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['comp_id'];?>" >  <?php echo $r2['comp_name'];?></option>
											<?php } ?>
									</select>
								</div>
								
						</div>
						
					<span class="getrevenue_hide">	
					
						<span id="getBankName123">
						<div class="form-group" style="display:none">
							<label class="col-md-2 control-label">Bank Name</label>
							<div class="col-md-4">
								
								<select class="form-control" id="bank_id" name="bank_id" >
									<option value="">Select</option>	
										
								</select>
								
							</div>
						</div>
						</span>
						
			<span class="getrevenue_show">								
						<div class="form-group" >
							<label class="col-lg-2 control-label">Percentage</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="percentage" name="percentage" placeholder="" autocomplete="off" value="">
							</div>
							
							<label class="col-lg-2 control-label">Deduction from </label>
							<div class="col-lg-2" style="padding-top: 6px;">
							<input type="radio" name='deduction_from'  checked="checked"  value='V'> Vendor &nbsp;&nbsp;
							<input type="radio" name='deduction_from' value='A'> Accounts
							</div>
							
							<label class="col-lg-3 control-label">Is This Retention Account? </label>
							<div class="col-lg-1" style="padding-top: 4px;">
								<input type="checkbox" name='retention_flag' value='Y' > 
							</div>
							
						</div>	
			</span>
			
						<div class="form-group">
							<label class="col-lg-2 control-label">Branch</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="branch" name="branch" autocomplete="off" value="" >
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Address</label>
							<div class="col-md-6">
								<textarea class="form-control" rows="3" id="address" autocomplete="off" name="address"></textarea>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">IFSC Code</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="isfc_code" name="isfc_code" autocomplete="off" value="" >
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Account Number</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="account_number" name="account_number" autocomplete="off" value="" >
							</div>
						</div>
		</span>
						
		<span class="getrevenue_show">					
						<div class="form-group">
							<label class="col-lg-2 control-label">Account Tally Name</label>
							<div class="col-md-6">
								<input type="text" class="form-control" id="account_tally_name" name="account_tally_name" autocomplete="off" value="" >
							</div>
						</div>
		</span>
		
					<!--	<div class="form-group">
							<label for="user_category" class="control-label col-sm-2">Account Link to Tally*</label>
												
							<div class="col-md-2">
						<span id="gettallylinkac">	
								<select class="form-control select2" name="budget_code" id="budget_code" required="true" onchange="getaccount_data(this.value)" >
										<option value=""> Select </option>
										<option value="0"> NA </option>
								</select>
						</span>		
							</div>

							<span id="getaccount_data">
							</span>
													
						</div>
					-->	
						
						
						<div class="form-group" >
							<label class="col-lg-2 control-label">Status </label>
							<div class="col-lg-3" style="padding-top: 6px;">
								<input type="radio" name='status'  checked="checked"  value='Y'> Active &nbsp;&nbsp;
								<input type="radio" name='status' value='N'> Inactive
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
								<input class="btn btn-primary HideSave" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
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
 //echo $_POST['Save'];
 
	if(isset($_POST['Save'])){
			$id					= $_POST['id']; 
			$account_name		= $_POST['account_name'];
			$account_type		= $_POST['account_type'];
			$percentage			= $_POST['percentage'];
			$budget_name		= $_POST['budget_name'];
			$budget_code		= $_POST['budget_code'];
			$deduction_from		= $_POST['deduction_from'];
			$retention_flag		= $_POST['retention_flag'];
			$tds_flag			= $_POST['tds_flag'];
			$status				= $_POST['status'];
			$branch				= $_POST['branch'];
			$address		 	= $_POST['address'];
			$account_number		= $_POST['account_number'];
			$isfc_code			= $_POST['isfc_code'];
			$company_id			= $_POST['company_id'];
			$email				= $_POST['email'];
			$mobile				= $_POST['mobile'];
			$bank_id				= $_POST['bank_id'];
			
			$account_name_prev		= trim($_POST['account_name_prev']);
            if($account_name_prev !=$account_name){
			    $sql="SELECT * FROM account_mst where 1 and account_name ='$account_name' ";
        		mysqli_query($con, $sql);
        		$rowaffect = mysqli_affected_rows($con);
        		if($rowaffect>0){
        		    echo "<script>alert('Error: Account name Already available ...');</script>";
        		    echo "<script>window.location.href='account_mst.php?sub=edit&id=$id';</script>";
        		    exit();
        		}
			}
			
			$rtgs_format_available				= $_POST['rtgs_format_available'];
			$account_tally_name	= $_POST['account_tally_name'];
			
  			$sql="update account_mst set account_name ='$account_name',
							account_type 	= '$account_type',
							percentage		= '$percentage',
							deduction_from	= '$deduction_from',
							retention_flag	= '$retention_flag',
							budget_name		= '$budget_name',
							budget_code		= '$budget_code',
							tds_flag		= '$tds_flag',
							status			= '$status',
							branch			= '$branch',
							address			= '$address',
							account_number	= '$account_number',
							isfc_code		= '$isfc_code',
							company_id		= '$company_id',
							email			= '$email',
							mobile			= '$mobile',
							account_tally_name	= '$account_tally_name',
							rtgs_format_available = '$rtgs_format_available',
							bank_id				= '$bank_id'
					where id='$id' ";
			$query = mysqli_query($con, $sql);
			$error = mysqli_error($con);
			if(!empty($error)){
			    echo "<script>alert('Error: Already available ...');</script>";
			    echo "<script>window.location.href='account_mst.php?sub=edit&id=$$id';</script>";
			    exit();
			}

    //         $sql="SELECT * FROM account_mst where 1 and account_name = '$account_name' ";
    // 		mysqli_query($con, $sql);
    // 		$rowaffect = mysqli_affected_rows($con);
    // 		if($rowaffect>0){
    // 		    echo "Error: Already available ... ";
    // 		}
            
			$pgname = "account_mst.php";
			include "../viewonly.php";
			$description = $account_name.', '. $percentage. ','. $account_type. ','. $budget_name . ', '. $budget_code;
		    $user_name= $_SESSION['user']; 
		    $affect = 'Modified';
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
			$page					= $_POST['page']; 
			echo "<script>window.location.href='account_mst.php?sub=list&same_page=$page';</script>";
			
	}
		
		$page = $_GET['page'];
		$id = $_GET['id'];
		$sql="Select * from account_mst where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	
		
		$budget_code = $row['budget_code'];
		$budget_name = $row['budget_name'];
		$account_type = $row['account_type'];

?>

    <section class="content-header">
        <h1>
            Account
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Account</a></li>
			
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
            <form class="form-horizontal" action="account_mst.php?sub=edit&same_page=<?= $page ?>" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
					  <input type="hidden" name="page" value="<?= $page;?>">
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Account Name</label>
							<div class="col-md-7">
								<input type="text" class="form-control" id="account_name" autocomplete="off" name="account_name" placeholder="" value="<?php echo $row['account_name'];?>" >
								
								<input type="hidden"  name="account_name_prev"  value="<?php echo $row['account_name'];?>" >
								
							</div>
							
							<?php $tds_flag = $row['tds_flag']; ?>
					<?php if($account_type!= 'R' && $account_type!= 'B'){ ?>		
							<label class="col-lg-2 control-label">Is This TDS Accountt? </label>
							<div class="col-lg-1" style="padding-top: 4px;">
								<input type="checkbox" name='tds_flag' <?php if ($tds_flag=="Y") {echo "checked"; } ?> value='Y' > 
							</div>
					<?php } ?>		
						</div>
						
						
				<?php 
					$account_type = $row['account_type'];
				?>		
						<div class="form-group">
							<label class="col-lg-2 control-label">Account Type</label>
							<div class="col-md-3">
								<select class="form-control" name="account_type" id="account_type" required onchange="getrevenue(this.value)" >
									<option value=""> Select </option>
									<!--<option value="A" <?php echo ($row['account_type'] == 'A')?'selected="selected"':'';?> > Purchase</option>-->
									<option value="B" <?php echo ($row['account_type'] == 'B')?'selected="selected"':'';?> > Bank </option>
									<option value="D" <?php echo ($row['account_type'] == 'D')?'selected="selected"':'';?> > Deduction</option>
									<option value="E" <?php echo ($row['account_type'] == 'E')?'selected="selected"':'';?>> Expense</option>
									<option value="R"  <?php echo ($row['account_type'] == 'R')?'selected="selected"':'';?>> Revenue</option>
									<option value="I"  <?php echo ($row['account_type'] == 'I')?'selected="selected"':'';?>> Receipt</option>
									<option value="S"  <?php echo ($row['account_type'] == 'S')?'selected="selected"':'';?>> Sales</option>
							
								</select>	
							</div>
							
					<?php $company_id = $row['company_id']; ?>
					
							<label class="col-md-2 control-label">Company</label>
								<div class="col-md-5">
									
									<select class="form-control" name="company_id" id="company_id" onchange="gettallylinkac(this.value);" >
										<option value=""> Select </option>
											<?php $sql = "select * from company order by comp_name ";
											
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_id'] == $r2['comp_id'])?'selected="selected"':'';?>  >  <?php echo $r2['comp_name'];?></option>
											<?php } ?>
									</select>
								</div>
							
						</div>
						
				<?php if($account_type!= 'R'){ ?>		
						<div class="form-group" style="display:none">
							<label class="col-md-2 control-label">Bank Name</label>
							<div class="col-md-4">
								
										<select class="form-control" id="bank_id" name="bank_id" >
											<option value="">Select</option>	
										<?php
											$sql="SELECT * FROM account_mst where account_type = 'B'  and company_id = '$company_id'  ORDER BY account_name ASC";
											$q2 = mysqli_query($con, $sql);
											echo mysqli_error($con);
											while($r2 = mysqli_fetch_array($q2)){
										?>
											<option value="<?php echo $r2['id']?>" <?php echo ($row['bank_id'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['account_name'] ?></option>
											<?php } ?>
										</select>
								
							</div>
						</div>
			<?php if($account_type!='B'){	?>
						<div class="form-group" >
							<label class="col-lg-2 control-label">Percentage</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="percentage" name="percentage" placeholder="" autocomplete="off" value="<?php echo $row['percentage']; ?>">
							</div>
							
							<label class="col-lg-2 control-label">Deduction from </label>
							<div class="col-lg-2" style="padding-top: 6px;">
							<input type="radio" name='deduction_from' <?php $deduction_from=$row['deduction_from']; if ($deduction_from=="V") echo "checked";?> value='V'> Vendor &nbsp;&nbsp;
							<input type="radio" name='deduction_from' <?php $deduction_from=$row['deduction_from']; if ($deduction_from=="A") echo "checked";?> value='A'> Accounts
							</div>
							
					<?php $retention_flag = $row['retention_flag'] ;?>		
					
							<label class="col-lg-3 control-label">Is This Retention Account? </label>
							<div class="col-lg-1" style="padding-top: 4px;">
								<input type="checkbox" name='retention_flag' <?php if ($retention_flag=="Y") {echo "checked"; } ?> value='Y' > 
							</div>
							
						</div>	
			<?php } ?>
			
				<?php if($account_type== 'B'){ ?>
						<div class="form-group">
							<label class="col-lg-2 control-label">Branch</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="branch" name="branch" autocomplete="off" value="<?php echo $row['branch'];?>" >
							</div>
							
							<?php $rtgs_format_available = $row['rtgs_format_available']; ?>
							
							<label class="col-lg-2 control-label">RTGS Format Available? </label>
							<div class="col-lg-3" style="padding-top: 4px;">
								<input type="checkbox" name='rtgs_format_available' <?php if ($rtgs_format_available=="Y") {echo "checked"; } ?> value='Y' > 
							</div>
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Address</label>
							<div class="col-md-6">
								<textarea class="form-control" rows="3" id="address" autocomplete="off" name="address"><?php echo $row['address'];?></textarea>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">IFSC Code</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="isfc_code" name="isfc_code" autocomplete="off" value="<?php echo $row['isfc_code'];?>" >
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Account Number</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="account_number" name="account_number" autocomplete="off" value="<?php echo $row['account_number'];?>" >
							</div>
						</div>
						
						<div class="form-group">
								<label class="col-lg-2 control-label">Contact Email</label>
								<div class="col-md-6">
									<input class="form-control" id="email" autocomplete="off" name="email" value="<?php echo $row['email'];?>" >
								</div>
								
								<label class="col-lg-2 control-label">Contact Mobile</label>
								<div class="col-md-2">
									<input class="form-control" id="mobile" autocomplete="off" name="mobile" value="<?php echo $row['mobile'];?>" >
								</div>
						</div>
				<?php } ?>
					
						
				<?php //if($account_type=='E'){ ?>		
				<!--		<div class="form-group">
							<label for="user_category" class="control-label col-sm-2">Account Link to Tally*</label>
							<div class="col-md-2">
						<span id="gettallylinkac">		
								<select class="form-control" name="budget_code" id="budget_code" required="true" onchange="getaccount_data(this.value)" >
								
									<option value=""> Select </option>
								<?php $sql = "select distinct(budget_code) as budget_code from sma_budget where 1  order by budget_code "; //and project = '$company_id'
										$q22 	= mysqli_query($con, $sql);
									while($r22 = mysqli_fetch_array($q22)){ ?>
									<option value="<?php echo $r22['budget_code'];?>"  <?php echo ($budget_code == $r22['budget_code'])?'selected="selected"':'';?> ><?php echo $r22['budget_code'];?></option>
								<?php } ?>
								</select>
						</span>		
							</div>
						<?php
						$sql = "select distinct(budget_name) as budget_name , budget_code, budget_head from sma_budget where 1 and budget_code = '$budget_code' ";
						$q22 	= mysqli_query($con, $sql);
						$r22 = mysqli_fetch_array($q22);
						//$budget_name = $r22['budget_name'];
						$budget_head = $r22['budget_head'];
						
						$sql = "select * from sma_budget_name where 1 and id = '$budget_name' ";
						$q22 	= mysqli_query($con, $sql);
						$r22 = mysqli_fetch_array($q22);
						$budget_name_id = $r22['id'];
						$budget_name 	= $r22['name'];
						?>
							<span id="getaccount_data">
								<label class=" col-md-1 control-label">CC Group</label>
								<div class="col-md-2">
								<input type="hidden" class="form-control" name="budget_name" id="budget_name" readonly value="<?php echo $budget_name_id;?>" >
								
								<input type="text" class="form-control"  readonly value="<?php echo $budget_name;?>" >
								</div>
								
								<label class="control-label col-md-1">CC Sub Group</label>
								<div class="col-md-4">
								<input type="text" class="form-control" name="budget_head" id="budget_head" readonly value="<?php echo $budget_head;?>" >
								</div>
							</span>
													
						</div>
					-->	
				<?php } ?>
				
				<span id="getrevenue_show">	
					<?php if($account_type=='R' || $account_type=='S'){
								$account_tally_name	= $row['account_tally_name'];
					?>		
						<div class="form-group">
							<label class="col-lg-2 control-label">Account Tally Name</label>
							<div class="col-md-6">
								<input type="text" class="form-control" id="account_tally_name" name="account_tally_name" autocomplete="off" value="<?php echo $account_tally_name;?>" >
							</div>
						</div>
					<?php } ?>	
				</span>
						<?php 
								$status = $row['status'];
						?>
						<div class="form-group" >
							<label class="col-lg-2 control-label">Status </label>
							<div class="col-lg-3" style="padding-top: 6px;">
								<input type="radio" name='status' <?php if ($status=="Y") {echo "checked"; } ?> value='Y' > Active &nbsp;&nbsp;
								<input type="radio" name='status' <?php if ($status=="N") {echo "checked"; } ?> value='N' > Inactive
							</div>
						</div>	
						
   						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; 
								//Mrunmayee started
								$account_type= $row['account_type'];
								$sq2 = "SELECT COUNT(*) as total FROM `tally_journal_entry` where account_id  = '$did'";
								$q2  = mysqli_query($con, $sq2);
				
								$r2  = mysqli_fetch_assoc($q2);
								$mycount = $r2['total'];
								if ($mycount <= 0) {?>
								<a href="<?php echo $baseurl."setting/account_mst.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a>
								<?php }  //Mrunmayee ended?>
								
							</div>
							<?php $baseurl1 = $baseurl.$modulePath.'&same_page='.$page;?>
							<div class="col-sm-6 text-right">
								<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Cancel</a>
								<span>&nbsp;&nbsp;</span>
								<input class="btn btn-primary HideSave" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
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


<script>

	function getproject(id){
		
        var sub    = 'sub1';
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub1:sub},function(result){
		      $('#getproject').html(result);
		});

	}

	function getbudget(id){
		
        var sub    = 'sub2';

//var project = document.getElementById("companY").value;
//	var account_year = document.getElementById("account_Year").value;
//alert(project);
	
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub2:sub},function(result){
		      $('#getbudgethead').html(result);
		});

	}

	function getavailbudget(id){
		
        var sub    = 'sub22';
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub22:sub},function(result){
		      $('#getavailbudget').html(result);
		});

	}
	
	function getaccount_data(id){
		var sub    = 'sub1';
//alert(sub);	
		var strURL = "account_func.php";
		$.post(strURL,{id:id,sub1:sub},function(result){
		      $('#getaccount_data').html(result);
		});
		
	}



	function gettallylinkac(id){
		var sub    = 'sub2';
//alert(sub);	
		var strURL = "account_func.php";
		$.post(strURL,{id:id,sub2:sub},function(result){
		      $('#gettallylinkac').html(result);
		});
		
	}

	function getrevenue(id){
//alert(id);		
		if(id=='R' || id=='S' || id=='I'){
//			tds_flag
			$('.getrevenue_show').show();
			$('.getrevenue_hide').hide();
			$('.tds_flag_hide').hide();
			
		}	
		else {
			
			$('.getrevenue_show').hide();
			$('.getrevenue_hide').show();
			$('.tds_flag_hide').show();
			
		}
		if(id=='D' ){
		   $('.tds_flag_hide').show();
		   $('.getrevenue_show').show();
		}
		
		if(id=='B' ){
		   $('.tds_flag_hide').hide();
		}    
		
	}	
	
	
	function getBankName(id){
		var sub    = 'sub3';
//alert(sub);	
		var strURL = "account_func.php";
		$.post(strURL,{id:id,sub3:sub},function(result){
		      $('#getBankName').html(result);
		});
		
	}


    function getDuplicate(id){
		var sub    = 'sub4';
//alert(sub);
        $('.HideSave').show();
        $('#getDuplicate').html('');
		var strURL = "account_func.php";
		$.post(strURL,{id:id,sub4:sub},function(result){
		      $('#getDuplicate').html(result);
		      
		});
		
	}
	
</script>

</body>
</html>
