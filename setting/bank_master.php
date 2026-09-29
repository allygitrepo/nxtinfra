<?php 

include("../header.php");
$modulePath = "setting/bank_master.php?sub=list";

$pgname = $modulePath;
include("../viewonly.php");

?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
         <?= $sub_menu;?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active"> <?= $sub_menu;?></li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
			<?php	
				$targetpage = "bank_master.php?sub=list"; 
				$limit = 10; 
				$start = 0;	
				$comid  = $_SESSION['comid'];
				
				
				if ($_POST['company_id']  or $_POST['search']){
					$_SESSION['company_id'] 	    = $_POST['company_id'];
					$_SESSION['bank_account_no'] 	= $_POST['bank_account_no'];
					$_SESSION['search'] 		    = $_POST['search'];
					$_SESSION['reset']              ='';
				}
				
				if ($_SESSION['company_id'] or $_SESSION['search']){
					$company_id 	    = $_SESSION['company_id'];
					$search 		    = $_SESSION['search'];
				}
				
				if (!empty($_GET['reset']) || !empty($_SESSION['reset'])){
					$_SESSION['company_id'] 	= '';
					$_SESSION['search'] 		= '';
					$company_id 	= $_SESSION['company_id'];
					$search 		= $_SESSION['search'];
				}
		
			?>
			<form class="form-horizontal" action="bank_master.php?sub=list" method="post">
						<div class="form-group">
								<div class="col-md-5">
									<label class=" control-label">Company</label>
									<select class="form-control" name="company_id" id="company_id" >
										<option value=""> Select </option>
											<?php $sql = "select * from company order by comp_name ";
											
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['comp_id'];?>" >  <?php echo $r2['comp_name'];?></option>
											<?php } ?>
									</select>
								</div>
							
							<div class="col-md-3">
								<label class="control-label">Text</label>
								<input type="text" class="form-control " name="search" id="search" value="<?= $search; ?>" >
										
							</div>
								
							<div class="col-md-2">
                                <label class="control-label">&nbsp;</label><BR>	
								<input class="btn btn-success" type="submit" value="Search" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="bank_master.php?sub=list&reset=1" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
							</div>
							
						</div>
											   
				</form>
				
              <h3 class="box-title">List of <?= $sub_menu;?></h3>
				<span class="pull-right"><a href="bank_master.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>Create </a></span>
				
				<span class="pull-right" >&nbsp;&nbsp;&nbsp;&nbsp;</span>
                
				<!--<span class="pull-right"><a href="account_export.php?sub=exp" name="btnAdd" class="btn btn-primary"><i class="splashy-document_letter_add"></i>Export </a></span>&nbsp;&nbsp;-->
				
				
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable123" class="table table-bordered table-striped">

	<thead>
		<tr>
			
			<th>Company</th>
			<th>Bank Name</th>
			<th>Bank Account No.</th>
			<th>Status</th>
			<th style="text-align:right;">Action</th>	
			
		</tr>
	</thead>
<tbody>
<?php
	
	
		$sql="SELECT * from sma_bank_master where 1  "; //and status != 'N'
		$query="SELECT count(*) as num from sma_bank_master where 1  "; //and status != 'N'
		
		
		if(!empty($company_id)){
			$sql   .= " and company_id = '$company_id' ";
			$query .= " and company_id = '$company_id' ";
		}		
	
	
		if(!empty($search)){
			$sql 	.= " and (bank_name like '%$search%' || bank_account_no like '%$search%' )";
			$query 	.= " and (bank_name like '%$search%' || bank_account_no like '%$search%' ) ";
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
			
		$sql .= ' order by bank_name asc ';
		
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
		
		$status		    = $row['status'];
		
		$company_id     = $row['company_id'];
		$sql = "select * from company where comp_id = '$company_id' ";	
		$q2  = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($q2);
		$comp_code = $r2['comp_code'];
		
		if($status=='N'){
			$status = 'Inactive';
		}
		else {
			$status = 'Active';
		}	
?>

	<tr>

		<td width="10%"><?php echo $comp_code;?></td>
		<td width="25%"><?php echo $row['bank_name'];?></td>
		<td width="25%"><?php echo $row['bank_account_no'];?></td>
		<td width="10%"><?php echo $status;?></td>
		
		<td width="10%" style="text-align:right;">
		<a href="bank_master.php?sub=edit&id=<?= $row['id'];?>&page=<?= $page;?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
<!--<a href="bank_master.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
		
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
		
		$sql="update sma_bank_master set status = 'N' where id ='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		
			$pgname = "bank_master.php";
			include "../viewonly.php";
			$description = $bank_name.', '. $bank_account_no. ','. $budget_name . ', '. $budget_code;
		    $user_name= $_SESSION['user']; 
		    $affect = 'Deleted';
			
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
        echo '<script>window.location.href="bank_master.php?sub=list";</script>';
	}
?>

<?php if($_GET['sub'] == 'add'){
?>

<?php
 
	if(isset($_POST['Save'])){
	    
			$bank_name		    = $_POST['bank_name'];
			$company_id			= $_POST['company_id'];
			$bank_account_no	= $_POST['bank_account_no'];
			
			$sql="SELECT * FROM sma_bank_master where 1 and bank_name = '$bank_name' ";
    		mysqli_query($con, $sql);
    		$rowaffect = mysqli_affected_rows($con);
    		if($rowaffect>0){
    		    echo "<script>alert('Error: Account Already available ...');</script>";
    		    echo '<script>window.location.href="bank_master.php?sub=add";</script>';
    		    exit();
    		}
    		
  			$sql = " INSERT INTO sma_bank_master ( bank_name, company_id, bank_account_no, status ) 
				        Values( '$bank_name', '$company_id',  '$bank_account_no', 'Y' )";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
		
			$pgname = "bank_master.php";
			include "../viewonly.php";
			$description = $bank_name.', '. $company_id. ','. $bank_account_no. ','. $budget_name . ', '. $budget_code;
		    $user_name= $_SESSION['user']; 
		    $affect = 'Added';
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
			echo "Bank successful added";
			echo '<script>window.location.href="bank_master.php?sub=list";</script>';
		}
	
?>

    <section class="content-header">
        <h1>
            <?= $sub_menu; ?>
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>"><?= $sub_menu; ?></a></li>
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
            <form class="form-horizontal" action="bank_master.php?sub=add" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      
					    <div class="form-group">
							
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
						
						<div class="form-group">	
							
							<label class="col-lg-2 control-label">Bank Name</label>
							<div class="col-md-7">
								<input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="" autocomplete="off" value="" onchange="getDuplicate(this.value);">
								<span id="getDuplicate" style="color:red;" ></span>
							</div>
						
						</div>
								
						<div class="form-group">
							<label class="col-lg-2 control-label">Bank Account No.</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="bank_account_no" name="bank_account_no" autocomplete="off" value="" >
							</div>
						</div>
		
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
			$bank_name		    = $_POST['bank_name'];
			$company_id			= $_POST['company_id'];
			$bank_account_no	= $_POST['bank_account_no'];
			$status         	= $_POST['status'];
			
			$bank_name_prev		= trim($_POST['bank_name_prev']);
            if($bank_name_prev !=$bank_name){
			    $sql="SELECT * FROM sma_bank_master where 1 and bank_name ='$bank_name' ";
        		mysqli_query($con, $sql);
        		$rowaffect = mysqli_affected_rows($con);
        		if($rowaffect>0){
        		    echo "<script>alert('Error: Account name Already available ...');</script>";
        		    echo "<script>window.location.href='bank_master.php?sub=edit&id=$id';</script>";
        		    exit();
        		}
			}
			
  			$sql="update sma_bank_master set bank_name = '$bank_name',
							company_id		= '$company_id',
							bank_account_no	= '$bank_account_no',
							status          = '$status'
					where id='$id' ";
			$query = mysqli_query($con, $sql);
			$error = mysqli_error($con);
			
			$pgname = "bank_master.php";
			include "../viewonly.php";
			$description = $bank_name.', '. $company_id. ','. $bank_account_no. ','. $budget_name . ', '. $budget_code;
		    $user_name= $_SESSION['user']; 
		    $affect = 'Modified';
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
			$page					= $_POST['page']; 
			echo "<script>window.location.href='bank_master.php?sub=list&same_page=$page';</script>";
			
	}
		
		$page = $_GET['page'];
		$id = $_GET['id'];
		$sql="Select * from sma_bank_master where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	
		
?>

    <section class="content-header">
        <h1>
            <?= $sub_menu;?>
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>"><?= $sub_menu;?></a></li>
			
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
            <form class="form-horizontal" action="bank_master.php?sub=edit&same_page=<?= $page ?>" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
					  <input type="hidden" name="page" value="<?= $page;?>">
								
					<?php $company_id = $row['company_id']; ?>
					    <div class="form-group">
							<label class="col-md-2 control-label">Company</label>
								<div class="col-md-5">
									
									<select class="form-control" name="company_id" id="company_id"  >
										<option value=""> Select </option>
											<?php $sql = "select * from company order by comp_name ";
											
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_id'] == $r2['comp_id'])?'selected="selected"':'';?>  >  <?php echo $r2['comp_name'];?></option>
											<?php } ?>
									</select>
								</div>
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Bank Name</label>
							<div class="col-md-7">
								<input type="text" class="form-control" id="bank_name" autocomplete="off" name="bank_name" placeholder="" value="<?php echo $row['bank_name'];?>" >
								
								<input type="hidden"  name="bank_name_prev"  value="<?php echo $row['bank_name'];?>" >
								
							</div>
							
						</div>	
					
						<div class="form-group">
							<label class="col-lg-2 control-label">Bank Account Number</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="bank_account_no" name="bank_account_no" autocomplete="off" value="<?php echo $row['bank_account_no'];?>" >
							</div>
						</div>
						
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
								$bank_account_no= $row['bank_account_no'];
								$sq2 = "SELECT COUNT(*) as total FROM `tally_journal_entry` where account_id  = '$did'";
								$q2  = mysqli_query($con, $sq2);
				
								$r2  = mysqli_fetch_assoc($q2);
								$mycount = $r2['total'];
								if ($mycount <= 0) {?>
								<a href="<?php echo $baseurl."setting/bank_master.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a>
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

	function getaccount_data(id){
		var sub    = 'sub1';
//alert(sub);	
		var strURL = "account_func.php";
		$.post(strURL,{id:id,sub1:sub},function(result){
		      $('#getaccount_data').html(result);
		});
		
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
