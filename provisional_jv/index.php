<?php
include("../header.php");
$modulePath = "provisional_jv/";

$userid   	= $_SESSION['usrid'];


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
        Provisional Journal <small>List</small>
		<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
		</small>
      </h1>
      <ol class="breadcrumb">
		
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Provisional Journal List</li>
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
				$limit = 10; 
				$start = 0;	
				
				if ($_POST['comp_id'] or $_POST['status']  or $_POST['start_date'] ){
					$_SESSION['comp_id'] = $_POST['comp_id'];
					$_SESSION['statuss'] = $_POST['status'];
					$_SESSION['start_date'] = $_POST['start_date'];
					
				}
				
				if ( $_SESSION['comp_id'] or $_SESSION['approval_status'] or $_SESSION['statuss'] or $_SESSION['start_date'] or $_SESSION['search_own'] ){
					$comp_id 		= $_SESSION['comp_id'];
					$status 		= $_SESSION['statuss'];
					$start_date 	= $_SESSION['start_date'];
					$_SESSION['reset']='';
				}
				
				if (!empty($_GET['reset']) || !empty($_SESSION['reset'])){
					$_SESSION['comp_id'] = '';
					$_SESSION['statuss'] = '';
					$_SESSION['start_date'] = '';
					$comp_id = $_SESSION['comp_id'];
					$status = $_SESSION['statuss'];
					$start_date = $_SESSION['start_date'];
					$_SESSION['Createdby_ap'] ='';
				}
				
			?>
			
					<form class="form-horizontal" action="index.php?sub=list" method="post">
                      
						<div class="form-group">
								
								<div class="col-md-4">
									<label class=" control-label">Company</label>
									<select class="form-control select2" name="comp_id" id="comp_id" onchange="getprovName(this.value);" >
										<option value=""> Select </option>
											<?php $sql = "select * from company order by comp_name ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['comp_id'];?>" <?php echo ($comp_id == $r2['comp_id'])?'selected="selected"':'';?>  ><?php echo $r2['comp_name'];?></option>
											<?php } ?>
									</select>
								</div>
								
								<div class="col-md-2">
									<label for="reqDate" class=" control-label">Status</label>
									<select class="form-control select2" name="status" id="status" >
										<option value=""> Select </option>
										<option value="Draft" <?php echo ($status == 'Draft')?'selected="selected"':'';?> > Draft </option>
										<option value="Submitted" <?php echo ($status == 'Submitted')?'selected="selected"':'';?>> Submitted </option>
										<option value="Completed" <?php echo ($status == 'Completed')?'selected="selected"':'';?>> Completed </option>
										<option value="Rejected" <?php echo ($status == 'Rejected')?'selected="selected"':'';?>> Rejected </option>
									</select>
								</div>

							
								<div class="col-md-2">
									<label class=" control-label">For the Month /Year</label>
								
									<div class="input-group date" data-provide="datepicker" data-date-format="mm-yyyy">
										<input type="text" class="form-control" id="start_date" name="start_date" autocomplete="off" value="<?php echo $start_date;?>" > 
										<div class="input-group-addon">
												<i class="fa fa-calendar-alt"></i>
										</div>
									</div>
								</div>
								
							<div class="col-md-2">
                                <label class=" control-label">&nbsp;</label><br>
								<input class="btn btn-success" type="submit" value="Search" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="index.php?sub=list&reset=1" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
							</div>
							
							<div class="col-md-2">
								<label class=" control-label">&nbsp;</label><br>
								<span class="pull-right"><a href="<?php echo $baseurl . $modulePath . "add.php"?>" class="btn btn-primary">Create </a> &nbsp;&nbsp;&nbsp;&nbsp; </span> 
					
								<span class="pull-right"><a href="<?php echo $baseurl . $modulePath . "provisional_jv_export_func.php?sub=pdf"?>" target="_blank" class="btn btn-primary">Export</a>&nbsp;&nbsp;&nbsp;&nbsp; </span>
							</div>
							
					</div>
					
					<div id="getprovName">
							
					</div>
						
				</form>

			
		   </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="prtable123" class="table table-bordered table-striped">
                <thead>
                <tr >
                    <th></th>
					<th>Sr.No.</th>
					<th>Company</th>
                    <th>Prepared On</th>
					<th>First Account</th>
					<th style="text-align:right;">Amount</th>
					<th>By</th>
					
					<th>Status</th>
					<th>Decision</th>				
					<th>Action</th>
				</tr>
                </thead>
                <tbody>
				<?php
					//$sql="SELECT * from sma_provisional_jv_hdr order by id desc";
					
					$role			= $_SESSION['role'];
					$readonly		= $_SESSION['readonly'];
					$user_category	= $_SESSION['user_category'];

	$sql = "select * from sma_role where id = '$primary_role' ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	$row = mysqli_fetch_array($result);
	$primaryrole = $row['role'];
					
					$sql	="SELECT * from sma_provisional_jv_hdr where company_id in ( $comid ) and (status = 'Draft' or status = 'Submitted' or status = 'Completed' or approval_status = 'Rejected'  )  ";
					$query  ="SELECT count(*) as num from sma_provisional_jv_hdr where company_id in ( $comid ) and ( status = 'Draft' or status = 'Submitted' or status = 'Completed' or approval_status = 'Rejected'  )   ";
					
//echo $sql. ' ' . $readonly[2]. ' '. $role;					
					if ($comp_id){
						$sql .= " and company_id = '$comp_id' ";
						$query .= " and company_id = '$comp_id' ";
					}
					if ($status){
						$sql .= " and status = '$status' ";
						$query .= " and status = '$status' ";
					}
					
					if(!empty($start_date)){
						$sql .= " and mm_yyyy = '$start_date' ";
						$query .= " and mm_yyyy = '$start_date' ";
					}
					
					$sql .= " AND del != 'Y' ";
					$query .= " AND del != 'Y' ";
					
					$_SESSION['sqlex'] = $sql;
			
			//echo $query."<BR>";
					$qresult = mysqli_query($con,$query);
					echo mysqli_error($con);
					$total_pages = mysqli_fetch_array($qresult);
					//$total_pages = mysqli_fetch_array(mysqli_query($con,$query));
					$total_pages = $total_pages['num'];
			//echo $total_pages. ' <<<>>';		
					
					$stages = 3;
					//$page = mysqli_real_escape_string($_GET['page']);
					
					$page = ($_GET['page']);
					if($_GET['same_page']){
						$page = $_GET['same_page'];
					}

					if($page){
						$start = ($page - 1) * $limit; 
					}
					else{
						$start = 0;	
					}						
						
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
						
			$rid 				= $row['id'];
						
			$company = $row['company_id'];
			$sql = "select * from company where comp_id = '$company' ";
			$q2  = mysqli_query($con, $sql);
			$r2 = mysqli_fetch_array($q2);
			$company_name  	= $r2['comp_name'];
			$company  		= $r2['comp_code'];

						
			$draft_by = $row['draft_by'];
				
				$changed_by = $row['changed_by'];
				if(empty($changed_by)){
					$changed_by = $draft_by;
				}	
				
				$sql = "select * from sma_user where userid = '$changed_by' ";
				$q2  = mysqli_query($con, $sql);
				$r2 = mysqli_fetch_array($q2);
				$changed_by  = $r2['username'];
				
				$approver_1			= $row['approver_1'];
				$approver_2			= $row['approver_2'];
				$approver_3			= $row['approver_3'];
				$approver_4			= $row['approver_4'];
				$approver_5			= $row['approver_5'];
				$approver_6			= $row['approver_6'];
				$approver_7			= $row['approver_7'];
				$approver_8			= $row['approver_8'];
				
				$approver_1_status	= $row['approver_1_status'];
				$approver_2_status	= $row['approver_2_status'];
				$approver_3_status	= $row['approver_3_status'];
				$approver_4_status	= $row['approver_4_status'];
				$approver_5_status	= $row['approver_5_status'];
				$approver_6_status	= $row['approver_6_status'];
				$approver_7_status	= $row['approver_7_status'];
				$approver_8_status	= $row['approver_8_status'];
				
				if($approver_1_status == 'Submitted'){
					$pending_by  = $approver_1;	
				}
				if($approver_2_status == 'Submitted'){
					$pending_by  = $approver_2;	
				}
				if($approver_3_status == 'Submitted'){
					$pending_by  = $approver_3;	
				}
				if($approver_4_status == 'Submitted'){
					$pending_by  = $approver_4;	
				}
				if($approver_5_status == 'Submitted'){
					$pending_by  = $approver_5;	
				}
				if($approver_6_status == 'Submitted'){
					$pending_by  = $approver_6;	
				}
				if($approver_7_status == 'Submitted'){
					$pending_by  = $approver_7;	
				}
				if($approver_8_status == 'Submitted'){
					$pending_by  = $approver_8;	
				}
				$sql = "select * from sma_user where id = '$pending_by' ";
				$q2  = mysqli_query($con, $sql);
				$r2 = mysqli_fetch_array($q2);
				$pending_by  = $r2['username'];
				
				
				$dated = date('d-m-Y', strtotime($row['dated']));
				if($dated =='01-01-1970'){
					$dated ='';
				}	
				
				$pv_id = $row['id'];
				$sql = "select sum(amount) as amount from sma_provisional_jv_details where provisional_jv_hdr_id = '$pv_id' order by id ";
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$amount  		= $r2['amount'];
				
				$sql = "select * from sma_provisional_jv_details where provisional_jv_hdr_id = '$pv_id' order by id ";
				$q2 	= mysqli_query($con, $sql);
				$r2 	= mysqli_fetch_array($q2);
				$account_id  		= $r2['account_id'];
				
				//$sql="SELECT * FROM sma_budget where id  = '$account_id' ";
				$sql="SELECT * FROM sma_product where id  = '$account_id' ";				
				$qry = mysqli_query($con, $sql);
				echo mysqli_error($con);
				$r2 = mysqli_fetch_array($qry);
				$account_name		= $r2['name'];
											
				$j=$j+1;				

				$status = $row['status'];		
						
				$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$row["id"].'&page='.$page;
				
		?>
	<a href="<?php echo $baseurl . $modulePath . "edit.php?sub=edit&id=". $row['id']?>&page=<?= $page;?>" title="Edit">
	<tr style="cursor:pointer; <?php echo $backcolor?> " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'" <?php echo $styl; ?> >
	
					<td width="1%" ><input type="hidden" value="<?php echo $j;?>" > </td>
					<td width="4%" <?php echo $styl; ?> ><?php echo $row['id'];?></td>
					<td width="10%" <?php echo $styl; ?>><?php echo $company;?></td>
					<td width="12%" <?php echo $styl; ?>><?php echo $dated;?></td>
					
					<td width="25%" <?php echo $styl; ?>><?php echo $account_name;?></td>
					<td width="10%"  style="text-align:right;<?php echo $styl2 ?>" <?php echo $styl; ?> ><?php echo moneyFormatIndiaa($amount);?></td>
					<td width="10%" <?php echo $styl; ?>><?php echo $changed_by;?></td>
					
					<td width="08%" <?php echo $styl; ?>><?php echo $row['status'];?></td>
					<td width="08%" <?php echo $styl; ?>><?php echo $row['approval_status'];?></td>
					
<!--					<td width="5%" style="text-align:right;" >
						<a href="<?php echo $baseurl . $modulePath . "edit.php?sub=edit&id=". $row['id']?>"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
						
						<a href='#modalHistoryItem' data-id='<?php echo $rid;?>' data-mode='edit' data-toggle='modal' data-target='#modalHistoryItem<?php echo $rid;?>' title="History" > <i class='fa fa-history' ></i></a>
						<?php include "view_history.php"; ?>
						<a href="approval_notes_prn.php?sub=pdf&id=<?php echo $row['id'];?>&comp_id=<?php echo $row['company'];?>&r=1" name="PDF" title="PDF" target="_blank"><i class="fa fa-print"></i></a>

					</td>-->
						<td width="10%" style="text-align:center;">
						<a href="<?php echo $baseurl . $modulePath . "edit.php?sub=edit&id=". $row['id']?>&page=<?= $page;?>" name="btnEdit" title="Edit" placeholder="top center" ><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
				<?php if($status == 'Completed' ){ ?>		
						<a href="copy_pv_jv_.php?sub=copy&pv_id=<?php echo $row['id'];?>" title="Copy" onclick="return confirm('Are you sure you want to copy?');"><i class="fa fa-copy"></i></a>
				<?php } ?>
		
						</td>
				
				</tr>
		</a>		
				<?php } ?>
				
                
                </tbody>
                <tfoot>
                
                </tfoot>
              </table>			  

<?php
  $end  =$start+10;
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

  <!-- Modal Add Item-->
<div class="modal fade" id="modalExport" role="dialog" aria-labelledby="modalExportLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalExportLabel">Export Provisional Journal data</h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                    <div class="row123">
                        <form class="form-horizontal" action="ap_export_func.php?sub=pdf" target="_blank" method="POST" >
                            <input type="hidden" id="mode" value='Export'>
                            <input type="hidden" id="tempId">
<!--							<input type="hidden" id="purchaseId" value="<?php echo $_GET['id'];?>">-->
							
							<div class="form-group">
                                
								<div class="col-sm-4">
									<label class="control-label">From Date</label>
							        <div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy" required="required">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                        <input type="text" class="form-control" id="fromDate" name="from_date" required="required" >
									</div>
								</div>
								
								<div class="col-sm-4">
									<label class="control-label">To Date</label>
							        <div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                        <input type="text" class="form-control" id="toDate" name="to_date" >
									</div>
								</div>
							</div>
								
							<div class="form-group">
                                <div class="col-sm-12">
									<label for="itemCategory" class="control-label"> Company</label>
									<select class="form-control" name="company_id" id="companyId" >
									<option value=""> Select </option>
									<option value=""> All </option>
										<?php $sql = "select * from company where comp_id in ( $comid )order by comp_name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" > <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>
								</div>
                            </div>
                            
							<div class="form-group">
                                
								<div class="col-sm-6">
                                <label class="control-label">Department</label>
									<select class="form-control" name="department" id="departMent" <?php echo $readonly; ?> >
										<option value=""> Select </option>
										<option value=""> All </option>
											<?php $sql = "select * from sma_department order by name ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['id'];?>" ><?php echo $r2['name'];?></option>
											<?php } ?>
									</select>
                                </div>
                            </div>
							
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" name="submit" id="exportItem12" onclick="exportItem123()" value="Submit">
            </div>                
                        </form>
                    </div>
                </section>
            </div>
            
        </div>
    </div>
</div>
<!-- Modal Add Item-->

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


	
function getprovName(id){
    var sub = 'sub1';
//alert(id);
	var strURL = "app_func.php";
	$.post(strURL,{ sub1:sub,company_id:id},function(result){
			  $('#getprovName').html(result);
		});
}
	
</script>

</body>
</html>

