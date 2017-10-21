<?php

namespace Bwinkers\Nugget;

use Bwinkers\Nugget\Exceptions\NuggetException;

class Nugget
{

    /**
     * Create a new Skeleton Instance
     */
    public function __construct()
    {
    }

    /**
     * Test function
     *
     * @param string $phrase Phrase to return
     *
     * @return string Returns the phrase passed in
     */
    public function echoPhrase($phrase)
    {
        return $phrase;
    }
}