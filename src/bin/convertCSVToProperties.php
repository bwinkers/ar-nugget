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

    // Get the file name
    $name = $fileInfo->getFilename();

    // Skip '.', '..' and `.*` files
    if (!$fileInfo->isDot() && substr($name,0,1) != '.') {

        
        var_dump($fileInfo->getPathname());
        
        $fullPath = $fileInfo->getPathname();
        
        $row = 1;
        if (($handle = fopen($fullPath, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 5000, ",")) !== FALSE) {
                $num = count($data);
                echo "<p> $num fields in line $row: <br /></p>\n";
                $row++;
                for ($c=0; $c < $num; $c++) {
                    echo $data[$c] . "<br />\n";
                }
            }
            fclose($handle);
        }

        

        
        // Write the spec to the file pointer
        //fwrite($fp, $spec);

        // Close the file pointer
        //fclose($fp);
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

