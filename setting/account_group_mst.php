<?php

include("../header.php");
$modulePath = "setting/account_group_mst.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Account Group
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Account Group</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of Account Group</h3>
                <span class="pull-right"><a href="account_group_mst.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create Account Group </a></span>
				<span class="pull-right">
					<a href="account_export.php?sub=accgrp" name="btnAdd" target="_blank" class="btn btn-info"><i class="splashy-document_letter_add"></i>Export</a>&nbsp;&nbsp;&nbsp;&nbsp;
				</span>
			</div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>Account Group</th>
			<th style="text-align:right;">Action</th>
			
		</tr>
	</thead>
<tbody>
<?php

	$modulePath1 = "setting/";
	$sql="SELECT * from sma_account_group";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		$baseurl1 = $baseurl.$modulePath1.'account_group_mst.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "account_group_mst.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">

		<td width="20%"><?php echo $row['account_group'];?></td>
		

		<td width="10%" style="text-align:right;">
		<a href="account_group_mst.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
<!--<a href="account_group_mst.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
		
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
		
			$sql	="Select * from sma_account_group where id ='$id'";
			$query 	= mysqli_query($con, $sql);
			$row 	= mysqli_fetch_array($query);
			$account_group 			= $row['account_group'];
			
			$company_id 	= '';
			$pgname 		= "account_group_mst.php";
			include "../viewonly.php";
			$description 	= $account_group;
		    $affect 		= 'Deleted';
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
		$sql="delete from sma_account_group where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="account_group_mst.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){
?>

<?php
 
	if(isset($_POST['Save'])){
		
			$account_group		= $_POST['account_group'];
			
			$sql="SELECT * FROM sma_account_group where 1 and account_group = '$account_group' ";
        		mysqli_query($con, $sql);
        		$rowaffect = mysqli_affected_rows($con);
        		if($rowaffect>0){
        		    echo "<script>alert('Error: account_group Already available ...');</script>";
        		    echo '<script>window.location.href="account_group_mst.php?sub=add";</script>';
        		    exit();
        		}
        	
  			$sql="insert into sma_account_group (account_group) Values('$account_group')";
					
			$query = mysqli_query($con, $sql);
			$error = mysqli_error($con);
			if(!empty($error)){echo $error; exit();}
			
			$company_id 	= '';
			$pgname 		= "account_group_mst.php";
			include "../viewonly.php";
			$description 	= $account_group;
		    $affect 		= 'Added';
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
			//echo "Account Group successful added";
			echo '<script>window.location.href="account_group_mst.php?sub=list";</script>';
			
	}

?>

    <section class="content-header">
        <h1>
            Account Group
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Account Group</a></li>
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
            <form class="form-horizontal" action="account_group_mst.php?sub=add" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      
						<div class="form-group">
							<label class="col-lg-2 control-label">Account Group</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="account_group" name="account_group" placeholder="" autocomplete="off" value="" onchange="getDuplicate(this.value);" >
								<span id="getDuplicate" style="color:red;" ></span>
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


<?php 

	if($_GET['sub'] == 'edit'){

?>
	
<?php 
 
	if(isset($_POST['Save'])){
		
			$id					= $_POST['id']; 
			$account_group		= trim($_POST['account_group']);
			$account_group_prev		= trim($_POST['account_group_prev']);
            if($account_group_prev !=$account_group){
			    $sql="SELECT * FROM sma_units where 1 and account_group ='$account_group' ";
        		mysqli_query($con, $sql);
        		$rowaffect = mysqli_affected_rows($con);
        		if($rowaffect>0){
        		    echo "<script>alert('Error: Account Group Already available ...');</script>";
        		    echo "<script>window.location.href='account_group.php?sub=edit&id=$id';</script>";
        		    exit();
        		}
			}
			
  			$sql="update sma_account_group set account_group = '$account_group' where id='$id' ";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);

			if(!empty($error)){echo $error; exit();}
			
			$company_id 	= '';
			$pgname 		= "account_group_mst.php";
			include "../viewonly.php";
			$description 	= $account_group;
		    $affect 		= 'Modified';
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
			echo '<script>window.location.href="account_group_mst.php?sub=list";</script>';
	}
		
		$id = $_GET['id'];
		$sql="Select * from sma_account_group where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

?>

    <section class="content-header">
        <h1>
            Account Group
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Account Group</a></li>
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
            <form class="form-horizontal" action="account_group_mst.php?sub=edit" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Account Group</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="account_group" name="account_group" placeholder="" value="<?php echo $row['account_group'];?>" >
								
								<input type="hidden"  name="account_group_prev"  value="<?php echo $row['account_group'];?>" >
								
							</div>
						</div>
						
                        <!--<div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
								<a href="account_group_mst.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
                        </div>-->

   						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; 
								//Mrunmayee started
								$sq2 = "SELECT COUNT(*) as total FROM `account_mst` where account_group = '$did'";
								$q2  = mysqli_query($con, $sq2);
								$r2  = mysqli_fetch_assoc($q2);
								$mycount = $r2['total'];
								if ($mycount <= 0) {?>
								<a href="<?php echo $baseurl."setting/account_group_mst.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a> 
								<?php }//Mrunmayee ended ?>
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
        var table_name = 'sma_account_group';
        var col_name = 'account_group';
        
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
