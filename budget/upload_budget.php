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
            Budget Upload
            <small>Copy</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Budget Upload</a></li>
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
            <form class="form-horizontal" action="upload_budget.php?sub=upd" method="post" enctype="multipart/form-data" >
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

<?php } 	?>


<?php if($_GET['sub'] == 'upd'){
	
		$infofile = explode(".",$_FILES['updfile']['name']);
echo strtolower(end($infofile));
		$updfile = $_FILES['updfile']['name'];
echo $updfile. ' <<<>>>' ;		
		if(strtolower(end($infofile)) != 'csv'){
		    echo "<script>alert('Please upload csv format file...')</script>";
			$bpath = $baseurl.$modulePath;
			exit();
			//echo "<script>window.location.href='$bpath';</script>";
		}
//exit();
					$csv_file=$_FILES['updfile']['tmp_name'];
					if (($handle = fopen($csv_file, "r")) !== FALSE){
						
						fgetcsv($handle);
						fgetcsv($handle);
						$prev_dated = '';
					    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					
							$num = count($data);
								for ($c=0; $c < $num; $c++){
									$col[$c] = $data[$c];
								}

								$company				= $col[0];
								$budget_name	 		= $col[1];
								$budget_head	 		= $col[2];
								$account_year     		= $col[3];
								$budget_amount  		= $col[4];
					echo $company . ' ' . $budget_name . ' ' . $budget_head. ' ' . $budget_amount. "<BR>";
					
							$sql = " select * from company where comp_name = '$company' ";
echo $sql. "<BR>";									
							$q2 	= mysqli_query($con, $sql);
							echo mysqli_error($con);
							$r2 = mysqli_fetch_array($q2);
							$project = $r2['comp_id'];
							
						//	$project = 5;
							
							$sql = " select * from sma_budget_name where name = '$budget_name' ";
					echo $sql. "<BR>";		
							$q2 	= mysqli_query($con, $sql);
							echo mysqli_error($con);
							$r2 = mysqli_fetch_array($q2);
							$budget_name_id = $r2['id'];
							
							$sql = " select * from sma_budget_subgroup where 1 and budget_name = '$budget_name_id' and budget_head  = '$budget_head' ";
					echo $sql. "<BR>";		
							$q2 	= mysqli_query($con, $sql);
							echo mysqli_error($con);
							$r2 = mysqli_fetch_array($q2);
							$budget_head_id = $r2['id'];
							//$budget_head = $r2['budget_head'];
							
							if(empty($budget_head_id)){
								$sql = "INSERT INTO sma_budget_subgroup(budget_head, budget_name, budget_code  ) values('$budget_head', '$budget_name_id', '$budget_head' ) ";
echo $sql. "<BR>";	
exit();
							//	mysqli_query($con, $sql);
								echo mysqli_error($con);
								$budget_head_id = mysqli_insert_id($con);
							}	
												
					$sql = "select * from  sma_budget	WHERE project = '$project' 
								and  budget_name = '$budget_name_id' 
								and budget_head = '$budget_head_id' 
								and account_year = '$account_year' ";
					$q2  = mysqli_query($con, $sql);
echo $sql. "<BR>";						
					$rwcnt = mysqli_affected_rows($con);
echo $rwcnt	. "<BR>";
					if($rwcnt>0){			
						$sql = "update  sma_budget set total_budget = '$budget_amount', board_approved_budget = '$budget_amount' 
							WHERE project = '$project' 
								and  budget_name = '$budget_name_id' 
								and budget_head = '$budget_head_id' 
								and account_year = '$account_year' ";
echo $sql. "<BR>";								
						$q2  = mysqli_query($con, $sql);
						echo mysqli_error($con) ."<BR>";
					}
					else {
						$sql = " INSERT INTO sma_budget (project, budget_name, budget_head, budget_code, account_year, total_budget, board_approved_budget ) 
							VALUES ('$project', '$budget_name_id', '$budget_head_id', '$budget_head', '$account_year', '$budget_amount', '$budget_amount') ";
						mysqli_query($con, $sql);
echo $sql. "<BR>";						
						echo mysqli_error($con) ;
					}	
//echo $sql. "<BR>";
//exit();

			}
						
		}
	
	exit();
	
		//		echo "<script>alert(' Budget Uploaded...');window.location.href='upload_budget.php?sub=list';</script>";
					
}
?>

