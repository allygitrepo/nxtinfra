<script type="text/javascript" src="js/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="js/jquery.easyui.min.js"></script>

<script src="jui/js/jquery-ui-1.9.2.min.js"></script>
<script src="1314/jquery.min.js"></script>

<?php
	session_start();
?>

<?php
function backup_tables($host,$user,$pass,$name,$bckp_flnm,$tables = '*') {
    $link = mysqli_connect($host, $user, $pass, $name);
   // mysql_select_db($name,$link);
    mysqli_query($link, "SET NAMES 'utf8'");
	

    $i=0;
    //get all of the tables 
    if($tables == '*'){
        $tables = array();
        $result = mysqli_query($link, 'SHOW TABLES');
		
echo mysqli_error($link);
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

	$flnm=$bckp_flnm.'.sql';
    $handle=fopen($flnm,'w+');

		$return.= 'create database IF NOT EXISTS '.$name.';';
		$return.= "\n\n";
        $return.= 'use '.$name.';';
		$return.= "\n\n";

    fwrite($handle,$return);

    foreach($tables as $table){
        $result = mysqli_query($link,'SELECT * FROM '.$table);
        $num_fields = mysqli_num_fields($result);
        $return = 'DROP TABLE '.$table.';';

		fwrite($handle,$return);
        $row2 = mysqli_fetch_row(mysqli_query($link,'SHOW CREATE TABLE '.$table));
        $return = "\n\n".$row2[1].";\n\n";
		fwrite($handle,$return);

        for ($i = 0; $i < $num_fields; $i++) {
            while($row = mysqli_fetch_row($result))
            {
                $return = 'INSERT INTO '.$table.' VALUES(';
                for($j=0; $j<$num_fields; $j++) 
                {
                    $row[$j] = addslashes($row[$j]);
                    $row[$j] = str_replace("\n","\\n",$row[$j]);
                    if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
                    if ($j<($num_fields-1)) { $return.= ','; }
                }
                $return.= ");\n";
				
				fwrite($handle,$return);

            }
        }
        $return.="\n\n\n";
    }

	//echo $return;

      
    fclose($handle);
	
//	header("Location: $flnm");
//	header("Content-disposition: attachment; filename = $flnm");
	
	$new_filename = 'D:/backup/'.$flnm;								 
	$old_filename = $flnm;					 
	rename($old_filename, $new_filename);

	
//		include("../header.php"); 
		
		include("../baseurl.php");
		$baseurl1 = $baseurl. 'dashboard.php?sub=list';
//		echo $baseurl1;
		echo "<script>alert('Backup process done...'); window.location.href='$baseurl1';</script>";
//exit("STOP RAVINDRA");		
}
?>

<?php

	$dbhost = 'localhost';
	$dbuser = 'root'; 
	$dbpass = ''; 
	$dbname	= "hc_workflow";
	
/*	$dbhost = 'localhost';
	$dbuser = 'syncorvc_escon'; 
	$dbpass = 'escon123'; 
	$dbname	= "syncorvc_escon";
*/

    $link = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);

    if(!$link) {
		die("Problem with connection");
	}
		
	$backupfile_name	=	$_SESSION['backupfile_name'];
    $backupdate     	=	$_SESSION['$backupdate'];
	
//Auto backup before Clear data	for Transaction
	$bckp_flnm 			=	$backupfile_name.'-HC-'.$backupdate;
	
    //$selectdb 	=	mysqli_select_db($dbname,$link);
	echo mysqli_error($link);
//echo "<script>confirm('Are you sure You want to Take Backup!!!')</script>";

	backup_tables($dbhost,$dbuser,$dbpass,$dbname,$bckp_flnm);	


//echo '<script>alert("Backup Done....Press Enter!!!");window.location.href="dashboard.php?sub=list";</script>';
	
?>

