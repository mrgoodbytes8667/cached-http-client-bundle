<?php

namespace Bytes\HttpClient\Cached\Services;

use DateInterval;
use JetBrains\PhpStorm\Deprecated;
use Psr\Cache\CacheItemInterface;

use function Symfony\Component\String\u;

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CachedHttpClient
{
    public function __construct(private readonly CacheInterface $cache, private readonly HttpClientInterface $client)
    {
    }

    public function getContent(string $method, string $url, int|DateInterval|null $time, ?string $key = null, array $options = []): ?string
    {
        return $this->cache->get($this->buildCacheKey(key: $key, method: $method, url: $url), function (CacheItemInterface $item) use ($method, $url, $time, $options) {
            $content = $this->client->request(method: $method, url: $url, options: $options)->getContent();

            $item->expiresAfter($time);

            return $content;
        });
    }

    #[Deprecated('This function will change functionality in v0.2.0, replace calls to this with getContent() method.', '%class%->getContent(%parametersList%)')]
    public function request(string $method, string $url, int|DateInterval|null $time, ?string $key = null, array $options = []): ?string
    {
        return $this->getContent(method: $method, url: $url, time: $time, key: $key, options: $options);
    }

    public function buildCacheKey(?string $key, string $method, string $url): string
    {
        return $key ?? u('bytes_cached_http_client_')->append($method, '_', $url)->snake()->replaceMatches('/[^A-Za-z0-9_]++/', '');
    }
}
