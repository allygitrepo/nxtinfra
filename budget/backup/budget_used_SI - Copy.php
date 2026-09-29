<!DOCTYPE html>
<?php

include("../header.php");
$modulePath = "budget/budget_used_SI.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">


<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Budget Used Transactions - SI
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Budget</li>
      </ol>
    </section>

<div class="col-md-12">
	
		<div class="box box-info">
            <div class="box-header with-border">
               			<?php 
				//echo $_POST['project'].'<> ';
				if ($_POST['project'] or $_POST['budget_name'] or $_POST['budget_head'] or $_POST['account_year']){
					$_SESSION['project_a'] = $_POST['project'];
					$_SESSION['budget_name_a'] = $_POST['budget_name'];
					$_SESSION['budget_head_a'] = $_POST['budget_head'];
					$_SESSION['account_year_a'] = $_POST['account_year'];
				}
				
				if ($_SESSION['project_a'] or $_SESSION['account_year_a'] or $_SESSION['budget_name_a'] or $_POST['budget_head_a']){
					$project_v = $_SESSION['project_a'];
					$budget_name_v = $_SESSION['budget_name_a'];
					$budget_head_v = $_SESSION['budget_head_a'];
					$account_year_v = $_SESSION['account_year_a'];
				}
	
				if (!empty($_GET['reset']) || !empty($_SESSION['reset']) ) {
					$_SESSION['project_a'] = '';
					$_SESSION['budget_name_a'] = '';
					$_SESSION['budget_head_a'] = '';
					$_SESSION['account_year_a'] = '';
					$_SESSION['reset'] = '';
					$project_v 		= $_SESSION['project_a'];
					$budget_name_v 	= $_SESSION['budget_name_a'];
					$budget_head_v 	= $_SESSION['budget_head_a'];
					$account_year_v 	= $_SESSION['account_year_a'];
				}
				
	//	echo $project_v. ' >< '. $account_year_v;
			?>
					<form class="form-horizontal" action="budget_used_SI.php?sub=list" method="post">
                      
								
						<div class="form-group">
							<div class="col-md-2">
								<label class="control-label">Account Year</label>
								<select class="form-control" name="account_year" id="account_year" >
									<option value=""> Select </option>
									<option value="1" <?php echo ($account_year_v == '1')?'selected="selected"':'';?> > 2017-2018 </option>
									<option value="2" <?php echo ($account_year_v == '2')?'selected="selected"':'';?> > 2018-2019 </option>
									<option value="3" <?php echo ($account_year_v == '3')?'selected="selected"':'';?> > 2019-2020 </option>
									<option value="4" <?php echo ($account_year_v == '4')?'selected="selected"':'';?> > 2020-2021 </option>
									<option value="5" <?php echo ($account_year_v == '5')?'selected="selected"':'';?> > 2021-2022 </option>									
								</select>	
							</div>

							
							<div class="col-sm-3">
								<label for="project" class="control-label ">Company</label>
								<select class="form-control select2" name="project" id="project" onchange="getlocation(this.value)" >
                             		<option value=""> Select </option>
										<?php $sql = "select * from company order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($project_v == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
							</div>
						
							
							<div class="col-md-3">
								<label class=" control-label">Budget Name</label>
								<select class="form-control" name="budget_name" id="budget_name" >
									<option value=""> Select </option>
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
								<a href="budget_used_SI.php?sub=list&reset=1" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
							
							</div>

			<div class="pull-right">
				<span class="sepV_c marginRight">
					<a href="budget_SI_export.php?sub=pdf&sql=<?php echo $_SESSION['sql'];?>" class="btn btn-primary">Export</a>
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
			<th></th>
			<th>Account Year</th>
			<th>Company</th>
			<th>Budget Name-Head</th>
			<th style="text-align:left;">PO.Number</th>
			<th style="text-align:left;">SI.No./ Vendor Name</th>
			<th>Material Name</th>
			<th  style="text-align:left;">Dated</th>
			<th  style="text-align:right;">Total Budget</th>
			<th  style="text-align:right;">Used Budget</th>
			<th  style="text-align:right;">Balance</th>
			
		</tr>
	</thead>
<tbody>
<?php

	$i = 0;
	$modulePath1 = "budget/";
	
//	$sql = " SELECT a.po_number, a.approval_memo_ref, b.purchase_id, a.id, b.budget_head as bhe, a.budget_head as bh, c.budget_head as budget_head, a.dated, b.quantity, b.unit_rate, b.gst, (b.quantity * b.unit_rate) as total_price, a.budget_name, b.product_id AS product_id, b.material_name as material_name
//		FROM sma_purchase_order a, `sma_po_items` b, `sma_approval_memo` c 
//			where a.id = b.purchase_id and a.approval_memo_ref = c.id order by c.budget_head, a.po_number ";


	$sql = "SELECT a.si_hdr_id, a.material_id, a.description, a.account_year, a.company_id, a.budget_id, a.amount , b.id as material_id , 
			b.name as material_name, b.`group` as material_group
			FROM sma_supplier_invoice_details a, `sma_product` b 
				WHERE si_srno > 0 and a.material_id =  b.id
					ORDER BY material_name  ASC ";

	$_SESSION['sql'] = $sql;
	
//echo $sql."<BR>";
	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		$si_hdr_id 		= $row['si_hdr_id'];
		$sql 	= " SELECT * FROM `sma_supplier_invoice` where id = '$si_hdr_id' ";
		$q1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($q1);
		$company_id   	= $r1['company_id'];
		$our_po_ref_no  = $r1['our_po_ref_no'];
		$suplier_name	= $r1['suplier_name'];
		$invoice_date 	= date('d-m-Y', strtotime($r1['invoice_date']));
		
		$amount			= $row['amount'];
		$material_group = $row['material_group'];
		$material_name  = $row['material_name'];
		//$company_id   	= $row['company_id'];
		$account_year	= $row['account_year'];
		
		$account_year	= '3'; //'2019-20'
		
		$sql = "SELECT 	distinct(a.id), a.project, a.budget_name, a.budget_category, a.account_year, a.total_budget, a.blocked_budget, a.used_budget, a.locked, b.budget_name, b.budget_head 
		FROM `sma_budget` a, sma_product_group b
		where a.locked !='Y' and a.budget_name = b.budget_name  
			and a.budget_category 	= b.budget_head
			and a.budget_category 	= '$material_group'
			and a.account_year	 	= '$account_year'
			and a.project 			= '$company_id'
		ORDER BY `a`.`budget_name` ASC ";
//echo $sql."<BR>";
//exit();		
		$q2 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2 = mysqli_fetch_array($q2);
		$total_budget   	= $r2['total_budget'];
		$used_budget		= $amount;
		$balance_budget 	= $total_budget - $used_budget;
	
		$project 			= $r2['project'];
		$budget_name 		= $r2['budget_name'];
		$budget_head	 	= $r2['budget_category'];
		$account_year	 	= $r2['account_year'];
			
	//echo $sql."<BR>";
	//exit();
	
	if(!empty($project_v)){
		if($project_v != $project){
			continue;
		}
	}
	if (!empty($budget_head_v)){
		if($budget_head_v !=$budget_head){
			continue;
		}
	}
	if (!empty($budget_name_v)){
		if($budget_name_v !=$budget_name){
			continue;
		}
	}
	
//	if (!empty($account_year_v)){
//		if($account_year_v != $account_year){
//			continue;
//		}
//	}
	
	$sql = "SELECT * from company where comp_id = '$company_id' ";
	$res = mysqli_query($con, $sql);
	//echo mysqli_error($con);
	$r3 = mysqli_fetch_array($res);
	$comp_name = $r3['comp_name'];
	
	
	if ($account_year=='1'){
		$acyr = '2017-2018';
	}
	else if ($account_year=='2'){
		$acyr = '2018-2019';
	} 
	else if ($account_year=='3'){
		$acyr = '2019-2020';
	} 
	else if ($account_year=='4'){
		$acyr = '2020-2021';
	}
	else if ($account_year=='5'){
		$acyr = '2021-2022';
	}
	else { $acyr = $account_year; }
	
	$budget_category = $budget_head;
	$sql = "SELECT * from sma_budget_category where id = '$budget_category' ";
	$res = mysqli_query($con, $sql);
	$r4 = mysqli_fetch_array($res);
	$category = $r4['category'];
	
	$budget_name = $budget_name;
	$sql = "SELECT * from sma_budget_name where id = '$budget_name' ";
	$res = mysqli_query($con, $sql);
	$r5 = mysqli_fetch_array($res);
	$bname = $r5['name'];
	
		$sql  = " SELECT * FROM `sma_purchase_order` where id = '$our_po_ref_no' ";
		$q6 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r6 = mysqli_fetch_array($q6);
		$po_number   	= $r6['po_number'];
		
		
		$sql  = " SELECT * FROM `sma_party_mst` where id = '$suplier_name' ";
		$q7 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r7 = mysqli_fetch_array($q7);
		$party_name   	= $r7['party_name'];
		
		
	$baseurl1 = $baseurl.$modulePath1.'budget_used_SI.php?sub=edit&id='.$row["id"];	
		
	?>
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="0%"><input type="hidden" value="<?php echo ++$i;?>" > </td>
		<td width="5%"><?php echo $acyr;?></td>
		<td width="20%"><?php echo $comp_name;?></td>
		<td width="15%"><?php echo $bname.'- '. $category;?></td>
		<td width="08%" style="text-align:left;"><?php echo $po_number;?></td>
		<td width="10%" style="text-align:left;"><?php echo $si_hdr_id .' / '. $party_name;?></td>
		<td width="15%" style="text-align:left;"><?php echo $material_name;?></td>
		<td width="07%" style="text-align:left;"><?php echo $invoice_date;?></td>
		<td width="07%" style="text-align:right;"><?php echo moneyFormatIndia($total_budget);?></td>
		<td width="07%" style="text-align:right;"><?php echo moneyFormatIndia($used_budget);?></td>
		<td width="07%" style="text-align:right;"><?php echo moneyFormatIndia($balance_budget);?></td>
		
  
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
