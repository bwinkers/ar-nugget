<?php

// include your composer dependencies
require_once 'vendor/autoload.php';


$shortopts  = "";
$shortopts .= "s:";  // Required - Google Spreadsheet ID
$shortopts .= "p:";  // Required - path to property definitions directory
$shortopts .= "c:";  // Required - path to credentials file for Google Drive API

$options = getopt($shortopts);

// Google Spreadsheet ID 
$spreadsheetID = $options['s'];

// Properties are stored here.
// They can be extended and reused across objects.
$propertyDir = realpath($options['p']);
// Create properties directory if needed
if (false === realpath($propertyDir)) {
    mkdir($propertyDir);
    $propertyDir = realpath($propertyDir);
}

define('SCOPES', implode(' ', array(
  Google_Service_Sheets::SPREADSHEETS_READONLY)
));

putenv('GOOGLE_APPLICATION_CREDENTIALS='.realpath($options['c']));

$client = new Google_Client();
$client->setScopes(SCOPES);
$client->useApplicationDefaultCredentials();

$service = new Google_Service_Sheets($client);

$range = 'IncomingProperties!A1:C';
$response = $service->spreadsheets_values->get($spreadsheetID, $range);
$values = $response->getValues();

var_dump($values);
