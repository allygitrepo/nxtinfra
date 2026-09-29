<?php

include("../header.php");
$modulePath = "travel_approval/import_utr_bank.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

  <section class="content-header">
        <h1>
            Import Bank UTR
            
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Import Bank UTR </a></li>
            
        </ol>
    </section>

    <!-- Content Header (Page header) -->
    <div class="col-md-12">
		
        <!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box">
            <!-- form start -->
            <form class="form-horizontal" action="import_utr_bank.php?sub=upd" method="post" enctype="multipart/form-data" >
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
						
						<div class="form-group">
							<label for="project" class="control-label col-sm-2">Company</label>
							<div class="col-md-4">
								<select class="form-control" name="company_id" required >
									<option value=""> Select </option>
									<?php $sql = "select * from company where 1 order by comp_name ";//comp_id in ($compid)
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" ><?php echo $r2['comp_name'];?></option>
									<?php } ?>
								</select>
							</div>
						</div>
							
						<div class="form-group">
							<label for="project" class="control-label col-sm-2">Upload file</label>
							<div class="col-sm-4">
								<input type ="file" class="form-control"  value="" name="updfile">
							</div>
						</div>
						
						
					<!--	<div class="form-group">
							<label for="project" class="control-label col-sm-2">Sample File</label>
							<div class="col-sm-4">
								<a href="sample/CE_sample_format_Athaang.csv"><b style="position: relative;top: 5px;"> DOWNLOAD </b></a>
							</div>
						</div>
					-->	
						
						<div class="box-footer">
							<div class="col-sm-2">
								
							</div>
							<?php $baseurl1 = $baseurl.$modulePath;?>
							<div class="col-sm-4 text-right">
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
	
		$company_id = $_POST['company_id'];
		
		$infofile = explode(".",$_FILES['updfile']['name']);
//echo strtolower(end($infofile));
		$updfile = $_FILES['updfile']['name'];
echo $updfile. ' <<<>>>' . $company_id. "<BR>";		
		if(strtolower(end($infofile)) != 'csv'){
		    echo "<script>alert('Please upload csv format file...')</script>";
			$bpath = $baseurl.$modulePath;
			echo "<script>window.location.href='$bpath';</script>";
			exit();
		}
//exit();
		
		if($company_id==5){

			include "import_dc_utr_bank.php";
			exit();

		}
		else if($company_id==10){
			include "import_ktpl_utr_bank.php";
			exit();

		}
		else if($company_id==6 || $company_id==11){

				$comp_id = "6, 11";
				
				$csv_file=$_FILES['updfile']['tmp_name'];
				if (($handle = fopen($csv_file, "r")) !== FALSE){
						
						for ($c=0; $c < 17; $c++){
							fgetcsv($handle);
						}
						
						$prev_dated = '';
					    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					
							$num = count($data);
								for ($c=0; $c < $num; $c++){
									$col[$c] = $data[$c];
								}

								
								$srno			 		= $col[0];
								$trans_id		  		= $col[1];
								$value_date  			= $col[2];
								$transaction_date 		= $col[3];
								$transaction_posted_date= substr($col[4],0,10);
								$cheque_ref_no		    = $col[5];
								$transaction_remarks    = $col[6];
								$withdrawal_amount		= str_replace(',','',$col[7]);
								$deposits_amount		= $col[8];
								$balance_amount			= $col[9];

								//$transaction_posted_date= date('Y-m-d', strtotime($col[4]));
								
						//echo substr($transaction_remarks,0,8). "<BR>";
						
								if(substr($transaction_remarks,0,8)!= 'INF/NEFT'){
									continue;
								}	
							
							$myArray = explode('/', $transaction_remarks);
							$party_name_match = $myArray['5'];
					
					echo $transaction_remarks. ' ' . $value_date. ' ' .  str_replace('/','-',$transaction_posted_date). ' ' . $withdrawal_amount. ' ' . $party_name_match. "<BR>";
							
						//	exit();
							//SELECT trim(utr_no), total_amount_paid FROM `payment_header` where REPLACE(utr_no,' ','') like REPLACE('INF/NEFT/ICICN42025011058157586/SCBL0036057/Professional Fees/ArvindMahajan',' ','') and total_amount_paid >= '90000';
							
							$sql = " INSERT INTO bank_transactions( `file_name`, `company_id`, `srno`, `trans_id`, `value_date`, `transaction_date`, `transaction_posted_date`,party_name, `cheque_ref_no`, `transaction_remarks`, `withdrawal_amount`, `deposit_amount`, `balance_amount`) 
									VALUES ( '$updfile', '$company_id', '$srno', '$trans_id', '$value_date', '$transaction_date', 
										'$transaction_posted_date', '$party_name_match', '$cheque_ref_no', '$transaction_remarks', 
										'$withdrawal_amount', '$deposits_amount', '$balance_amount' ) ";
							mysqli_query($con, $sql);
							echo mysqli_error($con);
							$bank_transactions_id = mysqli_insert_id($con);
							
							
							$sql = " SELECT * FROM `payment_header` 
										where 1 and company_id in ( $comp_id )
												and total_amount_paid = '$withdrawal_amount' 
												 " ;					
							//and REPLACE(utr_no,' ','') like REPLACE('$transaction_remarks',' ','')					 
echo $sql. "<BR>";
							$q2 	= mysqli_query($con, $sql);
							$rwaffect = mysqli_affected_rows($con);
echo	$rwaffect."<BR>";
							echo mysqli_error($con);
							while($r2 = mysqli_fetch_array($q2)){
								
								$py_id	 			= $r2['id'];
								$paid_to 			= $r2['paid_to'];
								$st_flag 			= $r2['st_flag'];
								//$company_id 		= $r2['company_id'];
								$cash_bank_name		= $r2['cash_bank_name'];
								$paid_date			= date('d-m-Y', strtotime($r2['paid_date']));
								$utr_no				= $r2['utr_no'];
								$total_amount_paid	= $r2['total_amount_paid'];
								$cheque_no			= $r2['cheque_no'];
								
								if($st_flag=='S' || $st_flag=='D' || $st_flag=='R' || $st_flag=='C'){
									$sql = " SELECT * FROM `sma_party_mst` where 1 and id = '$paid_to' ";
									$qry 	= mysqli_query($con, $sql);
									$rwaffect = mysqli_affected_rows($con);
									$rw2 = mysqli_fetch_array($qry);
									$party_name 			= $rw2['party_name'];
								}
								else if($st_flag=='T'){
									$sql = " SELECT * FROM `sma_user` where 1 and id = '$paid_to' ";
									$qry 	= mysqli_query($con, $sql);
									$rwaffect = mysqli_affected_rows($con);
									$rw2 = mysqli_fetch_array($qry);
									$party_name 			= $rw2['username'];
								}
								
								$transaction_posted_date= str_replace('/','-',$transaction_posted_date);
								//echo $transaction_posted_date . ' == ' .$paid_date .' '. $party_name_match. ' ' . $party_name. "<BR>";
								if( substr(str_replace(' ','',trim($party_name_match)),0,9) == substr(str_replace(' ','',trim($party_name)),0,9) 
									&&
									$transaction_posted_date == $paid_date ){
									echo	'<== '.$rwaffect.' '. $utr_no. ' '. $party_name.' ' . $paid_date."<BR>";
									
									$sql = "UPDATE bank_transactions SET match_id = '$py_id', matched_date = now() 
											WHERE id = '$bank_transactions_id' ";
									mysqli_query($con, $sql);
								echo $sql. "<BR>";	
									$sql = "UPDATE payment_header SET match_id = '$bank_transactions_id', matched_date = now() 
											WHERE id = '$py_id' "; //$utr_no = '$transaction_remarks'
									mysqli_query($con, $sql);
									
									echo $sql. "<BR>";
									
								}
								
							}
			
							

				}
						
				
							
			}
			

		}		
			
		
		exit('######123');
				echo "<script>alert('Bank UTR Imported...');window.location.href='import_utr_bank.php?sub=list;</script>";
	
}
?>

