<?php

require_once "vendor/autoload.php";

$nugget = new \Activerules\Nugget\Nugget();

$shortopts  = "";
$shortopts .= "o:";  // Required - path to object definitions
$shortopts .= "p:";  // Required - path to property definitions
$shortopts .= "s:";  // Required - path for generated Open API schema objects
$shortopts .= "d:";  // Required - path for generated Open API schema w/ populated $refs

$options = getopt($shortopts);

if(empty($options['p']) || empty($options['o']) || empty($options['s'])  || empty($options['d'])) {
  exit;
}

// Objects and their related properties are defined here. 
define('OBJECTDIR', realpath($options['o']));

// Properties are stored here.
// They can be extended and reused across objects.
define('PROPERTYDIR', realpath($options['p']));

// A writeable directory for the OpenAPI schema w/o included $ref definitions
define('SCHEMADIR', realpath($options['s']));

// A writeable directory for the OpenAPI schema with populated local $ref
define('SCHEMADOCDIR', realpath($options['d']));

// Process the object directory
processObjectDirectory();

// Process the object directory
processSchemaReferences();



/**
 * Loop through created schema file and populate definitions based on #ref
 */
function processSchemaReferences()
{
  // Create a directory iterator for the defined objects directory
  $files = new \DirectoryIterator(SCHEMADIR);
  
  // Iterate through object definitions
  foreach ($files as $fileInfo) {
    // Make sure its a valid file
    if(validObjectFile($fileInfo)) {
      // Attempt creating a Schema object from the definition
      processReferences($fileInfo->getFilename());
    }
  }
}

/**
 * 
 * @param type $objectName
 */
function processReferences($schemaName) {
  
  // Get the full path to the JSON schema file
  $objFile = realpath (SCHEMADIR.'/'.$schemaName);

  // Read the file contents
  $objJSON = file_get_contents($objFile);

  // Convert JSON into a PHP object defining the schema 
  $schema = json_decode($objJSON, true);

  // If we have schema properties continue
  if(isset($schema['properties'])) {
    
      $refs = [];

      findRefs($schema['properties'], $refs);
      
      populateRefs($schema, $refs);

      writeSchema($schema, $schemaName, 'schemadoc');
  }
}

function populateRefs(& $schema, $refs){
  array_unique($refs);
  foreach($refs as $ref) {
    populateRef($schema, $ref);
  }
}

function populateRef(& $schema, $ref) {
  
  $refParts = explode('/', $ref);
  
  $type = array_shift($refParts);
  
  $refSchemaName = array_pop($refParts);

  if($type === '#') {
    $refSchema = hydrateLocalRef($refSchemaName);
    setRefSchema($schema, $refSchemaName, $refParts, $refSchema);
  }
}

function setRefSchema(& $schema, $refSchemaName, $refParts, $refSchema) {
  
  $a = &$schema;
  
  while( count($refParts) > 0 ){
      // get next first key
      $k = array_shift($refParts);

      // if $a isn't an array already, make it one
      if(!is_array($a)){
          $a = array();
      }

      // move the reference deeper
      $a = &$a[$k];
  }
  $a[$refSchemaName] = $refSchema;

}

function hydrateLocalRef($refSchemaName) {
  // Get the full path to the JSON schema file
  $objFile = realpath (SCHEMADIR.'/'.$refSchemaName.'.json');

  if($objFile) {
    // Read the file contents
    $objJSON = file_get_contents($objFile);

    // Convert JSON into a PHP object defining the schema parts
    $schemaDef = json_decode($objJSON);

    return $schemaDef;
  }
}

function findRefs($props, & $refs) {
  foreach($props as $key => $val) {
    
    if ($key === '$ref') {
      $refs[] = $val;
    } elseif(is_array($val)) {
      findRefs($val, $refs);
    }
  }
}


/**
 * Process the object definitions directory for defined schema
 */
function processObjectDirectory()
{
  // Create a directory iterator for the defined objects directory
  $files = new \DirectoryIterator(OBJECTDIR);
  
  // Iterate through object definitions
  foreach ($files as $fileInfo) {
    // Make sure its a valid file
    if(validObjectFile($fileInfo)) {
      // Attempt creating a Schema object from the definition
      processSchema($fileInfo->getFilename());
    }
  }
}

/**
 * 
 * @param type $objectName
 */
function processSchema($schemaName) {
  
  // Get the full path to the JSON schema file
  $objFile = realpath (OBJECTDIR.'/'.$schemaName);

  // Read the file contents
  $objJSON = file_get_contents($objFile);

  // Convert JSON into a PHP object defining the schema parts
  $schemaDef = json_decode($objJSON);
  
  mergeParentDef($schemaDef);

  // If we have schema properties continue
  if(isset($schemaDef->properties)) {
      $schema = hydrateSchema($schemaDef, $schemaName);
      writeSchema($schema, $schemaName);
  }
}

function mergeParentDef(& $schemaDef) {
  if(isset($schemaDef->extends)) {
    // Get the full path to the JSON schema file
    $parentFile = realpath (OBJECTDIR.'/'.$schemaDef->extends);

    if($parentFile) {
      // Read the file contents
      $parentJSON = file_get_contents($parentFile);

      // Convert JSON into a PHP object defining the schema parts
      $parentDef = json_decode($parentJSON);

      if($parentDef) {
        mergeProps($parentDef, $schemaDef);
        mergeRequired($parentDef, $schemaDef);
      }
    }
  }
}

function mergeRequired($parent, & $child) {
  $child->required = array_merge((array)$parent->properties, (array)$child->properties);
}

function mergeProps($parent, & $child) {
  $child->properties = array_merge($parent->properties, $child->properties);
}

function writeSchema($schema, $schemaName, $dir='schema') {
  // JSON encode the obj to get a valid spec JSON
  $spec = json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

  // Create a writeable file pointer to the OpenAPI schema location
  switch($dir) {
    case 'schemadoc':
      $path = SCHEMADOCDIR.'/'.$schemaName;
      break;
      
    default:
      $path = SCHEMADIR.'/'.$schemaName;
      break;
  }
  $fp = fopen($path, 'w');

  // Write the spec to the file pointer
  fwrite($fp, $spec);

  // Close the file pointer
  fclose($fp);
}

/**
 * 
 * @param type $schemaDef
 */
function hydrateSchema($schemaDef, $schemaName) {
    
    // Set type as object if not defined otherwise
    if(!isset($schemaDef->type)) {
        $schemaDef->type = 'object';
    }

    // Array to hold definitions created by all this object properties
    $schemaDef->definitions = array();

    // Loop through top level properties and populate, all definitions should be at top level.
    populateProperties($schemaDef);
    
    if(count($schemaDef->definitions) === 0) {
      unset($schemaDef->definitions);
    }
    
    return $schemaDef;
}

/**
 * 
 * @param type $schemaDef
 */
function populateProperties(& $schemaDef) {
  
    $props = [];
  
    // Loop through the defined properties array and load the definition for each one
    foreach($schemaDef->properties as $property) {

        // Get the full path to the JSON property JSON file
        $propertyFile = realpath (PROPERTYDIR.'/'.$property.'.json');

        if($propertyFile) {
            $props[$property] = hydrateProperty($propertyFile, $schemaDef->definitions);
        }
    }
    
    $schemaDef->properties = $props;
}

/**
 * 
 * @param type $filePath
 */
function hydrateProperty($propertyFile, & $defs) {
  
  // Read the file into a PHP string
  $propertyDef = file_get_contents($propertyFile);

  // Use the serialized JSON string as a JSON object
  $propObj = json_decode($propertyDef);

  // Use this definition as the value for the OpenAPI property
  return $propObj;
}

/**
 * 
 * @param type $propObj
 * @param type $defs
 */
function populateObjectProperty(& $propObj, & $defs) {
    // Loop through looking for $ref's and items
    foreach($propObj as $prop => $def) {
       resolvePropertyReference($prop, $def, $defs );
    }
}

/**
 * 
 * @param type $prop
 * @param type $def
 * @param type $defs
 */
function resolvePropertyReference($prop, $def, & $defs ) {
  switch($prop) {
      case '$ref':
          loadRef($def, $defs);
          break;
      case 'items':
          checkThenProcessItem($def, $defs);
          break;
      default:
          break;
  }
}

/**
 * 
 * @param type $def
 * @param type $defs
 */
function checkThenProcessItem($def, & $defs){
    if(gettype($def) === 'object') {
        processItem($def, $defs);
    }
}

/**
 * 
 * @param type $def
 * @param type $defs
 */
function processItem($def, & $defs) {
  // Loop through ref object
  foreach($def as $refKey => $refString) {
      // Is this our reference?
      if($refKey === '$ref') {
          // Load the reference
          loadRef($refString, $defs);
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
       
        populateRef($name, $defs);
    }
}

/**
 * 
 * @param type $fileInfo
 */
function validObjectFile($fileInfo) {

  $name = $fileInfo->getFilename();
  
  $isValid = false;
  
  if (!$fileInfo->isDot() 
          && !$fileInfo->isDir()
          && substr($name,0,1) != '.') {
    $isValid = true;   
  }
  
  return $isValid;
}
