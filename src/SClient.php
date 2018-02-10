<?php

namespace Activerules\Nugget;

use Activerules\Nugget\Exceptions\NuggetException;
use Aws\S3\S3Client;

/**
 * The Nugget WS S3 Client
 */
class SClient extends \Aws\S3\S3Client
{
    /**
     * 
     * @param string $key
     * @param string $secret
     */
    public function __construct($config)
    {
        try {
            parent::__construct($config);
        } catch (NuggetException $ex) {
            
        }
    }
}