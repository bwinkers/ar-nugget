<?php

namespace Activerules\Nugget;

use Activerules\Nugget\Exceptions\NuggetException;

/**
 * The Nugget 
 */
class Nugget
{
  
    protected $types = [
        'integer' => [
          'type' => 'integer',
          'format' => 'int32',
          'comment' => 'signed 32 bits'  
        ],
        'long' => [
          'type' => 'integer',
          'format' => 'int64',
          'comment' => 'signed 64 bits'  
        ],
        'float' => [
            'type' => 'number',
            'format' => 'float'
        ],
        'double' => [
            'type' => 'number',
            'format' => 'double'
        ],
        'string' =>[
            'type' => 'string'
        ],
        'byte' =>[
            'type' => 'string',
            'format' => 'byte',
            'comment' => 'base64 encoded characters'
        ],
        'binary' =>[
            'type' => 'string',
            'format' => 'binary',
            'comment' => 'any sequence of octets'
        ],
        'boolean' => [
            'type' => ' boolean'
        ],
        'date' =>[
            'type' => 'string',
            'format' => 'date',
            'comment' => 'As defined by full-date - RFC3339'
        ],
        'datetime' =>[
            'type' => 'string',
            'format' => 'date-ime',
            'comment' => 'As defined by date-time - RFC3339'
        ],
        'password' =>[
            'type' => 'string',
            'comment' => 'A hint to UIs to obscure input.'
        ],
        'url' => [
            'type' => 'string'
        ],
        'text' => [
            'type' => 'string'
        ]
    ];


    /**
     * Constructor
     */
    public function __construct()
    {
    }
    
    public function convertSchemaFileRefs($schemaDir, $schemaOut, $replacementPath, $targetPath) {
      
      // Create a directory iterator for the defined objects directory
      $files = new \DirectoryIterator($schemaDir);

      // Iterate through object definitions
      foreach ($files as $fileInfo) {
          // Make sure its a valid file
          if ($this->realFile($fileInfo)) {
              // Get the name for the new file
              $fileName = $fileInfo->getFilename();
              $currentFile = $fileInfo->getPathName();
              //$filePath = $fileInfo->getP
              // Attempt creating a Schema object from the definition
              $newSchema = $this->convertSchemaFile($currentFile, $replacementPath, $targetPath);
              var_dump($newSchema);
              $this->writeFile($newSchema, $this->pathRoot($schemaOut).$fileName.'json');
          }
      }
    }
    
    /**
     * 
     * @param type $newSchema
     * @param type $schemaOut
     * @param type $fileName
     */
    public function writeFile($data, $path){
      $fp = fopen($path, 'w');

      // Write the spec to the file pointer
      fwrite($fp, $data);

      // Close the file pointer
      fclose($fp);
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
    public function realFile($fileInfo)
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
      if(!is_string($type)) {
        throw new \Activerules\Nugget\Exceptions\NuggetException('Invalid Type');
      }
      
      $type = strtolower($type);
      if(isset($this->types[$type])) {
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
    public function limitToSchema(string $object, $schema, $jsonOut=true)
    {
        $data = json_decode($object);

        // We loop through the top level schema objects and only use the data elements defined therein.
        // We don't loop though data becasue it could be much larger than the schema.
        // Start with an empty array.
        $cleanObject = [];
        
        foreach($schema->properties as $prop => $val) {
            if(isset($data->$prop)) {
                $cleanObject[$prop] = $data->$prop;
            }
        }
        
        return $jsonOut ? json_encode($cleanObject) : $cleanObject;
    }
    
    

}