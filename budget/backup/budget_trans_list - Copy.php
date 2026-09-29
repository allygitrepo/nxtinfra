<?php

include("../header.php");
$modulePath = "budget/budget_trans_list.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">


<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Budget Transactions
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Budget</li>
      </ol>
    </section>

<div class="col-md-12">
	
		<div class="box box-info">
            <div class="box-header with-border">
               			<?php 
				//echo $_POST['project'].'<> ';
				if ($_POST['project'] or $_POST['budget_name'] or $_POST['budget_head'] ){
					$_SESSION['project_a'] = $_POST['project'];
					$_SESSION['budget_name_a'] = $_POST['budget_name'];
					$_SESSION['budget_head_a'] = $_POST['budget_head'];
					
				}
				
				if ($_SESSION['project_a'] or $_SESSION['budget_name_a'] or $_POST['budget_head_a']){
					
					$project_v 		= $_SESSION['project_a'];
					$budget_name_v 	= $_SESSION['budget_name_a'];
					$budget_head_v 	= $_SESSION['budget_head_a'];
					
				}
	
				if ( !empty($_GET['reset']) || !empty($_SESSION['reset']) ){
				
					$_SESSION['project_a'] = '';
					$_SESSION['budget_name_a'] = '';
					$_SESSION['budget_head_a'] = '';
					$_SESSION['reset'] = '';
					$project_v 		= $_SESSION['project_a'];
					$budget_name_v 	= $_SESSION['budget_name_a'];
					$budget_head_v 	= $_SESSION['budget_head_a'];
				
				}

	//	echo $project_v. ' >< '. $account_year_v;
			?>
					<form class="form-horizontal" action="budget_trans_list.php?sub=list" method="post">
                      
								
						<div class="form-group">
							
							<div class="col-sm-3">
								<label for="project" class="control-label ">Company</label>
								<select class="form-control select2" name="project" id="project" onchange="getlocation(this.value)" >
                             		<option value=""> Select </option>
									<option value="All" <?php echo ($project_v == 'All' )?'selected="selected"':'';?> > All </option>
									<?php 
										$sql = " select * from company where comp_id in ( $comid ) order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ 
									?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($project_v == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
							</div>
						
							<div class="col-md-3">
								<label class=" control-label">Budget Name</label>
								<select class="form-control" name="budget_name" id="budget_name" >
									<option value=""> Select </option>
									<option value="All" <?php echo ($budget_name_v == 'All' )?'selected="selected"':'';?>  > All </option>
										<?php $sql = "select * from sma_budget_name order by name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($budget_name_v == $r2['id'])?'selected="selected"':'';?>> <?php echo $r2['name'];?></option>
										<?php } ?>
								</select>
							</div>

							
							<div class="col-md-3">
									<label class="control-label">Budget Head</label>
									<select class="form-control" name="budget_head" id="budget_head" >
									<option value=""> Select </option>
									<option value="All" <?php echo ($budget_head_v == 'All' )?'selected="selected"':'';?> > All </option>
										<?php $sql = "select * from sma_budget_category order by category ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" <?php echo ($budget_head_v == $r2['id'])?'selected="selected"':'';?>> <?php echo $r2['category'];?></option>
										<?php } ?>
									</select>
							</div>
							
						</div>
												
												
						<div class="form-group">		
							<div class="col-xs-2">
                                		
								<input class="btn btn-primary" type="submit" value="Search" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="budget_trans_list.php?sub=list&reset=1" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
							
							</div>

			<div class="pull-right">
				<span class="sepV_c marginRight">
					<a href="budget_po_export.php?sub=pdf&sql=<?php echo $_SESSION['sql'];?>" class="btn btn-primary">Export</a>
					&nbsp;&nbsp;&nbsp;
					<!--<a href="budget_trans_export.php?sub=pdf&sql=<?php echo $_SESSION['sql'];?>" class="btn btn-primary">Export</a>
					&nbsp;&nbsp;&nbsp;-->
			    </span>
			</div>							
						</div>
						
				</form>

			</div>
			
		</div>	
    <div class="box">
    <table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>Company</th>
			<th>Budget Name</th>
			<th>Budget Head</th>
			<th style="text-align:left;">PO.Number</th>
			<th>Material Name</th>
			<th  style="text-align:left;">Dated</th>
			<th  style="text-align:right;">Amount</th>
			
		</tr>
	</thead>
<tbody>
<?php
	$modulePath1 = "budget/";
	
	$sql = " SELECT a.po_number, a.approval_memo_ref, b.purchase_id, a.id, b.budget_head as bhe, a.budget_head as bh, c.budget_head as budget_head, a.dated, b.quantity, b.unit_rate, b.gst, (b.quantity * b.unit_rate) as total_price, a.budget_name, b.product_id AS product_id, b.product_name as product_name
		FROM sma_purchase_order a, `sma_po_items` b, `sma_approval_memo` c , sma_budget d 
			where a.id = b.purchase_id and a.approval_memo_ref = c.id and d.id = c.budget_head 
			and a.project in ($comid) ";
	
//$sql = "SELECT a.po_number, a.approval_memo_ref, a.id as po_id, a.budget_head as bh, c.budget_head as budget_head, a.dated, a.budget_name, a.to_supplier 
//			FROM sma_purchase_order a, `sma_approval_memo` c, sma_budget d 
//				where a.approval_memo_ref = c.id and d.id = c.budget_head ";

//and a.project = '6' and d.budget_name = '5' 

	if($project_v !='All' && !empty($project_v) ){
		$sql .= " and a.project = '$project_v' ";
	}
	
	if( $budget_name_v !='All' && !empty($budget_name_v) ){
		$sql .= " and d.budget_name = '$budget_name_v' ";
	}
	
	if( $budget_head_v !='All'  && !empty($budget_head_v) ){
		$sql .= " and d.budget_category = '$budget_head_v' ";
	}

	$sql .= " order by a.project, d.budget_category, a.po_number  ";

	$_SESSION['sql'] = $sql;
	
//echo $sql;
	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
	
	$budget_head = $row['budget_head'];
	$product_name = $row['product_name'];
	
	if($budget_head==0){continue;}
	
	
		$sql = "SELECT * from sma_budget where id = '$budget_head' ";
	
//echo $sql;
//exit();
	
	$res = mysqli_query($con, $sql);
	$r2  = mysqli_fetch_array($res);
	$project 			= $r2['project'];
	$budget_name 		= $r2['budget_name'];
	$budget_head	 	= $r2['budget_category'];
	$total_budget 		= $r2['total_budget'];
	$used_budget 		= $r2['used_budget'];

	if($project_v !='All' && !empty($project_v)){
		if($project_v != $project){
			continue;
		}
	}
	if ($budget_head_v !='All' && !empty($budget_head_v)){
		if($budget_head_v !=$budget_head){
			continue;
		}
	}
	if ( $budget_name_v !='All' && !empty($budget_name_v)){
		if($budget_name_v !=$budget_name){
			continue;
		}
	}
//	if (!empty($account_year_v)){
//		if($account_year_v != $account_year){
//			continue;
//		}
//	}
	
	$balance_budget = $total_budget - $used_budget;
	
	$sql = "SELECT * from company where comp_id = '$project' ";
	$res = mysqli_query($con, $sql);
	//echo mysqli_error($con);
	$r2 = mysqli_fetch_array($res);
	$comp_name = $r2['comp_name'];
	
	$budget_category = $budget_head;
	$sql = "SELECT * from sma_budget_category where id = '$budget_category' ";
	$res = mysqli_query($con, $sql);
	$r2 = mysqli_fetch_array($res);
	
	$category = $r2['category'];
	
	$budget_name = $budget_name;
	$sql = "SELECT * from sma_budget_name where id = '$budget_name' ";
	$res = mysqli_query($con, $sql);
	$r2 = mysqli_fetch_array($res);
	
	$bname = $r2['name'];
	
	$dated 		= date('d-m-Y', strtotime($row['dated']));
	$po_number 	= $row['po_number'];
	$gst		 	= $row['gst'];
	$amount 	    = $row['total_price'];
	$total_amount 	= round(($amount + ($amount * $gst / 100)),2);
	$total_amount 	= bcadd($total_amount, 0, 2);
	
	$baseurl1 = $baseurl.$modulePath1.'budget_trans_list.php?sub=edit&id='.$row["id"];
				
	?>
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="25%"><?php echo $comp_name;?></td>
		<td width="15%"><?php echo $bname;?></td>
		<td width="12%"><?php echo $category;?></td>
		<td width="10%" style="text-align:left;"><?php echo $po_number;?></td>
		<td width="15%" style="text-align:left;"><?php echo $product_name;?></td>
		<td width="08%" style="text-align:left;"><?php echo $dated;?></td>
		<td width="10%" style="text-align:right;"><?php echo moneyFormatIndia($total_amount);?></td>
  
		</td>
    </tr>
	
	
	<?php }?>
</tbody> 
</table>
	</div>
    </div>
</div>	

    <?php }?>

<?php 	
		include("../footer.php");	

		
function moneyFormatIndia($num){
        $nums = explode(".",$num);
        if(count($nums)>2){
            return "0";
        }else{
        if(count($nums)==1){
            $nums[1]="00";
        }
        $num = $nums[0];
        $explrestunits = "" ;
        if(strlen($num)>3){
            $lastthree = substr($num, strlen($num)-3, strlen($num));
            $restunits = substr($num, 0, strlen($num)-3); 
            $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; 
            $expunit = str_split($restunits, 2);
            for($i=0; $i<sizeof($expunit); $i++){

                if($i==0)
                {
                    $explrestunits .= (int)$expunit[$i].","; 
                }else{
                    $explrestunits .= $expunit[$i].",";
                }
            }
            $thecash = $explrestunits.$lastthree;
        } else {
            $thecash = $num;
        }

		if($thecash==0){
			$nums = $nums[1];
			if($nums==0){
				$nums[1] = '';
			}
			$thecash = '';
		}
		else{
			$thecash = $thecash.".".$nums[1]; // with decimal eg. 123.12
			//$thecash = $thecash; // without decimal eg. 123
		}
        
		return $thecash;
    }
}

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
