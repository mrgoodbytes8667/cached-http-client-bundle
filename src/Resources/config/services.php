<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Bytes\HttpClient\Cached\Services\CachedHttpClient;

/*
 * @param ContainerConfigurator $container
 */
return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->set('bytes_cached_http_client.client', CachedHttpClient::class)
        ->args([
            service('cache.app'), // 0 => \Symfony\Contracts\Cache\CacheInterface $cache
            service('http_client'), // 1 => \Symfony\Contracts\HttpClient\HttpClientInterface $client
        ])
        ->lazy()
        ->alias(CachedHttpClient::class, 'bytes_cached_http_client.client')
        ->public();
};
