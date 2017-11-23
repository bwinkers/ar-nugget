<?php

namespace Activerules\Nugget;

use Activerules\Nugget\Exceptions\NuggetException;

/**
 * The Nugget 
 */
class Nugget
{

    /**
     * Create a new Skeleton Instance
     */
    public function __construct()
    {
    }
    
    /**
     * Determine if an object is valid
     *
     * @param string $phrase Phrase to return
     *
     * @return string Returns the phrase passed in
     */
    public function meetsSchema(string $object, $schema)
    {
        $data = json_decode($object);

        $validator     = new \Activerules\JsonGuard\Validator($data, $schema);
   
        if ($validator->fails()) {
            $errors = $validator->errors();
            return false;
        }
        
        return true;
    }
    
    /**
     * Remove all properties not defined in the schema
     *
     * @param string $phrase Phrase to return
     *
     * @return string Returns the phrase passed in
     */
    public function limitToSchema(string $object, $schema)
    {
        $data = json_decode($object);
        
        var_export($schema);

        $validator     = new \Activerules\JsonGuard\Validator($data, $schema);
   
        if ($validator->fails()) {
            $errors = $validator->errors();
            return false;
        }
        
        return true;
    }

}