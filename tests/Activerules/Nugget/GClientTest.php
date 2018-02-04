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
        // Mock credentials
        $credentials = [
            'private_key_id' => 'key123',
            'private_key' => 'privatekey',
            'client_email' => 'test@example.com',
            'client_id' => 'client123',
            'type' => 'service_account',
        ];
        
        // All test will have these variables available to them under $this->
        $this->client = new \Activerules\Nugget\GClient($credentials);

    }

   /**
     * The client class type should be correct
     */
    public function testClientType()
    {
        $clientType = get_class($this->client);
       
        $this->assertEquals('Activerules\Nugget\GClient', $clientType);
    }
    
    /**
     * The gClient class type should be correct
     */
    public function testGClientType()
    {
        $clientType = get_class($this->client->gClient());
       
        $this->assertEquals('Google_Client', $clientType);
    }
    
    /**
     * The gClient class type should be correct
     */
    public function testSettingScope()
    {
        $clientType = get_class($this->client->gClient());
       
        $this->assertEquals('Google_Client', $clientType);
    }
}
