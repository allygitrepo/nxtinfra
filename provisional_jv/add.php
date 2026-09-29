<?php
	include("../header.php");
	$modulePath = "provisional_jv/";
	
	$help_code = $modulePath.'index.php';
	include "../help_code.php";
	
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php

	if(isset($_POST['Save'])){
			
			//$invoice_date			= date('Y-m-d', strtotime($_POST['invoice_date']));
			$dated					= date('Y-m-d', strtotime($_POST['dated']));
			$company_id				= $_POST['company_id'];
			$trans_type				= $_POST['trans_type'];
			$provisional_account_name	= $_POST['provional_jv_name'];
			$mm_yyyy_v 				= $_POST['yyyy'] + 1;
			$fin_year				= $_POST['yyyy'].'-'.$mm_yyyy_v;
			$mm_yyyy				= $_POST['mm'].'-'.$_POST['yyyy'];
			$provisional_jv_flag	= $_POST['provisional_jv_flag'];

			$tally_narration = "";
			$user   	= $_SESSION['user'];
			$userid     = $_SESSION['usrid'];
			if(!isset($_SESSION['user']) || empty($user) ){
				echo '<script>alert("Session is expired...");</script>';	
				$baseurl1= $baseurl.'index.php';
			   echo "<script>window.location.href='$baseurl1';</script>";
			   exit();
			}
			
			$status = 'Draft';
  			$sql="INSERT INTO sma_provisional_jv_hdr ( dated, company_id, trans_type, provisional_account_name, status , draft_by, draft_date, mm_yyyy, tally_narration, provisional_jv_flag )
			Values( '$dated', '$company_id', '$trans_type', '$provisional_account_name', '$status', '$user', now(), '$mm_yyyy', '$tally_narration', '$provisional_jv_flag' )";
//echo $sql."<BR>";			
			$query=mysqli_query($con, $sql);
			$srno = mysqli_insert_id($con);
			$error= mysqli_error($con);
			if(!empty($error)){
				echo "<script>alert('".$error."')</script>";
				$baseurl.=$modulePath.'edit.php?id='.$srno;//.'&active=active'
				echo "<script>window.location.href='$baseurl';</script>";	
				exit();	
			}
			
			$sql = "insert into workflow_history ( doc_type, doc_id, create_by, create_date, status ) 
									values( 'PV', '$srno', '$userid', now(), '$status' )";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}

			$baseurl.=$modulePath.'edit.php?id='.$srno;//.'&active=active'

			echo "<script>window.location.href='$baseurl';</script>";
		
		}

?>

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Provisional Journal
            <small>Add</small>
			<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
			</small>
			
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Provisional Journal</a></li>
            <li class="active">Create</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- right column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
						<form class="form-horizontal" action="add.php?sub=add" method="post" enctype="multipart/form-data">
							<?php
							$sql  = " SELECT max(id) as srno from sma_provisional_jv_hdr ";
							$res  = mysqli_query($con, $sql);
							echo mysqli_error($con);
							$r1 = mysqli_fetch_array($res);
							
							$srno = $r1['srno']+1;
							?>
						
					
						<div class="form-group">	
							<div class="col-md-2">
								<label class="control-label">Serial Number</label>
								<input type="text" class="form-control" id="id" name="srno" readonly style="text-align:right;" value="<?php echo $srno;?>" >
							</div>
							
							<div class="col-md-4">
								<label for="project" class="control-label">Company <span style="color:red;"> **</span> </label>
								<select class="form-control select2" name="company_id" id="company_ID" onchange="getprovName(this.value);getworkflowtype(this.value);" required >
								<option value=""> Select</option>
								<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
								$q2 	= mysqli_query($con, $sql);
								while($r2 = mysqli_fetch_array($q2)){ ?>
								<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['project'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
								<?php } ?>
								</select>
							</div>
							
							<div class="col-md-2">
								<label class="control-label">For Month </label>
								<select class="form-control" id="mm" name="mm" onchange="getDate(this.value);" >
									<option value="" >Select</option>
									<option value="01" >January</option>
									<option value="02" >February</option>
									<option value="03" >March</option>
									<option value="04" >April</option>
									<option value="05" >May</option>
									<option value="06" >June</option>
									<option value="07" >July</option>
									<option value="08" >August</option>
									<option value="09" >September</option>
									<option value="10" >October</option>
									<option value="11" >November</option>
									<option value="12" >December</option>
								</select>
							</div>
							<div class="col-md-2">
								<label class="control-label">Year</label>
								<select class="form-control" id="yyyy" name="yyyy" onchange="getDate(this.value);" >
									<option value="" >Select</option>
									<option value="2023" >2023</option>
									<option value="2024" >2024</option>
									<option value="2025" >2025</option>
								<!--	<option value="2026" >2026</option>-->
								</select>
							</div>
								<!--<label class="control-label">For Month /Year</label>
								<div class="input-group date" data-provide="datepicker" data-date-format="mm-yyyy">
									<input type="text" class="form-control" id="mm_yyyy" name="mm_yyyy"  value="" onchange="getDate(this.value);">
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>-->
								
							
							<div class="col-md-2">
								<label class="control-label">Enter Date</label>
							<span id ="getDate">	
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="dated" name="dated" value="<?php echo date('d-m-Y'); ?>" >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
							</span>	
							</div>	
							
							
						</div>
						
						<div class="form-group">
							
							<div class="col-md-2">
								<label class="control-label">Provisional Expense JV</label><br>
								<input type="radio" id="provisional_exp_jv_flag" name="provisional_jv_flag" checked value="E" onchange="getprovName(this.value);" >
							</div>	
							
							<div class="col-md-2">	
								<label class="control-label">Provisional Revenue JV</label>	<br>
								<input type="radio" id="provisional_rev_jv_flag" name="provisional_jv_flag" value="R" onchange="getprovName(this.value);" >
							</div>	
							
						</div>
						
						
							<span id="getprovName">	
								
							</span>		
							
						
						<div class="form-group">
						
							<div class="col-sm-5">
								<label for="company_id" class="control-label ">Workflow Type *</label>
							<span id="getworkflowtype">		
								<select class="form-control select3" name="trans_type" id="trans_type" required >
								<option value=""> Select </option>
								<?php $sql = "select * from sma_workflow_type where doc_type = 'PV' and status = 'Y' ";
								$q2 	= mysqli_query($con, $sql);
								while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" ><?php echo $r2['workflow_type'];?></option>
								<?php } ?>
								</select>
							</span>										
							</div>
							
									
						</div>
							
						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; 
								?>
							</div>
							<div class="col-sm-6 text-right">
								<a href="<?php echo $baseurl.$modulePath;?>" class="btn btn-default" >Back</a>
								<span>&nbsp;&nbsp;</span>
								<!--<button type="submit" class="btn btn-primary" form="form1" >Save Changes</button>-->
								
							<span id="hidediv">							
								<input class="btn btn-primary" type="submit" onclick="getvalidate()"; value="Next" name="Save">&nbsp;&nbsp;&nbsp;
							</span>
							
							</div>
						</div>
						
														
                        </form>
                    </div>
                </div>
				
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </section>
</div>
<!--/.col (right) -->

<?php
include("../footer.php");
?>

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

</script>
 
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  -->
<script>

	function getsupplier(id){
		
        var sub    				= 'sub14';
		//var company_id		 	=  $("#company_id").val();
		var company_id		 	= id;

		var strURL = "si_func.php";
//alert(sub + ' ' + company_id);		
		$.post(strURL,{company_id:company_id,sub14:sub},function(result){
		      $('#getsupplier').html(result);
			  //alert('result');
		});

	}
	
	
function getprovName(id){
    var sub = 'sub1';
//alert(id);
	
	var company_id = document.getElementById('company_ID').value;
	var st_flag = '';


		if (document.getElementById('provisional_exp_jv_flag').checked) {
		    st_flag = document.getElementById('provisional_exp_jv_flag').value;
		}
		else if (document.getElementById('provisional_rev_jv_flag').checked) {
		    st_flag = document.getElementById('provisional_rev_jv_flag').value;
		}
//alert(id + ' >><< '+ st_flag +' <<>> ' +company_id);			
	var strURL = "app_func.php";
	$.post(strURL,{ sub1:sub,st_flag:st_flag,company_id:company_id},function(result){
			  $('#getprovName').html(result);
		});
}


function getDate(id){
    var sub = 'sub4';

	mm = document.getElementById('mm').value;
	yyyy = document.getElementById('yyyy').value;
	var mmyyyy = mm + '-' + yyyy;
//alert(mmyyyy);	
	var strURL = "app_func.php";
	$.post(strURL,{ sub4:sub,mmyyyy:mmyyyy},function(result){
			  $('#getDate').html(result);
		});
}

</script>

</body>
</html>
