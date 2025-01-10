<?php

namespace Bytes\HttpClient\Cached\Tests\Services;

use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use Psr\Clock\ClockInterface;
use Symfony\Component\Clock\MockClock;
use Symfony\Component\HttpClient\Response\JsonMockResponse;

abstract class TestCachedHttpClient extends TestCase
{
    public const CACHE_LIFETIME = 10;

    public const FOOBAR = ['foo' => 'bar'];

    public const BARFOO = ['bar' => 'foo'];

    protected ?ClockInterface $clock = null;

    protected function buildJsonResponse(array $json = self::FOOBAR): JsonMockResponse
    {
        return new JsonMockResponse($json, [
            'response_headers' => [
                'Date' => $this->clock->now()->format(DateTimeInterface::RFC822),
                'Cache-Control' => 'private, max-age=60, s-maxage=60',
                'github-authentication-token-expiration' => (new MockClock('2022-11-16 16:20:00'))->withTimeZone('UTC')->now()->format('Y-m-d H:i:s e'),
            ],
        ]);
    }

    public function setUp(): void
    {
        $this->clock = (new MockClock('2022-11-16 15:20:00'))->withTimeZone('UTC');
    }

    public function tearDown(): void
    {
        $this->clock = null;
    }
}
