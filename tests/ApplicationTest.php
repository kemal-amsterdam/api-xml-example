<?php

use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    public function testCheckRequestMethod()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
