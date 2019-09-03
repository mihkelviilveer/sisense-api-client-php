<?php

use Sisense\Client;
use Sisense\ClientInterface;
use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    /**
     * @covers \Sisense\Api\Auth
     * @test
     */
    public function testLogin()
    {
        $client = new Client('http://localhost');

        $client->auth->getToken('username', 'password');

        $this->assertSame('http://localhost', $client->getUrl());
    }
}
