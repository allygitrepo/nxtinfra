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
            Adjust Budget Upload
            <small>Upload</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Adjust Budget Upload</a></li>
            <li class="active">Create</li>
        </ol>
    </section>

    <!-- Content Header (Page header) -->
    <div class="col-md-12">
    
		
        <!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box">
            <!-- form start -->
            <form class="form-horizontal" action="upload_adjust_budget.php?sub=upd" method="post" enctype="multipart/form-data" >
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
						
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
	
		$infofile = explode(".",$_FILES['updfile']['name']);
echo strtolower(end($infofile));
		$err_msg	= '';
		$updfile = $_FILES['updfile']['name'];
echo $updfile. ' <<<>>>' ;		
		if(strtolower(end($infofile)) != 'csv'){
		    echo "<script>alert('Please upload csv format file...')</script>";
			$bpath = $baseurl.$modulePath;
			exit();
			//echo "<script>window.location.href='$bpath';</script>";
		}
//exit();

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

								$dated					= $col[0];
								$fin_year				= $col[1];
								$company				= $col[2];
								$budget_name	 		= $col[3];
								$budget_head	 		= $col[4];
								$effect					= $col[5];
								$budget_amount  		= $col[6];
								$adjust_type			= $col[7];
								$remarks				= $col[8];
							
							
							if($budget_amount==0){
								continue;
							}
							
					//echo $company . ' ' . $budget_name . ' ' . $budget_head. ' ' . $budget_amount. "<BR>";
					
							$sql = " select * from company where comp_code = '$company' or comp_name = '$company' ";
							$q2 	= mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$project = $r2['comp_id'];
							
							$sql = " select * from sma_budget_name where name = '$budget_name' ";
					//echo $sql. "<BR>";		
							$q2 	= mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$budget_name_id = $r2['id'];
							
							$sql = " select * from sma_budget_category where category = '$budget_head' ";
					//echo $sql. "<BR>";		
							$q2 	= mysqli_query($con, $sql);
							$rowcnt = mysqli_affected_rows($con);
							$r2 = mysqli_fetch_array($q2);
							$budget_category_id = $r2['id'];
							
					$sql = "select count(*) as cnt from sma_budget where project = '$project' and budget_name = '$budget_name_id' and budget_category = '$budget_category_id' ";
			//echo $sql; exit();		
					$q2  = mysqli_query($con, $sql);
					$r2  = mysqli_fetch_array($q2);
					$cnt = $r2['cnt'];
					
					if($cnt==0){
						$err_msg .= "Budget Name : $budget_name , ". " Budget Head : $budget_head"."<BR>";	
						
					}
					
//echo $sql."<BR>";

				}
			}
			
			if(!empty($err_msg)){
				
				echo "Below budget head not uploaded due to not found in budget head master...<BR>";
				echo "<b>".$err_msg."</b>"."<BR><BR>";
				exit('Please check budget head master or add new ...');
			}
			
//exit('STOPED ####1');			
//Budget Head validation End			  

//Date Validation

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

								$dated					= $col[0];
								$fin_year				= $col[1];
								$company				= $col[2];
								$budget_name	 		= $col[3];
								$budget_head	 		= $col[4];
								$effect					= $col[5];
								$budget_amount  		= $col[6];
								$adjust_type			= $col[7];
								$remarks				= $col[8];
								
							//	$adated = explode('/',$dated);
								
								$adated = explode('-',$dated);
								
							//echo $dated. ' ' .  "<BR>";
							
							//print_r($adated);
							
							//$ardate = $adated[2].'-'.$adated[0].'-'.$adated[1];
							$ardate = $adated[2].'-'.$adated[1].'-'.$adated[0];

				//echo $ardate; exit();
						
							$sql 	= " select * from company where comp_code = '$company' or comp_name = '$company' ";
							$q2 	= mysqli_query($con, $sql);
							$r2 	= mysqli_fetch_array($q2);
							$project = $r2['comp_id'];
							
						$sql = "delete from budget_adjust where project = '$project' and dated = '$ardate' and upload_flag = 'Y' ";
						$q2 	= mysqli_query($con, $sql);
				//echo $sql;		
						break;
						
					}
			  }
			  
///exit();			
					
					if (($handle = fopen($csv_file, "r")) !== FALSE){
						
						fgetcsv($handle);
					//	fgetcsv($handle);
						$prev_dated = '';
					    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					
							$num = count($data);
								for ($c=0; $c < $num; $c++){
									$col[$c] = $data[$c];
								}

								$dated					= $col[0];
								$fin_year				= $col[1];
								$company				= $col[2];
								$budget_name	 		= $col[3];
								$budget_head	 		= $col[4];
								$effect					= $col[5];
								$budget_amount  		= $col[6];
								$adjust_type			= $col[7];
								$remarks				= $col[8];
								
							if($budget_amount==0){
								continue;
							}
							
					//echo $company . ' ' . $budget_name . ' ' . $budget_head. ' ' . $budget_amount. "<BR>";
					
							$sql = " select * from company where comp_code = '$company' or comp_name = '$company' ";
							$q2 	= mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$project = $r2['comp_id'];
							
							$sql = " select * from sma_budget_name where name = '$budget_name' ";
					//echo $sql. "<BR>";		
							$q2 	= mysqli_query($con, $sql);
							$r2 = mysqli_fetch_array($q2);
							$budget_name_id = $r2['id'];
							
							$sql = " select * from sma_budget_category where category = '$budget_head' ";
					//echo $sql. "<BR>";		
							$q2 	= mysqli_query($con, $sql);
							$rowcnt = mysqli_affected_rows($con);
							$r2 = mysqli_fetch_array($q2);
							$budget_category_id = $r2['id'];
							$category = $r2['category'];
							
							/* if(empty($category)){
								$sql = "INSERT INTO sma_budget_category(category) values('$budget_head') ";
								mysqli_query($con, $sql);
								$budget_category_id = mysqli_insert_id($con);
							}	 */
					
							if($effect == 'Increase'){
								$effect = 'I';
							}
							else if($effect == 'Decrease'){
								$effect = 'D';
							}
							
					$sql = "select count(*) as cnt from sma_budget where project = '$project' and budget_name = '$budget_name_id' and budget_category = '$budget_category_id' ";
			//echo $sql; exit();		
					$q2  = mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$cnt = $r2['cnt'];
					
					
					if($rowcnt==1 && $cnt==1){
						$sql="insert into budget_adjust ( fin_year, project, budget_name, budget_head, amount, effect, dated,  remarks ,adjust_type, date_uploaded , upload_flag) 
						Values( '$fin_year', '$project', '$budget_name_id', '$budget_category_id', '$budget_amount', '$effect', '$ardate', '$remarks', '$adjust_type', now(), 'Y' )";
						mysqli_query($con, $sql)  ;
						echo mysqli_error($con) ;
	//echo $sql."<BR>";
						$sql = "update sma_budget set total_budget = 0 where project = '$project' and budget_name = '$budget_name_id' and budget_category = '$budget_category_id' ";
						mysqli_query($con, $sql);
						echo mysqli_error($con) ;
							
						$sql = "select * from budget_adjust where project ='$project' and budget_name='$budget_name_id' and budget_head = '$budget_category_id' ";
			//echo $sql. "<BR>";
						$q2  = mysqli_query($con, $sql);
						echo mysqli_error($con) ;
						while ($r2 = mysqli_fetch_array($q2)){
							
							$budget_amount = $r2['amount'];
							$effect_v      = $r2['effect'];
							if($effect_v == 'D'){
								$budget_amount = $budget_amount * -1;
							}	
							
							$sql = "update sma_budget set total_budget = total_budget + '$budget_amount' where project = '$project' and budget_name = '$budget_name_id' and budget_category = '$budget_category_id' ";
				//echo $sql. "<BR>";			
							mysqli_query($con, $sql);
							echo mysqli_error($con) ;
						}
						
						//exit();
						
					}
					else if($cnt==0){
						$sql="insert into budget_adjust ( fin_year, project, budget_name, budget_head, amount, effect, dated,  remarks ,adjust_type, date_uploaded, upload_flag) 
						Values( '$fin_year', '$project', '$budget_name_id', '$budget_category_id', '$budget_amount', '$effect', '$ardate', '$remarks', '$adjust_type', now(), 'Y' )";
						mysqli_query($con, $sql);
						echo mysqli_error($con) ;
	//echo $sql."<BR>";
						$sql = "insert into sma_budget ( total_budget, project, budget_name, budget_category ) 
								Values ( '$budget_amount', '$project', '$budget_name_id', '$budget_category_id' ) ";
						mysqli_query($con, $sql);
						
						echo mysqli_error($con);

					}	
					else {
						$err_msg .= "Budget Name : $budget_name , ". " Budget Head : $budget_head"."<BR>";	
					}
//echo $sql."<BR>";
//exit();
			}

		}

			if(!empty($err_msg)){
				echo "Below budget head not uploaded due to not found in budget head master...";
				echo $err_msg;
				exit();
			}
			else{
				echo "<script>alert(' Adjust Budget Uploaded...');window.location.href='budget_adjust.php?sub=list';</script>";
			}	

}
?>

