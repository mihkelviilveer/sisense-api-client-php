<?php

use Sisense\Client;
use Sisense\ClientInterface;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    /**
     * @covers \Sisense\Client
     * @test
     */
    public function should_instanciate_client_class()
    {
        $client = new Client('http://localhost');

        $this->assertInstanceOf(ClientInterface::class, $client);
    }

    /**
     * @covers \Sisense\Client
     * @test
     */
    public function should_return_api_url()
    {
        $client = new Client('http://localhost');
        $this->assertSame('http://localhost', $client->getUrl());
    }

    /**
     * @covers \Sisense\Client
     * @test
     */
    public function should_not_get_api_instance()
    {
        $this->expectException(\InvalidArgumentException::class);

        $client = new Client('http://localhost');
        $client->api('do_not_exist');
    }
}
