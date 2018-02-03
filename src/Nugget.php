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
        
        $this->filesys = new \Activerules\Nugget\Filesys();
        
        $this->schema = new \Activerules\Nugget\Schema();
        
        $this->openAPI = new \Activerules\Nugget\openAPI();
        
        $this->builder = new \Activerules\Nugget\SchemaBuilder();
    }
}