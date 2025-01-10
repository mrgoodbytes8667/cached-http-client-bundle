<?php

namespace Bytes\HttpClient\Cached\Services;

use Bytes\HttpClient\Cached\Objects\Response\CacheableResponse;
use DateInterval;
use Psr\Cache\CacheItemInterface;

use function Symfony\Component\String\u;

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class CachedHttpClient
{
    public function __construct(private readonly CacheInterface $cache, private readonly HttpClientInterface $client)
    {
    }

    public function request(string $method, string $url, int|DateInterval|null $time, ?string $key = null, array $options = []): ?ResponseInterface
    {
        $cacheKey = $this->buildCacheKey(key: $key, method: $method, url: $url, function: 'request');

        return $this->cache->get($cacheKey, function (CacheItemInterface $item) use ($method, $url, $time, $options) {
            $response = $this->client->request(method: $method, url: $url, options: $options);

            $item->expiresAfter($time);

            return CacheableResponse::createFromResponse($response);
        });
    }

    public function getContent(string $method, string $url, int|DateInterval|null $time, ?string $key = null, array $options = []): ?string
    {
        return $this->cache->get($this->buildCacheKey(key: $key, method: $method, url: $url, function: 'getContent'), function (CacheItemInterface $item) use ($method, $url, $time, $options) {
            $content = $this->client->request(method: $method, url: $url, options: $options)->getContent();

            $item->expiresAfter($time);

            return $content;
        });
    }

    public function buildCacheKey(?string $key, string $method, string $url, string $function): string
    {
        return $key ?? u('bytes_cached_http_client_')->append($method, '_', $function, '_', $url)->snake()->replaceMatches('/[^A-Za-z0-9_]++/', '');
    }
}
