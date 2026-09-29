<?php
session_start();

if($_GET['sub'] == 'pdf' || $_GET['sub'] == 'list' || $_GET['bank_id'] ){
	
	include "../dbcon.php";
	include "../baseurl.php";
	
		$bank_id		= $_GET['bank_id'];
		$py_id				= $_GET['id'];
	
	$sql	= "Select * from payment_header where id = '$py_id'";
	$query 	= mysqli_query($con, $sql);
    $row 	= mysqli_fetch_array($query);	
	$company_id 	= $row['company_id'];
		
	$sql 	= "select * from company where comp_id = '$company_id' ";
//echo $sql ."<BR>";
	$q2 	= mysqli_query($con, $sql);
	$r2 	= mysqli_fetch_array($q2);
	$comp_code = $r2['comp_code'];
	$comp_name = $r2['comp_name'];

	$short_fy_code 		= $_SESSION['short_fy_code'];
	
		if($comp_code=='JUHI'){
			
			//if($bank_id==78){
				include "juhi_tds_sbi_print.php";
				exit();
			//}
			
		}
		else if($comp_code=='ADHTPL'){
			
			include "adhtpl_tds_print.php";
			exit();
		}
		else if($comp_code=='ADTPL'){
			include "adtpl_tds_print.php";
			exit();
			
		}
		else if($comp_code=='QB'){
			
			//if($bank_id==75){
				include "qb_tds_kotak_print.php";
				exit();
			//}
			
		}
		else if($comp_code=='KTPL'){
			include "ktpl_tds_print.php";
			exit();
		}
		else if($comp_code=='AIPL'){
			include "aiim_tds_print.php";
			exit('');
			
		}
		else if($comp_code=='AIIMPL'){
			include "aiim_tds_print.php";
			exit();

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
				$nums[1] = '';
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
 
  /**
   * Created by PhpStorm.
   * User: sakthikarthi
   * Date: 9/22/14
   * Time: 11:26 AM
   * Converting Currency Numbers to words currency format
   */
   function numbertoword($num){
	   $number = $num;
	   $no = round($number);
	   $point = round($number - $no, 2) * 100;
	   $hundred = null;
	   $digits_1 = strlen($no);
	   $i = 0;
	   $str = array();
	   $words = array('0' => '', '1' => 'one', '2' => 'two',
		'3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
		'7' => 'seven', '8' => 'eight', '9' => 'nine',
		'10' => 'ten', '11' => 'eleven', '12' => 'twelve',
		'13' => 'thirteen', '14' => 'fourteen',
		'15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
		'18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
		'30' => 'thirty', '40' => 'forty', '50' => 'fifty',
		'60' => 'sixty', '70' => 'seventy',
		'80' => 'eighty', '90' => 'ninety');
	   $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
	   while ($i < $digits_1) {
		 $divider = ($i == 2) ? 10 : 100;
		 $number = floor($no % $divider);
		 $no = floor($no / $divider);
		 $i += ($divider == 10) ? 1 : 2;
		 if ($number) {
			//$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
			$plural = (($counter = count($str)) && $number > 9) ? '' : null;
			$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
			$str [] = ($number < 21) ? $words[$number] .
				" " . $digits[$counter] . $plural . " " . $hundred
				:
				$words[floor($number / 10) * 10]
				. " " . $words[$number % 10] . " "
				. $digits[$counter] . $plural . " " . $hundred;
		 } else $str[] = null;
	  }
	  $str = array_reverse($str);
	  $result = implode('', $str);
	  $points = ($point) ?
		"." . $words[$point / 10] . " " . 
			  $words[$point = $point % 10] : '';
	  if(!empty($points)){
			$points = $points . " Paise";
		}
		else{$points='';}
	  //echo $result . "Rupees  " . $points . " Paise";
	  $words=ucwords($result) . " " . $points;
	  return $words;
	}
