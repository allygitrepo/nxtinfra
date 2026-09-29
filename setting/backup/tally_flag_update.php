<!DOCTYPE html>
<?php

include("../header.php");
$modulePath = "setting/tally_flag_update.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">


<?php if($_GET['sub'] == 'list'){
?>

    <section class="content-header">
        <h1>
            Tally Revert updated flag
            <small>Tally Update</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Tally Revert</a></li>
            
        </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
            <!-- form start -->
            <form class="form-horizontal" action="tally_flag_update.php?sub=edit" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      
						<div class="form-group">
								<label class="col-lg-2 control-label">Document Type</label>
								<div class="col-md-3">
									<select class="form-control" name="doc_type" id="doc_type" required >
										<option value=""> Select </option>
										<option value="SI"> Supplier Invoice</option>
										<option value="OE"> Operating Expense </option>
										<option value="RE"> Regular Expense</option>
										<option value="TE"> Travel Expense</option>
										<option value="PY"> Payment</option>
										<option value="PC"> Petty Cash</option>
									</select>	
								</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Doc.No.</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="doc_no" name="doc_no" onkeyup="getdetail(this.value)" autocomplete="off" value="">
							</div>
						</div>
						
						<span id="getdetail">
						
						</span>
						
						<div class="box-footer">
							<div class="col-sm-6">
							</div>
							<?php $baseurl1 = $baseurl.$modulePath;?>
							<div class="col-sm-6 text-right">
								<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Cancel</a>
								<span>&nbsp;&nbsp;</span>
								<input class="btn btn-primary" type="submit" value="Submit" name="Save">&nbsp;&nbsp;&nbsp;
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


<?php 
	if($_GET['sub'] == 'edit'){
		
				$doc_no				= $_POST['doc_no']; 
				$doc_type			= $_POST['doc_type'];
				
				$sql="update tally_journal_entry set status ='' where doc_no = '$doc_no' and doc_type = '$doc_type' ";
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
				
				if($doc_type == 'SI'){
					$sql = " update sma_supplier_invoice set tally_status	= '', tally_updated_on	= '' where id='$doc_no' ";	
				}		
				if($doc_type == 'OE'){
					$sql = " update sma_travel_expenses set tally_status	= '', tally_updated_on	= '' where id='$doc_no' ";
				}
				if($doc_type == 'RE' || $doc_type == 'TE'){
					$sql = " update sma_travel_expenses set tally_status	= '', tally_updated_on	= '' where id='$doc_no' ";
				}
				if($doc_type == 'PY'){
					$sql = " update payment_header set tally_status	= '', tally_updated_on	= '' where id='$doc_no' ";
				}
				if($doc_type == 'PC'){
					$sql = " update sma_pettycash set tally_status	= '', tally_updated_on	= '' where id='$doc_no' ";
				}
				
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
				
				echo "<script>alert('Successfully updated...')</script>";
				
				echo '<script>window.location.href="tally_flag_update.php?sub=list";</script>';
		
	}
?>


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

	function getdetail(id){
		
		var doc_type = document.getElementById('doc_type').value;
		
//		alert('Hello.... ' + doc_type);
		
		var sub    = 'sub1';
		var strURL = "sett_func.php";
		$.post(strURL,{id:id,doc_type:doc_type,sub1:sub},function(result){
		      $('#getdetail').html(result);
		});
		
	}
	
</script>


</body>
</html>
