<?php

require_once "vendor/autoload.php";

$nugget = new \Activerules\Nugget\Nugget();

$shortopts = "";
$shortopts .= "s:";  // Required - path for incoming first-pass schema objects
$shortopts .= "u:";  // Required - URL root for schema files
$shortopts .= "o:";  // Required - path for generated schema w/ URL $refs

$options = getopt($shortopts);

if (empty($options['s']) || empty($options['u']) || empty($options['o'])) {
    exit;
}

// The directory with the core Open API schema
define('SCHEMADIR', realpath($options['u']));

// The base URL for the referenced schema
define('SCHEMAURL', realpath($options['u']));

// A writeable directory for the OpenAPI schema w/o included $ref definitions
define('OUTPUTDIR', realpath($options['o']));


