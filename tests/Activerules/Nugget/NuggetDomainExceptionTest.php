<?php

namespace Activerules\Nugget;

use PHPUnit\Framework\TestCase;
use Activerules\Nugget\Exception\NuggetDomainException;

// Need more test


class NuggetDomainExceptionTest extends TestCase
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
    public function testDomainExceptionIsADomainException()
    {
        $this->expectException('\Activerules\Nugget\Exception\NuggetDomainException');

        throw new \Activerules\Nugget\Exception\NuggetDomainException('What What!');
    }
}
