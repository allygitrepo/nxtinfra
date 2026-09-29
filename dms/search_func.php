<?php 
	
	session_start();
	include('../dbcon.php');
	include('../baseurl.php');
	
	$modulePath = "dms/";
	
	$userid   	    = $_SESSION['usrid'];
	$user   	    = $_SESSION['user'];
	$role   	    = $_SESSION['role'];
	
?>

<?php
	
    if(isset($_POST['sub1'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$searchf = $_POST['id'];
		
		if($searchf=='U' ){
			$value = '';
			$value .='<div class="col-md-3">';
			//$value .='<input type="text" class="form-control" id="search_data" name="search_data" value="" autocomplete="off" >';
			$value .= '<select class="form-control select2" name="search_data">';
				$sql = "select * from sma_user order by username ";
				$q2 	= mysqli_query($con, $sql);
										
			$value .='<option value=""> Select </option>';
					while($r2 = mysqli_fetch_array($q2)){ 	
			$value .='<option value="'. $r2['id'].'">'.$r2['username'].'</option>';
					}
			$value .='</select>';
			$value .= "</div>";	
			
		}
		else if($searchf=='T'){
?>
				<div class="col-md-3">
					<select class="form-control col-sm-2 " id="search_data" name="search_data" >
					<option value=""  >Select</option>
					<option value="D" <?php echo ($search_data == 'D' )?'selected="selected"':'';?> >Digital</option>
					<option value="P" <?php echo ($search_data == 'P' )?'selected="selected"':'';?> >Physical</option>
					<option value="B" <?php echo ($search_data == 'B' )?'selected="selected"':'';?> >Both</option>
					</select>
				</div>
								
<?php		
		}
		else if($searchf=='S'){
			$value = '';
			$value .='<div class="col-md-3">';
			//$value .='<input type="text" class="form-control" id="search_data" name="search_data" value="" autocomplete="off" >';
			$value .= '<select class="form-control select2" name="search_data">';
				$sql = "select * from sma_party_mst order by party_name ";
				$q2 	= mysqli_query($con, $sql);
										
			$value .='<option value=""> Select </option>';
					while($r2 = mysqli_fetch_array($q2)){ 	
			$value .='<option value="'. $r2['id'].'">'.$r2['party_name'].'</option>';
					}
			$value .='</select>';
			$value .= "</div>";	
			
		}
		else if($searchf=='N' || $searchf=='O' || $searchf=='T' || $searchf=='P' || $searchf=='F' ){
			$value = '';
			$value .='<div class="col-md-4">';
			$value .='<input type="text" class="form-control" id="search_data" name="search_data" value="" autocomplete="off" >';
			$value .= "</div>";	
		}
		else if($searchf=='D'){
			$value = '<label class="col-lg-1 control-label">From</label>
				<div class="col-md-2">
					<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
						<input type="text" class="form-control" id="start_date" name="start_date" autocomplete="off" value="" > 
						<div class="input-group-addon">
								<i class="fa fa-calendar-alt"></i>
						</div>
					</div>
				</div>';
			$value .= '<label class="col-lg-1 control-label">To</label>
				<div class="col-md-2">
					<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
						<input type="text" class="form-control" id="end_date" name="end_date" autocomplete="off" value="" > 
						<div class="input-group-addon">
								<i class="fa fa-calendar-alt"></i>
						</div>
					</div>
				</div>';	
		}					
//$value=$sql;

        echo $value;
    }
	
    if(isset($_POST['sub2'])){
	
		$checked = 'CHECKED';
		
		$sql = $_SESSION['sqls'];
		
		$targetpage = "document_search.php?sub=list"; 
		$limit 		= 10; 
		$start 		= 0;	
	
		$qresult = mysqli_query($con,$sql);
					echo mysqli_error($con);
					$total_pages = mysqli_affected_rows($con);
	
					$stages = 3;
					
					$page = ($_GET['page']);
					if($page){
						$start = ($page - 1) * $limit; 
					}
					else{
						$start = 0;	
					}	
					
					$sql .= ' order by inward_no desc ';
					//$sql .= " LIMIT $start, $limit ";
					
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
//echo $sql. "<BR>";;
			
			$result = mysqli_query($con, $sql);
			echo mysqli_error($con);
?>	
		
			<!--<div style="height:350px;overflow:scroll;border:1px #999;">-->
				<table id="prtable123" class="table table-bordered table-striped">
		<?php
				while($row = mysqli_fetch_array($result)){
						
						$status    = $row['status'];
						$inward_no = $row['inward_no'];
						
						$company   = $row['company_for'];
						$date_of_received = date('d-m-Y h:i:s', strtotime($row['date_of_received']));
						$outward_no = $row['outward_no'];
						
						$sql = "select * from company where comp_id = '$company' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$company  = $r2['comp_name'];

						$department = $row['department_for'];
						$sql = "select * from sma_department where id = '$department' ";
						$q2  = mysqli_query($con, $sql);
						$r2  = mysqli_fetch_array($q2);
						$department  = $r2['name'];						
						
						$mode_of_receipt = $row['mode_of_receipt'];
						if($mode_of_receipt=='C'){
							$mode_of_receipt = 'Courier';
						}
						else if($mode_of_receipt=='H'){
							$mode_of_receipt = 'Hand Delivery';
						}
						else if($mode_of_receipt=='E'){
							$mode_of_receipt = 'Email';
						}
						else if($mode_of_receipt=='S'){
							$mode_of_receipt = 'Self';
						}
			
				$sql = " INSERT into forward_share_doc ( userid, dated, doc_id, doc_type, status, flag ) 
						values ( '$userid', now(), '$inward_no', 'IN', '',  'Y') ";
				mysqli_query($con, $sql);
			
				$baseurl1 = $baseurl.$modulePath.'document_search.php?sub=edit&inward_no='.$inward_no;

		?>
	<!--<a href="<?php echo $baseurl . $modulePath . "my_document.php?sub=edit&inward_no=". $inward_no?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">-->
			<tr>
			
					<td width="0%"><input type="hidden" value="<?php echo $inward_no;?>" > </td>
					<td width="10%" <?php echo $styl; ?> ><?php echo $date_of_received;?></td>
					<td width="10%" <?php echo $styl; ?>><?php echo $sent_by;?></td>
					<td width="29%" <?php echo $styl; ?>><?php echo $company;?></td>
					<td width="10%" <?php echo $styl; ?>><?php echo $department;?></td>
					<td width="10%"<?php echo $styl; ?>><?php echo $send_to;?></td>
					<td width="4%" style="text-align:right;<?php echo $styl2; ?>" ><?php echo $inward_no;?></td>
					<td width="10%"<?php echo $styl; ?>><?php echo $mode_of_receipt;?></td>
					<td width="08%"<?php echo $styl; ?>><?php echo $status;?></td>
					
					<td width="08%"<?php echo $styl; ?>>
						<input type="checkbox" name="forward_check[]" <?php echo $checked; ?> id="forward_check" value="<?php echo $inward_no; ?>" onclick="getchecked(this.value)" >
					&nbsp;&nbsp;
						<a href="<?php echo $baseurl . $modulePath . "my_document.php?sub=edit&inward_no=". $inward_no?>" title='Edit' ><i class="fa fa-fw fa-edit"></i> </a>
					</td>
					
				</tr>
		<!--</a>-->
				<?php } ?>
				
                </tbody>
                <tfoot>
                
                </tfoot>
              </table>	
<?php			  
				
		//$value = 'Hello World';
        //echo $value;
    }
	
    if(isset($_POST['sub3'])){
		
		$targetpage = "inbox_scr.php?sub=list"; 
		$limit = 10; 
		$start = 0;	
				
?>

		<table id="prtable123" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th></th>
					<th>Inward Date</th>
					<th>Sent By</th>
					<th>For Company</th>
					<th>For Department</th>
					<th>Sent To</th>
					<th>Document No.</th>
					<th>Mode </th>
					<th>Status</th>
					<th>Location</th>
					<!--<th style="text-align:right;">Action</th>-->
				
				</tr>
                </thead>
                <tbody>

<?php
		include "pagenate.php";
		
		$sql = $_SESSION['sqlex'].' order by doc_id desc ';
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($row = mysqli_fetch_array($result)){
			
			$inward_no = $row['doc_id'];
			$sql	= "SELECT * from dms_inward where inward_no = '$inward_no' ";
			$res 	= mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r1 	= mysqli_fetch_array($res);
			$company 	= $r1['company_for'];
			$draft_by 	= $r1['draft_by'];	
			$checked = '';
			if( $draft_by != $user || $role == 'Filing' ){
				$checked = "CHECKED";
			
				$sql = " INSERT into forward_share_doc ( userid, dated, doc_id, doc_type, status, flag ) values ( '$userid', now(), '$inward_no', 'IN', '',  'Y' ); ";
				mysqli_query($con, $sql);
				
			}
			
						$status  = $row['status'];
				//echo $status. "<BR>";
						$sent_by = $row['create_by'];
						$sql = "SELECT * FROM `sma_user` where id = '$sent_by' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$sent_by  = $r2['username'];
						
						$sent_to = $row['reviewed_by'];
						$sql = "SELECT * FROM `sma_user` where id = '$sent_to' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$send_to  = $r2['username'];
						
						if( $status =='Sent' ){
							$status = $r1['status'];
						}
				
						$date_of_received = date('d-m-Y h:i:s', strtotime($r1['date_of_received']));
						$outward_no = $r1['outward_no'];
						
						$sql = "select * from company where comp_id = '$company' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$company  = $r2['comp_name'];

						$department = $r1['department_for'];
						$sql = "select * from sma_department where id = '$department' ";
						$q2  = mysqli_query($con, $sql);
						$r2 = mysqli_fetch_array($q2);
						$department  = $r2['name'];
						
						
						$mode_of_receipt = $r1['mode_of_receipt'];
						if($mode_of_receipt=='C'){
							$mode_of_receipt = 'Courier';
						}
						else if($mode_of_receipt=='H'){
							$mode_of_receipt = 'Hand Delivery';
						}
						else if($mode_of_receipt=='E'){
							$mode_of_receipt = 'Email';
						}
						else if($mode_of_receipt=='S'){
							$mode_of_receipt = 'Self';
						}

				$styl="";
				$styl2="";
				//$status	='';
				//echo $status."<<>>";
				$approval_status = $row['approval_status'];
				if( $outward_no>0 && $status =='Sent' ){
					$styl	= "style='background-color: #D6E0F8;'";
					$styl2	= "background-color: #D6E0F8;";
					$status	= 'Not Received';
					$approval_status = 'Branch Sent';
				}
				
				if( $status =='Accepted' ){
					$status = 'My Document';
				}
				else if( $status =='Sent' ){
					$status = 'Received';
				}
				
				$j = $j+1;						
				
				//$date_of_received = date('d-m-Y', strtotime($row['create_date']));
				
				$baseurl1 = $baseurl.$modulePath.'inbox_scr.php?sub=edit&inward_no='.$inward_no;
				
				$styll	= "style='background-color: #81DAF5;'";
				$styl22	= "background-color: #81DAF5;";
			
			
		?>
		<!--<a href="<?php echo $baseurl . $modulePath . "inbox_scr.php?sub=edit&inward_no=". $inward_no?>" title="Edit">
			<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">-->
		<tr>	
					<td width="1%"><input type="hidden" value="<?php echo $rid;?>" > </td>
					<td width="10%" <?php echo $styl; ?> ><?php echo $date_of_received;?></td>
					<td width="10%" <?php echo $styl; ?>><?php echo $sent_by;?></td>
					<td width="29%" <?php echo $styl; ?>><?php echo $company;?></td>
					<td width="10%" <?php echo $styl; ?>><?php echo $department;?></td>
					<td width="10%"<?php echo $styl; ?>><?php echo $send_to;?></td>
					<td width="4%" style="text-align:right;<?php echo $styl2; ?>" ><?php echo $inward_no;?></td>
					<td width="10%"<?php echo $styl; ?>><?php echo $mode_of_receipt;?></td>
					<td width="08%"<?php echo $styl; ?>><?php echo $status;?></td>
					<td width="08%"<?php echo $styl; ?>>
						<input type="checkbox" name="forward_checked[]" <?php echo $checked; ?> id="forward_checked<?php echo $inward_no;?>" value="<?php echo $inward_no; ?>" onclick="getcheckedA(this.value)" >
					&nbsp;&nbsp;
						<a href="<?php echo $baseurl . $modulePath . "inbox_scr.php?sub=edit&inward_no=". $inward_no?>" title='Edit' ><i class="fa fa-fw fa-edit"></i> </a>
					</td>
			</tr>
		</a>		
				
				
<?php                
                		
		}
					
?>
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
		<span id="predit"> </span>
		<span class="pull-right">
			<a href="#sendAuthority" class="btn btn-success" data-toggle="modal" data-mode="Approve" data-target="#sendAuthority">Forward </a><span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
								
			<a href="#approvalAuthority" class="btn btn-info" data-toggle="modal" data-mode="Approve" data-target="#approvalAuthority">My Document </a><span>&nbsp;&nbsp;&nbsp;&nbsp;
		</span>
		
<!--Approval Workflow Popup-->

<div class="modal fade" id="approvalAuthority" role="dialog" aria-labelledby="approvalAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="approvalAuthority">Accept </h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
										//	$inward_no 		= $_SESSION['inward_no'];
											$status 		= $_SESSION['status'];
											$userid   	    = $_SESSION['usrid'];
											
										?>
										
										<input type="hidden" name="inward_no" id="inward_noA" value="<?php echo $inward_no; ?>" >
										<input type="hidden" id="modeA" name="mode" value='Approve'>
										<input type="hidden" id="statusA" name="status" value='<?php echo $status ?>' >
										
										<input type="hidden" id="approverA" name="approver" value='<?php echo $userid ?>' >
										
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksA"></textarea>
											</div>
										</div>
										
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitApprove">Accept</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Approval Workflow Popup End -->


<!--Send Workflow Popup-->

<div class="modal fade" id="sendAuthority" role="dialog" aria-labelledby="sendAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="sendAuthority">Forward To</h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                        <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$inward_no 	= $_SESSION['inward_no'];
											$status 	= $_SESSION['status'];
										?>
										
										<input type="hidden" name="inward_no" id="ap_idS" value="<?php echo $inward_no; ?>" >
										<input type="hidden" id="modeS" name="mode" value='Forwarded'>
										
										<input type="hidden" id="statusS" name="status" value='<?php echo $status; ?>' >
										
										<div class="form-group">
											<label class=" col-sm-2 control-label">Send To</label> <?php // multiple="multiple" ?>
											<div class="col-sm-10">
												<select class="form-control"  name="send_to" id="send_toS" <?php echo $readonly; ?> >
													<option value="0">Select</option>
													<?php
														$sql="SELECT * FROM sma_user where active != '0' ORDER BY username ASC";
														$rs = mysqli_query($con, $sql);
														echo mysqli_error($con);
														while($rw = mysqli_fetch_array($rs)){
													?>
														<option value="<?php echo $rw['id']?>" <?php echo ($rw['id']==$send_to)?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
														<?php } ?>	
												</select>
											</div>
										</div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksS"></textarea>
											</div>
										</div>
									
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitSend">Forward</button>
							</div>
			
                          </div>
						  
                        </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Forward Workflow Popup End -->	
	 
<?php									
	}
	
	if(isset($_POST['sub4'])){
		
		$sql = $_SESSION['sqlex'];
		$result = mysqli_query($con, $sql);
		echo mysqli_error($con);
		while($row = mysqli_fetch_array($result)){
			
			$inward_no = $row['doc_id'];
			
			$sql = " INSERT into forward_share_doc ( userid, dated, doc_id, doc_type, status, flag ) values ( '$userid', now(), '$inward_no', 'IN', '',  'Y' ); ";
			mysqli_query($con, $sql);
	
		}
					
?>
		<span class="pull-right">
			<a href="#forwardAuthority" class="btn btn-success" data-toggle="modal" data-mode="Approve" 
					data-target="#forwardAuthority" title="Forward" ><i class='fa fa-sign-out-alt'></i> Forward</a>&nbsp;&nbsp;&nbsp;
								
			<a href="#shareAuthority" class="btn btn-danger" data-toggle="modal" data-mode="Share" data-target="#shareAuthority" title="Share"><i class='fa fa-share-alt'></i> Share</a>&nbsp;&nbsp;&nbsp;
		</span>


<!--Forward Workflow Popup-->
<div class="modal fade" id="forwardAuthority" role="dialog" aria-labelledby="forwardAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="forwardAuthority">Forward To</h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="box-body">
										<form class="form-horizontal">
                                        
										<?php   
											$inward_no 	= $_SESSION['inward_no'];
											$status 	= $_SESSION['status'];
										?>
										
										<input type="hidden" name="inward_no" id="ap_idF" value="<?php echo $inward_no; ?>" >
										<input type="hidden" id="modeF" name="mode" value='Forward'>
										
										<input type="hidden" id="statusF" name="status" value='<?php echo $status; ?>' >
										
										<div class="form-group">
											<label class=" col-sm-2 control-label">Forward To</label> <?php // multiple="multiple" ?>
											<div class="col-sm-10">
												<select class="form-control col-sm-10 " name="send_to" id="send_toF"  >
													<option value="0">Select</option>
													<?php
														$sql="SELECT * FROM sma_user ORDER BY username ASC";
														$rs = mysqli_query($con, $sql);
														echo mysqli_error($con);
														while($rw = mysqli_fetch_array($rs)){
													?>
														<option value="<?php echo $rw['id']?>" <?php echo ($rw['id']==$send_to)?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
														<?php } ?>	
												</select>
											</div>
										</div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksF"></textarea>
											</div>
										</div>
									
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitForward">Forward</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>

<!--Forward Workflow Popup End -->	


<!--Shared Workflow Popup-->
<div class="modal fade" id="shareAuthority" role="dialog" aria-labelledby="shareAuthority">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="shareAuthority">Share</h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                            <div class="box-body">
                                <div class="col-md-12">
                                    
									<form class="form-horizontal">
										<div class="box-body">    
										<?php   
											//$inward_no 	= $_SESSION['inward_no'];
											$status 	= $_SESSION['status'];
										?>
										
										<input type="hidden" name="inward_no" id="ap_idS" value="<?php echo $inward_no; ?>" >
										<input type="hidden" id="modeS" name="mode" value='Shared'>
										
										<!--<input type="hidden" id="statusS" name="status" value='<?php echo $status; ?>' >-->
										<input type="hidden" id="statusS" name="status" value="Accepted" >
										
											
										<div class="form-group">
											<label class=" col-sm-2 control-label">To Company</label> <?php // multiple="multiple" ?>
											<div class="col-sm-10">
												<select class="form-control" name="send_to_comp" id="send_toS_comp"  >
													<option value="0">Select</option>
													<?php
														$sql="SELECT * FROM company ORDER BY comp_name ASC";
														$rs = mysqli_query($con, $sql);
														echo mysqli_error($con);
														while($rw = mysqli_fetch_array($rs)){
													?>
														<option value="<?php echo $rw['comp_id']?>" <?php echo ($rw['comp_id']==$send_to)?'selected="selected"':'';?> ><?php echo $rw['comp_name'] ?></option>
														<?php } ?>	
												</select>
											</div>
										</div>
										
										<div class="form-group">
											<label class=" col-sm-2 control-label">To Group</label> <?php // multiple="multiple" ?>
											<div class="col-sm-10">
												<select class="form-control" name="send_to_group" id="send_to_Group"  >
													<option value="0">Select</option>
													<?php
														$sql="SELECT * FROM sma_user_group ORDER BY user_group ASC";
														$rs = mysqli_query($con, $sql);
														echo mysqli_error($con);
														while($rw = mysqli_fetch_array($rs)){
													?>
														<option value="<?php echo $rw['id']?>" ><?php echo $rw['user_group'] ?></option>
														<?php } ?>	
												</select>
											</div>
										</div>
										
										<div class="form-group">
											<label class=" col-sm-2 control-label"> To User</label> <?php // multiple="multiple" ?>
											<div class="col-sm-10">
												<select class="form-control select2" multiple name="send_to" id="send_toS" <?php echo $readonly; ?> >
													<option value="0">Select</option>
													<?php
														$sql="SELECT * FROM sma_user ORDER BY username ASC";
														$rs = mysqli_query($con, $sql);
														echo mysqli_error($con);
														while($rw = mysqli_fetch_array($rs)){
													?>
														<option value="<?php echo $rw['id']?>" <?php echo ($rw['id']==$send_to)?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
														<?php } ?>	
												</select>
											</div>
										</div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">To Email</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="ext_email" id="ext_emaiL"></textarea>
											</div>
										</div>
										
										<div class="form-group">
											<label for="approver" class="col-sm-2 control-label">Remarks</label>
                                            <div class="col-sm-10">
												<textarea class="form-control" rows="3" name="remarks" id="remarksS"></textarea>
											</div>
										</div>
									
                                    </div>

								</form>	
									
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary" id="submitShared">Share</button>
							</div>
			
                                </div>
                            </div>			
			</section>
		</div>
    </div>
  </div>
</div>


<!--Shared Workflow Popup End -->	

<?php
		}
?>


<script>

    $("#submitSend").on("click", function(e){
        var sub 			= 'sub5';
		var mode		 	=  $("#modeS").val();
		var send_to			=  $("#send_toS").val();
        
		var inward_no		=  $("#ap_idS").val();		
		var status 			=  $("#statusS").val();
		var remarks			=  $("#remarksS").val();

		var doc_scr			= 'INW';
//alert(sub + ' ' + mode + ' ' + send_to + ' ' + status );	

		$('#sendAuthority').modal('hide');
		var strURL = "app_func123.php";
		$.post(strURL,{ inward_no:inward_no,
						status:status,
						send_to:send_to,
						statusap:mode,
						remarks:remarks,
						doc_scr:doc_scr,
						sub5:sub},
						function(result){
		      $('#prshare').html(result);
		});
		
	});


    $("#submitApprove").on("click", function(e){
        var sub 			= 'sub13';
		var mode		 	=  $("#modeA").val();
		
		var inward_no		=  $("#inward_noA").val();
	    var status 			=  $("#statusA").val();
		var approver		=  $("#approverA").val();
        var remarks			=  $("#remarksA").val();

//alert(sub + ' ' + mode  + ' ' + approver + ' ' + status );
		
		$('#approvalAuthority').modal('hide');
		
		var strURL = "app_func.php";
		$.post(strURL,{ inward_no:inward_no,
						mode:mode,
						status:status,
						approver:approver,
						remarks:remarks,
						sub13:sub},
						function(result){
		      $('#predit').html(result);
		});
		
		
	});


</script>

<?php
	if(isset($_POST['sub5'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$searchf = $_POST['id'];
		if($searchf=='I'){
?>
		<div class="form-group">
			<div class="col-sm-12">
				<label class="control-label"> User Name</label> <?php // multiple="multiple" ?>
				<select class="form-control select2" multiple123 name="send_to_a" id="send_toS_a"  >
					<option value="0">Select</option>
				<?php
					$sql="SELECT * FROM sma_user ORDER BY username ASC";
					$rs = mysqli_query($con, $sql);
					echo mysqli_error($con);
					while($rw = mysqli_fetch_array($rs)){
				?>
					<option value="<?php echo $rw['id']?>" <?php echo ($rw['id']==$send_to)?'selected="selected"':'';?> ><?php echo $rw['username'] ?></option>
				<?php } ?>	
				</select>
			</div>
		</div>
<?php   
		}
		if($searchf=='E'){
?>
		<div class="form-group">
			<div class="col-sm-12">
				<label class="control-label"> Party Name</label> <?php // multiple="multiple" ?>
				<select class="form-control select2" multiple123 name="send_to_a" id="send_toS_a"  >
					<option value="0">Select</option>
				<?php
					$sql="SELECT * FROM sma_party_mst ORDER BY party_name ASC";
					$rs = mysqli_query($con, $sql);
					echo mysqli_error($con);
					while($rw = mysqli_fetch_array($rs)){
				?>
					<option value="<?php echo $rw['id']?>" <?php echo ($rw['id']==$send_to)?'selected="selected"':'';?> ><?php echo $rw['party_name'] ?></option>
				<?php } ?>	
				</select>
			</div>
		</div>
<?php   
		}
		
	}
?>
  


<?php
	if(isset($_POST['sub6'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$searchf = $_POST['id'];
//echo $searchf;
		if($searchf=='B'){
?>
		
			<div class="col-sm-4">
				
				<select class="form-control select2" multiple123 name="search_data" id="search_data"  >
					<option value="0">Select</option>
				<?php
					$sql="SELECT * FROM sma_user ORDER BY username ASC";
					$rs = mysqli_query($con, $sql);
					echo mysqli_error($con);
					while($rw = mysqli_fetch_array($rs)){
				?>
					<option value="<?php echo $rw['id']?>"  ><?php echo $rw['username'] ?></option>
				<?php } ?>	
				</select>
			</div>
		
<?php   
		}
		if($searchf=='S'){
?>
		
			<div class="col-sm-4">
				
				<select class="form-control select2" multiple123 name="search_data" id="search_data"  >
					<option value="0">Select</option>
				<?php
					$sql = "SELECT id as id, party_name as up_name, 'P' as ptype FROM sma_party_mst  
							union
							SELECT id as id, username as up_name, 'U' as ptype FROM sma_user  ORDER BY up_name ";
					$rs = mysqli_query($con, $sql);
					echo mysqli_error($con);
					while($rw = mysqli_fetch_array($rs)){
				?>
					<option value="<?php echo $rw['id'].'-'.$rw['ptype'];?>"  ><?php echo $rw['up_name'].'-'.$rw['ptype']; ?></option>
				<?php } ?>	
				</select>
			</div>
		
<?php   
		}
		else if($searchf=='D'){
?>			
			<label class="col-lg-1 control-label">From</label>
				<div class="col-md-2">
					<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
						<input type="text" class="form-control" id="start_date" name="start_date" autocomplete="off" value="" > 
						<div class="input-group-addon">
								<i class="fa fa-calendar-alt"></i>
						</div>
					</div>
				</div>
			<label class="col-lg-1 control-label">To</label>
				<div class="col-md-2">
					<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
						<input type="text" class="form-control" id="end_date" name="end_date" autocomplete="off" value="" > 
						<div class="input-group-addon">
								<i class="fa fa-calendar-alt"></i>
						</div>
					</div>
				</div>
<?php
		}
		else if($searchf=='C'){
?>					
			<div class="col-md-4">
				<select class="form-control " name="search_data" id="search_data" >
					<option value=""> Select </option>
					<?php $sql = "select * from company order by comp_name ";
					$q2 	= mysqli_query($con, $sql);
					while($r2 = mysqli_fetch_array($q2)){ ?>
					<option value="<?php echo $r2['comp_id'];?>" ><?php echo $r2['comp_name'];?></option>
				<?php } ?>
				</select>
										
			</div>
<?php		
		}
		else if( $searchf=='N' || $searchf=='O' ){
?>				
			<div class="col-md-4">
			<input type="text" class="form-control" id="search_data" name="search_data" value="" autocomplete="off" >
			</div>
<?php
		}
	
}	
?>


<?php
	if(isset($_POST['sub7'])){
    
        $id = $_POST['id'];
		if($_POST['id'] == ''){$id = '';}
		
		$sql="SELECT * FROM dms_description where id = '$id' ";
		$rs = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$rw = mysqli_fetch_array($rs);
		$remarks = $rw['description'];
?>
		
		<input type="text" class="form-control" id="remarks" required name="remarks" value="<?php echo $remarks;?>" >
		
<?php

		}

?>		


