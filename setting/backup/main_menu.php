<?php

include("../header.php");
$modulePath = "setting/main_menu.php?sub=list";

$pgname = "main_menu.php";
include("../viewonly.php");

?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Main Menu
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Main Menu</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of Main Menu</h3>
			  <?php if ( $viewonly!='Y'){ ?>
                <span class="pull-right"><a href="main_menu.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create Main Menu </a></span>
			  <?php } ?>	
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>Order No.</th>
			<th>Main Menu</th>
			<th>Status</th>
			
			<th style="text-align:right;">Action</th>
			
		</tr>
	</thead>
<tbody>
<?php

	$modulePath1 = "setting/";
	$sql="SELECT * from sma_main_menu";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		$baseurl1 = $baseurl.$modulePath1.'main_menu.php?sub=edit&id='.$row["id"];
		
		$status = $row['status'];
		if($status=='Y'){
			$status = 'Active';
		}	
		else {
			$status = 'Inactive';
		}	
		
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "main_menu.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">

		<td width="10%"><?php echo $row['order_no'];?></td>
		<td width="50%"><?php echo $row['menu_name'];?></td>
		<td width="10%"><?php echo $status;?></td>
		

		<td width="10%" style="text-align:right;">
		<a href="main_menu.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
<!--<a href="main_menu.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
		
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
		$sql="delete from sma_main_menu where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="main_menu.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){
?>

<?php
 
	if(isset($_POST['Save'])){
			$menu_name		= $_POST['menu_name'];
			$order_no		= $_POST['order_no'];
			
			$status = 'Y';	
  			$sql="insert into sma_main_menu ( menu_name, order_no, status ) Values( '$menu_name', '$order_no', '$status' )";
					
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}

			
			echo "Main Menu successful added";
			echo '<script>window.location.href="main_menu.php?sub=list";</script>';
		}
	

?>

    <section class="content-header">
        <h1>
            Main Menu
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Main Menu</a></li>
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
            <form class="form-horizontal" action="main_menu.php?sub=add" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      
						<div class="form-group">
							<label class="col-lg-2 control-label">Main Menu</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="menu_name" name="menu_name" placeholder="" autocomplete="off" value="">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Order No.</label>
							<div class="col-md-1">
								<input type="text" class="form-control" id="order_no" name="order_no" placeholder="" autocomplete="off" value="">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Menu Icon</label>
							<div class="col-md-8">
								<input type="text" class="form-control" id="menu_icon" name="menu_icon" placeholder="" autocomplete="off" value="<?php echo $row['menu_icon'];?>">
							</div>
						</div>
						
                        <!--<div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="main_menu.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
                        </div>-->
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
 //echo $_POST['Save'];
 
	if(isset($_POST['Save'])){
		
			$id					= $_POST['id']; 
			$menu_name			= $_POST['menu_name'];
			$order_no			= $_POST['order_no'];
			$menu_icon			= $_POST['menu_icon'];
			$status				= $_POST['status'];
			
  			$sql="update sma_main_menu set 	menu_name = '$menu_name', 
						order_no 	= '$order_no',
						menu_icon	= '$menu_icon',
						status	 	= '$status'
					where id='$id' ";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);

			if(!empty($error)){echo $error; exit();}
			
			echo '<script>window.location.href="main_menu.php?sub=list";</script>';
	}
		
		$id = $_GET['id'];
		$sql="Select * from sma_main_menu where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

		if ( $viewonly=='Y'){
			$readonly = 'READONLY';
		}
?>

    <section class="content-header">
        <h1>
            Main Menu
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Main Menu</a></li>
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
            <form class="form-horizontal" action="main_menu.php?sub=edit" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Main Menu</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="menu_name" name="menu_name" placeholder="" value="<?php echo $row['menu_name'];?>" <?= $readonly; ?>  >
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Order No.</label>
							<div class="col-md-1">
								<input type="text" class="form-control" id="order_no" name="order_no" placeholder="" autocomplete="off" value="<?php echo $row['order_no'];?>"  <?= $readonly; ?> >
							</div>
						</div>

						<div class="form-group">
							<label class="col-lg-2 control-label">Menu Icon</label>
							<div class="col-md-8">
								<input type="text" class="form-control" id="menu_icon" name="menu_icon" placeholder="" autocomplete="off" value="<?php echo $row['menu_icon'];?>">
							</div>
						</div>

						<?php 
						
								$active 	= '';
								$inactive	= '';
								$status = $row['status'];
								if( $status=='Y' ){
									$active = 'checked';
								}
								else if( $status=='N' ){
									$inactive = 'checked';
								}
								
						?>

						<div class="form-group">	
						
							<label for="user_category" class="control-label col-sm-2">Status </label>
							<div class="col-sm-2" style="padding-top: 6px;">
								<input type="radio" class="minimal" <?php echo $active; ?> name="status" id="status" value="Y" > Active &nbsp;
                            	<input type="radio"  class="minimal" <?php echo $inactive; ?> name="status" id="status" value="N" > Inactive &nbsp;
								
							</div>
						</div>
					
					<?php if ( $viewonly!='Y'){ ?>
   							
   						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; ?>
								<a href="<?php echo $baseurl."setting/main_menu.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a>
							</div>
							<?php $baseurl1 = $baseurl.$modulePath;?>
							<div class="col-sm-6 text-right">
								<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Cancel</a>
								<span>&nbsp;&nbsp;</span>
								<input class="btn btn-primary" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
							</div>
						</div>
                     <?php } ?>
					<?php	if ( $viewonly=='Y'){ ?>
						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; ?>
							</div>
							<?php $baseurl1 = $baseurl.$modulePath;?>
							<div class="col-sm-6 text-right">
								<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Cancel</a>
								<span>&nbsp;&nbsp;</span>
								
							</div>
						</div>
					<?php } ?>
					
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
      
<?php } 	?>


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
