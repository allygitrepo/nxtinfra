<?php
include("../header.php");
$modulePath = "purchase_order/";

//$pgname = "purchase_order/index.php";
//include("../viewonly.php");
$usrid  = $_SESSION['usrid'];
?>
<!-- DataTables -->
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Log <small>List</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Purchase Order</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
   			<?php 
				$targetpage = "auditreport.php?sub=list"; 
				$limit = 10; 
				$start = 0;	
				$comid  = $_SESSION['comid'];
			
				if ( $_POST['Search'] or $_POST['comp_id'] or $_POST['user_name'] or $_POST['main_menu'] or $_POST['sub_menu'] or $_POST['action'] or $_POST['from_date'] ){
					 $company 		= $_POST['comp_id'];
	                 $user_name 			= $_POST['user_name'];
	                $main_menu 		= $_POST['main_menu'];
					$sub_menu 		= $_POST['sub_menu'];
					$action 		= $_POST['action'];
					$from_date 		= $_POST['from_date'];
					$to_date 		= $_POST['to_date'];
					$Search 		= $_POST['Search'];
					
					$_SESSION['comp_id'] 	= $company;
					$_SESSION['user_name'] 	= $user_name;
					$_SESSION['main_menu'] 	= $main_menu;
					$_SESSION['sub_menu'] 	= $sub_menu;
					$_SESSION['action'] 	= $action;
					$_SESSION['from_date'] 	= $from_date;
					$_SESSION['to_date'] 	= $to_date;
					$_SESSION['Search'] 	= $Search;
									
				}
				
				if ( $_SESSION['from_date'] or $_SESSION['action'] or $_SESSION['comp_id'] or $_SESSION['approval_status'] or $_SESSION['user_name'] or $_SESSION['main_menu'] 
						or $_SESSION['sub_menu'] or $_SESSION['search_own'] ){
					$comp_id 		= $_SESSION['comp_id'];
					$user_name 		= $_SESSION['user_name'];
					$main_menu	 	= $_SESSION['main_menu'];
					$sub_menu 		= $_SESSION['sub_menu'];
					$action 		= $_SESSION['action'];
			 		$from_date 		= $_SESSION['from_date'];
					$to_date 		= $_SESSION['to_date'];
					$Search 		= $_SESSION['Search'];
					
					$from_date_v 	= date('d-m-Y', strtotime($from_date));
					$to_date_v 	    = date('d-m-Y', strtotime($to_date));
					
					$from_date 	= date('d-m-Y', strtotime($from_date));
					$to_date 	= date('d-m-Y', strtotime($to_date));
					
					$_SESSION['reset']='';
				}
				
				if ( !empty($_POST['reset']) || !empty($_GET['reset']) || !empty($_SESSION['reset'])){
					$_SESSION['comp_id'] 	= '';
					$_SESSION['user_name'] 	= '';
					$_SESSION['main_menu'] 	= '';
					$_SESSION['sub_menu'] 	= '';
					$_SESSION['action'] 	= '';
					$_SESSION['from_date'] 	= '';
					$_SESSION['to_date'] 	= '';
					$_SESSION['Search'] 	= '';
					
					$comp_id 		= $_SESSION['comp_id'];
					$user_name 		= $_SESSION['user_name'];
					$main_menu 		= $_SESSION['main_menu'];
					$sub_menu		= $_SESSION['sub_menu'];
					$action			= $_SESSION['action'];
					$from_date		= $_SESSION['from_date'];
					$to_date		= $_SESSION['to_date'];
					$Search		= $_SESSION['Search'];
					
				}
				
				
				
				if($from_date=='01-01-1970'){
					$from_date = '';
				}	
				if($to_date=='01-01-1970'){
					$to_date = '';
				}
				//echo 		$from_date .">><<";
				
				/* 
				if(!$_POST['Save']){
					$search_own='Y';
				} */
				
			?>
					<form class="form-horizontal" action="auditreport.php?sub=find" method="post">
                      
						<div class="form-group">
								
								<div class="col-md-4">
								<label class="col-lg-1a control-label">Company</label>
								
									<select class="form-control" name="comp_id" id="comp_id" onchange="getcomp(this.value)" >
										<option value=""> Select </option>
											<?php $sql = "select * from company order by comp_name";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['comp_id'];?>" <?php echo ($comp_id == $r2['comp_id'])?'selected="selected"':'';?> ><?php echo $r2['comp_name'];?></option>
											<?php } ?>
									</select>
										
								</div>
							
								<div class="col-md-3">
									<label class="col-lg-1a control-label">User&nbsp;Name</label>
									<select class="form-control" name="user_name" id="user_name" >
										<option value=""> Select </option>
											<?php $sql = "select * from log_tbl where 1 and user_name !='' group by user_name";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['user_name'];?>" <?php echo ($user_name == $r2['user_name'])?'selected="selected"':'';?> ><?php echo $r2['user_name'];?></option>
											<?php } ?>
									</select>
								</div>
								
								<div class="col-xs-2">
									<label for="prDate" class="col-sm-1a control-label">From Dated</label>
                                    <div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                        <input type="text" class="form-control"  id="from_date" autocomplete='off' name="from_date" placeholder="dd-mm-yyyy"
                                               value="<?= $from_date; ?>">
                                    </div>
                                </div>
								
								<div class="col-xs-2">
									<label for="prDate" class="col-sm-1a control-label">To Dated</label>
                                    <div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar-alt"></i>
                                        </div>
                                        <input type="text" class="form-control"  id="to_date" autocomplete='off' name="to_date" placeholder="dd-mm-yyyy"
                                               value="<?= $to_date; ?>">
                                    </div>
                                </div>
								
						</div>
								
						<div class="form-group">		
								
								<div class="col-md-2">
									<label class="col-lg-1a control-label">Main&nbsp;Menu</label>
									<select class="form-control " name="main_menu" id="main_menu" onchange="getSubMenu(this.value);">
										<option value=""> Select </option>
											<?php $sql = "select distinct(main_menu) from log_tbl order  by main_menu ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['main_menu'];?>" <?php echo ($main_menu == $r2['main_menu'])?'selected="selected"':'';?> ><?php echo $r2['main_menu'];?></option>
											<?php } ?>
									</select>
										
								</div>
								
								<div class="col-md-3">
									<label class="col-lg-1a control-label">Sub&nbsp;Menu</label>
							<span id="getSubMenu">		
									<select class="form-control" name="sub_menu" id="sub_menu" >
										<option value=""> Select </option>
											<?php $sql = "select distinct(sub_menu) from log_tbl where main_menu = '$main_menu' order by sub_menu";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['sub_menu'];?>" <?php echo ($sub_menu == $r2['sub_menu'])?'selected="selected"':'';?> ><?php echo $r2['sub_menu'];?></option>
											<?php } ?>
									</select>
							</span>			
								</div>
								
								<div class="col-md-2">
									<label class="col-lg-1a control-label">Action&nbsp;Taken</label>
									<select class="form-control" name="action" id="action" >
										<option value=""> Select </option>
											<?php $sql = "select action from log_tbl group by action";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['action'];?>" <?php echo ($action == $r2['action'])?'selected="selected"':'';?> ><?php echo $r2['action'];?></option>
											<?php } ?>
									</select>
										
								</div>
						
								<centera>	
									<div class="col-md-2" >
										<label class="col-lg-1a control-label">&nbsp;</label><br>
											
										<input class="btn btn-success" type="submit" value="Search" name="Search" >&nbsp;&nbsp;&nbsp;
										<input class="btn btn-danger" type="submit" value="Reset" name="reset" >&nbsp;&nbsp;&nbsp;
										
									</div>
								</centera>
							
			<?php  if($user=='Admin'){ ?>
					<div class="col-md-2" >
						<label class="col-lg-1a control-labela">&nbsp;</label><br>
						<span class="pull-right"><a href="audit_log_export.php?sub=pdf" class="btn btn-primary">Export</a></span>
					</div>
			<?php } ?>
							</div>	
						
							
								
								   
				</form>
				
			
            </div>
			
			<span id="prItemsTableBody123"> </span>
			 <span id="getcomp">
            <!-- /.box-header -->
            <div class="box-body">
              <table id="prtable1235" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%" data-toggle="table" data-sort-name="Audit Date Time" data-sort-order="desc">
                 
                <thead>
                <tr>
                   <th></th>
					<th>Audit Date Time</th>
					<th>Company</th>
					<th>User Name</th>
					<th style="text-align:left;">Main Menu</th>
					<th>Sub Menu</th>
					<th>Details</th>
					<th style="text-align:left;">Action Taken</th>
				</tr>
                </thead>
               
               <tbody>
	<?php

	    //$company = "0";
	    //$user = "0";
		//$main_menu = "0";
	    //$sub_menu = "0";
			/* $Search 		= $_POST['Search'];
			$company 		= $_POST['comp_id'];
			$user 			= $_POST['user_name'];
			$main_menu 		= $_POST['main_menu'];
			$sub_menu 		= $_POST['sub_menu'];
			$action			= $_POST['action'];
			 */
	   $select="Select * from log_tbl where 1 ";
	   
	   if(!empty($Search)){
		   if(!empty($company)){
			  $select .=" AND company_name='$company' ";
		   }	   
		   if(!empty($main_menu)){
			   $select .=" AND main_menu='$main_menu' ";
		   }
		   if(!empty($sub_menu)){
			   $select .=" AND sub_menu='$sub_menu' ";
		   }
		   if(!empty($user_name)){
			   $select .=" AND user_name = '$user_name' ";
		   }
		   if(!empty($action)){
			   $select .=" AND action = '$action' ";
		   }
		   
			if(!empty($from_date)){
				$from_date 	= date('Y-m-d', strtotime($from_date_v));
				$to_date 	= date('Y-m-d', strtotime($to_date_v));
				
				$select    .= " AND audit_date_time >= '$from_date' AND audit_date_time <= '$to_date' ";
			}
			
			//$select .= " order by audit_date_time desc ";
	    }
		else {
			$select="Select * from log_tbl where 1 "; 
		}	

		//	$select .= " order by audit_date_time desc ";
			
		//$_SESSION['sql'] = $select;

		
		$qresult = mysqli_query($con,$select);
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

		$select .= ' order by audit_date_time desc ';
		
		$_SESSION['sqlex'] = $select;		
					
		
		$select .= " LIMIT $start, $limit ";
		
//echo $select. "<BR>";				
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

//echo $select."<BR>";		
		$rzlt=mysqli_query($con,$select);
		while($row=mysqli_fetch_array($rzlt)){
		    
			$company_name = $row['company_name'];
			$sql="SELECT * FROM company where 1 and comp_id = '$company_name' ";
            $res = mysqli_query($con, $sql);
			$r2=mysqli_fetch_array($res);
			$comp_code = $r2['comp_code'];
       ?>
               
                    <tr>
						<td width="1%"><input type="hidden" value="<?= ++$ij; ?>" ></td>
                        <td><?= $row['audit_date_time'] ?></td>
                        <td><?= $comp_code ?></td>
						<td><?= $row['user_name'] ?></td>
                        <td><?= $row['main_menu'] ?></td>
                        <td><?= $row['sub_menu'] ?></td>

                        <td><?= $row['description'];?></td>
                       
                        <td><?= $row['action'] ?></td>
                    </tr>

				<?php
	
	}
				?>
	
                </tbody></span>
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

</div>
<!-- ./wrapper -->

<!-- Modal Add Item-->
<div class="modal fade" id="modalAddItem" role="dialog" aria-labelledby="modalAddItemLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalAddItemLabel">Export Purchase order data</h4>
            </div>
            <div class="modal-body123">
                <section class="content">
                    <div class="row123">
                        <form class="form-horizontal" action="po_export_func.php?sub=pdf" target="_blank" method="POST" >
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
									<label for="itemCategory" class="control-label"> Compnay</label>
									<select class="form-control" name="company_id" id="companyId" >
									<option value=""> Select </option>
									<option value=""> All </option>
										<?php $sql = "select * from company order by comp_name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" > <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>
								</div>
                            </div>
                            
							
							<div class="form-group">
                                <div class="col-sm-6">
									<label for="itemCategory" class="control-label"> Supplier</label>
									<select class="form-control" name="supplier_id" id="toSupplier" >
									<option value=""> Select </option>
									<option value=""> All </option>
										<?php $sql = "select * from sma_party_mst order by party_name ";
										$q2 	  = mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>" > <?php echo $r2['party_name'];?></option>
										<?php } ?>
									</select>
							
                                </div>
                            
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
<?php
include("../footer.php");
$sql = "SELECT * from log_tbl";

$result = mysqli_query($con, $sql);
$sql .= ' order by id DESC ';

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
?>

<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>

<script>
    $(function () {
        $("#prtable").DataTable();
    });


$(document).ready(function () {
  $('#prtable123').DataTable();
  $('.dataTables_length').addClass('bs-select');
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

function getSubMenu(id){
    
    var sub = 'sub2';
//alert(id);
	var strURL = "sett_func.php";
	$.post(strURL,{ sub2:sub,id:id},function(result){
			  $('#getSubMenu').html(result);
		});
}
		
</script>

</body>
</html>

