<?php

namespace Permafrost\Example\Tests;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /** @test */
    public function it_adds_two_plus_two_and_gets_four(): void
    {
        $this->assertEquals(4, 2 + 2);
    }
    
}

