<!DOCTYPE html>
<?php

include("../header.php");
$modulePath = "supp_invoice/";
?>

  <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

<?php if($_GET['sub'] == 'edit'){
?>

<?php
	if(isset($_POST['Save'])){
		
			$id			= $_POST['id']; 
			
			$purchase_id		= $_POST['purchase_id'];
			$po_dtl				= $_POST['po_dtl'];
			$budget_id			= $_POST['budget_id_new'];
			$budget_id_old		= $_POST['budget_id_old'];

	echo 	$purchase_id . ' ' . $po_dtl. ' NEW> ' . $budget_id . ' Old>'. $budget_id_old;

	echo	$sql  = "SELECT * from sma_purchase_order where id = '$purchase_id' ";					
			$res  = mysqli_query($con, $sql);
			echo mysqli_error($con);
			$r1 = mysqli_fetch_array($res);
			$status_po 	= $r1['status'];
			
	echo	$sql  = "SELECT * from sma_po_items where purchase_id = '$purchase_id' and id = '$po_dtl' ";
			$res  = mysqli_query($con, $sql);
			$r1   = mysqli_fetch_array($res);
			$product_id = $r1['product_id'];
			
			
	echo	$sql  = "update sma_po_items set budget_id = '$budget_id' 
					        where purchase_id = '$purchase_id' and id = '$po_dtl' ";
			mysqli_query($con, $sql);

	echo	$sql  = "SELECT * from sma_supplier_invoice where our_po_ref_no = '$purchase_id' ";					
			$res2  = mysqli_query($con, $sql);
			echo mysqli_error($con);
			while ($r2 = mysqli_fetch_array($res2)){
				$si_hdr_id  	= $r2['id'];
				$status_si 		= $r2['status'];
								
		echo	$sql = "update sma_supplier_invoice_details set budget_id = '$budget_id' 
							where si_hdr_id = '$si_hdr_id' and material_id = '$product_id' ";
				mysqli_query($con, $sql);
							
				if( $status_si == 'Completed' ){
					
		echo		$sql = "SELECT * from sma_supplier_invoice_details where si_hdr_id = '$si_hdr_id' and material_id = '$product_id' ";
						$res  = mysqli_query($con, $sql);
						echo mysqli_error($con);
						$r1 = mysqli_fetch_array($res);
										
						$qty 	= $r1['qty'];
						$rate 	= $r1['rate'];
						$gst	= $r1['gst'];
						$amount = $qty * $rate + (($qty * $rate) * $gst / 100);		
										
		echo 	$sql = "select * from  sma_budget where id = '$budget_id'";		
		echo 	$sql = "select * from  sma_budget where id = '$budget_id_old'";						
					
		  		$sql="update sma_budget set used_budget = used_budget - '$amount' where id = '$budget_id' ";
					$query=mysqli_query($con, $sql);
					$error= mysqli_error($con);
					if(!empty($error)){echo $error; exit();}
		
		  		$sql="update sma_budget set used_budget = used_budget + '$amount' where id = '$budget_id_old' ";
					$query=mysqli_query($con, $sql);
					$error= mysqli_error($con);
					if(!empty($error)){echo $error; exit();}
		
				}
				
			}
			
			if( $status_po =='Completed' and $status_si !='Completed' ){
				
				$sql="update sma_budget set blocked_budget = blocked_budget - '$amount' where id = '$budget_id_old'";
					$query=mysqli_query($con, $sql);
					$error= mysqli_error($con);
					if(!empty($error)){echo $error; exit();}
		
		  		$sql="update sma_budget set blocked_budget = blocked_budget + '$amount' where id = '$budget_id'";
					$query=mysqli_query($con, $sql);
					$error= mysqli_error($con);
					if(!empty($error)){echo $error; exit();} 
		
			}

//exit('STOPED HERE....');
			
			echo "Budget successfully changed....";
			
			echo '<script>window.location.href="budget_change.php?sub=edit";</script>';
		}
		
	
?>
	
    <!-- Content Header (Page header) -->
    <div class="col-md-12">
    
   <section class="content-header">
        <h1>
            Change Budget for Material
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
            <li><a href="<?php echo $baseurl . $modulePath ?>"> Change Budget </a></li>
            
        </ol>
    </section>
	
        <!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box">
            <!-- form start -->
            <form class="form-horizontal" action="budget_change.php?sub=edit" method="post">
              <div class="box-body">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
                      <input type="hidden" name="id" value="<?php echo $row['id'];?>">
						
						<div class="form-group">
							
								<label for="project" class="control-label col-sm-2">Module</label>
								<div class="col-sm-4">
									<select class="form-control select2" name="module" id="module" >
                             		<option value=""> Select </option>
									<option value="S">Purchase Order</option>
									<option value="S">Operating Expense</option>
									
									</select>		
							</div>
							
							<label class="col-lg-2 control-label">Transaction ID</label>
							<div class="col-md-2">
								<input type="text" class="form-control" name="trans_id" id="trans_id" onchange="getmat(this.value); " value='' >
							</div>
						</div>
						
						<span id="getmaterial">
						
						</span>
						
						
						<span id="getmatbudget">
						
						</span>
				
						
                    </fieldset>
				</div>	
            </form>
          </div>
          
          <!-- /.box -->
        </div>
        <!--/.col (right) -->
      
<?php } 	?>

<?php 	
		include("../footer.php");	
?>
<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>

<script>
    
	function getbudget(id){
		
        var sub    = 'sub2';
//alert(sub);		
		var strURL = "app_func.php";
		$.post(strURL,{id:id,sub2:sub},function(result){
		      $('#getbudget').html(result);
		});

	}


	function getmat(id){
		
		var sub    = 'sub11';
		var module =  document.getElementById('module').value;
		var trans_id =  document.getElementById('trans_id').value;
	
//	alert(sub + ' ' + id+ ' '  + module + ' ' + trans_id);	
		
		//document.getElementById('balance_budget').value=balance_budget;
        var strURL = "bc_func.php";
		$.post(strURL,{id:id,module:module,trans_id:trans_id,sub11:sub},function(result){
		      $('#getmaterial').html(result);
		});
	
	}
	

	function getmatbudget(id){
		
		var sub    = 'sub1';
		var module =  document.getElementById('module').value;
		var trans_id =  document.getElementById('trans_id').value;
	
	alert(sub + ' ' + id+ ' '  + module);	
		
		//document.getElementById('balance_budget').value=balance_budget;
        var strURL = "bc_func.php";
		$.post(strURL,{id:id,trans_id:trans_id,module:module,sub1:sub},function(result){
		      $('#getmatbudget').html(result);
		});
		
	}	
	
	
	function gettotbudget123(id){
		
        var sub    = 'sub2';
		
		var budget_name_id 	= document.getElementById('budget_name').value;
		var comp_id 		= document.getElementById('company_id').value;
		//document.getElementById("Text1").value;
alert(sub + ' <<>> ' + id + ' <<>> ' + budget_name_id + ' <<>> ' + comp_id);
		var strURL = "bc_func.php";
		$.post(strURL,{id:id,budget_name_id:budget_name_id,comp_id:comp_id,sub2:sub},function(result){
		      $('#gettotbudget').html(result);
		});

	}
	
</script>

</body>
</html>
