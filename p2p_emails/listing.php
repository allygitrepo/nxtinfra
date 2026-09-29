<?php

include("../header.php");

$pgname = "index.php";
include("../viewonly.php");

$show = '';

if(isset($_GET['show']) && !empty($_GET['show']))
{
    $show = $_GET['show'];
}

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

<section class="content-header">
    <h1>
        Inbox
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Email Inbox</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Emails</h3>
                    <?php if ( $addonly=='Y'){ ?>
                        <!--<span class="pull-right"><a href="main_menu.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create Main Menu </a></span>-->
                    <?php } ?>	
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div id="divNotify" class="col-md-12">
                        <div class="alert alert-info">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            This page will refresh automatically every 15 minutes!
                        </div>
                    </div>
                    
                    <div class="col-md-12" style="margin-bottom: 10px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-1 control-label">Show: </label>
                                <div class="col-md-2">
                                    <select class="form-control select" id="ddlShow" name="ddlShow">
                                        <option value="">Pending</option>
                                        <option value="1" <?= ($show == '1') ? 'selected="selected"' : ''; ?>>Completed</option>
                                        <option value="2" <?= ($show == '2') ? 'selected="selected"' : ''; ?>>Deleted</option>  
										<option value="3" <?= ($show == '3') ? 'selected="selected"' : ''; ?>>Pending PO</option> 
										<option value="4" <?= ($show == '4') ? 'selected="selected"' : ''; ?>>Date</option>		
                                    </select>
                                </div>
                            
					<?php if($show == '4'){ 
								$start_date = date('Y-m-d', strtotime($_GET['sd']));
								$end_date   = date('Y-m-d', strtotime($_GET['ed']));
								if($start_date=='1970-01-01'){
									$start_date ='';
								}	
								if($end_date=='1970-01-01'){
									$end_date ='';
								}	
								
					?>		
							
								<div class="col-md-2">
								<label class=" control-label">Start.Date</label>
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="start_date" name="start_date" autocomplete="off" value="<?= (empty($start_date) ? '': date('d-M-Y', strtotime($start_date))); ?>" > 
										<div class="input-group-addon">
												<i class="fa fa-calendar-alt"></i>
										</div>
									</div>
								</div>
								<div class="col-md-2">
								<label class=" control-label">End.Date</label>
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="end_date" name="end_date" autocomplete="off" value="<?= (empty($end_date) ? '': date('d-M-Y', strtotime($end_date))); ?>" > 
										<div class="input-group-addon">
												<i class="fa fa-calendar-alt"></i>
										</div>
									</div>
								</div>
								<div class="col-md-2">
									<input class="btn btn-primary" type="button" value="Show" name="dateShow" id="dateShow" />
								</div>
					<?php 
						$end_date   = date('Y-m-d', strtotime($_GET['ed']. ' + 1 days'));
						} 
						else { ?>
							<div class="col-md-4">
								<label class=" control-label">&nbsp;</label>
							</div>	
						<?php } ?>
						
                        </div>
                        <div class="col-md-3">
                            <input class="btn btn-primary" type="button" value="Refresh Mailbox" name="btnRefresh" id="btnRefresh" />
							
							<span class="pull-right"><a href="<?php echo $baseurl . 'p2p_emails/' . "emails_report.php?sub=pdf"?>" target="_blank" class="btn btn-primary">Export</a>
						&nbsp;&nbsp;&nbsp;</span>
						
                        </div>
						
						</div>
						
                    </div>


                    <table id="prtable" class="table table-bordered table-striped table-hover">

                        <thead>
                            <tr>
                                <!--<th>
                                    <input id="chkSelectAllTable" type="checkbox" value="1" style="width: 20px; height: 20px; " />
                                </th>-->
                                <th>Date</th>
                                <th>From</th>
                                <th>Supplier</th>
                                <th>Subject</th>
                                <th>GRN / OpEx No.</th>
                                <th>Type</th>
								<th>Status</th>
								
                                <th style="text-align:right;">Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Default query
                            $sqlQuery = "SELECT E.*, S.party_name AS supplier FROM email_inbox AS E
                                            LEFT OUTER JOIN sma_party_mst AS S ON E.from_email = S.party_email
                                            WHERE E.deleted=0 AND (E.grn_no IS NULL OR E.grn_no = '') 
                                            ORDER BY E.message_date DESC";
                                            
                            if($show == '1')                
                            {
                                // Show Completed emails
                                $sqlQuery = "SELECT E.*, S.party_name AS supplier FROM email_inbox AS E
                                                LEFT OUTER JOIN sma_party_mst AS S ON E.from_email = S.party_email
                                                WHERE E.deleted=0 AND (E.grn_no IS NOT NULL AND E.grn_no != '')
                                                ORDER BY E.message_date DESC";
                            }
                            
                            if($show == '2')                
                            {
                                // Show ONLY DELETED emails
                                $sqlQuery = "SELECT E.*, S.party_name AS supplier FROM email_inbox AS E
                                                LEFT OUTER JOIN sma_party_mst AS S ON E.from_email = S.party_email
                                                WHERE E.deleted=1 ORDER BY E.message_date DESC";
                            }
                            if($show == '3')                
                            {
                                // Show ONLY Pending PO emails
                                $sqlQuery = "SELECT E.*, S.party_name AS supplier FROM email_inbox AS E
                                                LEFT OUTER JOIN sma_party_mst AS S ON E.from_email = S.party_email
                                                WHERE 1 AND  E.deleted=0 AND E.status='Pending PO' and grn_no is Null ORDER BY E.message_date DESC";
                            }
							if($show == '4')                
                            {
                                // Show ONLY Date wise emails
								$sqlQuery = "SELECT E.*, S.party_name AS supplier FROM email_inbox AS E
                                                LEFT OUTER JOIN sma_party_mst AS S ON E.from_email = S.party_email
                                                WHERE 1 AND  E.deleted=0
												AND E.message_date >= '$start_date' 
												AND E.message_date <= '$end_date' ORDER BY E.message_date DESC";
                            }

//echo $sqlQuery. "<BR>";							
							$_SESSION['sqlqry'] = $sqlQuery;
							
                            $resultEmails = mysqli_query($con, $sqlQuery);
                            echo mysqli_error($con);

                            while($row = mysqli_fetch_array($resultEmails))
                            {
                                //var_dump($row);

                                $baseurl1 = $baseurl.'p2p_emails/view.php?id='.$row["id"];
                                $baseurl1 .= (!empty($show) ? ('&show=' . $show) : '');

								$status = $row['status'];
								$styl = "";
								if(!empty($status)){
									$styl = " style='color:red;' ";
								}		
								
								
								
                            ?>
                                <tr style="cursor:pointer;" onclick="location.href='<?php echo $baseurl1;?>'">
                                    <!--<td width="2%">
                                        <input type="checkbox" class="chkSelect" id="chkSelect<?= $row['id']; ?>" value="<?= $row['id']; ?>" style="width: 16px; height: 16px;"  />
                                    </td>-->
                                    <td width="10%" <?= $styl;?> ><?= (empty($row['message_date']) ? '': date('d-M-Y', strtotime($row['message_date']))). ' '. $row['id']. ' '. $deleted; ?></td>
                                    <td width="20%" <?= $styl;?>><?= (empty($row['from']) ? '': $row['from']); ?></td>
                                    <td width="20%" <?= $styl;?>><?= (empty($row['supplier']) ? '': $row['supplier']); ?></td>
                                    <td width="20%" <?= $styl;?>><?= (empty($row['subject']) ? '': $row['subject']); ?></td>
                                    <td width="20%" <?= $styl;?>><?= (empty($row['grn_no']) ? '': $row['grn_no']); ?></td>
                                    <td width="10%" <?= $styl;?>><?= (empty($row['grn_type']) ? '': $row['grn_type']); ?></td>
                                    <td width="10%" <?= $styl;?>><?= (empty($row['status']) ? '': $row['status']); ?></td>
                                    <td width="2%" style="text-align:right;">
                                        <a href="<?= $baseurl1; ?>" name="btnView" title="View" placeholder="top center"><i class="fa fa-eye"></i>&nbsp;&nbsp;</a>
                                    </td>
                                </tr>

                            <?php }?>
                        </tbody> 
                    </table>

                </div>
            </div>
        </div>


<?php 	
include("../footer.php");	
?>
<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>

<script>
    $(document).ready(function() {
        // Refresh every 1 Hour = 36,00,000 ms
        // Refresh every 15 minutes = 9,00,000 ms
        setInterval(function() {window.location.href='index.php';}, 900000);
        
        $("#prtable").DataTable({
            order: [[0, 'desc']],
            'aoColumns': [
                //{ 'bSortable': false },
                null,
                null,
                null,
                null,
                null,
                null,
				null,
                { 'bSortable': false },
            ]
        });

        $('#btnRefresh').click(function() {
            window.location.href = 'index.php';
        });
        
		$('#dateShow').click(function() {
			
			var ed= document.getElementById('end_date').value;
			var sd= document.getElementById('start_date').value;
            window.location.href = 'listing.php?show=4&ed='+ed+'&sd='+sd;
        });
		
        $('#ddlShow').change(function() {
			
            if($(this).val() == 1) {
                window.location.href = 'listing.php?show=1';
            } else if($(this).val() == 2) {
                window.location.href = 'listing.php?show=2';
            } else if($(this).val() == 3) {
                window.location.href = 'listing.php?show=3';
            } else if($(this).val() == 4) {
                window.location.href = 'listing.php?show=4';
            }
			else {
                window.location.href = 'listing.php';
            }
        });
    });
</script>

</body>
</html>
