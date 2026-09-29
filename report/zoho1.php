<?php

include("../header.php");
$modulePath = "budget/budget_used_SI.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">


	<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

		<section class="content-header">
		  
		  <ol class="breadcrumb">
			<li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
			<li class="active"></li>
		  </ol>
		</section>

		<div class="col-md-12">
			<div class="box">
				<iframe src="https://analytics.zoho.in/open-view/192229000000034035/4cb8fb1e6ccfc2043f5203dd81648c16" height="570" width="1100" title="Iframe Example">
				
				</iframe>
				
				<?php	
				//	echo '<script>window.location.href="https://analytics.zoho.in/open-view/192229000000030543";</script>';
				?>
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

</script>


