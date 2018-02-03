<?php

namespace Activerules\Nugget;

use Activerules\Nugget\Exceptions\NuggetException;

/**
 * The Nugget SChema Builder functions
 */
class SchemaBuilder
{

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->filesys = new \Activerules\Nugget\Filesys();
        $this->schema = new \Activerules\Nugget\Schema();
    }

    /**
     * Change the base of a $ref schema
     *
     * @param string $schemaDir
     * @param string $schemaOut
     * @param string $replacementPath
     * @param string $targetPath
     */
    public function convertSchemaFileRefs($schemaDir, $schemaOut, $replacementPath, $targetPath)
    {
        // Create a directory iterator for the defined objects directory
        $files = new \DirectoryIterator($schemaDir);

        // Iterate through object definitions
        foreach ($files as $fileInfo) {
            // Make sure its a valid file
            if ($this->filesys->realDirFile($fileInfo)) {
                // Get the name for the new file
                $fileName = $fileInfo->getFilename();
                $currentFile = $fileInfo->getPathName();
                //$filePath = $fileInfo->getP
                // Attempt creating a Schema object from the definition
                $newSchema = $this->convertSchemaFile($currentFile, $replacementPath, $targetPath);

                $this->filesys->writeFile($newSchema, $this->filesys->cleanPath($schemaOut) . $fileName);
            }
        }
    }

    /**
     *
     * @param string $file
     * @param string $replacementPath
     * @param string $targetPath
     * @return string
     */
    public function convertSchemaFile($file, $replacementPath, $targetPath)
    {
        // Read the file contents
        $JSON = file_get_contents($file);

        return str_replace($this->filesys->cleanPath($targetPath), $this->filesys->cleanPath($replacementPath), $JSON);
    }

    /**
     *
     * @param object $parent
     * @param object $child
     */
    public function mergeRequired($parent, & $child)
    {
        $parentReq = [];
        if (isset($parent->required)) {
            $parentReq = $parent->required;
        }
        if (isset($child->required)) {
            $merged = array_unique(array_merge($parentReq, $child->required));
            sort($merged);
            $child->required = $merged;
        }
    }

    /**
     *
     * @param object $parent
     * @param object $child
     */
    public function mergeProps($parent, & $child)
    {
        $properties = array_unique(array_merge($parent->properties, $child->properties));
        sort($properties);
        $child->properties = $properties;
    }

    /**
     *
     * @param string $filePath
     */
    public function loadPropertyFile($propertyFile)
    {
        // Read the file into a PHP string
        $propertyDef = file_get_contents($propertyFile);

        // Use the serialized JSON string as a JSON object
        $propObj = json_decode($propertyDef);

        // Use this definition as the value for the OpenAPI property
        return $propObj;
    }
}