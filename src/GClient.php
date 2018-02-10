<?php

namespace Activerules\Nugget;

use Activerules\Nugget\Exceptions\NuggetException;
use Google_Client;

/**
 * The Nugget Google Client V4
 */
class GClient extends Google_Client
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

        parent::__construct($config);
        //$this->gClient = $gClient;

        $this->setAuthConfig($credentials);

        if ($scopes) {
            $this->setScopes($scopes);
        }  
    }
}
