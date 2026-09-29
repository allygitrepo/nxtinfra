<?php
include("../header.php");
$modulePath = "budget/budget.php?sub=list";

	$help_code = $modulePath;
	include "../help_code.php";

$pgname = $help_code;
include("../viewonly.php");

?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">


<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Budget Balances
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Budget Balances</li>
      </ol>
    </section>

<div class="col-md-12">
	
		<div class="box box-info">
            <div class="box-header with-border">
             <!-- <h3 class="box-title">Budget Balance List</h3>-->
			  <?php
			  if ($_POST['comp_id'] || $_POST['account_year'] || $_POST['budget_name'] ){
					$_SESSION['comp_id'] 		= $_POST['comp_id'];
					$_SESSION['account_year'] 	= $_POST['account_year'];
					$_SESSION['budget_name'] 	= $_POST['budget_name'];
				}
				
				if ( $_SESSION['comp_id'] ||  $_SESSION['account_year'] ||  $_SESSION['budget_name'] ){
					$comp_id 			= $_SESSION['comp_id'];
					$account_year 		= $_SESSION['account_year'];
					$budget_name 		= $_SESSION['budget_name'];
					
					$_SESSION['reset']='';
				}
				
				if (!empty($_GET['reset']) || !empty($_SESSION['reset'])){
					$_SESSION['comp_id'] = '';
					$_SESSION['budget_name'] = '';
					
					$comp_id 		= $_SESSION['comp_id'];
					$account_year 	= $_SESSION['account_year'];
					$budget_name 	= $_SESSION['budget_name'];
					
				}
				
				if(empty($account_year)){
					$sql = "select * from sma_financial_year where status = 'Y' order by short_fy_code ";
					$q2 	= mysqli_query($con, $sql);
					$r2 = mysqli_fetch_array($q2);
					$account_year = $r2['short_fy_code'];
				}	
				
			?>
				<form class="form-horizontal" action="budget.php?sub=list" method="post">
                      
						<div class="form-group">
								
								<div class="col-md-2">
									<label class="control-label">Fin.Year</label>
									<select class="form-control" name="account_year" id="account_year" required >
										<option value=""> Select </option>
											<?php $sql = "select * from sma_financial_year order by short_fy_code desc";
												$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['short_fy_code'];?>" <?php echo ($account_year == $r2['short_fy_code'])?'selected="selected"':'';?>  ><?php echo $r2['short_fy_code'];?></option>
										<?php } ?>
									</select>
								</div>
							
								<div class="col-md-4">
									<label class="control-label">Company</label>
									<select class="form-control select2" name="comp_id" id="comp_id" >
										<option value=""> Select </option>
											<?php $sql = "select * from company order by comp_name ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['comp_id'];?>" <?php echo ($comp_id == $r2['comp_id'])?'selected="selected"':'';?>  ><?php echo $r2['comp_name'];?></option>
											<?php } ?>
									</select>	
								</div>
								
								
								<div class="col-md-3">
									<label class="control-label">Budget Group</label>
									<select class="form-control" name="budget_name" id="budget_name"  >
										<option value=""> Select </option>
											<?php $sql = "select * from sma_budget_name order by name ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['id'];?>" <?php echo ($budget_name == $r2['id'])?'selected="selected"':'';?>> <?php echo $r2['name'];?></option>
											<?php } ?>
									</select>
								</div>
							
						<div class="col-xs-2">
                            
							<input class="btn btn-success" type="submit" value="Search" name="Save">&nbsp;&nbsp;&nbsp;
							<a href="budget.php?sub=list&reset=1" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
						</div>
					</div>		
				</form>
				
				<div class="pull-right">
				
					<span class="sepV_c marginRight">
						<a href="budget_export_func.php?sub=pdf" class="btn btn-primary">Export</a>&nbsp;&nbsp;&nbsp;&nbsp;
						
						<a href="budget_report.php?sub=list" target="_blank" class="btn btn-primary">Report</a>&nbsp;&nbsp;&nbsp;&nbsp;
						
					<?php if ( $addonly=='Y'){ ?>
						<a href="budget.php?sub=add" name="btnAdd" class="btn btn-primary"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Add </a>
						&nbsp;&nbsp;&nbsp;&nbsp;
					<?php } ?>	
					</span>
				</div>
				
			</div>
		</div>	
    <div class="box">
    <table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>Budget Group</th>
			<th>Budget Name</th>
			<th>Fin.Year</th>
			<th>Company</th>
			
			<th  style="text-align:right;">Opening Budget</th>
			<th  style="text-align:right;">Adjustment Budget</th>
			<th  style="text-align:right;">Total Budget<br> (Opening + Adjustment)</th>
			<th  style="text-align:right;">Blocked Budget</th>
			<th  style="text-align:right;">Used Budget</th>
			<th style="text-align:right;">Balance Budget</th>
			<th style="text-align:right;">Action</th>
			
		</tr>
	</thead>
<tbody>
<?php
	$modulePath1 = "budget/";
	
	$sql="SELECT * from sma_budget where project in ($comid)";
	
	if ($viewonly =='Y' ){
		$sql="SELECT * from sma_budget where project in ($comid)";
	}	
	
	if(!empty($comp_id)){
		$sql .= " and project = '$comp_id' ";
	}
	
	if(!empty($account_year)){
		$sql .= " and account_year = '$account_year' ";
	}
	
	if(!empty($budget_name)){
		$sql .= " and budget_name = '$budget_name' ";
	}
	
//echo $sql."<BR>";
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

	//$board_approved_budget = $row['board_approved_budget'];
    $board_approved_budget = '';
	$budget_head = $row['budget_head'];
	$budget_head_id = $row['budget_head'];
	
	$budget_name = $row['budget_name'];
	$sql 	= "select * from sma_budget_name where id = '$budget_name' ";
	$q2 	= mysqli_query($con, $sql);
	$r2 	= mysqli_fetch_array($q2);
	$budget_name = $r2['name'];
	$admin_flag 	= $r2['admin_flag'];
	
	$sql 	= "select * from sma_budget_subgroup where id = '$budget_head_id' ";
	$q2 	= mysqli_query($con, $sql);
	$r2 	= mysqli_fetch_array($q2);
	$budget_head 	= $r2['budget_head'];
	$admin_flag 	= $r2['admin_flag'];
	
	$styl = '';
	if($admin_flag=='Y' ){
		if( $user=='Admin' ){
			$styl = ' style = "color:red;" ';
		}
		else{
			$styl = ' style = "color:red;" ';
			//continue;	
		}
	}
											
	
	$budget_id = $row['id'];
	
	$open_budget 	= $row['total_budget'];
	$used_budget 	= $row['used_budget'];
	$blocked_budget	= $row['blocked_budget'];
	$adjustment_budget	= $row['adjustment_budget'];
	$locked 		= $row['locked'];
	
	$total_budget		= $open_budget + $adjustment_budget;
	
	/* if($budget_id==291){
		echo $open_budget.'  + '.$adjustment_budget .' - '. $blocked_budget . '+ '. $used_budget. "<BR>";
	} */
	$balance_budget = ($open_budget + $adjustment_budget) - ( $blocked_budget + $used_budget); 

	//$total_budget 	= $total_budget + $adjustment_budget;
	
	if($balance_budget<0){
		$balance_budget = number_format($balance_budget,2);
	}
	else {
		$balance_budget = moneyFormatIndiaa(round($balance_budget,2));
	}
	
	if($blocked_budget<0){
		$blocked_budget = number_format($blocked_budget,2);
	}
	else {
		$blocked_budget = moneyFormatIndiaa($blocked_budget,2);
	}
	
	if($used_budget<0){
		$used_budget = number_format($used_budget,2);
	}
	else {
		//$used_budget = moneyFormatIndiaa($used_budget,2);
		$used_budget = number_format($used_budget,2);
	}
	
	if($total_budget<0){
		$total_budget = number_format($total_budget,2);
	}
	else {
		$total_budget = moneyFormatIndiaa(round($total_budget,2));
	}
	
	$baseurl1 = $baseurl.$modulePath1.'budget.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "budget.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'"  >
		<td width="15%" <?= $styl; ?>><?php echo $budget_name;?></td>
		<td width="25%" <?= $styl; ?> ><?php echo $budget_head;?></td>
		<td width="08%" <?= $styl; ?>><?php echo $account_year;?></td>
		<td width="08%" ><?php echo $project;?></td>
		
		<td width="10%" style="text-align:right;"><?php echo moneyFormatIndiaa($open_budget,2);?></td>
		<td width="10%" style="text-align:right;"><?php echo $row['adjustment_budget'];?></td>
		<td width="10%" style="text-align:right;"><?php echo ($total_budget);?></td>
		
		<td width="10%" style="text-align:right;"><?php echo ($blocked_budget);?></td>
		<td width="10%" style="text-align:right;"><?php echo ($used_budget);?></td>
		
		<td width="10%" style="text-align:right;"><?php echo ($balance_budget);?></td>
		
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
// 			$board_approved_budget	= $_POST['board_approved_budget'];
			$board_approved_budget = '0';
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
			$remarks			= $_POST['remarks'];
			
  			$sql="insert into sma_budget ( project, budget_name, budget_head, budget_code, total_budget, used_budget, balance_budget, april, may, june, july, august, september, 
  			        october, november, december, january, february, march , account_year, board_approved_budget, remarks, budget_status ) 
			Values('$project', '$budget_name', '$budget_head', '$budget_code', '$total_budget', '$used_budget', '$balance_budget', '$april', '$may', '$june', '$july', 
			'$august', '$september', '$october', '$november', '$december', '$january', '$february', '$march', '$account_year', '$board_approved_budget', '$remarks', 'D' )";
					
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; sleep(5);}
//exit('Exit Here ....');

			//echo "Budget successful added";
			echo '<script>window.location.href="budget.php?sub=list";</script>';
			
		}
	

?>
   <section class="content-header">
        <h1>
            Budget Balance
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Budget Balance</a></li>
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
						
							<label class="col-lg-2 control-label">Budget Group</label>
							<div class="col-md-4">
								<select class="form-control" name="budget_name" id="budget_name" required onchange="getccsubgroup(this.value);" >
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
							<label class="col-lg-2 control-label">Budget Sub Group</label>
							<div class="col-md-4">
							<span id="getccsubgroup">
								<input type="text" class="form-control" id="budget_head" name="budget_head"  placeholder="" value="<?php echo $row['budget_head'];?>" >
							</span>	
							</div>
						
							<label class="col-lg-2 control-label">Posting A/c Name</label>
							<div class="col-md-3">
								<span id="getcccode">
								<input type="text" class="form-control" id="budget_code" name="budget_code"  placeholder="" value="<?php echo $row['budget_code'];?>" >
								</span>	
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Opening Budget</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="total_budget" name="total_budget" style="text-align:right;" placeholder="" value="<?php echo $row['total_budget'];?>" onkeyup="getbalbugdget123()">
							</div>
							
							
							<!--<label class="col-lg-2 control-label">Board Approved Budget</label>-->
							<!--<div class="col-md-2">-->
								<!--<input type="text" class="form-control" id="board_approved_budget" name="board_approved_budget" style="text-align:right;" placeholder="" value="<?php echo $row['board_approved_budget'];?>" >-->
							<!--</div>-->
							
							<label class="col-lg-2 control-label">Fin.Year</label>
							<div class="col-md-2">
								<select class="form-control" name="account_year" id="account_year" required >
									<option value=""> Select </option>
    							<?php		
									$sql="SELECT * FROM sma_financial_year order by id desc ";
									$q2 = mysqli_query($con, $sql);
									echo mysqli_error($con);
									while($r2 = mysqli_fetch_array($q2)){
									?>
									<option value="<?php echo $r2['short_fy_code']?>" <?php echo ($row['account_year'] == $r2['short_fy_code'] )?'selected="selected"':'';?> ><?php echo $r2['short_fy_code'] ?></option>
								<?php } ?>
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
							
							<label class="col-lg-1 control-label">Remarks</label>
							<div class="col-md-7">
								<input type="text" class="form-control" id="remarks" name="remarks" value="<?= $row['remarks'];?>" > 
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
			$adjustment_budget	= $_POST['adjustment_budget'];
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
			
			$show_dashboard     = $_POST['show_dashboard'];
			
			$account_year		= $_POST['account_year'];
			$board_approved_budget 	= $_POST['board_approved_budget'];
			$remarks			= $_POST['remarks'];
			
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
						account_year		= '$account_year',
						board_approved_budget = '$board_approved_budget',
						remarks				= '$remarks',
						show_dashboard      = '$show_dashboard'
				where id = '$id'";

			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			
			// add attachments
			// file upload
			$doc_invoice_no		= $_POST["doc_invoice_no"];
			$arrDocType 		= $_POST["doctype"];
			$arrDocDesc 		= $_POST["docdesc"];
			$share_point_link 	= $_POST["share_point_link"];
			$arrFUDoc 			= $_FILES["fudoc"];
		
			for($i = 0; $i < sizeof($arrDocType); $i++) {
				$folder_path = "uploads/cc/" . $id;
				if (!file_exists($folder_path)){
					mkdir($folder_path, 0755, true);
					
					$findex = $folder_path.'/index.php';
					fopen($findex,'w');
				} 
				$filename 		= $arrFUDoc['name'][$i];
				$tmpFileName 	= $arrFUDoc['tmp_name'][$i];
				if(!empty($filename)){
					$sql = "INSERT INTO file_uploads ( module, file_name, file_path,  doc_type, doc_desc, reference_id, date_uploaded ) VALUES( 'CC', '$filename', '$folder_path', '$arrDocType[$i]', '$arrDocDesc[$i]', '$id', now() )";
					if (mysqli_query($con, $sql)){
							move_uploaded_file($tmpFileName, $folder_path. "/" . $filename);
					}
					else {
							echo "Error: " . mysqli_error($con);
					}
						
				}
			}	
		
		
			echo '<script>window.location.href="budget.php?sub=list";</script>';
		}
		
		$id = $_GET['id'];
		$budget_id = $_GET['id'];
		$sql="Select * from sma_budget where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	
		$locked	 = $row['locked'];
		$blocked_budget	 = $row['blocked_budget'];
		$used_budget	 = $row['used_budget'];

		$budget_head_id = $row['budget_head'];		
		
		$sql 	= "select * from sma_budget_subgroup where id = '$budget_head_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$admin_flag 	= $r2['admin_flag'];

		$readonly='';
		
		if($locked=='Y'  || $viewonly=='Y'){
			$readonly = "READONLY";
		}
		
		if($used_budget>0 || $blocked_budget>0){
			$readonly = "READONLY";
		}
		
		if($user=='Admin'){
			$readonly ='READONLY';
		} 
		
		if($admin_flag=='Y' && $user!='Admin'){
			$readonly ='READONLY';
		}
		else if($admin_flag=='Y' && $user=='Admin'){
			$readonly ='';
		}
		
		$project_v = $row['project'];
		
//echo $user. ' ' .$readonly ."<<>>";		
?>
	
    <!-- Content Header (Page header) -->
    <div class="col-md-12">
    
   <section class="content-header">
        <h1>
            Budget Balance 
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Budget Balance</a></li>
            
        </ol>
    </section>
	
        <!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box">
            <!-- form start -->
            <form class="form-horizontal" action="budget.php?sub=edit" method="post"  enctype="multipart/form-data" >
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
			  
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab_1" data-toggle="tab" id="first_tab" >Budget</a></li>
					<li><a href="#tab_3" data-toggle="tab" id="third_tab">Transaction</a></li>
					<li><a href="#tab_2" data-toggle="tab" id="second_tab">Documents</a></li>	
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="tab_1">
						
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
						<div class="form-group">
							
								<label for="project" class="control-label col-sm-2">Company Name</label>
								<div class="col-sm-4">
									<select class="form-control select2" <?= $readonly ?> name="project" id="project"  >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company where 1 order by comp_name ";//comp_id in ($comid)
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($row['project'] == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
							</div>
							
							<label class="col-lg-2 control-label">Budget Group</label>
							<div class="col-md-4">
								<select class="form-control" <?= $readonly ?> name="budget_name" id="budget_name" onchange="getccsubgroup(this.value);" >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_budget_name order by name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['budget_name'] == $r2['id'])?'selected="selected"':'';?>> <?php echo $r2['name'];?></option>
										<?php } ?>
								</select>
							</div>
						</div>
						<?php
							$budget_name = $row['budget_name'];
						?>
						<div class="form-group">
							<label class="col-lg-2 control-label">Budget Sub Group</label>
							<div class="col-md-4">
							<span id="getccsubgroup">
								<select class="form-control" <?= $readonly ?> name="budget_head" id="budget_head" >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_budget_subgroup where 1 AND budget_name = '$budget_name' order by budget_head ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($row['budget_head'] == $r2['id'])?'selected="selected"':'';?>> <?php echo $r2['budget_head'];?></option>
										<?php } ?>
								</select>
							</span>
							</div>
					<?php 
						$budget_code_v = $row['budget_code'];
						
				// 		$budget_head = $row['budget_head'];
				// 		$sql = "select * from sma_budget_subgroup where 1 AND budget_name = '$budget_name' and id = '$budget_head' ";
				// 		$q2 	= mysqli_query($con, $sql);
				// 		$r2 = mysqli_fetch_array($q2);
				// 		$budget_code = $r2['budget_code'];
						
				// 		if($budget_code_v != $budget_code){
				// 			$sql = "UPDATE sma_budget SET budget_code = '$budget_code' where id ='$budget_id' ";
				// 			mysqli_query($con, $sql);
				// 		}	

					?>							
										
							<label class="col-lg-2 control-label">Posting A/c Name</label>
							<div class="col-md-4">
								<input type="text" class="form-control" id="budget_code" name="budget_code"  placeholder="" <?= $readonly ?> value="<?php echo $budget_code_v;?>" >
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Opening Budget</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="total_budget" name="total_budget" style="text-align:right;" <?= $readonly123; ?> value="<?php echo $row['total_budget'];?>" onkeyup="getbalbugdget()" >
							</div>
							
					<?php
						$readonlyb = 'READONLY';	
						if ( $user == 'Admin' ){
							$readonlyb ='';
						}
						
						$account_year = $row['account_year'];
						
					?>		
							<!--<label class="col-lg-2 control-label">Board Approved Budget</label>-->
							<!--<div class="col-md-2">-->
							<!--	<input type="text" class="form-control" id="board_approved_budget" name="board_approved_budget" style="text-align:right;" <?= $readonlybz ?> placeholder="" value="<?php echo $row['board_approved_budget'];?>" >-->
							<!--</div>-->
							
							<label class="col-lg-2 control-label">Fin.Yearrr</label>
							<div class="col-md-2">
								<select class="form-control" name="account_year" <?= $readonly ?> id="account_year" required >
									<option value=""> Select </option>
								<?php		
									$sql="SELECT * FROM sma_financial_year order by id desc ";
									$q2 = mysqli_query($con, $sql);
									echo mysqli_error($con);
									while($r2 = mysqli_fetch_array($q2)){
									?>
									<option value="<?php echo $r2['short_fy_code']?>" <?php echo ($row['account_year'] == $r2['short_fy_code'] )?'selected="selected"':'';?> ><?php echo $r2['short_fy_code'] ?></option>
								<?php } ?>
								</select>
							</div>
							
							<?php
							$total_budget = $row['total_budget'];
							$month_total = $row['april'] + $row['may'] + $row['june'] + $row['july'] + 
							$row['august'] + $row['september'] + $row['october'] + $row['november'] + 
							$row['december'] + $row['january'] + $row['february'] + $row['march'];
							if($total_budget!=$month_total){ ?>
								<!--<label class="control-label" style="color:red;" > Opening budget not matching with monthly total...</label>-->
							<?php	}	
							?>
						</div>
					
						
						<div class="form-group">
							<div class="col-md-2">
								<label class=" control-label">April</label>
								<input type="text" class="form-control" id="april" name="april" style="text-align:right;" placeholder="" <?= $readonly ?> value="<?php echo $row['april'];?>" >
							</div>
							<div class="col-md-2">
								<label class=" control-label">May</label>
								<input type="text" class="form-control" id="may" name="may" style="text-align:right;" placeholder="" <?= $readonly ?> value="<?php echo $row['may'];?>" >
							</div>
							<div class="col-md-2">
								<label class=" control-label">June</label>
								<input type="text" class="form-control" id="june" name="june" style="text-align:right;" placeholder="" <?= $readonly ?> value="<?php echo $row['june'];?>" >
							</div>
							<div class="col-md-2">
								<label class=" control-label">July</label>
								<input type="text" class="form-control" id="july" name="july" style="text-align:right;" placeholder="" <?= $readonly ?> value="<?php echo $row['july'];?>" >
							</div>
							<div class="col-md-2">
								<label class=" control-label">August</label>
								<input type="text" class="form-control" id="august" name="august" style="text-align:right;" placeholder="" <?= $readonly ?> value="<?php echo $row['august'];?>" >
							</div>
							<div class="col-md-2">
								<label class=" control-label">September</label>
								<input type="text" class="form-control" id="september" name="september" style="text-align:right;" <?= $readonly ?> placeholder="" value="<?php echo $row['september'];?>" >
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-2">
								<label class=" control-label">October</label>
								<input type="text" class="form-control" id="october" name="october" style="text-align:right;" <?= $readonly ?> placeholder="" value="<?php echo $row['october'];?>" >
							</div>
							<div class="col-md-2">
								<label class=" control-label">November</label>
								<input type="text" class="form-control" id="november" name="november" style="text-align:right;" <?= $readonly ?> placeholder="" value="<?php echo $row['november'];?>" >
							</div>
							<div class="col-md-2">
								<label class=" control-label">December</label>
								<input type="text" class="form-control" id="december" name="december" style="text-align:right;" <?= $readonly ?> placeholder="" value="<?php echo $row['december'];?>" >
							</div>
							<div class="col-md-2">
								<label class=" control-label">January</label>
								<input type="text" class="form-control" id="january" name="january" style="text-align:right;" <?= $readonly ?> placeholder="" value="<?php echo $row['january'];?>" >
							</div>
							<div class="col-md-2">
								<label class=" control-label">February</label>
								<input type="text" class="form-control" id="february" name="february" style="text-align:right;" <?= $readonly ?> placeholder="" value="<?php echo $row['february'];?>" >
							</div>
							<div class="col-md-2">
								<label class=" control-label">March</label>
								<input type="text" class="form-control" id="march" name="march" style="text-align:right;" placeholder="" <?= $readonly ?> value="<?php echo $row['march'];?>" >
							</div>
						</div>
					<?php	
						$blocked_budget		= $row['blocked_budget'];
						if($blocked_budget<0){
								$blocked_budget = number_format($blocked_budget,2);
							}
							else {
								$blocked_budget = moneyFormatIndiaa($blocked_budget);
							}
					?>		
						<div class="form-group">
							<label class="col-lg-2 control-label">Blocked Budget</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="blocked_budget" name="blocked_budget" style="text-align:right;" <?php echo $readonly;?> value="<?php echo $blocked_budget;?>" readonly  >
							</div>
						
							<label class="col-lg-2 control-label">Used Budget</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="used_budget" name="used_budget" style="text-align:right;" <?php echo $readonly;?> value="<?php echo moneyFormatIndiaa($row['used_budget']);?>" readonly  >
							</div>
						
						<?php
							$total_budget 		= $row['total_budget'];
							$used_budget		= $row['used_budget'];
							$blocked_budget		= $row['blocked_budget'];
							$adjustment_budget	= $row['adjustment_budget'];
							$balance_budget 	= ($total_budget + $adjustment_budget) - ($blocked_budget + $used_budget) ;
							if($adjustment_budget<0){
								$adjustment_budget = number_format($adjustment_budget,2);
							}
							else {
								$adjustment_budget = moneyFormatIndiaa($adjustment_budget);
							}	
							
							if($balance_budget<0){
								$balance_budget = number_format($balance_budget,2);
							}
							else {
								$balance_budget = moneyFormatIndiaa(round($balance_budget,2));
							}
						?>
						
							<label class="col-lg-2 control-label">Adjustment to Budget</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="adjustment_budget" name="adjustment_budget" style="text-align:right;" <?php echo $readonly;?> placeholder="" value="<?php echo $adjustment_budget;?>" >
							</div>
						</div>
	
						<div class="form-group">
							<label class="col-lg-2 control-label">Balance Budget</label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="balance_budget" name="balance_budget" style="text-align:right;" readonly value="<?php echo $balance_budget;?>" onkeyup="getbalbugdget()" > 
							</div>
							
							<label class="col-lg-1 control-label">Remarks</label>
							<div class="col-md-7">
								<input type="text" class="form-control" id="remarks" name="remarks" value="<?= $row['remarks'];?>" > 
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

				</div>
				
				<div class="tab-pane"  id="tab_2">
			                <!-- Attachments -->
						<div class="box-header">	
							<p><?= $label_line; ?></p>
						</div>
							<!-- Attachments company_idd -->
								
						<?php
                            $sql = "SELECT * FROM file_uploads WHERE module = 'CC' AND reference_id = " . $id;

                            $docResults = mysqli_query($con, $sql);
	                    ?>
                                  <table id="prDocsTable" class="table table-bordered table-striped">
                                      <thead>
                                      <tr>
                                          <th width="20%" >Document Type</th>
                                          <th  width="20%">Description</th>
										   <th  width="30%">File</th>
                                          <th  width="10%">Action</th>
                                          
                                      </tr>
                                      </thead>
                                      <tbody id="prDocsTableBody">
				                              <?php
				                              echo mysqli_error($con);
				                              while($docRow = mysqli_fetch_array($docResults)) {
				                                  $delDocUrl = "deldoc.php?id=" . $docRow['id'] . "&url=" . urlencode($_SERVER['REQUEST_URI']);
													
													$doc_type 		= $docRow['doc_type'];
													$doc_type 		= $docRow['doc_type'];
													$sql="SELECT * FROM sma_document_type where id ='$doc_type' ";
													$rs = mysqli_query($con, $sql);
													$rw = mysqli_fetch_array($rs);
													$document = $rw['document'];
													
												  ?>
                                          <tr>
                                              <td width="20%"><?php echo $document; ?></td>
											  <td width="20%"><?php echo $docRow['doc_desc'] ?></td>
                                              <td width="30%"><a target="_blank" href="<?php echo $dms_path . $docRow['file_path'] . '/' . $docRow['file_name']; ?>"><?php echo $docRow['file_name'] ?></a></td>
											  <?php //if(!$readonly){ ?>
													<td width="10%"><a href="<?php echo $delDocUrl; ?>"><i class='fa fa-trash-alt'></i></a></td>
											  <?php //} ?>  	
                                          
                                          </tr>
				                              <?php
				                              }
				                              ?>
										</tbody>
                                    
									</table>
								
							<span id="gegpartyDoc">
								
							</span>								  
								  
    						<div class="table-responsive">  
                               <table class="table table-bordered" id="dynamic_field">  
                                    <tr> 
										<td width="20%">
                                            <select class="form-control col-sm-2 doctype" name="doctype[]" required="true" >
                                                <option value="0">Select</option>
												<?php
												$sql="SELECT * FROM sma_document_type where 1  ORDER BY document ASC";
												$rs = mysqli_query($con, $sql);
												echo mysqli_error($con);
												while($rw = mysqli_fetch_array($rs)){
												?>
													<option value="<?php echo $rw['id']?>" <?php echo ($doctype == $rw['id'])?'selected="selected"':'';?> ><?php echo $rw['document'] ?></option>
												<?php } ?>	
												
                                            </select>
										</td>
										
										<!--<td><label class="col-sm-2 control-label">Invoice.No.&nbsp;* </label>
                                            <select class="form-control col-sm-2 doctype" name="doc_invoice_no[]" required="true"  <?php echo $readonly; ?>>
                                                <option value="0">Select</option>
												<?php
												$sql = "select id as id, invoice_no from sma_expenses  where approval_ref_no = '$approval_ref_no' and exp_type  = 'C' ";
												$rst = mysqli_query($con, $sql);
												echo mysqli_error($con);
												while($rs = mysqli_fetch_array($rst)){
												?>
													<option value="<?php echo $rs['invoice_no']?>" ><?php echo $rs['invoice_no'] ?></option>
												<?php } ?>	
												
                                            </select>
										</td>-->
										<td width="30%">
											 <textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea>
										</td>
										
										<td width="30%"><input type="file" name="fudoc[]" class="docfile">
										</td>										 
                                      <td width="10%"><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>  
										 
                                    </tr>  
                               </table>  
                            <!--   <input type="button" name="submit" id="submit" class="btn btn-info" value="Submit" />  -->
							</div>							
								
								<span id="predit">
								</span>
								
<?php
$opt = '';	
$sql="SELECT * FROM sma_document_type where 1 ORDER BY document ASC";
$rs = mysqli_query($con, $sql);
echo mysqli_error($con);
while($rw = mysqli_fetch_array($rs)){
	$did 		= $rw['id'];
	$ddocument 	= $rw['document'];
	$opt .= "<option value='" . $did ."' > ".$ddocument."</option>";
 }

?>

<input type="hidden"  value="<?php echo $opt ?>" name="opt" id="opt">

					</div>
<!--Tab_2 End-->						


<!--Transaction View Start -->				
	<div class="tab-pane"  id="tab_3">
		
<?php										
		
			$sql = "SELECT * FROM sma_financial_year WHERE 1 and  short_fy_code = '$account_year' ";
			$q3  = mysqli_query($con, $sql);
			$r3  = mysqli_fetch_array($q3);
			$from_date 	 = date('Y-m-d', strtotime($r3['from_date']));
			$to_date 	 = date('Y-m-d', strtotime($r3['to_date']));
								
			$sql = "truncate table budget_view";
			mysqli_query($con, $sql);							
						
//Purchase Order Start
		$sql = " SELECT distinct(a.id) as id, a.dated, a.project as company , po_type as 'check_var', b.budget_id, a.approval_status as status, approval_memo_ref, a.to_supplier as party_id, a.changed_date, a.status as statuss, a.po_number
			FROM `sma_purchase_order` a, `sma_po_items` b , sma_budget c 
				WHERE 1 
					and a.id = b.purchase_id and approval_status not in (  'Suspend', 'Rejected' )
					and b.budget_id = c.id and a.del !='Y'
					AND c.account_year = '$account_year'
					 AND a.project = '$project_v' 
					 AND dated 	>= '$from_date' 
					 AND dated 	<= '$to_date'
					 AND c.id = '$id' "; 
//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$sort_type 		= 'B';
		$doc_type		= 'PO';
		$doc_no 		= $row['id'];
		$doc_date		= $row['dated'];
		$company_id		= $row['company'];
		$check_var		= $row['check_var'];
		$budget_id		= $row['budget_id'];
		$party_id		= $row['party_id'];
		$status			= $row['status'];
		$statuss		= $row['statuss'];
		$approval_memo_ref	= $row['approval_memo_ref'];
		$changed_date		= $row['changed_date'];
		$po_number		= $row['po_number'];
		
		if($statuss	=='Draft'){
	//		continue;
		}
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$budget_control_gst = $r3['budget_control_gst'];
			
		$sql = " SELECT * FROM `sma_po_items` WHERE purchase_id = '$doc_no' and budget_id = '$budget_id' ";
//echo $sql."<BR>";		
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r2 = mysqli_fetch_array($res)){

			$product_id			= $r2['product_id'];
			$budget_id			= $r2['budget_id'];
			$quantity			= $r2['quantity'];
			$unit_rate			= $r2['unit_rate'];
			$gst				= $r2['gst'];
			
			$bal_si_qty			= $r2['bal_si_qty'];
			$bal_si_amount		= $r2['bal_si_amount'];
										  
			if($status=='Closed'){
				if($quantity == $bal_si_qty){
					$a='';
				}
				else {
					$quantity = $quantity - $bal_si_qty;
				}
			}	
			$amount = round(($quantity * $unit_rate) + ((($quantity * $unit_rate) * $gst) / 100),0);
			if($budget_control_gst =='N'){
				$amount = $quantity * $unit_rate;
			}
		
			$sql  = " SELECT * FROM sma_product WHERE id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name	=$r3['name'];
			$category	=$r3['category'];
			
			$amount_closed = 0;
			if($status=='Closed'){
				/* 
				if($quantity 	== $bal_si_qty){
					$amount_closed 	= $bal_si_amount;
					if($budget_control_gst =='N' ){
						$amount_closed 	= $quantity * $unit_rate;
					}
				
					if( $category=='S'){
						$amount_closed 	= $bal_si_amount;
					}	
				}
				else {
					$a='';
				}
				$amount_closed = $amount - $amount_closed;
				 26-11-2024 */
				$sql = "SELECT a.id , our_po_ref_no, round(sum((qty * rate)),2) as si_amount, round( sum(((qty * rate) * gst) / 100),2) as si_gst, sum(credit_note_value) as credit_note_value  
						FROM sma_supplier_invoice a, sma_supplier_invoice_details b 
							WHERE a.id = si_hdr_id AND a.our_po_ref_no = '$doc_no' 
								AND b.material_id = '$product_id'
								 AND a.del !='Y' 
								AND a.approval_Status != 'Rejected' " ;
				$res3 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r3   = mysqli_fetch_array($res3);
				$credit_note_value	= $r3['credit_note_value'];	
				$si_amount			= $r3['si_amount'] - $credit_note_value;
				$si_gst				= $r3['si_gst'];		
					
				$amount_closed = $amount - ($si_amount + $si_gst );
				if($budget_control_gst =='N' ){
					$amount_closed = $amount - $si_amount ;
				}		
				
			}
			
			
			$sql = " INSERT INTO budget_view (sort_type, doc_type, doc_no, doc_date, po_srno, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var, status, party_id, changed_date, po_number) 
					VALUES( '$sort_type', '$doc_type', '$doc_no', '$doc_date', '$approval_memo_ref', '$budget_id', '$product_name', '$amount', '0', '0', '0' , '$check_var', '$statuss', '$party_id', '$changed_date', '$po_number' ) ";
			mysqli_query($con, $sql);

			if($status=='Closed'){
				$amount_closed	= $amount_closed * -1;
				$sql = " INSERT INTO budget_view (sort_type, doc_type, doc_no, doc_date, po_srno, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var, status, party_id, changed_date, po_number) 
					VALUES( '$sort_type', '$doc_type', '$doc_no', '$doc_date', '$approval_memo_ref', '$budget_id', '$product_name', '$amount_closed', '0', '0', '0' , '$check_var', '$status', '$party_id', '$changed_date' ,'$po_number' ) ";
				mysqli_query($con, $sql);
			}
			
			$bal_amount = 0;
			if($status=='Suspend' || $status=='Amend'){
				//$amount = $amount * -1;
				$bal_si_qty			= $r2['bal_si_qty'];
				$bal_si_amount		= $r2['bal_si_amount'];
				
				if($budget_control_gst =='N'){
					$bal_si_amount = round(($bal_si_amount / ($gst+100) ) * 100,2);
				}	
				$bal_amount			= ($amount - $bal_si_amount ) * -1;
				
				if($bal_amount>0){
					$bal_amount = $bal_amount * -1;
				}
				
			//echo $amount . ' ' . $bal_si_amount. ' ' .$bal_amount. "<BR>";
				$sql = " INSERT INTO budget_view (sort_type, doc_type, doc_no, doc_date, po_srno, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var, status, party_id, changed_date, po_number) 
					VALUES( '$sort_type', '$doc_type', '$doc_no', '$doc_date', '$approval_memo_ref', '$budget_id', '$product_name', '$bal_amount', '0', '0', '0' , '$check_var', '$statuss', '$party_id', '$changed_date', '$po_number' ) ";
				mysqli_query($con, $sql);
				
				//19-03-2024 continue;
				
			}
			
//echo $sql. "<BR>";
			
		}

	}		
			
//Purchase Order End



//Supplier Invoice Start
			
		$sql = " SELECT distinct(a.id) as id, a.created_date as dated, a.company_id as company, '' as 'check_var' ,b.budget_id, a.our_po_ref_no, suplier_name as party_id, a.changed_date, a.status
			FROM `sma_supplier_invoice` a, `sma_supplier_invoice_details` b , sma_budget c 
				WHERE 1 AND a.approval_status !='Rejected' and a.id = b.si_hdr_id 
					and b.budget_id = c.id and a.del !='Y'
					AND c.account_year = '$account_year'
					 and a.company_id = '$project_v' 
					 AND created_date 	>= '$from_date' 
					 AND created_date 	<= '$to_date'
					 AND c.id = '$id' ";
	
//echo $sql."<BR>";
	//$project_v = $_POST['project'];
    if( !empty($location_v) ){
		$sql .= " and a.location = '$location_v' ";
	}
	if( !empty($budget_name_v) && !empty($budget_head_v) ){
		$sql .= " and c.id = '$budget_id_v' ";
	}
	else  {
		if( !empty($budget_name_v) ){
			$sql .= " and c.budget_name = '$budget_name_v' ";
		}
		
		if( !empty($budget_head_v) ){
			$sql .= " and c.budget_head  = '$budget_head_v' ";
		}
	}

//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$sort_type 		= 'C';
		$doc_type		= 'SI';
		$doc_no 		= $row['id'];
		$our_po_ref_no	= $row['our_po_ref_no'];
		$doc_date		= $row['dated'];
		$company_id		= $row['company'];
		$check_var		= $row['check_var'];
		$budget_id		= $row['budget_id'];
		$party_id		= $row['party_id'];
		$changed_date	= $row['changed_date'];
		
		$status			= $row['status'];
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$budget_control_gst = $r3['budget_control_gst'];
			
		$sql = " SELECT * FROM `sma_supplier_invoice_details` WHERE si_hdr_id = '$doc_no' and budget_id = '$budget_id' ";
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r2 = mysqli_fetch_array($res)){

			$product_id			= $r2['material_id'];
			$budget_id			= $r2['budget_id'];
			$quantity			= $r2['qty'];
			$unit_rate			= $r2['rate'];
			$gst				= $r2['gst'];
			$credit_note_value	= $r2['credit_note_value'];
			
			$amount = round(($quantity * $unit_rate) + ((($quantity * $unit_rate) * $gst) / 100),0);
			if($budget_control_gst =='N'){
				$amount = $quantity * $unit_rate;
			}
		
			$amount = $amount - $credit_note_value;
			
			$sql  = " SELECT * FROM sma_product WHERE id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name	=$r3['name'];
			
			if($amount>0){

				$sql = " INSERT INTO budget_view (sort_type, doc_type, doc_no,  po_srno, doc_date, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var, party_id, changed_date, status) 
						VALUES( '$sort_type', '$doc_type', '$doc_no', '$our_po_ref_no', '$doc_date', '$budget_id', '$product_name', '0', '$amount', '0', '0', '$check_var', '$party_id', '$changed_date', '$status' ) ";
				mysqli_query($con, $sql);
				
			}

//echo $sql. "<BR>";
			
		}

	}		
						
//Supplier Invoice End



//Operating Expense Start
			
		$sql = " SELECT distinct(a.id) as id, a.dated as dated, a.exp_type, a.company_id as company , a.approval_number as 'check_var', b.budget_id, approval_number, emp_id as party_id, onbehalf_emp_id, a.changed_date, a.status
			FROM `sma_travel_expenses` a, `sma_expenses` b , sma_budget c 
				WHERE 1 AND a.approval_status not in ('Amend','Rejected') and a.id = b.approval_ref_no 
					and b.budget_id = c.id and a.del !='Y'
					AND c.account_year = '$account_year'
					 and a.company_id = '$project_v'
					 AND a.dated 		>= '$from_date' 
					 AND a.dated 		<= '$to_date'
					 AND c.id = '$id' 
					 ";
	
//echo $sql."<BR>";	and a.company_id in ($comid)
	//$project_v = $_POST['project'];
	if( !empty($location_v) ){
		$sql .= " and a.location = '$location_v' ";
	}						  
	if( !empty($budget_name_v) && !empty($budget_head_v) ){
		$sql .= " and c.id = '$budget_id_v' ";
	}
	else  {
		if( !empty($budget_name_v) ){
			$sql .= " and c.budget_name = '$budget_name_v' ";
		}
		
		if( !empty($budget_head_v) ){
			$sql .= " and c.budget_head  = '$budget_head_v' ";
		}
	}

//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){
		
		$onbehalf_emp_id 	= $row['onbehalf_emp_id'];
		
		
		$sort_type 		= 'D';
		$exp_type 		= $row['exp_type'];
		if($exp_type=='T'){
			$doc_type		= 'TE';
			$party_id 		= $onbehalf_emp_id;
		}
		else if($exp_type=='C'){
			$doc_type		= 'OP';
			$party_id 			= $row['party_id'];
		}
		if($exp_type=='R'){
			$doc_type		= 'RE';
			$party_id 		= $onbehalf_emp_id;
		}
		
		$doc_no 		= $row['id'];
		$doc_date		= $row['dated'];
		$company_id		= $row['company'];
		$check_var		= $row['check_var'];
		$budget_id		= $row['budget_id'];
		$approval_number	= $row['approval_number'];
		$changed_date		= $row['changed_date'];
		$status				= $row['status'];
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$budget_control_gst = $r3['budget_control_gst'];
			
		$sql = " SELECT * FROM `sma_expenses` WHERE approval_ref_no = '$doc_no' and budget_id = '$budget_id' ";
//echo $sql. "<BR>";		
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($r2 = mysqli_fetch_array($res)){

			$product_id			= $r2['reference'];
			$budget_id			= $r2['budget_id'];
			$amount			    = $r2['amount'];
			$gst_amount		    = $r2['gst_amount'];
			
			if($budget_control_gst =='Y'){
				$amount = $amount + $gst_amount;
			}
		
			$sql  = " SELECT * FROM sma_product WHERE id = '$product_id' ";
			$res3 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r3   = mysqli_fetch_array($res3);
			$product_name	=$r3['name'];
			
			$sql = " INSERT INTO budget_view (sort_type, doc_type, doc_no, doc_date, po_srno, budget_id, items, blocked_budget, used_budget, adjustment_budget, balance_budget, check_var, party_id , changed_date, status) 
					VALUES( '$sort_type', '$doc_type', '$doc_no', '$doc_date', '$approval_number', '$budget_id', '$product_name', '0', '$amount', '0', '0' , '$check_var', '$party_id', '$changed_date', '$status' ) ";
			mysqli_query($con, $sql);
			echo mysqli_error($con);
//echo $sql. "<BR>";

//echo $sql. "<BR>";
			
		}

	}		
						
//Operating Expense End



//Budget Adjustment Start
	
		$sql = " SELECT id, dated, project, budget_name, budget_head, budget_code, budget_id, effect, amount, last_year_cf_block FROM `budget_adjust`  where 1  and status = 'Completed' ";
		//and status = 'Completed'
		
		$sql .= " AND project = '$project_v' 
				  AND dated   >= '$from_date' 
				  AND dated   <= '$to_date' 
				  AND budget_id = '$id' ";

//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$sort_type 		= 'A';
		$doc_type		= 'BD';
		$doc_no 		= $row['id'];
		$doc_date		= $row['dated'];
		$company_id		= $row['project'];
		$check_var 		= $row['effect'];
		$budget_id		= $row['budget_id'];
		$amount			= $row['amount'];
		$last_year_cf_block = $row['last_year_cf_block'];
		
		$blocked_budget = 0;
		if($last_year_cf_block=='Y'){
			$blocked_budget 	= $amount;
			
		}	
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$comp_code 			= $r3['comp_code'];
			
			$sql = " INSERT INTO budget_view (sort_type, comp_code, doc_type, doc_no, doc_date, budget_id, adjustment_budget, blocked_budget, check_var) 
			VALUES( '$sort_type', '$comp_code', '$doc_type', '$doc_no', '$doc_date',  '$budget_id', '$amount', '$blocked_budget', '$check_var' ) ";
			mysqli_query($con, $sql);

//echo $sql. "<BR>";

	}		
	
//Budget Adjustment End


//Budget Adjustment From To Start
	
		$sql = " SELECT id, dated, project, budget_id_from, budget_id_to, amount FROM `budget_adjust_from_to`  where 1  and status = 'Completed' ";
		
		$sql .= " AND project = '$project_v' 
				  AND dated   >= '$from_date' 
				  AND dated   <= '$to_date' 
				  AND (budget_id_from = '$id' OR budget_id_to = '$id' ) ";

//echo $sql."<BR>";	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){

		$sort_type 		= 'A';
		$doc_type		= 'BT';
		$doc_no 		= $row['id'];
		$doc_date		= $row['dated'];
		$company_id		= $row['project'];
		$budget_id_from 	= $row['budget_id_from'];
		$budget_id_to		= $row['budget_id_to'];
		$amount				= $row['amount'];
		
		$sql = "SELECT * FROM company WHERE comp_id = '$company_id' ";
		$q3  = mysqli_query($con, $sql);
		$r3  = mysqli_fetch_array($q3);
		$comp_code 			= $r3['comp_code'];
			
		$amount_v = $amount * -1;
		$sql = " INSERT INTO budget_view (sort_type, comp_code, doc_type, doc_no, doc_date, budget_id, adjustment_budget, blocked_budget, check_var, budget_id_transfer) 
			VALUES( '$sort_type', '$comp_code', '$doc_type', '$doc_no', '$doc_date',  '$budget_id_from', '$amount_v', 0, 'From' , '$budget_id_to' ) ";
		mysqli_query($con, $sql);
				
		$sql = " INSERT INTO budget_view (sort_type, comp_code, doc_type, doc_no, doc_date, budget_id, adjustment_budget, blocked_budget, check_var, budget_id_transfer) 
			VALUES( '$sort_type', '$comp_code', '$doc_type', '$doc_no', '$doc_date',  '$budget_id_to', '$amount', 0, 'To', '$budget_id_from'  ) ";
		mysqli_query($con, $sql);
		
//echo $sql. "<BR>";

	}
	
//Budget Adjustment From To End

?>

<table id="prtable123" class="table table-bordered table-striped">

		<thead>
		<tr>
			<th>Doc Type.</th>
			<th>Doc.No.</th>
			<th>PO.SrNo.</th>
			<th  style="text-align:left;">Dated</th>
			<th  style="text-align:left;">Approved Dated</th>
			<th>Party/Items/Expense</th>
			<th>Blocked By PO.</th>
			<th  style="text-align:right;">Blocked By Approval</th>
			<th  style="text-align:right;">Consumed</th>
			<th  style="text-align:right;">Adjustment</th>
			<th  style="text-align:right;">Balance</th>

		</tr>
	</thead>

<?php		
	$sql = " SELECT a.doc_type, a.doc_no, a.items, a.budget_id, a.check_var, a.po_srno , a.doc_date, 
			a.party_name, a.invoice_no, a.approval_no, a.po_number, a.comp_code,
			a.blocked_budget, a.used_budget, a.adjustment_budget, b.total_budget ,
			a.location, a.department, a.status, a.party_id, a.doctype, budget_id_transfer, a.changed_date, a.sort_type
				FROM budget_view a, sma_budget b 
					WHERE  1 and b.id = a.budget_id ";	
//echo $sql;																  
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){
		
		$doc_type	 		= $row['doc_type'];
		$doc_type_v	 		= $row['doc_type'];
		$doc_no	 			= $row['doc_no'];
		$po_srno			= $row['po_srno'];
		$po_number          = $row['po_number'];
		
		$product_name 		= $row['items'];
		$status		 		= $row['status'];
		
		$budget_id 			= $row['budget_id'];
		$party_id 			= $row['party_id'];
		$check_var 			= $row['check_var'];
		$doctype 			= $row['doctype'];
		$budget_id_transfer	= $row['budget_id_transfer'];
		$changed_date		= date('d-m-Y', strtotime($row['changed_date']));
		
		if($doc_type =='PO'){
			$po_srno		= $doc_no;
		}
		
		$baseurl_v = "";
		if($doc_type=='PO'){
			$baseurl_v = $baseurl."purchase_order/edit.php?sub=edit&id=$doc_no";
		}
		else if($doc_type=='AP'){
			$baseurl_v = $baseurl."approval/edit.php?sub=edit&id=$doc_no";
		}
		else if($doc_type=='SI'){
			$baseurl_v = $baseurl."supp_invoice/edit.php?sub=edit&id=$doc_no";
		}
		else if($doc_type=='OP'){
			$baseurl_v = $baseurl."travel_approval/company_expense.php?sub=edit&id=$doc_no";
		}
		else if($doc_type=='TE'){
			$baseurl_v = $baseurl."travel_approval/travel_expence.php?sub=edit&id=$doc_no";
		}
		else if($doc_type=='RE'){
			$baseurl_v = $baseurl."travel_approval/regular_expense.php?sub=edit&id=$doc_no";
		}
		else if($doc_type=='BA'){
			$baseurl_v = $baseurl."budget/budget_adjust.php?sub=edit&id=$doc_no";
		}
		else if($doc_type=='BT'){
			$baseurl_v = $baseurl."budget/budget_adjust_from_to.php?sub=edit&id=$doc_no";
		}
		
		
		if($changed_date=='01-01-1970' || $changed_date=='30-01-0001'){
			$changed_date = '';
		}
		
		$po_amount ='';
		$party_name = '';
		if($doc_type =='AP' || $doc_type =='PO' || $doc_type =='SI'  || $doc_type =='CE' || $doc_type =='OP' ){
			$sql = " SELECT * from sma_party_mst WHERE id = '$party_id' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$party_name 		= $r2['party_name'];
		}
		else if($doc_type =='BT' ){
			$sql = " SELECT * from sma_budget WHERE id = '$budget_id' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$budget_name 		= $r2['budget_name'];
			$budget_head 		= $r2['budget_head'];
			
			$sql = " SELECT * from sma_budget_subgroup WHERE id = '$budget_head' and budget_name= '$budget_name' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$budget_name 		= $r2['budget_name'];
			$budget_head 		= $r2['budget_head'];
			
			$sql = " SELECT * from sma_budget_name WHERE id = '$budget_name' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$budget_name 		= $r2['name'];
			$product_name 		= $budget_name . ' ' . $budget_head;
			
//Transfer Budget			
			$sql = " SELECT * from sma_budget WHERE id = '$budget_id_transfer' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$budget_name 		= $r2['budget_name'];
			$budget_head 		= $r2['budget_head'];
			
			$sql = " SELECT * from sma_budget_subgroup WHERE id = '$budget_head' and budget_name= '$budget_name' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$budget_name 		= $r2['budget_name'];
			$budget_head 		= $r2['budget_head'];
			
			$sql = " SELECT * from sma_budget_name WHERE id = '$budget_name' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$budget_name 		= $r2['name'];
			if($check_var=='From'){
				$product_name 		= $check_var.' ' .$product_name. '<br> To ' .$budget_name . ' ' . $budget_head;
			}
			else if($check_var=='To'){
				$product_name 		= $check_var.' ' .$product_name. '<br> From ' .$budget_name . ' ' . $budget_head;
			}
			
			
		}	
		else {
			$sql = " SELECT * from sma_user WHERE id = '$party_id' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$party_name 		= $r2['username'];
		}	
		
		if($budget_id_prev	!= $budget_id){
			$sql = " SELECT * from sma_budget WHERE id = '$budget_id' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$total_budget 		= $r2['total_budget'];
			$budget_name 		= $r2['budget_name'];
			$budget_head 		= $r2['budget_head'];
			$budget_code 		= $r2['budget_code'];
			//$adjustment_budget 	= $r2['adjustment_budget'];
			$running_balance_budget = $total_budget ;
			
			
			$sql = " SELECT * from sma_budget_name WHERE id = '$budget_name' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$budget_name 		= $r2['name'];
			
			$sql = " SELECT * from sma_budget_subgroup WHERE id = '$budget_head' ";
			$res = mysqli_query($con, $sql);
			$r2  = mysqli_fetch_array($res);
			$budget_head 		= $r2['budget_head'];
			
?>			
			<tr>
				<td width="10%" style="text-align:left;"></td>
				<td width="10%" style="text-align:left;"></td>
				<td width="15%" style="text-align:left;"><b>Opening Balance</b></td>
				<th width="10%" style="text-align:left;"><?= $budget_name; ?></th>
				<th width="10%" style="text-align:left;" colspan="4"><?= $budget_head; ?></th>
				<td width="10%" style="text-align:left;"></td>
				<td width="10%" style="text-align:right;"><?php echo moneyFormatIndiaa($total_budget);?></td>
		  
				</td>
			</tr>
<?php	
		}
		
		$budget_id_prev		= $budget_id;
		
		$dated 				= date('d-m-Y', strtotime($row['doc_date']));
		
		if($dated == '01-01-1970'){
			$dated = '';
		}
		
		$blocked_budget 	= $row['blocked_budget'];
		$used_budget 		= $row['used_budget'];
		$adjustment_budget 	= $row['adjustment_budget'];
		//$balance_budget 	= $row['balance_budget'];
		
		if( $doc_type=='AP' ){
			
			$doc_type = 'Approval Memo';
			
			if($doctype=='AP-ADJ'){
				$doc_type = 'Approval Memo Reversal';
				if($blocked_budget==0){
					continue;	
				}	
				$adjustment_budget	= $blocked_budget * -1;
				$blocked_budget = '';
			}
			else if($status=='Suspend' || $status=='Closed' || $status=='Amend' ){
					
				$blocked_budget	= $blocked_budget * -1;
				$running_balance_budget		= $running_balance_budget - $adjustment_budget - ( $blocked_budget ); 
				$blocked_budget_var = $blocked_budget_var + $blocked_budget;
				
			}
			else {
				$running_balance_budget		= $running_balance_budget - $adjustment_budget - ( $blocked_budget ); //+ $used_budget
				$blocked_budget_var = $blocked_budget_var + $blocked_budget;
			}
			
			if($blocked_budget<0){
				//$doc_type .= ' - '.$status;
			}
		
		}
		else if( $doc_type=='PO' ){
			
			$doc_type = 'Purchase Order';

//echo $running_balance_budget. "<BR>";					
			$running_balance_budget	= $running_balance_budget - $adjustment_budget - ( $blocked_budget ); //+ $used_budget
//echo $running_balance_budget. "<BR>";						
			if($blocked_budget<0){
//echo $running_balance_budget. "<BR>";				
//				$running_balance_budget	= $running_balance_budget - ( $blocked_budget ); 
				$po_amount 		= $blocked_budget;
				$blocked_budget_var = $blocked_budget_var + $blocked_budget;
				$blocked_budget	= '';
				//$doc_type .= ' '.$status;
//echo $blocked_budget_var. "<BR>";				
			}
			else{
				$po_amount 		= $blocked_budget;
				$blocked_budget_var = $blocked_budget_var + $blocked_budget;
				$blocked_budget	= '';	
			}
				
		}
		else if( $doc_type=='SI' ){
			$doc_type = 'Supplier Invoice';
			$used_budget_upd_si	= $used_budget_upd_si + $used_budget;
			
		}
		else if( $doc_type=='OP' ){
			
			$doc_type = 'Operating Expense';
			
			if(empty($check_var) && $running_balance_budget >0){
				$running_balance_budget	= $running_balance_budget - $adjustment_budget - ( $used_budget  ); 
			//	$used_budget_var = $used_budget_var + $used_budget;
			}
			
		}
		else if( $doc_type=='TE' ){
			
			$doc_type = 'Travel Expense';
			//&& $running_balance_budget >0
			if(empty($check_var) ){
				$running_balance_budget	= $running_balance_budget - $adjustment_budget - ( $used_budget  ); 
				//$used_budget_var = $used_budget_var + $used_budget;
			}
			
		}
		else if( $doc_type=='RE' ){
			
			$doc_type = 'Regular Expense';
			
			if(empty($check_var) ){
				$running_balance_budget	= $running_balance_budget - $adjustment_budget - ( $used_budget  ); 
				//$used_budget_var = $used_budget_var + $used_budget;
			}
		}
		else if( $doc_type=='BD' ){
			$doc_type = 'Budget Adjustment';
			
			if($check_var=='I'){
				$running_balance_budget	= $running_balance_budget + $adjustment_budget ; 
			}
			else if($check_var=='D'){
				$running_balance_budget	= $running_balance_budget - $adjustment_budget ; 
				$adjustment_budget 		= $adjustment_budget * -1;
			}

		}
		else if( $doc_type=='BT' && $check_var == 'From'){
			$doc_type = 'Budget Adjustment From';
			$running_balance_budget = $running_balance_budget + $adjustment_budget;
		}
		else if( $doc_type=='BT' && $check_var == 'To'){
			$doc_type = 'Budget Adjustment To';
			$running_balance_budget = $running_balance_budget + $adjustment_budget;
		}
		
		
		$baseurl1 = $baseurl.$modulePath1.'budget_trans_list.php?sub=edit&id='.$row["id"];
					
		if($blocked_budget>0){
			$blocked_budget = moneyFormatIndiaa($blocked_budget);
		}
		else if($blocked_budget<0){
			//echo $blocked_budget. "<BR>";
			$blocked_budget = number_format($blocked_budget,2);
		}
		
		//$blocked_budget_upd = $blocked_budget_upd + $po_amount;
		$used_budget_upd 	= $used_budget_upd + $used_budget;
		
		
		
		if($po_amount>0){
			$po_amount = moneyFormatIndiaa($po_amount);
		}
		else if ($po_amount<0){
			$blocked_budget_revert = $blocked_budget_revert  + $po_amount;
			$blocked_budget = number_format($po_amount,2);
			
			$po_amount ='';
		}
		
		if($adjustment_budget==0){
			$adjustment_budget ='';
		}
		else {
			//$adjustment_budget = number_format($adjustment_budget,2);
			if($adjustment_budget>0){
			$adjustment_budget = moneyFormatIndiaa($adjustment_budget);
			}
			else {
			$adjustment_budget = number_format($adjustment_budget,2);
			
			}
		}												  
		
		if($party_id> 0){
			$party_name = "<BR>($party_name) ";
		}	
				
		$running_balance_budget = round($running_balance_budget,2);
		
		if($running_balance_budget>0){
			$running_balance_budget_v = moneyFormatIndiaa($running_balance_budget);
		}
		else {
			$running_balance_budget_v = number_format($running_balance_budget,2);
			
		}
		
		$used_budget_var = $used_budget_var + $used_budget;
		
		if($used_budget>0){
			$used_budget_v = moneyFormatIndiaa($used_budget);
		}
		else {
			$used_budget_v = number_format($used_budget,2);
			
		}
		
		if($changed_date=='30-11--0001'){
			$changed_date='';
		}
		
		$doc_type .= ' '.$status;
		
		if($doc_type_v == 'PO'){
		    
		   $doc_no=  $po_number;
		}
	?>
	<tr>
		
		<td width="10%" style="text-align:left;"><?php echo $doc_type ;?></td>
		<td width="10%" style="text-align:left;"><a href="<?= $baseurl_v; ?>" target="_blank" ><?php echo $doc_no;?></a></td>
		<td width="10%" style="text-align:left;"><?php echo $po_srno;?></td>
		<td width="10%" style="text-align:left;"><?php echo $dated;?></td>
		<td width="10%" style="text-align:left;"><?php echo $changed_date;?></td>
		
		<td width="15%" style="text-align:left;"><?php echo $product_name. $party_name;?></td>
		<td width="10%" style="text-align:right;"><?php echo $po_amount;?></td>
		<td width="10%" style="text-align:right;"><?php echo ($blocked_budget);?></td>
		<td width="10%" style="text-align:right;"><?php echo $used_budget_v;?></td>
		<td width="10%" style="text-align:right;"><?php echo ($adjustment_budget);?></td>
		<td width="10%" style="text-align:right;"><?php echo $running_balance_budget_v;?></td>
		</td>
    </tr>
	
	<?php } ?>
	
	<tr>	
		<td width="15%" style="text-align:left;">&nbsp;</td>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		<td width="15%" style="text-align:left;">&nbsp;</td>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		<td width="10%" style="text-align:left;">&nbsp;</td>
		</td>
    </tr>
	
</tbody> 
</table>

</div>
  
<!-- TAB_3 End-->						
					
	
		</div>
				
						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; 
								//Mrunmayee started
								$sql =" select count(*) as cnt from sma_supplier_invoice_details a, sma_po_items b where 1 and a.budget_id = b.budget_id and b.budget_id = '$did' ";
								$query1 = mysqli_query($con, $sql);
								echo mysqli_error($con);
								$r2 = mysqli_fetch_array($query1);
								$cnt = $r2['cnt'];
								
								?>
							<?php  if ( $cnt==0 && $viewonly!='Y' && empty($readonly) ){ ?>	
								<a href="<?php echo $baseurl."budget/budget.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a>
							<?php  } //Mrunmayee ended?>	
							</div>
							<?php $baseurl1 = $baseurl.$modulePath;?>
							<div class="col-sm-6 text-right">
								<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Cancel</a>
								<span>&nbsp;&nbsp;</span>
							<?php //if ($role!='Checker' && $viewonly!='Y'){ ?>
								<input class="btn btn-primary" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
							<?php //} ?>	
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


<!-- For Document Attachment Start-->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>        -->
<script>  
/*  $(document).ready(function(){  
      var i=1;  
      $('#add').click(function(){
			var opt =  document.getElementById('opt').value;
			
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'"><td width="20%"><select class="form-control select2 doctype" name="doctype[]"><option value="0">Select</option>'+opt+'</select></td><td width="20%"><textarea class="form-control docdesc" name="docdesc[]" rows="2" placeholder="Enter document description..."></textarea></td><td width="30%"><input type="file" name="fudoc[]" class="docfile"><td width="10%"><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
      });
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      });  
      $('#submit').click(function(){
           $.ajax({  
                url:"name.php",  
                method:"POST",  
                data:$('#add_name').serialize(),  
                success:function(data)  
                {  
                     alert(data);  
                     $('#add_name')[0].reset();  
                }  
           });  
      });  
 });   */
</script>      

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
		var total_budget 		=  document.getElementById('total_budget').value;
		var used_budget 		=  document.getElementById('used_budget').value;
		var blocked_budget 		=  document.getElementById('blocked_budget').value;
		var adjustment_budget 	=  document.getElementById('adjustment_budget').value;
		 
//alert(total_budget + ' ' + adjustment_budget + ' ' + used_budget + ' ' + blocked_budget);
		var balance_budget = (parseInt(total_budget) + parseInt(adjustment_budget)) - (parseInt(used_budget) + parseInt(blocked_budget)) ;
		
		//$('#balance_budget').attr('readonly', true);
		//document.getElementById('balance_budget').value= parseInt(balance_budget);
        
		//alert(balance_budget);
		if (balance_budget < 0){
			alert("Used Budget should be less then total budget...");
			//var used_budget = 0;
			document.getElementById('used_budget').value=0;
			
		}
		
	}	
	
		
	function getccsubgroup(id){
		
        var sub    = 'sub14a';
		var company_id =  document.getElementById('project').value;
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,company_id:company_id,sub14a:sub},function(result){
		      $('#getccsubgroup').html(result);
		});

	}
	
	function getcccode(id){
		
        var sub    = 'sub14b';
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub14b:sub},function(result){
		      $('#getcccode').html(result);
		});

	}
</script>

</body>
</html>
