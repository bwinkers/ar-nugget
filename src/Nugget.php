<?php

namespace Activerules\Nugget;

use Activerules\Nugget\Exceptions\NuggetException;

/**
 * The Nugget
 */
class Nugget
{
    public $types;
    public $filesys;
    public $schema;
    public $builder;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->filesys = new \Activerules\Nugget\Filesys();

        $this->schema = new \Activerules\Nugget\Schema();

        $this->openAPI = new \Activerules\Nugget\openAPI();

        $this->builder = new \Activerules\Nugget\SchemaBuilder();
    }
}
