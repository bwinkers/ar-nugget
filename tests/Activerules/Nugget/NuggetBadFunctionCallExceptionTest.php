<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;
use Activerules\Nugget\Exception\NuggetBadFunctionCallException;

// Need more test


class NuggetBadFunctionCallExceptionTest extends TestCase
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
    public function testBadFunctionCallExceptionIsABadFunctionCallException()
    {
        $this->expectException('\Activerules\Nugget\Exception\NuggetBadFunctionCallException');

        throw new \Activerules\Nugget\Exception\NuggetBadFunctionCallException('What What!');
    }
}
