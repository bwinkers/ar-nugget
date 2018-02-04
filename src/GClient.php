<?php

namespace Activerules\Nugget;

use Activerules\Nugget\Exceptions\NuggetException;

/**
 * The Nugget Google Client V4
 */
class GClient
{
    protected $gClient;

    /**
     *
     * @param object $credentials
     * @param string $scopes
     */
    public function __construct($credentials, $scopes = null)
    {

        $config = array(
            'application_name' => 'activerules-nugget-gclient',
            'use_application_default_credentials' => false
        );

        $gClient = new \Google_Client($config);

        $gClient->setAuthConfig($credentials);
        
        if($scopes) {
            $this->setScopes($scopes);
        }

        $this->gClient = $gClient;
    }

    public function gClient()
    {
        return $this->gClient;
    }

    public function setScopes($scopes)
    {
        $this->gClient->setScopes($scopes);
    }

    public function getScopes()
    {
        return $this->gClient->getScopes();
    }
}
