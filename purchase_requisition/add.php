<?php
session_start();

include("../header.php");
$modulePath = "purchase_requisition/";  
//$module_name = "purchase_requisition";

	if($_GET['sub'] == 'delete'){ 
        $id = $_GET['id'];
		$sql="delete from sma_purchase_req where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        $baseurl.=$modulePath;
			echo "<script>window.supplier_id.href='$baseurl';</script>";
	} 

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Purchase Requisition Note
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Purchase Requisition Note</a></li>
            <li class="active">Create</li>
        </ol>
    </section>
<?php
if (isset($_POST["submit"])) {
    
	//$prdate     				= DateTime::createFromFormat('d-m-Y', $_POST["prdate"])->format('Y-m-d');
	//$delivery_require_by    	= $_POST["delivery_require_by"] != '' ? DateTime::createFromFormat('d-m-Y', $_POST["delivery_require_by"])->format('Y-m-d') : date('Y-m-d');
	
	$prdate						= date('Y-m-d', strtotime($_POST["prdate"]));
    $delivery_require_by		= date('Y-m-d', strtotime($_POST["delivery_require_by"]));
    $company_id					= $_POST["company_id"];
	$supplier_id   				= $_POST["supplier_id"];
	$trans_type					= '';
    $department 				= $_POST["department"];
    $delivery_address   		= $_POST["delivery_address"];
    $background_section     	= $_POST["background_section"];
	$scope_of_work     			= $_POST["scope_of_work"];
	$subject					= $_POST['subject'];
	$reason 					= $_POST['reason_remark'];
	
	$status 					= 'Draft';
    
	$supplier_id ='';
	$usrid  =$_SESSION['usrid'];
	$user   = $_SESSION['user'];

			if(!isset($_SESSION['user']) || empty($user) ){
				echo '<script>alert("Session is expired...");</script>';	
				$baseurl1= $baseurl.'index.php';
			   echo "<script>window.location.href='$baseurl1';</script>";
			   exit();
			}
			
// 	$sql = "SELECT max(prid) as srno FROM `sma_purchase_req` where date = '$prdate' ";
// 	$qry = mysqli_query($con, $sql);
// 	$r2	 = mysqli_fetch_array($qry);
// 	$srno = $r2['srno'] + 1;
	//$pr_number = date('Ymd', strtotime($_POST["prdate"])).'-'.$srno;
	
	$sql = "SELECT * FROM company where comp_id = '$company_id' ";
	$q2  = mysqli_query($con, $sql);
	$r2  = mysqli_fetch_array($q2);
	$comp_code 		= $r2['comp_code'];
	$prefix 		= $r2['prefix'];
	$pr_last_number = $r2['pr_last_number']+1;
			
	$sql="Select * from sma_financial_year where status = 'Y' ";
	$q2 = mysqli_query($con, $sql);
	$r2 = mysqli_fetch_array($q2);	
	$yyyy 		= $r2['finyear_prefix'];
			
			//Nxt-Infra/SPV/CGRG/PO/2025-26/0XXX
	$pr_number = $prefix.$comp_code.'/'.'PR'.'/'.$yyyy.'/'.$pr_last_number;
			
	$sql = " UPDATE company set  pr_last_number = '$pr_last_number' where comp_id = '$company_id' ";
	$q2  = mysqli_query($con, $sql);
	
    $sql = "INSERT INTO sma_purchase_req (prid, pr_number, date, delivery_require_by,company_id, supplier_id, trans_type, delivery_address, department_id, background_section, scope_of_work, created_by, status, draft_by, draft_date , subject, reason) 
	            VALUES ('$srno', '$pr_number', '$prdate', '$delivery_require_by', '$company_id', '$supplier_id', '$trans_type', '$delivery_address', '$department', '$background_section', '$scope_of_work', '$usrid', '$status', '$user', now(), '$subject', '$reason' )";
//echo $sql. "<BR>";
    if (mysqli_query($con, $sql)) {
        $last_id = mysqli_insert_id($con);
		$srno = $last_id;
		
		$sql = "insert into workflow_history ( doc_type, doc_id, create_by, create_date, status ) 
									values( 'PR', '$srno', '$usrid', now(), '$status' )";

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
		
//		$msg = 'PR.Number : '.$srno . ' ' . 'Dated : ' . $_POST["prDate"];
//		include "pr_mail.php";
		
			$sql 	= "select * from sma_party_mst where id = '$supplier_id' ";
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$party_name = $r2['party_name'];
			
			$pgname 		= $modulePath."index.php";
			include "../viewonly.php";
			$description 	= $pr_number. ','. $department. ','. $party_name;
		    $affect 		= 'Add';
			$user_name		= $_SESSION['user'];
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
		
		//$baseurl .= $modulePath;
		$baseurl1 =$baseurl.$modulePath.'edit.php?id='.$srno.'&active=active';
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
            <form class="form-horizontal" action="add.php" method="post" onsubmit="return validateInputs123();" enctype="multipart/form-data">
            <input type="hidden" id="items" name="items">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Create Purchase Requisition Note</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="text-danger" id="err" name="err"></div>
                    <div class="box-body">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab">Purchase Requisition Note Details</a></li>
                    </ul>
                    <div class="tab-content">
					    <div class="tab-pane active" id="tab_1">
                            	<div class="form-group ">
									<label for="prDate" class="col-sm-1 control-label">Dated</label>
									<div class="col-xs-2">
										<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
											<div class="input-group-addon">
												<i class="fa fa-calendar-alt"></i>
											</div>
											<input type="text" class="form-control" id="prdate" name="prdate" placeholder="dd-mm-yyyy"
												   value="<?php echo date('d-m-Y') ?>">
										</div>
									</div>
									
								<?php
									$prdate = date('Y-m-d');
									$sql = "SELECT max(prid) as srno FROM `sma_purchase_req` where date = '$prdate' ";
									$qry = mysqli_query($con, $sql);
									$r2	 = mysqli_fetch_array($qry);
									$srno = $r2['srno'] + 1;
									//$pr_number = date('Ymd', strtotime($prdate)).'-'.$srno;
									$pr_number = '';
								?>
									<label for="prDate" class="col-sm-2 control-label">Serial Number</label>
									<div class="col-xs-2">
										<input type="text" class="form-control" id="srno" name="srno" readonly  style="text-align:right;" value="<?php echo $pr_number ?>">
									</div>
                                    <label for="company" class="col-sm-1 control-label">Company*&nbsp;<span data-toggle="tooltip" title="Select Company for which PR is being raised" class="badge bg-light-blue">?</span></label>
                                    <div class="col-sm-4">
                                        <select class="form-control" required id="company_id" name="company_id" onchange="getDeliveryAddress(this.value);" >
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
								</div>
								
								<div class="form-group ">
								
								<label for="department" class="col-sm-2 control-label">Department* <span data-toggle="tooltip" title="Select Department for which PR is being raised" class="badge bg-light-blue">?</span></label>
                                <div class="col-sm-4">
                                    <select class="form-control " id="department" name="department" required >
									<option value="">Select</option>
                                    <?php
                                    	$sql="SELECT id, name FROM sma_department ORDER BY name ASC";
                                        $result = mysqli_query($con, $sql);
                                        echo mysqli_error($con);
                                        while($row = mysqli_fetch_array($result)){
                                    ?>
                                        <option value="<?php echo $row['id']?>"><?php echo $row['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
								
                            </div>
							
							<div class="form-group ">
								<label for="delivery_Address" class="col-sm-2 control-label">Delivery Address</label>
                                <div class="col-xs-6">
                                <span id="getDeliveryAddress">
                                    <input type="text" class="form-control" id="delivery_address" name="delivery_address" >
                                </span>    
                                </div>
								
								<label for="delivery_require_by" class="col-sm-2 control-label">Required by Date *</label>
                                <div class="col-xs-2">
                                    <div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                        <input type="text"  class="form-control" required id="delivery_require_by" name="delivery_require_by" placeholder="dd-mm-yyyy">
                                    </div>
                                </div>
								
							</div>
                            
						
							
							<div class="form-group">
								<label for="company_id" class="control-label col-sm-2">Subject *</label>
								<div class="col-sm-10">
                                     <input type="text" class="form-control" id="subject" name="subject"  value="" >
                                </div>
                            </div>
							
							<div class="col-md-12">
                                <div class="box">
                                    <div class="box-header"><span class="box-title">Remarks</span></div>
                                    <div class="box-body">
                                        <textarea class="form-control" rows="2" name="reason_remark" ></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="box">
                                    <div class="box-header"><span class="box-title">Background Section</span></div>
                                    <div class="box-body">
                                        <textarea class="form-control" id="background_section" name="background_section"
                                                  placeholder="Enter text ..."></textarea>
                                    </div>
                                </div>
                            </div>
							
							<div class="col-md-12">
                                <div class="box">
                                    <div class="box-header"><span class="box-title">Scope of Work Section</span></div>
                                    <div class="box-body">
                                        <textarea class="form-control" id="scope_of_work" name="scope_of_work"
                                                  placeholder="Enter text ..."></textarea>
                                    </div>
                                </div>
                            </div>

							
				<div class="box-footer">
                    <div class="col-sm-6">
                        <!--<button type="submit" class="btn btn-danger">Delete</button>-->
                    </div>
                    <div class="col-sm-6 text-right">
                        <button type="button" class="btn btn-default" onclick="history.go(-1);">Back</button>
                        <span>&nbsp;&nbsp;</span>
                        <button type="submit" name='submit' class="btn btn-primary">Next</button>
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

    function validateInputs() {
        if ($("#delivery_require_by").val() === '') {
            return false;
        }
        if (itemArray.length == 0) {
            $("#err").html("Please add items to the Purchase Requisition Note");
            return false;
        }
        $("#items").val(JSON.stringify(itemArray));
        console.log($("#items"));
        return true;
    }

    function calculateTotalAmount() {
        var qty = $('#itemQuantity').val();
        var rate = $('#itemRate').val();
        var amt = qty * rate;
        amt = parseFloat(amt);
        amt = amt.toLocaleString('en-US', { style: 'currency', currency: 'INR' });
        $('#itemAmount').val(amt);
    }

    $('#modalDeleteItem').on('show.bs.modal', function(e) {
        var tempId = $(e.relatedTarget).data('id');
        var i;
        for (i = 0; i < itemArray.length; i++) {
            var obj = itemArray[i];
            if (obj.tempId == tempId) {
                $(e.currentTarget).find('input[id="itemTempId"]').val(tempId);
                $(e.currentTarget).find('div[class="modal-body"]').html('Are you sure you want to delete item "' + obj.name + "'");
                break;
            }
        }
    });

    $("#btnDeleteItemYes").on("click", function(e){
        var i;
        for (i = 0; i < itemArray.length; i++) {
            //var obj = itemArray[i];
            if (itemArray[i].tempId == $("#itemTempId").val()) {
                itemArray.splice(i, 1);
                break;
            }
        }
        buildItemsTable();
        $('#modalDeleteItem').modal('hide');
    });

    function setSelectedValue(object, value) {
        for (var i = 0; i < object.options.length; i++) {
            if (object.options[i].text === value) {
                object.options[i].selected = true;
                return;
            }
        }

        // Throw exception if option `value` not found.
        var tag = object.nodeName;
        var str = "Option '" + value + "' not found";

        if (object.id != '') {
            str = str + " in //" + object.nodeName.toLowerCase()
                + "[@id='" + object.id + "']."
        }

        else if (object.name != '') {
            str = str + " in //" + object.nodeName.toLowerCase()
                + "[@name='" + object.name + "']."
        }

        else {
            str += "."
        }

        throw str;
    }

    $('#modalAddItem').on('show.bs.modal', function(e) {
        var mode = $(e.relatedTarget).data('mode');
        $(e.currentTarget).find('input[id="mode"]').val(mode);
        if (mode === 'add') {
            // clear existing values
            $("#itemDescription").val("");
            $("#itemQuantity").val(0);
            $("#itemUnits").val("");
            $("#itemRate").val(0);
            $("#itemAmount").val(0);
        }
        else {
            var tempId = $(e.relatedTarget).data('id');
            $(e.currentTarget).find('input[id="tempId"]').val(tempId);
            var i;
            for (i = 0; i < itemArray.length; i++) {
                var obj = itemArray[i];
                if (obj.tempId == tempId) {
                    $(e.currentTarget).find('input[id="itemTempId"]').val(tempId);
                    //$(e.currentTarget).find('select[id="itemName"]').val(obj.id);
                    setSelectedValue($(e.currentTarget).find('select[id="itemName"]')[0], obj.name);
                    $(e.currentTarget).find('input[id="itemDescription"]').val(obj.description);
                    $(e.currentTarget).find('input[id="itemQuantity"]').val(obj.quantity);
                    $(e.currentTarget).find('input[id="itemUnits"]').val(obj.units);
                    $(e.currentTarget).find('input[id="itemRate"]').val(obj.rate);
                    $(e.currentTarget).find('input[id="itemAmount"]').val(obj.amount);
                    break;
                }
            }
        }
    });

    $("#addItem").on("click", function(e){
        var mode = $("#mode").val();
        saveItem(mode);
        $('#modalAddItem').modal('hide');
    });

    function saveItem(mode) {    
        if (mode === 'add') {
            var objItem = new Object();
            objItem.tempId = 621355968000000000 + (new Date().getTime() * 10000);
            objItem.id =            $("#itemName option:selected").val();
            objItem.name =          $("#itemName option:selected").html();
            objItem.description =   $("#itemDescription").val();
            objItem.quantity =      $("#itemQuantity").val();
            objItem.units =         $("#itemUnits").val();
            objItem.rate =          $("#itemRate").val();
            objItem.amount =        $("#itemAmount").val();
            itemArray.push(objItem);
        }
        else {
            for (var i = 0; i < itemArray.length; i++) {
                if (itemArray[i].tempId == $("#tempId").val()) {
                    itemArray[i].id =            $("#itemName option:selected").val();
                    itemArray[i].name =          $("#itemName option:selected").html();
                    itemArray[i].description =   $("#itemDescription").val();
                    itemArray[i].quantity =      $("#itemQuantity").val();
                    itemArray[i].units =         $("#itemUnits").val();
                    itemArray[i].rate =          $("#itemRate").val();
                    itemArray[i].amount =        $("#itemAmount").val();
                    break;
                }
            }
        }
        $("#items").val(itemArray);
        //console.log($("#items").val());

        // clear existing values
        $("#itemDescription").val("");
        $("#itemQuantity").val(0);
        $("#itemUnits").val("");
        $("#itemRate").val(0);
        $("#itemAmount").val(0);

        buildItemsTable();
    }

    function buildItemsTable() {
        $("#prItemsTableBody").empty();
        var tableRef = document.getElementById('prItemsTable').getElementsByTagName('tbody')[0]

        if (itemArray.length == 0) {
            var newRow = tableRef.insertRow(tableRef.rows.length);
            // action buttons
            var newCell = newRow.insertCell(0);
            var no_rows_content = document.createElement("SPAN");
            no_rows_content.innerHTML = "No data available in table";
            newCell.class = "dataTables_empty";
            newCell.colSpan = 8;
            newCell.vAlign = "top";
            newCell.appendChild(no_rows_content);
        }
        var i, j;    
        for (i = 0; i < itemArray.length; i++) {
            var obj = itemArray[i];
            var newRow = tableRef.insertRow(tableRef.rows.length);
            // action buttons
            // item name
            newCell = newRow.insertCell(0);
            var newText = document.createTextNode(obj.name);
            newCell.appendChild(newText);

            // item name
            newCell = newRow.insertCell(1);
            newText = document.createTextNode(obj.description);
            newCell.appendChild(newText);

            // item name
            newCell = newRow.insertCell(2);
            newText = document.createTextNode(obj.quantity);
            newCell.appendChild(newText);

            // item name
            newCell = newRow.insertCell(3);
            newText = document.createTextNode(obj.units);
            newCell.appendChild(newText);

            // item name
            newCell = newRow.insertCell(4);
            newText = document.createTextNode(obj.rate);
            newCell.appendChild(newText);

            // item name
            newCell = newRow.insertCell(5);
            newText = document.createTextNode(obj.amount);
            newCell.appendChild(newText);
			
            var newCell = newRow.insertCell(6);            
			var actionBtns = document.createElement("SPAN");
            actionBtns.innerHTML = "<a href='#modalAddItem' data-id='" + obj.tempId + "' data-mode='edit' data-toggle='modal' data-target='#modalAddItem'><i class='fa fa-edit'></i></a>&nbsp;&nbsp;<a href='#modalDeleteItem' id='delete-" + obj.tempId +  "' data-toggle='modal' data-id='" + obj.tempId +  "' data-target='#modalDeleteItem'><i class='fa fa-trash-alt'></i></a>";
            newCell.style.width = "48px";
            newCell.appendChild(actionBtns);

        }
    }


	function getcategory(id){
        var sub    = 'sub1';
//alert(sub);		
		var strURL = "pr_func.php";
		$.post(strURL,{id:id,sub1:sub},function(result){
		      $('#getcategory').html(result);
		});

	}

	function getuom(id){
        var sub    = 'sub2';
		var strURL = "pr_func.php";
		$.post(strURL,{id:id,sub2:sub},function(result){
		      $('#getuom').html(result);
		});

	}

	
	function getlocation(id){
		
        var sub    = 'sub5';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub5:sub},function(result){
		      $('#getlocation').html(result);
		});

	}

	function getuser(id){
		
        var sub    = 'sub8';
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub8:sub},function(result){
		      $('#getuser').html(result);
		});

	}

	function getworkflowtype(id){
		
        var sub    = 'sub27';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub27:sub},function(result){
		      $('#getworkflowtype').html(result);
		});

	}
	
	
	function getDeliveryAddress(id){
		
        var sub    = 'sub28';
//alert(sub);
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub28:sub},function(result){
		      $('#getDeliveryAddress').html(result);
		});

	}
	
</script>


</body>
</html>
