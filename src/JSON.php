<?php

namespace Activerules\Nugget;

use Activerules\Filesys;
use Activerules\Nugget\Exception\NuggetException;

/**
 * The Nugget JSON functions
 */
class JSON
{
    public $filesys;
    
    public function __construct()
    {
        $this->filesys = new \Activerules\Nugget\Filesys();
    }

    /**
     * Write JSON to a file
     *
     * @param string $data
     * @param string $path
     */
    public function write($data, $path)
    {
        $data = json_encode($data);
        
        return $this->filesys->writeFile($data, $path);
    }

    /**
     * Read JSON from a file
     *
     * @param string $path
     * @return mixed False or object 
     */
    public function fromFile($path)
    {
        // Read the file into a PHP string
        $json = file_get_contents($path);

        // Use the serialized JSON string as a JSON object
        $json = $this->valid($json);

        // Use this definition as the value for the OpenAPI property
        return $json;
    }
    
    /**
     * Is the data valid JSON
     * 
     * @param type $data
     * @return mixed False or object
     */
    public function valid($data) 
    {
        $data = json_decode($data);
        
        if (json_last_error() === JSON_ERROR_NONE) {
           return $data;
        }
        
        return false;
    }
}
