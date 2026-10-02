<?php

include("../header.php");
$modulePath = "setting/financial_year.php?sub=list";

$pgname = "designation.php";

//Mrunmayee start 
$help_code = $modulePath;
include "../help_code.php";

$pgname = $help_code;
//Mrunmayee end

include("../viewonly.php");
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Financial Year
        <!--Mrunmayee satrt-->
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Financial Year</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of Financial Year</h3>
                <span class="pull-right">&nbsp;&nbsp;&nbsp;<a href="user_export_func.php?sub=financial_year" class="btn btn-primary">Export</a></span>
			  <?php if ( $viewonly!='Y'){ ?>
                <span class="pull-right"><a href="financial_year.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create  </a></span>
			<?php } ?>	
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<td>From Date</th>
			<td>To Date</th>
			<td>Account Year</th>
			<td>PO Last Number</th>
			<td>Active</th>
			<th style="text-align:right;">Action</th>
			
		</tr>
	</thead>
<tbody>
<?php

	$modulePath1 = "setting/";
	$sql="SELECT * from sma_financial_year order by id desc ";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		$baseurl1 = $baseurl.$modulePath1.'financial_year.php?sub=edit&id='.$row["id"];
		
		$from_date 			= date('d-m-Y', strtotime($row['from_date']));
		$to_date 			= date('d-m-Y', strtotime($row['to_date']));
		$short_fy_code 		= $row['short_fy_code'];
		$po_last_number     = $row['po_last_number'];
		
		$status 		= $row['status'];
		if(empty($status)){
			$status='N';	
		}
		
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "financial_year.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">

		<td width="20%"><?php echo $from_date;?></td>
		<td width="20%"><?php echo $to_date;?></td>
		<td width="20%"><?php echo $short_fy_code;?></td>
		<td width="10%" style="text-align:right;"><?php echo $po_last_number;?></td>
		<td width="20%"><?php echo $status;?></td>

		<td width="10%" style="text-align:right;">
		<a href="financial_year.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
<!--<a href="financial_year.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
		
		</td>
    </tr>
	</a>
	
	<?php }?>
</tbody> 
</table>
	</div>
    </div>
</div>	

    <?php }?>


<?php  
	if($_GET['sub'] == 'delete'){ 
        $id = $_GET['id'];
		$sql="delete from sma_financial_year where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="financial_year.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){
?>

<?php
 
		if(isset($_POST['Save'])){
		
			$from_date			= date('Y-m-d', strtotime($_POST['from_date']));
			$to_date			= date('Y-m-d', strtotime($_POST['to_date']));
			$short_fy_code		= date('Y', strtotime($_POST['from_date'])).'-'.date('Y', strtotime($_POST['to_date']));
			$status				= $_POST['status'];
			$finyear_prefix		= $_POST['finyear_prefix'];
			$po_last_number     = $_POST['po_last_number'];
			
  			$sql="insert into sma_financial_year ( from_date, to_date, short_fy_code, status, finyear_prefix,po_last_number ) 
					VALUES( '$from_date', '$to_date', '$short_fy_code', '$status', '$finyear_prefix' , '$po_last_number' )";
					
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}

			
			echo "Successful added";
			echo '<script>window.location.href="financial_year.php?sub=list";</script>';
		}
	

?>

    <section class="content-header">
        <h1>
            Financial Year
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Financial Year</a></li>
            <li class="active">Create</li>
        </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
            <!-- form start -->
            <form class="form-horizontal" action="financial_year.php?sub=add" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      
						<div class="form-group">
							<label class="col-lg-2 control-label">Account Year From<span class="f_req">&nbsp;*</span></label>
							<div class="col-md-2" class="input-append">
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="from_date" name="from_date" value="<?php echo $from_date; ?>" required >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
							</div>	

							<label class="col-lg-2 control-label"> To<span class="f_req">&nbsp;*</span></label>
							<div class="col-md-2" class="input-append">
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="to_date" name="to_date" value="<?php echo $to_date; ?>" required >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
							</div>								
								
						</div>
					
						<div class="form-group">
							<label class="col-lg-2 control-label">Prefix to be used in PO : </label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="finyear_prefix" name="finyear_prefix" placeholder="" autocomplete="off" value="<?= $row['finyear_prefix']; ?>">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">PO Last Number: </label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="po_last_number" name="po_last_number" style="text-align:right;" placeholder="" autocomplete="off" value="<?= $row['po_last_number']; ?>">
							</div>
						</div>

						<div class="form-group">
							
							<div class="col-md-2">
								<label class=" control-label"> Status </label>
							</div>	
							<div class="col-md-2">
								<input type="radio" id="status" name="status" checked value="Y" > Active &nbsp;
								<input type="radio" id="status" name="status" value="N" > Inactive
							</div>
						
						</div>
						
						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; ?>
								
							</div>
							<?php $baseurl1 = $baseurl.$modulePath;?>
							<div class="col-sm-6 text-right">
								<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Cancel</a>
								<span>&nbsp;&nbsp;</span>
								<input class="btn btn-primary" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
							</div>
						</div>
                        
                    </fieldset>
				</div>	
            </form>
          </div>
          
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
      </div>
  <!-- /.content-wrapper -->
</section>  
<?php } 	?>


<?php if($_GET['sub'] == 'edit'){

?>
	
<?php 
// echo $_POST['Save'];
 
	if(isset($_POST['Save'])){
			$id					= $_POST['id']; 
			$status				= $_POST['status']; 
			$from_date			= date('Y-m-d', strtotime($_POST['from_date']));
			$to_date			= date('Y-m-d', strtotime($_POST['to_date']));
			$finyear_prefix		= $_POST['finyear_prefix'];
			$short_fy_code		= date('Y', strtotime($_POST['from_date'])).'-'.date('Y', strtotime($_POST['to_date']));
			
			$po_last_number     = $_POST['po_last_number'];
  			$sql="update sma_financial_year set from_date	= '$from_date',
								to_date			= '$to_date',
								short_fy_code	= '$short_fy_code',
								status			= '$status',
								finyear_prefix	= '$finyear_prefix',
								po_last_number  = '$po_last_number'
					WHERE id='$id' ";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);

			if(!empty($error)){echo $error; exit();}
			
			echo '<script>window.location.href="financial_year.php?sub=list";</script>';

	}
		
		$id = $_GET['id'];
		$sql="Select * from sma_financial_year where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

		if ( $viewonly=='Y'){
			$readonly = 'READONLY';
		}

?>

    <section class="content-header">
        <h1>
            Financial Year
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Financial Year</a></li>
        </ol>
    </section>
		
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            
		<!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box-body">
            <!-- form start -->
            <form class="form-horizontal" action="financial_year.php?sub=edit" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
						
						
					<?php
						$from_date 	= date('d-m-Y', strtotime($row['from_date']));
						$to_date 	= date('d-m-Y', strtotime($row['to_date']));
					?>
					
						<div class="form-group">
							<label class="col-lg-2 control-label">Account Year From<span class="f_req">&nbsp;*</span></label>
							<div class="col-md-2" class="input-append">
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="from_date" name="from_date" value="<?php echo $from_date; ?>" required >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
							</div>	

							<label class="col-lg-2 control-label"> To<span class="f_req">&nbsp;*</span></label>
							<div class="col-md-2" class="input-append">
								<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
									<input type="text" class="form-control" id="to_date" name="to_date" value="<?php echo $to_date; ?>" required >
									<div class="input-group-addon">
										<i class="fa fa-calendar-alt"></i>
									</div>
								</div>
							</div>								
								
						</div>
						
						
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Account Year Short Code </label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="account_year" name="account_year" readonly placeholder="" autocomplete="off" value="<?= $row['short_fy_code']; ?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-lg-2 control-label">Prefix to be used in PO : </label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="finyear_prefix" name="finyear_prefix" placeholder="" autocomplete="off" value="<?= $row['finyear_prefix']; ?>">
							</div>
						</div>

                        <div class="form-group">
							<label class="col-lg-2 control-label">PO Last Number: </label>
							<div class="col-md-2">
								<input type="text" class="form-control" id="po_last_number" name="po_last_number" style="text-align:right;" placeholder="" autocomplete="off" value="<?= $row['po_last_number']; ?>">
							</div>
						</div>

						
						<div class="form-group">
							<div class="col-md-1">
								<label class="control-label">&nbsp;</label>
							</div>

							<?php 
								$status = $row['status'];
								if($status=='Y'){
									$selected_draft = 'checked';
								}
								else if($status=='N'){
									$selected_final = 'checked';
								}
							?>
							
							<div class="col-md-1">
								<label class=" control-label"> Status </label>
							</div>	
							<div class="col-md-2">
								<input type="radio" id="status" <?php echo $selected_draft; ?> name="status" value="Y" > Active &nbsp;
								<input type="radio" id="status" <?php echo $selected_final; ?> name="status" value="N" > Inactive
							</div>
							
						</div>
						
						<div class="box-footer">
							<div class="col-sm-6">
								<a href="<?php echo $baseurl."setting/financial_year.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a> 
							</div>
							<?php $baseurl1 = $baseurl.$modulePath;?>
							<div class="col-sm-6 text-right">
								<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Cancel</a>
								
								<input class="btn btn-primary" type="submit" value="Save" name="Save">
							</div>
						</div>
                    
                    </fieldset>
				</div>	
            </form>
          </div>
          
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
    </div>
  </div>
</section>
      
<?php 
}	

?>


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
</script>

</body>
</html>
