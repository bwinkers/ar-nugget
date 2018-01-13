<?php

$shortopts  = "";
$shortopts .= "o:";  // Required - path to object definitions
$shortopts .= "p:";  // Required - path to property definitions
$shortopts .= "s:";  // Required - path for generated Open API schema objects

$options = getopt($shortopts);

if(empty($options['p']) || empty($options['o']) || empty($options['s'])) {
  exit;
}

// Objects and their related properties are defined here. 
define('OBJECTDIR', realpath($options['o']));

// Properties are stored here.
// They can be extended and reused across objects.
define('PROPERTYDIR', realpath($options['p']));

// A writeable directory for the OpenAPI schema definitions
define('SCHEMADIR', realpath($options['s']));

// Create a directory iterator for the defined objects directory
$dir = new DirectoryIterator(OBJECTDIR);

// Iterate through object definitions
foreach ($dir as $fileInfo) {

    // Property objects
    $props = array();

    // definition objects
    $defs = array();
    
    // Get the file name
    $name = $fileInfo->getFilename();

    // Skip '.', '..' and `.*` files
    if (!$fileInfo->isDot() && substr($name,0,1) != '.') {

        // Get the full path to the JSON schema file
        $file = realpath (OBJECTDIR.'/'.$name);

        // Read the file into a PHP string
        $def = file_get_contents($file);

        // Decode JSON into a PHP object 
        $obj = json_decode($def);

        // Set as object if not defined otherwise
        if(isset($object->type)) {
            $obj->type = 'object';
        }

        if(isset($obj->properties)) {
 
            // Loop through the defined properties array and load the definition for each one
            foreach($obj->properties as $property) {

                // Get the full path to the JSON property JSON file
                $propertyFile = realpath (PROPERTYDIR.'/'.$property.'.json');

                if($propertyFile) {
                    // Read the file into a PHP string
                    $propertyDef = file_get_contents($propertyFile);

                    // Use the serialized JSON string as a JSON object
                    $propObj = json_decode($propertyDef);

                    // Check for references and expand or hydrate as needed
                    populateRefs($propObj, $defs);

                    // Use this definition as the value for the OpenAPI property
                    $props[$property] = $propObj;
                }
            }

            // Overwrite the array of property strings with an array of property objects
            $obj->properties = $props;

            // Were there any definitions found?
            if(!empty($defs)) {
                 // Set the object definitions
                $obj->definitions = $defs;
            }
        }
        
        // JSON encode the obj to get a valid spec JSON
        $spec = json_encode($obj, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        
        // Create a writeable file pointer to the OpenAPI schema location
        $fp = fopen(SCHEMADIR.'/'.$name, 'w');

        // Write the spec to the file pointer
        fwrite($fp, $spec);

        // Close the file pointer
        fclose($fp);
    }
}

/**
 * Populate references
 * 
 * @param type $propObj
 * @param type $defs
 */
function populateRefs($propObj, & $defs) {
 
    $propType = gettype($propObj);
    
    if($propType === 'object' || $propType === 'array') {
        
        // Loop through looking for $ref's
        foreach($propObj as $prop => $def) {

            // We are only looking for $ref properties
            switch($prop) {

                case '$ref':
                    loadRef($def, $defs);
                    break;

                // Check if items are an object reference
                case 'items':

                    // The $def has to be an object for this to be an array of $ref
                    $refType = gettype($def);

                    if($refType === 'object') {
                        // Loop through ref object
                        foreach($def as $refKey => $refString) {

                            // Is this our reference?
                            if($refKey === '$ref') {
                                // Load the reference
                                loadRef($refString, $defs);
                            }
                        }
                    }
                    break;

                default:
                    break;
            }
        }
    }
}

/**
 * Load a reference object
 * 
 * @param type $refString
 * @param type $defs
 */
function loadRef($refString, & $defs) {
   
    // If the first char is a '#' hydrate the referenced object into definitions
    if(substr($refString, 0, 13) === '#/definitions') {
        
        // We only want the final definition name from the path
        $parts = explode('/', $refString);

        $name = array_pop($parts);

        if(!array_key_exists($name, $defs)) {
            // Get the full path to the JSON schema file
            $file = realpath (OBJECTDIR.'/'.ucfirst($name).'.json');

            if($file) {

                $props = null;

                // Read the file into a PHP string
                $def = file_get_contents($file);

                // Create a PHP object from decoded def
                $obj = json_decode($def);

                // Set the type to `object`
                $obj->type = 'object';

                // Array to hold defiend properties
                $props = [];

                // Loop through the defined properties array and load the definition for each one
                foreach($obj->properties as $property) {

                    // Get the full path to the JSON property JSON file
                    $propertyFile = realpath (PROPERTYDIR.'/'.$property.'.json');

                    if($propertyFile) {
                        // Read the file into a PHP string
                        $propertyDef = file_get_contents($propertyFile);

                        // Use the serialized JSON string as a JSON object
                        $propObj = json_decode($propertyDef);

                        // Check the property for a $ref to another object.
                        // If a $ref is found we need to add  it's definition in this object.
                        populateRefs($propObj, $defs);

                        // Use this definition as the value for the OpenAPI property
                        $props[$property] = $propObj;
                    }
                }

                // Overwrite the array of property strings with an array of property objects
                $obj->properties = $props;

                $defs[$name] = $obj;           
            }
        }
    }
}