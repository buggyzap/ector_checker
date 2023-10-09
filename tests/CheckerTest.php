<?php

namespace Ector\Checker\Tests;

use PHPUnit\Framework\TestCase;

class CheckerTest extends TestCase
{
    public function testCheckReturnsTrue()
    {
        $checker = new \Ector\Checker\Checker();
        $this->assertTrue($checker->check());
    }
}
