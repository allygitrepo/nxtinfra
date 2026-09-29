<?php
session_start(); 	

//include("header.php");
include("dbcon.php");			
	$role		= $_SESSION['role'];
	$userid   	= $_SESSION['usrid'];
	$comid      = $_SESSION['comid'];
	
?>

<section class="content">
			
      <div class="row">
		
        <div class="col-xs-12">
			
          <div class="box">

<!-- Supplier Invoice Start -->			
			<div class="panel panel-default">
				<?php
				$sql = "SELECT * from sma_supplier_invoice where 1 and del !='Y' and grn_status = 'Completed' and status = 'Draft' and company_id in ( $comid ) ";
				$result = mysqli_query($con,$sql);
				$si_cnt = mysqli_affected_rows($con);
				?>
				<div class="panel-heading">
                    <h4 class="panel-title"><a data-toggle="collapse" data-parent="#steps" href="#step2"><b>  Pending Supplier Invoice</b> ( <span style="font-size:18px;"><?= $si_cnt;?></span> ) <span class="caret"></span> </a></h4>
				</div>
							
                <div id="step2" class="panel-collapse collapse in">
					<div class="panel-body">
						<fieldset>
								
						<div class="box-body">

		<table id="prtable" class="table table-bordered table-striped">

            <thead>
    <tr>
		
        <th>Sr.No.</th>
			<th>Dated</th>
			<th>Supp.Inv.No.</th>
			<th style="text-align:right;">Amount</th>
			<th>Our PO Ref.NO.</th>
			<th>Supplier Name</th>
		    <th>By</th>
			<th>Tally Status</th>
			<th>Decision</th>
			<th>Action</th>
	</tr>
</thead>
<tbody>
<?php

	$modulePath = "supp_invoice/";
	$sql="SELECT * from sma_supplier_invoice where 1 and del !='Y' and grn_status = 'Completed' and status = 'Draft' and company_id in ( $comid ) order by id desc ";
//echo $sql. "<BR>";	
	$result = mysqli_query($con,"$sql");
	echo mysqli_error($con); 
	while($row = mysqli_fetch_array($result)){
	
		$tally_status  = $row['tally_status'];
		
		if(empty($tally_status)){
			$tally_status_a = 'JV Pending';
		}
		else if($tally_status=='R'){
			$tally_status_a = 'JV Created';
		}
		else if($tally_status=='C'){
			$tally_status_a = 'JV Checked ';
		}
		else if($tally_status=='U'){
			$tally_status_a = 'JV Synched';	
		}
		
		$supplier = $row['suplier_name'];
		$sql 	= "select * from sma_party_mst where id = '$supplier' ";
		$q2 	= mysqli_query($con, $sql);
		$r2 	= mysqli_fetch_array($q2);
		$supplier_name = $r2['party_name'];

		$our_po_ref_no = $row['our_po_ref_no'];
		$sql  = " SELECT * from sma_purchase_order where id = '$our_po_ref_no' or po_number = '$our_po_ref_no' ";
		$res  = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res);
		$our_po_ref_no 	= $r1['po_number'];
		$po_rev			= $r1['po_rev'];
		if($po_rev>0){
			$our_po_ref_no 	= $our_po_ref_no .'-'.	$po_rev;
		}
		
		$changed_by = $row['draft_by'];			
		$sql="SELECT * from sma_user where userid = '$changed_by' ";
		$res1 = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r1 = mysqli_fetch_array($res1);
		$changed_by 	= $r1['username'];
		
		$due_date = date('d-m-Y', strtotime($row['due_date']));
		if($due_date=='01-01-1970'){$due_date='';}
		
		$rid = $row['id'];
		$baseurl1 = $baseurl.$modulePath.'edit.php?sub=edit&id='.$row["id"];
				
		?>
	
	<tr >
		<!--<td width="0%"><input type="hidden" value="<?php echo $row['id'];?>"></td>-->
		<td width="3%" style="text-align:right;"><?php echo $row['id']?></td>
		<td width="11%"><?php echo date('d-m-Y', strtotime($row['invoice_date']));?></td>
		<td width="10%"><?php echo $row['supplier_invoice_no'];?></td>
		<td width="10%" style="text-align:right;"><?php echo moneyFormatIndia($row['total_amount'])?></td>
		<td width="15%"><?php echo $our_po_ref_no;?></td>
		<td width="17%"><?php echo $supplier_name;?></td>
		<td width="09%"><?php echo $changed_by;?></td>
		<td width="11%"><?php echo $tally_status_a;?></td>
		<td width="15%" ><?php echo $row['status'].'-'.$row['approval_status'];?></td>
		<td width="10%" style="text-align:right;">
		<a href="<?php echo $baseurl1;?>" name="btnEdit" target= "_blank" title="Edit" placeholder="top center"><i class="fa fa-edit"></i>&nbsp;&nbsp;</a>

		
		</td>
    </tr>
	
	<?php } ?>
</tbody> 
</table>
    </div>
									</fieldset>
									
								  </div>
								</div>
                            </div>
<!--Supplier Invoice End -->



				</div>
			</div> 
		
	</section> 


<?php 	
		include("footer.php");	
?>
<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>

<script>
    $(function () {
        $("#prtable").DataTable();
		$("#prtableA").DataTable();
		$("#prtableB").DataTable();
		$("#prtableC").DataTable();
		$("#prtableD").DataTable();
		$("#prtableE").DataTable();
		$("#prtableF").DataTable();
		$("#prtableG").DataTable();
		$("#prtableH").DataTable();
		$("#prtableI").DataTable();
		$("#prtableJ").DataTable();
    });
</script>


<?php 	

function moneyFormatIndia($num){
        $nums = explode(".",$num);
        if(count($nums)>2){
            return "0";
        }else{
        if(count($nums)==1){
            $nums[1]="00";
        }
        $num = $nums[0];
        $explrestunits = "" ;
        if(strlen($num)>3){
            $lastthree = substr($num, strlen($num)-3, strlen($num));
            $restunits = substr($num, 0, strlen($num)-3); 
            $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; 
            $expunit = str_split($restunits, 2);
            for($i=0; $i<sizeof($expunit); $i++){

                if($i==0)
                {
                    $explrestunits .= (int)$expunit[$i].","; 
                }else{
                    $explrestunits .= $expunit[$i].",";
                }
            }
            $thecash = $explrestunits.$lastthree;
        } else {
            $thecash = $num;
        }

		if($thecash==0){
			$nums = $nums[1];
			if($nums==0){
				$nums[1] = '';
			}
			$thecash = '';
		}
		else{
			$thecash = $thecash.".".$nums[1];
		}
        
		return $thecash;
    }
}


