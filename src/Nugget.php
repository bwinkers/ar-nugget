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