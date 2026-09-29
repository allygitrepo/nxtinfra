<?php 
 //Echo "Hello World....";
 require_once 'DbConnect.php';
 $response = array();
 
 if(isset($_GET['apicall'])){
 
// echo $_POST['userid']. '<<>>' . $_POST['password'];
 switch($_GET['apicall']){
 
 case 'login':
 
 if(isTheseParametersAvailable(array('userid', 'password'))){
 
	$userid 		= $_POST['userid'];
	$password 		= md5($_POST['password']); 
	// $password 	= $_POST['password']; 
	$passwd   		= $_POST['password'];

	$token           =rand(15,100000000000000);
	// $userid = $_GET['userid'];
	// $password = md5($_GET['password']); 
	 
	//$stmt = $con->prepare("SELECT id, userid, userid, email FROM sma_user WHERE userid = '$userid' AND (password = '$password' OR password = '$passwd' ) ");
		$link = ldap_connect('10.0.0.11',389 ); // Your domain or domain server
		if(! $link){
			// Could not connect to server - handle error appropriately
			echo "Could not connect to server";
		}

		ldap_set_option($link, LDAP_OPT_PROTOCOL_VERSION, 3); // Recommended for AD

	//Commented for testing
	/*  	if(empty($password)){
			$msg = "Invalid Credential...###4..";
			$rowcount=0;	
			echo '<script>window.location.href="index.php?rowcount=0";</script>';
			exit();
		}	 */
	//Commented for testing	
		if (!ldap_bind($link, $userid, $password )) {
			// Invalid credentials! Handle error appropriately
			$sql = " select * from sma_user where userid = '$userid' and password = '$password' ";	
			$qry = mysqli_query($con, $sql);
			$rowcount = mysqli_num_rows($qry);
			if($rowcount==0){	
				$response['status'] = '0'; 
				$response['message'] = 'Invalid userid or password';
				echo json_encode($response);

				//break;
				exit();
			}
			$bind 		= 'Y';
			
		}
		else {
			
	//		echo "Bind Successfully.....OK ";
			$rowcount 	= 1;
			$bind 		= 'Y';
			
		}
		
		/* 	$sql = " select * from sma_user where userid = '$userid' and password = '$password' ";
			$qry = mysqli_query($con, $sql);
			$rowcount = mysqli_num_rows($qry);
			if($rowcount==0){	
				$response['status'] = '0'; 
				$response['message'] = 'Invalid userid or password';
				echo json_encode($response);

				//break;
				exit();
			}
			$bind 		= 'Y';
		 */
	 $stmt = $con->prepare("SELECT id, userid, username, email FROM sma_user WHERE userid = '$userid' ");
	 //$stmt->bind_param("ss",$userid, $password);
	 $stmt->execute();
	 
	 $stmt->store_result();
	 
	 if($stmt->num_rows > 0){
	 
	 $stmt->bind_result($id, $userid, $username, $email);
	 $stmt->fetch();
	 
	 $user = array(
	 'id'=>$id, 
	 'userid'=>$userid, 
	 'username'=>$username, 
	 'email'=>$email
	 );
	 
	 //print_r($user);
	 
		 $response['status'] = '1'; 
		 $response['message'] = 'Login successfull'; 
		 $response['token'] = $token; 
		 $response['user'] = $user; 
	 }else{
		 $response['status'] = '0'; 
		 $response['message'] = 'Invalid userid or password';
	 }
 }
 break; 
 
 default: 
	 $response['status'] = '0'; 
	 $response['message'] = 'Invalid Operation Called';
 }
 
 }
 else{
	 $response['status'] = '0'; 
	 $response['message'] = 'Invalid API Call';
 }

//print_r($response);
 
echo json_encode($response);
 
 
 function isTheseParametersAvailable($params){
 
	 foreach($params as $param){

		 if(!isset($_POST[$param])){
			//if(!isset($_GET[$param])){
			return false; 
		 }
		 
	 }
	 return true; 
 }
 