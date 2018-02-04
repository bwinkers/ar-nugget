<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;

require_once 'vendor/autoload.php';

class GClientTest extends TestCase
{

    /**
     * This gets called before all test functions
     */
    public function setUp()
    {
        $config = '/home/brian/.google/izzup-client_secret.json';

        if (file_exists($config)) {

            $json = file_get_contents($config);
            if (!$credentials = json_decode($json, true)) {
                throw new LogicException('invalid json for auth config');
            }
        }

        // All test will have these variables available to them under $this->
        $this->client = new \Activerules\Nugget\GClient($credentials);
        
        $readScopes = \Google_Service_Sheets::SPREADSHEETS_READONLY;

        $this->client->setScopes($readScopes);
    }

   /**
     * The client class type should be correct
     */
    public function testClientType()
    {
        $clientType = get_class($this->client);
       
        $this->assertEquals('Activerules\Nugget\GClient', $clientType);
    }

}
