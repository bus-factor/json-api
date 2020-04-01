<?php

/**
 * LinkObjectTest.php
 *
 * @author    michael.lessnau
 * @since     27.03.2020
 */

declare(strict_types=1);

namespace Test\JsonApi\Model;

use InvalidArgumentException;
use JsonApi\Model\LinkObject;
use JsonApi\Model\MetaObject;
use PHPUnit\Framework\TestCase;

/**
 * Class LinkObjectTest
 *
 * @coversDefaultClass \JsonApi\Model\LinkObject
 * @uses \JsonApi\Model\AbstractObject
 * @uses \JsonApi\Model\LinkObject
 * @uses \JsonApi\Model\MetaObject
 */
class LinkObjectTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::setHref
     * @covers ::setMeta
     * @covers ::getHref
     * @covers ::getMeta
     *
     * @testWith ["http://example.com", {}]
     *           ["klajsdf", [], "InvalidArgumentException", "Invalid URL: klajsdf"]
     */
    public function testConstruct(
        string $href,
        array $metaData,
        ?string $exception = null,
        ?string $exceptionMessage = null
    ): void {
        $meta = new MetaObject($metaData);

        if (isset($exception)) {
            $this->expectException($exception);

            if (isset($exceptionMessage)) {
                $this->expectExceptionMessage($exceptionMessage);
            }
        }

        $subject = new LinkObject($href, $meta);

        if (!isset($exception)) {
            $this->assertSame($href, $subject->getHref());
            $this->assertSame($meta, $subject->getMeta());
        }
    }

    /**
     * @return void
     *
     * @covers ::jsonSerialize
     */
    public function testJsonSerialize(): void
    {
        $subject = new LinkObject(
            'http://example.com',
            new MetaObject([
                'foo' => 'bar',
            ])
        );

        $this->assertSame(
            [
                'href' => 'http://example.com',
                'meta' => [
                    'foo' => 'bar',
                ],
            ],
            $subject->jsonSerialize()
        );

        $subjectWithoutMeta = $subject->withoutMeta();

        $this->assertSame(
            'http://example.com',
            $subjectWithoutMeta->jsonSerialize()
        );
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::getHref
     * @covers ::getMeta
     * @covers ::setMeta
     * @covers ::setHref
     * @covers ::withHref
     */
    public function testWithHref(): void
    {
        $meta = new MetaObject([]);
        $subject = new LinkObject('http://example.com', $meta);

        $subjectClone = $subject->withHref('http://example.org');

        $this->assertNotSame($subject, $subjectClone);
        $this->assertSame($subject->getMeta(), $subjectClone->getMeta());
        $this->assertSame('http://example.org', $subjectClone->getHref());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid URL: foo');

        $subject->withHref('foo');
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::getHref
     * @covers ::getMeta
     * @covers ::setMeta
     * @covers ::setHref
     * @covers ::withMeta
     */
    public function testWithMeta(): void
    {
        $meta = new MetaObject([]);
        $meta2 = new MetaObject(['foo' => 'bar']);
        $subject = new LinkObject('http://example.com', $meta);

        $subjectClone = $subject->withMeta($meta2);

        $this->assertNotSame($subject, $subjectClone);
        $this->assertSame($subject->getHref(), $subjectClone->getHref());
        $this->assertSame($meta2, $subjectClone->getMeta());
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::getHref
     * @covers ::getMeta
     * @covers ::setMeta
     * @covers ::setHref
     * @covers ::withoutMeta
     */
    public function testWithoutMeta(): void
    {
        $meta = new MetaObject([]);
        $subject = new LinkObject('http://example.com', $meta);

        $subjectClone = $subject->withoutMeta();

        $this->assertNotSame($subject, $subjectClone);
        $this->assertSame($subject->getHref(), $subjectClone->getHref());
        $this->assertNull($subjectClone->getMeta());
    }
}
