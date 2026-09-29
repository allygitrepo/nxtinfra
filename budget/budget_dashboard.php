<?php
//include("../header.php");
$comid = $_SESSION['comid'];
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
											
	$open_budget 	= $row['total_budget'];
	$used_budget 	= $row['used_budget'];
	$blocked_budget	= $row['blocked_budget'];
	$adjustment_budget	= $row['adjustment_budget'];
	$locked 		= $row['locked'];
	
	$total_budget		= $open_budget + $adjustment_budget;
	
	
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
