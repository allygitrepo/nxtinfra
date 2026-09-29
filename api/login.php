<?php 
 //"Hello World....";
 require_once 'DbConnect.php';
 $response = array();
 
 if(isset($_GET['apicall'])){
 
//echo $_POST['userid']. '<<>>' . $_POST['password'];
 switch($_GET['apicall']){
 
 case 'login':
 
 if(isTheseParametersAvailable(array('userid', 'password'))){
 
	$userid 		= $_POST['userid'];
	$password 		= md5($_POST['password']); 
	//$password 		= $_POST['password']; 
	$passwd   		= $_POST['password'];

//Not allow for Admin 
	if($userid=='admin' || $userid=='Admin'){
		$response['status'] = '0'; 
		$response['message'] = 'Invalid #2 userid or password';
		echo json_encode($response);
		exit();
	}
	
	

//Production	 
	 	
			$password = md5($_POST['password']);
			$sql = " select * from sma_user where userid = '$userid' and ( password = '$password' || ps = '$passwd' )";	
			$qry = mysqli_query($con, $sql);
			$rowcount = mysqli_num_rows($qry);
		//echo $sql. "<BR>";	
			if($rowcount==0){	
				$response['status'] = '0'; 
				$response['message'] = 'Invalid userid or password';
				echo json_encode($response);

				//break;
				exit();
			}
			$bind 		= 'Y';
			
//Production
        
        $token           =rand(15,100000000000000);
        
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
		 $response['message'] = 'Invalid###1 userid or password';
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
 