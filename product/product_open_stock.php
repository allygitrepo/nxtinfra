<?php

include("../header.php");
$modulePath = "product/product_open_stock.php?sub=list";

	$help_code = $modulePath;
	include "../help_code.php";

$pgname = $help_code;
include("../viewonly.php");

?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Product wise  Stock
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Product wise  Stock</li>
      </ol>
    </section>

<div class="col-md-12">
	
		<div class="box box-info">
            <div class="box-header with-border">
             <!-- <h3 class="box-title">Product wise Opening Stock List</h3>-->
			  <?php
				
				$targetpage = "product_open_stock.php?sub=list"; 
				$limit = 25; 
				$start = 0;	
				
			  if ($_POST['comp_id'] || $_POST['product_name'] || $_POST['product_group'] || $_POST['category'] || S_POST['product_text'] ){
					$_SESSION['comp_id'] 		= $_POST['comp_id'];
					$_SESSION['product_name'] 	= $_POST['product_name'];
					$_SESSION['product_group'] 	= $_POST['product_group'];
					$_SESSION['category'] 		= $_POST['category'];
					$_SESSION['product_text'] 		= $_POST['product_text'];
					
				}
				
				if ( $_SESSION['comp_id']  || $_SESSION['product_name']  || $_SESSION['product_group'] || $_SESSION['category'] || $_SESSION['product_text'] ){
					$comp_id 			= $_SESSION['comp_id'];
					$product_name 		= $_SESSION['product_name'];
					$product_group 		= $_SESSION['product_group'];
					$category 			= $_SESSION['category'];
					$product_text 			= $_SESSION['product_text'];
					
					$_SESSION['reset']='';
				}
				
				if (!empty($_GET['reset']) || !empty($_SESSION['reset'])){
					$_SESSION['comp_id'] 		= '';
					$_SESSION['product_name'] 	= '';
					$_SESSION['product_group'] 	= '';
					$_SESSION['category'] 		= '';
					$_SESSION['product_text'] 		= '';
					
					$comp_id 		= $_SESSION['comp_id'];
					$product_name 	= $_SESSION['product_name'];
					$product_group 	= $_SESSION['product_group'];
					$category 		= $_SESSION['category'];
					$product_text 		= $_SESSION['product_text'];
					
				}
				
			if(empty($category)){
				$category = 'M';
			}		
			
			//echo $product_group . ' >><< ' . $product_name;
			?>
				<form class="form-horizontal" action="product_open_stock.php?sub=list" method="post">
                      
						<div class="form-group">
								
								<div class="col-md-2">
									<label class="control-label">Company</label>
									<select class="form-control select2" name="comp_id" id="comp_id" onchange="getproduct_c(this.value);"  >
										<option value=""> Select </option>
										<option value="" <?php echo ($comp_id == '')?'selected="selected"':'';?> > All </option>
											<?php $sql = "select * from company where 1 and comp_id in ( $comid ) order by comp_name ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['comp_id'];?>" <?php echo ($comp_id == $r2['comp_id'])?'selected="selected"':'';?>  ><?php echo $r2['comp_code'];?></option>
											<?php } ?>
									</select>	
								</div>
								
								
									<div class="col-md-2">
									<label class=" control-label">Product Group</label>
										<span id="getproduct_c">
										<select class="form-control"  onchange="getproductv(this.value);" name="product_group" >
											<option value=""> Select </option>
										
											<option value="" <?php echo ($product_group == '')?'selected="selected"':'';?> > All </option>
									
											<?php  $sql = "select * from sma_product_group where id in ( select product_group from sma_product where id in ( SELECT distinct(product_name) from sma_product_open_stock where project = '$comp_id' ) order by name )  order by product_group ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['id'];?>"  <?php echo ($product_group == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['product_group'];?></option>
										<?php } ?>
										</select>
										</span>
									</div>
								
									<div class="col-md-3">
									<label class="control-label">Product Name</label>
									<span id="getproductv">
										<select class="form-control" name="product_name" id="product_name"  >
											<option value=""> Select </option>
										<?php	
											$sql = "select * from sma_product where id in ( SELECT distinct(product_name) from sma_product_open_stock where project = '$comp_id'  ) and `product_group` = '$product_group' order by trim(name) ";
										
										?>		
												
											<option value="" <?php echo ($product_name == '')?'selected="selected"':'';?>  > All </option>
										<?php 
													$q2 	= mysqli_query($con, $sql);
													while($r2 = mysqli_fetch_array($q2)){ ?>
											<option value="<?php echo $r2['id'];?>"  <?php echo ($product_name == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['name'];?></option>
													<?php } ?>
												
										</select>
									</span>	
									</div>
								
									<div class="col-md-2">
										<label class="control-label">Category</label>
										<select class="form-control select2" name="category" id="category" >
											<option value=""> Select </option>
											<option value="M" <?= ($category == 'M')?'selected="selected"':'';?>> Material </option>
											<option value="S" <?= ($category == 'S')?'selected="selected"':'';?>> Service </option>		
											<option value="B" <?= ($category == 'B')?'selected="selected"':'';?>> Both </option>	
										</select>	
									</div>
								
								<div class="col-xs-2">
									
									<input class="btn btn-success" type="submit" value="Search" name="Save">&nbsp;&nbsp;&nbsp;
									<a href="product_open_stock.php?sub=list&reset=1" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
								</div>
								
						</div>	
						
						<div class="form-group">
							<div class="col-xs-2 ">
								<label class="control-label pull-right" >Search By Product Text </label>
							</div>	
							<div class="col-xs-2">
								<input type="text" class="form-control" id="product_text" name="product_text"  value="<?php echo $product_text;?>" > 
							</div>
							
							
				</form>
				
				<div class="pull-right">
				
					<span class="sepV_c marginRight">
						<a href="product_opening_stock_export.php?sub=pdf" target="_blank" class="btn btn-primary">Export</a>&nbsp;&nbsp;&nbsp;&nbsp;
						
					<?php //if ( $addonly=='Y'){ ?>
						<a href="product_open_stock.php?sub=add" name="btnAdd" class="btn btn-primary"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Add </a>
						&nbsp;&nbsp;&nbsp;&nbsp;
					<?php //} ?>	
					</span>
				</div>
				
				</div>
			
		</div>	
    <div class="box">
	
    <table id="prtable123" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>Product Name</th>
			<th>Product Group</th>
			<th>UOM</th>
			<th>Company Name</th>
			<th  style="text-align:right;">Opening Stock</th>
			<th  style="text-align:right;">Receipts</th>
			<th  style="text-align:right;">Issue</th>
			<th style="text-align:right;">Closing Stock</th>
			<th style="text-align:right;">Average Rate</th>
			<th style="text-align:right;">Total Amount</th>
			<th style="text-align:right;">Action</th>
			
		</tr>
	</thead>
<tbody>
<?php
	$modulePath1 = "product/";
	
	$sql="SELECT * from sma_product_open_stock where project in ($comid)";
	
	if ($viewonly =='Y' ){
		$sql="SELECT * from sma_product_open_stock where project in ($comid)";
	}	
	
	if(!empty($comp_id)){
		$sql .= " and project = '$comp_id' ";
	}
	
	if(!empty($product_name)){
		$sql .= " and product_name = '$product_name' ";
	}
	
	if(!empty($product_group)){
		//select product_group from sma_product where
		$sql .= " and product_name in (select id from sma_product where product_group = '$product_group')";
	}
	if(!empty($product_text)){
		
		$sql .= " and product_name in (select id from sma_product where name like '%$product_text%')";
	}
	
	
	if($category=='M' || $category =='S'){
		$sql .= " and product_name in (select id from sma_product where category = '$category')";
	}
	
	$_SESSION['sqlpros']= $sql;
	
//echo $sql."<BR>";

	$result = mysqli_query($con, $sql);
	$total_pages = mysqli_affected_rows($con);
	echo mysqli_error($con);
	
	if(!empty($comp_id)){
		product_open_stock_func($sql);
	}
						//$total_pages = $total_pages['num'];
			//echo $total_pages. ' <<<>>';		
					
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
						
					$sql .= ' order by id desc ';
					$sql .= " LIMIT $start, $limit ";

		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
	
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
//echo $sql ."<BR>";
	while($row = mysqli_fetch_array($result)){
		
		$rid = $row['id'];
		
	$company_id = $row['project'];
	$sql = "SELECT * from company where comp_id = '$company_id' ";
	$res = mysqli_query($con, $sql);
	//echo mysqli_error($con);
	$r2 = mysqli_fetch_array($res);
	$comp_name 	= $r2['comp_name'];
	$project 	= $r2['comp_code'];

	$product_id = $row['product_name'];
	
	$sql 	= "select * from sma_product where id = '$product_id' ";
	$q2 	= mysqli_query($con, $sql);
	$r2 	= mysqli_fetch_array($q2);
	$product_name_id 	= $r2['id'];
	$product_name 	= $r2['name'];
	$unit 			= $r2['uom'];
	$category		= $r2['category'];
	$product_group	= $r2['product_group'];
											
	$sql 	= "select * from sma_product_group where id = '$product_group' ";
	$q2 	= mysqli_query($con, $sql);
	$r2 	= mysqli_fetch_array($q2);
	$product_group_name 	= $r2['product_group'];
	
	$opening_stock 	= $row['opening_stock'];
	$receipts 		= $row['receipts'];
	$issue			= $row['issue'];
	
	$sql = " SELECT sum(b.qty) as qty FROM `sma_supplier_invoice` a , sma_supplier_invoice_details  b WHERE 1 
				and del !='Y' and approval_status != 'Rejected' and a.id = b.si_hdr_id 
				AND b.material_id = '$product_id'
				AND a.company_id   = '$company_id' ";
				
	$q2 	= mysqli_query($con, $sql);
	$r2 	= mysqli_fetch_array($q2);
	$receipts_qty 	= $r2['qty'];
//echo $sql .' ' .$receipts_qty." ##1<BR>";	
if($rid==3494){
	echo $sql .' ' .$receipts_qty." ##1<BR>";
}	
//	if($receipts_qty != $receipts && $receipts_qty >0 && !empty($product_name) ){
		$sql = " UPDATE sma_product_open_stock SET receipts = '$receipts_qty' where id = '$rid' ";
//echo $sql ."<BR>";		
		mysqli_query($con, $sql);
		$receipts 		= $receipts_qty;
//	}	
	
	$sql = " SELECT sum(b.receipt_qty) as receipt_qty FROM `sma_goods_receipt_note` a , sma_goods_receipt_note_items  b WHERE 1 
				and del !='Y' and approval_status != 'Rejected' and a.id = b.grn_hdr_id 
				AND b.product_id = '$product_id'
				AND a.company_id   = '$company_id' ";	
if($rid==3494){
	echo $sql ." ##2<BR>";
}	
	$q2 	= mysqli_query($con, $sql);
	$r2 	= mysqli_fetch_array($q2);
	$receipt_qty 	= $r2['receipt_qty'];
	//if( $receipts_qty != $receipts && $receipts>0 ){
		$sql = " UPDATE sma_product_open_stock SET receipts = receipts + '$receipt_qty' where id = '$rid' ";
		mysqli_query($con, $sql);
		$receipts 		= $receipts + $receipt_qty;
	//}
	
	$sql = " SELECT sum(b.issue_qty) as issue_qty FROM `sma_goods_issue_note` a , sma_goods_issue_note_items  b WHERE 1 
				and del !='Y' and approval_status != 'Rejected' and a.id = b.gin_hdr_id 
				AND b.product_id = '$product_id'
				AND a.company_id   = '$company_id' ";	
	
	$q2 	= mysqli_query($con, $sql);
	$r2 	= mysqli_fetch_array($q2);
	$issue_qty 	= $r2['issue_qty'];
if($rid==3494){
	echo $sql .' ' . $issue_qty. " ##2<BR>";
}	
	if( $issue_qty != $issue && $issue>0 ){
		$sql = " UPDATE sma_product_open_stock SET issue = '$issue_qty' where id = '$rid' ";
		mysqli_query($con, $sql);
		$issue 		= $receipts_qty;
	}
	
	$closing_stock = ($opening_stock + $receipts ) - ( $issue ); 
	$total_stock   = ($opening_stock + $receipts ) ; 


	$average_rate	='';
	$total_value	='';
	if($category=='M'){
		$sql = "select material_id, round(( total_value / total_qty ),2) as average_rate, total_qty from (
			SELECT material_id, round(sum((qty * rate) + (qty * rate) * gst / 100 ),2) total_value , sum(qty) total_qty , qty, gst,rate FROM `sma_supplier_invoice_details` a, sma_supplier_invoice b where 1 and b.id = a.si_hdr_id and b.del !='Y' and b.approval_status != 'Rejected' and material_id = '$product_id' ) DS ";
		$res 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($res);
		$average_rate 	= $r2['average_rate'];
		$total_value	= round($total_stock * $average_rate,2);
		
	}
	else if($category=='S'){
		$sql = "select material_id, round(( total_value / total_qty ),2) as average_rate, total_qty from (
			SELECT material_id, round(sum((qty * rate) + (qty * rate) * gst / 100 ),2) total_value , (qty) as total_qty , qty, gst,rate FROM `sma_supplier_invoice_details` a, sma_supplier_invoice b where 1 and b.id = a.si_hdr_id and b.del !='Y' and b.approval_status != 'Rejected' and material_id = '$product_id' ) DS ";
		$res 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($res);
		$average_rate 	= $r2['average_rate'];
		$total_value	= round($total_stock * $average_rate,2);
		
	}
	//echo $average_rate;
if($rid==3494){
	echo $sql ." ##3<BR>";
}	
	if($total_value<=0 ){
		$sql = " SELECT sum(total_value) as total_value , sum(receipt_qty) as total_stock, sum(total_value)/ sum(receipt_qty) as rate 
					FROM `sma_goods_receipt_note` a, `sma_goods_receipt_note_items` b 
						WHERE b.grn_hdr_id = a.id AND total_value >0 AND company_id = '$company_id' AND b.product_id = '$product_id' ";
		$res 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($res);	
		
		$total_value	= round($r2['total_value'],2);
		$total_stock	= round($r2['total_stock'],2);
		if($total_value!=0){
			$average_rate 	= round($total_value / $total_stock,2);
		}
	}
//echo $average_rate;
	if($average_rate=='NAN'){
		$average_rate = 'Del';
	}	
if($product_id=='3494'){
	echo $sql ." ##4<BR>";
}	

	$baseurl1 = $baseurl.$modulePath1.'product_open_stock.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "product_open_stock.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="15%"><?php echo $product_name;?></td>
		<td width="12%"><?php echo $product_group_name;?></td>
		
		<td width="08%"><?php echo $unit;?></td>
		<td width="06%"><?php echo $project;?></td>
		
		<td width="08%" style="text-align:right;"><?php echo ($opening_stock);?></td>
		
		<td width="08%" style="text-align:right;"><?php echo bcadd($receipts,0,4);?></td>

		<td width="08%" style="text-align:right;"><?php echo bcadd($issue,0,4);?></td>
		
		<td width="08%" style="text-align:right;"><?php echo bcadd($closing_stock,0,4);?></td>
		<td width="08%" style="text-align:right;"><?php echo bcadd($average_rate,0,2);?></td>
		<td width="10%" style="text-align:right;"><?php echo bcadd($total_value,0,2);?></td>
		
		<td width="5%" style="text-align:right;">
		<a href="product_open_stock.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
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

    <?php 
	
	}
	
	function product_open_stock_func($sql){
			include "../dbcon.php";
	
			//$sql = "SELECT * from sma_product_open_stock where 1 and (opening_stock + receipts) - issue < 0 ";
			//$sql = "SELECT * from sma_product_open_stock where 1 and issue < 0 ";
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
			while($row = mysqli_fetch_array($result)){
				
				$rid = $row['id'];
				
				$company_id = $row['project'];
				$sql = "SELECT * from company where comp_id = '$company_id' ";
				$res = mysqli_query($con, $sql);
				//echo mysqli_error($con);
				$r2 = mysqli_fetch_array($res);
				$comp_name 	= $r2['comp_name'];
				$project 	= $r2['comp_code'];

				$product_id 	= $row['product_name'];
				
				$opening_stock 	= $row['opening_stock'];
				$receipts 		= $row['receipts'];
				$issue			= $row['issue'];
				
				$sql = " SELECT sum(b.qty) as qty FROM `sma_supplier_invoice` a , sma_supplier_invoice_details  b WHERE 1 
							and del !='Y' and approval_status != 'Rejected' and a.id = b.si_hdr_id 
							AND b.material_id = '$product_id'
							AND a.company_id   = '$company_id' ";
		//echo $sql ."<BR>";						
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$receipts_qty 	= $r2['qty'];

			//echo $receipts_qty ." ###1<BR>";
			
					$sql = " UPDATE sma_product_open_stock SET receipts = '$receipts_qty' where id = '$rid' ";
			//echo $sql ."<BR>";		
					mysqli_query($con, $sql);
					$receipts 		= $receipts_qty;
				
				$sql = " SELECT sum(b.receipt_qty) as receipt_qty FROM `sma_goods_receipt_note` a , sma_goods_receipt_note_items  b WHERE 1 
							and del !='Y' and approval_status != 'Rejected' and a.id = b.grn_hdr_id 
							AND b.product_id = '$product_id'
							AND a.company_id   = '$company_id' ";	
		//echo $sql ."<BR>";			
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$receipt_qty 	= $r2['receipt_qty'];
			//echo $receipt_qty ." ###2<BR>";	
				
					$sql = " UPDATE sma_product_open_stock SET receipts = receipts + '$receipt_qty' where id = '$rid' ";
					mysqli_query($con, $sql);
					$receipts 		= $receipts + $receipt_qty;
				
				$sql = " SELECT sum(b.issue_qty) as issue_qty FROM `sma_goods_issue_note` a , sma_goods_issue_note_items  b WHERE 1 
							and del !='Y' and approval_status != 'Rejected' and a.id = b.gin_hdr_id 
							AND b.product_id = '$product_id'
							AND a.company_id   = '$company_id' ";	
		//echo $sql ."<BR>";			
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$issue_qty 	= $r2['issue_qty'];
			//echo $issue_qty. " ###3<BR>";
				
					$sql = " UPDATE sma_product_open_stock SET issue = '$issue_qty' where id = '$rid' ";
					mysqli_query($con, $sql);
					$issue 		= $receipts_qty;
				
			}		
			
		}	
	?>


<?php  
	if($_GET['sub'] == 'delete'){ 
        $id = $_GET['id'];
		
		
		$sql="delete from sma_product_open_stock where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="product_open_stock.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){
?>

<?php
	if(isset($_POST['Save'])){

			$project			= $_POST['project'];
			$product_name		= $_POST['product_name'];
			$opening_stock		= $_POST['opening_stock'];
			$receipts			= $_POST['receipts'];
			$issue				= $_POST['issue'];
			$issued_order_no	= $_POST['issued_order_no'];
			$uom				= $_POST['uom'];
			$specification		= $_POST['specification'];
			
			$sql = " SELECT * FROM `sma_product_open_stock` WHERE product_name = '$product_name' AND project = '$project' ";
			mysqli_query($con, $sql);
			$row_affected = mysqli_affected_rows($con);
			if($row_affected>=1){
				
				echo "<script>alert('Error :  Duplicate Product...');</script>";
				/* $sql = "UPDATE sma_product_open_stock set opening_stock = opening_stock + '$opening_stock',
									specification 	= '$specification'
								where product_name = '$product_name' and project = '$project'  ";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con); */
			}
			else {
				$sql="insert into sma_product_open_stock ( project, product_name, opening_stock, created_date, issued_order_no, uom, specification ) 
				Values('$project', '$product_name', '$opening_stock', now(), '$issued_order_no', '$uom', '$specification' )";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; sleep(5);}
			}
			//echo "Budget successful added";
			echo '<script>window.location.href="product_open_stock.php?sub=list";</script>';
			
		}
	
?>
   <section class="content-header">
        <h1>
            Product wise  Stock
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Product wise  Stock</a></li>
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
            <form class="form-horizontal" action="product_open_stock.php?sub=add" method="post">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Product Group</label>
							<div class="col-md-4">
								<select class="form-control" required id="product_group" onchange="getproduct(this.value);" >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_product_group order by product_group ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" > <?php echo $r2['product_group'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<label class="col-lg-1 control-label">Specification</label>
							<div class="col-md-5">
								<input type="text" class="form-control" id="specification" name="specification" style="text-align:left;" placeholder="" value="<?php echo $row['specification'];?>" >
							</div>
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Product Name</label>
							<div class="col-md-4">
							<span id="getproduct">
								<select class="form-control" name="product_name" id="product_name" required >
									<option value=""> Select </option>
										
								</select>
							</span>	
							</div>
							
							<label class="col-lg-1 control-label">UOM</label>
							<div class="col-md-2">
								<input type="text" class="form-control" readonly id="uom" name="uom" style="text-align:left;" placeholder="" value="<?php echo $uom;?>" >
							</div>
							
						</div>
						
						<div class="form-group">
							
							<label for="project" class="control-label col-sm-2"> Company Name</label>
							<div class="col-sm-4">
									<select class="form-control select2" name="project" id="project" required onchange="product_dupplicate();" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company where 1 and comp_id in ( $comid ) order by comp_name "; //comp_id in ($comid)
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
							</div>
							<span style="color:red;font-weight:bold;" id ="product_dupplicate"></span>
						</div>
						
						
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Opening Stock</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="opening_stock" name="opening_stock" style="text-align:right;" placeholder="" value="<?php echo $row['opening_stock'];?>" onkeyup="getbalbugdget123()">
							</div>
							
							<label class="col-lg-2 control-label">Issued Order No.</label>
							<div class="col-md-6">
								<input type="text" class="form-control" id="issued_order_no" name="issued_order_no" style="text-align:left;" placeholder="" value="<?php echo $row['issued_order_no'];?>" >
							</div>
							
						</div>
						
						<div class="form-group">
						
							<label class="col-lg-2 control-label">Receipts</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="receipts" name="receipts" style="text-align:right;" placeholder="" value="<?php echo $row['receipts'];?>" readonly >
							</div>
						
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Issue</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="issue" name="issue" style="text-align:right;" <?php echo $readonly;?> value="<?php echo $row['issue'];?>" readonly  >
							</div>
						</div>
					<?php	
					
						$opening_stock 	= $row['opening_stock'];
						$receipts 		= $row['receipts'];
						$issue 			= $row['issue'];
						$closing_stock 	= ( $opening_stock + $receipts ) - $issue;
					?>
						<div class="form-group">
							<label class="col-lg-2 control-label">Closing Stock </label>
							<div class="col-md-2">
								<input type="text" class="form-control" style="text-align:right;" readonly value="<?php echo $closing_stock;?>" >
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
								<span id="hidesave">
								<input class="btn btn-primary" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								</span>
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

			$id					= $_POST['id']; 			
			$project			= $_POST['project'];
			$product_name		= $_POST['product_name'];
			$opening_stock		= $_POST['opening_stock'];
			$budget_status		= $_POST['budget_status'];
			$receipts 			= $_POST['receipts'];
			$issue 				= $_POST['issue'];
			$issued_order_no	= $_POST['issued_order_no'];
			$uom				= $_POST['uom'];
			$specification		= $_POST['specification'];
			
  			$sql = "UPDATE sma_product_open_stock set opening_stock = '$opening_stock',
								issued_order_no = '$issued_order_no',
								specification 	= '$specification'
							where id = '$id'";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			echo '<script>window.location.href="product_open_stock.php?sub=list";</script>';
		}
		
		$id = $_GET['id'];
		$sql="Select * from sma_product_open_stock where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	
		$locked	 = $row['locked'];
//echo $sql;		
		$readonly='';
		
		if($locked=='Y' || $viewonly=='Y'){
			$readonly = "READONLY";
		}
		
		if($user!='Admin'){
			$readonly = "READONLY";
		}	
		
?>
	
    <!-- Content Header (Page header) -->
    <div class="col-md-12">
    
   <section class="content-header">
        <h1>
            Product wise  Stock
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Product wise  Stock</a></li>
            
        </ol>
    </section>
	
        <!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box">
            <!-- form start -->
            <form class="form-horizontal" action="product_open_stock.php?sub=edit" method="post">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
					<?php 	
							$project = $row['project'];
							$company_id = $row['project'];
							$sql = "select * from company where 1 and comp_id in ( $project ) ";
							$q2 	= mysqli_query($con, $sql);
							$r2 	= mysqli_fetch_array($q2);
							$comp_name = $r2['comp_name'];
											
							$product_name 	= $row['product_name'];
							$product_id		= $row['product_name'];
							$sql = "select * from sma_product where id = '$product_name' ";
							$q2 	= mysqli_query($con, $sql);
							$r2  = mysqli_fetch_array($q2);
							$product_group = $r2['product_group'];
							$product_name	= $r2['name'];
							
							$uom = $row['uom'];	
							if(empty($uom)){
								
								$uom = $r2['uom'];
							}
							
							$hdrview = "<p>Company : $comp_name &nbsp; Product : $product_name </p>";
						?>	
				<ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_1" data-toggle="tab" id="first_tab" > Product </a></li>
                    <li><a href="#tab_2" data-toggle="tab" class="btn btn-info" id="second_tab" target="_blank" >Transaction</a></li>
				</ul>
					
					<div class="tab-content">
					    <div class="tab-pane active " id="tab_1">
							
										
						<div class="form-group">
							<label class="col-lg-2 control-label">Product Group</label>
							<div class="col-md-4">
								<select class="form-control" disabled required onchange="getproduct(this.value);" >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_product_group order by product_group ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($product_group == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['product_group'];?></option>
										<?php } ?>
								</select>
							</div>
							
							<label class="col-lg-1 control-label">Specification</label>
							<div class="col-md-5">
								<input type="text" class="form-control" id="specification" name="specification" <?= $readonly; ?> style="text-align:left;" placeholder="" value="<?php echo $row['specification'];?>" >
							</div>
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Product Name</label>
							<div class="col-md-4">
							<span id="getproduct">
								<select class="form-control" disabled name="product_name" id="product_name" required >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_product order by name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['product_name'] == $r2['id'])?'selected="selected"':'';?>> <?php echo $r2['name'];?></option>
										<?php } ?>
								</select>
							</span>
							</div>
							
							<label class="col-lg-1 control-label">UOM</label>
							<div class="col-md-2">
								<input type="text" class="form-control" readonly id="uom" name="uom" style="text-align:left;" placeholder="" value="<?php echo $uom;?>" >
							</div>
							
						</div>
						
						<div class="form-group">
							
							<label for="project" class="control-label col-sm-2"> Company Name</label>
							<div class="col-sm-4">
									<select class="form-control select2" disabled name="project" id="project" required >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company where 1 and comp_id in ( $comid ) order by comp_name "; //comp_id in ($comid)
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['project'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
							</div>
						
							
						</div>
						
						<?php $opening_stock = $row['opening_stock']; ?>
						<div class="form-group">
							<label class="col-lg-2 control-label">Opening Stock</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="opening_stock" name="opening_stock" style="text-align:right;" placeholder="" value="<?= $row['opening_stock'];?>" <?= $readonly; ?> >
							</div>
							
							<label class="col-lg-2 control-label">Issued Order No.</label>
							<div class="col-md-6">
								<input type="text" class="form-control" id="issued_order_no" name="issued_order_no" <?= $readonly; ?> style="text-align:left;" placeholder="" value="<?php echo $row['issued_order_no'];?>" >
							</div>
							
						</div>
						
						<div class="form-group">
						
							<label class="col-lg-2 control-label">Receipts</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="receipts" name="receipts" style="text-align:right;" placeholder="" value="<?php echo $row['receipts'];?>" readonly >
							</div>
					
						</div>

						<div class="form-group">
							<label class="col-lg-2 control-label">Issue</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="issue" name="issue" style="text-align:right;" <?php echo $readonly;?> value="<?php echo $row['issue'];?>" readonly  >
							</div>
					
							
					</div>
					
					<?php	
					
						$opening_stock 	= $row['opening_stock'];
						$receipts 		= $row['receipts'];
						$issue 			= $row['issue'];
				 		$closing_stock 	= ( $opening_stock + $receipts ) - $issue;
					?>
						<div class="form-group">
							<label class="col-lg-2 control-label">Closing Stock </label>
							<div class="col-md-2">
								<input type="text" class="form-control" style="text-align:right;" readonly value="<?php echo bcadd($closing_stock,0,4);?>" >
							</div>
						</div>
						
						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; 
								//Mrunmayee started
								/* $sql =" select count(*) as cnt from sma_supplier_invoice_details a, sma_po_items b where b.product_id='$id' and a.material_id = b.product_id ";
								$query1 = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r2 = mysqli_fetch_array($query1);
								$cnt = $r2['cnt']; */
								$product_id = $row['product_name'];
								$company_id = $row['project'];
								$sql = " SELECT sum(b.qty) as qty FROM `sma_supplier_invoice` a , sma_supplier_invoice_details  b WHERE 1 
								and del !='Y' and approval_status != 'Rejected' and a.id = b.si_hdr_id 
								AND b.material_id = '$product_id'
								AND a.company_id   = '$company_id' ";
									$q2 	= mysqli_query($con, $sql);
									$r2 	= mysqli_fetch_array($q2);
									$cnt 	= $r2['qty'];
					
								?>
							<?php //echo $closing_stock . ' <<>> ' . $cnt; //$closing_stock==0 &&
							if($cnt>0){
							//	echo $sql. "<BR>";	
							}	
								if ( $cnt==0 && $viewonly!='Y' && $user=='Admin' ){ 
							
								?>	
								<a href="<?php echo $baseurl."product/product_open_stock.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a>
							<?php } //Mrunmayee ended?>	
							</div>
							<?php $baseurl1 = $baseurl.$modulePath;?>
							<div class="col-sm-6 text-right">
								<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Cancel</a>
								<span>&nbsp;&nbsp;</span>
							<?php if ($role!='Checker' && $viewonly!='Y'){ ?>
								<input class="btn btn-primary" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
							<?php } ?>	
							</div>
						</div>
						
                    </div>
					
					<div class="tab-pane  " id="tab_2">
						<div class="col-md-12">
	
								<div class="box box-info">
									
						<?php echo "<b>".$hdrview . "</b>"; ?>
<?php						
//GRN without PO Start
	
	$sql = " TRUNCATE table product_stock_ledger";
	mysqli_query($con, $sql);
	
	$sql = " SELECT a.company_id, a.dated, b.* FROM `sma_goods_receipt_note` a , sma_goods_receipt_note_items  b WHERE 1 
				and del !='Y' and approval_status != 'Rejected' and a.id = b.grn_hdr_id
				AND b.product_id = '$product_id' 
				and a.company_id   = '$company_id' ". $sqlfy;
	
//echo $sql;																					  
	$_SESSION['sql'] = $sql;
	
	$res		 = mysqli_query($con, $sql);
	echo mysqli_error($con);					 
	$total_pages = mysqli_affected_rows($con);
	
//echo $sql."<BR>";

	$budget_id_prev		= '';
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){
	
		$company_id	 		= $row['company_id'];
		$material_id	 	= $row['product_id'];
		$created_date	 	= $row['dated'];
		$doc_no				= $row['grn_hdr_id'];
		$qty		 		= $row['receipt_qty'];
		$doctype 			= 'GRN';
		
		$sql = " INSERT INTO product_stock_ledger (company_id, product_id, dated, doc_no, doc_type, receipts) VALUES ('$company_id', '$material_id', '$created_date', '$doc_no', '$doctype', '$qty' ) ";
//echo $sql. "<BR>";
		mysqli_query($con, $sql);
		echo mysqli_error($con);
		
	}
	
//GRN without PO End	


//GRN Start
	
	//$sqlfy = " AND created_date >= '$finance_from_date' AND created_date <= '$finance_to_date' ";
	
	$sql = " SELECT a.company_id, a.created_date, b.* FROM `sma_supplier_invoice` a , sma_supplier_invoice_details  b WHERE 1 
				and del !='Y' and approval_status != 'Rejected' and a.id = b.si_hdr_id AND b.material_id = '$product_id' 
				and a.company_id   = '$company_id' " . $sqlfy ;
//echo $sql. "<BR>";
	$res		 = mysqli_query($con, $sql);
	echo mysqli_error($con);					 
	$total_pages = mysqli_affected_rows($con);

	$budget_id_prev		= '';
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){
	
		$company_id	 		= $row['company_id'];
		$material_id	 	= $row['material_id'];
		$created_date	 	= $row['created_date'];
		$doc_no				= $row['si_hdr_id'];
		$qty		 		= $row['qty'];
		$doctype 			= 'GRNS';
		
		$sql = " INSERT INTO product_stock_ledger (company_id, product_id, dated, doc_no, doc_type, receipts) VALUES ('$company_id', '$material_id', '$created_date', '$doc_no' , '$doctype', '$qty' ) ";
//echo $sql. "<BR>";		
		mysqli_query($con, $sql);
		echo mysqli_error($con);
		
	}
	
//GRN End	


//GIN Start
	
	//$sqlfy = " AND dated >= '$finance_from_date' AND dated <= '$finance_to_date' ";
	
	$sql = " SELECT a.company_id, a.dated, b.* FROM `sma_goods_issue_note` a , sma_goods_issue_note_items  b WHERE 1 
				and del !='Y' and approval_status != 'Rejected' and a.id = b.gin_hdr_id
				AND b.product_id = '$product_id' 
				and a.company_id   = '$company_id' ". $sqlfy;
	
//echo $sql;																					  
	$_SESSION['sql'] = $sql;
	
	$res		 = mysqli_query($con, $sql);
	echo mysqli_error($con);					 
	$total_pages = mysqli_affected_rows($con);
	
//echo $sql."<BR>";

	$budget_id_prev		= '';
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){
	
		$company_id	 		= $row['company_id'];
		$material_id	 	= $row['product_id'];
		$created_date	 	= $row['dated'];
		$doc_no				= $row['gin_hdr_id'];
		$qty		 		= $row['issue_qty'];
		$doctype 			= 'GIN';
		
		$sql = " INSERT INTO product_stock_ledger (company_id, product_id, dated, doc_no, doc_type, issued) VALUES ('$company_id', '$material_id', '$created_date', '$doc_no', '$doctype', '$qty' ) ";
//echo $sql. "<BR>";
		mysqli_query($con, $sql);
		echo mysqli_error($con);
		
	}
							$closing_stock 		=  $opening_stock;
//GIN END
?>
					
							<div class="box">
							<table id="prtable123" class="table table-bordered table-striped">

							<thead>
								<tr>
								
									<th>Company </th>
									<th>Product Name</th>
									<th>UOM</th>
									
									<th style="text-align:left;">Dated</th>
									<th>Doc Type</th>
									<th>Doc No.</th>
									<th  style="text-align:right;">Opening Stock</th>
									<th  style="text-align:right;">Receipts</th>
									<th  style="text-align:right;">Issued</th>
									<th  style="text-align:right;">Closing Stock</th>

								</tr>
							</thead>
						<tbody>
								<tr>
									<td width="10%" style="text-align:right;"></td>
									<td width="10%" style="text-align:right;"></td>
									<td width="10%" style="text-align:right;"></td>
									<td width="10%" style="text-align:left;"><?php echo $dated;?></td>
									<td width="10%" style="text-align:left;">Open Stock</td>
									<td width="10%" style="text-align:left;"></td>
									<td width="10%" style="text-align:right;"><?php echo $opening_stock;?></td>
									<td width="10%" style="text-align:right;"></td>
									<td width="10%" style="text-align:right;"></td>
									<td width="10%" style="text-align:right;"></td>
								</tr>
								
						<?php 

	//$sqlfy = " AND dated >= '$finance_from_date' AND dated <= '$finance_to_date' ";

							$sql = "SELECT * from product_stock_ledger where 1  order by product_id, dated ";
//echo $sql. "<BR>";							
							$result = mysqli_query($con, $sql);
							echo mysqli_error($con);
							while($row = mysqli_fetch_array($result)){
							
								$company_id	 		= $row['company_id'];
								$product_id	 		= $row['product_id'];
								$dated			 	= date('d-m-Y', strtotime($row['dated']));
								$doc_no				= $row['doc_no'];
								$doc_type			= $row['doc_type'];
								//$opening_stock		= $row['opening_stock'];
								$issued		 		= $row['issued'];	
								$receipts		 	= $row['receipts'];
								
								if($issued==0 && $receipts==0){
									continue;
								}
								
								if($doc_type=='GIN'){
									$closing_stock      = round($closing_stock - $issued,2);
									$total_issue 		= round($total_issue + $issued,2);
									//$opening_stock ='';
									$receipts ='';
								}
								else if($doc_type=='GRN' || $doc_type=='GRNS' ){
									$closing_stock      = round($closing_stock + $receipts,2);
									$total_receipts	 	= round($total_receipts + $receipts,2);
									//$opening_stock ='';
									$issued ='';
								}
								
								$sql = "select * from sma_product where id = '$product_id'  ";
								$q2 	= mysqli_query($con, $sql);
								$r2 = mysqli_fetch_array($q2);
								$product_name = $r2['name'];
								$uom = $r2['uom'];
								
								$sql = "select * from company where comp_id = '$company_id' ";
								$q2 	= mysqli_query($con, $sql);
								$r2 = mysqli_fetch_array($q2);
								$comp_code = $r2['comp_code'];
								
								$baseurla = "";
								if($doc_type=='GRN'){
									$baseurla = $baseurl . "goods_issue_note/goods_receipt_note.php?sub=edit&id=$doc_no";
								}
								else if($doc_type=='GRNS'){
									$baseurla = $baseurl . "supp_invoice/editgrn.php?sub=edit&id=$doc_no";
								}
								
								else {
									$baseurla = $baseurl . "goods_issue_note/goods_issue_note.php?sub=edit&id=$doc_no";
									
								}
								$baseurl1 = $baseurla;
								
						?>
								
								<a href="<?php echo $baseurl . $baseurla;?>" target="_blank" title="Edit">
								<tr style="cursor:pointer; " target="_blank" onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>' ;">
									<td width="10%" style="text-align:left;"><?php echo $comp_code ;?></td>
									<td width="20%" style="text-align:left;"><?php echo $product_name;?></td>
									<td width="20%" style="text-align:left;"><?php echo $uom;?></td>
									<td width="10%" style="text-align:left;"><?php echo $dated;?></td>
									<td width="10%" style="text-align:left;"><?php echo $doc_type;?></td>
									<td width="10%" style="text-align:left;"><?php echo $doc_no;?></td>
									<td width="10%" style="text-align:right;"><?php //echo $opening_stock;?></td>
									<td width="10%" style="text-align:right;"><?php echo $receipts;?></td>
									<td width="10%" style="text-align:right;"><?php echo $issued;?></td>
									<td width="10%" style="text-align:right;"><?php echo $closing_stock;?></td>
								</tr>
								</a>	
						<?php	
							}

						?>

							<tr>
								<td width="10%" style="text-align:left;">&nbsp;</td>
								<td width="10%" style="text-align:left;">&nbsp;</td>
								<td width="10%" style="text-align:left;">&nbsp;</td>
								<td width="10%" style="text-align:left;">&nbsp;</td>
								<td width="20%" style="text-align:left;">Total</td>
								<td width="10%" style="text-align:left;">&nbsp;</td>
								<td width="10%" style="text-align:right;"><?= $opening_stock;?></td>
								<td width="10%" style="text-align:right;"><?= $total_receipts;?></td>
								<td width="10%" style="text-align:right;"><?= $total_issue;?></td>
								<td width="10%" style="text-align:right;"><b><?php echo $closing_stock;?></b></td>
							</tr>
							
							<tr>
								<td width="10%" style="text-align:left;">&nbsp;</td>
								<td width="10%" style="text-align:left;">&nbsp;</td>
								<td width="10%" style="text-align:left;">&nbsp;</td>
								<td width="10%" style="text-align:left;">&nbsp;</td>
								<td width="10%" style="text-align:left;">&nbsp;</td>
								<td width="10%" style="text-align:left;">&nbsp;</td>
								<td width="10%" style="text-align:left;">&nbsp;</td>
								<td width="10%" style="text-align:left;">&nbsp;</td>
								<td width="10%" style="text-align:left;">&nbsp;</td>
								<td width="10%" style="text-align:left;">&nbsp;</td>
							</tr>
						</tbody> 
						</table>

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

	function getproduct(id){
		
        var sub    = 'sub6';
		//var company_id = document.getElementById("companY").value;
//alert(sub + ' ' + company_id);
		
		var strURL = "account_func.php";
		$.post(strURL,{id:id,sub6:sub},function(result){
		      $('#getproduct').html(result);
		});

	}
	
	function getproduct_c(id){

		var sub    		= 'sub7a';
//alert(sub);		
		var company_id  = document.getElementById("comp_id").value;
		
//alert(sub + ' ' + id + ' <<>> ' + company_id );
		var strURL 		= "account_func.php";
		$.post(strURL,{id:id,company_id:company_id,sub7a:sub},function(result){
		      $('#getproduct_c').html(result);
		});
		
	}
	
	function getproductv(id){
		
        var sub    = 'sub7';
		var company_id  = document.getElementById("comp_id").value;
//alert(sub + ' ' + company_id);
		
		var strURL = "account_func.php";
		$.post(strURL,{id:id,company_id:company_id,sub7:sub},function(result){
		      $('#getproductv').html(result);
		});

	}
	
	function product_dupplicate(){
		
		var sub    = 'sub8';
		
		var company_id = document.getElementById("project").value;
		var product_name = document.getElementById("product_name").value;
		$('#hidesave').show();
		var strURL = "account_func.php";
		$.post(strURL,{company_id:company_id,product_name:product_name,sub8:sub},function(result){
		      $('#product_dupplicate').html(result);
			  var error = result.trim();
			  if(error!=''){
				alert(error);
				$('#hidesave').hide();
			  }
		});
		
	}	
	
</script>

</body>
</html>
