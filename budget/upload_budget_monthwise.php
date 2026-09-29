<!DOCTYPE html>
<?php

include("../header.php");
$modulePath = "budget/upload_budget.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">


<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
        <h1>
            Budget Upload (Months fields only)
            <small>Upload</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Budget Upload</a></li>
            
        </ol>
    </section>

    <!-- Content Header (Page header) -->
    <div class="col-md-12">
    
        <!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box">
            <!-- form start -->
            <form class="form-horizontal" action="upload_budget_monthwise.php?sub=upd" method="post" target="_blank" enctype="multipart/form-data" >
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
						<div class="form-group">
							
							<label for="project" class="control-label col-sm-2">Company Name</label>
							<div class="col-sm-4">
									<select class="form-control select2" <?= $readonly ?> name="project" id="project" required >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company where 1 order by comp_name ";//comp_id in ($comid)
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['project'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
							</div>
						
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Fin.Year</label>
							<div class="col-md-2">
								<select class="form-control" name="account_year"  id="account_year" required >
									<option value=""> Select </option>
									<option value="2024-2025" selected >2024-2025</option>
									<option value="2023-2024" <?php echo ($row['account_year']=='2023-2024')?'selected="selected"':'';?>>2023-2024</option>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label for="project" class="control-label col-sm-2">Upload file</label>
							<div class="col-sm-4">
								<input type ="file" class="form-control"  value="" name="updfile">
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

<?php } ?>

<?php if($_GET['sub'] == 'upd'){
	
		$company_id 	= $_POST['project'];
		$account_year 	= $_POST['account_year'];
		
		$infofile = explode(".",$_FILES['updfile']['name']);
echo strtolower(end($infofile));
		$err_msg	= 'Y';
		$updfile = $_FILES['updfile']['name'];
echo $updfile. ' <<<>>><BR>' ;		
		if(strtolower(end($infofile)) != 'csv'){
		    echo "<script>alert('Please upload csv format file...')</script>";
			$bpath = $baseurl.$modulePath;
			exit();
			//echo "<script>window.location.href='$bpath';</script>";
		}
//exit();

if (($handle = fopen($csv_file, "r")) !== FALSE){
	echo "<script>alert('Error in open file...')</script>";
	echo 'Error in open file...';
	exit();
}

//Budget Head validation
			$csv_file=$_FILES['updfile']['tmp_name'];
			if (($handle = fopen($csv_file, "r")) !== FALSE){
						
						fgetcsv($handle);
					//	fgetcsv($handle);
						$prev_dated = '';
						
					    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					
							$num = count($data);
								for ($c=0; $c < $num; $c++){
									$col[$c] = $data[$c];
								}

								$budget_group			= $col[0];
								$budget_subgroup		= $col[1];
								$budget_code			= $col[2];
								$open_budget			= $col[3];
								$board_approved_budget	= $col[4];
								$april	 				= $col[5];
								$may	 				= $col[6];
								$june					= $col[7];
								$july  					= $col[8];
								$august					= $col[9];
								$september				= $col[10];
								$october				= $col[11];
								$november				= $col[12];
								$december				= $col[13];
								$january				= $col[14];
								$february				= $col[15];
								$march					= $col[16];
							
					echo $budget_group . ' ' . $budget_subgroup . ' ' . $budget_code. ' ' . $april. "<BR>";
							//$company = '8';
							//$account_year = '2023-2024';
							/* $sql = " select * from company where comp_code = '$company_id' or comp_name = '$company' ";
//echo $sql. "<BR>";							
							$q2 	= mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$project = $r2['comp_id']; */
							
							$sql = " select * from sma_budget_name where name = trim('$budget_group') ";
					//echo $sql. "<BR>";
							$q2 	= mysqli_query($con, $sql);
							$rowcnt = mysqli_affected_rows($con);
							$r2 = mysqli_fetch_array($q2);
							$budget_name_id = $r2['id'];
							if($rowcnt==0){
								$sql = "INSERT INTO sma_budget_name(name ) VALUES (trim('$budget_group'))";
								mysqli_query($con, $sql);
								$budget_name_id = mysqli_insert_id($con);
								echo $sql. "<BR>";	
							}
							
							$sql = " select * from sma_budget_subgroup where budget_name = '$budget_name_id' 
											AND budget_head = trim('$budget_subgroup') AND budget_code = trim('$budget_code') ";
					//echo $sql. "<BR>";	
							$q2 	= mysqli_query($con, $sql);
							$rowcnt = mysqli_affected_rows($con);
							$r2 = mysqli_fetch_array($q2);
							$budget_category_id = $r2['id'];
							if($rowcnt==0){
								$sql = "INSERT INTO sma_budget_subgroup(budget_name, budget_head, budget_code ) 
											VALUES ('$budget_name_id','$budget_subgroup','$budget_code')";
								mysqli_query($con, $sql);
								$budget_category_id = mysqli_insert_id($con);
								echo $sql. "<BR>";	
							}	
							
					$sql = "select count(*) as cnt from sma_budget where account_year = '$account_year' AND project = '$company_id' 
									AND budget_name = '$budget_name_id' AND budget_head = '$budget_category_id' ";
//echo $sql;
					$q2  = mysqli_query($con, $sql);
					echo mysqli_error($con);
					$r2  = mysqli_fetch_array($q2);
					$cnt = $r2['cnt'];
					
					if($cnt==0){
						$sql = "INSERT INTO sma_budget(account_year, project, budget_name, budget_head, budget_code, april, may, june, july, august, september, october, november, december, january, february, march, total_budget, board_approved_budget ) 
								VALUES ( '$account_year', '$company_id', '$budget_name_id', '$budget_category_id', '$budget_code',
										'$april', '$may', '$june', '$july', '$august', '$september', '$october', '$november', 
										'$december', '$january', '$february', '$march', '$open_budget', '$board_approved_budget' )";
						mysqli_query($con, $sql);
						echo mysqli_error($con);	
echo $sql."<BR>";								
						$err_msg .= "Budget Group : $budget_group , ". " Budget Subgroup : $budget_subgroup". ', Budget Code :'. $budget_code."<BR>";	
						
					} 
					else {
						$sql = "UPDATE sma_budget SET april				= '$april',
													may					= '$may',
													june				= '$june',
													july				= '$july',
													august				= '$august',
													september			= '$september',
													october				= '$october',
													november			= '$november',
													december			= '$december',
													january				= '$january',
													february			= '$february',
													march				= '$march',
													total_budget		= '$open_budget', 
													board_approved_budget= '$board_approved_budget'
									WHERE account_year = '$account_year' AND project = '$company_id' 
										AND budget_name= '$budget_name_id' AND budget_head= '$budget_category_id' ";
echo $sql."<BR>";
						mysqli_query($con, $sql);	
						echo mysqli_error($con);	
					}
					
//echo $sql."<BR>";
//exit();		
				}
			}
			
			if(!empty($err_msg)){
				
				echo "Below budget head not uploaded due to not found in budget head master...<BR>";
				echo "<b>".$err_msg."</b>"."<BR><BR>";
				exit('Please check budget head master or add new ...');
			}
			
//exit('STOPED ####1');			
//Budget Head validation End			  

			if(!empty($err_msg)){
				echo "Error in upload file...";
				echo $err_msg;
				exit();
			}
			else{
				echo "<script>alert(' Budget Uploaded...');window.location.href='budget.php?sub=list';</script>";
			}	

}
?>

