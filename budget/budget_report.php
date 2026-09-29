<?php if($_GET['sub'] == 'list'){ 
include("../header.php");
$modulePath = "approval/";
?>

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Budget report 
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Budget report</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
					
						<form class="form-horizontal" action="budget_report.php?sub=pdf" method="post">
                      
							<div class="form-group">
								<div class="col-md-2">
									<label class="control-label">Fin.Year</label>
									<select class="form-control" name="account_year" id="account_year" required >
										<option value=""> Select </option>
											<?php $sql = "select * from sma_financial_year order by short_fy_code desc";
												$q2 	= mysqli_query($con, $sql);
											while($r2 = mysqli_fetch_array($q2)){ ?>
										<option value="<?php echo $r2['short_fy_code'];?>" ><?php echo $r2['short_fy_code'];?></option>
										<?php } ?>
									</select>
								</div>
							</div>	
								
							<div class="form-group">
								<div class="col-md-2">
									<label class="control-label">Month</label>
									<select class="form-control" name="month" id="month" required >
										<option value=""> Select </option>
										<option value="01" > January </option>
										<option value="02" > February </option>
										<option value="03" > March </option>
										<option value="04" > April </option>
										<option value="05" > May </option>
										<option value="06" > June </option>
										<option value="07" > July </option>
										<option value="08" > August </option>
										<option value="09" > September </option>
										<option value="10" > October </option>
										<option value="11" > November </option>
										<option value="12" > December </option>
									</select>
								</div>
							</div>
							
							<div class="form-group">
								<div class="col-sm-4">
									<label for="Company" class="control-label">Company</label>
                                	<select class="form-control" name="company_id" id="companY"  >
                             		<option value=""> Select </option>
									<option value="" selected > All </option>
										<?php $sql = "select * from company where comp_id in ($comid) order by comp_name ";
										$q2 	= mysqli_query($con, $sql);
										while($r2 = mysqli_fetch_array($q2)){ ?>
									<option value="<?php echo $r2['comp_id'];?>" ><?php echo $r2['comp_name'];?></option>
										<?php } ?>
									</select>		
								</div>
								
								
							</div>
							
							<div class="form-group">
								<div class="col-xs-4">
									<label for="project" class="control-label">&nbsp;</label>
								</div>
								
								<div class="col-xs-2">
                                	<input class="btn btn-primary" type="submit" value="Submit" name="submit">&nbsp;&nbsp;&nbsp;
									<a href="../dashboard_athang.php?sub=list&" name="btnCancel" class="btn btn-primary btn-inverse"><i class="splashy-refresh_backwards"></i>&nbsp;&nbsp;cancel</a>
								</div>
								
							</div>
						
						
				</form>
<?php 

	include("../footer.php");

 }
 ?>
 

<?php
if($_GET['sub'] == 'pdf'){

	session_start();
	
	include "../dbcon.php";
	include "../baseurl.php";

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
	//$message="<table><tr><td>Table</td></tr></table>";

	
	$budget_name_prev ='';
	$project_prev ='';
	
	$comid  = $_SESSION['comid'];

	$prn		= "excel";

	$mth		= $_POST['month'];	
	$company_id = $_POST['company_id'];
	$account_year	= $_POST['account_year'];
		
	//$mth		= date("m");
			
	$curyear  = date("y");
	$nextyear = $curyear;
	if($mth=='01' ){
		$current_mth = 'December';
		$last_mth = 'January';
		$cmth		= 9;
	}
	else if($mth=='02' ){
		$current_mth = 'January';
		$last_mth = 'February';
		$cmth		= 10;
	}
	else if($mth=='03' ){
		$current_mth = 'February';
		$last_mth = 'March';
		$cmth		= 11;
	}
	else if($mth=='04' ){
		$current_mth = 'March';
		$last_mth = 'April';
		$nextyear = $curyear + 1;
		$cmth		= 12;
	}
	else if($mth=='05' ){
		$current_mth = 'April';
		$last_mth = 'May';
		$nextyear = $curyear + 1;
		$cmth		= 1;
	}
	else if($mth=='06' ){
		$current_mth = 'May';
		$last_mth = 'June';
		$nextyear = $curyear + 1;
		$cmth		= 2;
	}
	else if($mth=='07' ){
		$current_mth = 'June';
		$last_mth = 'July';
		$nextyear = $curyear + 1;
		$cmth		= 3;
	}
	else if($mth=='08' ){
		$current_mth = 'July';
		$last_mth = 'August';
		$nextyear = $curyear + 1;
		$cmth		= 4;
	}
	else if($mth=='09' ){
		$current_mth = 'August';
		$last_mth = 'September';
		$nextyear = $curyear + 1;
		$cmth		= 5;
	}
	else if($mth=='10' ){
		$current_mth = 'September';
		$last_mth = 'October';
		$nextyear = $curyear + 1;
		$cmth		= 6;
	}
	else if($mth=='11' ){
		$current_mth = 'October';
		$last_mth = 'November';
		$nextyear = $curyear + 1;
		$cmth		= 7;
	}
	else if($mth=='12' ){
		$current_mth = 'November';
		$last_mth = 'December';
		$nextyear = $curyear + 1;
		$cmth		= 8;
	}	
		
	
	$message ='';
	
	$message .= "<table border='1' cellspacing='0' style='width: 100%; text-align: center; font-size: 12pt;'>
			<tr><th style='width: 100%;' colspan='11'> Budget Report </th></tr></table>";		
	
		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: center; font-size: 12pt; background-color: skyblue;'>
				<tr><td style='width: 10%;'> Company </td>
					<td style='width: 10%;'> Budget Group </td>
					<td style='width: 10%;text-align: left;'> BUDGET SUB GROUP</td>
					
					<td style='width: 10%;text-align: left;'> Board Approved </td>
					<td style='width: 10%;'> Additional Board Approved </td>
					
					
					<td style='width: 10%;'> Inter Head Transfer </td>
					<td style='width: 10%;text-align: left;'> Inter Head Transfer %</td>
					 
					<td style='width: 10%;'>Proportionate upto $current_mth $curyear</td>
					<td style='width: 10%;'>Total Available Budget upto $current_mth $curyear</td>
					
					<td style='width: 10%;'>Actual Expenses upto $current_mth $curyear</td>
					<td style='width: 10%;'>Variance</td>
					<td style='width: 10%;'>Balance</td>
					
				</tr></table>";

		$message .= "<table border='1' cellspacing='0' style='width: 100%; border: solid 1px black; text-align: left; font-size: 10pt;'>";
				
	$id				= $_GET['id'];
	
	$tableName	= "sma_budget";
	
	$sqla = "";
	if(!empty($company_id)){
		$sqla = " AND project = '$company_id' ";
	}
	
	$sql 	= " SELECT * FROM $tableName where 1 and account_year = '$account_year' and project in ($comid) $sqla order by project, budget_name ";
//echo $sql; exit();	
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	while($row = mysqli_fetch_array($result)){
	
		$budget_id	 	= $row['id'];
		$project 		= $row['project'];
		$sql = "SELECT * from company where comp_id = '$project' ";
		$res = mysqli_query($con, $sql);
		$r2 = mysqli_fetch_array($res);
		
		$project 				= $r2['comp_name'];
		
		$budget_code 			= $row['budget_code'];		
		$budget_name			= $row['budget_name'];
		$budget_head			= $row['budget_head'];
		$board_approved_budget	= $row['board_approved_budget'];
		
		$account_year			= $row['account_year'];
		
		$total_budget			= $row['total_budget'];
		$used_budget			= $row['used_budget'];
		$blocked_budget			= $row['blocked_budget'];
		$adjustment_budget		= $row['adjustment_budget'];
		//$balance_budget			= ($total_budget + $adjustment_budget) - ( $row['used_budget'] + $row['blocked_budget'] );
	
		$sql 		= "select * from sma_budget_name where id = '$budget_name' ";
		$res 		= mysqli_query($con,$sql);
		$rw  		= mysqli_fetch_array($res);
		$budget_name	= $rw['name'];
		
		$sql = " SELECT * from sma_budget_subgroup WHERE id = '$budget_head' ";
		$res = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($res);
		$budget_head 		= $r2['budget_head'];
			
		$sql = " SELECT sum(amount) as budget_adjustment FROM `budget_adjust` where budget_id = '$budget_id' and status = 'Completed'; ";
		$res = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($res);
		$budget_adjustment 		= $r2['budget_adjustment'];
		
		$sql = " SELECT sum(amount) as budget_transfer_from FROM `budget_adjust_from_to` where budget_id_from = '$budget_id' and status = 'Completed'; ";
		$res = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($res);
		$budget_transfer_from 		= ( $r2['budget_transfer_from'] * -1 );
		
		$sql = " SELECT sum(amount) as budget_transfer_to FROM `budget_adjust_from_to` where budget_id_to = '$budget_id' and status = 'Completed'; ";
		$res = mysqli_query($con, $sql);
		$r2  = mysqli_fetch_array($res);
		$budget_transfer_to 		= $r2['budget_transfer_to'];
		
		$transfer_budget = '';
		if($budget_transfer_to !=0){
			$transfer_budget			= $budget_transfer_to;
		}
		else if($budget_transfer_from !=0){
			$transfer_budget			= $budget_transfer_from;
		}
		
		$total_available_budget = $total_budget + $budget_adjustment + $transfer_budget;
		
		//$variance_budget			= $total_available_budget - $used_budget;
		
		$balance_budget				= ( $total_budget + $budget_adjustment + $transfer_budget ) - $used_budget;
		
		$forcast_budget 			= $balance_budget;
		
		$factor	 = '';
		
		/* if($transfer_budget!=0 && ($board_approved_budget>0 || $budget_adjustment >0 ) ){
			$factor					= round( ( $transfer_budget / ( $board_approved_budget + $budget_adjustment ) ) * 100 ,2); 
		} */
		
		if( $transfer_budget!=0 && $total_budget!=0 ){
			$factor = round( ( ($transfer_budget / $total_budget ) * 100 ) ,2);
		}
		
		$proportion_budget = ($total_budget / 12 ) * $cmth;
		
		$variance_budget = $total_available_budget - $proportion_budget;
		
		$balance_budget	 = $total_available_budget - $used_budget;
		
		
		if( ($budget_name_prev != $budget_name || $project_prev != $project ) && !empty($budget_name_prev) && !empty($project_prev) ){
			$message .= "<tr style='background-color: #D3D3D3; font-weight: bold;'>
					<td>".$project_prev."</td>
					<td>".$budget_name_prev."</td>
					<td></td>
					<td style='text-align: right;'>".moneyFormatIndia($total_budget_gtot)."</td>
					<td style='text-align: right;'></td>
					<td style='text-align: right;'></td>
					<td style='text-align: right;'></td>
					
					<td style='text-align: right;'>".moneyFormatIndia($proportion_budget_gtot)."</td>
					<td style='text-align: right;'>".moneyFormatIndia($total_available_budget_gtot )."</td>
					<td style='text-align: right;'>".moneyFormatIndia($used_budget_gtot) ."</td>
					<td style='text-align: right;'>".moneyFormatIndia($variance_budget_gtot)."</td>
					<td style='text-align: right;'>".moneyFormatIndia($balance_budget_gtot)."</td>
					</tr>";

			$total_budget_gtot 		= 0;
			$proportion_budget_gtot = 0;
			$total_available_budget_gtot = 0;
			$used_budget_gtot 		= 0;
			$balance_budget_gtot 	= 0;
			$variance_budget_gtot	= 0;
			
		}	
		
		$total_budget_gtot 		= $total_budget_gtot + $total_budget;
		$variance_budget_gtot 	= $variance_budget_gtot + $variance_budget;
		$proportion_budget_gtot	= $proportion_budget_gtot + $proportion_budget;
		$total_available_budget_gtot = $total_available_budget_gtot + $total_available_budget;
		$used_budget_gtot 		= $used_budget_gtot + $used_budget;
		$balance_budget_gtot 	= $balance_budget_gtot + $balance_budget;
		
		
		$total_budget_ftot 		= $total_budget_ftot + $total_budget;
		$variance_budget_ftot 	= $variance_budget_ftot + $variance_budget;
		$proportion_budget_ftot	= $proportion_budget_ftot + $proportion_budget;
		$total_available_budget_ftot = $total_available_budget_ftot + $total_available_budget;
		$used_budget_ftot 		= $used_budget_ftot + $used_budget;
		$balance_budget_ftot 	= $balance_budget_ftot + $balance_budget;
		
		$message .= "<tr>
					<td>".$project."</td>
					<td>".$budget_name."</td>
					<td>".$budget_head ."</td>
					<td style='text-align: right;'>".moneyFormatIndia($total_budget)."</td>
					<td style='text-align: right;'>".moneyFormatIndia($budget_adjustment) ."</td>
					<td style='text-align: right;'>".moneyFormatIndia($transfer_budget) ."</td>
					<td style='text-align: right;'>".moneyFormatIndia($factor) ." </td>
					
					<td style='text-align: right;'>".moneyFormatIndia($proportion_budget)."</td>
					<td style='text-align: right;'>".moneyFormatIndia($total_available_budget) ."</td>
					<td style='text-align: right;'>".moneyFormatIndia($used_budget) ."</td>
					<td style='text-align: right;'>".moneyFormatIndia($variance_budget)."</td>
					<td style='text-align: right;'>".moneyFormatIndia($balance_budget)."</td>
					</tr>";
			
			$budget_name_prev = $budget_name;
			$project_prev = $project;
	
	}
	
	$message .= "<tr style='background-color: #D3D3D3; font-weight: bold;'>
					<td>".$project_prev."</td>
					<td>".$budget_name_prev."</td>
					<td></td>
					<td style='text-align: right;'>".moneyFormatIndia($total_budget_gtot)."</td>
					<td style='text-align: right;'></td>
					<td style='text-align: right;'></td>
					<td style='text-align: right;'></td>
					
					<td style='text-align: right;'>".moneyFormatIndia($proportion_budget_gtot)."</td>
					<td style='text-align: right;'>".moneyFormatIndia($total_available_budget_gtot) ."</td>
					<td style='text-align: right;'>".moneyFormatIndia($used_budget_gtot) ."</td>
					<td style='text-align: right;'>".moneyFormatIndia($variance_budget_gtot)."</td>
					<td style='text-align: right;'>".moneyFormatIndia($balance_budget_gtot)."</td>
					
					</tr>";
	
	$message .= "<tr style='background-color: #D3D3D3; font-weight: bold;'>
					<td></td>
					<td></td>
					<td></td>
					<td style='text-align: right;'>".moneyFormatIndia($total_budget_ftot)."</td>
					<td style='text-align: right;'></td>
					<td style='text-align: right;'></td>
					<td style='text-align: right;'></td>
					
					<td style='text-align: right;'>".moneyFormatIndia($proportion_budget_ftot)."</td>
					<td style='text-align: right;'>".moneyFormatIndia($total_available_budget_ftot) ."</td>
					<td style='text-align: right;'>".moneyFormatIndia($used_budget_ftot) ."</td>
					<td style='text-align: right;'>".moneyFormatIndia($variance_budget_ftot)."</td>
					<td style='text-align: right;'>".moneyFormatIndia($balance_budget_ftot)."</td>
					</tr>";
					
	$message .= "</table>";
	
//echo $message;
//exit();
	
    // get the HTML
    ob_start();
    //include(dirname(__FILE__).'../res/exemple07a.php');
    //include(dirname(__FILE__).'../res/exemple07b.php');
    //$content = ob_get_clean();
	//$fl_name = 'poorder_'.$id;
    
	if($prn=='excel'){
		$fl_name = 'budget_report.xls';
		header("Content-type: application/xls");
		header("Content-Type:'application/force-download'");
		Header("Content-Disposition: attachment; filename=$fl_name");
	
		print $message;
	}

    // convert to PDF
	if($prn=='pdf'){
	//$baseurl
		$dirname = 'C:\xampp\htdocs\hc_template';
		//require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');
		require_once($dirname.'/html2pdf/html2pdf.class.php');
		try
		{
			$fl_name  = 'budget_export.pdf';
			$html2pdf = new HTML2PDF('P', 'A4', 'fr');
			$html2pdf->pdf->SetDisplayMode('fullpage');
	//      $html2pdf->pdf->SetProtection(array('print'), 'spipu');
		   // $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
			$html2pdf->writeHTML($message);
			$html2pdf->Output($fl_name);
			
		}
		catch(HTML2PDF_exception $e) {
			echo $e;
			exit;
		}
	}
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
				$nums[1] = '0';
			}
			$thecash = '';
		}
		else{
			//$thecash = $thecash.".".$nums[1];
			$thecash = $thecash;
		}
        
		return $thecash;
    }
}