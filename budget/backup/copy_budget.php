<!DOCTYPE html>
<?php

include("../header.php");
$modulePath = "budget/budget.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">


<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

  <section class="content-header">
        <h1>
            Budget Copy 
            <small>Copy</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Budget Copy</a></li>
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
            <form class="form-horizontal" action="copy_budget.php?sub=Add" method="post">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
						<?php
										$yyear =  date("Y");
										if ($yyear=='2018'){
											$account_year = '2';
										}
										else if ($yyear=='2019'){
											$account_year = '3';
										}
									?>
									
						<div class="form-group">
							<label class="col-lg-2 control-label">Copy From Account Year</label>
							<div class="col-md-3">
								<select class="form-control" name="account_year_from" id="account_year_from" >
									<option value=""> Select </option>
									<option value="1" <?php echo ($account_year == '1')?'selected="selected"':'';?> > 2017-2018 </option>
									<option value="2" <?php echo ($account_year == '2')?'selected="selected"':'';?> > 2018-2019 </option>
									<option value="3" <?php echo ($account_year == '3')?'selected="selected"':'';?> > 2019-2020 </option>
									<option value="4" <?php echo ($account_year == '4')?'selected="selected"':'';?> > 2020-2021 </option>
									<option value="5" <?php echo ($account_year == '5')?'selected="selected"':'';?> > 2021-2022 </option>
									<option value="6" <?php echo ($account_year == '6')?'selected="selected"':'';?> > 2022-2023 </option>
									<option value="7" <?php echo ($account_year == '7')?'selected="selected"':'';?> > 2023-2024 </option>
									<option value="8" <?php echo ($account_year == '8')?'selected="selected"':'';?> > 2024-2025 </option>
									<option value="9" <?php echo ($account_year == '9')?'selected="selected"':'';?> > 2025-2026 </option>
									<option value="10" <?php echo ($account_year == '10')?'selected="selected"':'';?> > 2026-2027 </option>									
								</select>	
							</div>
							
						<!--	<label class="col-lg-2 control-label">To Account Year</label>
							<div class="col-md-3">
								<select class="form-control" name="account_year_to" id="account_year_to" >
									<option value=""> Select </option>
									<option value="1" <?php echo ($account_year == '1')?'selected="selected"':'';?> > 2017-2018 </option>
									<option value="2" <?php echo ($account_year == '2')?'selected="selected"':'';?> > 2018-2019 </option>
									<option value="3" <?php echo ($account_year == '3')?'selected="selected"':'';?> > 2019-2020 </option>
									<option value="4" <?php echo ($account_year == '4')?'selected="selected"':'';?> > 2020-2021 </option>
									<option value="5" <?php echo ($account_year == '5')?'selected="selected"':'';?> > 2021-2022 </option>
									<option value="6" <?php echo ($account_year == '6')?'selected="selected"':'';?> > 2022-2023 </option>
									<option value="7" <?php echo ($account_year == '7')?'selected="selected"':'';?> > 2023-2024 </option>
									<option value="8" <?php echo ($account_year == '8')?'selected="selected"':'';?> > 2024-2025 </option>
									<option value="9" <?php echo ($account_year == '9')?'selected="selected"':'';?> > 2025-2026 </option>
									<option value="10" <?php echo ($account_year == '10')?'selected="selected"':'';?> > 2026-2027 </option>									
								</select>	
							</div>-->
							
						</div>
						
						<div class="form-group">
							
							<label for="project" class="control-label col-sm-2">Company</label>
							<div class="col-sm-4">
									<select class="form-control select2" name="project" id="project" onchange="getlocation(this.value)" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['project'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
							</div>
							
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Budget Name</label>
							<div class="col-md-4">
								<select class="form-control" name="budget_name" id="budget_name" >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_budget_name order by name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['budget_name'] == $r2['id'])?'selected="selected"':'';?>> <?php echo $r2['name'];?></option>
										<?php } ?>
								</select>
							</div>
						</div>
                        
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
<?php } 	?>


<?php if($_GET['sub'] == 'Add'){
	
		$account_year_from 	= $_POST['account_year_from'];
		$account_year_to	= $_POST['account_year_to'];
		$project	  		= $_POST['project'];
		$budget_name  		= $_POST['budget_name'];
		
		
		if($account_year_from == $account_year_to){
			echo "<script>alert('From and To Year should not be same');window.location.href='copy_budget.php?sub=list';</script>";
		}
		else {
			$sql = "delete from sma_budget where  project = '$project' and budget_name = '$budget_name' ";
			$q2  = mysqli_query($con, $sql);
			

			$sql = "insert into sma_budget (account_year, project, budget_name, budget_category, total_budget) SELECT '$account_year_to', project, budget_name, budget_category, total_budget FROM `sma_budget` where project = '$project' and budget_name = '$budget_name' ";
			$q2  = mysqli_query($con, $sql);

			echo "<script>alert('Net Budget copied...');window.location.href='budget.php?sub=list';</script>";
		}
}
?>

<?php 	
		include("../footer.php");	
?>

</body>
</html>
