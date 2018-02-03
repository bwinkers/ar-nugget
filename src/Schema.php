<?php

namespace Activerules\Nugget;

use Activerules\Nugget\Exceptions\NuggetException;

/**
 * The Nugget 
 */
class Schema
{
        
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
    
}