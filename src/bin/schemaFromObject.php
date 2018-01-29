<?php

require_once "vendor/autoload.php";

$shortopts = "";
$shortopts .= "o:";  // Required - path to object definitions
$shortopts .= "p:";  // Required - path to property definitions
$shortopts .= "s:";  // Required - path for generated Open API schema objects

$options = getopt($shortopts);

if (empty($options['p']) || empty($options['o']) || empty($options['s'])) {
    exit;
}

$nugget = new \Activerules\Nugget\Nugget();

// The directory with the core Open API schema
$schemaDir = realpath($options['s']);

// Object path
$objectDir = realpath($options['o']);

// Properties Dir
$propertiesDir = realpath($options['p']);

// Process the object directory
processObjectDirectory($objectDir, $schemaDir, $propertiesDir);


/**
 * Process the object definitions directory for defined schema
 * @param string $objectDir
 * @param string $schemaDir
 */
function processObjectDirectory($objectDir, $schemaDir, $propertiesDir)
{
  $nugget = new \Activerules\Nugget\Nugget();
    // Create a directory iterator for the defined objects directory
    $files = new \DirectoryIterator($objectDir);

    // Iterate through object definitions
    foreach ($files as $fileInfo) {
        // Make sure its a valid file
        if ($nugget->realDirFile($fileInfo)) {
            // Attempt creating a Schema object from the definition
            processSchema($objectDir, $propertiesDir, $fileInfo->getFilename(), $schemaDir);
        }
    }
}

/**
 *
 * @param object $schema
 * @param array $refs
 */
function populateRefs(& $schema, $refs)
{
    array_unique($refs);
    foreach ($refs as $ref) {
        populateRef($schema, $ref);
    }
}

/**
 *
 * @param object $schema
 * @param string $ref
 */
function populateRef(& $schema, $ref)
{
    $refParts = explode('/', $ref);

    $type = array_shift($refParts);

    $refSchemaName = array_pop($refParts);

    if ($type === '#') {
        $refSchema = hydrateLocalRef($refSchemaName);
        setRefSchema($schema, $refSchemaName, $refParts, $refSchema);
    }
}

/**
 *
 * @param object $schema
 * @param string $refSchemaName
 * @param array $refParts
 * @param object $refSchema
 */
function setRefSchema(& $schema, $refSchemaName, $refParts, $refSchema)
{
    $schemaRef = &$schema;

    while (count($refParts) > 0) {
        // get next first key
        $index = array_shift($refParts);

        // if $schemaRef isn't an array already, make it one
        if (!is_array($schemaRef)) {
            $schemaRef = array();
        }

        // move the reference deeper
        $schemaRef = &$schemaRef[$index];
    }
    $schemaRef[$refSchemaName] = $refSchema;
}
/**
 *
 * @param string $refSchemaName
 * @return object
 */
function hydrateLocalRef($refSchemaName)
{
    // Get the full path to the JSON schema file
    $objFile = realpath(SCHEMADIR . '/' . $refSchemaName . '.json');

    if ($objFile) {
        // Read the file contents
        $objJSON = file_get_contents($objFile);

        // Convert JSON into a PHP object defining the schema parts
        $schemaDef = json_decode($objJSON);

        return $schemaDef;
    }
}

/**
 *
 * @param array $props
 * @param array $refs
 */
function findRefs($props, & $refs)
{
    foreach ($props as $key => $val) {
        if ($key === '$ref') {
            $refs[] = $val;
        } elseif (is_array($val)) {
            findRefs($val, $refs);
        }
    }
}



/**
 *
 * @param string $objectName
 */
function processSchema($objectDir, $propertiesDir, $schemaName, $schemaDir)
{

  // Get the full path to the JSON schema file
    $objFile = realpath($objectDir . '/' . $schemaName);

    // Read the file contents
    $objJSON = file_get_contents($objFile);

    // Convert JSON into a PHP object defining the schema parts
    $schemaDef = json_decode($objJSON);

    mergeParentDef($schemaDef,$objectDir);

    // If we have schema properties continue
    if (isset($schemaDef->properties)) {
        $schema = hydrateSchema($schemaDef,$propertiesDir);
        writeSchema($schema, $schemaName, $schemaDir);
    }
}

/**
 *
 * @param object $schemaDef
 */
function mergeParentDef(& $schemaDef, $objectDir)
{
    if (isset($schemaDef->extends)) {

        // Get the full path to the JSON schema file
        $parentFile = realpath($objectDir . '/' . $schemaDef->extends . '.json');

        if ($parentFile) {
            mergeDefs($schemaDef, $parentFile, $objectDir);
        }
    }
}

function mergeDefs(& $schemaDef, $parentFile, $objectDir){
  // Read the file contents
  $parentJSON = file_get_contents($parentFile);
  
  $nugget = new \Activerules\Nugget\Nugget();

  // Convert JSON into a PHP object defining the schema parts
  $parentDef = json_decode($parentJSON);

  if ($parentDef) {
      $nugget->mergeProps($parentDef, $schemaDef);
      $nugget->mergeRequired($parentDef, $schemaDef);
      loadParent($parentDef, $schemaDef, $objectDir);
  } 
}

function loadParent($parentDef, & $schemaDef, $objectDir) {
  if(isset($parentDef->extends)) {
    // Get the full path to the JSON schema file
    $gparentFile = realpath($objectDir . '/' . $parentDef->extends . '.json');

    if ($gparentFile) {
        mergeDefs($schemaDef, $gparentFile, $objectDir);
    }
  }
}


/**
 *
 * @param object $schema
 * @param string $schemaName
 * @param string $dir
 */
function writeSchema($schema, $schemaName, $dir)
{
    // JSON encode the obj to get a valid spec JSON
    $spec = json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

    $path = rtrim($dir, '/') . '/' . $schemaName;

    $fp = fopen($path, 'w');

    // Write the spec to the file pointer
    fwrite($fp, $spec);

    // Close the file pointer
    fclose($fp);
}

/**
 *
 * @param object $schemaDef
 */
function hydrateSchema($schemaDef,$propertiesDir)
{

  // Set type as object if not defined otherwise
    if (!isset($schemaDef->type)) {
        $schemaDef->type = 'object';
    }

    // Array to hold definitions created by all this object properties
    $schemaDef->definitions = array();
    
    sort($schemaDef->properties);

    // Loop through top level properties and populate, all definitions should be at top level.
    populateProperties($schemaDef, $propertiesDir);

    if (count($schemaDef->definitions) === 0) {
        unset($schemaDef->definitions);
    }

    return $schemaDef;
}

/**
 *
 * @param object $schemaDef
 */
function populateProperties(& $schemaDef, $propertiesDir)
{
    $props = [];
    
    $nugget = new \Activerules\Nugget\Nugget();

    // Loop through the defined properties array and load the definition for each one
    foreach ($schemaDef->properties as $property) {

    // Get the full path to the JSON property JSON file
        $propertyFile = realpath($propertiesDir . '/' . $property . '.json');

        if ($propertyFile) {
            $props[$property] = $nugget->loadPropertyFile($propertyFile);
        }
    }

    $schemaDef->properties = $props;
}

/**
 *
 * @param string $filePath
 */
function hydrateProperty($propertyFile)
{

  // Read the file into a PHP string
    $propertyDef = file_get_contents($propertyFile);

    // Use the serialized JSON string as a JSON object
    $propObj = json_decode($propertyDef);

    // Use this definition as the value for the OpenAPI property
    return $propObj;
}

/**
 *
 * @param object $propObj
 * @param string $defs
 */
function populateObjectProperty(& $propObj, & $defs)
{
    // Loop through looking for $ref's and items
    foreach ($propObj as $prop => $def) {
        resolvePropertyReference($prop, $def, $defs);
    }
}

/**
 *
 * @param string $prop
 * @param object $def
 * @param array $defs
 */
function resolvePropertyReference($prop, $def, & $defs)
{
    switch ($prop) {
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
 * @param object $def
 * @param array $defs
 */
function checkThenProcessItem($def, & $defs)
{
    if (gettype($def) === 'object') {
        processItem($def, $defs);
    }
}

/**
 *
 * @param object $def
 * @param array $defs
 */
function processItem($def, & $defs)
{
    // Loop through ref object
    foreach ($def as $refKey => $refString) {
        // Is this our reference?
        if ($refKey === '$ref') {
            // Load the reference
            loadRef($refString, $defs);
        }
    }
}

/**
 * Load a reference object
 *
 * @param string $refString
 * @param array $defs
 */
function loadRef($refString, & $defs)
{
    // If the first char is a '#' hydrate the referenced object into definitions
    if (substr($refString, 0, 13) === '#/definitions') {
        // We only want the final definition name from the path
        $parts = explode('/', $refString);
        $name = array_pop($parts);

        populateRef($name, $defs);
    }
}

