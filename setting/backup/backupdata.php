<script type="text/javascript" src="js/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="js/jquery.easyui.min.js"></script>

<script src="jui/js/jquery-ui-1.9.2.min.js"></script>
<script src="1314/jquery.min.js"></script>

<?php
	session_start();
?>

<?php
function backup_tables($host,$user,$pass,$name,$bckp_flnm,$tables = '*') {
    $link = mysql_connect($host,$user,$pass);
    mysql_select_db($name,$link);
    mysql_query("SET NAMES 'utf8'");

    $i=0;
    //get all of the tables
    if($tables == '*'){
        $tables = array();
        $result = mysql_query('SHOW TABLES');
        while($row = mysql_fetch_row($result))
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
        $result = mysql_query('SELECT * FROM '.$table);
        $num_fields = mysql_num_fields($result);
        $return.= 'DROP TABLE '.$table.';';
        $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
        $return.= "\n\n".$row2[1].";\n\n";

        for ($i = 0; $i < $num_fields; $i++) {
            while($row = mysql_fetch_row($result))
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
	
	$flnm=$bckp_flnm.'.csv';


    $handle=fopen($flnm,'a+');
      
    fwrite($handle,$return);

    fclose($handle);
	
//	header("Location: $flnm");
//	header("Content-disposition: attachment; filename = $flnm");
	
	$new_filename = 'E:/backup/'.$flnm;								 
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

    $link = mysql_connect($dbhost, $dbuser, $dbpass);

    if(!$link) {
		die("Problem with connection");
	}
		
	$backupfile_name	=	$_SESSION['backupfile_name'];
    $backupdate     	=	$_SESSION['$backupdate'];
	
//Auto backup before Clear data	for Transaction
	$bckp_flnm 			=	$backupfile_name.'-HC-'.$backupdate;
	
    $selectdb 	=	mysql_select_db($dbname,$link);
	echo mysql_error();


//echo "<script>confirm('Are you sure You want to Take Backup!!!')</script>";

	backup_tables($dbhost,$dbuser,$dbpass,$dbname,$bckp_flnm);	


//echo '<script>alert("Backup Done....Press Enter!!!");window.location.href="dashboard.php?sub=list";</script>';
	
?>

