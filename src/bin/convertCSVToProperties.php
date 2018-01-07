<?php

// Incoming property definitions are found here
$propsDir = realpath('./propsIn');

// Properties are stored here.
// They can be extended and reused across objects.
$propertyDir = realpath('./properties');
if (false === realpath($propertyDir)) {
    mkdir($propertyDir);
    $propertyDir = realpath($propertyDir);
}

// Create a directory iterator for the defined incoming properties directory
$dir = new DirectoryIterator($propsDir);

// Iterate through files in the incoming properties directory
foreach ($dir as $fileInfo) {

    // Property objects
    $props = array();

    // definition objects
    $defs = array();

    // Skip '..' etc
    if (!$fileInfo->isDot()) {
        
        // Use Object name as Schema name
        $name = $fileInfo->getFilename();

        // Get the full path to the JSON schema file
        $file = realpath ($objectDir.'/'.$name);

        
        // JSON encode the obj to get a valid spec JSON
        $spec = json_encode($obj, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        
        // Create a writeable file pointer for the property definition
        $fp = fopen($propertyDir.'/'.$name, 'w');

        // Write the spec to the file pointer
        fwrite($fp, $spec);

        // Close the file pointer
        fclose($fp);
    }
}

function splitName($name) {
    $re = '/(?#! splitCamelCase Rev:20140412)
    # Split camelCase "words". Two global alternatives. Either g1of2:
      (?<=[a-z])      # Position is after a lowercase,
      (?=[A-Z])       # and before an uppercase letter.
    | (?<=[A-Z])      # Or g2of2; Position is after uppercase,
      (?=[A-Z][a-z])  # and before upper-then-lower case.
    /x';
    $a = preg_split($re, $ccWord);
    $count = count($a);
    for ($i = 0; $i < $count; ++$i) {
        printf("Word %d of %d = \"%s\"\n",
            $i + 1, $count, $a[$i]);
    }
}

