<?php

include("../header.php");
$modulePath = "vendor/category.php?sub=list";
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

  <?php if ($_GET['sub'] == 'list') {
  ?>
    <link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css" ?>">

    <section class="content-header">
      <h1>
        Category
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl . 'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Category</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of Category</h3>
              <span class="pull-right"><a href="category.php?sub=add" name="btnAdd" class="btn btn-info"><i class="splashy-document_letter_add"></i>&nbsp;&nbsp;Create Category </a></span>
              <!-- Ruchi started-->
              <span class="pull-right">
                <a href="vendor_export.php?sub=cat" name="btnAdd" target="_blank" class="btn btn-info"><i class="splashy-document_letter_add"></i>Export</a>&nbsp;&nbsp;&nbsp;&nbsp;
              </span>
              <!-- Ruchi ended-->
            </div>
            <!-- /.box-header -->
            <div class="box-body">

              <table id="prtable" class="table table-bordered table-striped">


                <thead>
                  <tr>
                    <th>Category</th>
                    <th style="text-align:right;">Action</th>

                  </tr>
                </thead>
                <tbody>
                  <?php
                  $sql = "SELECT * from sma_categories";
                  $result = mysqli_query($con, $sql);
                  echo mysqli_error($con);

                  while ($row = mysqli_fetch_array($result)) {
                  ?>

                    <tr>
                      <td width="20%"><?php echo $row['name']; ?></td>
                      <td width="10%" style="text-align:right;">
                        <a href="category.php?sub=edit&id=<?php echo $row['id']; ?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>

                        <!--		<a href="category.php?sub=delete&id=<?php echo $row['id']; ?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->

                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
  <?php } ?>


  <?php
  if ($_GET['sub'] == 'delete') {
    $id = $_GET['id'];
			$sql	="Select * from sma_categories where id ='$id'";
			$query 	= mysqli_query($con, $sql);
			$row 	= mysqli_fetch_array($query);
			$name 			= $row['name'];
			
			$company_id 	= '';
			$pgname 		= "category.php";
			include "../viewonly.php";
			$description 	= $name;
		    $affect 		= 'Deleted';
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
    $sql = "delete from sma_categories where id='$id' ";
    $query1 = mysqli_query($con, $sql);
    echo mysqli_error($con);
    echo '<script>window.location.href="category.php?sub=list";</script>';
  }
  ?>

  <?php if ($_GET['sub'] == 'add') {

    if (isset($_POST['Save'])) {
      $name  = $_POST['name'];

        $sql="SELECT * FROM sma_categories where 1 and name = '$name' ";
        	mysqli_query($con, $sql);
        	$rowaffect = mysqli_affected_rows($con);
        	if($rowaffect>0){
        		echo "<script>alert('Error: Category Already available ...');</script>";
        		echo '<script>window.location.href="category.php?sub=add";</script>';
        		exit();
        	}
        	
      $sql = "insert into sma_categories (name) 
					Values('$name')";

      $query = mysqli_query($con, $sql);
      $error = mysqli_error($con);
      if (!empty($error)) {
        echo $error;
        exit();
      }

			$company_id 	= '';
			$pgname 		= "category.php";
			include "../viewonly.php";
			$description 	= $name;
		    $affect 		= 'Added';
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
      //echo "Category successful added";
		echo '<script>window.location.href="category.php?sub=list";</script>';
		
    }


  ?>

    <section class="content-header">
      <h1>
        Category
        <small>Add</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li><a href="<?php echo $baseurl . $modulePath ?>">Category</a></li>
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
              <form class="form-horizontal" action="category.php?sub=add" method="post">

                <!-- /.box-body -->
                <!-- /.box-footer -->
                <fieldset>

                  <div class="form-group">
                    <label class="col-lg-2 control-label">Category</label>
                    <div class="col-md-3">
                      <input type="text" class="form-control" id="category" name="name" placeholder="" required autocomplete="off" value="" onchange="getDuplicate(this.value);" >
								<span id="getDuplicate" style="color:red;" ></span>
                    </div>
                  </div>

                  <!--<div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;
                                &nbsp;
								<a href="category.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
                        </div>-->

                  <div class="box-footer">
                    <div class="col-sm-6">
                      <?php $did = $_GET['id']; ?>

                    </div>
                    <?php $baseurl1 = $baseurl . $modulePath; ?>
                    <div class="col-sm-6 text-right">
                      <a href="<?php echo $baseurl1; ?>" class="btn btn-default">Cancel</a>
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
  <?php }   ?>


  <?php if ($_GET['sub'] == 'edit') {

    if (isset($_POST['Save'])) {
		$id      = $_POST['id'];
		$name    = trim($_POST['name']);
        $name_prev		= trim($_POST['name_prev']);
            if($name_prev !=$name){
			    $sql="SELECT * FROM sma_categories where 1 and name ='$name' ";
        		mysqli_query($con, $sql);
        		$rowaffect = mysqli_affected_rows($con);
        		if($rowaffect>0){
        		    echo "<script>alert('Error: Categories Already available ...');</script>";
        		    echo "<script>window.location.href='units.php?sub=edit&id=$id';</script>";
        		    exit();
        		}
			}
			
			
		$sql = "update sma_categories set 	name ='$name'
					where id='$id'";

		  $query = mysqli_query($con, $sql);
		  $error = mysqli_error($con);
		  if (!empty($error)) {
			echo $error;
			exit();
		  }

			$company_id 	= '';
			$pgname 		= "category.php";
			include "../viewonly.php";
			$description 	= $name;
		    $affect 		= 'Modified';
			$sql = "INSERT INTO `log_tbl`(`user_name`, `audit_date_time`, `main_menu`, `sub_menu`, `company_name`, `description`, `action`) 
			VALUES ('$user_name',NOW(),'$main_menu','$sub_menu','$company_id','$description','$affect')";
		    mysqli_query($con, $sql);
			
		echo '<script>window.location.href="category.php?sub=list";</script>';
    }

    $id = $_GET['id'];
    $sql = "Select * from sma_categories where id ='$id'";
    $query = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($query);

  ?>

    <section class="content-header">
      <h1>
        Category
        <small>Edit</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li><a href="<?php echo $baseurl . $modulePath ?>">Category</a></li>
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
              <form class="form-horizontal" action="category.php?sub=edit" method="post">
                <div class="box-body">

                  <!-- /.box-body -->
                  <!-- /.box-footer -->
                  <fieldset>
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                    <div class="form-group">
                      <label class="col-lg-2 control-label">Category</label>
                      <div class="col-md-3">
                        <input type="text" class="form-control" id="category" name="name" placeholder="" value="<?php echo $row['name']; ?>" readonly="readonly">
                        
                        <input type="hidden"  name="name_prev"  value="<?php echo $row['name'];?>" >
                        
                      </div>
                    </div>

                    <!--<div class="form-group">
						<center>
                            <div>
                                <input class="btn btn-info" type="submit" value="Save" name="Save">&nbsp;&nbsp;
                                &nbsp;
								<a href="category.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
                            </div>
						</center>
                        </div>-->

                    <div class="box-footer">
                      <div class="col-sm-6">
                        <?php $did = $_GET['id'];
                        //Mrunmayee started
                        $sq2 = "SELECT COUNT(*) as total FROM `sma_party_mst` where 	party_category = '$did'";
                        $q2  = mysqli_query($con, $sq2);
                        $r2  = mysqli_fetch_assoc($q2);
                        $mycount = $r2['total'];
                        if ($mycount <= 0) { ?>
                          <a href="<?php echo $baseurl . "vendor/category.php?id=$did&sub=delete"; ?>" class="btn btn-danger">Delete</a> 
                          <?php } //Mrunmayee ended?>
                      </div>
                      <?php $baseurl1 = $baseurl . $modulePath; ?>
                      <div class="col-sm-6 text-right">
                        <a href="<?php echo $baseurl1; ?>" class="btn btn-default">Cancel</a>
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
    </section>
  <?php }   ?>


  <?php
  include("../footer.php");
  ?>
  <!-- DataTables -->
  <script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js" ?>"></script>
  <script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js" ?>"></script>

  <script>
    $(function() {
      $("#prtable").DataTable();
    });
    
   function getDuplicate(id){
		var sub    = 'sub10';
//alert(sub);
        var table_name = 'sma_categories';
        var col_name = 'name';
        
        $('.HideSave').show();
        $('#getDuplicate').html('');
		var strURL = "v_func.php";
		$.post(strURL,{id:id,col_name:col_name,table_name:table_name,sub10:sub},function(result){
		      $('#getDuplicate').html(result);
		      
		});
		
	}

  </script>

  </body>

  </html>