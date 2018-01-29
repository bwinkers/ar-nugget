<?php

namespace Activerules\Nugget;

use Activerules\Nugget\Exceptions\NuggetException;

/**
 * The Nugget 
 */
class Nugget
{
  
    protected $types;


    /**
     * Constructor
     */
    public function __construct()
    {
        include realpath(__DIR__.'/lookup/nuggetTypes.php');

        $this->types = $types;
    }
    
    /**
     * Change the base of a $ref schema
     * 
     * @param string $schemaDir
     * @param string $schemaOut
     * @param string $replacementPath
     * @param string $targetPath
     */
    public function convertSchemaFileRefs($schemaDir, $schemaOut, $replacementPath, $targetPath) {
      
        // Create a directory iterator for the defined objects directory
        $files = new \DirectoryIterator($schemaDir);

        // Iterate through object definitions
        foreach ($files as $fileInfo) {
            // Make sure its a valid file
            if ($this->realDirFile($fileInfo)) {
                // Get the name for the new file
                $fileName = $fileInfo->getFilename();
                $currentFile = $fileInfo->getPathName();
                //$filePath = $fileInfo->getP
                // Attempt creating a Schema object from the definition
                $newSchema = $this->convertSchemaFile($currentFile, $replacementPath, $targetPath);

                $this->writeFile($newSchema, $this->pathRoot($schemaOut).$fileName);
            }
        }
    }

    /**
     * Write a file out
     * 
     * @param string $data
     * @param string $path
     */
    public function writeFile($data, $path){
        $filePath = fopen($path, 'w');

        // Write the spec to the file pointer
        fwrite($filePath, $data);

        // Close the file pointer
        fclose($filePath);
    }
    
    /**
     * 
     * @param string $file
     * @param string $replacementPath
     * @param string $targetPath
     * @return string
     */
    public function convertSchemaFile($file, $replacementPath, $targetPath) {
        // Read the file contents
        $JSON = file_get_contents($file);

        return str_replace($this->pathRoot($targetPath), $this->pathRoot($replacementPath), $JSON);

    }
    
    /**
     * Making sure the trailing slash in path is consistent.
     * 
     * @param string $path
     */
    public function pathRoot($path) {
      return rtrim($path, '/').'/';
    }
    
    /**
     * Filter out dot files and directories
     * 
     * @param string $fileInfo
     * @return boolean
     */
    public function realDirFile($fileInfo)
    {
        $name = $fileInfo->getFilename();

        $isValid = false;

        if (!$fileInfo->isDot() && !$fileInfo->isDir() && substr($name, 0, 1) != '.') {
            $isValid = true;
        }

        return $isValid;
    }

    /**
     * Get the JSON type for a higher-level Nugget type
     * @param string $type
     * @return mixed string of mapped JSON type or boolean FALSE
     */
    public function jsonType($type) {
        if (!is_string($type)) {
            throw new \Activerules\Nugget\Exceptions\NuggetException('Invalid Type');
        }

        $type = strtolower($type);
        if (isset($this->types[$type])) {
            return $this->types[$type]['type'];
        }

        return false;
    }
    
    /**
     * Determine if an object is valid
     *
     * @param string $object, JSON serialized object
     * @param type $schema
     * @return boolean
     */
    public function meetsSchema(string $object, $schema)
    {
        $data = json_decode($object);

        $validator = new \Activerules\JsonGuard\Validator($data, $schema);
   
        return $validator->fails() ? false : true ;
    }
    
    /**
     * Remove all properties not defined in the schema
     * 
     * @param string $object
     * @param type $schema
     * @return boolean
     */
    public function limitToSchema(string $object, $schema)
    {
        $data = json_decode($object);

        // We loop through the top level schema objects and only use the data elements defined therein.
        // We don't loop though data becasue it could be much larger than the schema.
        // Start with an empty array.
        $cleanObject = [];
        
        foreach(array_keys((array) $schema->properties) as $prop) {
            if (isset($data->$prop)) {
                $cleanObject[$prop] = $data->$prop;
            }
        }
        
        return json_encode($cleanObject);
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
            $child->required = array_merge($parentReq, $child->required);
        }    
    }

    /**
     *
     * @param object $parent
     * @param object $child
     */
    public function mergeProps($parent, & $child)
    {
        $child->properties = array_merge($parent->properties, $child->properties);
    }

}