<?php

namespace Bytes\HttpClient\Cached\Tests\Services;

use Bytes\HttpClient\Cached\Services\CachedHttpClient;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\HttpClient\MockHttpClient;

class CachedHttpClientTest extends TestCachedHttpClient
{
    private ?CachedHttpClient $client = null;

    public function testRequest()
    {
        $response1 = $this->client->request('GET', 'https://example.example', time: self::CACHE_LIFETIME);
        $response2 = $this->client->request('GET', 'https://example.example', time: self::CACHE_LIFETIME);
        $response3 = $this->client->request('GET', 'https://example.example/index.html', time: self::CACHE_LIFETIME);

        self::assertEquals($response1, $response2);
        self::assertNotEquals($response1, $response3);
    }

    public function testGetContent()
    {
        $response1 = $this->client->getContent('GET', 'https://example.example', time: self::CACHE_LIFETIME);
        $response2 = $this->client->getContent('GET', 'https://example.example', time: self::CACHE_LIFETIME);
        $response3 = $this->client->getContent('GET', 'https://example.example/index.html', time: self::CACHE_LIFETIME);

        self::assertSame($response1, $response2);
        self::assertNotSame($response1, $response3);
    }

    public function setUp(): void
    {
        parent::setUp();

        $mockClient = new MockHttpClient([
            $this->buildJsonResponse(),
            $this->buildJsonResponse(self::BARFOO),
        ]);

        $cache = new ArrayAdapter(defaultLifetime: static::CACHE_LIFETIME, maxLifetime: static::CACHE_LIFETIME * 2,
            clock: $this->clock);

        $this->client = new CachedHttpClient(cache: $cache, client: $mockClient);
    }

    public function tearDown(): void
    {
        parent::tearDown();

        $this->client = null;
    }
}
