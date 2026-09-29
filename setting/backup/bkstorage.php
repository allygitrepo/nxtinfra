<?php
	include("../header.php"); 
	include("../baseurl.php"); 

//	require "../dbcon.php";
	$user  = $_SESSION['user'];
	
	
	$dbhost = 'localhost';
	$dbuser = 'root'; 
	$dbpass = ''; 
	$dbname	= "hc_workflow";
	
    $link = mysql_connect($dbhost, $dbuser, $dbpass);

    $selectdb 	=	mysql_select_db($dbname,$link);
	echo mysql_error();

?>
     <!-- Main Container Start -->
<div class="content-wrapper">
        
        	<!-- Inner Container Start -->
            <div class="container">
 
            <?php if($_GET['sub'] == 'bkp'){ ?>
                
			<?php
			
			if(isset($_POST['submit'])){
								
				//$pass = md5($_POST['Password']);
				$pass = $_POST['Password'];
				$sql = "select * from sma_user where userid='$user' and password='$pass'";
//echo $sql;	
				$result = mysql_query($sql);;

				echo mysql_error();
				
				$rowcount = mysql_num_rows($result);
				while($r = mysql_fetch_object($result)){
					$username = $r->username;
					$passworda = $r->passworda;
				}
				//echo $rowcount;
				//exit();
					if($rowcount>0)
					{   
						unset($_POST['submit']);				
						echo '<script>window.location.href="bkstorage.php?sub=add";</script>';
					}
					else
					{
						$baseurl1 = $baseurl. 'dashboard.php?sub=list';
						echo "<script>alert('Password Invalid'); window.location.href='$baseurl1';</script>";
					}
						}?>
				
<div class="row">
    <div class="col-sm-12 col-md-12">            
                <form id="form1" action="bkstorage.php?sub=bkp" method="post" class="form-horizontal form_validation_reg">
                    <fieldset>
                        <h3 class="heading">Backup Data	</h3>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Enter Admin Password<span class="f_req">&nbsp;*</span></label>
							<div class="col-md-2">
								<input class="form-control" type="password" name="Password" placeholder="Enter Password" value="">
							</div>
						</div>
						
						<div class="form-group">
						<center>
						<div>
							<input class="btn btn-info" type="submit" value=" Submit " name="submit">&nbsp;&nbsp;&nbsp;
							<a href="dashboard.php?sub=list" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
						</div>
						</center>
						</div>
								
					</fieldset>
				</form>
				
							
    </div>
</div>
    <div class="clear"></div><!-- End .clear -->
		                       
        <?php } if($_GET['sub'] == 'add'){ ?>                
			<?php
				if(isset($_POST['submit'])){
				
					//$backupdate = date("Y-m-dhis", strtotime($_POST['backupdate']));
					
					$backupdate = date("Y-m-d", strtotime($_POST['backupdate']));
					
					$backupfile_name = $_POST['backupfile_name'];
					$user     		 = $_POST['user'];

					$_SESSION['backupfile_name'] = $backupfile_name;
					$_SESSION['$backupdate']     = $backupdate;

					echo '<script>window.location.href="backupdata.php";</script>';
				}
			?>
<div class="row">
    <div class="col-sm-12 col-md-12">            
                <form id="form1" action="bkstorage.php?sub=add" method="post" class="form-horizontal form_validation_reg">
                    <fieldset>
                        <h3 class="heading">Data Backup Maintainance</h3>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Backupfile Date </label>
							<div class="col-md-2">
								<input class="form-control" type="text" name="backupdate" value="<?php $opdate=date("d-m-Y"); echo $opdate;?>" readonly="readonly" >
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">Backupfile Name <span class="f_req">*</span></label>
							<div class="col-md-2">
								<input class="form-control" type="text" name="backupfile_name" value="backup">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-lg-2 control-label">User </label>
							<div class="col-md-2">
								<input type="text" name="user" class="form-control" id="user" readonly="readonly" value="<?php echo $user;?>"/>
							</div>
						</div>
						
						<div class="form-group">
						<center>
						<div>
						<?php 
							$baseurl1 = $baseurl. 'dashboard.php?sub=list';
						?>
							<input class="btn btn-info" type="submit" value=" Submit " name="submit">&nbsp;&nbsp;&nbsp;
							<a href="<?php echo $baseurl1; ?>" name="btnCancel" class="btn btn-info btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Cancel</a>
						</div>
						</center>
						</div>
								
					</fieldset>
				</form>
    </div>
</div>
	
		<div class="clear"></div><!-- End .clear -->
                        
	<?php } ?>
				
</div>

<?php include('../footer.php');?>
