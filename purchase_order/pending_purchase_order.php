<?php if($_GET['sub'] == 'list'){ 
include("../header.php");
$modulePath = "approval/";
?>

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Pending Purchase Order Workflow Report 
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Pending Purchase Order Workflow Report</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
					
						<form class="form-horizontal" action="pending_purchase_order.php?sub=pdf" method="post">
							
							<div class="form-group">
								<div class="col-xs-2">
									<label for="project" class="control-label">&nbsp;</label>
								</div>
								
                                <div class="col-sm-6">
									<label for="Company" class="control-label">Company</label>
                                	<select class="form-control" name="company" id="companY" >
                             		<option value=""> Select </option>
									<option value=""> All </option>
										<?php $sql = "select * from company order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" >  <?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-xs-2">
									<label for="project" class="control-label">&nbsp;</label>
								</div>
								<div class="col-md-2">
									<label class="control-label">Start.Date</label>
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="start_date" name="start_date" autocomplete="off" value="<?php echo $start_date;?>" > 
										<div class="input-group-addon">
												<i class="fa fa-calendar-alt"></i>
										</div>
									</div>
								</div>
								<div class="col-md-2">
									<label class="control-label">End.Date</label>
									<div class="input-group date" data-provide="datepicker" data-date-format="dd-mm-yyyy">
										<input type="text" class="form-control" id="end_date" name="end_date" autocomplete="off" value="<?php echo $end_date;?>" > 
										<div class="input-group-addon">
												<i class="fa fa-calendar-alt"></i>
										</div>
									</div>
								</div>
							
							</div>
							
							<div class="form-group">
								<div class="col-xs-2">
									<label for="project" class="control-label">&nbsp;</label>
								</div>
								
                                <div class="col-xs-3">
									<input class="btn btn-primary" type="submit" value="Submit" name="submit">&nbsp;&nbsp;&nbsp;
								</div>
								
								<div class="col-xs-2">
									<label for="project" class="control-label">&nbsp;</label>
								</div>
								
								<div class="col-xs-2">
									<a href="../dashboard.php?sub=list" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;cancel</a>
								</div>
								
							</div>
						
						
				</form>
<?php 

	include("../footer.php");

 }
 
 ?>
 
<?php 

if($_GET['sub'] == 'pdf'){
	
//	include("../header.php");
	$modulePath = "purchase_order/"; 
	
	$user   	= $_SESSION['user'];
	$role		= $_SESSION['role']; //Maker
	$prn		= "excel";
	$company	= $_POST['company'];
	$start_date	= date('Y-m-d', strtotime($_POST['start_date']));
	$end_date	= date('Y-m-d', strtotime($_POST['end_date']));

	include "../dbcon.php";
	include "../baseurl.php";

	$phead = 'For the Period '.date('d-m-Y', strtotime($_POST['start_date'])). ' To ' . date('d-m-Y', strtotime($_POST['end_date']));
//echo dirname(__FILE__);
//exit();

/**
 * HTML2PDF Librairy - example
 *
 * HTML => PDF convertor
 * distributed under the LGPL License
 *
 * @author      Laurent MINGUET <webmaster@html2pdf.fr>
 *
 * isset($_GET['vuehtml']) is not mandatory
 * it allow to display the result in the HTML format
 */
	
	$message = '';
	$prev_purchase_id = '';
	
	$message .= "<table border='1' cellspacing='10' style='width: 95%; text-align: center; font-size: 12pt;margin-left:10px;margin-top: 12px;'>
			<tr><td style='width: 95%;text-align: Center;'>Pending Purchase Order $phead</td></tr>
			</table>";
/* 
	$message .= "<table cellspacing='0' style='width: 95%; text-align: center; margin-left:10px;font-size: 12pt;'>
			<tr><th style='width: 95%;'> &nbsp;</th></tr></table> ";
*/

	$message .= "<table border='1' cellspacing='0' style='width: 95%; text-align: center;margin-left:10px; font-size: 12px;' >
			<tr><td style='width: 06%;text-align: Center;font-size:12px;'><b> PO.No.</b></td>
				<td>Doc.No.</td>
				<td>Company Code</td>
				<td>Party Name</td>
				<td>Mobile</td>
				<td>Email</td>
				<td>Approval Memo No.</td>
				<td>Dated</td>
				<td>Contact Person Name</td>
				<td>Product </td>
				<td>Product Desc </td>
				<td>Fin.Year </td>
				<td>Cost Center Group </td>
				<td>Cost Center Sub Group </td>
				<td>Order Qty</td>
				<td>Received Qty.</td>
				<td>Bal.Qty.</td>
				<td>Unit</td>
				<td> Unit Rate </td>
				<td> GST% </td>
				<td> Order Value</td>
				<td> Received Value</td>
				<td> Pending Value</td>
				<td> Status</td>
			</tr></table>";
				
	$tableName		= "sma_purchase_order";
	//$sql 	= "SELECT * FROM $tableName where 1 and quantity > bal_si_qty and status = 'Completed'";
	$company_sql = '';
	if(!empty($company)){
		$company_sql = " and  b.project = '$company' ";
	}
	$sql = " SELECT distinct(b.id) as id_number, b.* 
				FROM `sma_po_items` a , sma_purchase_order b 
					where a.purchase_id = b.id and a.quantity > a.bal_si_qty and status in ('Completed', 'Draft', 'Submitted' )
					and b.dated >= '$start_date' and b.dated <= '$end_date' " . $company_sql ;

	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
		
		$purchase_id			= $row['id'];
		$dated  				= date('d-m-Y', strtotime($row['dated']));
		$to_supplier			= $row['to_supplier'];
		$company_id				= $row['project'];
		$approval_memo_ref		= $row['approval_memo_ref'];
		$quotation_reference_no = $row['quotation_reference_no'];
		$po_number  			= $row['po_number'];
		$po_rev		  			= $row['po_rev'];
		$location	 			= $row['location'];
		$discount 				= $row['discount'];
		$transport 				= $row['transport'];
		$other_charges 			= $row['other_charges'];
		$terms 					= $row['terms'];
		$status					= $row['status'];
		$delivery_address		= $row['delivery_address'];

		$header_text			= $row['header_text'];
		$po_doc_type 			= $row['po_doc_type'];
		
	
	if($po_doc_type=='PO'){
		$po_desc = "Purchase Order";
	}
	else if($po_doc_type=='WO'){
		$po_desc = "Work Order";
	}
	else if($po_doc_type=='SO'){
		$po_desc = "Service Order";
	}
	else if($po_doc_type=='CA'){
		$po_desc = "Contract Agreement";
	}
	
	$sql="SELECT * FROM `company` where comp_id = '$company_id' ";
	$comresult 	= mysqli_query($con,$sql);
	$com 				= mysqli_fetch_array($comresult);	
	$comp_code 			= $com['comp_code'];
	
	$sql 	= "SELECT * FROM sma_party_mst where id = '$to_supplier'";
	$par_res = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
		$par = mysqli_fetch_array($par_res);
		$party_name  	 = $par['party_name'];
		$party_address_1 = $par['party_address_1'];
		$party_address_2 = $par['party_address_2'];
		$party_address_3 = $par['party_address_3'];
		$party_city  	 = $par['party_city'];
		$party_pincode   = $par['party_pincode'];
		$party_mobile    = $par['party_mobile'];
		$party_email     = $par['party_email'];
		$party_contact_person_name = $par['party_contact_person_name'];
	
	$sql 	= " SELECT * FROM cities where id = '$party_city' ";
	$qry1 = mysqli_query($con,$sql);
	$cty = mysqli_fetch_array($qry1);
	$party_city  = $cty['city_name'];

	$ln =0;
	
	//$sql 	= "SELECT * FROM sma_po_items where purchase_id = '$id'";
	$sql = " SELECT a.* FROM `sma_po_items` a , sma_purchase_order b where a.purchase_id = '$purchase_id' and a.purchase_id = b.id and a.quantity > a.bal_si_qty and status in ('Completed', 'Draft', 'Submitted' ) ";
	
	$qry2 = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	
	while($res2 = mysqli_fetch_array($qry2)){
	
		$budget_id		= $res2['budget_id'];
		$quantity		= $res2['quantity'];
		$unit_rate		= round($res2['unit_rate'],2);
		$pod_discount	= $res2['pod_discount'];
		$gst			= $res2['gst'];
		$product_desc   = $res2['product_desc'];
		$tot_qty		= $quantity;
		$bal_si_qty		= $res2['bal_si_qty'];
		$bal_qty		= $quantity - $bal_si_qty;
		
		$actual_amt     = $quantity * $unit_rate;
		
		$net_amt  		= round($actual_amt ,0);
		
		$order_amt			= round( ( $quantity * $unit_rate) + ( ($quantity * $unit_rate) * $gst / 100) ,0);
		$received_amt		= round( ( $bal_si_qty * $unit_rate) + ( ($bal_si_qty * $unit_rate) * $gst / 100) ,0);
		$pending_amt		= round( ( $bal_qty * $unit_rate) + ( ($bal_qty * $unit_rate) * $gst / 100) ,0);
		
		$delivery_date  = date('d-m-Y', strtotime($res2['delivery_date']));
		
		$product_id     = $res2['product_id'];
		$sql="Select * from sma_product where id = '$product_id'";
		$output = mysqli_query($con,$sql);
		echo mysqli_error($con);
		$r2 = mysqli_fetch_array($output);

		$product_name = $r2['name'];
		
		$unit		  = $r2['uom'];
		$hsn_code	  = $r2['hsn_code'];
		
		$gst_amt  	  = $gst_amt + round(($net_amt - $discount )* $gst / 100,0);
			
		$sql ="SELECT * FROM `sma_budget` where id = '$budget_id' ";
		$q2  = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2  = mysqli_fetch_array($q2);
		$budget_head 		= $r2['budget_category'];
		$budget_name 		= $r2['budget_name'];
		$budget_head_name 	= $r2['budget_head_name'];
		$account_year 		= $r2['account_year'];
		//$project 	 = $r2['project'];
		
		$sql ="SELECT * FROM `sma_budget_name` where id = '$budget_name' ";
		$q2  = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2  = mysqli_fetch_array($q2);
		$budget_name = $r2['name'];
		
		/* $sql ="SELECT * FROM `sma_budget_category` where id = '$budget_head' ";
		$q2  = mysqli_query($con, $sql);
		echo mysqli_error($con);
		$r2  = mysqli_fetch_array($q2);
		$budget_head = $r2['category']; */
		
		$message .= "<table border='1' cellspacing='0' style='width: 95%; text-align: left;margin-left:10px; font-size: 14px;valign=top;' >
			<tr>
				<td style='text-align: left; valign=top; '>$po_number</td>
				<td>$purchase_id</td>
				<td>$comp_code</td>
				<td>$party_name </td>
				 <td>$party_mobile</td>
				 <td>$party_email</td> 
				<td>$quotation_reference_no</td>
				<td>$dated</td>
				<td>$party_contact_person_name</td>
				<td>$product_name</td>
				<td>$product_desc </td>
				<td>$account_year</td>
				<td>$budget_name</td>
				<td>$budget_head_name</td>
				<td style='text-align: right; valign=top; '>$quantity</td>
				<td style='text-align: right; valign=top;'>$bal_si_qty</td>
				<td style='text-align: right; valign=top;'>$bal_qty</td>
				<td>$unit </td>
				<td style='text-align: right; valign=top;'>$unit_rate </td>
				<td style='text-align: right; valign=top;'>$gst</td>
				<td style='text-align: right; valign=top;'>$order_amt</td>
				<td style='text-align: right; valign=top;'>$received_amt</td>
				<td style='text-align: right; valign=top;'>$pending_amt</td>
				<td style='text-align: right; valign=top;'>$status</td>
				
			</tr></table>";
			
			$sql = " INSERT INTO pending_po( po_number, doc_no, comp_code, vender_name, approval_memo_no, dated, contact_person_name, product_name, product_desc, fin_year, cost_center_group, cost_center_subgroup, order_qty, received_qty, balance_qty, unit, unit_rate, gst, order_value, received_value, pending_value, status ) VALUES ( '$po_number', 
				'$purchase_id', '$comp_code', '$party_name', '$quotation_reference_no', '$dated', 
				'$party_contact_person_name', '$product_name', '$product_desc', '$account_year', 
				'$budget_name', '$budget_head_name', '$quantity', '$bal_si_qty', '$bal_qty', '$unit',
				'$unit_rate', '$gst', '$order_amt', '$received_amt', '$pending_amt', '$status' ) ";
			mysqli_query($con,$sql);
			echo mysqli_error($con);
		
	}
	
	
}

//print $message;
//exit();
	
    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();
	//$fl_name = 'poorder_'.$id;
//    if($prn=='excel'){
	if(empty($company)){
		$comp_code ='All';	
	}	
		$fl_name = 'AT_'.$comp_code.'_pending_PO'.date('Y-m-d'). '.xls';
		header("Content-type: application/xls");
		Header("Content-Disposition: attachment; filename=$fl_name");

		print $message;
//	}	

	
}


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

?>

	
	
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>    	
<script>

function viewmail(id){

		
		//alert("PO "+id);
		var strURL = "prn_func.php";
		$.post(strURL,{id:id},function(result){
		      $('#viewmail').html(result);
		});
		
	}
	
</script>
