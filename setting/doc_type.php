<?php

include("../header.php");
$modulePath = "setting/doc_type.php?sub=list";
?>

  <!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

    <section class="content-header">
      <h1>
        Transaction Type
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Transaction Type</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of Transaction Type</h3>
                <span class="pull-right"><a href="doc_type.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create </a></span>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>Transaction Type</th>
			<th>Short code</th>
			<th style="text-align:right;">Action</th>
			
		</tr>
	</thead>
<tbody>
<?php

	$modulePath1 = "setting/";
	$sql="SELECT * from sma_doc_type";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		$status = $row['status'];
		if($status =='Y'){
			$status = 'Active';
		}
		else {
			$status = 'Inactive';
		}	
		
		$baseurl1 = $baseurl.$modulePath1.'doc_type.php?sub=edit&id='.$row["id"];
				
	?>
	<a href="<?php echo $baseurl . $modulePath1 . "doc_type.php?sub=edit&id=". $row['id']?>" title="Edit">
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">

		<td width="20%"><?php echo $row['doc_description'];?></td>
		<td width="20%"><?php echo $row['doc_type'];?></td>
		<td width="10%"><?php echo $status;?></td>
		

		<td width="10%" style="text-align:right;">
		<a href="doc_type.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
<!--<a href="doc_type.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
		
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
		$sql="delete from sma_doc_type where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="doc_type.php?sub=list";</script>';
	} 
?>

<?php if($_GET['sub'] == 'add'){
?>

<?php
 
	if(isset($_POST['Save'])){
	    
			$doc_description		= $_POST['doc_description'];
			$doc_type       		= $_POST['doc_type'];
			$status				    = $_POST['status'];
				
			 $sql="SELECT * FROM sma_doc_type where 1 and doc_description = '$doc_description' ";
        		mysqli_query($con, $sql);
        		$rowaffect = mysqli_affected_rows($con);
        		if($rowaffect>0){
        		    echo "<script>alert('Error: Transaction Type Already available ...');</script>";
        		    echo '<script>window.location.href="doc_type.php?sub=add";</script>';
        		    exit();
        		}
        		
        		
  			$sql="insert into sma_doc_type (doc_description, doc_type, status) Values('$doc_description', '$doc_type', 'Y' )";
					
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);
			if(!empty($error)){echo $error; exit();}

			//echo "doc_description successful added";
			echo '<script>window.location.href="doc_type.php?sub=list";</script>';
		}
	

?>

    <section class="content-header">
        <h1>
            Transaction Type
            <small>Add</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Transaction Type</a></li>
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
            <form class="form-horizontal" action="doc_type.php?sub=add" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      
						<div class="form-group">
							<label class="col-lg-2 control-label">Transaction Type</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="doc_description" name="doc_description" placeholder="" autocomplete="off" value=""  onchange="getDuplicate(this.value);" >
								<span id="getDuplicate" style="color:red;" ></span>
							</div>
						</div>
						
                       <div class="form-group">
							<label class="col-lg-2 control-label">Transaction Code</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="doc_type" name="doc_type" placeholder="" autocomplete="off" value=""  onchange="getDuplicate(this.value);" >
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


<?php if($_GET['sub'] == 'edit'){

?>
	
<?php 
 echo $_POST['Save'];
 
	if(isset($_POST['Save'])){
			$id				= $_POST['id']; 
			$doc_description			= trim($_POST['doc_description']);
			$doc_description_pre	    = trim($_POST['doc_description_pre']);
			$doc_type       			= trim($_POST['doc_type']);
			$status				= $_POST['status'];
				
			if($doc_description_pre!=$doc_description){
			    $sql="SELECT * FROM sma_doc_type where 1 and doc_description = '$doc_description' ";
        		mysqli_query($con, $sql);
        		$rowaffect = mysqli_affected_rows($con);
        		if($rowaffect>0){
        		    echo "<script>alert('Error: Transaction Type Already available ...');</script>";
        		    echo "<script>window.location.href='doc_type.php?sub=edit&id=$id';</script>";
        		    exit();
        		}
			}
			
  			$sql="update sma_doc_type set doc_description ='$doc_description', doc_type = '$doc_type'	, status = '$status'	
				where id='$id' ";
			$query=mysqli_query($con, $sql);
			$error= mysqli_error($con);

			if(!empty($error)){echo $error; exit();}
			
			echo '<script>window.location.href="doc_type.php?sub=list";</script>';
	}
		
		$id = $_GET['id'];
		$sql="Select * from sma_doc_type where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	

?>

    <section class="content-header">
        <h1>
            Transaction Type
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>">Transaction Type</a></li>
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
            <form class="form-horizontal" action="doc_type.php?sub=edit" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Transaction Type</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="doc_description" name="doc_description" placeholder="" value="<?php echo $row['doc_description'];?>" >
								
								<input type="hidden" id="doc_description_pre" name="doc_description_pre" value="<?php echo $row['doc_description'];?>" >
								
							</div>
						</div>
						
                        <div class="form-group">
							<label class="col-lg-2 control-label">Short Code</label>
							<div class="col-md-3">
								<input type="text" class="form-control" id="doc_type" name="doc_type" placeholder="" value="<?php echo $row['doc_type'];?>" >
								
							</div>
						</div>
						
						<div class="form-group">	
						
							<label for="user_category" class="control-label col-sm-2">Status</label>
							<div class="col-sm-8" style="padding-top: 6px;">
								
								<input type="radio" class="minimal"   name="status" id="status" checked value="Y" > Active &nbsp;
                            	<input type="radio"  class="minimal"  name="status" id="status" value="N" > Inactive &nbsp;
						</div>

   						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; ?>
								
								<a href="<?php echo $baseurl."setting/doc_type.php?id=$did&sub=delete";?>" class="btn btn-danger" >Delete</a>
								
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
        var table_name = 'sma_doc_type';
        var col_name = 'doc_description';
        
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
