<?php
//$baseurl = "http://114.143.234.194/workflow2020/";    //dev url

$baseurl = "http://localhost/p2p_workflow/";    //dev url
$baseurl = "http://sekura.xyz/";    //dev url

//$ipc = gethostbyname('www.hcone.co.in');
//echo $ip. "<br>";
//$ipa = gethostbyname('114.143.234.194');
//echo $ip. "<br>";
if ($ipc == '114.143.234.194'){
	$baseurl = "http://localhost/p2p_workflow/";    //dev url	
}

//$alink = $_SERVER[HTTP_HOST];

//$baseurl = "https://".$alink."/workflow2020/";
?>