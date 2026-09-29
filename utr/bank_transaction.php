<?php
	include("../header.php");
	include("../baseurl.php");
	$modulePath = "utr/bank_transaction.php?sub=list";

?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

<?php if($_GET['sub'] == 'list'){
?>
<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">
    <section class="content-header">
      <h1>
        Bank Transaction
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Bank Transaction</li>
      </ol>
    </section>

    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List of Bank Transaction</h3>
			  <?php
				if ($_POST['comp_id'] || $_POST['matched']  ){
					$_SESSION['comp_id'] 		= $_POST['comp_id'];
					$_SESSION['matched'] 	= $_POST['matched'];
					
				}
				
				if ( $_SESSION['comp_id'] ||  $_SESSION['matched']  ){
					$comp_id 			= $_SESSION['comp_id'];
					$matched 		= $_SESSION['matched'];
					
					$_SESSION['reset']='';
				}
				
				if (!empty($_GET['reset']) || !empty($_SESSION['reset'])){
					$_SESSION['comp_id'] = '';
					$_SESSION['matched'] = '';
					
					$comp_id 		= $_SESSION['comp_id'];
					$matched 	= $_SESSION['matched'];
					
				}
				
			?>
			  <form class="form-horizontal" action="bank_transaction.php?sub=list" method="post">
					<div class="form-group">
						<div class="col-md-4">
									<label class="control-label">Company</label>
									<select class="form-control select2" name="comp_id" id="comp_id" >
										<option value=""> Select </option>
											<?php $sql = "select * from company order by comp_name ";
											$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['comp_id'];?>" <?php echo ($comp_id == $r2['comp_id'])?'selected="selected"':'';?>  ><?php echo $r2['comp_name'];?></option>
											<?php } ?>
									</select>	
								</div>

						<div class="col-md-2">
								<label class="control-label">Status</label>
								<select class="form-control select2" name="matched" id="matched" >
									<option value=""> Select </option>
									<option value="M" <?php echo ($matched == 'M')?'selected="selected"':'';?> > Matched </option>
									<option value="U" <?php echo ($matched == 'U')?'selected="selected"':'';?> > Unmatched </option>
								</select>		
						</div>
						
						<div class="col-xs-2">
                            
							<input class="btn btn-success" type="submit" value="Search" name="Save">&nbsp;&nbsp;&nbsp;
							<a href="bank_transaction.php?sub=list&reset=1" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;Reset</a>
						</div>
					</div>		
				</form>
                
            </div>
            <!-- /.box-header -->
            <div class="box-body">
    <table id="prtable" class="table table-bordered table-striped">

	<thead>
		<tr>
			<th>File Name</th>
			<th>Company Name</th>
			<th>Trans.Id</th>
			<th>Value Date</th>
			<th>Trans.Posted Date</th>
			<th>Remarks</th>
			<th>Withdrawal Amt.</th>
			<th>Matched ID</th>
			<th>Matched Date</th>
			
			<th style="text-align:right;">Action</th>
			
		</tr>
	</thead>
<tbody>
<?php
	$sql="SELECT * from bank_transactions where 1 ";
	
	if(!empty($comp_id)){
		$sql .= " AND company_id = '$comp_id' ";
	}

	if($matched=='M'){
		$sql .= " AND match_id > 0 ";
	}
	else if($matched=='U'){
		$sql .= " AND match_id = 0 ";
	}
	
	$sql .=" order by file_name, id";
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	
	while($row = mysqli_fetch_array($result)){
		
		$company_id = $row['company_id'];
		$sql = "select * from company where 1 and comp_id = '$company_id' order by comp_name ";
		$q2 	= mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$comp_name = $r2['comp_name'];
		$comp_code = $r2['comp_code'];
		
		$match_id 		= $row['match_id'];
		
		$baseurl1 = '';
		if($match_id >0){
			$baseurl1 = $baseurl."payment/edit.php?id=$match_id" ;
		}
		$matched_date 	= date('d-m-Y', strtotime($row['matched_date']));
		if($matched_date=='01-01-1970' || $matched_date=='30-11--0001'){
			$matched_date='';
		}
		
		$myArray = explode('/', $row['transaction_remarks']);

		$transaction_remarks = '';
		 $cnt = count($myArray);
		if($cnt==6 || $cnt==11){
			$transaction_remarks = $myArray[0].'/'.$myArray[1].'/'.$myArray[2].'/ '.$myArray[3].'/'.$myArray[4].'/'.$myArray[5];
		}
		else if($cnt==7){
			$transaction_remarks = $myArray[0].'/'.$myArray[1].'/'.$myArray[2].'/ '.$myArray[3].'/'.$myArray[4].'/'.$myArray[5].' /'.$myArray[6];
		}
		else if($cnt==1){
			$transaction_remarks = $row['transaction_remarks'];
		}
?>

    <tr >
		<td width="10%"><?php echo $row['file_name'];?></td>
		<td width="10%"><?php echo $comp_code;?></td>
		<td width="10%"><?php echo $row['trans_id'];?></td>
		<td width="10%"><?php echo $row['value_date'];?></td>
		<td width="10%"><?php echo $row['transaction_posted_date'];?></td>
		<td width="15%"><?php echo $transaction_remarks;?></td>
		<td width="10%"><?php echo $row['withdrawal_amount'];?></td>
		<td width="10%"><a href="<?=$baseurl1;?>" target='_blank' ><?= $match_id;?></a></td>
		<td width="10%"><?php echo $matched_date;?></td>
		
		<td width="05%" style="text-align:right;">
		<a href="bank_transaction.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>
		
<!--		<a href="bank_transaction.php?sub=delete&id=<?php echo $row['id'];?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>-->
		
		</td>
    </tr>
	
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
		$sql="delete from bank_transactions where id='$id' ";
        $query1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
        echo '<script>window.location.href="bank_transaction.php?sub=list";</script>';
	} 
?>


<?php if($_GET['sub'] == 'edit'){
?>
  
<?php

	if(isset($_POST['Save'])){
			 $id			= $_POST['id']; 
			//$file_name	= $_POST['file_name'];
			
			$p2p_match_id    = $_POST['p2p_match_id'];
			
//print_r($p2p_match_id);	
			
			$cnt = count($p2p_match_id);

			if($cnt>0){
				for($i = 0; $i < sizeof($p2p_match_id); $i++){
					$py_id .= $p2p_match_id[$i].',';
				}	
				$py_id .= '0';
				$sql="update bank_transactions set match_id ='$py_id', matched_date = now() where id='$id'";
echo $sql ."<BR>";				
				$query=mysqli_query($con, $sql);
				$error= mysqli_error($con);
				if(!empty($error)){echo $error; exit();}
			//exit('#####0');
			
				for($i = 0; $i < sizeof($p2p_match_id); $i++){
					
					$py_id = $p2p_match_id[$i];
					
					$sql="update `payment_header` set match_id ='$id', matched_date = now() where id='$py_id'";
echo $sql ."<BR>";					
					$query=mysqli_query($con, $sql);
					$error= mysqli_error($con);
					if(!empty($error)){echo $error; exit();}
					
				}
				
			}

			echo "<script>window.location.href='bank_transaction.php?sub=edit&id=$id';</script>";
				exit('#####1');
				
		}
		
		$id = $_GET['id'];
		$sql="Select * from bank_transactions where id ='$id'";
		$query = mysqli_query($con, $sql);
        $row = mysqli_fetch_array($query);	
		$company_id = $row['company_id'];
		$match_id 		= $row['match_id'];
		
		
		$sql = "select * from company where 1 and comp_id = '$company_id' order by comp_name ";
		$q2 	= mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($q2);
		$comp_name = $r2['comp_name'];
		$comp_code = $r2['comp_code'];
		
		$baseurl1 = '';
		if($match_id >0){
			$baseurl1 = $baseurl."payment/edit.php?id=$match_id" ;
		}
		$matched_date 	= date('d-m-Y', strtotime($row['matched_date']));
		if($matched_date=='01-01-1970' || $matched_date=='30-11--0001'){
			$matched_date='';
		}
		
		$myArray = explode('/', $row['transaction_remarks']);

		$transaction_remarks = '';
		 $cnt = count($myArray);
		if($cnt==6 || $cnt==11){
			$transaction_remarks = $myArray[0].'/'.$myArray[1].'/'.$myArray[2].'/ '.$myArray[3].'/'.$myArray[4].'/'.$myArray[5];
		}
		else if($cnt==7){
			$transaction_remarks = $myArray[0].'/'.$myArray[1].'/'.$myArray[2].'/ '.$myArray[3].'/'.$myArray[4].'/'.$myArray[5].' /'.$myArray[6];
		}
		else if($cnt==1){
			$transaction_remarks = $row['transaction_remarks'];
		}
		
?>
	
    <!-- Content Header (Page header) -->
        <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
				<section class="content-header">	
					<h1>Bank Transaction</h1>
				</section>	
		<!-- right column -->
          <!-- Horizontal Form -->
            <!-- /.box-header -->
		<div class="box-body">
        <!-- form start -->
            <form class="form-horizontal" action="bank_transaction.php?sub=edit" method="post">
                
              <!-- /.box-body -->
              <!-- /.box-footer -->
			  <fieldset>
					 
					 <input type="hidden" name="id" value="<?php echo $row['id'];?>">
					 
			  <table id="prtable123" class="table table-bordered table-striped">

				<thead>
					<tr>
						<th>File Name</th>
						<th>Company Name</th>
						<th>Trans.Id</th>
						<th>Value Date</th>
						<th>Trans.Posted Date</th>
						<th>Remarks</th>
						<th style="text-align:right;">Withdrawal Amt.</th>
						<th  style="text-align:right;">Matched ID</th>
						<th>Matched Date</th>
						
					</tr>
				</thead>
				<tbody>
					<tr>
						<td width="10%"><?php echo $row['file_name'];?></td>
						<td width="10%"><?php echo $comp_code;?></td>
						<td width="10%"><?php echo $row['trans_id'];?></td>
						<td width="10%"><?php echo $row['value_date'];?></td>
						<td width="10%"><?php echo $row['transaction_posted_date'];?></td>
						<td width="15%"><?php echo $transaction_remarks;?></td>
						<td width="10%" style="text-align:right;"><?php echo $row['withdrawal_amount'];?></td>
						<td width="10%"  style="text-align:right;" ><a href="<?=$baseurl1;?>" target='_blank' ><?= $match_id;?></a></td>
						<td width="10%"><?php echo $matched_date;?></td>
					</tr>
				</tbody>
				</table>

<?php                     
				$transaction_posted_date 	= date('Y-m-d', strtotime($row['transaction_posted_date']));
				$party_name_match 			= substr(str_replace(' ','',trim(strtolower($row['party_name']))),0,9);;
?>
				<section class="content-header">
				<h1>P2P Payment Transaction</h1>
				</section>
				<table id="prtable123" class="table table-bordered table-striped">

				<thead>
					<tr>
						<th  style="text-align:right;" >Trans.No.</th>
						<th>Party Name</th>
						<th>Paid Date</th>
						<th>Trans Flag.</th>
						<th>Bank Name</th>
						<th>UTR No.</th>
						<th style="text-align:right;" >Amount Paid</th>
						<th  style="text-align:right;">Matched ID</th>
						<th>Matched Date</th>
						<th>Action</th>
					</tr>
				</thead>
				
<?php			
			$py_id_array = [];
			if(!empty($party_name_match)){
				
				$sql = " SELECT * FROM `sma_party_mst` where 1 and SUBSTRING(LOWER(REPLACE(party_name,' ','')),1,9)  like '%$party_name_match%' ";
//echo $sql. "<BR>";	
						$qryy 	= mysqli_query($con, $sql);
						$rwaffect = mysqli_affected_rows($con);
						while($row2 = mysqli_fetch_array($qryy)){
							$party_id 			= $row2['id'];
							$party_name 		= $row2['party_name'];
							
							$sql = " SELECT distinct(id) as id, total_amount_paid, paid_to, st_flag, cash_bank_name, paid_date, utr_no, 
										cheque_no, match_id, matched_date 
										FROM `payment_header` 
											WHERE 1 and ( company_id = '$company_id' 
												and paid_date = '$transaction_posted_date'
												and paid_to = '$party_id' )
												OR ( id in ($match_id) )";
									
//echo $sql. "<BR>";			
							$qry2 	= mysqli_query($con, $sql);
							$rwaffect = mysqli_affected_rows($con);
							while($rw22 = mysqli_fetch_array($qry2)){
								$total_amount_paid 			= $rw22['total_amount_paid'];
								$py_id	 				= $rw22['id'];	
								$paid_to 				= $rw22['paid_to'];
								$st_flag 				= $rw22['st_flag'];
								$cash_bank_name			= $rw22['cash_bank_name'];
								$paid_date				= date('d-m-Y', strtotime($rw22['paid_date']));
								$utr_no					= $rw22['utr_no'];
								$cheque_no				= $rw22['cheque_no'];
								$matched_id				= $rw22['match_id'];
								$matched_date			= date('d-m-Y', strtotime($rw22['matched_date']));
								
								if($matched_date=='01-01-1970' || $matched_date=='30-11--0001'){
									$matched_date='';
								}
								
								if (in_array($py_id, $py_id_array, TRUE)){
								    //echo "Match found<br>";
									continue;
								}
								  
								$py_id_array[] = $py_id;
						
						//print_r($py_id_array);
						
								$match_id = '0';
								$sql = " SELECT * FROM `account_mst` where 1 and id = '$cash_bank_name' ";
								$qry 	= mysqli_query($con, $sql);
								$rw2 = mysqli_fetch_array($qry);
								$cash_bank_name 	= $rw2['account_name'];
									
								if($st_flag=='S' || $st_flag=='D' || $st_flag=='R' || $st_flag=='C'){
									$sql = " SELECT * FROM `sma_party_mst` where 1 and id = '$paid_to' ";
									$qry 	= mysqli_query($con, $sql);
									$rw2 = mysqli_fetch_array($qry);
									$party_name 			= $rw2['party_name'];
								}
								else if($st_flag=='T'){
									$sql = " SELECT * FROM `sma_user` where 1 and id = '$paid_to' ";
									$qry 	= mysqli_query($con, $sql);
									$rw2 = mysqli_fetch_array($qry);
									$party_name 			= $rw2['username'];
								}
								
								if($st_flag=='S'){
									$st_flag = 'Supplier Invoice';
								}
								if($st_flag=='D'){
									$st_flag = 'Advance';
								}
								if($st_flag=='S'){
									$st_flag = 'Company Expense';
								}
								if($st_flag=='R'){
									$st_flag = 'Retention';
								}
								if($st_flag=='T'){
									$st_flag = 'Travel Expense';
								}
								
								$baseurl1 = '';
								if($py_id >0){
									$baseurl1 = $baseurl."payment/edit.php?id=$py_id" ;
								}
								
								$total_amount_paid_net = round($total_amount_paid_net + $total_amount_paid,2);
?>								
								
								<tbody>
									<tr>
										<td width="10%" style="text-align:right;" ><a href="<?=$baseurl1;?>" target='_blank' ><?php echo $py_id;?></a></td>
										<td width="10%"><?php echo $party_name;?></td>
										<td width="10%"><?php echo $paid_date;?></td>
										<td width="10%"><?php echo $st_flag;?></td>
										<td width="10%"><?php echo $cash_bank_name;?></td>
										
										<td width="15%"><?php echo $utr_no;?></td>
										<td width="10%" style="text-align:right;" ><?php echo $total_amount_paid;?></td>
										<td width="10%"  style="text-align:right;" ><a href="<?=$baseurl1;?>" target='_blank' ><?= $matched_id;?></a></td>
										<td width="10%"><?php echo $matched_date;?></td>
										
										<td width="10%"><input type="checkbox" name="p2p_match_id[]" value="<?= $py_id;?>"</td>
										
									</tr>
								</tbody>
								
<?php				
							}
									
						}
?>							
								<tr>
										<td width="10%" style="text-align:right;" ></td>
										<td width="10%"></td>
										<td width="10%"></td>
										<td width="10%"></td>
										<td width="10%"></td>
										
										<td width="15%" style="text-align:right;" ><b>Total</b></td>
										<td width="10%" style="text-align:right;" ><?= bcadd($total_amount_paid_net, 0,2);?></td>
										<td width="10%"  style="text-align:right;" ></td>
										<td width="10%"></td>
										
										<td width="10%"></td>
										
								</tr>
						
<?php						
				}		
?>						
				</table>
				
   						<div class="box-footer">
							<div class="col-sm-6">
								<?php $did = $_GET['id']; 
                
               ?>
							</div>
							<?php $baseurl1 = $baseurl.$modulePath;?>
							<div class="col-sm-6 text-right">
								<a href="<?php echo $baseurl1;?>" class="btn btn-default" >Cancel</a>
								<span>&nbsp;&nbsp;</span>
								<input class="btn btn-primary" type="submit" value="Save" name="Save">&nbsp;&nbsp;&nbsp;
							</div>
						</div>

                    </fieldset>
            </form>
			</div>	
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
    $(function () {
        $("#prtable").DataTable();
    });
</script>

<!-- page script -->
<script>
  $(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });
</script>

</body>
</html>
