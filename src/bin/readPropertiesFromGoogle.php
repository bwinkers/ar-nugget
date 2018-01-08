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

putenv('GOOGLE_APPLICATION_CREDENTIALS='.$options['c']);
$client = new Google_Client;
$client->useApplicationDefaultCredentials();
 
$client->setApplicationName("Nugget - Property Definitions Reader");
$client->setScopes(['https://www.googleapis.com/auth/drive','https://spreadsheets.google.com/feeds']);
 
if ($client->isAccessTokenExpired()) {
    $client->refreshTokenWithAssertion();
}
 
$service = new Google_Service_Sheets($client);
var_dump($service);

// Prints the names and majors of students in a sample spreadsheet:
// https://docs.google.com/spreadsheets/d/17Eh19JUUKSuk9SLOv-ydiEBjG0GUv_CzA87PuUZIakw/edit
$spreadsheetId = '17Eh19JUUKSuk9SLOv-ydiEBjG0GUv_CzA87PuUZIakw';
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


