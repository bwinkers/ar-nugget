<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;
use Activerules\Nugget\SClient;

require_once 'vendor/autoload.php';

class SClientTest extends TestCase
{

    /**
     * This gets called before all test functions
     */
    public function setUp()
    {
        
    }

    /**
     * The client class type should be correct
     */
    public function testClientType()
    {
        $config = [];

        $this->expectException('InvalidArgumentException');
        
        // All test will have these variables available to them under $this->
        $client = new \Activerules\Nugget\SClient($config);
        
        $clientType = \get_class($client);
    }
}
