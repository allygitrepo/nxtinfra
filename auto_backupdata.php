<!--<script type="text/javascript" src="js/jquery-1.6.1.min.js"></script>-->
<!--<script type="text/javascript" src="js/jquery.easyui.min.js"></script>-->

<!--<script src="jui/js/jquery-ui-1.9.2.min.js"></script>-->
<!--<script src="1314/jquery.min.js"></script>-->
<?php
	session_start();
?>

<?php
function backup_tables($host,$user,$pass,$name,$bckp_flnm,$tables = '*') {
    //$link = mysql_connect($host,$user,$pass);
	$link = mysqli_connect($host,$user,$pass,$name);
    //mysql_select_db($name,$link);
    mysqli_query($link,"SET NAMES 'utf8'");

    $i=0;
    //get all of the tables
    if($tables == '*'){
        $tables = array();
        $result = mysqli_query($link,'SHOW TABLES');
        while($row = mysqli_fetch_row($result))
        {
            $tables[] = $row[0];
			++$i;
        }
    }
    else
    {
        $tables = is_array($tables) ? $tables : explode(',',$tables);
    }

	
    $return='';
    //cycle through

		$return.= 'create database IF NOT EXISTS '.$name.';';
		$return.= "\n\n";
        $return.= 'use '.$name.';';
		$return.= "\n\n";

    foreach($tables as $table){
        $result = mysqli_query($link,'SELECT * FROM '.$table);
        $num_fields = mysqli_num_fields($result);
        $return.= 'DROP TABLE '.$table.';';
        $row2 = mysqli_fetch_row(mysqli_query($link,'SHOW CREATE TABLE '.$table));
        $return.= "\n\n".$row2[1].";\n\n";

        for ($i = 0; $i < $num_fields; $i++) {
            while($row = mysqli_fetch_row($result))
            {
                $return.= 'INSERT INTO '.$table.' VALUES(';
                for($j=0; $j<$num_fields; $j++) 
                {
                    $row[$j] = addslashes($row[$j]);
                    $row[$j] = str_replace("\n","\\n",$row[$j]);
                    if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
                    if ($j<($num_fields-1)) { $return.= ','; }
                }
                $return.= ");\n";
            }
        }
        $return.="\n\n\n";
    }

	//echo $return;
	
	$flnm='/home/u174398304/domains/nxtinfra-p2p.com/public_html/backup/'.$bckp_flnm.'.csv';

    $handle=fopen($flnm,'w+');
      
    fwrite($handle,$return);

    fclose($handle);
	
//	header("Location: $flnm");
//	header("Content-disposition: attachment; filename = $flnm");
	//$fl_name = "/home/u174398304/domains/nxtinfra-p2p.com/public_html/admin/student_enroll_list.csv";
	$new_filename = '/home/u174398304/domains/nxtinfra-p2p.com/public_html/backup/'.$flnm;
	$old_filename = $flnm;					 
	rename($old_filename, $new_filename);

	echo "<script>window.close();</script>";	
	exit();
	
//		include("../header.php"); 
		
//		include("../baseurl.php");
//		$baseurl1 = $baseurl. 'dashboard.php?sub=list';
//		echo $baseurl1;
//		echo "<script>alert('Backup process done...'); window.location.href='$baseurl1';</script>";
//exit("STOP RAVINDRA");		
}
?>

<?php
// $con = mysqli_connect("localhost","u174398304_awcqrs","Symphony@123#","u174398304_awcqrs");
//$con = mysqli_connect("localhost","u174398304_nxtinfra_p2p","Nxtinfra@123#","u174398304_nxtinfra_p2p");

	$dbhost = 'localhost';
	$dbuser = 'u174398304_nxtinfra_p2p'; 
	$dbpass = 'Nxtinfra@123#'; 
	$dbname	= "u174398304_nxtinfra_p2p";
	
	//$con = mysqli_connect("localhost","contmdzp_awc","awc@123#","contmdzp_awc");
	
/*	$dbhost = 'localhost';
	$dbuser = 'syncorvc_escon'; 
	$dbpass = 'escon123'; 
	$dbname	= "syncorvc_escon";
*/

    //$link = mysql_connect($dbhost, $dbuser, $dbpass);
	$link = mysqli_connect($dbhost, $dbuser, $dbpass,$dbname);

    if(!$link) {
		die("Problem with connection");
	}
		
//	$backupfile_name	=	$_SESSION['backupfile_name'];
//    $backupdate     	=	$_SESSION['$backupdate'];
	
//Auto backup before Clear data	for Transaction
	$backupdate = date("Y-m-d");
	$backupfile_name = "backup";
	$bckp_flnm 		 = $backupfile_name.'-nxt-'.$backupdate;
	
    $selectdb 	=	mysqli_select_db($link,$dbname);
	echo mysqli_error($link);


//echo "<script>confirm('Are you sure You want to Take Backup!!!')</script>";

	backup_tables($dbhost,$dbuser,$dbpass,$dbname,$bckp_flnm);	


//echo '<script>alert("Backup Done....Press Enter!!!");window.location.href="dashboard.php?sub=list";</script>';
	
?>

