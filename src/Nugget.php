<?php

namespace Activerules\Nugget;

use Activerules\Nugget\Exception\NuggetException;

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

        $this->openAPI = new \Activerules\Nugget\OpenAPI();

        $this->schemaBuilder = new \Activerules\Nugget\SchemaBuilder();
    }
}
