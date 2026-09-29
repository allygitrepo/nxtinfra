<?php

include("../header.php");
$modulePath = "product.php?sub=list";
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
              <h3 class="box-title">List of Products</h3>
			<?php
				$targetpage = "product.php?sub=list"; 
				$limit = 10; 
				$start = 0;	
			?> 
				<span class="pull-right"><a href="product.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>Create Products </a></span>
			
				<span class="pull-right">
					<a href="product_export.php?sub=list" name="btnAdd" target="_blank" class="btn btn-info"><i class="splashy-document_letter_add"></i>Export</a>&nbsp;&nbsp;&nbsp;&nbsp;
				</span>

            </div>
            <!-- /.box-header -->
            <div class="box-body">
	
    <table id="prtable" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>Product Name</th>
			<th>Product Group</th>
			<th>Unit</th>
			<th>Account Name</th>
			<th>CC Code</th>
			<th style="text-align:right;">Action</th>
			
		</tr>
	</thead>
<tbody>
<?php
	
	$modulePath1 = 'product/';
	
	$sql="SELECT * from sma_product where 1   ";

	$query="SELECT count(*) as num from sma_product where 1  ";
	
					$qresult = mysqli_query($con,$query);
					echo mysqli_error($con);
					$total_p = mysqli_fetch_array($qresult);
					$total_pages = $total_p['num'];
					$stages = 3;
					//$page = mysqli_real_escape_string($_GET['page']);
		
					$page = ($_GET['page']);
					if($page){
						$start = ($page - 1) * $limit; 
					}
					else{
						$start = 0;	
					}	
		
		$sql .= ' order by name ';
		
		//$sql .= " LIMIT $start, $limit ";
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
		
		$product_group = $row['product_group'];
		$sql= "SELECT * FROM sma_product_group where id = '$product_group' ";
		$q2 = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$product_group = $r2['product_group'];
		
		$budget_code		= $row['budget_code'];
		$sql 	= "select * from account_mst where 1 and id = '$budget_code' ";
		$q22 	= mysqli_query($con, $sql);
		$r22 	= mysqli_fetch_array($q22);
		$account_name = $r22['account_name'];
										
		$budget_code 	= $row['budget_code'];
		
		$did = $row['id'];
		$sql =" select count(*) as cnt from sma_product a, sma_budget b where a.id='$did' and a.budget_code = b.budget_code ";		
		$query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2 = mysqli_fetch_array($query1);
		$cnt = $r2['cnt'];
								
		$baseurl1 = $baseurl.$modulePath1.'product.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "product.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="20%"><?php echo $row['name'];?></td>
		<td width="15"><?php echo $product_group;?></td>
		<td width="10%"><?php echo $row['uom'];?></td>
		<td width="20%"><?php echo $account_name;?></td>
		<td width="10%"><?php echo $budget_code;?></td>

		<td width="6%" style="text-align:right;">
		
			<a href="product.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
	<?php if($cnt==0){ ?>	    
		<a href="product.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>
	<?php } ?>	
		</td>
    </tr>
	</a>
	<?php }?>
</tbody> 
</table>

<?php
 /*  $end  =$start+10;
  $begin=$start+1;
  
  if ($end>$total_pages){ $end=$total_pages;}
  echo 'Showing ' . $begin .' to ' . $end . ' of ' . $total_pages . ' entries ';
  echo $paginate; */
?>

	</div>
    </div>
</div>	
</section>
  </div>

  <!-- /.content-wrapper -->

</div>

    <?php }?>


<?php  
	if($_GET['sub'] == 'delete'){ 
        $id = $_GET['id'];
		$sql="delete from sma_product where id='$id' and id not in (SELECT distinct(product_id) FROM `sma_po_items` where product_id = '$id') ";
		$row_affected = mysqli_affected_rows($con);
        if($row_affected ==0 ){
			echo '<script>alert("Material exist on transactions...could not delete");</script>';
		}
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
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
			$budget_code		= $_POST['budget_code'];
			$po_threashold		= $_POST['po_threashold'];
			$tolerance_level	= $_POST['tolerance_level'];
			
  			$sql = " insert into sma_product (name, category, `product_group`, uom, gst_type, hsn_code, budget_name, budget_code, po_threashold, tolerance_level ) Values('$name', '$category', '$product_group', '$uom', '$gst_type', '$hsn_code', '$budget_name', '$budget_code', '$po_threashold', '$tolerance_level' )";

				$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			echo "Products successful added";
			echo "<script>window.location.href='product.php?sub=list';</script>";
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
							<label class="col-lg-2 control-label">Product Name </label>
							<div class="col-md-5">
								<input type="text" class="form-control" id="department" name="name" maxlength="50" placeholder="" autocomplete="off" value="">
							</div>
						
						</div>
		
						<div class="form-group">
							<label class="col-lg-2 control-label">Product Group</label>
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
						
							<label for="user_category" class="control-label col-sm-2">Category</label>
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
											
								<label class="col-lg-2 control-label">GST Type</label>
								<div class="col-md-3">
									
									<select class="form-control" name="gst_type" id="gst_type" >
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
										
							<div class="form-group">
								<label for="user_category" class="control-label col-sm-2">Link to Tally A/c*</label>
												
								<div class="col-md-2">
									<select class="form-control select2" name="budget_code" id="budget_code" required="true" onchange="getaccount_data(this.value)" >
										<option value=""> Select </option>
										<?php $sql = "select distinct(budget_code) as budget_code from sma_budget where 1 order by budget_code ";
										$q22 	= mysqli_query($con, $sql);
										while($r22 = mysqli_fetch_array($q22)){ ?>
										<option value="<?php echo $r22['budget_code'];?>" ><?php echo $r22['budget_code'];?></option>
										<?php } ?>
									</select>
								</div>

								<span id="getaccount_data">
								</span>
													
							</div>
						
						
						<div class="form-group">	
						
							<label for="user_category" class="control-label col-sm-2">PO Threashold on</label>
							<div class="col-sm-2" style="padding-top: 6px;">
								<input type="radio" class="minimal"  name="po_threashold" id="po_threashold" value="Q" > Qty &nbsp;
                            	<input type="radio"  class="minimal"  name="po_threashold" id="po_threashold" value="V" > Value &nbsp;
								
							</div>
						</div>
						
						<div class="form-group">
							
							<label class="col-lg-2 control-label">Tolerance Level%</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="tolerance_level" name="tolerance_level" value="<?php echo $row['tolerance_level'];?>" >
							</div>
						</div>
						
						<div class="form-group">
							
							<label class="col-lg-2 control-label">HSN Code</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="hsn_code" name="hsn_code" placeholder="" value="" >
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
			$hsn_code		= $_POST['hsn_code'];
			$gst_type		= $_POST['gst_type'];
			$budget_name		= $_POST['budget_name'];
			$budget_code		= $_POST['budget_code'];
			
			$po_threashold	= $_POST['po_threashold'];
			$tolerance_level	= $_POST['tolerance_level'];
			
  			$sql="update sma_product set 	name = '$name',
						product_group	= '$product_group',
						uom				= '$uom',
						category		= '$category',
						hsn_code		= '$hsn_code',
						gst_type		= '$gst_type',
						budget_name		= '$budget_name',
						budget_code		= '$budget_code',
						po_threashold	= '$po_threashold',
						tolerance_level	= '$tolerance_level'
					where id='$id'";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			$baseurl1 = $modulePath;
			echo "<script>window.location.href='$baseurl1';</script>";
		}
		
		$id 		= $_GET['id'];
		$product_id = $_GET['id'];
		
		$sql="Select * from sma_product where id ='$id'";
//echo $sql;
		
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

		$product_name = $row['name'];
		$budget_code = $row['budget_code'];
		$budget_name = $row['budget_name'];
		
		if($role!='Checker'){
		
			$readonly = "READONLY";
			
		}
		
?>
    <section class="content-header">
        <h1>
            Products
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'product/'.$modulePath  ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Products</a></li>
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
            <form class="form-horizontal" action="product.php?sub=edit" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Product Name </label> 
							<div class="col-md-5">
								<input type="text" class="form-control" id="product" name="name" maxlength="50" 
								value='<?php echo $product_name ?>'  >
							</div>
						
						</div>
						
						<div class="form-group">
						
							<label class="col-lg-2 control-label">Product Group</label>
							<div class="col-md-4">
								<select class="form-control" name="product_group" id="product_group" required="true" >
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
						
								$selected_service 	= '';
								$selected_material	= '';
								$category = $row['category'];
								if($category=='M' ){
									$selected_material = 'checked';
								}
								else if($category=='S'){
									$selected_service = 'checked';
								}
								
						?>
						
						<div class="form-group">	
							<label for="user_category" class="control-label col-sm-2">Category</label>
							<div class="col-sm-2" style="padding-top: 6px;">
								<input type="radio" class="minimal"  <?php echo $selected_material; ?>  name="category" id="category" value="M" > Material &nbsp;
                            	<input type="radio"  class="minimal" <?php echo $selected_service; ?>  name="category" id="category" value="S" > Service &nbsp;
								
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
								
						?>
						
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Unit Of Measurement*</label>
							<div class="col-md-3">
								<select class="form-control" name="uom" id="uom" required="true" >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_units order by name ";
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
						?>
						
							<div class="form-group">
								
								<label class="col-lg-2 control-label">GST Type</label>
								
								<div class="col-md-3">
						
								<select class="form-control" name="gst_type" id="gst_type" >
									<option value=""> Select </option>
									<?php 
										$sql = "select * from gst_mst where 1 order by gst_name ";
										$q22 	= mysqli_query($con, $sql);
										while($r22 = mysqli_fetch_array($q22)){ 
									?>
									<option value="<?php echo $r22['id'];?>" <?php echo ($gst_type == $r22['id'])?'selected="selected"':'';?> ><?php echo $r22['gst_name'];?></option>
									<?php } ?>
								</select>
											
							</div>
							
							</div>
							
							<div class="form-group">
								<label for="user_category" class="control-label col-sm-2">Link to Tally A/c*</label>
									
								<div class="col-md-2">
									<select class="form-control" name="budget_code" id="budget_code" required="true" onchange="getaccount_data(this.value)" >
										<option value=""> Select </option>
											<?php $sql = "select distinct(budget_code) as budget_code from sma_budget where 1 order by budget_code ";
											$q22 	= mysqli_query($con, $sql);
											while($r22 = mysqli_fetch_array($q22)){ ?>
										<option value="<?php echo $r22['budget_code'];?>"  <?php echo ($budget_code == $r22['budget_code'])?'selected="selected"':'';?> ><?php echo $r22['budget_code'];?></option>
										<?php } ?>
									</select>
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
								<label class=" col-md-1 control-label">CC Name</label>
								<div class="col-md-2">
								<input type="hidden" class="form-control" name="budget_name" id="budget_name" readonly value="<?php echo $budget_name_id;?>" >
								
								<input type="text" class="form-control"  readonly value="<?php echo $budget_name;?>" >
								</div>
								
								<label class="control-label col-md-1">CC Head</label>
								<div class="col-md-4">
								<input type="text" class="form-control" name="budget_head" id="budget_head" readonly value="<?php echo $budget_head;?>" >
								</div>
							</span>
													
						</div>
						
						
						<div class="form-group">		
							<label for="user_category" class="control-label col-sm-2">PO Threashold on</label>
							<div class="col-sm-2" style="padding-top: 6px;">
								<input type="radio" class="minimal" <?php echo $selected_qty; ?> name="po_threashold" id="po_threashold" value="Q" > Qty &nbsp;
                            	<input type="radio"  class="minimal" <?php echo $selected_value; ?> name="po_threashold" id="po_threashold" value="V" > Value &nbsp;
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Tolerance Level%</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="tolerance_level" name="tolerance_level" value="<?php echo $row['tolerance_level'];?>" >
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">HSN Code</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="hsn_code" name="hsn_code" value="<?php echo $row['hsn_code'];?>" >
							</div>
						</div>
						
						<div class="form-group">
						<center123>
                            <div class="col-sm-6">
								
                            </div>
						<center>
							<div class="col-sm-2">
							</div>
							<div class="col-sm-2">
								<a href="product.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
							</div>	
							<div class="col-sm-2">	
								<input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;
                                &nbsp;
							</div>
						</center>
                        </div>

						<div class="form-group">
							<div class="col-sm-6">
							<?php $did = $_GET['id']; 
								$sql = " SELECT a.id, b.product_id FROM `sma_product` a, sma_po_items b 
										  where a.id = b.product_id and b.product_id = '$product_id' ";
								//echo $sql;	
								$res = mysqli_query($con,$sql);
								echo mysqli_error($con);
								$rowcount=mysqli_num_rows($res);
								if ( $rowcount=='0' ){
								
							?>
								<a href="<?php echo $baseurl."product/product.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a>
								
							<?php } ?>	
							</div>
							<?php $baseurl1 = $baseurl.'product/'.$modulePath;?>
							<div class="col-sm-6 text-right">
								
						
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
<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>

<script>
    $(function () {
        $("#prtable").DataTable();
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
	
	function getaccount_data(id){
		var sub    = 'sub1';
//alert(sub);	
		var strURL = "account_func.php";
		$.post(strURL,{id:id,sub1:sub},function(result){
		      $('#getaccount_data').html(result);
		});
		
	}
	
</script>

</body>
</html>
