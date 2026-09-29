<?php
include("../../dbcon.php");
//$dir = "../data/auto";
//$dir = "/home/bidsdpei/public_html/bidsdms.in/edmstest";

require '../../PHPMailer-master/PHPMailerAutoload.php';

$dir_array = array('AIPL','ADTPL','ADHTPL','QB','KTPL','JUHI', 'AIIMPL');

foreach($dir_array as $comp_dir){

//	echo $comp_dir.'<br>';

$dir = "c:/xampp-7.4/htdocs/p2p2023/payment/tds_certificate/". $comp_dir;

$move_dir = "c:/xampp-7.4/htdocs/p2p2023/payment/tds_certificate/success/". $comp_dir;

//echo $dir."<BR>";
//exit();
	
	$error_msg ='';
	$loc_arr = '';
	$file_uploaded_msg ='';
	$file_uploaded_error = '';

// Open a directory, and read its contents
if (is_dir($dir)){
  if ($dh = opendir($dir)){
    while (($file = readdir($dh)) !== false){
		$info = explode(".",$file);
		if(strtolower(end($info)) == 'pdf'){
			
			$file_name	= $file;
			
			$flname 	= explode("_",$file);

			// echo "<script>alert('Please upload csv format file...')</script>";
			//$bpath = $bpath.$modulePath;
			//echo "<script>window.location.href='$bpath';</script>";
		
			$flname = explode("_",$file);
			$fl_name = $file;
			
			$fcnt 	= count($flname);
			$branch = '';
			$success_msg = '';
			
			//$vendor_name = $flname[0]; 'AIPL','ADTPL','ADHTPL','QB'
			if ( $comp_dir=='ABC'  ){
				//         0               1       2  3         4  
				//AMIT ARVIND SUBHEDAR_BSMPS7599P_Q1_AY202122_16A.pdf
				//AXIS TRUSTEE SERVICES LIMITED_AAHCA3172B_Q1_AY202122_16A.pdf
				$panno  = $flname[1];
				$period = $flname[2].'_'.$flname[3];
				$text   = $flname[2].'_'.$flname[3].'_'.$flname[4];
			}
			else if ( $comp_dir=='AIIMPL' || $comp_dir=='AIPL' || $comp_dir=='ADTPL' || $comp_dir=='ADHTPL' || $comp_dir=='QB' || $comp_dir=='JUHI' || $comp_dir=='KTPL'){
				//IDBI TRUSTEESHIP SERVICES LTD_AAACI8912J_Q2_AY202122_16A.pdf		
				
				$panno = $flname[0];
				$period1 = explode(".",$flname[2]);
				$period = $flname[1].'_'.$period1[0];
				$text   	= $flname[1].'_'.$flname[2];
			}
			
//print_r($flname);
//exit();
			$file_ext	= strtolower(end($info));
			$today_date	= date("Y-m-d");
			
			$panno_len = strlen($panno);
/* echo $panno. "<BR>";			
if(trim($panno) != 'AABCI7215D'){
	continue;
} */
			
echo $panno . ' <<##1>> ' . $panno_len . ' <<##2>> ' . $text . ' <<##3>> ' . $period . "<BR>";
//exit();


			if($panno_len==10){
				
				$sql = " select * from sma_party_mst where trim(party_pan_number) = '$panno' ";
				$q2  = mysqli_query($con, $sql);
				$r2  = mysqli_fetch_array($q2);
				$party_pan_number = $r2['party_pan_number'];
				$vendor_name	  = $r2['party_name'];
				
//			echo $party_pan_number. ' << ###1 >> ' . $panno. ' << ####2 >> '. "<BR>";
//exit();			
				if( trim($party_pan_number) == trim($panno) ){
					
					$party_email	  = $r2['party_email'];
					$party_name	      = $r2['party_beneficiary_name'];
					//echo $party_pan_number . ' ' . $party_email. ' ' . $file . "<BR>";
	
					include "tds_mail.php";
				
					$new_filename = $move_dir.'/'.$file;
					$old_filename = $dir.'/'.$file;
			//echo $new_filename. "<BR>";
			//echo $old_filename. "<BR>";	
					
					rename($old_filename, $new_filename);
					/* if (file_exists($old_filename) && 
						((!file_exists($new_filename)) || is_writable($new_filename))) {
						rename($old_filename, $new_filename);
					}
					else { 
						unlink($old_filename);
					} */
					
					$upload_date = date('Y-m-d');
					$sql = "INSERT into tds_cert_upload (vendor_name, panno, period, text, file_ext, upload_date, file_name, comp_code) 
							values('$vendor_name', '$panno', '$period', '$text', '$file_ext', '$upload_date', '$file_name', '$comp_dir' ) ";
//echo $sql."<BR>";							
					mysqli_query($con, $sql);
					$error= mysqli_error($con);
					//if(!empty($error)){echo $error; exit();}	
					echo $error;
					
//					exit('###1234');
					
				}
				else {
					$error_msg .= 'Error : PAN Number not found in party Master : '. $panno . '  Company Code : ' . $comp_dir . "<br>";
				}	
			
			}
			
		//exit('#####123');

		}
		else {
			continue;
		}
		
	}
	
    closedir($dh);
	
	
	$loc_arr .= "'0'";

//	require '../PHPMailer-master/PHPMailerAutoload.php';

	$loc_na 	= '';
	$error_msg 	= '';
	$err 		= '';

    } 
  }

}	
	
	if(!empty($error_msg)){
		include "tds_error_mail.php";
	}
	

	echo "<script>window.close();</script>";	
	exit();
	
?>
