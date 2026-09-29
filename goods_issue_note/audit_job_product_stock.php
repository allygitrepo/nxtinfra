<?php

include("../header.php");
$modulePath = "product/audit_job_product_stock.php?sub=list";

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
        Create Audit Job
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Create Audit Job</li>
      </ol>
    </section>

<div class="col-md-12">
	
		<div class="box box-info">
            <div class="box-header with-border">
             <!-- <h3 class="box-title">Audit Job List</h3>-->
			  <?php
			  if ($_POST['comp_id'] || $_POST['account_year'] || $_POST['product_name'] || $_POST['product_group'] || $_POST['category'] ){
					$_SESSION['comp_id'] 		= $_POST['comp_id'];
					$_SESSION['product_name'] 	= $_POST['product_name'];
					$_SESSION['product_group'] 	= $_POST['product_group'];
					$_SESSION['category'] 		= $_POST['category'];
				}
				
				if ( $_SESSION['comp_id'] ||  $_SESSION['account_year'] || $_SESSION['product_name'] || $_SESSION['category'] ){
					$comp_id 			= $_SESSION['comp_id'];
					$product_name 		= $_SESSION['product_name'];
					$product_group 		= $_SESSION['product_group'];
					$category 			= $_SESSION['category'];
					
					$_SESSION['reset']='';
				}
				
				if (!empty($_GET['reset']) || !empty($_SESSION['reset'])){
					$_SESSION['comp_id'] 		= '';
					$_SESSION['product_name'] 	= '';
					$_SESSION['product_group'] 	= '';
					$_SESSION['category'] 		= '';
					
					$comp_id 		= $_SESSION['comp_id'];
					$product_name 	= $_SESSION['product_name'];
					$product_group 	= $_SESSION['product_group'];
					$category 		= $_SESSION['category'];
					
				}
				
			if(empty($category)){
				$category = 'M';
			}		
			?>
				<form class="form-horizontal" action="audit_job_product_stock.php?sub=list" method="post">
                      
						<div class="form-group">
							<div class="col-sm-9">
								<label for="approver" class=" control-label">Enter Audit Name **</label>
                            	<textarea class="form-control" rows="1" required name="audit_name" id="Audit_NAME"></textarea>
							</div>
						</div>
							
						<div class="form-group">
								
								<div class="col-md-6">
									<label class="control-label">Company **</label>
									<select class="form-control select2" name="comp_id" id="COMPANY_ID" required >
										<option value=""> Select </option>
											<?php $sql = "select * from company where 1 and comp_id in ( $comid ) order by comp_name ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['comp_id'];?>" <?php echo ($comp_id == $r2['comp_id'])?'selected="selected"':'';?>  ><?php echo $r2['comp_code'];?></option>
											<?php } ?>
									</select>	
								</div>
								
						</div>
							
						<div class="form-group">
								<div class="col-md-6">
									<label class=" control-label">Product Group</label>
									<select class="form-control"  onchange="getproductv123(this.value);" name="product_group" id="product_group" >
										<option value=""> Select </option>
												<?php $sql = "select * from sma_product_group where id in ( select product_group from sma_product where id in ( SELECT distinct(product_name) from sma_product_open_stock ) order by name ) order by product_group ";
												$q2 	= mysqli_query($con, $sql);
												while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['id'];?>" <?php echo ($product_group == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['product_group'];?></option>
												<?php } ?>
									</select>
								</div>
						</div>
							
					<!--	<div class="form-group">		
									<div class="col-md-6">
									<label class="control-label">Product Name</label>
									<span id="getproductv">
										<select class="form-control" name="product_name" id="product_name"  >
											
										</select>
									</span>	
									</div>
								
						</div>
							
						<div class="form-group">
								<div class="col-md-6">
										<label class="control-label">Category</label>
										<select class="form-control select2" name="category" id="category" >
											<option value=""> Select </option>
											<option value="M" <?= ($category == 'M')?'selected="selected"':'';?>> Material </option>
											<option value="S" <?= ($category == 'S')?'selected="selected"':'';?>> Service </option>		
											<option value="B" <?= ($category == 'B')?'selected="selected"':'';?>> Both </option>	
										</select>	
								</div>
								
						</div>		-->
						
				</form>
				
				<div class="pull-left">
					
					<span id="predit" style="color:red;font-weight: bold;" ></span>
					
					<span class="sepV_c marginRight">
					<!--<a href="product_opening_stock_export.php?sub=pdf" target="_blank" class="btn btn-primary">Export</a>&nbsp;&nbsp;&nbsp;&nbsp;
					-->		
						<a href="#Audit_Name" class="btn btn-info" data-toggle="modal" data-mode="Approve" data-target="#Audit_Name">Create Audit Job </a>
									<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
						
					</span>
					
				</div>
				
				
				
			</div>
		</div>	
    <div class="box">
    
	</div>
    </div>
</div>	


<!--Audit Name Popup-->
<div class="modal fade" id="Audit_Name" role="dialog" aria-labelledby="Audit_Name">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="Audit_Name">Create Audit Name </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                    <div class="box-body">
                        <div class="col-md-12">
                        <div class="box-body">
							
                        </div>
			
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" id="submitApprove">Confirm Submit</button>
					</div>
			
                </div>
             </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Audit Name Popup End -->

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

	function getproduct(id){
		
        var sub    = 'sub6';
		//var company_id = document.getElementById("companY").value;
//alert(sub + ' ' + company_id);
		
		var strURL = "account_func.php";
		$.post(strURL,{id:id,sub6:sub},function(result){
		      $('#getproduct').html(result);
		});

	}
	
	function getproductv(id){
		
        var sub    = 'sub7';
		//var company_id = document.getElementById("companY").value;
//alert(sub + ' ' + company_id);
		
		var strURL = "gin_func.php";
		$.post(strURL,{id:id,sub7:sub},function(result){
		      $('#getproductv').html(result);
		});

	}

    $("#submitApprove").on("click", function(e){
		
		//$('.hidden-div').hide();
		
        var sub = 'sub10';
		
//		alert(sub);

		var company_id	 		=  $("#COMPANY_ID").val();
		var audit_job_name		=  $("#Audit_NAME").val();
		var product_group		=  $("#product_group").val();
		/* var product_name		=  $("#product_name").val();
		var category			=  $("#category").val();
		 */
//alert(audit_job_name);
		
		if(audit_job_name==''){
			alert('Audit Name should not be empty !');
			return false;
		}

		if(company_id=='' || company_id==0){
			alert('Company Name should be select !');
			return false;
		}
		
		$('#predit').html('Wait...');
		
//$('#Audit_Name').modal('hide');
//alert(company_id + ' ' + audit_job_name);
//product_name:product_name,
//category:category,
		var strURL = "gin_func.php";
		$.post(strURL,{ company_id:company_id,
						audit_job_name:audit_job_name,
						product_group:product_group,
						sub10:sub},
						function(result){
		      $('#predit').html(result);
		});
		
	});

	
</script>

</body>
</html>
