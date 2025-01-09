<?php

namespace Bytes\HttpClient\Cached\Tests\Services;

use Bytes\HttpClient\Cached\Services\CachedHttpClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\JsonMockResponse;
use Symfony\Contracts\Cache\CacheInterface;

class LegacyCachedHttpClientTest extends TestCase
{
    public function testRequest()
    {
        $mockClient = new MockHttpClient([
            new JsonMockResponse([
                'foo' => 'bar',
            ]),
        ]);

        $cache = self::createStub(CacheInterface::class);
        $cache->method('get')
            ->willReturn(json_encode([
                'foo' => 'bar',
            ]));

        $client = new CachedHttpClient(cache: $cache, client: $mockClient);

        $response1 = $client->request('GET', 'https://example.example', time: 3600);
        $response2 = $client->request('GET', 'https://example.example', time: 3600);

        self::assertSame($response1, $response2);
    }

    public function testCache()
    {
        $mockClient = new MockHttpClient([
            new JsonMockResponse([
                'foo' => 'bar',
            ]),
            new JsonMockResponse([
                'bar' => 'foo',
            ]),
        ]);

        $cache = new ArrayAdapter();

        $client = new CachedHttpClient(cache: $cache, client: $mockClient);

        $response1 = $client->request('GET', 'https://example.example', time: 3600);
        $response2 = $client->request('GET', 'https://example.example', time: 3600);
        $response3 = $client->request('GET', 'https://example.example/index.html', time: 3600);

        self::assertSame($response1, $response2);
        self::assertNotSame($response1, $response3);
    }
}
