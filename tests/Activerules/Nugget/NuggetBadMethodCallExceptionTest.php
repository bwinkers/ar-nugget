<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;
use Activerules\Nugget\Exception\NuggetBadMethodCallException;

// Need more test


class NuggetBadMethodCallExceptionTest extends TestCase
{

    /**
     * This gets called before all test functions
     */
    public function setUp()
    {
        $this->openAPI = new \Activerules\Nugget\OpenAPI();
    }

    /**
     * The exception should be of type exception
     */
    public function testBadMethodCallExceptionIsABadMethodCallException()
    {
        $this->expectException('\Activerules\Nugget\Exception\NuggetBadMethodCallException');

        throw new \Activerules\Nugget\Exception\NuggetBadMethodCallException('What What!');
    }
}
