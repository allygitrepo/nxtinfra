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
        Cost Center Master
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Cost Center Master</li>
      </ol>
    </section>

<div class="col-md-12">
	
		<div class="box box-info">
            <div class="box-header with-border">
             <!-- <h3 class="box-title">Cost Center Master List</h3>-->
			  <?php
			  if ($_POST['comp_id']  ){
					$_SESSION['comp_id'] = $_POST['comp_id'];
					
				}
				
				if ( $_SESSION['comp_id'] ){
					$comp_id 		= $_SESSION['comp_id'];
					
					$_SESSION['reset']='';
				}
				
				if (!empty($_GET['reset']) || !empty($_SESSION['reset'])){
					$_SESSION['comp_id'] = '';
					
					$comp_id = $_SESSION['comp_id'];
					
				}
				
			?>
				<form class="form-horizontal" action="budget.php?sub=list" method="post">
                      
						<div class="form-group">
								
								<label class="col-lg-1 control-label">Company</label>
								<div class="col-md-5">
									<select class="form-control select2" name="comp_id" id="comp_id" >
										<option value=""> Select </option>
											<?php $sql = "select * from company order by comp_name ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['comp_id'];?>" <?php echo ($comp_id == $r2['comp_id'])?'selected="selected"':'';?>  ><?php echo $r2['comp_name'];?></option>
											<?php } ?>
									</select>
										
								</div>
								
						<div class="col-xs-2">
                            
							<input class="btn btn-success" type="submit" value="Search" name="Save">&nbsp;&nbsp;&nbsp;
							<a href="budget.php?sub=list&reset=1" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
						</div>
							
				</form>
				
            <div class="pull-right">
				<span class="sepV_c marginRight">
					<a href="budget_export_func.php?sub=pdf" class="btn btn-primary">Report</a>&nbsp;&nbsp;&nbsp;&nbsp;
					
<?php // $i = $menu_id[10]; echo $menuonly[$i]; //if ( $menuonly[$i] =='Y' ){ ?>
				
					<a href="budget.php?sub=add" name="btnAdd" class="btn btn-primary"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Add </a>
					&nbsp;&nbsp;&nbsp;&nbsp;
			    </span>
			</div>
			</div>
		</div>	
    <div class="box">
    <table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>Cost Center Group</th>
			<th>Cost Center Name</th>
			<th>Cost Center Code</th>
			<th>Company</th>
			<th  style="text-align:right;">Opening Budget</th>
			<!--<th  style="text-align:right;">Blocked Budget</th>
			<th  style="text-align:right;">Used Budget</th>-->
			<th style="text-align:right;">Balance Budget</th>
			<th style="text-align:right;">Action</th>
			
		</tr>
	</thead>
<tbody>
<?php
	$modulePath1 = "budget/";
	
	
	
	$sql="SELECT * from sma_budget where project in ($comid)";
	
	if(!empty($comp_id)){
		$sql .= " and project = '$comp_id' ";
	}
	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
	
	$project = $row['project'];
	$sql = "SELECT * from company where comp_id = '$project' ";
	$res = mysqli_query($con, $sql);
	//echo mysqli_error($con);
	$r2 = mysqli_fetch_array($res);
	$comp_name 	= $r2['comp_name'];
	$project 	= $r2['comp_code'];

	$budget_code = $row['budget_code'];

	$budget_head = $row['budget_head'];
	
	$budget_name = $row['budget_name'];
	$sql 	= "select * from sma_budget_name where id = '$budget_name' ";
	$q2 	= mysqli_query($con, $sql);
	$r2 	= mysqli_fetch_array($q2);
	$budget_name = $r2['name'];
											
	$total_budget 	= $row['total_budget'];
	$used_budget 	= $row['used_budget'];
	$blocked_budget	= $row['blocked_budget'];
	$adjustment_budget	= $row['adjustment_budget'];
	$locked 		= $row['locked'];
	
	$balance_budget = ($total_budget + $adjustment_budget) - ( $blocked_budget + $used_budget); 

	$baseurl1 = $baseurl.$modulePath1.'budget.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "budget.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="15%"><?php echo $budget_name;?></td>
		<td width="25%" style="text-align:left;"><?php echo $budget_head;?></td>
		<td width="10%"><?php echo $budget_code;?></td>
		<td width="10%"><?php echo $project;?></td>
		
		<td width="10%" style="text-align:right;"><?php echo moneyFormatIndiaa($total_budget,2);?></td>
		<!--<td width="10%" style="text-align:right;"><?php echo moneyFormatIndiaa($used_budget,2);?></td>
		<td width="10%" style="text-align:right;"><?php echo moneyFormatIndiaa($blocked_budget,2);?></td>-->
		<td width="10%" style="text-align:right;"><?php echo moneyFormatIndiaa($balance_budget,2);?></td>
		
		<td width="5%" style="text-align:right;">
		<a href="budget.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
		</td>
    </tr>
	</a>
	
	<?php }?>
</tbody> 
</table>
	</div>
    </div>
</div>	

    <?php }?>


<?php  
	if($_GET['sub'] == 'delete'){ 
        $id = $_GET['id'];
		
		
		$sql="delete from sma_budget where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="budget.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){
?>

<?php
	if(isset($_POST['Save'])){

			$project			= $_POST['project'];
			$budget_name		= $_POST['budget_name'];
			$budget_head		= $_POST['budget_head'];
			$budget_code		= $_POST['budget_code'];
			$total_budget		= $_POST['total_budget'];
			$used_budget		= $_POST['used_budget'];
			$balance_budget		= $_POST['balance_budget'];
			$budget_status		= $_POST['budget_status'];
			//$adjustment_budget	= $_POST['adjustment_budget'];
			
			$april				= $_POST['april'];
			$may				= $_POST['may'];
			$june				= $_POST['june'];
			$july				= $_POST['july'];
			$august				= $_POST['august'];
			$september			= $_POST['september'];
			$october			= $_POST['october'];
			$november			= $_POST['november'];
			$december			= $_POST['december'];
			$january			= $_POST['january'];
			$february			= $_POST['february'];
			$march				= $_POST['march'];
			
			$account_year		= $_POST['account_year'];
			
  			$sql="insert into sma_budget ( project, budget_name, budget_head, budget_code, total_budget, used_budget, balance_budget,  budget_status, april, may, june, july, august, september, october, november, december, january, february, march , account_year ) 
			Values('$project', '$budget_name', '$budget_head', '$budget_code', '$total_budget', 
			'$used_budget', '$balance_budget', '$budget_status', '$april', '$may', '$june', '$july', 
			'$august', '$september', '$october', '$november', '$december', '$january', '$february', 
			'$march', '$account_year' )";
					
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; sleep(5);}
			
			//echo "Budget successful added";
			echo '<script>window.location.href="budget.php?sub=list";</script>';
			
		}
	

?>
   <section class="content-header">
        <h1>
            Cost Center Master
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Cost Center Master</a></li>
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
            <form class="form-horizontal" action="budget.php?sub=add" method="post">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
						
						<div class="form-group">
							
							<label for="project" class="control-label col-sm-2"> Company Name</label>
							<div class="col-sm-4">
									<select class="form-control select2" name="project" id="project" required >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company where 1 order by comp_name "; //comp_id in ($comid)
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
							</div>
						
							<label class="col-lg-2 control-label">Cost Center Group</label>
							<div class="col-md-4">
								<select class="form-control" name="budget_name" id="budget_name" required >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_budget_name order by name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['budget_name'] == $r2['id'])?'selected="selected"':'';?>> <?php echo $r2['name'];?></option>
										<?php } ?>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Cost Center Sub Group</label>
							<div class="col-md-4">
								<input type="text" class="form-control" id="budget_head" name="budget_head"  placeholder="" value="<?php echo $row['budget_head'];?>" >
							</div>
						
							<label class="col-lg-2 control-label">Cost Center Code (For Tally Posting)</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="budget_code" name="budget_code"  placeholder="" value="<?php echo $row['budget_code'];?>" >
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Opening Budget</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="total_budget" name="total_budget" style="text-align:right;" placeholder="" value="<?php echo $row['total_budget'];?>" onkeyup="getbalbugdget123()">
							</div>
							
							<label class="col-lg-2 control-label">Fin.Year</label>
							<div class="col-md-2">
								<select class="form-control" name="account_year" id="account_year" required >
									<option value=""> Select </option>
									<option value="2122">2021-2022</option>
									<option value="2223">2022-2023</option>
									<option value="2324">2023-2024</option>
									<option value="2425">2024-2025</option>
									<option value="2526">2025-2026</option>
										
								</select>
							</div>
							
						</div>
						
						<div class="form-group">
							<div class="col-md-2">
								<label class=" control-label">April</label>
								<input type="text" class="form-control" id="april" name="april" style="text-align:right;" placeholder="" value="<?php echo $row['april'];?>" >
							</div>
							<div class="col-md-2">
								<label class=" control-label">May</label>
								<input type="text" class="form-control" id="may" name="may" style="text-align:right;" placeholder="" value="<?php echo $row['may'];?>" >
							</div>
							<div class="col-md-2">
								<label class=" control-label">June</label>
								<input type="text" class="form-control" id="june" name="june" style="text-align:right;" placeholder="" value="<?php echo $row['june'];?>" >
							</div>
							<div class="col-md-2">
								<label class=" control-label">July</label>
								<input type="text" class="form-control" id="july" name="july" style="text-align:right;" placeholder="" value="<?php echo $row['july'];?>" >
							</div>
							<div class="col-md-2">
								<label class=" control-label">August</label>
								<input type="text" class="form-control" id="august" name="august" style="text-align:right;" placeholder="" value="<?php echo $row['august'];?>" >
							</div>
							<div class="col-md-2">
								<label class=" control-label">September</label>
								<input type="text" class="form-control" id="september" name="september" style="text-align:right;" placeholder="" value="<?php echo $row['september'];?>" >
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-2">
								<label class=" control-label">October</label>
								<input type="text" class="form-control" id="october" name="october" style="text-align:right;" placeholder="" value="<?php echo $row['october'];?>" >
							</div>
							<div class="col-md-2">
								<label class=" control-label">November</label>
								<input type="text" class="form-control" id="november" name="november" style="text-align:right;" placeholder="" value="<?php echo $row['november'];?>" >
							</div>
							<div class="col-md-2">
								<label class=" control-label">December</label>
								<input type="text" class="form-control" id="december" name="december" style="text-align:right;" placeholder="" value="<?php echo $row['december'];?>" >
							</div>
							<div class="col-md-2">
								<label class=" control-label">January</label>
								<input type="text" class="form-control" id="january" name="january" style="text-align:right;" placeholder="" value="<?php echo $row['january'];?>" >
							</div>
							<div class="col-md-2">
								<label class=" control-label">February</label>
								<input type="text" class="form-control" id="february" name="february" style="text-align:right;" placeholder="" value="<?php echo $row['february'];?>" >
							</div>
							<div class="col-md-2">
								<label class=" control-label">March</label>
								<input type="text" class="form-control" id="march" name="march" style="text-align:right;" placeholder="" value="<?php echo $row['march'];?>" >
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Blocked Budget</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="blocked_budget" name="blocked_budget" style="text-align:right;" <?php echo $readonly;?> value="<?php echo $row['blocked_budget'];?>" readonly  >
							</div>
						
							<label class="col-lg-2 control-label">Used Budget</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="used_budget" name="used_budget" style="text-align:right;" placeholder="" value="<?php echo $row['used_budget'];?>" readonly onkeyup="getbalbugdget()">
							</div>
						
							<label class="col-lg-2 control-label">Adjustment Budget</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="adjustment_budget" name="adjustment_budget" style="text-align:right;" readonly placeholder="" value="<?php echo $row['adjustment_budget'];?>" >
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Balance Budget</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="balance_budget" name="balance_budget" style="text-align:right;" readonly value="<?php echo $row['balance_budget'];?>" onkeyup="getbalbugdget()">
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-1">
								<label class="control-label">&nbsp;</label>
							</div>
							
							<div class="col-md-1">
								<label class=" control-label"> Status </label>
							</div>	
							<div class="col-md-2">
								<input type="radio" id="budget_status" name="budget_status" checked value="D" > Active &nbsp;
								<input type="radio" id="budget_status" name="budget_status" value="F" > Inactive
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
								<input class="btn btn-primary" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
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


<?php if($_GET['sub'] == 'edit'){
?>

<?php
	if(isset($_POST['Save'])){

			$id					= $_POST['id']; 			
			$project			= $_POST['project'];
			$budget_name		= $_POST['budget_name'];
			$budget_head		= $_POST['budget_head'];
			$budget_group	    = $_POST['budget_group'];
			$total_budget		= $_POST['total_budget'];
			$budget_status		= $_POST['budget_status'];
			$budget_code		= $_POST['budget_code'];
			//$adjustment_budget	= $_POST['adjustment_budget'];
			$april				= $_POST['april'];
			$may				= $_POST['may'];
			$june				= $_POST['june'];
			$july				= $_POST['july'];
			$august				= $_POST['august'];
			$september			= $_POST['september'];
			$october			= $_POST['october'];
			$november			= $_POST['november'];
			$december			= $_POST['december'];
			$january			= $_POST['january'];
			$february			= $_POST['february'];
			$march				= $_POST['march'];
			
			$account_year		= $_POST['account_year'];
			
  			$sql="update sma_budget set project	= '$project',
						budget_name			= '$budget_name',
						budget_head			= '$budget_head',
						total_budget		= '$total_budget',
						budget_status		= '$budget_status',
						budget_code			= '$budget_code',
						april				= '$april',
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
						account_year		= '$account_year'
				where id = '$id'";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			echo '<script>window.location.href="budget.php?sub=list";</script>';
		}
		
		$id = $_GET['id'];
		$sql="Select * from sma_budget where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	
		$locked	 = $row['locked'];
//echo $sql;		
		$readonly='';
		if($locked=='Y'){
			$readonly = "READONLY";
		}
?>
	
    <!-- Content Header (Page header) -->
    <div class="col-md-12">
    
   <section class="content-header">
        <h1>
            Cost Center Master
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Cost Center Master</a></li>
            
        </ol>
    </section>
	
        <!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box">
            <!-- form start -->
            <form class="form-horizontal" action="budget.php?sub=edit" method="post">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
						<div class="form-group">
							
								<label for="project" class="control-label col-sm-2">Company Name</label>
								<div class="col-sm-4">
									<select class="form-control select2" name="project" id="project"  >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company where 1 order by comp_name ";//comp_id in ($comid)
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['project'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
							</div>
							
							<label class="col-lg-2 control-label">Cost Center Group</label>
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
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Cost Center Sub Group</label>
							<div class="col-md-4">
								<input type="text" class="form-control" id="budget_head" name="budget_head"  placeholder="" value="<?php echo $row['budget_head'];?>" >
							</div>
						
							<label class="col-lg-2 control-label">Cost Center Code (For Tally Posting)</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="budget_code" name="budget_code"  placeholder="" value="<?php echo $row['budget_code'];?>" >
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Opening Budget</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="total_budget" name="total_budget" style="text-align:right;"  value="<?php echo $row['total_budget'];?>" onkeyup="getbalbugdget()" >
							</div>
							
							<label class="col-lg-2 control-label">Fin.Year</label>
							<div class="col-md-2">
								<select class="form-control" name="account_year" id="account_year" required >
									<option value=""> Select </option>
									<option value="2122" <?php echo ($row['account_year']=='2122')?'selected="selected"':'';?> >2021-2022</option>
									<option value="2223" <?php echo ($row['account_year']=='2223')?'selected="selected"':'';?>>2022-2023</option>
									<option value="2324" <?php echo ($row['account_year']=='2324')?'selected="selected"':'';?>>2023-2024</option>
									<option value="2425" <?php echo ($row['account_year']=='2425')?'selected="selected"':'';?>>2024-2025</option>
									<option value="2526" <?php echo ($row['account_year']=='2526')?'selected="selected"':'';?>>2025-2026</option>
										
								</select>
							</div>
							
							<?php
							$total_budget = $row['total_budget'];
							$month_total = $row['april'] + $row['may'] + $row['june'] + $row['july'] + 
							$row['august'] + $row['september'] + $row['october'] + $row['november'] + 
							$row['december'] + $row['january'] + $row['february'] + $row['march'];
							if($total_budget!=$month_total){ ?>
								<label class="control-label" style="color:red;" > Opening budget not matching with monthly total...</label>
							<?php	}	
							?>
						</div>
					
						
						<div class="form-group">
							<div class="col-md-2">
								<label class=" control-label">April</label>
								<input type="text" class="form-control" id="april" name="april" style="text-align:right;" placeholder="" value="<?php echo $row['april'];?>" >
							</div>
							<div class="col-md-2">
								<label class=" control-label">May</label>
								<input type="text" class="form-control" id="may" name="may" style="text-align:right;" placeholder="" value="<?php echo $row['may'];?>" >
							</div>
							<div class="col-md-2">
								<label class=" control-label">June</label>
								<input type="text" class="form-control" id="june" name="june" style="text-align:right;" placeholder="" value="<?php echo $row['june'];?>" >
							</div>
							<div class="col-md-2">
								<label class=" control-label">July</label>
								<input type="text" class="form-control" id="july" name="july" style="text-align:right;" placeholder="" value="<?php echo $row['july'];?>" >
							</div>
							<div class="col-md-2">
								<label class=" control-label">August</label>
								<input type="text" class="form-control" id="august" name="august" style="text-align:right;" placeholder="" value="<?php echo $row['august'];?>" >
							</div>
							<div class="col-md-2">
								<label class=" control-label">September</label>
								<input type="text" class="form-control" id="september" name="september" style="text-align:right;" placeholder="" value="<?php echo $row['september'];?>" >
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-2">
								<label class=" control-label">October</label>
								<input type="text" class="form-control" id="october" name="october" style="text-align:right;" placeholder="" value="<?php echo $row['october'];?>" >
							</div>
							<div class="col-md-2">
								<label class=" control-label">November</label>
								<input type="text" class="form-control" id="november" name="november" style="text-align:right;" placeholder="" value="<?php echo $row['november'];?>" >
							</div>
							<div class="col-md-2">
								<label class=" control-label">December</label>
								<input type="text" class="form-control" id="december" name="december" style="text-align:right;" placeholder="" value="<?php echo $row['december'];?>" >
							</div>
							<div class="col-md-2">
								<label class=" control-label">January</label>
								<input type="text" class="form-control" id="january" name="january" style="text-align:right;" placeholder="" value="<?php echo $row['january'];?>" >
							</div>
							<div class="col-md-2">
								<label class=" control-label">February</label>
								<input type="text" class="form-control" id="february" name="february" style="text-align:right;" placeholder="" value="<?php echo $row['february'];?>" >
							</div>
							<div class="col-md-2">
								<label class=" control-label">March</label>
								<input type="text" class="form-control" id="march" name="march" style="text-align:right;" placeholder="" value="<?php echo $row['march'];?>" >
							</div>
						</div>
						
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Blocked Budget</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="blocked_budget" name="blocked_budget" style="text-align:right;" <?php echo $readonly;?> value="<?php echo number_format($row['blocked_budget'],2);?>" readonly  >
							</div>
						
							<label class="col-lg-2 control-label">Used Budget</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="used_budget" name="used_budget" style="text-align:right;" <?php echo $readonly;?> value="<?php echo number_format($row['used_budget'],2);?>" readonly  >
							</div>
						
						
						<?php
							$total_budget 	= $row['total_budget'];
							$used_budget	= $row['used_budget'];
							$blocked_budget	= $row['blocked_budget'];
							$adjustment_budget	= $row['adjustment_budget'];
							$balance_budget = ($total_budget + $adjustment_budget) - ($blocked_budget + $used_budget) ;
						?>
						
						
							<label class="col-lg-2 control-label">Adjustment to Budget</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="adjustment_budget" name="adjustment_budget" style="text-align:right;" readonly placeholder="" value="<?php echo number_format($row['adjustment_budget'],2);?>" >
							</div>
						</div>
	
						<div class="form-group">
							<label class="col-lg-2 control-label">Balance Budget</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="balance_budget" name="balance_budget" style="text-align:right;" readonly value="<?php echo number_format($balance_budget,2);?>" onkeyup="getbalbugdget()" > 
							</div>
						</div>
						<?php 
								$locked = $row['locked'];
								if($locked=='Y'){
									$selected_yes = 'checked';
								}
								else if($locked==''){
									$selected_no = 'checked';
								}
						?>
						<div class="form-group">
							<div class="col-md-1">
								<label class="control-label">&nbsp;</label>
							</div>

							<?php 
								$budget_status = $row['budget_status'];
								if($budget_status=='D'){
									$selected_draft = 'checked';
								}
								else if($budget_status=='F'){
									$selected_final = 'checked';
								}
							?>
							
							<div class="col-md-1">
								<label class=" control-label"> Status </label>
							</div>	
							<div class="col-md-2">
								<input type="radio" id="budget_status" <?php echo $selected_draft; ?> name="budget_status" value="D" > Active &nbsp;
								<input type="radio" id="budget_status" <?php echo $selected_final; ?> name="budget_status" value="F" > Inactive
							</div>
							
						</div>
						
						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; 
								
								$sql =" select count(*) as cnt from sma_product a, sma_budget b where b.id='$did' and a.budget_code = b.budget_code ";
								$query1 = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r2 = mysqli_fetch_array($query1);
								$cnt = $r2['cnt'];
								
								?>
							<?php if ( $cnt==0 ){ ?>	
								<a href="<?php echo $baseurl."budget/budget.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a>
							<?php } ?>	
							</div>
							<?php $baseurl1 = $baseurl.$modulePath;?>
							<div class="col-sm-6 text-right">
								<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Cancel</a>
								<span>&nbsp;&nbsp;</span>
							<?php if ($role!='Checker'){ ?>
								<input class="btn btn-primary" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
							<?php } ?>	
							</div>
						</div>
                        
                    </fieldset>
				</div>	
            </form>
          </div>
          
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
      
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


	function getlocation(id){
		
        var sub    = 'sub1';
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub1:sub},function(result){
		      $('#getlocation').html(result);
		});

	}

	function getbudget(id){
		
        var sub    = 'sub2';
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub2:sub},function(result){
		      $('#getbudget').html(result);
		});

	}

	function getbalbugdget(){
		var total_budget =  document.getElementById('total_budget').value;
		//var used_budget =  document.getElementById('used_budget').value;
		
		var balance_budget = total_budget - used_budget;
		
		//$('#balance_budget').attr('readonly', true);
		document.getElementById('balance_budget').value=balance_budget;
        
		//alert(balance_budget);
		if (balance_budget < 0){
			alert("Used Budget should be less then total budget...");
			//var used_budget = 0;
			document.getElementById('used_budget').value=0;
			
		}
		
	}	
</script>

</body>
</html>
