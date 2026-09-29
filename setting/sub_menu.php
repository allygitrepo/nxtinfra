<?php

include("../header.php");
$modulePath = "setting/sub_menu.php?sub=list";

$pgname = "sub_menu.php";
include("../viewonly.php");

?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Sub Menu
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Sub Menu</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of Sub Menu</h3>
			  <?php if ( $addonly=='Y'){ ?>
                <span class="pull-right"><a href="sub_menu.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create Sub Menu </a></span>
			  <?php } ?>	
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>Main Menu Id</th>
			<th>Main Menu Name</th>
			<th>Sub Menu Order</th>
			<th>Sub Menu</th>
			<th>Target</th>
			<th>Status</th>
			
			<th style="text-align:right;">Action</th>
			
		</tr>
	</thead>
<tbody>
<?php

	$modulePath1 = "setting/";
	$sql="SELECT * from sma_menu";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		$baseurl1 = $baseurl.$modulePath1.'sub_menu.php?sub=edit&id='.$row["id"];
				
		$menu_id = 	$row['menu_id'];
		$sql="SELECT * from sma_main_menu where id = '$menu_id' ";
		$res = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2 = mysqli_fetch_array($res);
		$main_menu_name = $r2['menu_name'];
		
		
		$status = $row['status'];
		if($status=='Y'){
			$status = 'Active';
		}	
		else {
			$status = 'Inactive';
		}	
		
		//SELECT * FROM `sma_menu` a, sma_main_menu b where a.menu_id = b.id order by b.order_no, a.order_no
		
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "sub_menu.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		
		<td width="10%"><?php echo $menu_id;?></td>
		<td width="20%"><?php echo $main_menu_name;?></td>
		<td width="10%"><?php echo $row['order_no'];?></td>
		<td width="20%"><?php echo $row['sub_menu_name'];?></td>
		<td width="20%"><?php echo $row['target'];?></td>
		<td width="10%"><?php echo $status;?></td>
		

		<td width="10%" style="text-align:right;">
		<a href="sub_menu.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
<!--<a href="sub_menu.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
		
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
		$sql="delete from sma_menu where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="sub_menu.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){
?>

<?php
 
	if(isset($_POST['Save'])){
		
			$sub_menu_name		= $_POST['sub_menu_name'];
			$main_menu			= $_POST['main_menu'];
			$order_no			= $_POST['order_no'];
			$target				= $_POST['target'];
			$source				= $_POST['source'];
			$menu_icon			= $_POST['menu_icon'];
			$help_link			= $_POST['help_link'];
			
			$status = 'Y';	
  			$sql = "insert into sma_menu ( menu_id, sub_menu_name, order_no, target, source, status, menu_icon, help_link ) 
					VALUES( '$main_menu', '$sub_menu_name', '$order_no', '$target', '$source', '$status', '$menu_icon', '$help_link')";
					
			$query 	= mysqli_query($con, $sql);
			$error 	= mysqli_error($con);
			$menu_id = mysqli_insert_id($con);
			if(!empty($error)){echo $error; exit();}

			$sql = " SELECT * FROM sma_role order by id ";
			$res5 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			while($r5 = mysqli_fetch_array($res5)){
				
				$user_role	= $r5['id'];
				
				$sql = "INSERT into useraccess (userid, user_role, main_menu_id, menu_id, manu_name, menuonly) VALUES( '$user_role', '$user_role', '$main_menu', '$menu_id', '$sub_menu_name' , 'Y' )";
				$query=mysqli_query($con, $sql);
				echo mysqli_error($con);
						
			}			
					
			echo "Sub Menu successful added";
			echo '<script>window.location.href="sub_menu.php?sub=list";</script>';
		}
	

?>

    <section class="content-header">
        <h1>
            Sub Menu
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Sub Menu</a></li>
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
            <form class="form-horizontal" action="sub_menu.php?sub=add" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      
						<div class="form-group">
							<label class="col-lg-2 control-label">Main Menu</label>
							<div class="col-md-3">
								<select class="form-control" name="main_menu" id="main_menu" required="true" >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_main_menu  order by order_no ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($main_menu == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['menu_name'];?></option>
										<?php } ?>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Sub Menu Name</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="sub_menu_name" name="sub_menu_name" placeholder="" autocomplete="off" value="">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Order No.</label>
							<div class="col-md-1">
								<input type="text" class="form-control" id="order_no" name="order_no" placeholder="" autocomplete="off" value="">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Source Link</label>
							<div class="col-md-8">
								<input type="text" class="form-control" id="source" name="source" placeholder="" autocomplete="off" value="">
							</div>
						</div>
						
                        <div class="form-group">
							<label class="col-lg-2 control-label">Target</label>
							<div class="col-md-2">
								<select class="form-control" name="target" id="target" required="true" >
									<option value=""> Select </option>
									<option value="Self"> Self </option>
									<option value="Blank"> Blank </option>
								</select>	
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Help Link</label>
							<div class="col-md-10">
								<textarea type="text" class="form-control" id="help_link" name="help_link" placeholder="" autocomplete="off"><?php echo $row['help_link'];?></textarea>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Menu Icon</label>
							<div class="col-md-8">
								<input type="text" class="form-control" id="menu_icon" name="menu_icon" placeholder="" autocomplete="off" value="<?php echo $row['menu_icon'];?>">
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
 //echo $_POST['Save'];
 
	if(isset($_POST['Save'])){
		
			$id					= $_POST['id']; 
			$sub_menu_name		= $_POST['sub_menu_name'];
			$main_menu			= $_POST['main_menu'];
			$order_no			= $_POST['order_no'];
			$target				= $_POST['target'];
			$source				= $_POST['source'];
			$status				= $_POST['status'];
			$menu_icon			= $_POST['menu_icon'];
			$help_link			= $_POST['help_link'];
			$supplier_menu		= $_POST['supplier_menu'];
			
  			$sql="update sma_menu set sub_menu_name = '$sub_menu_name', 
						menu_id 	= '$main_menu',
						order_no    = '$order_no',
						target		= '$target',
						source		= '$source',
						status		= '$status',
						menu_icon	= '$menu_icon',
						help_link	= '$help_link',
						supplier_menu = '$supplier_menu'
					where id='$id' ";
					
//echo $sql	= "update useraccess  set main_menu_id = '$menu_id' where menu_id = '$id' ";
//exit();
								
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			$sql 	= "select * from sma_menu where id = '$id' ";
			$query	=mysqli_query($con, $sql);
			$error	= mysqli_error($con);
			$row 	= mysqli_fetch_array($query);	
			$menu_id = $row['menu_id'];
			
			$sql	= "update useraccess  set main_menu_id = '$menu_id' where menu_id = '$id' ";
			$query	= mysqli_query($con, $sql);
			$error	= mysqli_error($con);
			
			//if(!empty($error)){echo $error; exit();}
			
			echo '<script>window.location.href="sub_menu.php?sub=list";</script>';
	}
		
		$id = $_GET['id'];
		$sql="Select * from sma_menu where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	
		
		/* if ( $viewonly=='Y'){
			$readonly = 'READONLY';
		} */
		
?>

    <section class="content-header">
        <h1>
            Sub Menu
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Sub Menu</a></li>
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
            <form class="form-horizontal" action="sub_menu.php?sub=edit" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Main Menu</label>
							<div class="col-md-3">
								<select class="form-control" name="main_menu" id="main_menu" required="true"  <?= $readonly; ?> >
									<option value=""> Select </option>
										<?php $sql = "select * from sma_main_menu  order by order_no ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['id'];?>"  <?php echo ($row['menu_id'] == $r2['id'])?'selected="selected"':'';?> ><?php echo $r2['menu_name'];?></option>
										<?php } ?>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Sub Menu Name</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="sub_menu_name" name="sub_menu_name" placeholder="" value="<?php echo $row['sub_menu_name'];?>"  <?= $readonly; ?> >
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Order No.</label>
							<div class="col-md-1">
								<input type="text" class="form-control" id="order_no" name="order_no" placeholder="" autocomplete="off" value="<?php echo $row['order_no'];?>"  <?= $readonly; ?> >
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Source Link</label>
							<div class="col-md-8">
								<input type="text" class="form-control" id="source" name="source" placeholder="" autocomplete="off" value="<?php echo $row['source'];?>"  <?= $readonly; ?> >
							</div>
						</div>
						
                        <div class="form-group">
							<label class="col-lg-2 control-label">Target</label>
							<div class="col-md-2">
								<select class="form-control" name="target" id="target" required="true"  <?= $readonly; ?>  >
									<option value=""> Select </option>
									<option value="Self" <?php echo ($row['target'] == 'Self' )?'selected="selected"':'';?> > Self </option>
									<option value="Blank" <?php echo ($row['target'] == 'Blank' )?'selected="selected"':'';?> > Blank </option>
								</select>	
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
							<label class="col-lg-2 control-label">Help Link</label>
							<div class="col-md-10">
								<textarea type="text" class="form-control" id="help_link" name="help_link" placeholder="" autocomplete="off"><?php echo $row['help_link'];?></textarea>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Menu Icon</label>
							<div class="col-md-8">
								<input type="text" class="form-control" id="menu_icon" name="menu_icon" placeholder="" autocomplete="off" value="<?php echo $row['menu_icon'];?>">
							</div>
						</div>
						
						<div class="form-group">	
						
							<label for="user_category" class="control-label col-sm-2">Status </label>
							<div class="col-sm-2" style="padding-top: 6px;">
								<input type="radio" class="minimal" <?php echo $active; ?> name="status" id="status" value="Y" > Active &nbsp;
                            	<input type="radio"  class="minimal" <?php echo $inactive; ?> name="status" id="status" value="N" > Inactive &nbsp;
								
							</div>
						</div>
						
						<?php 
						
								$activesi 	= '';
								$inactivesi	= '';
								$supplier_menu = $row['supplier_menu'];
								if( $supplier_menu=='Y' ){
									$activesi = 'checked';
								}
								else if( $supplier_menu=='N' ){
									$inactivesi = 'checked';
								}
								
						?>
						<div class="form-group">	
						
							<label for="user_category" class="control-label col-sm-2">For Supplier Menu </label>
							<div class="col-sm-2" style="padding-top: 6px;">
								<input type="radio" class="minimal" <?php echo $activesi; ?> name="supplier_menu" id="supplier_menu" value="Y" > Active &nbsp;
                            	<input type="radio"  class="minimal" <?php echo $inactivesi; ?> name="supplier_menu" id="supplier_menu" value="N" > Inactive &nbsp;
								
							</div>
						</div>
						
					
					<?php if ( $addonly=='Y'){ ?>
   							
   						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; ?>
								<a href="<?php echo $baseurl."setting/sub_menu.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a>
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
