<?php
include("../header.php");
$modulePath = "product/manage_audit_job_list.php?sub=list";

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
        Manage Audit Job
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Manage Audit Job</li>
      </ol>
    </section>

<div class="col-md-12">
	
		<div class="box box-info">
            <div class="box-header with-border">
             <h3 class="box-title">Manage Audit Job List</h3>
			  
				<div class="pull-right">
				
					<span class="sepV_c marginRight">
					<!--<a href="product_opening_stock_export.php?sub=pdf" target="_blank" class="btn btn-primary">Export</a>&nbsp;&nbsp;&nbsp;&nbsp;-->
						
					</span>
				</div>
				
				<span id="predit"></span>
				
			</div>
		</div>	
    <div class="box">
    <table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>#</th>
			<th>Audit Job Name</th>
			<th>Company Name</th>
			<th>Date</th>
			<th>Created By</th>
			<th style="text-align:left;">Status</th>
			
		</tr>
	</thead>
<tbody>
<?php
	$modulePath1 = "goods_issue_note/";
	
	$sql="SELECT * from audit_job_header where 1 and company_id in ($comid)";
	
	if(!empty($comp_id)){
		$sql .= " and company_id = '$comp_id' ";
	}
	
	$sql .= "order by id desc ";
	
//echo $sql."<BR>";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
	
		$project = $row['company_id'];
		$sql = "SELECT * from company where comp_id = '$project' ";
		$res = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($res);
		$comp_name 	= $r2['comp_name'];
		$project 	= $r2['comp_code'];

		$audit_job_name = $row['audit_job_name'];

		$created_by = $row['created_by'];
		$sql = "SELECT * from sma_user where id = '$created_by' ";
		$res = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($res);
		$created_name 	= $r2['username'];
		
		$status = '';
		$audit_job_hdr_id = $row['id'];
		$sql="SELECT * from audit_job_details where 1 and physical_stock = 0 and audit_job_hdr_id = '$audit_job_hdr_id' ";
		$r3 = mysqli_query($con, $sql);
		$rowaffect = mysqli_affected_rows($con);
		if($rowaffect==0){
			$status = 'Audit Completed';
		}	
	
	
	$baseurl1 = $baseurl.$modulePath1.'manage_audit_job_list.php?sub=edit&audit_job_hdr_id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "manage_audit_job_list.php?sub=edit&audit_job_hdr_id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="1%"><input type="hidden" value="<?= $ii++;?>" > </td>
		<td width="30%"><?php echo $audit_job_name;?></td>
		<td width="10%"><?php echo $project;?></td>
		<td width="10%"><?php echo date('d-m-Y', strtotime($row['created_date']));?></td>
		<td width="10%" ><?php echo $created_name;?></td>
		<td width="10%" style="text-align:left;">
			<?php echo $status;?>
		<!--<a href="manage_audit_job_list.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>-->
		</td>
    </tr>
	</a>
	
	<?php }?>
</tbody> 
</table>
	</div>
    </div>
</div>	

<?php } ?>



<?php if($_GET['sub'] == 'edit'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Manage Audit Job
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Manage Audit Job</li>
      </ol>
    </section>

<div class="col-md-12">
	
		<div class="box box-info">
            <div class="box-header with-border">
             <h3 class="box-title">Manage Audit Job List</h3>
			  
				<div class="pull-right">
				
					<span class="sepV_c marginRight">
						<a href="manage_audit_job_list.php?sub=list" class="btn btn-primary">Back</a>&nbsp;&nbsp;
					</span>
				</div>
				
				<span id="predit"></span>
				
			</div>
		</div>	
    <div class="box">
    <table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr><td>#</td>
			<th>Product Name</th>
			<th>UOM</th>
			<th style="text-align:right;">Closing</th>
			<th style="text-align:right;">Verified Stock</th>
			<th style="text-align:left;">Notes</th>
			<th style="text-align:right;">Audited</th>
			
		</tr>
	</thead>
<tbody>
<?php
	$modulePath1 = "product/";
	
	$audit_job_hdr_id = $_GET['audit_job_hdr_id'];
	
	$sql="SELECT * from audit_job_details where 1 and audit_job_hdr_id = '$audit_job_hdr_id' ";
	
	$sql .= " order by id ";
//echo $sql."<BR>";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
	
		$product_id 	= $row['product_id'];
		$sql = "SELECT * from sma_product where id = '$product_id' ";
		$res = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($res);
		$product_name 	= $r2['name'];
		$uom 			= $r2['uom'];

		$closing_stock  = $row['closing_stock'];
		$physical_stock = $row['physical_stock'];
		
		$remarks 		= $row['remarks'];
		
		$audit_status 	= $row['audit_status'];
		$checked = '';
		if($audit_status=='Y'){
			$checked = "CHECKED";
		}	
		$rid			= $row['id'];

		$baseurl1 = $baseurl.$modulePath1.'manage_audit_job_list.php?sub=edit&id='.$row["id"];
					
	?>
	<tr>
		<td width="1%"><input type="hidden" value="<?= $ii++;?>" > </td>
		<td width="30%"><?php echo $product_name;?></td>
		<td width="10%" ><?php echo $uom;?></td>
		<td width="10%" style="text-align:right;" ><?php echo $closing_stock;?></td>
		<td width="10%" style="color:red;text-align:right;" border='1' bgcolor="lightgrey" contenteditable="true" 
						onBlur="saveToDatabase_ln(this,'physical_stock','<?= $audit_job_hdr_id; ?>', '<?= $rid; ?>');" ><?php echo $physical_stock;?></td>
		
		<td width="20%" style="color:red;text-align:left;" border='1' bgcolor="lightgrey" contenteditable="true" 
						onBlur="saveToDatabase_ln(this,'remarks','<?= $audit_job_hdr_id; ?>', '<?= $rid; ?>');" ><?php echo $remarks;?></td>
		
		<td width="5%" style="text-align:center;">
			<input type="checkbox" id="audit_status<?= $rid;?>" name="audit_status" <?= $checked;?> value="<?php echo $rid;?>" onchange="updateAudit_Status(this.value)" />
		<!--<a href="manage_audit_job_list.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>-->
		</td>
    </tr>

	<?php }?>
	
</tbody> 
</table>
	</div>
    </div>
</div>	

<?php } ?>


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


	function saveToDatabase_ln(editableObj,column,audit_job_hdr_id,line_no) {
		    
		var editableObj = editableObj.innerHTML;	
		//alert("UPDATE `enqdetail` set " + editableObj + qty);
		var sdivURL = "saveauditjobdtl.php";
			$.post(sdivURL,{column:column,editval:editableObj,audit_job_hdr_id:audit_job_hdr_id,line_no:line_no },function(result){
				//alert('Hello...');
				//$('#addbom_dtl123').html(result);
			});
			
	}
	
	function updateAudit_Status(id){
			
		var sub = 'sub15';
		var audit_status	 		=  $("#audit_status"+id).val();
	//	alert(id + ' ' +audit_status);
		
		audit_status = '';
		if (document.getElementById('audit_status'+id).checked) {
		    audit_status = document.getElementById('audit_status'+id).value;
			if(audit_status==id){
				audit_status = 'Y';	
			}	
		}
		
	//	alert(id + ' ' +audit_status);
	//	alert('Hello !');
		var strURL = "gin_func.php";
		$.post(strURL,{ audit_status:audit_status,
						id:id,
						sub15:sub},
						function(result){
		      //$('#predit').html(result);
		});
	}	

	
</script>

</body>
</html>
