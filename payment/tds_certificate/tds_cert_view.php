<?php

include("../../header.php");
//$modulePath = "setting/role_mst.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Vendor TDS Certificate view
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Vendor TDS Vertificate</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of TDS</h3>
                
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>Vendor Name</th>
			<th>Comp Code</th>
			<th>Pan No.</th>
			<th>Period</th>
			
			<th>Upload Date</th>
			
		</tr>
	</thead>
<tbody>
<?php

	
	$sql="SELECT * from tds_cert_upload";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		
		$file_name = $baseurl.'payment/tds_certificate/success/'.$row['comp_code'].'/'.$row['file_name'];
				
	?>
	<tr>

		<td width="30%"><?php echo $row['vendor_name'];?></td>
		<td width="10%"><?php echo $row['comp_code'];?></td>
		<td width="20%"><a href="<?php echo $file_name;?>" target="_blank" > <?php echo $row['panno'];?></a></td>
		<td width="20%"><?php echo $row['period'];?></td>
		
		<td width="10%"><?php echo date('d-m-Y', strtotime($row['upload_date']));?></td>

    </tr>
	
	<?php }?>
</tbody> 
</table>
	</div>
    </div>
</div>	

    <?php }?>



<?php 	
		include("../../footer.php");	
?>
<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>

<script>
    $(function () {
        $("#prtable").DataTable();
    });
</script>

</body>
</html>
