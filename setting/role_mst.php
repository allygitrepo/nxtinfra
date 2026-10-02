<?php

include("../header.php");
$modulePath = "setting/role_mst.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Role
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Role</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of Role</h3>
                <span class="pull-right">&nbsp;&nbsp;&nbsp;<a href="user_export_func.php?sub=role" class="btn btn-primary">Report</a></span>
                <span class="pull-right"><a href="role_mst.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create Role </a></span>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>Role</th>
			<th style="text-align:right;">Action</th>
			
		</tr>
	</thead>
<tbody>
<?php

	$modulePath1 = "setting/";
	$sql="SELECT * from sma_role";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		$baseurl1 = $baseurl.$modulePath1.'role_mst.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "role_mst.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">

		<td width="20%"><?php echo $row['role'];?></td>
		

		<td width="10%" style="text-align:right;">
		<a href="role_mst.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
<!--<a href="role_mst.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
		
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
		$sql="delete from sma_role where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="role_mst.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){
?>

<?php
 
	if(isset($_POST['Save'])){
			$role		= $_POST['role'];
			
			 $sql="SELECT * FROM sma_role where 1 and role = '$role' ";
        		mysqli_query($con, $sql);
        		$rowaffect = mysqli_affected_rows($con);
        		if($rowaffect>0){
        		    echo "<script>alert('Error: Role Already available ...');</script>";
        		    echo '<script>window.location.href="role_mst.php?sub=add";</script>';
        		    exit();
        		}
        		
        		
  			$sql="insert into sma_role (role) Values('$role')";
					
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}

			$sql="Select max(id) as id from sma_role";
			$query = mysqli_query($con, $sql);
			$row = mysqli_fetch_array($query);	
			
			$user_role = $row['id'];
			
			$sql = " SELECT * FROM sma_main_menu order by order_no ";
			$res5 = mysqli_query($con, $sql);
			echo mysqli_error($con);
			while($r5 = mysqli_fetch_array($res5)){
			
				$main_menu_id	= $r5['id'];
				$menu_name		= $r5['menu_name'];
				
				
				$sql = "INSERT into useraccess (userid, user_role, main_menu_id, manu_name, menuonly) Values('$user_role', '$user_role', '$main_menu_id', '$menu_name' , 'Y')";
				$query=mysqli_query($con, $sql);
				echo mysqli_error($con);
				
				//echo $sql; exit();
				
				$sql = " SELECT a.id as 'sub_menu_id' , a.menu_id as 'main_menu_id', a.sub_menu_name, a.target, a.source as 'source_link', a.order_no as sub_menu_no 
				FROM `sma_menu` a, sma_main_menu b 
				WHERE a.menu_id = b.id and a.menu_id = '$main_menu_id' order by b.order_no, a.order_no ";
				$res4 = mysqli_query($con, $sql);
				echo mysqli_error($con);
				while($r4 = mysqli_fetch_array($res4)){
				
					$sub_menu_name 	= $r4['sub_menu_name'];
					$target 		= $r4['target'];
					$source_link 	= $r4['source_link'];
					$main_menu_id	= $r4['main_menu_id'];
					$sub_menu_id	= $r4['sub_menu_id'];
					
					$sql="INSERT into useraccess (userid, user_role, main_menu_id, menu_id, manu_name, menuonly) Values( '$user_role', '$user_role', '$main_menu_id', '$sub_menu_id', '$sub_menu_name' , 'Y' )";
					$query=mysqli_query($con, $sql);
					echo mysqli_error($con);
					
				}
				
			}
			//exit();
			
			
			/* for($i=1;$i<41;$i++){
				$sql="insert into useraccess (userid, user_role, menu_id) Values('$user_role', '$user_role', '$i')";
				$query=mysqli_query($con, $sql);
			} */
			
			echo "Role successful added";
			echo '<script>window.location.href="role_mst.php?sub=list";</script>';
		}
	

?>

    <section class="content-header">
        <h1>
            Role
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Role</a></li>
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
            <form class="form-horizontal" action="role_mst.php?sub=add" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      
						<div class="form-group">
							<label class="col-lg-2 control-label">Role</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="role" name="role" placeholder="" autocomplete="off" value=""  onchange="getDuplicate(this.value);" >
								<span id="getDuplicate" style="color:red;" ></span>
							</div>
						</div>
						
                        <!--<div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="role_mst.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
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
 echo $_POST['Save'];
 
	if(isset($_POST['Save'])){
			$id				= $_POST['id']; 
			$role			= trim($_POST['role']);
			$role_pre	    = trim($_POST['role_pre']);
			
			if($role_pre!=$role){
			    $sql="SELECT * FROM sma_role where 1 and role = '$role' ";
        		mysqli_query($con, $sql);
        		$rowaffect = mysqli_affected_rows($con);
        		if($rowaffect>0){
        		    echo "<script>alert('Error: Role Already available ...');</script>";
        		    echo "<script>window.location.href='role_mst.php?sub=edit&id=$id';</script>";
        		    exit();
        		}
			}
			
  			$sql="update sma_role set 	role ='$role'		
				where id='$id' ";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);

			if(!empty($error)){echo $error; exit();}
			
			echo '<script>window.location.href="role_mst.php?sub=list";</script>';
	}
		
		$id = $_GET['id'];
		$sql="Select * from sma_role where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

?>

    <section class="content-header">
        <h1>
            Role
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Role</a></li>
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
            <form class="form-horizontal" action="role_mst.php?sub=edit" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Role</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="role" name="role" placeholder="" value="<?php echo $row['role'];?>" >
								
								<input type="hidden" id="role_pre" name="role_pre" value="<?php echo $row['role'];?>" >
								
							</div>
						</div>
						
                        <!--<div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="role_mst.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
                        </div>-->

   						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; 
								//Mrunmayee started
								$sq2 = "SELECT COUNT(*) as total FROM `sma_user` where role = '$did' or primary_role= '$did'";
								$q2  = mysqli_query($con, $sq2);
								$r2  = mysqli_fetch_assoc($q2);
								$mycount = $r2['total'];
								
								$sq4 = "SELECT COUNT(*) as total FROM `sma_workflow` where approval_role_1 = '$did' or approval_role_2 = '$did' or approval_role_3 = '$did'";
								$q4  = mysqli_query($con, $sq4);
								$r4 = mysqli_fetch_assoc($q4);
								$mycount1 = $r4['total'];

                                $sq3 = "SELECT COUNT(*) as total FROM `useraccess` where user_role = '$did'";
								$q3  = mysqli_query($con, $sq3);
								$r3  = mysqli_fetch_assoc($q3);
								$mycount2 = $r3['total'];
								
								if ($mycount <= 0 and $mycount1 <= 0 and $mycount2 <= 0) {?>
								<a href="<?php echo $baseurl."setting/role_mst.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a>
								<?php } //Mrunmayee ended?>
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

    function getDuplicate(id){
		var sub    = 'sub5';
//alert(sub);
        var table_name = 'sma_role';
        var col_name = 'role';
        
        $('.HideSave').show();
        $('#getDuplicate').html('');
		var strURL = "account_func.php";
		$.post(strURL,{id:id,col_name:col_name,table_name:table_name,sub5:sub},function(result){
		      $('#getDuplicate').html(result);
		      
		});
		
	}
	
</script>

</body>
</html>
