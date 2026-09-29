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
								
							<div class="col-xs-2">
                                		
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

		<table id="prtable123" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>Supplier</th>
			<th>Type</th>
			<th>City</th>
			<th>GST No.</th>
			<th>KYC</th>
			
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
		
		$sql .= " LIMIT $start, $limit ";

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
			
		$baseurl1 = $baseurl.$modulePath1.'vendor.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "vendor.php?sub=edit&id=". $row['id']?>&page=<?= $page;?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>&page=<?= $page;?>'">
		<td width="20%"><?php echo $row['party_name'];?></td>
		<td width="15%"><?php echo $party_type;?></td>
		<td width="15%"><?php echo $city;?></td>
		
		<td width="15%"><?php echo $party_gst_number;?></td>
		<td width="10%"><?php echo $row['party_kyc'];?></td>
		
		
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
  $end  =$start+$limit;
  $begin=$start+1;
  
  if ($end>$total_pages){ $end=$total_pages;}
  echo $paginate;
  echo 'Showing ' . $begin .' to ' . $end . ' of ' . $total_pages . ' entries ';
  
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
			
			$party_beneficiary_name 		= $_POST['party_beneficiary_name'];
			$party_bank_name 				= $_POST['party_bank_name'];
			$party_bank_account_type 		= $_POST['party_bank_account_type'];
			$party_bank_address 			= $_POST['party_bank_address'];
			$party_bank_account_no 			= $_POST['party_bank_account_no'];
			$party_bank_ifsc_code 			= $_POST['party_bank_ifsc_code'];
			
			$tax_category = $_POST['tax_category'];
			$tally_account_name = $_POST['tally_account_name'];
			
  			$sql="insert into sma_party_mst (party_type, party_name, party_category, party_contact_person_name, party_address_1, party_city, party_state, party_pincode, party_area, party_country, party_phone, party_phone1, party_phone2, party_mobile, party_mobile1, party_mobile2, party_email, party_gst_number, party_pan_number, party_msme_number, party_bank_name, party_bank_account_type, party_bank_address, party_bank_account_no, party_bank_ifsc_code, party_beneficiary_name, tax_category,tally_account_name ) 
			Values('$party_type', '$party_name', '$party_category', '$party_contact_person_name', '$party_address_1', '$party_city', '$party_state', '$party_pincode', '$party_area', '$party_country', '$party_phone', '$party_phone1', '$party_phone2', '$party_mobile', '$party_mobile1', '$party_mobile2', '$party_email', '$party_gst_number', '$party_pan_number', '$party_msme_number', '$party_bank_name', '$party_bank_account_type', '$party_bank_address', '$party_bank_account_no', '$party_bank_ifsc_code', '$party_beneficiary_name', '$tax_category', '$tally_account_name')";

			$query=mysqli_query($con, $sql);
			$party_id= mysqli_insert_id($con);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
				$userid   	    = $_SESSION['usrid'];
				$sql  = "insert into kyc_upd_log (create_by, created_on, party_id, party_kyc) values ( '$userid' , now(), '$party_id', '$party_kyc' )";
				$query= mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
				
			echo "Supplier successful added";
			echo '<script>window.location.href="vendor.php?sub=list";</script>';
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
							<div class="col-md-3">
							<label class=" control-label">Type</label>
								
								<select class="form-control" name="party_type" id="party_type" required="true" >
									<option value="0"> Select </option>
										<?php $sql = "select * from sma_type order by type ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($party_party == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['type'];?></option>
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
							
							<div class="col-md-6">
								<label class="control-label">Supplier Name **</label>
								<input type="text" class="form-control" id="party_name" name="party_name" placeholder="" value="<?php echo $row['party_name'];?>" required >
							</div>
			
						</div>
						
						<div class="form-group">
							
							<div class="col-md-3">
								<label class=" control-label">Tax Category</label><br>
								<input type="radio" id="tax_category" name="tax_category" checked value="R" > Registered  &nbsp;
								<input type="radio" id="tax_category" name="tax_category" value="U" > Unregistered  &nbsp;
							</div>
						
							<div class="col-md-6">
								<label class="control-label">Tally Account Name</label>
								<input type="text" class="form-control" id="tally_account_name" name="tally_account_name" placeholder="" value="<?php echo $row['tally_account_name'];?>" >
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
							
							<div class="col-md-2">
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
			$id			= $_POST['id']; 
			$party_name						= $_POST['party_name'];
			$party_category 				= $_POST['party_category'];
			$party_contact_person_name 		= $_POST['party_contact_person_name'];
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
			
			$party_beneficiary_name 		= $_POST['party_beneficiary_name'];
			$party_bank_name 				= $_POST['party_bank_name'];
			$party_bank_account_type 		= $_POST['party_bank_account_type'];
			$party_bank_address 			= $_POST['party_bank_address'];
			$party_bank_account_no 			= $_POST['party_bank_account_no'];
			$party_bank_ifsc_code 			= $_POST['party_bank_ifsc_code'];
			$party_kyc						= $_POST['party_kyc'];
			$tax_category 					= $_POST['tax_category'];
			$tally_account_name 			= $_POST['tally_account_name'];
			$party_type                     = $_POST['party_type'];
			
  			$sql="update sma_party_mst set 	party_type ='$party_type',
						party_name ='$party_name',
						party_category 				= '$party_category',
						party_contact_person_name 	= '$party_contact_person_name',
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
						party_bank_name 			= '$party_bank_name',
						party_bank_account_type 	= '$party_bank_account_type',
						party_bank_address 			= '$party_bank_address',
						party_bank_account_no 		= '$party_bank_account_no',
						party_bank_ifsc_code 		= '$party_bank_ifsc_code',
						party_beneficiary_name 		= '$party_beneficiary_name',
						tax_category 				= '$tax_category',
						tally_account_name          = '$tally_account_name',
						party_kyc					= '$party_kyc'
					where id='$id'";
//echo $sql;
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			$party_kyc_prev = $_POST['party_kyc_prev'];
			if($party_kyc != $party_kyc_prev){
				$userid   	    = $_SESSION['usrid'];
				$sql  = "insert into kyc_upd_log ( user_id, updated_on, party_id, party_kyc ) values ( '$userid' , now(), '$id', '$party_kyc' )";
				$query= mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
			}
			
			// add attachments
			// file upload
			$arrDocType = $_POST["doctype"];
			$arrDocDesc = $_POST["docdesc"];
			$arrFUDoc = $_FILES["fudoc"];
			for($i = 0; $i < sizeof($arrDocType); $i++) {
				$folder_path = "uploads/vn/" . $id;
				if (!file_exists($folder_path)){
					mkdir($folder_path, 0755, true);
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
		}
		
		$page = $_GET['page'];
		$id = $_GET['id'];
		$vendor_id = $_GET['id'];
		$sql="Select * from sma_party_mst where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	
		
		$kyc = $row['party_kyc'];
		
		$readonly = '';
		//echo $user. ' <<>>>';
		
		$user_category = $_SESSION['user_category'];
		$role = $_SESSION['role'];
		
		//($role = 'Accountant' && $user_category=='H')
		
		
		
	
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
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
					  <input type="hidden" name="page" value="<?= $page;?>">
					  
				<ul class="nav nav-tabs">
				  <li class="active"><a href="#tab_1" data-toggle="tab">Contact Details</a></li>
				  <li><a href="#tab_2" data-toggle="tab" id="second_tab" >Bank Details</a></li>
                  <li><a href="#tab_3" data-toggle="tab" id="third_tab" >Documents</a></li>
				   <li><a href="#tab_4" data-toggle="tab" id="fourth_tab" >KYC Log</a></li>
				  
				</ul>
				
				<div class="tab-content">
					<div class="tab-pane active" id="tab_1">
						
						<div class="form-group">
							<div class="col-md-3">
							<label class=" control-label">Type</label>
								
								<select class="form-control" name="party_type" id="party_type" required="true" >
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
								<select class="form-control" name="party_category" id="party_category" required="true" >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_categories order by name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($row['party_category'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['name'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<div class="col-md-6">
								<label class="control-label">Supplier Name **</label>
								<input type="text" class="form-control" id="party_name" name="party_name" <?php echo $readonly ?> value="<?php echo $row['party_name'];?>" >
							</div>
			
						</div>
						
						<?php $tax_category = $row['tax_category']; ?>
							
							<div class="form-group">
								
								<div class="col-md-3">
									<label class=" control-label">Tax Category</label><br>
									<input type="radio" id="tax_category" name="tax_category" <?php echo ($tax_category=='R')?"CHECKED":''; ?> value="R" > Registered &nbsp;
									<input type="radio" id="tax_category" name="tax_category" <?php echo ($tax_category=='U')?"CHECKED":''; ?> value="U" > Unregistered &nbsp;
								</div>
							
								<div class="col-md-6">
									<label class="control-label">Tally Account Name</label>
									<input type="text" class="form-control" id="tally_account_name" name="tally_account_name" placeholder="" value="<?php echo $row['tally_account_name'];?>" >
								</div>
							
							</div>
							
							
						<div class="form-group">
							<div class="col-md-4">
								<label class=" control-label">Contact Person Name</label>
								<input type="text" class="form-control" id="party_contact_person_name" name="party_contact_person_name" <?php echo $readonly ?> value="<?php echo $row['party_contact_person_name'];?>" >
							</div>
						
							<div class="col-md-3">
								<label class=" control-label">Designation</label>
								<input type="text" class="form-control" id="party_designation" name="party_designation" <?php echo $readonly ?> value="<?php echo $row['party_designation'];?>" >
							</div>
							
							<div class="col-md-4">
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
								<select class="form-control" name="party_state" id="party_state" onchange="getcity(this.value)" <?php echo $readonly ?> >
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
								<input type="text" class="form-control" id="party_msme_number" name="party_msme_number" placeholder="" value="<?php echo $row['party_msme_number'];?>" >
							</div>
							
							<?php 
							$kyc = $row['party_kyc'];
						//echo $kyc. ' ';	
							if($kyc =='Y'){
							?>
								<div class="col-md-2">
									<label class=" btn-info" ><h3>&nbsp; KYC Verified &nbsp;</h3> </label>
									
								</div>
							<?php
							}
							if($user=='Admin' || $accountant_role=='Y' || empty($kyc) || $kyc =='N'){
							?>
							<div class="col-md-2">
								<input type="hidden" name="party_kyc_prev" value="<?php echo $kyc; ?>" >
								
								<label class=" control-label">KYC</label><BR>
								<input type="radio" id="party_kyc" name="party_kyc" <?php echo ($kyc=='Y')?"CHECKED":''; ?> <?php echo $readonly ?> value="Y" > Yes &nbsp;
								<input type="radio" id="party_kyc" name="party_kyc" <?php echo ($kyc=='N')?"CHECKED":''; ?> <?php echo $readonly ?> value="N" > No &nbsp;
							</div>
							<?php } ?>
							
							
						</div>
													
						
					</div>

					<div class="tab-pane" id="tab_2">					
						
						<div class="form-group">
							
							<div class="col-md-5">
								<label class="control-label">Beneficiary Name</label>
								<input type="text" class="form-control" id="party_beneficiary_name" name="party_beneficiary_name" <?php echo $readonly ?> value="<?php echo $row['party_beneficiary_name'];?>" >
							</div>
							
						</div>
						
						<div class="form-group">
							
							<div class="col-md-3">
								<label class="control-label">Bank Name</label>
								<input type="text" class="form-control" id="party_bank_name" name="party_bank_name" <?php echo $readonly ?> value="<?php echo $row['party_bank_name'];?>" >
							</div>
						
							<div class="col-md-3">
								<label class="control-label">Account Type </label>
								<select class="form-control" name="party_bank_account_type" id="party_bank_account_type" <?php echo $readonly ?> >
									<option value=""> Select </option>
									<option value="Saving" <?php echo ($row['party_bank_account_type'] == 'Saving')?'selected="selected"':'';?> > Saving</option>
									<option value="Current" <?php echo ($row['party_bank_account_type'] == 'Current')?'selected="selected"':'';?> > Current</option>
								</select>	
							</div>
							
							<div class="col-md-6">
								<label class="control-label">Bank Address </label>
								<input type="text" class="form-control" id="party_bank_address" name="party_bank_address" <?php echo $readonly ?> value="<?php echo $row['party_bank_address'];?>" >
							</div>
						
						</div>
						
						<div class="form-group">
							
							<div class="col-md-2">
								<label class="control-label">Account Number</label>
								<input type="text" class="form-control" id="party_bank_account_no" name="party_bank_account_no" <?php echo $readonly ?> value="<?php echo $row['party_bank_account_no'];?>" >
							</div>
						
							<div class="col-md-2">
								<label class="control-label">Account IFSC Code</label>
								<input type="text" class="form-control" id="party_bank_ifsc_code" name="party_bank_ifsc_code" <?php echo $readonly ?> value="<?php echo $row['party_bank_ifsc_code'];?>" >
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
											  <td><a href="<?php echo $delDocUrl; ?>"><i class='fa fa-trash-alt'></i></a></td>
                                          
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
										<?php if(empty($readonly)){ ?>
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
                                          <th>Created By</th>
										  <th>Updated On</th>
                                          <th>Changed By</th>
                                          <th>KYC </th>
                                          
                                      </tr>
                                      </thead>
                                      <tbody id="partyKYC">
				                              <?php
				                              echo mysqli_error($con);
				                              while($kyrow = mysqli_fetch_array($ky)) {
													
													$uid = $kyrow['user_id'];
													$sql="SELECT * FROM sma_user where id ='$uid' ";
													
													$rs = mysqli_query($con, $sql);
													$rw = mysqli_fetch_array($rs);
													$username = $rw['username'];
													
													$uid = $kyrow['create_by'];
													$sql="SELECT * FROM sma_user where id ='$uid' ";
													$rs = mysqli_query($con, $sql);
													$rw = mysqli_fetch_array($rs);
													$created_by = $rw['username'];
													
													$created_on = date('d-m-Y', strtotime($kyrow['created_on']));
													$updated_on = date('d-m-Y', strtotime($kyrow['updated_on']));
													
													if($created_on == '01-01-1970'){
														$created_on = '';
													}
													else {
														$created_on = date('d-m-Y h:i:s', strtotime($kyrow['created_on'])); 
													}
													
													if($updated_on == '01-01-1970'){
														$updated_on = '';
													}
													else {
														$updated_on = date('d-m-Y h:i:s', strtotime($kyrow['updated_on']));
													}
													
												?>
                                          <tr>
                                              <td><?php echo $created_on ?></td>
											  <td><?php echo $created_by ?></td>
											  <td><?php echo $updated_on ?></td>
                                              <td><?php echo $username; ?></td>
                                              <td><?php echo $kyrow['party_kyc']; ?></td>
                                          
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
								if($kyc!='Y'){ 
							?>	
								<a href="<?php echo $baseurl."vendor/vendor.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a>
						    <?php } ?>
							</div>
							<?php $baseurl1 = $baseurl.$modulePath.'&same_page='.$page;?>
							<div class="col-sm-6 text-right">
								<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Cancel</a>
								<span>&nbsp;&nbsp;</span>
								
								<input class="btn btn-primary" type="submit" value="Save" name="Save" >&nbsp;&nbsp;&nbsp;
							
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
alert(party_gst_number);
	return;
	
}	

function getcity(id){
        var sub    = 'sub1';
		var strURL = "v_func.php";
		$.post(strURL,{id:id,sub1:sub},function(result){
		      $('#getcity').html(result);
		});
}
	
</script>

</body>
</html>
