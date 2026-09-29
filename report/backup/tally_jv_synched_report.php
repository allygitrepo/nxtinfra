<?php 
	if($_GET['sub'] == 'list'){
		
	include("../header.php");
//$modulePath = "budget/budget_trans_list.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Tally JV Synched Report
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Tally JV</li>
      </ol>
    </section>

<div class="col-md-12">
	
		<div class="box box-info">
            <div class="box-header with-border">
       		<?php 
				
			$sql = "SELECT * FROM sma_financial_year WHERE 1 and  short_fy_code = '$account_year' order by id desc ";
			$q3  = mysqli_query($con, $sql);
			$r3  = mysqli_fetch_array($q3);
			$comp_start_date = date('d-m-Y', strtotime($r3['from_date']));
			$comp_end_date 	 = date('d-m-Y', strtotime($r3['to_date']));
			
			?>
			<form class="form-horizontal" action="tally_jv_synched_report.php?sub=view&view=Y" target="_blank" method="post">
                      
						<div class="form-group">	
							<div class="col-sm-4">
								<label for="project" class="control-label ">Company</label>
								<select class="form-control select2" name="project" id="PROJECT" required  >
                             		<option value=""> Select </option>
									<?php 
										$sql = " select * from company WHERE comp_id in ( $comid ) order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ 
									?>
									<option value="<?php echo $r2['comp_id'];?>" <?php echo ($project_v == $r2['comp_id'])?'selected="selected"':'';?> >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
								</select>		
							</div>
						
							
								
						<?php
							$comp_start_date 	= date("d-m-Y");	
							$comp_end_date 		= date("d-m-Y");	
						?>
						
						
								<div class="col-md-2">
									<label class="control-label">From Date</label>
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="from_date" name="from_date" placeholder="" value="<?php echo $comp_start_date; ?>" >
										<div class="input-group-addon">
											<i class="fa fa-calendar-alt"></i>
										</div>
									</div>	
								</div>
								
								<div class="col-md-2">
									<label class="control-label">To Date</label>
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="to_date" name="to_date" placeholder="" value="<?php echo $comp_end_date;; ?>" >
										<div class="input-group-addon">
											<i class="fa fa-calendar-alt"></i>
										</div>
									</div>	
								</div>
							
							<div class="pull-right col-xs-1">	
								<a href="tally_jv_synched_report.php?sub=list&reset=1" name="btnCancel" class="btn btn-danger btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
							</div>
							
							<div class="pull-right col-xs-1">
								<input class="btn btn-success" type="submit" value="Export" name="Save">&nbsp;&nbsp;&nbsp;
							</div>
							
						</div>
						
				</form>

			</div>
			</div>
			
		</div>	
    

<?php 	
		include("../footer.php");	
?>

<!-- Select2 -->
<script src="<?php echo $baseurl . "plugins/select2/select2.full.js" ?>"></script>
<!-- InputMask -->
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.date.extensions.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/input-mask/jquery.inputmask.extensions.js" ?>"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo $baseurl . "plugins/ckeditor/ckeditor.js" ?>"></script>

<!-- DataTables 
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js" ?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js" ?>"></script>
-->

<script>
	
	function getproduct_group(id){

		var sub    		= 'sub16';
//alert(sub);		
		var company_id  = document.getElementById("PROJECT").value;
		
//alert(sub + ' ' + id + ' <<>> ' + company_id + ' ' + account_year);
		var strURL 		= "app_func.php";
		$.post(strURL,{id:id,company_id:company_id,sub16:sub},function(result){
		      $('#getproduct_group').html(result);
		});
		
	}
	
</script>
	
<?php
}

if($_GET['view']=='Y' ){
	
	include("../dbcon.php");	
	
		$project_v 		= $_POST['project'];
		$from_date 		= date('Y-m-d', strtotime($_POST['from_date']));
		$to_date 		= date('Y-m-d', strtotime($_POST['to_date']));

    $message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: left; font-size: 12pt;'>";
	$message .= "<tr>
			<th colspan ='8'>Tally JV Synched Report for the period from " . $_POST['from_date']. ' To '. $_POST['to_date'] . " </th>
		</tr>";
		
	$message .= "	<tr>
			<th>Company</th>
			<th>Doc Type.</th>
			<th>Doc.No.</th>
			<th>Invoice No.</th>
			<th>Invoice Date</th>
			<th>Party</th>
			<th>Amount</th>	
			<th>Uploaded Date</th>
		</tr>";	
		
	$sql = " SELECT distinct(doc_type), doc_no, doc_date, supp_invoice_no, supp_invoice_date, company_id, account_id, account_type, amount, tally_uploaded_on FROM `tally_journal_entry` where 1 and status = 'U' and account_type in ('V', 'U') ";
				
	$project_v 	= $_POST['project'];
	
	$sql .= " AND company_id = '$project_v' ";

	$sql .= " AND tally_uploaded_on >= '$from_date' AND tally_uploaded_on <= '$to_date' ";
	
	$sql .= " ORDER BY tally_uploaded_on, doc_type, doc_no  ";
//echo $sql;	
	$res  = mysqli_query($con, $sql);
	echo mysqli_error($con);					 
 	$total_pages = mysqli_affected_rows($con);

	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){
	
		$doc_type	 			= $row['doc_type'];
		$doc_no	 				= $row['doc_no'];
		$doc_date				= $row['doc_date'];
		$tally_uploaded_on		= date('d-m-Y', strtotime($row['tally_uploaded_on']));
		
		$supp_invoice_no 		= $row['supp_invoice_no'];
		$supp_invoice_date		= date('d-m-Y', strtotime($row['supp_invoice_date']));
		
		$company_id 			= $row['company_id'];
		$account_id				= $row['account_id'];
		$account_type			= $row['account_type'];
		$amount 				= $row['amount'];
		
		$sql = " select * from company WHERE comp_id  = '$company_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$company_name = $r2['comp_code'];
		
		if($account_type=='V'){
			$sql = " select * from sma_party_mst WHERE id  = '$account_id' ";
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$party_name = $r2['party_name'];
		}
		else if($account_type=='U'){
			$sql = " select * from sma_user WHERE id  = '$account_id' ";
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$party_name = $r2['username'];
		}
		
		$message .= "<tr>
			<td>". $company_name."</td>
			<td>".  $doc_type ."</td>
			<td>".  $doc_no."</td>
			<td>".  $supp_invoice_no."</td>
			<td>".  $supp_invoice_date."</td>
			<td>".  $party_name."</td>
			<td>".  $amount."</td>
			<td>".  $tally_uploaded_on."</td>
		</tr>";
		
	}
	 
	$message .= "</table>";
	
//echo $message;
//exit('Exit...');
	$fl_name = 'tally_jv_synched_report'.'_'.date('d-m-Y h:i:sa').'.xls';
		header("Content-type: application/xls");
		header("Content-Type:'application/force-download'");
		Header("Content-Disposition: attachment; filename=$fl_name");
	
		print $message;

}


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

