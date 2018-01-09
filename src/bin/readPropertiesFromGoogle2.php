<?php

// include your composer dependencies
require_once 'vendor/autoload.php';


$shortopts  = "";
$shortopts .= "i:";  // Required - path to incoming duplicate properties directory
$shortopts .= "p:";  // Required - path to property definitions directory
$shortopts .= "c:";  // Required - path to credentials file for Google Drive API

$options = getopt($shortopts);

// Incoming errors and duplicates get written here. 
$incomingDir = realpath($options['i']);

// Properties are stored here.
// They can be extended and reused across objects.
$propertyDir = realpath($options['p']);
// Create properties directory if needed
if (false === realpath($propertyDir)) {
    mkdir($propertyDir);
    $propertyDir = realpath($propertyDir);
}


define('APPLICATION_NAME', 'Google Sheets API PHP Quickstart');
define('CREDENTIALS_PATH', './credentials/sheets.googleapis.com.json');
define('CLIENT_SECRET_PATH', './credentials/client_secret.json');
// If modifying these scopes, delete your previously saved credentials
// at ~/.credentials/sheets.googleapis.com-php-quickstart.json
define('SCOPES', implode(' ', array(
  Google_Service_Sheets::SPREADSHEETS_READONLY)
));

if (php_sapi_name() != 'cli') {
  throw new Exception('This application must be run on the command line.');
}

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getClient() {
  $client = new Google_Client();
  $client->setApplicationName(APPLICATION_NAME);
  $client->setScopes(SCOPES);
  $client->setAuthConfig(CLIENT_SECRET_PATH);
  $client->setAccessType('offline');
  
  //$client->setRedirectUri('http://izzup.com/oauth2callback.php');


  // Load previously authorized credentials from a file.
  $credentialsPath = expandHomeDirectory(CREDENTIALS_PATH);
  if (file_exists($credentialsPath)) {
    $accessToken = json_decode(file_get_contents($credentialsPath), true);
  } else {
    // Request authorization from the user.
    $authUrl = $client->createAuthUrl();
    printf("Open the following link in your browser:\n%s\n", $authUrl);
    print 'Enter verification code: ';
    $authCode = trim(fgets(STDIN));

    // Exchange authorization code for an access token.
    $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

    // Store the credentials to disk.
    if(!file_exists(dirname($credentialsPath))) {
      mkdir(dirname($credentialsPath), 0700, true);
    }
    file_put_contents($credentialsPath, json_encode($accessToken));
    printf("Credentials saved to %s\n", $credentialsPath);
  }
  $client->setAccessToken($accessToken);

  // Refresh the token if it's expired.
  if ($client->isAccessTokenExpired()) {
    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
    file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
  }
  return $client;
}

/**
 * Expands the home directory alias '~' to the full path.
 * @param string $path the path to expand.
 * @return string the expanded path.
 */
function expandHomeDirectory($path) {
  $homeDirectory = getenv('HOME');
  if (empty($homeDirectory)) {
    $homeDirectory = getenv('HOMEDRIVE') . getenv('HOMEPATH');
  }
  return str_replace('~', realpath($homeDirectory), $path);
}

// Get the API client and construct the service object.
$client = getClient();
$service = new Google_Service_Sheets($client);

var_dump($service);

// Prints the names and majors of students in a sample spreadsheet:
// https://docs.google.com/spreadsheets/d/17Eh19JUUKSuk9SLOv-ydiEBjG0GUv_CzA87PuUZIakw/edit
$spreadsheetId = '1kcclfnDGn94cVt9UzHCSQbF1LUyhpBEIiHqHuJ1CfC8';
$range = 'Properties!A1:c1000';
$response = $service->spreadsheets_values->get($spreadsheetId, $range);
$values = $response->getValues();

if (count($values) == 0) {
  print "No data found.\n";
} else {
  print "property, type, description:\n";
  foreach ($values as $row) {
    // Print columns A and E, which correspond to indices 0 and 4.
    printf("%s, %s, %s\n", $row[0], $row[1], $row[2]);
  }
}


