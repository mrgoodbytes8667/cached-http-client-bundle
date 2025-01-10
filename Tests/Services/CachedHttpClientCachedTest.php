<?php

namespace Bytes\HttpClient\Cached\Tests\Services;

use Bytes\HttpClient\Cached\Services\CachedHttpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CachedHttpClientCachedTest extends TestCachedHttpClient
{
    private ?HttpClientInterface $mockClient = null;

    public function testCacheRequest()
    {
        $cache = self::createStub(CacheInterface::class);
        $cache->method('get')
            ->willReturn($this->buildJsonResponse());

        $client = new CachedHttpClient(cache: $cache, client: $this->mockClient);

        $response1 = $client->request('GET', 'https://example.example', time: self::CACHE_LIFETIME);
        $response2 = $client->request('GET', 'https://example.example', time: self::CACHE_LIFETIME);

        self::assertSame($response1, $response2);
    }

    public function testCacheGetContent()
    {
        $cache = self::createStub(CacheInterface::class);
        $cache->method('get')
            ->willReturn(json_encode(self::FOOBAR));

        $client = new CachedHttpClient(cache: $cache, client: $this->mockClient);

        $response1 = $client->getContent('GET', 'https://example.example', time: self::CACHE_LIFETIME);
        $response2 = $client->getContent('GET', 'https://example.example', time: self::CACHE_LIFETIME);

        self::assertSame($response1, $response2);
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->mockClient = new MockHttpClient([
            $this->buildJsonResponse(),
        ]);
    }

    public function tearDown(): void
    {
        parent::tearDown();

        $this->mockClient = null;
    }
}
