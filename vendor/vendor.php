<?php

include("../header.php");
$modulePath = "vendor/vendor.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
			
      <h1>
        Supplier
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Supplier</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
			<?php
				$targetpage = "vendor.php?sub=list"; 
				$limit = 25; 
				$start = 0;	
				if ($_POST['search'] ){
					$_SESSION['search'] = $_POST['search'];				
				}
				
				if ($_SESSION['search'] ){
					$search = $_SESSION['search'];
					$_SESSION['reset']='';
				}
				
				if (!empty($_GET['reset']) || !empty($_SESSION['reset'])){
					$_SESSION['search'] = '';
					$search = $_SESSION['search'];
				}
			?>	
				<form class="form-horizontal" action="vendor.php?sub=list" method="post">
                      
						<div class="form-group">
								
								<label class="col-lg-2 control-label">Search Text</label>
								<div class="col-md-3">
									<input type="text" class="form-control " name="search" id="search" value="<?= $search; ?>" >
										
								</div>
								
							<div class="col-xs-3">
                                		
							<input class="btn btn-success" type="submit" value="Search" name="Save">&nbsp;&nbsp;&nbsp;
							<a href="vendor.php?sub=list&reset=1" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
							</div>
							
						</div>
				
				</form>
              <h3 class="box-title">List of Supplier</h3>
			  
			
                <span class="pull-right"><a href="vendor.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create </a></span>
			
			
				<span class="pull-right">
					<a href="vendor_export.php?sub=list" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Export</a>&nbsp;&nbsp;&nbsp;&nbsp;
				</span>

            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>Supplier</th>
			<th>Type</th>
			<th>City</th>
			<th>State</th>
			<th>GST No.</th>
			<th>Email</th>
			
			<th style="text-align:right;">Action</th>
			
		</tr>
	</thead>
<tbody>
<?php
	$modulePath1 = "vendor/";
	$sql="SELECT * from sma_party_mst where 1";
	if(!empty($search)){
		$sql .=" and party_name like '%$search%' ";
	}	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
				$total_pages = mysqli_affected_rows($con);
				$stages = 3;
					//$page = mysqli_real_escape_string($_GET['page']);
					$page = ($_GET['page']);

					if($_GET['same_page']){
						$page = $_GET['same_page'];
					}

					if($page){
						$start = ($page - 1) * $limit; 
						if($start < 0){
							$start = 0;
						}	
					}
					else{
						$start = 0;	
					}	
			
		$sql .= ' order by party_name asc ';
		
//		$sql .= " LIMIT $start, $limit ";

//echo $sql;

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

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){
	
		$city = $row['party_city'];
		$sql = "select * from cities where id = '$city' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$city = $r2['city_name'];
		
		$state = $row['party_state'];
		$sql = "select * from states where id = '$state' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$state = $r2['state_name'];

		$category = $row['party_category'];
		$sql = "select * from sma_categories where id = '$category' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$category = $r2['name'];
										
		$party_type 					= $row['party_type'];
		$sql = "select * from sma_type where id = '$party_type' ";
		$q2  = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$party_type = $r2['type'];
		
		$party_gst_number 				= $row['party_gst_number'];
		$party_pan_number 				= $row['party_pan_number'];
		$party_email	 				= $row['party_email'];
			
		$baseurl1 = $baseurl.$modulePath1.'vendor.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "vendor.php?sub=edit&id=". $row['id']?>&page=<?= $page;?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>&page=<?= $page;?>'">
		<td width="20%"><?php echo $row['party_name'];?></td>
		<td width="15%"><?php echo $party_type;?></td>
		<td width="15%"><?php echo $city;?></td>
		<td width="15%"><?php echo $state;?></td>
		
		<td width="15%"><?php echo $party_gst_number;?></td>
		<td width="15%"><?php echo $party_email;?></td>
		
		<td width="10%" style="text-align:right;">
		<a href="vendor.php?sub=edit&id=<?php echo $row['id'];?>&page=<?= $page;?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
<!--		<a href="vendor.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
		
		</td>
    </tr>
	</a>
	<?php }?>
</tbody> 
</table>
<?php
/*   $end  =$start+$limit;
  $begin=$start+1;
  
  if ($end>$total_pages){ $end=$total_pages;}
  echo $paginate;
  echo 'Showing ' . $begin .' to ' . $end . ' of ' . $total_pages . ' entries ';
   */
?>

	</div>
    </div>
  </div>	
</div>
</section>  

    <?php }?>


<?php  
	if($_GET['sub'] == 'delete'){ 
        $id = $_GET['id'];
		
			$sql	="Select * from sma_party_mst where id ='$id'";
			$query 	= mysqli_query($con, $sql);
			$row 	= mysqli_fetch_array($query);
			$party_name 			= $row['party_name'];
			
			$company_id 	= '';
			$pgname 		= "vendor.php";
			include "../viewonly.php";
			$description 	= $party_name;
		    $affect 		= 'Deleted';
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
		$sql="delete from sma_party_mst where id='$id' and id not in (SELECT supplier_name FROM `sma_approval_details` where vendor_selected = 'Y' and supplier_name = '$id' ) ";
        $query1 = mysqli_query($con, $sql);
		$row_affected = mysqli_affected_rows($con);
        if($row_affected ==0 ){
			echo '<script>alert("Supplier exist on transactions...could not delete");</script>';
		}
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="vendor.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){
?>

<?php

	if(isset($_POST['Save'])){
			$party_type                     = $_POST['party_type'];
			$party_name						= $_POST['party_name'];
			$party_category 				= $_POST['party_category'];
			$party_contact_person_name 		= $_POST['party_contact_person_name'];
			$party_designation				= $_POST['party_designation'];
			$party_address_1 				= $_POST['party_address_1'];
//			$party_address_2 				= $_POST['party_address_2'];
//			$party_address_3 				= $_POST['party_address_3'];
			$party_city 					= $_POST['party_city'];
			$party_state 					= $_POST['party_state'];
			$party_pincode 					= $_POST['party_pincode'];
			$party_area 					= $_POST['party_area'];
			$party_country 					= $_POST['party_country'];
			$party_phone 					= $_POST['party_phone'];
			$party_phone1 					= $_POST['party_phone1'];
			$party_phone2 					= $_POST['party_phone2'];
			$party_mobile 					= $_POST['party_mobile'];
			$party_mobile1 					= $_POST['party_mobile1'];
			$party_mobile2 					= $_POST['party_mobile2'];			
			$party_email 					= $_POST['party_email'];
			$party_gst_number 				= $_POST['party_gst_number'];
			$party_pan_number 				= $_POST['party_pan_number'];
			$party_msme_number				= $_POST['party_msme_number'];
			$party_websites					= $_POST['party_websites'];
			$party_electricity_flag         = $_POST['party_electricity_flag'];
			$msme_flag                      = $_POST['msme_flag'];
			$party_beneficiary_name 		= $_POST['party_beneficiary_name'];
			$party_bank_name 				= $_POST['party_bank_name'];
			$party_bank_account_type 		= $_POST['party_bank_account_type'];
			$party_bank_address 			= $_POST['party_bank_address'];
			$party_bank_account_no 			= $_POST['party_bank_account_no'];
			$party_bank_ifsc_code 			= $_POST['party_bank_ifsc_code'];
// 			$ldc_cert_no 					= $_POST['ldc_cert_no'];
// 			$ldc_rate 						= $_POST['ldc_rate'];
// 			$limit_as_per_ldc 				= $_POST['limit_as_per_ldc'];
			
			
			$tax_category = $_POST['tax_category'];
			$tally_account_name 		= $_POST['tally_account_name'];
			$party_nature_business		= $_POST['party_nature_business'];
			
			$status = 'Draft';
			
			$userid   	    = $_SESSION['usrid'];
			
			$party_kyc   	= 'N';
			
  			$sql="insert into sma_party_mst ( party_type, party_name, party_category, party_contact_person_name, party_designation, party_address_1, party_city, party_state, party_pincode, party_area, party_country, party_phone, party_phone1, party_phone2, party_mobile, party_mobile1, party_mobile2, party_email, party_gst_number, party_pan_number, party_msme_number, party_bank_name, party_bank_account_type, party_bank_address, party_bank_account_no, party_bank_ifsc_code, party_beneficiary_name, tax_category,tally_account_name, status, draft_by , party_nature_business, party_kyc,  party_electricity_flag, msme_flag ) 
			Values( '$party_type', '$party_name', '$party_category', '$party_contact_person_name', 
			'$party_designation', '$party_address_1', '$party_city', '$party_state', '$party_pincode', '$party_area', '$party_country', '$party_phone', '$party_phone1', '$party_phone2', '$party_mobile', '$party_mobile1', '$party_mobile2', '$party_email', '$party_gst_number', 
			'$party_pan_number', '$party_msme_number', '$party_bank_name', '$party_bank_account_type', '$party_bank_address', '$party_bank_account_no', '$party_bank_ifsc_code', '$party_beneficiary_name', '$tax_category', '$tally_account_name', '$status' , '$userid', '$party_nature_business', '$party_kyc', '$party_electricity_flag', '$msme_flag' )";

			$query=mysqli_query($con, $sql);
			$party_id= mysqli_insert_id($con);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}

				//$userid   	    = $_SESSION['usrid'];
				$sql  = "insert into kyc_upd_log (create_by, created_on, party_id, party_kyc, status) values ( '$userid' , now(), '$party_id', '$party_kyc', 'Draft' )";
				$query= mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
				
			$company_id 	= '';
			$pgname 		= "vendor.php";
			include "../viewonly.php";
			$description 	= $party_name. ','. $party_category.','.$party_type;
		    $affect 		= 'Added';
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
			//echo "Supplier successful added";
			echo '<script>window.location.href="vendor.php?sub=list";</script>';
			exit();
			
		}
	
?>

    <section class="content-header">
        <h1>
            Supplier
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Supplier</a></li>
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
            <form class="form-horizontal" action="vendor.php?sub=add" enctype="multipart/form-data" method="post">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                	  
				<ul class="nav nav-tabs">
				  <li class="active"><a href="#tab_1" data-toggle="tab">Contact Details</a></li>
				  <li><a href="#tab_2" data-toggle="tab">Bank Details</a></li>
				</ul>
				
				<div class="tab-content">
					<div class="tab-pane active" id="tab_1">
						
						<div class="form-group">
							<div class="col-md-2">
							<label class=" control-label">Type **</label>
								
								<select class="form-control" name="party_type" id="party_type" required="true" >
									<option value="0"> Select </option>
										<?php $sql = "select * from sma_type order by type ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" ><?php echo $r2['type'];?></option>
										<?php } ?>
								</select>
							</div>

							<div class="col-md-3">
							<label class=" control-label">Supplier Category ** </label>
								<select class="form-control" name="party_category" id="party_category" required="true" >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_categories order by name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($party_category == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['name'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<div class="col-md-5">
								<label class="control-label">Supplier Name **</label>
								<input type="text" class="form-control" id="party_name" name="party_name" placeholder="" value="<?php echo $row['party_name'];?>" required >
							</div>
			
			                <div class="col-md-2">
								<label class=" control-label">Electricity Vender</label><br>
								<input type="checkbox" id="party_electricity_flag" name="party_electricity_flag"  value="Y" >  &nbsp;
							</div>
							
						</div>
						
						<div class="form-group">
							
							<div class="col-md-3">
								<label class=" control-label">Tax Category</label><br>
								<input type="radio" id="tax_category" name="tax_category" checked value="R" > Registered  &nbsp;
								<input type="radio" id="tax_category" name="tax_category" value="U" > Unregistered  &nbsp;
							</div>
						
						    
								<div class="col-md-2">
									<label class=" control-label">**</label><br>
									<input type="radio" id="msme_flag" name="msme_flag" CHECKED value="Y" > MSME &nbsp;
									<input type="radio" id="msme_flag" name="msme_flag" value="N" > NON MSME &nbsp;
								</div>
								
							<div class="col-md-4">
								<label class="control-label">Tally Account Name</label>
								<input type="text" class="form-control" id="tally_account_name" name="tally_account_name" placeholder="" value="" >
							</div>
							
							<div class="col-md-3">
									<label class="control-label">Nature of Business **</label>
									<input type="text" class="form-control" id="party_nature_business" name="party_nature_business"  value="" >
							</div>
								
						</div>
						
						<div class="form-group">
							<div class="col-md-4">
								<label class=" control-label">Contact Person Name</label>
								<input type="text" class="form-control" id="party_contact_person_name" name="party_contact_person_name" placeholder="" value="<?php echo $row['party_contact_person_name'];?>" >
							</div>
						
							<div class="col-md-3">
								<label class=" control-label">Designation</label>
								<input type="text" class="form-control" id="party_designation" name="party_designation" placeholder="" value="" >
							</div>
							
							<div class="col-md-4">
								<label class="control-label">Email</label>
								<input type="text" class="form-control" id="party_email" name="party_email" placeholder="" value="<?php echo $row['party_email'];?>" >
							</div>
							
						</div>
							
						<div class="form-group">
							<div class="col-md-6">
								<label class="control-label">Address </label>
								<textarea rows="2" cols="80" class="form-control" id="party_address_1" name="party_address_1" placeholder="" ><?php echo $row['party_address_1'];?> </textarea>
							</div>
						
							<div class="col-md-2">
								<label class="control-label">Mobile-1</label>
								<input type="text" class="form-control" id="party_mobile" name="party_mobile" placeholder="" value="" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Mobile-2</label>
								<input type="text" class="form-control" id="party_mobile1" name="party_mobile1" placeholder="" value="" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Mobile-3</label>
								<input type="text" class="form-control" id="party_mobile2" name="party_mobile2" placeholder="" value="" >
							</div>
							
						</div>
						
						<div class="form-group">
							<div class="col-md-2">
								<label class="control-label">State</label>
								<select class="form-control" name="party_state" id="party_state" onchange="getcity(this.value)" >
									<option value=""> Select </option>
										<?php $sql = "select * from states order by state_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($row['party_state'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['state_name'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<div class="col-md-2">
								<label class="control-label">City</label>
								<span id="getcity">
									<select class="form-control" name="party_city" id="party_city" >
										<option value=""> Select </option>
											<?php $sql = "select * from cities order by city_name ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['id'];?>"  <?php echo ($row['party_city'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['city_name'];?></option>
											<?php } ?>
									</select>
								</span>
								
							</div>
						
							<div class="col-md-2">
								<label class="control-label">Pincode</label>
								<input type="text" class="form-control" id="party_pincode" name="party_pincode" placeholder="" value="<?php echo $row['party_pincode'];?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Phone</label>
								<input type="text" class="form-control" id="party_phone" name="party_phone" placeholder="" value="" >
							</div>
							
							
							<div class="col-md-2">
								<label class="control-label">Phone-2</label>
								<input type="text" class="form-control" id="party_phone1" name="party_phone1" placeholder="" value="" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Phone-3</label>
								<input type="text" class="form-control" id="party_phone2" name="party_phone2" placeholder="" value="" >
							</div>
							
						</div>
						
						<div class="form-group">
							
							<div class="col-md-4">
								<label class="control-label">Area</label>
								<input type="text" class="form-control" id="party_area" name="party_area" placeholder="" value="<?php echo $row['party_area'];?>" >
							</div>
						
							<div class="col-md-2">
								<label class="control-label">Country</label>
								<input type="text" class="form-control" id="party_country" name="party_country" placeholder="" value="<?php echo $row['party_country'];?>" >
							</div>
							
							<div class="col-md-6">
								<label class="control-label">Website</label>
								<input type="text" class="form-control" id="party_websites" name="party_websites" placeholder="" value="<?php echo $row['party_websites'];?>" >
							</div>
						
						</div>
						
						<div class="form-group">
						
							<div class="col-md-3">
								<label class=" control-label">GST Number</label>
								<input type="text" class="form-control" id="party_gst_number" name="party_gst_number" placeholder="" value="<?php echo $row['party_gst_number'];?>" >
							</div>
							
							<div class="col-md-3">
								<label class=" control-label">PAN Number</label>
								<input type="text" class="form-control" id="party_pan_number" name="party_pan_number" placeholder="" value="<?php echo $row['party_pan_number'];?>" >
							</div>
			
							<div class="col-md-3">
								<label class=" control-label">MSME Number</label>
								<input type="text" class="form-control" id="party_msme_number" name="party_msme_number" placeholder="" value="<?php echo $row['party_msme_number'];?>" >
							</div>
						</div>
						
						<!--<div class="form-group">-->
						
						
						<!--	<div class="col-md-2">-->
						<!--		<label class=" control-label">LCD Certificate No. </label>-->
						<!--		<input type="text" class="form-control" id="ldc_cert_no" name="ldc_cert_no" value="" >-->
						<!--	</div>-->
							
						<!--	<div class="col-md-2">-->
						<!--		<label class=" control-label">LCD Rate % </label>-->
						<!--		<input type="text" class="form-control" id="ldc_rate" name="ldc_rate" value="" >-->
						<!--	</div>-->
							
						<!--	<div class="col-md-2">-->
						<!--		<label class=" control-label">Limit as per LCD </label>-->
						<!--		<input type="text" class="form-control" id="limit_as_per_ldc" name="limit_as_per_ldc" value="" >-->
						<!--	</div>-->
						<!--</div>-->
							
							
					</div>

					<div class="tab-pane" id="tab_2">					
						
						<div class="form-group">
							
							<div class="col-md-5">
							
								<label class="control-label">Beneficiary Name</label>
								<input type="text" class="form-control" id="party_beneficiary_name" name="party_beneficiary_name"  value="" >
							
							</div>
							
						</div>
						
						<div class="form-group">
							
							<div class="col-md-3">
								<label class="control-label">Bank Name</label>
								<input type="text" class="form-control" id="party_bank_name" name="party_bank_name" placeholder="" value="<?php echo $row['party_bank_name'];?>" >
							</div>
						
							<div class="col-md-3">
								<label class="control-label">Account Type </label>
								<select class="form-control" name="party_bank_account_type" id="party_bank_account_type" >
									<option value=""> Select </option>
									<option value="Saving"> Saving</option>
									<option value="Current"> Current</option>
								</select>	
							</div>
							
							<div class="col-md-6">
								<label class="control-label">Bank Address </label>
								<input type="text" class="form-control" id="party_bank_address" name="party_bank_address" placeholder="" value="<?php echo $row['party_bank_address'];?>" >
							</div>
						
						</div>
						
						<div class="form-group">
							
							<div class="col-md-3">
								<label class="control-label">Account Number</label>
								<input type="text" class="form-control" id="party_bank_account_no" name="party_bank_account_no" placeholder="" value="<?php echo $row['party_bank_account_no'];?>" >
							</div>
						
							<div class="col-md-2">
								<label class="control-label">Account IFSC Code</label>
								<input type="text" class="form-control" id="party_bank_ifsc_code" name="party_bank_ifsc_code" placeholder="" value="<?php echo $row['party_bank_ifsc_code'];?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">&nbsp; </label>
								
							</div>
							
						</div>

					</div>
					
				</div>	
                
					<!--	<div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="vendor.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
                        </div>-->

   						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; ?>
								
							</div>
							<?php $baseurl1 = $baseurl.$modulePath.'&same_page='.$page;?>
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

	if(isset($_POST['Save'])){
			$id					= $_POST['id']; 
			$vendor_id			= $_POST['id']; 
			$party_name						= $_POST['party_name'];
			$party_category 				= $_POST['party_category'];
			$party_contact_person_name 		= $_POST['party_contact_person_name'];
			$party_designation				= $_POST['party_designation'];
			$party_address_1 				= $_POST['party_address_1'];
//			$party_address_2 				= $_POST['party_address_2'];
//			$party_address_3 				= $_POST['party_address_3'];
			$party_city 					= $_POST['party_city'];
			$party_state 					= $_POST['party_state'];
			$party_pincode 					= $_POST['party_pincode'];
			$party_area 					= $_POST['party_area'];
			$party_country 					= $_POST['party_country'];
			$party_phone 					= $_POST['party_phone'];
			$party_phone1 					= $_POST['party_phone1'];
			$party_phone2 					= $_POST['party_phone2'];
			$party_mobile 					= $_POST['party_mobile'];
			$party_mobile1 					= $_POST['party_mobile1'];
			$party_mobile2 					= $_POST['party_mobile2'];			
			$party_email 					= $_POST['party_email'];
			$party_websites					= $_POST['party_websites'];
			$party_gst_number 				= $_POST['party_gst_number'];
			$party_pan_number 				= $_POST['party_pan_number'];
			$party_msme_number				= $_POST['party_msme_number'];
			$party_msme_register_date		= date('Y-m-d', strtotime($_POST['party_msme_register_date']));
			$party_msme_expiry_date			= date('Y-m-d', strtotime($_POST['party_msme_expiry_date']));
			$party_electricity_flag         = $_POST['party_electricity_flag'];
			$party_beneficiary_name 		= $_POST['party_beneficiary_name'];
			$party_bank_name 				= $_POST['party_bank_name'];
			$party_bank_account_type 		= $_POST['party_bank_account_type'];
			$party_bank_address 			= $_POST['party_bank_address'];
			$party_bank_account_no 			= $_POST['party_bank_account_no'];
			$party_bank_ifsc_code 			= $_POST['party_bank_ifsc_code'];
			//$party_kyc						= $_POST['party_kyc'];
			$tax_category 					= $_POST['tax_category'];
			$tally_account_name 			= $_POST['tally_account_name'];
			$party_type                     = $_POST['party_type'];
			$add_to_tally					= $_POST['add_to_tally'];
			$party_nature_business			= $_POST['party_nature_business'];
			$company_id						= $_POST['company_id'];
// 			$ldc_cert_no 					= $_POST['ldc_cert_no'];
// 			$ldc_rate 						= $_POST['ldc_rate'];
// 			$limit_as_per_ldc 				= $_POST['limit_as_per_ldc'];
			$msme_flag                      = $_POST['msme_flag'];
			
			$approver_1			= $_POST['approver_1'];
			$approver_2			= $_POST['approver_2'];
			
			
  			$sql="update sma_party_mst set 	party_type ='$party_type',
						party_name ='$party_name',
						party_category 				= '$party_category',
						party_contact_person_name 	= '$party_contact_person_name',
						party_designation			= '$party_designation',
						party_address_1 			= '$party_address_1',
						party_city 					= '$party_city',
						party_state 				= '$party_state',
						party_pincode 				= '$party_pincode',
						party_area 					= '$party_area',
						party_country 				= '$party_country',
						party_phone 				= '$party_phone',
						party_mobile 				= '$party_mobile',
						party_phone1 				= '$party_phone1',
						party_phone2 				= '$party_phone2',
						party_mobile1 				= '$party_mobile1',
						party_mobile2 				= '$party_mobile2',			
						party_email 				= '$party_email',
						party_websites				= '$party_websites',
						party_gst_number 			= '$party_gst_number',
						party_pan_number 			= '$party_pan_number',
						party_msme_number			= '$party_msme_number',
						party_msme_register_date	= '$party_msme_register_date',
						party_msme_expiry_date		= '$party_msme_expiry_date',
						party_bank_name 			= '$party_bank_name',
						party_bank_account_type 	= '$party_bank_account_type',
						party_bank_address 			= '$party_bank_address',
						party_bank_account_no 		= '$party_bank_account_no',
						party_bank_ifsc_code 		= '$party_bank_ifsc_code',
						party_beneficiary_name 		= '$party_beneficiary_name',
						tax_category 				= '$tax_category',
						tally_account_name          = '$tally_account_name',
						party_nature_business		= '$party_nature_business',
						company_id					= '$company_id',
						party_electricity_flag      = '$party_electricity_flag',
						msme_flag                   = '$msme_flag'
					where id = '$id'";
//echo $sql;
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			$company_id 	= '';
			$pgname 		= "vendor.php";
			include "../viewonly.php";
			$description 	= $party_name. ','. $party_category.','.$party_type;
		    $affect 		= 'Modified';
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
			$userid   	    = $_SESSION['usrid'];
			
			$rem = '';
			
			// add attachments
			// file upload
			$arrDocType = $_POST["doctype"];
			$arrDocDesc = $_POST["docdesc"];
			$arrFUDoc = $_FILES["fudoc"];
			for($i = 0; $i < sizeof($arrDocType); $i++) {
				$folder_path = "uploads/vn/" . $id;
				if (!file_exists($folder_path)){
					mkdir($folder_path, 0755, true);
					$findex = $folder_path.'/index.php';
					fopen($findex,'w');
				}
				$filename = $arrFUDoc['name'][$i];
				$tmpFileName = $arrFUDoc['tmp_name'][$i];
				
				if( !empty($filename) ){
					$sql = "INSERT INTO file_uploads (module, file_name, file_path, doc_type, doc_desc, reference_id, date_uploaded) VALUES('VN', '" . $filename . "', '" . $folder_path . "', '" . $arrDocType[$i] . "', '" . $arrDocDesc[$i] . "', " . $id . ", now() )";
					if (mysqli_query($con, $sql)) {
						move_uploaded_file($tmpFileName, "uploads/vn/" . $id . "/" . $filename);
					}
					else {
						echo "Error: " . mysqli_error($con);
					}
				}
			}

	
			
			$page					= $_POST['page'];
			
//exit();			
			echo "<script>window.location.href='vendor.php?sub=list&same_page=$page';</script>";
			
			exit();
			
		}
		
		$page = $_GET['page'];
		$id = $_GET['id'];
		$vendor_id = $_GET['id'];
		$sql="Select * from sma_party_mst where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	
		
	//	$kyc 				= $row['party_kyc'];
		$approver_1			= $row['approver_1'];
		$approver_1_status	= $row['approver_1_status'];
		$approver_2 		= $row['approver_2'];
		$approver_2_status	= $row['approver_2_status'];
		$status				= $row['status'];
		$add_to_tally		= $row['add_to_tally'];
		
		$readonly 			= '';
		//echo $user. ' <<>>>';
		
		$user_category = $_SESSION['user_category'];
		$role = $_SESSION['role'];
		

		if($status=='Submitted'){
			$readonly 	= '';
		}	
		if($status=='Completed'){
			$readonly 	= 'READONLY';
		}	
		
	
?>

    <section class="content-header">
        <h1>
            Supplier
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Supplier</a></li>
        </ol>
    </section>
		
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            
		<!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		    <!-- form start -->
            <form class="form-horizontal" action="vendor.php?sub=edit" enctype="multipart/form-data" method="post">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" id='party_id' value="<?php echo $row['id'];?>">
					  <input type="hidden" name="page" value="<?= $page;?>">
					  
					  <input type="hidden" name="status" id="statuS" value="<?php echo $row['status'];?>" >
					  
					  <?php 
						$status = $row['status'];
							
					  ?>
					  
				<ul class="nav nav-tabs">
				  <li class="active"><a href="#tab_1" data-toggle="tab">Contact Details</a></li>
				  <li><a href="#tab_2" data-toggle="tab" id="second_tab" >Bank Details</a></li>
                  <li><a href="#tab_3" data-toggle="tab" id="third_tab" >Documents</a></li>
				   
				  
					<span class="pull-right" style="color:red;font-size:bold;">
						<?php	echo $status; ?>
					</span>
				</ul>
				
				<div class="tab-content">
					<div class="tab-pane active" id="tab_1">
						
						<div class="form-group">
							<div class="col-md-2">
							<label class=" control-label">Type **</label>
								
								<select class="form-control" <?php echo $readonly ?> name="party_type" id="party_type" required="true" >
									<option value="0"> Select </option>
										<?php $sql = "select * from sma_type order by type ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($row['party_type'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['type'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<div class="col-md-3">
							<label class=" control-label">Supplier Category **</label>
								<select class="form-control" <?php echo $readonly ?> name="party_category" id="party_category" required="true" >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_categories order by name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($row['party_category'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['name'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<div class="col-md-5">
								<label class="control-label">Supplier Name **</label>
								<input type="text" class="form-control" id="party_name" name="party_name" <?php echo $readonly ?> value="<?php echo $row['party_name'];?>" >
							</div>
			            <?php $party_electricity_flag = $row['party_electricity_flag']; ?>
			                <div class="col-md-2">
								<label class=" control-label">Electricity Vender</label><br>
								<input type="checkbox" id="party_electricity_flag" name="party_electricity_flag" <?php echo ($party_electricity_flag=='Y')?"CHECKED":''; ?> value="Y" >  &nbsp;
							</div>
							
						</div>
						
						<?php 
						    $tax_category   = $row['tax_category'];
						    $msme_flag      = $row['msme_flag'];
						?>
							
							<div class="form-group">
								
								<div class="col-md-3">
									<label class=" control-label">Tax Category **</label><br>
									<input type="radio" id="tax_category" name="tax_category" <?php echo ($tax_category=='R')?"CHECKED":''; ?> value="R" > Registered &nbsp;
									<input type="radio" id="tax_category" name="tax_category" <?php echo ($tax_category=='U')?"CHECKED":''; ?> value="U" > Unregistered &nbsp;
								</div>
								
								<div class="col-md-2">
									<label class=" control-label">**</label><br>
									<input type="radio" id="msme_flag" name="msme_flag" <?php echo ($msme_flag=='Y')?"CHECKED":''; ?> value="Y" > MSME &nbsp;
									<input type="radio" id="msme_flag" name="msme_flag" <?php echo ($msme_flag=='N')?"CHECKED":''; ?> value="N" > NON MSME &nbsp;
								</div>
							
								<div class="col-md-4">
									<label class="control-label">Tally Account Name</label>
									<input type="text" class="form-control" <?php echo $readonly ?> id="tally_account_name" name="tally_account_name" placeholder="" value="<?php echo $row['tally_account_name'];?>" >
								</div>
							
								<div class="col-md-3">
									<label class="control-label">Nature of Business **</label>
									<input type="text" class="form-control" <?php echo $readonly ?> id="party_nature_business" name="party_nature_business"  value="<?php echo $row['party_nature_business'];?>" >
								</div>
							</div>
							
							
						<div class="form-group">
							<div class="col-sm-4">

								<label for="company_id" class="control-label">Registered By<span style="color:red;"> **</span></label>
									<select class="form-control select2123" name="company_id" id="company_id"  >
									<option value=""> Select </option>
									<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
									$q2 	= mysqli_query($con, $sql);
									while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['company_id'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
									<?php } ?>
								</select>		
							</div>
										
							<div class="col-md-3">
								<label class=" control-label">Contact Person Name</label>
								<input type="text" class="form-control" id="party_contact_person_name" name="party_contact_person_name" <?php echo $readonly ?> value="<?php echo $row['party_contact_person_name'];?>" >
							</div>
						
							<div class="col-md-2">
								<label class=" control-label">Designation</label>
								<input type="text" class="form-control" id="party_designation" name="party_designation" <?php echo $readonly ?> value="<?php echo $row['party_designation'];?>" >
							</div>
							
							<div class="col-md-3">
								<label class="control-label">Email</label>
								<input type="text" class="form-control" id="party_email" name="party_email" <?php echo $readonly ?> value="<?php echo $row['party_email'];?>" >
							</div>
							
						</div>
							
						<div class="form-group">
							<div class="col-md-6">
								<label class="control-label">Address </label>
								<textarea rows="2" cols="80" class="form-control" id="party_address_1" name="party_address_1" <?php echo $readonly ?> ><?php echo $row['party_address_1'];?> </textarea>
							</div>
						
							<div class="col-md-2">
								<label class="control-label">Mobile-1</label>
								<input type="text" class="form-control" id="party_mobile" name="party_mobile" <?php echo $readonly ?> value="<?php echo $row['party_mobile'];?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Mobile-2</label>
								<input type="text" class="form-control" id="party_mobile1" name="party_mobile1" <?php echo $readonly ?> value="<?php echo $row['party_mobile1'];?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Mobile-3</label>
								<input type="text" class="form-control" id="party_mobile2" name="party_mobile2" <?php echo $readonly ?> value="<?php echo $row['party_mobile2'];?>" >
							</div>
							
						</div>
						
						<div class="form-group">
							
							<div class="col-md-2">
								<label class="control-label">State</label>
								<select class="form-control" name="party_state" id="party_state" required onchange="getcity(this.value)" <?php echo $readonly ?> >
									<option value=""> Select </option>
										<?php $sql = "select * from states order by state_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($row['party_state'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['state_name'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<div class="col-md-2">
								<label class="control-label">City</label>
								<span id="getcity">
									<select class="form-control" name="party_city" id="party_city" <?php echo $readonly ?>>
										<option value=""> Select </option>
											<?php $sql = "select * from cities order by city_name ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['id'];?>"  <?php echo ($row['party_city'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['city_name'];?></option>
											<?php } ?>
									</select>
								</span>
								
							</div>
						
							<div class="col-md-2">
								<label class="control-label">Pincode</label>
								<input type="text" class="form-control" id="party_pincode" name="party_pincode" <?php echo $readonly ?> value="<?php echo $row['party_pincode'];?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Phone-1</label>
								<input type="text" class="form-control" id="party_phone" name="party_phone" <?php echo $readonly ?> value="<?php echo $row['party_phone'];?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Phone-2</label>
								<input type="text" class="form-control" id="party_phone1" name="party_phone1" <?php echo $readonly ?> value="<?php echo $row['party_phone1'];?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">Phone-3</label>
								<input type="text" class="form-control" id="party_phone2" name="party_phone2" <?php echo $readonly ?> value="<?php echo $row['party_phone2'];?>" >
							</div>
							
						</div>
						
						<div class="form-group">
							
							<div class="col-md-4">
								<label class="control-label">Area</label>
								<input type="text" class="form-control" id="party_area" name="party_area" <?php echo $readonly ?> value="<?php echo $row['party_area'];?>" >
							</div>
						
							<div class="col-md-2">
								<label class="control-label">Country</label>
								<input type="text" class="form-control" id="party_country" name="party_country" <?php echo $readonly ?> value="<?php echo $row['party_country'];?>" >
							</div>
							
							<div class="col-md-6">
								<label class="control-label">Website</label>
								<input type="text" class="form-control" id="party_websites" name="party_websites" <?php echo $readonly ?> value="<?php echo $row['party_websites'];?>" >
							</div>
						</div>
						<?php
							$gsterr = '';
							$party_gst_number = $row['party_gst_number'];
							if(strlen($party_gst_number)<15){
								$gsterr = 'GST length should be 15 character !!!';	
							}	
							
						?>
						<div class="form-group">
						
							<div class="col-md-3">
								<label class=" control-label">GST Number</label>
								<input type="text" class="form-control" id="party_gst_number" name="party_gst_number" <?php echo $readonly ?> value="<?php echo $row['party_gst_number'];?>" >
							<?php if(!empty($gsterr)){ ?>	
								<label class=" control-label" style='color:red;'><?= $gsterr;?></label>
							<?php } ?>	
							</div>
							
							<div class="col-md-3">
								<label class=" control-label">PAN Number</label>
								<input type="text" class="form-control" id="party_pan_number" name="party_pan_number" <?php echo $readonly ?> value="<?php echo $row['party_pan_number'];?>" >
							</div>
						
							<div class="col-md-2">
								<label class=" control-label">MSME Number</label>
								<input type="text" class="form-control" <?php echo $readonly ?> id="party_msme_number" name="party_msme_number" placeholder="" value="<?php echo $row['party_msme_number'];?>" >
							</div>
					<?php
						$party_msme_register_date	= date('d-m-Y', strtotime($row['party_msme_register_date']));
						$party_msme_expiry_date		= date('d-m-Y', strtotime($row['party_msme_expiry_date']));
						if($party_msme_register_date == '01-01-1970' || $party_msme_register_date == '31-12-1969' || $party_msme_register_date =='30-11--0001'){
								$party_msme_register_date='';
						}
						if($party_msme_expiry_date == '01-01-1970' || $party_msme_expiry_date == '31-12-1969' || $party_msme_expiry_date =='30-11--0001'){
								$party_msme_expiry_date='';
						}

					?>		
							<div class="col-md-2">
								<label class=" control-label">MSME Reg.Date</label>
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="party_msme_register_date" name="party_msme_register_date" value="<?php echo $party_msme_register_date;?>" >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
							</div>
							
							<div class="col-md-2">
								<label class=" control-label">MSME Expiry Date</label>
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="party_msme_expiry_date" name="party_msme_expiry_date" value="<?php echo $party_msme_expiry_date;?>" >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
							</div>
							
						</div>	
							
						
					
					</div>
					
					<div class="tab-pane" id="tab_2">					
						
								
						<div class="form-group">
							
							<div class="col-md-5">
								<label class="control-label">Beneficiary Name</label>
								<input type="text" class="form-control" id="party_beneficiary_name" name="party_beneficiary_name" <?php echo $readonlyb ?> value="<?php echo $row['party_beneficiary_name'];?>" >
							</div>
							
						</div>
						
						<div class="form-group">
							
							<div class="col-md-3">
								<label class="control-label">Bank Name</label>
								<input type="text" class="form-control" id="party_bank_name" name="party_bank_name" <?php echo $readonlyb ?> value="<?php echo $row['party_bank_name'];?>" >
							</div>
						
							<div class="col-md-3">
								<label class="control-label">Account Type </label>
								<select class="form-control" name="party_bank_account_type" id="party_bank_account_type" <?php echo $readonlyb ?> >
									<option value=""> Select </option>
									<option value="Saving" <?php echo ($row['party_bank_account_type'] == 'Saving')?'selected="selected"':'';?> > Saving</option>
									<option value="Current" <?php echo ($row['party_bank_account_type'] == 'Current')?'selected="selected"':'';?> > Current</option>
								</select>	
							</div>
							
							<div class="col-md-6">
								<label class="control-label">Bank Address </label>
								<input type="text" class="form-control" id="party_bank_address" name="party_bank_address" <?php echo $readonlyb ?> value="<?php echo $row['party_bank_address'];?>" >
							</div>
						
						</div>
						
						<div class="form-group">
							
							<div class="col-md-3">
								<label class="control-label">Account Number</label>
								<input type="text" class="form-control" id="party_bank_account_no" name="party_bank_account_no" <?php echo $readonlyb ?> value="<?php echo $row['party_bank_account_no'];?>" >
							</div>
						
							<div class="col-md-2">
								<label class="control-label">Account IFSC Code</label>
								<input type="text" class="form-control" id="party_bank_ifsc_code" name="party_bank_ifsc_code" <?php echo $readonlyb ?> value="<?php echo $row['party_bank_ifsc_code'];?>" >
							</div>
							
							<div class="col-md-2">
								<label class="control-label">&nbsp; </label>
								
							</div>
							
						</div>

					</div>
					
					
						<div class="tab-pane <?php echo $active;?>" id="tab_3">
						
                            <!-- Attachments -->
							
							 <?php
                              $sql = "SELECT * FROM file_uploads WHERE module = 'VN' AND reference_id = " . $id;
                              $docResults = mysqli_query($con, $sql);
	                          ?>
                                  <table id="prDocsTable" class="table table-bordered table-striped">
                                      <thead>
                                      <tr>
                                          <th>Document Type</th>
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
                                              <td><?php echo $docRow['doc_desc'] ?></td>
                                              <td><a target="_blank" href="<?php echo $docRow['file_path'] . '/' . $docRow['file_name']; ?>"><?php echo $docRow['file_name'] ?></a></td>
										<?php if(  $status !='Completed' ){ ?>	  
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
                                            <select class="form-control col-sm-2 doctype" name="doctype[]" required="true" >
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
										<td><label class="col-sm-1 control-label">Description</label>
											 <textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea>
										</td>
										<td><label class="control-label col-sm-3">Attachment</label><br>
											<input type="file" name="fudoc[]" class="docfile">
										</td>
										<?php if($user=='Admin'){ ?>
                                         <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>  
										<?php } ?>
                                    </tr>  
                               </table>  
                            <!--   <input type="button" name="submit" id="submit" class="btn btn-info" value="Submit" />  -->
							</div>
						</div>
					
					<div class="tab-pane <?php echo $active;?>" id="tab_4">
							<?php
                              $sql = "SELECT * FROM kyc_upd_log WHERE party_id ='$id' " ;
							  
                              $ky = mysqli_query($con, $sql);
	                          ?>
                                  <table id="prDocsTable" class="table table-bordered table-striped">
                                      <thead>
                                      <tr>
                                          <th>Created On</th>
                                          <th> By</th>
										  <th>Updated On</th>
                                          <th>Changed By</th>
                                          <th>KYC </th>
										  <th>Remarks </th>
										  <th>Decision </th>
                                
                                      </tr>
                                      </thead>
                                      <tbody id="partyKYC">
				                              <?php
				                              echo mysqli_error($con);
				                              while($kyrow = mysqli_fetch_array($ky)) {
													$created_by ='';
													$uid 	= $kyrow['user_id'];
													$uidc 	= $kyrow['user_id'];
													$sql="SELECT * FROM sma_user where id ='$uid' ";
													
													$rs = mysqli_query($con, $sql);
													$rw = mysqli_fetch_array($rs);
													$username = $rw['username'];
													
													$uid = $kyrow['create_by'];
													$sql="SELECT * FROM sma_user where id ='$uid' ";
													$rs = mysqli_query($con, $sql);
													$rwaffect = mysqli_affected_rows($con);
													
													$rw = mysqli_fetch_array($rs);
													$created_by = $rw['username'];
													if($rwaffect==0 && $uidc ==0 ){
														$created_by = $kyrow['created_by_web'];
														if(empty($created_by)){
															$created_by = $row['party_name'];
														}	
													}
													
													$created_on = date('d-m-Y', strtotime($kyrow['created_on']));
													$updated_on = date('d-m-Y', strtotime($kyrow['updated_on']));
													
													if($created_on == '01-01-1970' || $created_on=='30-11--0001'){
														$created_on = '';
													}
													else {
														$created_on = date('d-m-Y h:i:s', strtotime($kyrow['created_on'])); 
													}
													
													if($updated_on == '01-01-1970' || $updated_on == '30-11--0001'){
														$updated_on = '';
													}
													else {
														$updated_on = date('d-m-Y h:i:s', strtotime($kyrow['updated_on']));
													}
													
												?>
                                          <tr>
                                              <td width="10%"><?php echo $created_on ?></td>
											  <td width="15%"><?php echo $created_by ?></td>
											  <td width="10%"><?php echo $updated_on ?></td>
                                              <td width="15%"><?php echo $username; ?></td>
                                              <td width="5%"><?php echo $kyrow['party_kyc']; ?></td>
											  <td width="20%"><?php echo $kyrow['remarks']; ?></td>
											  <td width="10%"><?php echo $kyrow['status']; ?></td>
                                          
                                          </tr>
				                              <?php
				                              }
				                              ?>
                                      </tbody>
                                      
                                  </table>
						</div>	


				</div>	
  
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

?>

<input type="hidden"  value="<?php echo $opt ?>" name="opt" id="opt">
			
						<!--<div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="vendor.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
                        </div>-->

						<div class="box-footer">
							<div class="col-sm-6">
							<?php $did = $_GET['id'];
							//Mrunmayee Started
							$sq2 = "SELECT COUNT(*) as total FROM `sma_purchase_order` where to_supplier = '$did'";
							$q2  = mysqli_query($con, $sq2);
							$r2  = mysqli_fetch_assoc($q2);
							$mycount = $r2['total'];
							
							$sq2 = "SELECT COUNT(*) as total FROM `sma_tender_supplier` where supplier_id = '$did'";
							$q2  = mysqli_query($con, $sq2);
							$r2  = mysqli_fetch_assoc($q2);
							$mycount += $r2['total'];
							
							$sq3 = "SELECT COUNT(*) as total FROM `sma_supplier_invoice` where 	suplier_name = '$did'";
                			$q3  = mysqli_query($con, $sq3);
                			$r3  = mysqli_fetch_assoc($q3);
                			$mycount1 = $r3['total'];
                			if ($mycount <= 0 and $mycount1 <= 0) {
								if($kyc!='Y'){ 
							?>	
								<a href="<?php echo $baseurl."vendor/vendor.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a>
						    <?php } }//Mrunmayee ended?>
							
							<?php 
								if( ($user=='Admin' && $status !='Draft' ) || ( $user=='Admin' ) ){
							?>
									<a href="#makeDraftAuthority" class="btn btn-info" data-toggle="modal" data-mode="Delete" data-target="#makeDraftAuthority">Convert to Draft</a>
							<?php }	?>
							
							</div>
							<?php $baseurl1 = $baseurl.$modulePath.'&same_page='.$page;?>
							<div class="col-sm-6 text-right">
								<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Cancel</a>
								<span>&nbsp;&nbsp;</span>
							
						<?php	
//echo $kyc. ' ' .$approver_1. "<<>>" . $approver_1_status . ' ' . $status;
							if( ( $kyc !='Y' && ( empty($approver_1) || $status=='Draft' ) ) || ($status=='Draft' ) ){ ?>		
								<span class='hidesend' >	
									<!--<input type='button' class="btn btn-primary" onclick="getapprover()" value="Send " >-->
									<!--<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>-->
								</span>
						<?php } ?>		
						
								<input class="btn btn-primary" type="submit" value="Save" name="Save" >&nbsp;&nbsp;&nbsp;
					<?php			
			
								$approver_flag='';
								if( $status != 'Draft' ){
									
									$approver_flag='';
									if( $usrid == $approver_1 && $approver_1_status=='Submitted'  ){
										$approver_flag='Y';
									}
									if( $usrid == $approver_2 && $approver_2_status=='Submitted' ){
					
										$approver_flag='Y';
									
									}
									
//ECHO $usrid.  ' <<>> ' .$approver_2 . ' <<>> ' . $status. ' <2> '. $approver_2_status. ' << 22 >>' .$approver_flag." >><BR>";
							?>
								
								
							<?php	
								if($status!='Draft' && $status!='Completed' && $status!='Suspend' && $approver_flag=='Y'){
							?>
								<span class="hidden-div">
									<a href="#approvalAuthority" class="btn btn-info" data-toggle="modal" data-mode="Approve" data-target="#approvalAuthority">Approve </a>
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</span>
								
								<span class="hidden-reject_div">
									<a href="#rejectAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Reject" data-target="#rejectAuthority">Reject </a>
									</span>
									
							<?php 	}
							
								}
							?>	
							
							</div>
						</div>

				<span id="predit"></span>
						
                        <span id="getapprover">
							<div class="box-footer">
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
									<label class="control-label">Approver 1</label><BR>
									<label class="control-label"><?= $approver_1_name . " <BR> " . $approver_1_role;?>
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
									<label class="control-label">Approver 2</label><BR>
									<label class="control-label"><?= $approver_2_name . " <BR> " . $approver_2_role;?>
									</label>
								</div>
					<?php	
							}
						}	
					?>		
							</div>
						</span>
						
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

										?>
										
										<input type="hidden" name="vn_id" id="vn_idE" value="<?php echo $vendor_id; ?>" >
										
										<input type="hidden" id="approverC" name="approver" value='<?= $userid ?>' >
										
										<input type="hidden" id="modeC" name="mode" value='Approve' >
										
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
										
										?>
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

<!--Rejected Workflow Popup-->

<div class="modal fade" id="rejectAuthority" role="dialog" aria-labelledby="rejectAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="rejectAuthority">Reject...</h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   

										?>
										
										<input type="hidden" name="vn_id" id="vn_idR" value="<?php echo $vendor_id; ?>" >
										
										<input type="hidden" id="approverC" name="approver" value='<?= $userid ?>' >
										
										<input type="hidden" id="modeR" name="mode" value='Reject' >
										
										<div class="form-group col-md-12">
											<label for="approver" class="col-sm-4 control-label">Status</label>
                                            <div class="col-sm-7">
												<input type="text" class="form-control" id="statusE" style="color:red;" readonly name="status" value="<?php echo $status ?>" >
                                            </div>
                                        </div>
										
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

<!--Rejected Workflow Popup End -->	  
	  
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>        
<script>  
 $(document).ready(function(){  
      var i=1;  
      $('#add').click(function(){
			var opt =  document.getElementById('opt').value;
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'"><td><label class="col-sm-1 control-label">&nbsp;</label></td><td><select class="form-control select2 doctype" name="doctype[]"><option value="0">Select</option>'+opt+'</select></td><td><textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea></td><td><input type="file" name="fudoc[]" class="docfile"></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
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


function validate(){
	
	var party_gst_number    = document.getElementById("party_gst_number").value;
//alert(party_gst_number);
	return;
	
}	

function getcity(id){
        var sub    = 'sub1';
		var strURL = "v_func.php";
		$.post(strURL,{id:id,sub1:sub},function(result){
		      $('#getcity').html(result);
		});
}

function getapprover(){
		
		//var company_id    	= document.getElementById("projecT").value;
		var company_id    	= 4;
		//var checker_value   = document.getElementById("checker_value").value;
		//var trans_type    	= 'VN';
		//var po_type  	   	= document.getElementById("po_typea").value;

		var sub = 'sub24';
		$('.hidesend').hide();

//alert(sub + ' ' + po_type + ' ' + trans_type + ' ' + company_id + ' ' + checker_value);	
		
		var strURL = "v_func.php";
		$.post(strURL,{company_id:company_id,sub24:sub},function(result){
		      $('#getapprover').html(result);
			  
		});
		
	}


   $("#submitApprove").on("click", function(e){
		$('.hidden-div').hide();
		$('#predit').html('Wait...');
        var sub = 'sub9';
		var mode		 	=  $("#modeC").val();
		
		var vn_id		 	=  $("#vn_idE").val();
		var remarks			=  $("#remarksE").val();
		
//alert( sub + ' ' +  vn_id );
		 
			$('#approvalAuthority').modal('hide');
		 
		var strURL = "v_func.php";
		$.post(strURL,{ vn_id:vn_id,
						remarks:remarks,
						mode:mode,
						sub9:sub},
						function(result){
		      $('#predit').html(result);
		});
	});


$("#submitReject").on("click", function(e){
		$('.hidden-div').hide();
		$('#predit').html('Wait...');
        var sub = 'sub9';
		var mode		 	=  $("#modeR").val();
		
		var vn_id		 	=  $("#vn_idR").val();
		var remarks			=  $("#remarksR").val();
			 
		$('#rejectAuthority').modal('hide');
		 
		var strURL = "v_func.php";
		$.post(strURL,{ vn_id:vn_id,
						remarks:remarks,
						mode:mode,
						sub9:sub},
						function(result){
		      $('#predit').html(result);
		});
	});
	
	
   $("#submitDraft").on("click", function(e){
       
	   var party_id		 	=  $("#party_id").val();
        var status 			=  $("#statusD").val();
		var remarks			=  $("#remarksD").val();
		
		$('#predit').html('Wait...');
		
//alert(remarks +  ' ' + party_id );
	
		$('#makeDraftAuthority').modal('hide');
		var strURL = "py_draft_func.php";
		$.post(strURL,{ party_id:party_id,
						status:status,
						remarks:remarks},
						function(result){
		      $('#predit').html(result);
		});
	});
	
</script>

</body>
</html>
