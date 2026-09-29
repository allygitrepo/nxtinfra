<?php
	include("../header.php");
	
	date_default_timezone_set('Asia/Kolkata');
	
	$modulePath = "tender/"; 

	$userid   	= $_SESSION['usrid'];
	
if( $_GET['sub']=='Save' ){
	
		$tender_id 	= $_POST['tender_id'];
		$extend 	= $_POST['extend'];
		
		require '../PHPMailer-master/PHPMailerAutoload.php';
		
			if($extend=='Extend'){
				
				$deadline_date		= date('Y-m-d', strtotime($_POST['deadline_date']));
				$deadline_time		= $_POST['deadline_time'];
				$deadline_date		.= ' ' .$deadline_time;
				
				$sql = " UPDATE sma_tender_header SET extend = 'Y',
							deadline_date		= '$deadline_date',
							deadline_time		= '$deadline_time',
							status = 'Published'
							WHERE id = '$tender_id' ";
				mysqli_query($con, $sql);
				
				
				$sql = " SELECT * from sma_tender_header WHERE 1 and id = '$tender_id' " ;
				$q2  = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_object($q2);
				$tender_title 	= $r2->tender_title;
				$company_id 	= $r2->company_id;
				
				
				$sql = " SELECT * from company WHERE 1 and comp_id = '$company_id' " ;
				$q2  = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_object($q2);
				$comp_name 	= $r2->comp_name;
				
					
				$sql = " INSERT INTO workflow_history ( doc_type, doc_id, create_by, create_date, status, reviewed_by, approved_date ) 
				values( 'TN', '$tender_id', '$userid', now(), 'Extended', '', now() ) ";
				
				mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
				
				$sql="select * from sma_tender_supplier where tender_hdr_id = '$tender_id' ";
				$rt = mysqli_query($con, $sql);
				echo mysqli_error($con);
				while($r22 =mysqli_fetch_array($rt)){
					
					$to_supplier 	= $r22['supplier_id'];
				
					$sql = " SELECT * from sma_party_mst WHERE 1 and id = '$to_supplier' " ;
					$q2  = mysqli_query($con, $sql);
					$r2  = mysqli_fetch_object($q2);
					$party_name 	= $r2->party_name;
					$party_email 	= $r2->party_email;
				
					//$modulePath = "athaangSI/tender/"; 
					//$baseurl1 =$baseurl."athaangSI/tender/".'edit.php?id='.$tender_id;
					
					$encrypted = encryptIt( $to_supplier );
	
					$modulePathSI = "athaangSI/tender/"; 
					$baseurl1 = $baseurl.$modulePathSI.'editSI.php?id='.$tender_id. '&direct=D&supplier_id='.$encrypted;
					
					$btn_var = '<a href="'. $baseurl1 .'" class="btn btn-danger" style = "background-color: #00a65a;color: #fff;border-color: #ddd; border-radius: 3px;-webkit-box-shadow: none;box-shadow: none;border: 1px solid transparent;padding: 8px 12px;text-align: center;font-weight: 400;" >Click here to view the tender</a>';
					
					$msg = 'Tender Number : '.$tender_id . ' ' . 'Dated : ' . date("d-m-Y");

					$status = 'Extended';
					include "tn_vender_mail.php";
					
				}
				
			}

	$baseurl.= $modulePath. "index.php";
	echo "<script>window.location.href='$baseurl';</script>";
	exit();
		
}


if($_GET['sub']=='Extend') {	

		$tender_id = $_GET['tender_id'];
		
?>
<!-- Select2 -->
  <link rel="stylesheet" href="<?php echo $baseurl . 'plugins/select2/select2.min.css'; ?> ">
  
	<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
             Tender
            <small>Extend </small>
		<!--<small><a href="<?php echo $help_link;?>" target="_blank" class="btn btn-success"><i class="fa fa-anchor"></i> Help</a>
		</small>-->
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>"> Tender</a></li>
            <li class="active">Extend</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- right column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <!--<div class="box-header with-border">
                        <h3 class="box-title">Create  Order</h3>
                    </div>-->
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
						<form id="form1" class="form-horizontal" method="post" 
										action="tender_extend.php?sub=Save">
						  <div class="box-body">
							
						  <!-- /.box-body -->
						  <!-- /.box-footer -->
						  <fieldset>
	
						<input type="hidden" name="tender_id" value="<?= $_GET['tender_id'];?>">
						<input type="hidden" name="extend" value="<?= $_GET['sub'];?>">
						
						<div class="form-group ">
							<section class="content-header">
								<h1>Quotation Extend </h1>
							</section>
						</div>
						
						<div class="form-group ">
							<div class="col-md-2">
								
							</div>	
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
							</div>	
					
					<span id="predit"> </span>
				
						<div class="box-footer">
									
							<div class="col-sm-3 text-right">
										<span>&nbsp;&nbsp;</span>
										
							</div>
							<div class="col-sm-4 text-right">
										
								<a class="btn btn-default" href="<?= $baseurl . 'tender/' ?>">Cancel</a>
										
								<span>&nbsp;&nbsp;</span>
								<input class="btn btn-primary" type="submit" value="Submit" name="otpok">
										
							</div>
							
						</div>	
								
					</fieldset>


<?php
	include("../footer.php");

}

function encryptIt( $q ) {
    $cryptKey  = 'qJB0rGtIn5UB1xG03efyCp';
    $qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
    return( $qEncoded );
}

function decryptIt( $q ) {
    $cryptKey  = 'qJB0rGtIn5UB1xG03efyCp';
    $qDecoded      = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
    return( $qDecoded );
}

?>


<!-- Select2 -->
<script src="<?php echo $baseurl . "plugins/select2/select2.full.js" ?>"></script>
	
	

<script>
   
    $(document).ready(function () {
        $('.datepicker').datepicker({
            "format": 'd/M/Y',
            "autoclose": true
        });
        $('.select2').select2();
        CKEDITOR.replace('reason');
        CKEDITOR.replace('reason1');
        CKEDITOR.replace('reason2');
        CKEDITOR.replace('reason3');
		$("#prItemsTable").DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": false
        });
    });


</script>	
	