<?php

//LDAP TESTING
//https://gist.github.com/heiglandreas/5689592

//"CN=Athaangadmin,CN=Users,DC=Athaang,DC=local"

$link = ldap_connect('outlook.office365.com', 587 ); // Your domain or domain server

if(! $link) {
    // Could not connect to server - handle error appropriately
	echo "Could not connect to server";
}

ldap_set_option($link, LDAP_OPT_PROTOCOL_VERSION, 3); // Recommended for AD

// Now try to authenticate with credentials provided by user
if (! ldap_bind($link, 'workflow@highwayconcessions.com', 'highway@1234' )) {
    // Invalid credentials! Handle error appropriately
	echo "Invalid Credential..####1...";
}
else {
	
	echo "Bind Successfully..... ";

}

// Bind was successful - continue


	$bind_dn = " cn=read-only-admin,dc=example,dc=com";
	$bind_pass = "password";
	$ldap_con = ldap_connect("ldap.forumsys.com");
	
	ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION,3 );
	
	if( ldap_bind($ldap_con, $bind_dn, $bind_pass )){
		echo "Bind Successfully..... ";
	} else  {
		echo "Invalid User / Password or Other Errors...####2.. ";
	}
	 //exit();
	 
//LDAP TESTING


$link = ldap_connect('10.0.0.11',389 ); // Your domain or domain server
//$link = ldap_connect('CN=Athaangadmin,CN=Users,DC=Athaang,DC=local'); // Your domain or domain server
if(! $link) {
    // Could not connect to server - handle error appropriately
	echo "Could not connect to server";
}

ldap_set_option($link, LDAP_OPT_PROTOCOL_VERSION, 3); // Recommended for AD

// Now try to authenticate with credentials provided by user
if (! ldap_bind($link, 'athaangadmin@athaang.local', 'P@ssw00rd@121!!' )) {
    // Invalid credentials! Handle error appropriately
	echo "Invalid Credential...###3..";
}
else {
	
	echo "Bind Successfully..... ";

}

$username = 'athaangadmin@athaang.local';
	$password = 'P@ssw00rd@121!!';
	if (! ldap_bind($link, $username, $password )) {
		// Invalid credentials! Handle error appropriately
		echo "Invalid Credential...###4..";
	}
	else {
		
		echo "Bind Successfully.....OK ";

	}


//Athang
/* $link = ldap_connect('CN=Athaangadmin,CN=Users,DC=Athaang,DC=local'); // Your domain or domain server

if(! $link) {
    // Could not connect to server - handle error appropriately
	echo "Could not connect to server";
}

ldap_set_option($link, LDAP_OPT_PROTOCOL_VERSION, 3); // Recommended for AD

// Now try to authenticate with credentials provided by user
if (!ldap_bind($link, 'Athaangadmin', 'P@ssw00rd@121!!' )) {
    // Invalid credentials! Handle error appropriately
	echo "Invalid Credential.....";
}
else {
	
	echo "Bind Successfully..... ";

} */
 
//$ldap = ldap_connect("ldaps://11.22.33.44",636);
/* $ldap = ldap_connect('CN=Athaangadmin,CN=Users,DC=Athaang,DC=local'); // Your domain or domain server
ldap_set_option ($ldap, LDAP_OPT_REFERRALS, 0);
ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
$sr = ldap_search($ldap, "OU=User Accounts,DC=Domain1,DC=Athaang,DC=Local", "(|(sn=*))");
$username = "Athaangadmin";
$password = "P@ssw00rd@121!!";
$ds = ldap_bind($ldap, $username, $password );
if( $ds ){
    echo "logged in!";
}
else{
    echo "failed to log in!";
    exit;
}
  */
/* $domain = 'athaang.in/';
$username = 'Athaangadmin';
$password = 'P@ssw00rd@121!!';
//$ldapconfig['host'] = '10.10.10.11';
$ldapconfig['host'] = 'athaang.in';
$ldapconfig['port'] = 389;
$ldapconfig['basedn'] = 'dc=Athaang,dc=local';

$ds=ldap_connect($ldapconfig['host'], $ldapconfig['port']);
ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);

$dn="ou=Technology,".$ldapconfig['basedn'];
$bind=ldap_bind($ds, $username .'@' .$domain, $password);
$isITuser = ldap_search($bind,$dn,'(&(objectClass=User)(sAMAccountName=' . $username. '))');
if ($isITuser) {
    echo("Login correct");
} else {
    echo("Login incorrect");
}
 */
?>
