<?php

namespace ExportComments\Tests;

use PHPUnit\Framework\TestCase;
use ExportComments\Client;
use ExportComments\ExportCommentsException;
use ExportComments\ExportCommentsResponse;

class PingTest extends TestCase
{
    private $client;
    private $apiToken;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Use environment variable for API token in tests
        $this->client = new Client($this->apiToken);
        $this->apiToken = $_ENV['EXPORTCOMMENTS_API_TOKEN'] ?? 'test_token_123';
    }

    protected function tearDown(): void
    {
        $this->client = null;
        parent::tearDown();
    }

    public function testPingMethodExists()
    {
        $this->assertTrue(method_exists($this->client, 'ping'));
    }

    public function testPingEndpoint()
    {
        $this->markTestSkipped('Requires valid API token for actual API calls');
        
        try {
            $response = $this->client->ping();
            $this->assertInstanceOf(ExportCommentsResponse::class, $response);
            $this->assertArrayHasKey('message', $response->result);
            $this->assertEquals('pong', $response->result['message']);
        } catch (ExportCommentsException $e) {
            // If we get an authentication error, that means the endpoint is reachable
            $this->assertStringContainsString('auth', strtolower($e->getMessage()));
        }
    }

    public function testClientInitialization()
    {
        $client = new Client($this->apiToken);
        $this->assertInstanceOf(Client::class, $client);
    }

    public function testClientWithCustomEndpoint()
    {
        $customEndpoint = 'https://api.example.com/v3';
        $client = new Client($this->apiToken, $customEndpoint);
        $this->assertInstanceOf(Client::class, $client);
    }

    /**
     * Test basic connectivity by trying to list exports (which would verify API connection)
     */
    public function testApiConnectivity()
    {
        $this->markTestSkipped('Requires valid API token for actual API calls');
        
        try {
            $response = $this->client->exports->listExports(1, 1);
            $this->assertNotNull($response);
        } catch (ExportCommentsException $e) {
            // If we get an authentication error, that means the endpoint is reachable
            $this->assertStringContainsString('auth', strtolower($e->getMessage()));
        }
    }

    public function testPingWithInvalidToken()
    {
        $this->markTestSkipped('Requires actual API call to test authentication');
        
        $invalidClient = new Client('invalid_token');
        
        try {
            $response = $invalidClient->ping();
            // Ping might not require authentication, so we check the response
            $this->assertInstanceOf(ExportCommentsResponse::class, $response);
        } catch (ExportCommentsException $e) {
            // If authentication is required, we should get an auth error
            $this->assertStringContainsString('auth', strtolower($e->getMessage()));
        }
    }

    public function testNetworkErrorHandling()
    {
        $invalidEndpointClient = new Client($this->apiToken, 'https://invalid-endpoint.example.com/api/v3');
        
        $this->markTestSkipped('Requires network call to test error handling');
        
        $this->expectException(ExportCommentsException::class);
        $invalidEndpointClient->ping();
    }
}
