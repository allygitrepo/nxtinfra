<?php
include("../header.php");
$modulePath = "tender/";
$usrid  = $_SESSION['usrid'];

	$help_code = $modulePath.'index.php';
	include "../help_code.php";
	
$pgname = $help_code;
include("../viewonly.php");

	
?>
<!-- DataTables -->
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Tender / RFP <small>List</small>
		<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
		</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Tender / RFP</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
   			<?php 
				$targetpage = "index.php?sub=list"; 
				$limit = 25; 
				$start = 0;	
				$comid  = $_SESSION['comid'];
				
				if ($_POST['comp_id'] or $_POST['status'] or $_POST['approval_status'] or $_POST['searchf'] or $_POST['search_own'] ){
					$_SESSION['comp_id'] = $_POST['comp_id'];
					$_SESSION['statuss'] = $_POST['status'];
					$_SESSION['approval_status'] = $_POST['approval_status'];
					$_SESSION['searchf'] 	= $_POST['searchf'];
					$_SESSION['search_own'] = $_POST['search_own'];
					$_SESSION['search_data']= $_POST['search_data'];
					$_SESSION['start_date'] = $_POST['start_date'];
					$_SESSION['end_date'] = $_POST['end_date'];					
				}
				
				if ($_SESSION['comp_id'] or $_SESSION['approval_status'] or $_SESSION['statuss'] or $_SESSION['searchf'] or $_SESSION['search_own'] ){
					$comp_id = $_SESSION['comp_id'];
					$status = $_SESSION['statuss'];
					$approval_status = $_SESSION['approval_status'];
					$searchf = $_SESSION['searchf'];
					$search_own		= $_SESSION['search_own'];
					$search_data = $_SESSION['search_data'];
					$start_date = $_SESSION['start_date'];
					$end_date = $_SESSION['end_date'];
					$_SESSION['reset']='';
				}
				
				if (!empty($_GET['reset']) || !empty($_SESSION['reset'])){
					$_SESSION['comp_id'] = '';
					$_SESSION['statuss'] = '';
					$_SESSION['approval_status'] = '';
					$_SESSION['searchf'] = '';
					$_SESSION['search_own'] = '';
					$_SESSION['search_data'] = '';
					$_SESSION['start_date'] = '';
					$_SESSION['end_date'] = '';
					$comp_id = $_SESSION['comp_id'];
					$status = $_SESSION['statuss'];
					$searchf = $_SESSION['searchf'];
					$search_data = $_SESSION['search_data'];
					$start_date = $_SESSION['start_date'];
					$end_date = $_SESSION['end_date'];
					$search_own = $_SESSION['search_own'];
					$approval_status = $_SESSION['approval_status'];
					
				}
				/* 
				if(!$_POST['Save']){
					$search_own='Y';
				} */
				
			?>
					<form class="form-horizontal" action="index.php?sub=list" method="post">
                      
						<div class="form-group">
								
							<label class="col-lg-1 control-label">Company</label>
							<div class="col-md-3">
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
								<a href="index.php?sub=list&reset=1" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
							</div>
					<?php // echo $viewonly. ' ' . $primaryrole . ' >><< ' . $role. "<<>>";
				//	if ( $viewonly != 'Y'){ ?>						
							<span class="pull-right"><a href="<?php echo $baseurl . $modulePath . "add.php"?>" class="btn btn-primary">Create</a> &nbsp;&nbsp;&nbsp;</span>
					<?php //} ?>	
						</div>					   
											   
				</form>
				
			
            </div>
			
			<span id="prItemsTableBody123"> </span>
			
            <!-- /.box-header -->
            <div class="box-body">
              <table id="prtableabc" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>Tender No.</th>
					<th>Tender Title</th>
					<th>From Company</th>
					<th>Created Date</th>
					<th>Deadline</th>
					<th>Tender Send to Suppliers</th>
					<th>Quote Received From</th>
					<th>Final Selected Vendor</th>
					<th>Final Amount</th>
					<th>Submitted To</th>
					<th>Status</th>
					
				</tr>
                </thead>
                <tbody>
	<?php
		
		$today_date = date('Y-m-d');
		$today_time = date('H:i:s');
		
		$sql="SELECT * from sma_tender_header where company_id in ( $comid ) ";
		$query="SELECT count(*) as num  from sma_tender_header where company_id in ( $comid ) ";
			
		if($user=='Admin' || $primaryrole =='COO' || $primaryrole == 'Director' ){
			$sql="SELECT * from sma_tender_header where 1 ";
			$query="SELECT count(*) as num  from sma_tender_header where 1 ";
		}
		
		if (!empty($comp_id)){
			$sql .= " and company_id = '$comp_id' ";
			$query .= " and company_id = '$comp_id' ";
		}
		
					if($searchf=='D'){
						$start_date = date('Y-m-d', strtotime($_SESSION['start_date']));
						$end_date = date('Y-m-d', strtotime($_SESSION['end_date']));
						$sql .= " and dated >= '$start_date' and dated <= '$end_date' ";
						$query .= " and dated >= '$start_date' and dated <= '$end_date' ";
					}
					if($searchf=='N'){
						$sql .= " and id = '$search_data' ";
						$query .= " and  id = '$search_data'  ";
					}
					
					if($searchf=='S'){
							$sql .= " and to_supplier = '$search_data'   ";
							$query .= " and to_supplier = '$search_data'  " ;
					}
					
					if($search_own=='Y'){
						$sql .= " and draft_by = '$user' ";
						$query .= " and  draft_by = '$user' ";
					}
		
					//Active / Deactive records
					if($searchf=='T'){
						$sql .= " and del = 'Y' ";
						$query .= " and del = 'Y' ";
					}
					else if($searchf=='B'){
						$sql .= "";
						$query .= " ";
					}
					 else { // Default
						$sql .= " and del != 'Y' ";
						$query .= " and del != 'Y' ";
					} 
										
					$qresult = mysqli_query($con,$query);
					echo mysqli_error($con);
					$total_pages = mysqli_fetch_array($qresult);
					$total_pages = $total_pages['num'];
					
					$stages = 3;
					
					$page = ($_GET['page']);
					if($page){
						$start = ($page - 1) * $limit; 
					}
					else{
						$start = 0;	
					}	

		$_SESSION['sqlex'] = $sql;		
					
		$sql .= ' order by id desc ';
		
		$sql .= " LIMIT $start, $limit ";
				
//echo $sql;

	// Initial page num setup
	if ($page == 0){$page = 1;}
	$prev = $page - 1;	
	$next = $page + 1;							
	$lastpage = ceil($total_pages/$limit);		
	$LastPagem1 = $lastpage - 1;					
	
	$paginate = '';
	//echo $lastpage;
	//echo $paginate;
	if($lastpage > 1)
	{	
		$paginate .= '<div style="float:right"><ul class="pagination pagination-lg">';
		// Previous
		if ($page > 1){
			$paginate.= "<li><a href='$targetpage&page=$prev'>previous</a></li>";
		}else{
			$paginate.= "<li class='disabled'><a>previous</a></li>";	}
			
		// Pages	
		if ($lastpage < 7 + ($stages * 2))	// Not enough pages to breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page){
					$paginate.= "<li class='active'><a>$counter</a></li></span>";
				}else{
					$paginate.= "<li><a href='$targetpage&page=$counter'>$counter</a></li>";}
			}
		}
		elseif($lastpage > 5 + ($stages * 2))	// Enough pages to hide a few?
		{
			// Beginning only hide later pages
			if($page < 1 + ($stages * 2))		
			{
				for ($counter = 1; $counter < 4 + ($stages * 2); $counter++)
				{
					if ($counter == $page){
						$paginate.= "<li class='active'><a>$counter</a></li>";
					}else{
						$paginate.= "<li><a href='$targetpage&page=$counter'>$counter</a></li>";}
				}
				$paginate.= "<li><a>...</a></li>";
				$paginate.= "<li><a href='$targetpage&page=$LastPagem1'>$LastPagem1</a></li>";
				$paginate.= "<li><a href='$targetpage&page=$lastpage'>$lastpage</a></li>";		
			}
			// Middle hide some front and some back
			elseif($lastpage - ($stages * 2) > $page && $page > ($stages * 2))
			{
				$paginate.= "<li><a href='$targetpage&page=1'>1</a></li>";
				$paginate.= "<li><a href='$targetpage&page=2'>2</a></li>";
				$paginate.= "<li><a>...</a></li>";
				for ($counter = $page - $stages; $counter <= $page + $stages; $counter++)
				{
					if ($counter == $page){
						$paginate.= "<li class='active'><a>$counter</a></li>";
					}else{
						$paginate.= "<li><a href='$targetpage&page=$counter'>$counter</a></li>";}					
				}
				$paginate.= "<li><a>...</a></li>";
				$paginate.= "<li><a href='$targetpage&page=$LastPagem1'>$LastPagem1</a></li>";
				$paginate.= "<li><a href='$targetpage&page=$lastpage'>$lastpage</a></li>";
			}
			// End only hide early pages
			else
			{
				$paginate.= "<li><a href='$targetpage&page=1'>1</a></li>";
				$paginate.= "<li><a href='$targetpage&page=2'>2</a></li>";
				$paginate.= "<li><a>...</a></li>";
				for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page){
						$paginate.= "<li class='active'><a>$counter</a></li>";
					}else{
						$paginate.= "<li><a href='$targetpage&page=$counter'>$counter</a></li>";}
				}
			}
		}
					
				// Next
		if ($page < $counter - 1){ 
			$paginate.= "<li><a href='$targetpage&page=$next'>next</a></li>";
		}else{
			$paginate.= "<li class='disabled'><a>next</a></li>";
			}
			
		$paginate.= "</ul></div>";
}
//end page
//echo $sql;
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		
	while($row = mysqli_fetch_array($result)){
		
		$company_id = $row['company_id'];
		$sql 	= "select * from company where comp_id = '$company_id' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$company_name 	= $r2['comp_name'];
		$comp_code		= $r2['comp_code'];
		
		$remarks		= $row['remarks'];
		$extend			= $row['extend'];
		
		$to_supplier = $row['to_supplier'];
		$sql 	= "select * from sma_party_mst where id = '$to_supplier' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$to_supplier = $r2['party_name'];
	
		$tender_hdr_id = $row['id'];
		$quote_sent_to = '0';
		$sql="SELECT * from sma_tender_supplier where tender_hdr_id = '$tender_hdr_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$quote_sent_to  = mysqli_affected_rows($con);
		$quote_sent_to  = '';
		while($rs2 	= mysqli_fetch_array($res1)){
			$supplier_id = $rs2['supplier_id'];	
			$sql 	= "select * from sma_party_mst where id = '$supplier_id' ";
			$q2 	= mysqli_query($con, $sql);
			$r2 	= mysqli_fetch_array($q2);
			$quote_sent_to  .= $r2['party_name'].'<br>';
		}	
		
		//$sql="SELECT distinct(supplier_id) FROM `sma_tender_supplier_quote` where tender_hdr_id = '$tender_hdr_id' and rate > 0 and supplier_id in  ( SELECT supplier_id from sma_tender_supplier where tender_hdr_id = '$tender_hdr_id' ) "; //and quotation_received = 'Y' 
		$sql = "SELECT distinct(supplier_id) from sma_tender_supplier where tender_hdr_id = '$tender_hdr_id' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$quotation_not_received  = mysqli_affected_rows($con); 
		
		$sql = "SELECT distinct(supplier_id) from sma_tender_supplier where tender_hdr_id = '$tender_hdr_id' and supplier_id in ( SELECT distinct(supplier_id) as supplier_id FROM `sma_tender_supplier_quote` where tender_hdr_id = '$tender_hdr_id' and rate > 0 ) ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$quotation_received  = mysqli_affected_rows($con); 
		
		$quotation_not_received = $quotation_not_received - $quotation_received ;	
		
		$to_supplier_received ='';
		$sql 	= "select * from sma_party_mst where id in ( SELECT distinct(supplier_id) as supplier_id FROM `sma_tender_supplier_quote` where tender_hdr_id = '$tender_hdr_id' and rate > 0 ) ";
if($tender_hdr_id==109){
//echo $sql. "<BR>";
}	
		$q2 	= mysqli_query($con, $sql);
		while($r2 	= mysqli_fetch_array($q2)){
			$to_supplier_received .= $r2['party_name'].'<BR>';
		}
		//$quotation_received = $to_supplier_received;
		
		$rid = $row['id'];
			
			$draft_by 			= $row['draft_by'];
			$current_approver 	= $row['current_approver'];
			$status 			= $row['status'];
			$status_r 			= $row['status'];

//echo $rid. ' ##1 ' .$status . ' ' . $today_date . ' ' . $deadline_date ."<BR>";

		$deadline_date 	= date('Y-m-d', strtotime($row['deadline_date']));	
		$deadline_date 	= $deadline_date. ' ' .$row['deadline_time'];
		$deadline_time 	= $row['deadline_time'];
 if($rid==51){
//	echo $deadline_time. ' ' .date('Y-m-d', strtotime($deadline_date)) .' < '. $today_date .' && '. $deadline_time .' < '. $today_time. ' ' .$status. "<BR>";
} 
		$stats='';//|| $quotation_received==0
		if( ($status=='Received'  && $status!='Draft' ) || $status=='Completed' ){
			if( (date('Y-m-d', strtotime($deadline_date)) < $today_date ) || (date('Y-m-d', strtotime($deadline_date)) <= $today_date && $deadline_time < $today_time) ){
				$stats = 'Otp';
		 			 
				//if($quotation_received==0 ){
					$status = 'Expired';
				//}
				
			}
			else {
				$stats	= 'NOP';
			}
			
		}
		else if($status !='Completed' && $status !='Opened' && $status!='Draft'){
			if( (date('Y-m-d', strtotime($deadline_date)) < $today_date ) || (date('Y-m-d', strtotime($deadline_date)) <= $today_date && $deadline_time < $today_time ) ){
				$status = 'Expired';
				$stats = 'Otp';
			}
			
		}
			
			
			$sql="SELECT * from sma_user where id = '$current_approver' ";
//echo $sql."<BR>";			
			$res1 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r1 = mysqli_fetch_array($res1);
			$changed_by 	= $r1['username'];
			
			$sql="SELECT * from sma_user where userid = '$draft_by' ";
//echo $sql."<BR>";			
			$res1 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r1 = mysqli_fetch_array($res1);
			$draft_by 	= $r1['username'];
			
		
		if($status =='Completed'){
			$status = 'Approved for Publish';
			$stats	= "";
		}
		
		$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$row["id"];
				//|| $status=='Published'
//echo $stats	. ' ### ' . $status	. "<>";
		if($status=='Published'){
			
		}
		
		if($quotation_not_received>0 && $status=='Opened'){
			$status='Opened';
		}	

		$sel_party_name   = '';
		$selected_amount  = '';
		$sql = " select * from sma_tender_supplier_quote where tender_hdr_id = '$tender_hdr_id' ";
		$q2 	= mysqli_query($con, $sql);
		while($r2 = mysqli_fetch_array($q2)){
								
			$selected_vendor = $r2['selected_vendor'];
			if($selected_vendor == 'Y'){
				$sel_party_name  = $r2['supplier_id'];
				$selected_amount = $r2['selected_amount'];
				$selected_remarks= $r2['selected_remarks'];
				
				$sql 	= "select * from sma_party_mst where id = '$sel_party_name' ";
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$sel_party_name  = $r2['party_name'].'<br>';
			}
		}


/*  if($rid==67){		
	echo $stats	. ' ### ' . $status	. "<>". $quotation_received. "<BR>";
} */

		$tender_id = $row['id'];
		if($status_r =='Rejected'){
			$stats  = '';
			$status = $status_r;
				
		}
		
		if($stats=='Otp' || $stats=='NOP' || ( $quotation_not_received>0 && $status !='Opened' && $status !='Draft' && $status !='Rejected' && $status != 'Approved for Publish' && $status!='Published')  ){
			
			echo "<tr>";
			
		}
		else {
			
			
	?>		
		
	<a href="<?php echo $baseurl . $modulePath . "edit.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; <?php echo $backcolor?> " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
<?php } ?>		
		<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>"></td>
		<td width="5%"><?php echo $row['id'];?></td>
		<td width="10%"><?php echo $row['tender_title'];?></td>
		<td width="10%" <?php echo $styl; ?>><?php echo $comp_code;?></td>
		<td width="10%" <?php echo $styl; ?>><?php echo date('d-m-Y', strtotime($row['created_date']));?></td>
		<td width="10%" <?php echo $styl; ?>><?php echo date('d-m-Y H:i:s', strtotime($deadline_date));?></td>
		<td width="20%" style="text-align:left; " ><?= $quote_sent_to; ?></td>
		<td width="20%" style="text-align:left; " ><?= $to_supplier_received; ?></td>
		<td width="10%" <?php echo $styl; ?>><?php echo $sel_party_name;?>
		<td width="10%" <?php echo $styl; ?>><?php echo $selected_amount;?>
		<td width="10%" <?php echo $styl; ?>><?php echo $changed_by;?>
		<td width="10%" <?php echo $styl; ?>><?php echo $status;?>
			
	<?php if($stats=='Otp' ){
	
			if($quotation_received>0 ){ //&& $status!='Expired'
	?>			
			<span class="pull-right">
			<a href="<?php echo $baseurl . $modulePath . "tender_send_otp.php?sub=send&tender_id=".$row['id'];?>" class="btn btn-primary" target="_blank" >Send OTP</a>
			</span>
		<?php } ?>	
		
			<span class="pull-right">
			<a href="<?php echo $baseurl . $modulePath . "tender_extend.php?sub=Extend&tender_id=".$tender_hdr_id;?>" class="btn btn-success" target="_blank" >Extend</a>&nbsp;
			</span>
	<?php }
		
//		if($quotation_not_received>0 && $status !='Opened' && $status !='Draft' && $stats!='Otp' && $status!='Submitted' ){
		
	?>		
				
	<!--		<span class="pull-right">
			<a href="<?php echo $baseurl . $modulePath . "tn_vender_resend_mail.php?sub=resend&tender_id=".$row['id'];?>" class="btn btn-primary" target="_blank" onclick="return confirm('Do you want to resend email to vendor?');" >ReSend Mail </a>
			</span>
		-->	
	<?php //} 
	?>
			
		</td>
		
    </tr>
	</a>
				<?php } ?>
				
                </tbody>
                <tfoot>
                
                </tfoot>
              </table>
			  
<?php
  $end  =$start+$limit;
  $begin=$start+1;
  
  if ($end>$total_pages){ $end=$total_pages;}
  echo 'Showing ' . $begin .' to ' . $end . ' of ' . $total_pages . ' entries ';
  echo $paginate;
?>
			  
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>

  <!-- /.content-wrapper -->


<!-- Terms Start -->
<div class="modal fade" id="modalAddExtend<?= $tender_hdr_id;?>" role="dialog" aria-labelledby="modalAddTermsLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalAddExtendLabel">Extend</h4>
            </div>
			
            <div class="modal-body">
                <section class="content">
                    <div class="row">
                        <form class="form-horizontal" id="saveForm123" action="index.php?sub=Extend" method="POST">

							<input type="text" name="tender_hdr_id" value=<?php echo $tender_hdr_id; ?>>
                            
                            <div class="form-group ">
                                <div class="col-md-2">
										<label class="control-label"> Deadline Date</label>
										<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<div class="input-group-addon">
											<i class="fa fa-calendar-alt"></i>
										</div>
										<input type="text" class="form-control" <?= $readonlya; ?> id="deadline_date" name="deadline_date" placeholder="dd-mm-yyyy" value="<?= $deadline_date;?>">
										</div>
									</div>
									
									<div class="col-md-2">
									<div class="bootstrap-timepicker">
										<label>Time </label>
										<div class="input-group">
											<input type="time" class="form-control timepicker123" id="deadline_time" name="deadline_time" <?= $readonlya; ?> value="<?= $row['deadline_time'];?>" >

										<!--	<div class="input-group-addon">
											  <i class="fa fa-clock-o"></i>
											</div>-->
										</div>
									</div>
								</div>
								
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-primary" id='saveFormT' >Save</button>
							</div>
                        </form>
                    </div>
                </section>
            </div>

            </div>
        </div>
    </div>
</div>
<!-- Terms End -->	  
	  
	  
</div>
<!-- ./wrapper -->



	  
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

  $(function () {
        $("#prtablea").DataTable();
		$("#prtableb").DataTable();
    });

function getsearchf(id){
    var sub = 'sub1';
//alert(id);

//	var searchf = document.getElementById("searchf").value;
//alert(searchf);	
	var strURL = "search_func.php";
	$.post(strURL,{ sub1:sub,id:id},function(result){
			  $('#getsearchf').html(result);
		});
}
		
</script>

</body>
</html>

