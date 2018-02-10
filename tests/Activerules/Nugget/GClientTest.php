<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;
use Google_Service_Sheets;
use Activerules\Nugget\GClient;

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

        $scopes = \Google_Service_Sheets::SPREADSHEETS_READONLY;
        
        // All test will have these variables available to them under $this->
        $this->client = new \Activerules\Nugget\GClient($credentials);
        $this->scopedClient = new \Activerules\Nugget\GClient($credentials, $scopes);
        
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
    public function testSettingScope()
    {

        $this->client->setScopes(\Google_Service_Sheets::SPREADSHEETS_READONLY);
        $scopes = $this->client->getScopes();
        $this->assertEquals('https://www.googleapis.com/auth/spreadsheets.readonly', $scopes[0]);
    }
}
