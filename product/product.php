<?php
include("../header.php");
$modulePath = "product.php?sub=list";

$userid   	= $_SESSION['usrid'];

?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Products
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Products</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
			<?php
				if ( $_POST['search'] || $_POST['product_group'] || $_POST['category'] ){
					$_SESSION['search'] 		= $_POST['search'];
					$_SESSION['product_group'] 	= $_POST['product_group'];
					$_SESSION['category'] 	= $_POST['category'];
					$_SESSION['reset'] ='';
				}
				if ( $_SESSION['search'] || $_SESSION['product_group'] || $_SESSION['category'] ){
					$search 		= $_SESSION['search'];
					$product_group	= $_SESSION['product_group'];
					$category	= $_SESSION['category'];
				}
				if (!empty($_GET['reset']) || !empty($_SESSION['reset'])){
					$_SESSION['search'] 		= '';
					$_SESSION['category'] 		= '';
					$_SESSION['product_group']	= '';
					$search 		= $_SESSION['search'];
					$category 		= $_SESSION['category'];
					$product_group 	= $_SESSION['product_group'];
				}
		
			?>
			<form class="form-horizontal" action="product.php?sub=list" method="post">
						<div class="form-group">
						<?php	
							$sql= "SELECT * FROM sma_product_group where 1 order by product_group ";
							$q2 = mysqli_query($con, $sql);	
							
						?>
							<div class="col-md-2">
								<label class=" control-label">Product&nbsp;Group</label>
								<select class="form-control" name="product_group" id="product_group" >
								<option value=""> Select </option>
								<?php while($r2 = mysqli_fetch_array($q2)){ ?>
								<option value="<?= $r2['id'] ?>" <?php echo ($product_group == $r2['id'])?'selected="selected"':'';?> ><?= $r2['product_group'] ?></option>
								<?php } ?>	
								</select>
							</div>
								
							<div class="col-md-3">
								<label class="control-label">Search by Name</label>
								<input type="text" class="form-control " name="search" id="search" value="<?= $search; ?>" >
										
							</div>
								
							<div class="col-md-2">	
								<div class="col-sm-2a" >
								<label for="user_category" class="control-label col-sm-2">Category</label>
								<select class="form-control" name="category" id="category" >
									<option value=""> Select </option>
									<option value="M" <?= ($category=='M')?'selected="selected"':'';?>> Material </option>
									<option value="S" <?= ($category=='S')?'selected="selected"':'';?>> Service </option>
								</select>	
									
								</div>
							</div>
							
							<div class="col-md-2">
                                <label class="control-label">&nbsp;</label><BR>	
								<input class="btn btn-success" type="submit" value="Search" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="product.php?sub=list&reset=1" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
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
				
              <h3 class="box-title">List of Products</h3>
			<?php
				$targetpage = "product.php?sub=list"; 
				$limit = 10; 
				$start = 0;	
				if( $user=='Daksh123' || $user=='Raviraj123' || $user = 'femi.s@nxt-infra.com' ){
			?> 
				<span class="pull-right"><a href="product.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>Create </a></span>
				<!-- Ruchi started-->
			<?php } ?>	
				<span class="pull-right">
					<a href="product_export.php?sub=list" name="btnAdd" target="_blank" class="btn btn-info"><i class="splashy-document_letter_add"></i>Export</a>&nbsp;&nbsp;&nbsp;&nbsp;
				</span>
				
				<!--<span class="pull-right">-->
				<!--	<a href="product_companywise_export.php?sub=list" name="btnAdd" target="_blank" class="btn btn-info"><i class="splashy-document_letter_add"></i>Export Companywise </a>&nbsp;&nbsp;&nbsp;&nbsp;-->
				<!--</span>-->
				
				<!-- Ruchi ended-->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
	
    <table id="prtable123" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>Product Name</th>
			<th>Product Group</th>
			<th>Budget Group</th>
			<th>Category</th>
			<th>Unit</th>
			<th>Active</th>
			<th style="text-align:right;">Action</th>
			
		</tr>
	</thead>
<tbody>
<?php
	
	$modulePath1 = 'product/';
	
	$sql="SELECT * from sma_product where 1   ";

	$query="SELECT count(*) as num from sma_product where 1  ";
	
	
	if(!empty($category)){
		$sql   .= " and category = '$category' ";
		$query .= " and category = '$category' ";
	}
	if(!empty($product_group)){
		$sql   .= " and product_group = '$product_group' ";
		$query .= " and product_group = '$product_group' ";
	}
		
	if(!empty($search)){
		$sql 	.= " and (name like '%$search%' || budget_code in (select budget_code from sma_budget where 1 and budget_code like '%$search%' || budget_head  like '%$search%' ) )";
		
		$query 	.= " and (name like '%$search%' || budget_code in (select budget_code from sma_budget where 1 and budget_code like '%$search%' || budget_head  like '%$search%' ) ) ";
	}
		
					$qresult = mysqli_query($con,$query);
					echo mysqli_error($con);
					$total_p = mysqli_fetch_array($qresult);
					$total_pages = $total_p['num'];
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
		
	
		$sql .= ' order by name ';
		
		$sql .= " LIMIT $start, $limit ";
	// Initial page num setup
	if ($page == 0){$page = 1;}
	$prev = $page - 1;	
	$next = $page + 1;							
	$lastpage = ceil($total_pages/$limit);		
	$LastPagem1 = $lastpage - 1;					
	
	$paginate = '';

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
		
		$product_group = $row['product_group'];
		$sql= "SELECT * FROM sma_product_group where id = '$product_group' ";
		$q2 = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$product_group = $r2['product_group'];
		
		$budget_name = $row['budget_name'];
		$sql= "SELECT * FROM sma_budget_name where id = '$budget_name' ";
		$q2 = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$budget_name = $r2['name'];
		
		$category		= $row['category'];
		if($category=='S'){
			$category = 'Service';
		}	
		else if($category=='M'){
			$category = 'Material';
		}
		/* $budget_code		= $row['budget_code'];
		$sql 	= "select * from sma_budget where 1 and budget_code = '$budget_code' ";
		$q22 	= mysqli_query($con, $sql);
		$r22 	= mysqli_fetch_array($q22);
		$budget_head = $r22['budget_head'];
		$budget_code 	= $row['budget_code']; 
		*/

		$active		= $row['active'];
		if($active=='Y'){
			$active = 'Yes';
		}	
		else if($active=='N'){
			$active = 'No';
		}
		
		$baseurl1 = $baseurl.$modulePath1.'product.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "product.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="20%"><?php echo $row['name'];?></td>
		<td width="15"><?php echo $product_group;?></td>
		<td width="15"><?php echo $budget_name;?></td>
		
		<td width="15"><?php echo $category;?></td>
		<td width="10%"><?php echo $row['uom'];?></td>
		<td width="10%"><?php echo $active;?></td>
		
		<td width="6%" style="text-align:right;">
		
			<a href="product.php?sub=edit&id=<?php echo $row['id'];?>&page=<?= $page;?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		    
		<!--<a href="product.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
		
		</td>
    </tr>
	</a>
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
</section>
  </div>

  <!-- /.content-wrapper -->

</div>

    <?php 
	
	//exit();
	
	}?>


<?php  
	if($_GET['sub'] == 'delete'){ 
        $id = $_GET['id'];
		$sql="SELECT * FROM `sma_po_items` where product_id = '$id' ";
		mysqli_query($con, $sql);
		$row_affected = mysqli_affected_rows($con);
		
        if($row_affected >0 ){
			echo '<script>alert("Material exist on transactions...could not delete");</script>';
		}
		else {
			$company_id ='';
			$sql	="Select * from sma_product where id ='$id'";
			$query 	= mysqli_query($con, $sql);
			$row 	= mysqli_fetch_array($query);
			$name 			= $row['name'];
			$category 		= $row['category'];
			$product_group 	= $row['product_group'];
			$pgname = "product.php";
			include "../viewonly.php";
			$description = $name.', '. $category. ','. $product_group;
		    $user_name= $_SESSION['user']; 
		    $affect = 'Deleted';
			
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
			$sql = "DELETE from sma_product where id='$id' ";
			$query1 = mysqli_query($con, $sql);
			echo mysqli_error($con);
		}
        echo "<script>window.location.href='product.php?sub=list';</script>";
	} 
?>

<?php if($_GET['sub'] == 'add'){
?>

	
<?php
	if(isset($_POST['Save'])){
		
			$name				= $_POST['name'];
			$product_group		= $_POST['product_group'];
			$category			= $_POST['category'];
			$uom				= $_POST['uom'];
			$hsn_code			= $_POST['hsn_code'];
			$gst_type			= $_POST['gst_type'];
			$budget_name		= $_POST['budget_name'];
			$budget_head		= $_POST['budget_head'];
			$budget_code		= $_POST['budget_code'];
			$po_threashold		= $_POST['po_threashold'];
			//$tolerance_level	= $_POST['tolerance_level'];
			$active				= 'Y';
			$approval_status 	= 'Draft';
			
			$sql = " select id, budget_name, budget_head, budget_code  
    						from sma_budget_subgroup 
    							where 1 AND id = '$budget_head' ";
            $qry = mysqli_query($con, $sql);
            $r2 = mysqli_fetch_array($qry);	
		    $budget_name 	= $r2['budget_name'];
		    $budget_code 	= $r2['budget_code'];
		    
			$sql = "select * from sma_product where 1 and `name` = '$name' ";
			$q22 	= mysqli_query($con, $sql);
			$rowaffect 	= mysqli_affected_rows($con);
			$r22 = mysqli_fetch_array($q22);
			$product_name = $r22['name'];
			
			if($rowaffect>0){
				echo "<script>window.location.href='product.php?sub=add&pn=$name';</script>";
				exit();
			}
			
			$tolerance_level ='';
  			$sql = " insert into sma_product (name, category, `product_group`, uom, gst_type, hsn_code, budget_name, budget_head, budget_code, po_threashold, tolerance_level, approval_status, active ) 
  			            Values('$name', '$category', '$product_group', '$uom', '$gst_type', '$hsn_code', '$budget_name', '$budget_head', '$budget_code', '$po_threashold', '$tolerance_level', '$approval_status', 'Y' )";

				$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			$last_id = mysqli_insert_id($con);
			if(!empty($error)){echo $error; exit();}
			
			$company_id 	= '';
			$pgname 		= "product.php";
			include "../viewonly.php";
			$description 	= $name.', '. $category. ','. $product_group;
		    $user_name		= $_SESSION['user'];
		    $affect 		= 'Added';
			
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
			$sql = " INSERT into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved_date ) 
						VALUES( 'PD', '$last_id', '$userid', now(), 'Draft', '', now() ) ";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
				
			$page					= $_POST['page']; 
			
			//echo "Products successful added";
			//$baseurl1 = $modulePath."&same_page=$page";
			//echo "<script>window.location.href='$baseurl1';</script>";
			echo "<script>window.location.href='product.php?sub=list';</script>";
			
			exit();
			
		}
	

?>

    <section class="content-header">
        <h1>
            Products
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'product/'.$modulePath  ?>" ><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl .'product/'. $modulePath ?>">Products</a></li>
            <li class="active">Create</li>
        </ol>
    </section>

	
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            
            <!-- /.box-header -->
            <div class="box-body">
		
        <!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->

            <!-- form start -->
            <form class="form-horizontal" action="product.php?sub=add" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Product Name*</label>
							<div class="col-md-5">
								<input type="text" class="form-control" required id="product_name" name="name" placeholder="" autocomplete="off" value="<?= $_GET['pn']; ?>">
							</div>
							
							<span  class="duplicatecheck"> 
								<?php  
									if(!empty($_GET['pn'])){ ?>
										<div class="col-md-5">
											<label class=" control-label" style="color:red;" >Product Name already exist...</label>
										</div>
								<?php  } ?>
							</span>
							
						</div>
		
						<div class="form-group">
							<label class="col-lg-2 control-label">Product Group*</label>
							<div class="col-md-3">
								<select class="form-control" name="product_group" id="product_group" required="true" >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_product_group where 1 order by product_group ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" ><?php echo $r2['product_group'];?></option>
										<?php } ?>
								</select>
							</div>
						</div>
						
						
						<div class="form-group">	
						
							<label for="user_category" class="control-label col-sm-2">Category* <span data-toggle="tooltip" title="For Inventory Product, Select Material" class="badge bg-light-blue">?</span> </label>
							<div class="col-sm-2" style="padding-top: 6px;">
								<input type="radio" class="minimal"   name="category" id="category" value="M" > Material &nbsp;
                            	<input type="radio"  class="minimal"  name="category" id="category" value="S" > Service &nbsp;
								
							</div>
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Unit Of Measurement*</label>
							<div class="col-md-3">
								<select class="form-control" name="uom" id="uom" required="true">
									<option value=""> Select </option>
										<?php $sql = "select * from sma_units order by name ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['name'];?>"  <?php echo ($uom == $r2['name'])?'selected="selected"':'';?> ><?php echo $r2['name'];?></option>
										<?php } ?>
								</select>
							</div>
						</div>
						
						
							<div class="form-group">
											
								<label class="col-lg-2 control-label">GST Type*</label>
								<div class="col-md-3">
									
									<select class="form-control" required name="gst_type" id="gst_type" >
										<option value=""> Select </option>
										<?php 
											$sql = "select * from gst_mst where 1 order by gst_name ";
											$q22 	= mysqli_query($con, $sql);
											while($r22 = mysqli_fetch_array($q22)){ 
										?>
										<option value="<?php echo $r22['id'];?>" ><?php echo $r22['gst_name'];?></option>
										<?php } ?>
									</select>
												
								</div>
							</div>
										
							
						<!--<div class="form-group">	
						
							<label for="user_category" class="control-label col-sm-2">PO Threashold on</label>
							<div class="col-sm-2" style="padding-top: 6px;">
								<input type="radio" class="minimal"  name="po_threashold" id="po_threashold" value="Q" > Qty &nbsp;
                            	<input type="radio"  class="minimal"  name="po_threashold" id="po_threashold" value="V" > Value &nbsp;
								
							</div>
						</div>-->
						
						<!--<div class="form-group">-->
							
						<!--	<label class="col-lg-2 control-label">Tolerance Level%</label>-->
						<!--	<div class="col-md-2">-->
						<!--		<input type="text" class="form-control" id="tolerance_level" name="tolerance_level" value="<?php echo $row['tolerance_level'];?>" >-->
						<!--	</div>-->
						<!--</div>-->
						
						<div class="form-group">
							
							<label class="col-lg-2 control-label">HSN Code</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="hsn_code" name="hsn_code" placeholder="" value="" >
							</div>
						</div>
						
						<div class="form-group">
						    <label class="col-lg-2 control-label">Budget Group *<span data-toggle="tooltip" title="Select Budget Group which will be Blocked/Consumed when this product will be used" class="badge bg-light-blue">?</span></label>
							<div class="col-md-5">
    						    <select class="form-control select2 " <?= $readonly; ?> <?= $disabled;?> name="budget_head" id="budget_head" >
    								<option value=""> Select </option>
    								<?php $sql = " select distinct(a.id), a.budget_name, a.budget_head, a.budget_code  
    										from sma_budget_subgroup a, sma_budget_name b 
    										where 1 AND a.budget_name = b.id 
    										order by a.budget_head ";
    									$q22 	= mysqli_query($con, $sql);
    									while($r22 = mysqli_fetch_array($q22)){ ?>
    									<option value="<?php echo $r22['id'];?>" <?php echo ($budget_head == $r22['id'])?'selected="selected"':'';?> ><?php echo $r22['budget_head'];?></option>
    								<?php } ?>
    							</select>
						    </div>
						    
						</div>
		
						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; ?>
								
							</div>
							<?php $baseurl1 = $baseurl.'product/'.$modulePath;?>
							<div class="col-sm-6 text-right">
								<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Cancel</a>
								<span>&nbsp;&nbsp;</span>
								<input class="btn btn-primary" type="submit" value="Save" name="Save" 
								onclick="duplicateCheck();">&nbsp;&nbsp;&nbsp;
								
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
</section>	  
  <!-- /.content-wrapper -->
<?php } 	?>


<?php if($_GET['sub'] == 'edit'){
?>
	
<?php
	if(isset($_POST['Save'])){
		
			$id				= $_POST['id'];
			$name			= $_POST['name'];
			$product_group	= $_POST['product_group'];
			$category		= $_POST['category'];
			$uom			= $_POST['uom'];
			$budget_head     = $_POST['budget_head'];
			$budget_name     = $_POST['budget_name'];
			$hsn_code		= $_POST['hsn_code'];
			$gst_type		= $_POST['gst_type'];
			$exp_flag		= $_POST['exp_flag'];
			$active			= $_POST['active'];
			
			$name_prev		= trim($_POST['name_prev']);
//             if($name_prev !=$name){
// 			    $sql="SELECT * FROM sma_product where 1 and name ='$name' ";
//         		mysqli_query($con, $sql);
//         		$rowaffect = mysqli_affected_rows($con);
//         		if($rowaffect>0){
//         		    echo "<script>alert('Error: Product Already available ...');</script>";
//         		    echo "<script>window.location.href='product.php?sub=edit&id=$id';</script>";
//         		    exit();
//         		}
// 			}
			
			$sql = " select id, budget_name, budget_head, budget_code  
    						from sma_budget_subgroup 
    							where 1 AND id = '$budget_head' ";
            $qry = mysqli_query($con, $sql);
            $r2 = mysqli_fetch_array($qry);	
		    $budget_name 	= $r2['budget_name'];
		    $budget_code 	= $r2['budget_code'];
		
    //         $sql = " select * from sma_budget_name b 
    // 							where 1 AND id = '$budget_name' "; 
    // 		$qry = mysqli_query($con, $sql);
    //         $r2 = mysqli_fetch_array($qry);	
		  //  $budget_name 	= $r2['id'];
    										
			$po_threashold		= $_POST['po_threashold'];
			//$tolerance_level	= $_POST['tolerance_level'];
			$tolerance_level	= '';
			
			$approver_1			= $_POST['approver_1'];
			$approver_2			= $_POST['approver_2'];
			$approver_3			= $_POST['approver_3'];
			$approver_4			= $_POST['approver_4'];
			$approver_5			= $_POST['approver_5'];
			$approver_6			= $_POST['approver_6'];
			$approver_7			= $_POST['approver_7'];
			$approver_8			= $_POST['approver_8'];
			$approval_status	= $_POST['approval_status'];
			
  			$sql="update sma_product set 	name = '$name',
						product_group	= '$product_group',
						uom				= '$uom',
						category		= '$category',
						hsn_code		= '$hsn_code',
						gst_type		= '$gst_type',
						exp_flag		= '$exp_flag',
						active			= '$active',
						po_threashold	= '$po_threashold',
						tolerance_level	= '$tolerance_level',
						budget_name 	= '$budget_name',
		                budget_head 	= '$budget_head'
					where id='$id'";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}

// 			,
// 						budget_head     = '$budget_head',
// 			            budget_name     = '$budget_name',
// 			            budget_code     = '$budget_code'
			            
			$company_id 	= '';
				$pgname 		= "product.php";
				include "../viewonly.php";
				$description 	= $name.', '. $category. ','. $product_group;
				$user_name		= $_SESSION['user'];
				$affect 		= 'Modified';
				
				$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
				VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
				mysqli_query($con, $sql);
//echo $sql. "<BR>";
//exit();

			if( !empty($approver_1) && $approval_status == 'Draft' ){
				$sql="select * from sma_user where id='$userid' and active='1' ";				
				$result = mysqli_query($con, $sql);
				$r = mysqli_fetch_object($result);
				$draft_by		= $r->userid;
					
				$status				= 'Submitted';
				$approver_1_status  = 'Submitted';
				$sql = "update sma_product set current_approver = '$approver_1',
						approver_1			= '$approver_1',
						approver_1_status	= '$approver_1_status',
						approval_status		= '$status',
						draft_by			= '$draft_by'
					where id='$id'";	
				$query=mysqli_query($con, $sql);	
				
				$pr_id = $id;
				$sql = " insert into workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved_date ) 
				values( 'PD', '$pr_id', '$userid', now(), 'Submitted', '$approver_1', now() ) ";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
				
				$modulePath = "product/"; 
				
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
				
				
				$baseurl1 = $baseurl.$modulePath.'product.php?sub=edit&id='.$pr_id;
		
				//$baseurl1 = $baseurl.$modulePath.'edit.php?id='.$po_id;
				$btn_var = '<a href="'. $baseurl1 .'" class="btn btn-danger" style = "background-color: #6699CC;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Click for more information </a>';
				
				/* $baseurl1A = $baseurl.$modulePath.'editm.php?id='.$pr_id. '&status=A'.'&emid='.$user_email;
				$btn_varA = '<a href="'. $baseurl1A .'" class="btn btn-danger" style = "background-color: #00a65a;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Approve </a>';
				
				$baseurl1R = $baseurl.$modulePath.'editm.php?id='.$pr_id. '&status=R'.'&emid='.$user_email;
				$btn_varR = '<a href="'. $baseurl1R .'" class="btn btn-danger" style = "background-color: #b53737;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 10px 14px;text-align: center;font-weight: 400;font-size:16px;" >Reject </a>';
 */				
				$msg = 'Product : '.$pr_id . ' ' . 'Dated : ' . date("d-m-Y");

			//	include "pd_mail.php";
				
			}

//exit();
			
			$modulePath = "product.php?sub=list";
			$page					= $_POST['page']; 
			$baseurl1 = $modulePath."&same_page=$page";
			
			echo "<script>window.location.href='$baseurl1';</script>";
			
			exit();

		}
		
		$id 		= $_GET['id'];
		$product_id = $_GET['id'];
		
		$page = $_GET['page'];
		
		$sql="Select * from sma_product where id ='$id'";
//echo $sql;
		
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

		$product_id 	= $row['id'];
		$product_name = $row['name'];
		$budget_code = $row['budget_code'];
		$budget_name = $row['budget_name'];
		
		$readonly = '';
		if($role!='Checker'){
		
		//	$readonly = "READONLY";
			
		}
		
		//$sql="Select * from sma_po_items where product_id ='$id'";
		$sql="SELECT * from sma_purchase_order a, sma_po_items b where 1 and a.id = b.purchase_id and b.product_id = '$product_id' and a.del != 'Y' and dated >= '$from_date' and dated <= '$to_date' ";
		$qry = mysqli_query($con, $sql);
		$rowaffected = mysqli_affected_rows($con);
		if($rowaffected>0){
			$readonly = "READONLY";
		}
		
		$sql="Select * from sma_expenses where 1 and reference ='$id'";
		$qry = mysqli_query($con, $sql);
		$rowaffecteda = mysqli_affected_rows($con);
		if($rowaffecteda>0){
			$readonly = "READONLY";
		}
	
		$sql = " SELECT from_date, to_date, short_fy_code, finyear_prefix, status FROM `sma_financial_year` where status = 'Y' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$short_fy_code 	= $r2['short_fy_code'];
		$from_date 	= $r2['from_date'];
		$to_date 	= $r2['to_date'];
		
?>
    <section class="content-header">
        <h1>
            Products
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'product/'.$modulePath  ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl .'product/'. $modulePath ?>">Products</a></li>
        </ol>
    </section>
		
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
         <!--<div class="box-header">
            <div class="pull-right">
				<span class="sepV_c marginRight">
					<a href="product.php?sub=list&page=0" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Back</a>
				</span>
			</div>
		</div>-->
		<!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box-body">
            <!-- form start -->
            <form class="form-horizontal" action="product.php?sub=edit&same_page=<?= $page ?>" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
			  <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_1" data-toggle="tab" id="first_tab" > Product </a></li>
                    <!--<li><a href="#tab_2" data-toggle="tab" class="btn btn-info" id="forth_tab" >Workflow History</a></li>-->
				</ul>
					
					<div class="tab-content">
					    <div class="tab-pane active " id="tab_1">
						
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
					  
					  <input type="hidden" id="product_id" value="<?php echo $row['id'];?>">
					  
						<input type="hidden" name="page" value="<?= $page;?>">
						
						<input type="hidden" name="approval_status" value="<?= $row['approval_status'];?>">
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Product Name *</label> 
							<div class="col-md-5">
								<input type="text" class="form-control" <?= $readonly; ?> id="product" name="name"  
								value='<?php echo $product_name ?>'  >
								
								<input type="hidden"  name="name_prev"  value="<?php echo $row['name'];?>" >
								
							</div>
					
						</div>
						
						<div class="form-group">
						
							<label class="col-lg-2 control-label">Product Group *</label>
							<div class="col-md-4">
								<select class="form-control" name="product_group" <?= $readonly; ?> id="product_group" required="true" >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_product_group where 1 order by product_group ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($row['product_group'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['product_group'];?></option>
									<?php } ?>
								</select>
								
							</div>
						
						</div>
						
						
						<?php 
							$po_count = 0;
								
								$selected_service 	= '';
								$selected_material	= '';
								$category = $row['category'];
								if($category=='M' ){
									$selected_material = 'checked';
								}
								else if($category=='S'){
									$selected_service = 'checked';
								}
						/* if($user !='Admin'){
							$sql="SELECT * from sma_purchase_order a, sma_po_items b where 1 and a.id = b.purchase_id and b.product_id = '$product_id' and a.del != 'Y' and dated >= '$from_date' and dated <= '$to_date' ";
							mysqli_query($con,$sql);
							$po_count = mysqli_affected_rows($con);
						} */	

			//echo $po_count ."<<>>".  $readonly. ' ' . $category;
			
						?>
						
						<div class="form-group">	
							<label for="user_category" class="control-label col-sm-2">Category* <span data-toggle="tooltip" title="For Inventory Product, Select Material" class="badge bg-light-blue">?</span></label>
							<div class="col-sm-2" style="padding-top: 6px;">
					<?php if( ( !empty($readonly) ) && $category=='M' ){
					?>	
								<input type="radio" class="minimal"  <?php echo $selected_material;  ?>  name="category" id="category" value="M" > <b>Material </b>&nbsp; <span data-toggle="tooltip" title="Product Already in Use, Don't change" class="badge bg-light-blue">?</span>
					<?php	
							}
							else if( ( !empty($readonly) ) && $category=='S'  ){
					?>	
								<input type="radio"  class="minimal" <?php echo $selected_service; ?>  name="category" id="category" value="S" > <b>Service </b>&nbsp; <span data-toggle="tooltip" title="Product Already in Use, Don't change" class="badge bg-light-blue">?</span>
					<?php	
							}
						else {	
					?>
							
								<input type="radio" class="minimal"  <?php echo $selected_material;  ?> <?= $disabled; ?>  name="category" id="category" value="M" > Material &nbsp;
                            	<input type="radio"  class="minimal" <?php echo $selected_service; ?>  <?= $disabled; ?> name="category" id="category" value="S" > Service &nbsp;
					<?php } ?>			
							</div>
						</div>	
						
						<?php 
						
								$selected_qty 	= '';
								$selected_value	= '';
								$po_threashold = $row['po_threashold'];
								if( $po_threashold=='Q' ){
									$selected_qty = 'checked';
								}
								else if( $po_threashold=='V' ){
									$selected_value = 'checked';
								}
								
						$uom = $row['uom'];	
						if(!empty($readonly)){ 
						    $sqla = " AND name = '$uom' ";
						}
						?>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Unit Of Measurement *</label>
							<div class="col-md-3">
								<select class="form-control" <?= $readonly; ?> name="uom" id="uom" required="true" >
						<?php if(empty($readonly)){ ?>
									<option value=""> Select </option>
						<?php } ?>			
										<?php $sql = "select * from sma_units where 1 $sqla order by name ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['name'];?>"  <?php echo ($row['uom'] == $r2['name'])?'selected="selected"':'';?> ><?php echo $r2['name'];?></option>
										<?php } ?>
								</select>
							</div>
						
						</div>
						
						<?php 
							$budget_code = $row['budget_code'] ; 
							$gst_type   = $row['gst_type'] ; 
							$sqlb = "";
						if(!empty($gst_type)){	
							$sqlb = " AND id = '$gst_type' ";	
						}	
						?>
						
							<div class="form-group">
								
								<label class="col-lg-2 control-label">GST Type *</label>
								
								<div class="col-md-3">
						
								<select class="form-control" <?= $readonly; ?> required name="gst_type" id="gst_type" >
							<?php if(empty($readonly)){ ?>
									<option value=""> Select </option>
							<?php } ?>	
									<?php 
										$sql = "select * from gst_mst where 1 $sqlb order by gst_name ";
										$q22 	= mysqli_query($con, $sql);
										while($r22 = mysqli_fetch_array($q22)){ 
									?>
									<option value="<?php echo $r22['id'];?>" <?php echo ($gst_type == $r22['id'])?'selected="selected"':'';?> ><?php echo $r22['gst_name'];?></option>
									<?php } ?>
								</select>
											
							</div>
							
							</div>
							
					
				<!--	<div class="form-group">		
							<label for="user_category" class="control-label col-sm-2">PO Threashold on</label>
							<div class="col-sm-2" style="padding-top: 6px;">
								<input type="radio" class="minimal" <?php //echo $selected_qty; ?> name="po_threashold" id="po_threashold" value="Q" > Qty &nbsp;
                            	<input type="radio"  class="minimal" <?php //echo $selected_value; ?> name="po_threashold" id="po_threashold" value="V" > Value &nbsp;
							</div>
						</div>
				-->		
						<!--<div class="form-group">-->
						<!--	<label class="col-lg-2 control-label">Tolerance Level%</label>-->
						<!--	<div class="col-md-2">-->
						<!--		<input type="text" class="form-control" <?= $readonly; ?> id="tolerance_level" name="tolerance_level" value="<?php echo $row['tolerance_level'];?>" >-->
						<!--	</div>-->
						<!--</div>-->
						
						<div class="form-group">
							<label class="col-lg-2 control-label">HSN Code</label>
							<div class="col-md-3">
								<input type="text" class="form-control" <?= $readonly; ?> id="hsn_code" name="hsn_code" value="<?php echo $row['hsn_code'];?>" >
							</div>
						</div>
						
				<?php 
				    $budget_head = $row['budget_head'];
				?>
						<div class="form-group">
						    <label class="col-lg-2 control-label">Budget Group*<span data-toggle="tooltip" title="Select Budget Group which will be Blocked/Consumed when this product will be used" class="badge bg-light-blue">?</span> </label>
						    <div class="col-md-5">
    						    <select class="form-control select2 " <?= $readonly .' '.  $disabled;?>   required name="budget_head" id="budget_head" >
    								<option value=""> Select </option>
    								<?php $sql = " select distinct(a.id), a.budget_name, a.budget_head, a.budget_code  , b.name as bname
    										from sma_budget_subgroup a, sma_budget_name b 
    										where 1 AND a.budget_name = b.id 
    										order by a.budget_head ";
    									$q22 	= mysqli_query($con, $sql);
    									while($r22 = mysqli_fetch_array($q22)){ ?>
    									<option value="<?php echo $r22['id'];?>" <?php echo ($budget_head == $r22['id'])?'selected="selected"':'';?> ><?php echo $r22['bname'];?></option>
    								<?php } ?>
    							</select>
						    </div>
						</div>
						
						<div class="form-group">
						
							<label class="col-lg-2 control-label">Budget Sub Group</label>
							<div class="col-md-5">
    						    <select class="form-control select2 " <?= $readonly; ?> <?= $disabled;?>  required  name="budget_name" id="budget_name" >
    								<option value=""> Select </option>
    								<?php $sql = " select distinct(a.id), a.budget_name, a.budget_head, a.budget_code  
    										from sma_budget_subgroup a, sma_budget_name b 
    										where 1 AND a.budget_name = b.id 
    										order by a.budget_head ";
    									$q22 	= mysqli_query($con, $sql);
    									while($r22 = mysqli_fetch_array($q22)){ ?>
    									<option value="<?php echo $r22['id'];?>" <?php echo ($budget_head == $r22['id'])?'selected="selected"':'';?> ><?php echo $r22['budget_head'];?></option>
    								<?php } ?>
    							</select>
						    </div>
							
						</div>	
						
				<?php

								$active_yes	= '';
								$active_no	= '';
								$active = $row['active'];
								if( $active=='Y' ){
									$active_yes = 'checked';
								}
								else if( $active=='N' ){
									$active_no = 'checked';
								}
								
				?>
						
						<div class="form-group">		
							<label for="user_category" class="control-label col-sm-2">Active</label>
							<div class="col-sm-2" style="padding-top: 6px;">
						
								<input type="radio" class="minimal" <?php echo $active_yes; ?> name="active" id="active" value="Y" > Yes &nbsp;
                            	<input type="radio"  class="minimal" <?php echo $active_no; ?> name="active" id="active" value="N" > No &nbsp;
								
							</div>
						</div>
				
					
						<div class="form-group">
						<center123>
                           
							<div class="col-sm-9">
							<?php $did = $_GET['id']; 
								/*$sql = " SELECT a.id, b.product_id FROM `sma_product` a, sma_po_items b 
										  where a.id = b.product_id and b.product_id = '$product_id' ";
								//echo $sql;	
								$res = mysqli_query($con,$sql);
								echo mysqli_error($con);
								$rowcount=mysqli_num_rows($res);
								if ( $rowcount=='0' ){*/
									//Mrunmayee started
									$sql = "SELECT * FROM `sma_supplier_invoice_details` where material_id='$did'";
									$sql2 = "SELECT count(*) from sma_po_items where product_id ='$did'";
								  $query=mysqli_query($con, $sql);
								   $error= mysqli_error($con);
								   $row= mysqli_fetch_array($query);
								   
								   $query2=mysqli_query($con, $sql2);
								   $error= mysqli_error($con);
								   $row2= mysqli_fetch_array($query2);
							//	   if($row <=0 && $row2<=0 && empty($readonly) ){
								
							?>
								<a href="<?php echo $baseurl."product/product.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a>
								
							<?php //} //Mrunmayee ended?>
							</div>
							<?php $baseurl1 = $baseurl.'product/'.$modulePath;?>
							<center>
							
						
							
							<div class="col-sm-1">
								<a href="product.php?sub=list&same_page=<?= $page;?>" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>Cancel</a>
							</div>	
					<?php //if(empty($readonly)){ ?>		
							<div class="col-sm-1">	
								<input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
							</div>
					<?php //} ?>	
							
							<div class="col-sm-1">
									<label class="control-label"><?=  "&nbsp"; ?></label>
							</div>
							
						</center>
                        </div>

						<div class="form-group">
							<div class="col-sm-12">
								<span id="getapprover">
									<?php	
								$approver_1 = $row['approver_1'];
								/*$approver_2 = $row['approver_2'];
								$approver_3 = $row['approver_3']; */
						
								if(!empty($approver_1)){
							?>	
								<div class="col-sm-3">
									<label class="control-label">Approver 1</label>
									<select class="form-control  approver_1" name="approver_1"   >
                                        <option value="">Select</option>
										<?php
										$sql = " select * from sma_user where FIND_IN_SET( $role, role ) ";
											$rs = mysqli_query($con, $sql);
										echo mysqli_error($con);
										while( $rw = mysqli_fetch_array($rs) ){
										?>
                                        <option value="<?php echo $rw['id']?>" <?php echo ($approver_1 == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
										<?php } ?>	
                                    </select>
								
								</div>
							<?php }	?>
							
								</span>
							</div>
							
						</div>
						
			</div>
				
			<div class="tab-pane " id="tab_2">

				<div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
						<div class="modal-header" >
								<p><?= $label_line; ?></p>
								<?php 
									
									$srno = $po_id;
									$s1  = "SELECT * from workflow_history where doc_id = '$product_id' and doc_type = 'PD' order by id desc  ";
							//echo $s1;		
									$res  = mysqli_query($con, $s1);
									echo mysqli_error($con);
									$r1 = mysqli_fetch_array($res);
									$create_by		= $r1['create_by'];
									$create_date	= date('d-m-Y', strtotime($r1['create_date']));
									
									$sl="SELECT * FROM sma_user where id = '$create_by' ";
									$r3 = mysqli_query($con, $sl);
									$rw = mysqli_fetch_array($r3);
									$create_by = $rw['username'];
					
								?>			
												
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
										
											$s1  = "SELECT * from workflow_history where doc_id = '$product_id' and doc_type = 'PD' order by id desc";
									//	echo $s1;
											$res  = mysqli_query($con, $s1);
											echo mysqli_error($con);
											while($r1 = mysqli_fetch_array($res)){
												$id 				= $r1['id'];
												
												$status				= $r1['status'];
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
					</div>
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
</section>	        
<?php } 	?>

<?php 	
		include("../footer.php");	
?>
<!-- Select2 -->
<script src="<?php echo $baseurl . "plugins/select2/select2.full.js" ?>"></script>

<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>

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
        CKEDITOR.replace('reason1');
        CKEDITOR.replace('reason2');
        CKEDITOR.replace('reason3');
        $("#prItemsTable").DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": false
        });
    });
	
</script>


<script>
	function getsubgroup(id){
        var sub    = 'sub1';
		var strURL = "../highway_func.php";
		$.post(strURL,{id:id,sub1:sub},function(result){
		      $('#getsubgroup').html(result);
		});
    }
	
	function getbudget(id){
        var sub    = 'sub2';
		var strURL = "highway_func.php";
		$.post(strURL,{id:id,sub2:sub},function(result){
		      $('#getbudget').html(result);
		});
    }
	
	function getbudgethead(id){
		var sub    = 'sub2';
//alert(sub);	
		var strURL = "account_func.php";
		$.post(strURL,{id:id,sub2:sub},function(result){
		      $('#getbudgethead').html(result);
		});
		
	}
	
	function getaccount_data(id){
		var sub    = 'sub1';
		var budget_name 	= $("#budget_name").val();
//alert(sub);	
		var strURL = "account_func.php";
		$.post(strURL,{id:id,budget_name:budget_name,sub1:sub},function(result){
		      $('#getaccount_data').html(result);
		});
		
	}

	function getccsubgroup (id, company_id){
		var sub    = 'sub3';
		var product_id 	= $("#product_id").val();
		var company_id 	= company_id;
		var budget_name = id;
//alert(sub + ' ' + company_id + ' ' + budget_name + ' ' + product_id);
		var strURL = "account_func.php";
		$.post(strURL,{company_id:company_id,product_id:product_id,budget_name:budget_name,sub3:sub},function(result){
		      $('.getccsubgroup'+company_id).html(result);
		});
		
	}	
	
	function showEdit(editableObj) {
			$(editableObj).css("background","#FFF");
	}
		
	function saveToDatabase(editableObj,column,company_id,product_id) {
		    
		var sub    = 'sub4';
	//		var rate = editableObj.innerHTML;
//		alert("UPDATE `column` set " + column + ' ' + editableObj + ' ' + company_id + ' ' + product_id);
		
		var strURL = "account_func.php";
			$.post(strURL,{column:column,editval:editableObj,company_id:company_id,product_id:product_id,sub4:sub},function(result){
		      $('.getbudetcode'+company_id).html(result);
			 
		});
			
	  }
	   
	function duplicateCheck(){

		var sub    = 'sub5';
		var product_name 	= $("#product_name").val();
	
		var strURL = "account_func.php";
			$.post(strURL,{product_name:product_name,sub5:sub},function(result){
		      $('.duplicatecheck').val(result);
//			alert(result);
			if(result>0){
			  alert('Product Name already exist...');
			  return false;
			}
			 
		});
		
	}		
	
	function getapprover(){
		
		/* var company_id    	= document.getElementById("projecT").value;
		var checker_value   = document.getElementById("checker_value").value;
		var trans_type    	= document.getElementById("trans_type").value;
		var po_type  	   	= document.getElementById("po_typea").value;
 */
		var sub = 'sub24';
		$('.hidesend').hide();
//alert(sub );
//alert(sub + ' ' + po_type + ' ' + trans_type + ' ' + company_id + ' ' + checker_value);	
		
		var strURL = "highway_func.php";
		$.post(strURL,{sub24:sub},function(result){
		      $('#getapprover').html(result);
			  
		});
		
	}
	
</script>

</body>
</html>
