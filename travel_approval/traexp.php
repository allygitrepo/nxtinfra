<?php 

	$approval_ref_no= $_POST['approval_ref_no'];
	
	$exp_type		= $_POST['exp_type'];	
	$expence_name	= $_POST['expence_name'];
	$dated			= date('Y-m-d', strtotime($_POST['dated']));
	$amount			= $_POST['amount'];
	$advance_amount	= $_POST['advance_amount'];
	$invoice_nm		= $_POST['invoice_nm'];
	$remarks		= $_POST['remarks'];
echo "HELLO". ' ' .$remarks;
die;
	$folder_path = "uploads/te/";
	if (!file_exists($folder_path)){
		mkdir($folder_path, 0755, true);
	}
	
	$arrFUDoc = $_FILES["file_attach"];
	
	$filename = $arrFUDoc['name'];
	$tmpFileName = $arrFUDoc['tmp_name'];
echo $filename;
exit();
	$sql="Insert into sma_expenses ( exp_type ,approval_ref_no, reference, dated, invoice_no, amount, note, file_name, file_path ) values( '$exp_type', '$approval_ref_no', '$expence_name', '$dated', '$invoice_nm', '$amount', '$remarks', '" . $filename . "', '" . $folder_path . "' )";
	$result = mysqli_query($con, $sql);

	move_uploaded_file($tmpFileName, "uploads/te/" . $filename);

//echo $sql;
//	exit();

?>

<table id="prtable" class="table table-bordered table-striped">
									 <tr>
											<th> SrNo.</th>
											<th> Expense Type</th>
											<th> Invoice No.</th>
											<th>Date</th>
											<th style="text-align:right;"> Amount</th>
											<th> Remarks</th>
											<th style="text-align:right;"> Action</th>
									 </tr>
									
<tbody>
<?php
	$j = 0;
	$modulePath1 = "travel_approval/";
	$sql="SELECT * from sma_expenses where approval_ref_no = '$approval_ref_no' and exp_type = '$exp_type' order by dated ";
//echo $sql;
echo '';
//exit();	
	$result = mysqli_query($con, $sql);
	echo mysqli_error($con);
	while($row = mysqli_fetch_array($result)){
		$j = $j + 1;
		$reference = $row['reference'];
		$sql="SELECT * from account_mst where id = '$reference'";
		$q2 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2 = mysqli_fetch_array($q2);
		$reference = $r2['account_name'];
		
		$tot_amount += $row['amount'];
		
?>
	<tr style="cursor:pointer; " onmouseover="ChangeBackgroundColor(this)" onmouseout="RestoreBackgroundColor(this)" onclick="location.href='<?php echo $baseurl1;?>'">
		<td width="2%"><?php echo $j;?></td>
		<td width="25%"><?php echo $reference;?></td>
		<td width="10%"><?php echo date('d-m-Y', strtotime($row['dated']));?></td>
		<td width="10%"><?php echo $row['invoice_no'];?></td>
		<td width="10%" style="text-align:right;"><?php echo $row['amount'];?></td>
		<td width="45%"><?php echo $row['note'];?></td>
		<td width="08%" style="text-align:right;">
			<!--<a href="travel_expence.php?sub=edit&id=<?php echo $row['id'];?>" name="btnEdit" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>-->
			<a href="delete_expenses.php?sub=delete&id=<?php echo $row['id'];?>&approval_ref_no=<?php echo $approval_ref_no;?>" title="Delete" onclick="return confirm('Are you sure you want to delete?');"><i class="fa fa-remove"></i></a>
		</td>
										
    </tr>

	<?php }?>
	
	<?php if ($exp_typ=='T'){ 
		$tot_amount = $tot_amount - $advance_amount;
	?>	
		<tr> <td colspan="4" style="text-align:right;"> Total </td><td style="text-align:right;"> <?php echo number_format($tot_amount,2); ?> </td><td colspan="2"></td></tr>
	<?php } else if ($exp_type=='R'){ ?>
		<input type="hidden" name="total_amount" id="total_amount"  value="<?php echo number_format($tot_amount,2) ?>">
	<?php }	?>
</tbody> 
</table>
	