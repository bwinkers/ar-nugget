<?php

require_once "vendor/autoload.php";

$nugget = new \Activerules\Nugget\Nugget();

$shortopts = "";
$shortopts .= "s:";  // Required - path for incoming first-pass schema objects
$shortopts .= "t:";  // Required - the target '$refs' string to be replaced
$shortopts .= "r:";  // Required - Replacement root for '$refs'
$shortopts .= "o:";  // Required - path for generated schema w/ new $refs

$options = getopt($shortopts);

if (empty($options['s']) || empty($options['t']) || empty($options['r']) || empty($options['o'])) {
    exit;
}

// The directory with the core Open API schema
$schemaDir = realpath($options['s']);

// The $refs path being replaced
$targetPath = $options['t'];

// The new $refs path
$replacementPath = $options['r'];

// A writeable directory for the OpenAPI schema w/new $ref paths
$schemaOut = realpath($options['o']);

/**
 * Use Nugget
 */
$nugget->convertSchemaFileRefs($schemaDir, $schemaOut, $replacementPath, $targetPath);