<?php

namespace Activerules\Nugget;

use Activerules\Nugget\Exceptions\NuggetException;

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
    public function isValid(string $object, $schema)
    {
        $data = json_decode($object);

        $validator     = new \League\JsonGuard\Validator($data, $schema);
   
        if ($validator->fails()) {
            $errors = $validator->errors();
            return false;
        }
        
        return true;
    }

}