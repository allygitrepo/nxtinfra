<?php

if(!isset($_GET['id']) || empty($_GET['id']))
{
    header('Location: ' . $baseurl . 'listing.php');
    exit();
}

include("../header.php");

$pgname = "index.php";
include("../viewonly.php");

if(isset($_GET['id']) && !empty($_GET['id']))
{
    $sqlQuery = "SELECT * FROM email_inbox WHERE id=" . $_GET['id'];
    $resultQuery = mysqli_query($con, $sqlQuery);
    $emailData = mysqli_fetch_array($resultQuery);

    if(empty($emailData))
    {
        header('Location: ' . $baseurl . 'listing.php');
        exit();
    }
}

?>

<style type="text/css">
    .btn-info {
        color: #fff;
        background-color: #5bc0de;
        border-color: #46b8da;
    }

    .btn-info:hover, .btn-info:focus, .btn-info:active, .btn-info.active, .open>.dropdown-toggle.btn-info {
        color: #fff;
        background-color: #31b0d5;
        border-color: #269abc;
    }

    a {
        text-decoration: none !important;
    }
    a:link, span.MsoHyperlink {
        color: #fff !important;
    }
    .dropdown-menu>li>a {
        color: #777 !important;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

<section class="content-header">
    <h1>
        Inbox
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">View Email</li>
    </ol>
</section>

<section class="content">
<div class="row">
<div class="col-xs-12">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">View Email</h3>
            <?php if ( $addonly=='Y'){ ?>
                <!--<span class="pull-right"><a href="main_menu.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create Main Menu </a></span>-->
            <?php } ?>    
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div id="divNotify" class="col-md-12"></div>

            <div class="col-md-12" style="margin-bottom: 20px;">
                <div class="col-md-5">
                    <!--<a class="btn btn-info" href="listing.php" id="btnBack">Back to Inbox</a>-->
                </div>
                <div class="col-md-2">
                    <?php if($emailData['deleted'] == 0) { ?>
                        <button type="button" class="btn btn-danger" name="btnDelete" id="btnDelete" rowid="<?= $_GET['id']; ?>">Delete Message</button>
                    <?php } ?>
                </div>
                <div class="col-md-5">
                    <?php
                    /*$grnLink = $baseurl . 'supp_invoice/addgrn.php?sub=add&msg_id=' . $emailData['message_id'];
                    $grnLink .= '&subject=' . $emailData['subject'];

                    $splitFromAddress = explode('<', html_entity_decode($emailData['from']), 2);
                    $grnLink .= '&email=' . str_replace('>', '', $splitFromAddress[1]);*/
                    
                    $grnLink = '';
                    if($emailData['grn_type'] == 'grn')
                    {
                        $grnLink = $baseurl . 'supp_invoice/editgrn.php?sub=edit&id=' . $emailData['grn_no'] . '&page=1';
                    }
                    else if($emailData['grn_type'] == 'opex')
                    {
                        $grnLink = $baseurl . 'travel_approval/company_expense.php?sub=edit&id=' . $emailData['grn_no'] . '&page=1';
                    }
                    

                    $attachmentFolder = 'attachments/' . date('d-M-Y', strtotime($emailData['message_date'])) . '/' . $emailData['message_id'];
                    $attachments = glob($attachmentFolder . "/*.*");
                    //print_r($attachments);

                    if(!empty($attachments) && count($attachments) > 0)
                    {
                    ?>                            
                        <div class="btn-group mr5">
                            <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
                                View Attachments <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <?php                                        
                                foreach($attachments as $fileName)
                                {
                                ?>
                                    <li>
                                        <a href="<?= $baseurl . 'p2p_emails/' . $fileName ?>" target="_blank">
                                            <?php
                                            $splitFileName = explode('_', $fileName, 2);
                                            //var_dump($array);
                                            echo $splitFileName[1];
                                            ?>
                                        </a>
                                    </li>
                                <?php
                                }
                                ?>
                            </ul>
                        </div>
                        <?php if(empty($emailData['grn_no'])) { ?>
                            <button class="btn btn-success" data-toggle="modal" data-target=".bs-example-modal-sm" rowid="<?= $_GET['id']; ?>">Create Entry</button>                            
                    <?php
                        }
                    }

                    $backLink = 'listing.php';
                    if(isset($_GET['show']) && !empty($_GET['show']))
                    {
                        $backLink .= '?show=' . $_GET['show'];
                    }
                    ?>
                    <?php if(!empty($emailData['grn_no'])) { ?>
                        <a href="<?= $grnLink; ?>" class="btn btn-success" id="btnViewGRN" target="_blank">View Entry</a>
                    <?php } ?>
                    <a class="btn btn-info" href="<?= $backLink; ?>" id="btnBack">Back to Inbox</a>
					
					
                </div>
            </div>

			<?php
			
			?>
			

            <div class="col-md-12">
                <div class="col-md-6">
                    <b>Date:</b> <?= date('d-M-Y H:i:s a', strtotime($emailData['message_date'])); ?>
						
                    <br>
                    <b>From:</b> <?= $emailData['from']; ?>
                    <br>
                    <b>Subject:</b> <?= $emailData['subject']; ?>
                </div>
                <div class="col-md-6">
				<?php if(empty($emailData['status'])) { ?>
					<button type="button" class="btn btn-info" name="btnMarkPendingPO" id="btnMarkPendingPO" rowid="<?= $_GET['id']; ?>">Mark as Pending for PO</button>
				<?php } ?>		
                </div>                        
                <div class="col-md-12">
                    <br><br>
                    <b>Email Body:</b><br>
                    <?= base64_decode($emailData['email_body']); ?>
                </div>
            </div>


        </div>
    </div>
</div>

<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                <h4 class="modal-title">Create Entry</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group ">
                            <label class="col-sm-6 control-label" style="padding-top: 20px;">Create Entry for: <span style="color:red;"> **</span></label>
                            <div class="col-md-6">
                                <div class="radio">
                                    <label><input type="radio" name="rbtnEntry" value="grn" required> GRN</label>
                                </div>
                                <div class="radio">
                                    <label><input type="radio" name="rbtnEntry" value="opex"> OpEx</label>
                                </div>                                
                                
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-primary" id="btnCreateGRN" rowid="<?= $_GET['id']; ?>">Submit</button>
                        <button class="btn btn-default" data-dismiss="modal" class="close" type="button">Cancel</button>
                    </div>
                </div>
            </div>    
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
        $('#btnDelete').click(function() {
            if (confirm("Are you sure you want to delete?") == true) {
                //debugger;
                var rowid = $(this).attr('rowid');
                //var elem = $(this);
                $.ajax({
                    url: "crud_functions.php",    //the page containing php script
                    type: "POST",    //request type,
                    //dataType: 'json',
                    data: {oprName: 'delete_email', id: rowid},   //{registration: "success", name: "xyz", email: "abc@gmail.com"},
                    success:function(result){
                        //console.log(result);
                        if(result == 'Success') {
                            $('#divNotify').html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' + result + '<br>Redirecting to Inbox page...</div>');                            
                            setTimeout(function() {window.location.href='listing.php';}, 3000);
                        } else {
                            $('#divNotify').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' + result + '</div>');
                        }

                    }
                });
            } 
        });

		$('#btnMarkPendingPO').click(function() {
            if (confirm("Are you sure you want to Mark Pending PO ?") == true) {
                //debugger;
                var rowid = $(this).attr('rowid');
                //var elem = $(this);
                $.ajax({
                    url: "crud_functions.php",    //the page containing php script
                    type: "POST",    //request type,
                    //dataType: 'json',
                    data: {oprName: 'mark_Pending', id: rowid},   //{registration: "success", name: "xyz", email: "abc@gmail.com"},
                    success:function(result){
                        //console.log(result);
                        if(result == 'Success') {
                            $('#divNotify').html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' + result + '<br>Redirecting to Inbox page...</div>');                            
                            setTimeout(function() {window.location.href='listing.php';}, 3000);
                        } else {
                            $('#divNotify').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' + result + '</div>');
                        }

                    }
                });
            } 
        });


        $('#btnCreateGRN').click(function() {
            if ($('input[name="rbtnEntry"]:checked').length == 0) {
                //alert('please...');
                $('input[name="rbtnEntry"]').parent().parent().parent().append('<span style="color:red;">This field is required.</span>');
                return false; 
            } else {
                if (confirm("Are you sure you want to Create Entry?") == true) {
                    $('.bs-example-modal-sm').modal('hide');
                    //debugger;
                    var rowid = $(this).attr('rowid');
                    var userid = '<?= $_SESSION['usrid']; ?>';
                    var user = '<?= $_SESSION['user']; ?>';
                    var baseUrl = '<?= $baseurl; ?>';
                    var rbtnEntry = $('input[name="rbtnEntry"]:checked').val();
                    var elem = $(this);
                    $.ajax({
                        url: "crud_functions.php",    //the page containing php script
                        type: "POST",    //request type,
                        //dataType: 'json',
                        data: {oprName: 'create_grn', id: rowid, user: user, userId: userid, type: rbtnEntry, baseUrl: baseUrl},   //{registration: "success", name: "xyz", email: "abc@gmail.com"},
                        success:function(result){
                            //console.log(result);
                            if(result.indexOf('Success') > -1) {
                                $('#divNotify').html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' + 'Success' + '<br>Redirecting to listing page...</div>');                            
                                $(elem).hide();
                                
                                var splitMsg = result.split(':');
                                var redirectLink = '';
                                if(splitMsg[1] != null && splitMsg[1] != '') {
                                    if(splitMsg[1] == 'grn') {
                                        redirectLink = '<?= $baseurl . 'supp_invoice/editgrn.php?sub=edit&id='; ?>';
                                        if(splitMsg[2] != null && splitMsg[2] != '') {
                                            redirectLink += splitMsg[2] + '&page=1';
                                        }
                                    } else if(splitMsg[1] == 'opex') {
                                        redirectLink = '<?= $baseurl . 'travel_approval/company_expense.php?sub=edit&id='; ?>';
                                        if(splitMsg[2] != null && splitMsg[2] != '') {
                                            redirectLink += splitMsg[2] + '&page=1';
                                        }
                                    }
                                    
                                    var win = window.open(redirectLink, '_blank');
                                    if (win) {
                                        //Browser has allowed it to be opened
                                        win.focus();
                                    } else {
                                        //Browser has blocked it
                                        alert('Please allow popups for this website');
                                    }
                                }
                                
                                setTimeout(function() {
                                    window.location.href = 'listing.php';
                                }, 3000);
                            } else {
                                $('#divNotify').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' + result + '</div>');
                            }

                        }
                    });
                }
            } 
        });
    });
</script>

</body>
</html>
