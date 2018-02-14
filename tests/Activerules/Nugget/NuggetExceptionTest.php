<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;
use Activerules\Nugget\Exception\NuggetException;

// Need more test


class NuggetExceptionTest extends TestCase
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
    public function testExceptionIsAnException()
    {
        $this->expectException('\Activerules\Nugget\Exception\NuggetException');

        throw new \Activerules\Nugget\Exception\NuggetException('What What!');
    }

    /**
     * The exception should be of type exception
     */
    public function testNuggetExceptionIsAnException()
    {
        $this->expectException('\Activerules\Nugget\Exception\NuggetException');

        $this->openAPI->jsonType([]);
    }
}
