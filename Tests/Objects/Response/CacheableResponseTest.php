<?php

namespace Bytes\HttpClient\Cached\Tests\Objects\Response;

use Bytes\Common\Faker\TestFakerTrait;
use Bytes\HttpClient\Cached\Objects\Response\CacheableResponse;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\MockClock;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\JsonMockResponse;

class CacheableResponseTest extends TestCase
{
    use TestFakerTrait;

    public function testCreateFromResponse()
    {
        $response = (new MockHttpClient([new JsonMockResponse(['foo' => 'bar'])]))->request('GET', '');

        $object = CacheableResponse::createFromResponse($response);

        self::assertInstanceOf(CacheableResponse::class, $object);
        self::assertJsonStringEqualsJsonString(json_encode(['foo' => 'bar']), $object->getContent());
    }

    public function testGetSetContent()
    {
        $content = $this->faker->text();

        $object = new CacheableResponse();

        self::assertInstanceOf(CacheableResponse::class, $object);
        self::assertEmpty($object->getContent());
        self::assertInstanceOf(CacheableResponse::class, $object->setContent($content));
        self::assertSame($content, $object->getContent());
    }

    public function testGetSetStatusCode()
    {
        $statusCode = $this->faker->numberBetween(200, 500);

        $object = new CacheableResponse();

        self::assertInstanceOf(CacheableResponse::class, $object);
        self::assertSame(-1, $object->getStatusCode());
        self::assertInstanceOf(CacheableResponse::class, $object->setStatusCode($statusCode));
        self::assertSame($statusCode, $object->getStatusCode());
    }

    public function testGetSetHeaders()
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Date' => (new MockClock('2022-11-16 15:20:00'))->withTimeZone('UTC')->now()->format(DateTimeInterface::RFC822),
            'Cache-Control' => 'private, max-age=60, s-maxage=60',
            'github-authentication-token-expiration' => (new MockClock('2022-11-16 16:20:00'))->withTimeZone('UTC')->now()->format('Y-m-d H:i:s e'),
        ];

        $object = new CacheableResponse();

        self::assertInstanceOf(CacheableResponse::class, $object);
        self::assertEmpty($object->getHeaders());
        self::assertInstanceOf(CacheableResponse::class, $object->setHeaders($headers));
        self::assertSame($headers, $object->getHeaders());
        self::assertCount(4, $object->getHeaders());
    }

    public function testToArray()
    {
        $object = new CacheableResponse();

        self::assertCount(0, $object->toArray());
    }

    public function testGetInfo()
    {
        $object = new CacheableResponse();

        self::assertCount(0, $object->getInfo());
    }

    public function testCancel()
    {
        $object = new CacheableResponse();
        $object->cancel();

        self::assertInstanceOf(CacheableResponse::class, $object);
    }
}
