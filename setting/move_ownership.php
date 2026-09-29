<?php
	include("../header.php");
	$modulePath = "setting/move_ownership.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>

<?php
 
	if(isset($_POST['Save'])){
			
			$doc_type_v			= $_POST['doc_type'];
			$doc_no				= $_POST['doc_no'];
			$from_user			= $_POST['from_user'];
			$to_user			= $_POST['to_user'];
			$from_date			= date('Y-m-d', strtotime($_POST['from_date']));
			$to_date			= date('Y-m-d', strtotime($_POST['to_date']));
//echo $doc_type . "<BR>";			
// $doc_type_array = array('GR', 'SI', 'PO', 'AP', 'PR', 'GI', 'PY', 'CE', 'TE', 'RE', 'TA');

		$sqlz = "  ";
		
		if(empty($doc_type_v)){
			$doc_type_array = array('GR', 'SI', 'PO', 'AP', 'PR', 'GI', 'PY', 'CE', 'TE', 'RE', 'TA');
		}
		else {
			
			//$from_date = '';	
			//$to_date   = '';
			$sqlz = " OR id = '$doc_no' ";	
			$doc_type_array = array($doc_type_v);
			
		}
		
		foreach($doc_type_array as $doc_type){

			if($doc_type=='GR'){
				
				$table_name = 'sma_supplier_invoice';
				$sqla = " and grn_status = 'Submitted' and ( created_date >= '$from_date' and created_date <= '$to_date' $sqlz ) ";
				
			}
			else if($doc_type=='SI'){
				
				$table_name = 'sma_supplier_invoice';
				$sqla = " and status = 'Submitted' and ( created_date >= '$from_date' and created_date <= '$to_date'  $sqlz )";
				
			}
			else if($doc_type=='PO'){
				
				$table_name = 'sma_purchase_order';
				$sqla = " and status = 'Submitted' and (  dated >= '$from_date' and dated <= '$to_date'  $sqlz )";
				
			}
			else if($doc_type=='AP'){
				
				$table_name = 'sma_approval_memo';
				$sqla = " and status = 'Submitted' and ( dated >= '$from_date' and dated <= '$to_date'  $sqlz )";
				
			}
			else if($doc_type=='PR'){
				
				$table_name = 'sma_purchase_req';
				$sqla = " and status = 'Submitted' and ( date >= '$from_date' and date <= '$to_date'  $sqlz )";
				
			}
			else if($doc_type=='GI'){
				
				$table_name = 'sma_goods_issue_note';
				$sqla = " and status = 'Submitted' and ( dated >= '$from_date' and dated <= '$to_date'  $sqlz )";
				
			}
			else if($doc_type=='PY'){
				
				$table_name = 'payment_header';
				$sqla = " and status = 'Submitted' and ( dated >= '$from_date' and dated <= '$to_date'  $sqlz )";
				
			}
			else if($doc_type=='CE' || $doc_type=='TE' || $doc_type=='RE'){
				
				$table_name = 'sma_travel_expenses';
				$sqla = " and status = 'Submitted' and ( dated >= '$from_date' and dated <= '$to_date'  $sqlz )";
				
			}
			else if($doc_type=='TA'){
				
				$table_name = 'sma_traval_approval';
				$sqla = " and status = 'Submitted' and ( dated >= '$from_date' and dated <= '$to_date'  $sqlz )";
				
			}
		
			$sql 	= "SELECT * FROM $table_name WHERE 1 " . $sqla;
//echo $sql . "<BR>";			
			$q2 	= mysqli_query($con, $sql);
			while($r2 = mysqli_fetch_array($q2)){
				
				$doc_no 	= $r2['id'];
				if($doc_type=='GR'){
					$grn_approver = $r2['grn_approver'];
				}
				else {
					$approver_1 = $r2['approver_1'];
					$approver_2 = $r2['approver_2'];
					$approver_3 = $r2['approver_3'];
					$approver_4 = $r2['approver_4'];
					$approver_5 = $r2['approver_5'];
					$approver_6 = $r2['approver_6'];
					$approver_7 = $r2['approver_7'];
					$approver_8 = $r2['approver_8'];
					
					$approver_1_status = $r2['approver_1_status'];
					$approver_2_status = $r2['approver_2_status'];
					$approver_3_status = $r2['approver_3_status'];
					$approver_4_status = $r2['approver_4_status'];
					$approver_5_status = $r2['approver_5_status'];
					$approver_6_status = $r2['approver_6_status'];
					$approver_7_status = $r2['approver_7_status'];
					$approver_8_status = $r2['approver_8_status'];
				}
				
				if($doc_type=='GR'){
					if($grn_approver == $from_user){
						$sql = " UPDATE $table_name SET grn_approver = '$to_user', current_approver = '$to_user' WHERE id = '$doc_no' and grn_status = 'Submitted' ";
					}	
				}
				else {
					
					if($approver_1 == $from_user && $approver_1_status == 'Submitted' ){
						$sql = " UPDATE $table_name SET approver_1 = '$to_user', current_approver = '$to_user' WHERE id = '$doc_no' and status = 'Submitted' and approver_1_status = 'Submitted' ";
						
					}
					else if($approver_2 == $from_user && $approver_2_status == 'Submitted'){
						$sql = " UPDATE $table_name SET approver_2 = '$to_user', current_approver = '$to_user' WHERE id = '$doc_no' and status = 'Submitted' and approver_2_status = 'Submitted'";
						
					}
					else if($approver_3 == $from_user && $approver_3_status == 'Submitted'){
						$sql = " UPDATE $table_name SET approver_3 = '$to_user', current_approver = '$to_user' WHERE id = '$doc_no' and status = 'Submitted' and approver_3_status = 'Submitted'";
						
					}
					else if($approver_4 == $from_user && $approver_4_status == 'Submitted'){
						$sql = " UPDATE $table_name SET approver_4 = '$to_user', current_approver = '$to_user' WHERE id = '$doc_no' and status = 'Submitted' and approver_4_status = 'Submitted'";
						
					}
					else if($approver_5 == $from_user && $approver_5_status == 'Submitted'){
						$sql = " UPDATE $table_name SET approver_5 = '$to_user', current_approver = '$to_user' WHERE id = '$doc_no' and status = 'Submitted' and approver_5_status = 'Submitted'";
						
					}
					else if($approver_6 == $from_user && $approver_6_status == 'Submitted'){
						$sql = " UPDATE $table_name SET approver_6 = '$to_user', current_approver = '$to_user' WHERE id = '$doc_no' and status = 'Submitted' and approver_6_status = 'Submitted'";
						
					}
					else if($approver_7 == $from_user && $approver_7_status == 'Submitted'){
						$sql = " UPDATE $table_name SET approver_7 = '$to_user', current_approver = '$to_user' WHERE id = '$doc_no' and status = 'Submitted' and approver_7_status = 'Submitted'";
						
					}
					else if($approver_8 == $from_user && $approver_8_status == 'Submitted'){
						$sql = " UPDATE $table_name SET approver_8 = '$to_user', current_approver = '$to_user' WHERE id = '$doc_no' and status = 'Submitted' and approver_8_status = 'Submitted'";
						
					}
				}	
//echo $sql . "<BR>";				
				mysqli_query($con, $sql);
				echo mysqli_error($con);
				
				$sql = " SELECT * from workflow_history WHERE doc_type = '$doc_type' AND doc_id = '$doc_no' AND status in ( 'Approved','Submitted' ) ";	
				$qw2 	= mysqli_query($con, $sql);
//echo $sql . "<BR>";				
				while($rw2 = mysqli_fetch_array($qw2)){
						
					$reviewed_by = $rw2['reviewed_by'];
					$id 		 = $rw2['id'];
					
					if($reviewed_by==$from_user){
						
						$sql = " UPDATE workflow_history SET reviewed_by = '$to_user' WHERE id = '$id' ";
						mysqli_query($con, $sql);
						echo mysqli_error($con);
			//echo $sql . "<BR>";			
						
					}
					
				}
				
			}
			
		}
//exit('Exit Here....');
				
		echo '<script>window.location.href="move_ownership.php?sub=list";</script>';
		exit();
			
	}
	
?>

    <section class="content-header">
        <h1>
            Move Ownership
            
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Move Ownership</a></li>
            
        </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
            <!-- form start -->
            <form class="form-horizontal" action="move_ownership.php?sub=list" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      
						<div class="form-group">
							<label for="company_id" class="control-label col-sm-2">From User *</label>
								
							<div class="col-sm-5">
								<select class="form-control select3" name="from_user" id="from_user" required >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_user where 1 order by username ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" >  <?php echo $r2['username'];?></option>
										<?php } ?>
								</select>
							 </div>
						</div> 
						
						<div class="form-group">
							<label for="company_id" class="control-label col-sm-2">To User *</label>
							<div class="col-sm-5">
								
								<select class="form-control select3" name="to_user" id="to_user" required >
                             		<option value=""> Select </option>
										<?php $sql = "select * from sma_user where 1 order by username ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" >  <?php echo $r2['username'];?></option>
										<?php } ?>
								</select>
							 </div>
						</div> 
						
						<div class="form-group">
							<label class="col-lg-2 control-label">From Date *</label>
							<div class="col-md-2">
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="from_date" name="from_date"  autocomplete="off" value="" > 
										<div class="input-group-addon">
												<i class="fa fa-calendar-alt"></i>
										</div>
								</div>
									
							</div>
							
							<label class="col-lg-2 control-label">To Date *</label>
							<div class="col-md-2">
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="to_date" name="to_date" autocomplete="off"  value="" > 
										<div class="input-group-addon">
												<i class="fa fa-calendar-alt"></i>
										</div>
								</div>
									
							</div>
							
						</div>
						
						
						<div class="form-group">
							
							<label for="company_id" class="control-label col-sm-2">Type of Form </label>
							<div class="col-sm-4">
								<select class="form-control select2" name="doc_type" id="doc_type"  >
									<option value=""> Select </option>
									<option value=""> All </option>
									<option value="PR" > Material Requisition</option>
									<option value="AP" > Approval Memo</option>
									<option value="PO"> Purchase Order </option>
									<option value="GR" > GRN</option>
									<option value="SI"> Supplier Invoice </option>	
									<option value="GI" > Goods Issued Notes</option>
									<option value="PY"> Payment </option>	
									<option value="CE"> Operating Expense </option>
									<option value="TE"> Travel Expenses </option>
									<option value="RE"> Reimbursement </option>
									<option value="TA"> Travel Request </option>
									<option value="BD"> Budget Adjustment </option>
									<option value="TN"> Tender </option>	
									
								</select>
							</div>

						</div>
					
						<div class="form-group">
							<label class="col-lg-2 control-label">Doc.No.</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="doc_no" name="doc_no" autocomplete="off" value="" > 
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

</body>
</html>
