<?php

include("../header.php");
$modulePath = "travel_approval/upload_company_expense.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

  <section class="content-header">
        <h1>
            Upload Operating Expense
            
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Upload Operating Expense </a></li>
            
        </ol>
    </section>

    <!-- Content Header (Page header) -->
    <div class="col-md-12">
		
        <!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box">
            <!-- form start -->
            <form class="form-horizontal" action="upload_company_expense.php?sub=upd" method="post" enctype="multipart/form-data" >
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
						
						<input type ="hidden"  value="<?= $_GET['ce_id']; ?>" name="ce_id">
						
						<div class="form-group">
							<label for="project" class="control-label col-sm-2">Upload file</label>
							<div class="col-sm-4">
								<input type ="file" class="form-control"  value="" name="updfile">
							</div>
						</div>
						
						
						<div class="form-group">
							<label for="project" class="control-label col-sm-2">Sample File</label>
							<div class="col-sm-4">
								<a href="sample/CE_sample_format_Athaang.csv"><b style="position: relative;top: 5px;"> DOWNLOAD </b></a>
							</div>
						</div>
						
						<div class="box-footer">
							<div class="col-sm-2">
								
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

<?php 	
		include("../footer.php");	
?>

</body>
</html>

<?php } 	?>


<?php if($_GET['sub'] == 'upd'){
	
		$ce_id = $_POST['ce_id'];
		
		$sql="Select * from sma_travel_expenses where exp_type = 'C' and id = '$ce_id' ";
		$qry 	= mysqli_query($con, $sql);
        $r2 	= mysqli_fetch_array($qry);	
		$company_id 		= $r2['company_id'];
		$approval_number 	= $r2['approval_number'];
		
		$infofile = explode(".",$_FILES['updfile']['name']);
//echo strtolower(end($infofile));
		$updfile = $_FILES['updfile']['name'];
//echo $updfile. ' <<<>>>' ;		
		if(strtolower(end($infofile)) != 'csv'){
		    echo "<script>alert('Please upload csv format file...')</script>";
			$bpath = $baseurl.$modulePath;
			exit();
			//echo "<script>window.location.href='$bpath';</script>";
		}
//exit();
					$approval_ref_no 	= '';
					$total_amount 		= 0 ;
					
					$csv_file=$_FILES['updfile']['tmp_name'];
					if (($handle = fopen($csv_file, "r")) !== FALSE){
						
						fgetcsv($handle);
						$prev_dated = '';
					    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					
							$num = count($data);
								for ($c=0; $c < $num; $c++){
									$col[$c] = $data[$c];
								}

//Expense Type	Date_of_Invoice	Passenger_Name	Date_of_Departure Sector	Invoice_Number	Total_Amount 

								//$vendor_name			= $col[0];
								//$company_code	 		= $col[1];
								$expense_type	 		= $col[0];
								$invoice_number  		= $col[1];
								$invoice_date  			= date('Y-m-d', strtotime($col[2]));
								$exp_amount  		    = $col[3];
								$gst_percent  		    = $col[4];
								$narration 				= $col[5];
								
								$gst_amount = round(($exp_amount * $gst_percent ) / 100,0);
								
					echo $invoice_date. ' ' . $invoice_number. ' ' . $exp_amount. ' ' . $invoice_date. "<BR>";
							
						//	exit();
							
							if($exp_amount<=0){
								continue;
							}
							
							$fin_year = '2022-2023';
							
							$remarks = $narration ;
							
							$total_amount = $total_amount + $exp_amount + $gst_amount;
							
							$sql = " select * from sma_product where `name` = '$expense_type' ";
							$q2 	= mysqli_query($con, $sql);
							echo  mysqli_error($con);
							$rowcnt = mysqli_affected_rows($con);
							
							if($rowcnt==0){
								echo $expense_type. ' '. ' Given Expense type not found in master... Please check expense.';
								exit();
							}
							$r2     = mysqli_fetch_array($q2);
							$exp_id 		= $r2['id'];
							
							$sql = " SELECT * FROM `sma_product_cost_center` where product_id = '$exp_id' and company_id = '$company_id' " ;					
							$q2 	= mysqli_query($con, $sql);
							echo  mysqli_error($con);
							$r2 = mysqli_fetch_array($q2);
							$budget_head = $r2['budget_id'];
							
							$sql = " SELECT * FROM `sma_budget_subgroup` where id = '$budget_head' " ;	
							$q2 	= mysqli_query($con, $sql);
							echo 'Error #77: ' . mysqli_error($con);
							$r2 = mysqli_fetch_array($q2);
							$budget_name = $r2['budget_name'];
							
							$sql = " select * from sma_budget where budget_name = '$budget_name' and budget_head  = '$budget_head' and project = '$company_id' and account_year = '$fin_year' ";
							
							$q2 	= mysqli_query($con, $sql);
							echo  mysqli_error($con);
							$r2 = mysqli_fetch_array($q2);
							$budget_id = $r2['id'];
						 
							$sql = " INSERT into sma_expenses( dated, exp_type, approval_ref_no, reference, invoice_no, amount, gst_amount, gst, budget_id, budget_name, budget_head, note ) values ( '$invoice_date', 'C', '$ce_id', '$exp_id', '$invoice_number', '$exp_amount', '$gst_amount', '$gst_percent', '$budget_id', '$budget_name', '$budget_head', '$remarks' ) ";
							mysqli_query($con, $sql);
							echo mysqli_error($con);

			}
						
				$sql = "UPDATE sma_travel_expenses set total_amount = total_amount + '$total_amount', approval_ref_no = '$ce_id' where id = '$ce_id' ";
				mysqli_query($con, $sql);
				echo mysqli_error($con);
				
				if(!empty($approval_number)){
					$sql = "update sma_budget set blocked_budget = blocked_budget - $total_amount where id = '$budget_id' ";	
//echo $sql. "<BR>";		
					mysqli_query($con, $sql);
					echo mysqli_error($con);
				}
				
				$sql = "update sma_budget set used_budget = used_budget + $total_amount where id = '$budget_id' ";
//echo $sql. "<BR>";				
				mysqli_query($con, $sql);
				echo mysqli_error($con);
							
		}
		//exit('######123');
				echo "<script>alert('Company Expense Uploaded...');window.location.href='company_expense.php?sub=edit&id=$ce_id';</script>";
	
}
?>

