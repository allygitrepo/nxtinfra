	<?php
include("../dbcon.php");

//$dir = "C:/FTPDATA/Data";
$dir   = "uploads/si/";

$move_dir = "uploads/si";
//$move_fail_dir = "C:/xampp-7.4/htdocs/athaang_mob/fail";

echo $dir."<BR>";
//exit();
		
$company_id= 4;		
//		$comp_code = 'ADHTPL';

$sql 	= "SELECT * FROM sma_supplier_invoice where 1 and del !='Y' and company_id in ( '$company_id' ) ";
$r2 = mysqli_query($con,$sql);
$error  = mysqli_error($con);
if(!empty($error)){ echo "ERROR : " . $error; exit();}
while($row2 = mysqli_fetch_array($r2)){
	
	$id			= $row2['id'];
	$company_id	= $row2['company_id'];
	$dir  		= $dir.$id;
	
	echo $dir."<BR>";
	
	$sql 	= " SELECT * FROM company where comp_id = '$company_id' ";
	$result = mysqli_query($con,$sql);
    $error  = mysqli_error($con);
	if(!empty($error)){ echo "ERROR : " . $error; exit();}
	$row = mysqli_fetch_array($result);
	$comp_code				= $row['comp_code'];
		
		// Open a directory, and read its contents
		if (is_dir($dir)){
		  if ($dh = opendir($dir)){
			while (($file = readdir($dh)) !== false){
				if($file=='.' || $file =='..'  || $file=='index.php'){
					continue;
				}
				
				echo $file. " ##2<BR>";
				
					$folder_path = $move_dir.'/'.$comp_code.'/'.$id;
					if (!file_exists($folder_path)){
						mkdir($folder_path, 0755, true);
					}
						
					$new_filename = $folder_path.'/'.$file;
					$old_filename = $dir.'/'.$file;					 
					copy($old_filename, $new_filename);
									
				exit('###1');
				
			}
			
			closedir($dh);

		//P2P End

			//exit("HELLO");p2p_revenue_hdr 

			//echo "<script>window.close();</script>";	
			exit();
			
		  }
		  
		}
		
}
?>
