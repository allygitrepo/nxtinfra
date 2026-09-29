<?php
require_once __DIR__ . '/google-api-client/vendor/autoload.php';

if(!isset($_SESSION))
{
    session_start();
}

//date_default_timezone_set('America/Los_Angeles');

$APP_REDIRECT_URI = 'https://athaang.in/p2p2023/p2p_emails/index.php';
$REDIRECT_URI = 'https://athaang.in/p2p2023/p2p_emails/get_save_emails.php';
//$KEY_LOCATION = __DIR__ . '/client_secret.json';
$TOKEN_FILE   = "token.txt";

//$CODE = '';

$SCOPES = array(
    Google_Service_Gmail::MAIL_GOOGLE_COM,
    'email',
    'profile'
);

$client = new Google_Client();
$client->setApplicationName("P2P Emails");
//$client->setAuthConfig($KEY_LOCATION);

$secretsFile = __DIR__ . '/google-secrets.php';
$googleSecrets = file_exists($secretsFile) ? require $secretsFile : [];

$clientId     = $googleSecrets['client_id'] ?? getenv('GOOGLE_CLIENT_ID') ?: 'YOUR_GOOGLE_CLIENT_ID';
$clientSecret = $googleSecrets['client_secret'] ?? getenv('GOOGLE_CLIENT_SECRET') ?: 'YOUR_GOOGLE_CLIENT_SECRET';
$developerKey = $googleSecrets['developer_key'] ?? getenv('GOOGLE_DEVELOPER_KEY') ?: 'YOUR_GOOGLE_DEVELOPER_KEY';

$client->setClientId($clientId);
$client->setClientSecret($clientSecret);
$client->setDeveloperKey($developerKey);

// Incremental authorization
//$client->setIncludeGrantedScopes(true);

// Allow access to Google API when the user is not present.
$client->setAccessType('offline');
$client->setPrompt('consent');
$client->setRedirectUri($REDIRECT_URI);
$client->setScopes($SCOPES);

if (isset($_GET['code']) && !empty($_GET['code'])) {
    try {
        // Exchange the one-time authorization code for an access token
        $accessToken = $client->fetchAccessTokenWithAuthCode($_GET['code']);

        // Save the access token and refresh token in local filesystem
        //file_put_contents($TOKEN_FILE, json_encode($accessToken));

        $_SESSION['accessToken'] = $accessToken;		  
        //header('Location: ' . filter_var($REDIRECT_URI, FILTER_SANITIZE_URL));
        header('Location: ' . filter_var($APP_REDIRECT_URI, FILTER_SANITIZE_URL));
        exit();
    }
    catch (\Google_Service_Exception $e) {
        print_r($e);
        //throw new Exception($e->getMessage());
    }
}

/*if (!isset($_SESSION['accessToken'])) {

//$token = @file_get_contents($TOKEN_FILE);

if ($token == null) {

// Generate a URL to request access from Google's OAuth 2.0 server:
$authUrl = $client->createAuthUrl();

// Redirect the user to Google's OAuth server
header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
exit();

} else {

$_SESSION['accessToken'] = json_decode($token, true);

}
}*/

if (!isset($_SESSION['accessToken'])) {

    // Generate a URL to request access from Google's OAuth 2.0 server:
    $authUrl = $client->createAuthUrl();

    // Redirect the user to Google's OAuth server
    //header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
    //exit();
    
    echo $authUrl;  //filter_var($authUrl, FILTER_SANITIZE_URL);
    exit();

} else {

    $client->setAccessToken($_SESSION['accessToken']);
    $accessToken = $_SESSION['accessToken'];

    //var_dump($token);
    $_SESSION['tokenOnly'] = $accessToken['access_token'];
}


?>