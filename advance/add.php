<?php
session_start();

$pgname = "advance/index.php";
include("../viewonly.php");
$sub_menu_hdr = $main_menu;

include("../header.php");
$modulePath = "advance/";  
//$module_name = "advance";

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= $sub_menu;?> 
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>"><?= $sub_menu;?></a></li>
            <li class="active">Create</li>
        </ol>
    </section>
<?php
if (isset($_POST["submit"])) {
    
	//$dated     				= DateTime::createFromFormat('d-m-Y', $_POST["dated"])->format('Y-m-d');
	//$delivery_require_by    	= $_POST["delivery_require_by"] != '' ? DateTime::createFromFormat('d-m-Y', $_POST["delivery_require_by"])->format('Y-m-d') : date('Y-m-d');
	
	$dated						= date('Y-m-d', strtotime($_POST["dated"]));
    $company_id					= $_POST["company_id"];
	$supplier_id   				= $_POST["supplier_id"];

    $department_id 				= $_POST["department_id"];
    $location_id   				= $_POST["location_id"];
    $remarks			     	= $_POST["remarks"];
	$scope_of_work     			= '';
	$subject					= $_POST['subject'];
	
	$po_ref_no					= $_POST['po_ref_no'];
	$advance_amount				= $_POST['advance_amount'];
	$total_po_amount			= $_POST['total_po_amount'];
	
	$status 					= 'Draft';
    
    $yymmdd		= date('ymd', strtotime($dated));
    
	$usrid  =$_SESSION['usrid'];
	$user   = $_SESSION['user'];

			if(!isset($_SESSION['user']) || empty($user) ){
				echo '<script>alert("Session is expired...");</script>';	
				$baseurl1= $baseurl.'index.php';
			   echo "<script>window.location.href='$baseurl1';</script>";
			   exit();
			}
			
			$sql="Select * from sma_financial_year where status = 'Y' ";
			$q2 = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);	
			$yyyy 		= $r2['finyear_prefix'];
			
    $sql = "INSERT INTO sma_advance ( dated, company_id, supplier_id, remarks, department, location, po_ref_no, background, scope_of_work, status, draft_by, draft_date, advance_amount, total_po_amount  ) 
	        VALUES ( '$dated', '$company_id', '$supplier_id', '$remarks', '$department_id', '$location_id', '$po_ref_no', '$background', '$scope_of_work', '$status', '$user', now(), '$advance_amount', '$total_po_amount' )";

    if (mysqli_query($con, $sql)) {
        $last_id = mysqli_insert_id($con);
		$srno = $last_id;
		
		$sql = "insert into workflow_history ( doc_type, doc_id, create_by, create_date, status ) 
									values( 'AV', '$srno', '$usrid', now(), '$status' )";

		$query=mysqli_query($con, $sql);
		$error= mysqli_error($con);
		if(!empty($error)){echo $error; exit();}
		
		$s="select * from sma_user where id='$approver' ";	
		$sql = mysqli_query($con, $s);
		$rowcount = mysqli_num_rows($sql);
		while($r = mysqli_fetch_object($sql)){
			$username 		= $r->userid;
			$id		 		= $r->id;
			$role	 		= $r->role;
			$company_id 	= $r->company_id;
			$user_category 	= $r->user_category;
			$user_email		= $r->email;
			$user_name		= $r->first_name . ' ' . $r->last_name;
		}
	
		$baseurl1 =$baseurl.$modulePath.'edit.php?id='.$srno;
		
			$sql 	= "select * from sma_party_mst where id = '$supplier_id' ";
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$party_name = $r2['party_name'];
			
			$pgname 		= $modulePath."index.php";
			include "../viewonly.php";
			$description 	= $srno. ', PO.Ref.: '. $po_ref_no. ' ' .$department. ','. $party_name;
		    $affect 		= 'Add';
			$user_name		= $_SESSION['user'];
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
		
		//$baseurl .= $modulePath;
		$baseurl1 =$baseurl.$modulePath.'edit.php?id='.$srno;
		echo "<script>window.location.href='$baseurl1';</script>";

		//echo "<script>window.location.href='$baseurl';</script>";
		
        //header("Location: " . $baseurl . $modulePath);
		exit();
		
    }
    else {
        echo "Error: " . mysqli_error($con);
		exit();
    }    
}
?>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- right column -->
            <form class="form-horizontal" action="add.php" method="post" enctype="multipart/form-data">
            <input type="hidden" id="items" name="items">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Create </h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="text-danger" id="err" name="err"></div>
                    <div class="box-body">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab">Advance Note Details</a></li>
                    </ul>
                    <div class="tab-content">
					    <div class="tab-pane active" id="tab_1">
                            	<div class="form-group ">
									<div class="col-xs-2">
										<label for="prDate" class="control-label">Dated</label>
										<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
											<div class="input-group-addon">
												<i class="fa fa-calendar-alt"></i>
											</div>
											<input type="text" class="form-control" id="dated" readonly name="dated" placeholder="dd-mm-yyyy"
												   value="<?php echo date('d-m-Y') ?>">
										</div>
									</div>
							
									
                                    
                                    <div class="col-sm-5">
										<label for="company" class="control-label">Company*</label>
                                        <select class="form-control" required id="company_id" name="company_id" onchange="getsupplier(this.value);" > 
											<option value="">Select</option>
										<?php
                                            $sql="SELECT * FROM company where 1 and comp_id in ($comid) ORDER BY comp_name ASC";
                                            $result = mysqli_query($con, $sql);
                                            echo mysqli_error($con);
                                            while($row = mysqli_fetch_array($result)){
                                        ?>
                                            <option value="<?php echo $row['comp_id']?>" ><?php echo $row['comp_name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
									
									<div class="col-sm-5">	
										<label class="control-label">Supplier Name <span style="color:red;"> **</span></label>
							<span id="getsupplier">			
										<select class="form-control select2 " required id="supplier_id" name="supplier_id" onchange="getporefno(this.value);">
										
											<option value=""> Select </option>
										
										<?php //$sql = "select * from sma_party_mst where 1 order by party_name ";
											$sql = "select distinct(a.to_supplier)  
														FROM sma_purchase_order a, sma_po_items b 
															WHERE a.id = b.purchase_id and a.project = '$company_id' 
																and a.status in ( 'Completed' ) 
																AND ( ( b.quantity > b.bal_si_qty ) 
																OR ( ( ( b.quantity * b.unit_rate) + ((b.quantity * b.unit_rate) * b.gst /100) -1 ) > b.bal_si_amount ) ";
												$q2 	  = mysqli_query($con, $sql);
												while($r2 = mysqli_fetch_array($q2)){ ?>
											<option value="<?php echo $r2['id'];?>" <?php echo ($row['suplier_name'] == $r2['id'])?'selected="selected"':'';?> > <?php echo $r2['party_name'];?></option>
												<?php } ?>
										</select>
							</span>			
									</div>
							
								</div>
								
								<div class="form-group ">
									<span id="getporefno">
									
									</span>
									
									<span id="getproject">
									
									</span>
									
								<div class="col-md-2">
									<label class="control-label">Amount Pay Now</label>
									<input class="form-control" name='advance_amount' id='advance_amount' style="text-align:right;" value ="" >
								</div>
									
                            </div>
							
							
							<div class="form-group">
								
								<div class="col-sm-8">
								    <label for="company_id" class="control-label ">Remarks </label>
                                     <textarea rows = "2" class="form-control" id="remarks" name="remarks"  <?= $readonly;?> ><?= $row['remarks'];?></textarea>
                                </div>
                                
                            </div>

							
				<div class="box-footer">
                    <div class="col-sm-6">
                        <!--<button type="submit" class="btn btn-danger">Delete</button>-->
                    </div>
                    <div class="col-sm-6 text-right">
                        <button type="button" class="btn btn-default" onclick="history.go(-1);">Back</button>
                        <span>&nbsp;&nbsp;</span>
                        <span id="hideSave">
                            <button type="submit" name='submit' class="btn btn-primary">Next</button>
                        </span>
                    </div>
                </div>
						  
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.box-body -->
            </form>
        </div>
        <!-- /.box -->
    </section>
</div>
<!--/.col (right) -->
	
 
<?php
include("../footer.php");
?>

<!-- Select2 -->
<script src="<?php echo $baseurl . "plugins/select2/select2.full.js" ?>"></script>
<!-- InputMask -->
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.date.extensions.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.extensions.js" ?>"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo $baseurl . "plugins/ckeditor/ckeditor.js" ?>"></script>

<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js" ?>"></script>

<!--File Input -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.5/js/plugins/piexif.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.5/js/fileinput.min.js"></script>

<script>
    var itemArray = []; // stores all item details table values in memory

    $(document).ready(function () {
        $('.datepicker').datepicker();
        // $('.datepicker').datepicker({
        //     "format": 'd/M/Y',
        //     "autoclose": true
        // });
        $('.select2').select2();
        CKEDITOR.replace('background_section');
        CKEDITOR.replace('scope_of_work');		
        $("#prItemsTable").DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": false
        });
    });

	function validateDate(delivery_require_by){
	
		let date =
                document.getElementById('delivery_require_by').value;
//alert(date);				
      
		$('#hideSave').show();
		$('#validateDate').html("");
		let dateArray = date.split("-");

			let ddate = `${dateArray[2]}/${dateArray[1]}/${dateArray[0]}`;
			let inpDate = new Date(ddate);	
            let currDate = new Date();		
			
            //if (inpDate.setHours(0, 0, 0, 0) == currDate.setHours(0, 0, 0, 0)) {
			if (inpDate.setHours(0, 0, 0) >= currDate.setHours(0, 0, 0)) {	
                // alert("The input date is today's date");
				//return false;
            }
            else {
				//alert(inpDate.setHours(0, 0, 0) + ' == ' + currDate.setHours(0, 0, 0) );
                //alert("The input date is" +" different from today's date");
				$dataa = currDate.setHours(0, 0, 0) - inpDate.setHours(0, 0, 0);
				if($dataa > 1000){
					$('#hideSave').hide();
					
					$('#validateDate').html("Selecting a past date is not allowed");
					//$('#validateDate').html("The input date is different from today's date "+inpDate.setHours(0, 0, 0) + ' == ' + currDate.setHours(0, 0, 0));
					return false;
				}
				
            }
	}
	
	
	function getporefno(){
        var sub    = 'sub1';
        
        var company_id  = $("#company_id").val();
        var supplier_id = $("#supplier_id").val();
//alert(sub + ' '+ company_id + ' ' + supplier_id);
		var strURL = "app_func.php";
		$.post(strURL,{company_id:company_id,supplier_id:supplier_id,sub1:sub},function(result){
		      $('#getporefno').html(result);
		});
    }
    
	
	function getsupplier(){
        var sub    = 'sub3';
        var company_id  = $("#company_id").val();
        
		var strURL = "app_func.php";
		$.post(strURL,{company_id:company_id,sub3:sub},function(result){
		      $('#getsupplier').html(result);
		});
    }
	
	function getproject(id){
		 var sub    = 'sub2';
        
        //var company_id  = $("#company_id").val();
        //var supplier_id = $("#supplier_id").val();
//alert(sub + ' '+ id );
		var strURL = "app_func.php";
		$.post(strURL,{po_ref_no:id,sub2:sub},function(result){
		      $('#getproject').html(result);
		});
		
	}
	
</script>


</body>
</html>
