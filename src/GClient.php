<?php

namespace Activerules\Nugget;

use Activerules\Nugget\Exceptions\NuggetException;

require_once 'vendor/autoload.php';

/**
 * The Nugget Google Client V4
 */
class GClient
{
    
    protected $gClient;

    /**
     * 
     * @param object $credentials
     * @param string $scope
     */
    public function __construct($credentials)
    {

        $config = array(
            'application_name' => 'activerules-nugget-gclient',
            'use_application_default_credentials' => false
        );

        $gClient = new \Google_Client($config);

        $gClient->setAuthConfig($credentials);
        
        $this->gClient = $gClient;
    }
    
    public function gClient() {
        return $this->gClient;
    }
    
    public function setScopes($scopes) {
        $this->gClient->setSCopes($scopes);
    }
}
