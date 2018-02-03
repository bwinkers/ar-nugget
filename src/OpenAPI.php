<?php

namespace Activerules\Nugget;

use Activerules\Nugget\Exceptions\NuggetException;

/**
 * The Nugget 
 */
class OpenAPI
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
}