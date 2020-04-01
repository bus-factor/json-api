<?php

/**
 * ErrorObjectTest.php
 *
 * @author    michael.lessnau
 * @since     28.03.2020
 */

declare(strict_types=1);

namespace Test\JsonApi\Model;

use InvalidArgumentException;
use JsonApi\Model\ErrorObject;
use JsonApi\Model\ErrorSourceObject;
use JsonApi\Model\LinkObject;
use JsonApi\Model\LinksObject;
use JsonApi\Model\MetaObject;
use PHPUnit\Framework\TestCase;

/**
 * Class ErrorObjectTest
 *
 * @coversDefaultClass \JsonApi\Model\ErrorObject
 * @uses \JsonApi\Model\AbstractObject
 * @uses \JsonApi\Model\ErrorObject
 * @uses \JsonApi\Model\ErrorSourceObject
 * @uses \JsonApi\Model\LinkObject
 * @uses \JsonApi\Model\LinksObject
 * @uses \JsonApi\Model\MetaObject
 */
class ErrorObjectTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::getCode
     * @covers ::setCode
     * @covers ::withCode
     * @covers ::withoutCode
     */
    public function testCodeAccessors(): void
    {
        $subject = new ErrorObject();

        $this->assertNull($subject->getCode());

        $subjectClone = $subject->withCode('42');

        $this->assertNotSame($subject, $subjectClone);
        $this->assertSame('42', $subjectClone->getCode());

        $subjectClone2 = $subjectClone->withoutCode();

        $this->assertNotSame($subjectClone, $subjectClone2);
        $this->assertNull($subjectClone2->getCode());
    }

    /**
     * @return void
     *
     * @covers ::getDetail
     * @covers ::setDetail
     * @covers ::withDetail
     * @covers ::withoutDetail
     */
    public function testDetailAccessors(): void
    {
        $subject = new ErrorObject();

        $this->assertNull($subject->getDetail());

        $subjectClone = $subject->withDetail(
            'An Internal Server Error ocurred'
        );

        $this->assertNotSame($subject, $subjectClone);

        $this->assertSame(
            'An Internal Server Error ocurred',
            $subjectClone->getDetail()
        );

        $subjectClone2 = $subjectClone->withoutDetail();

        $this->assertNotSame($subjectClone, $subjectClone2);
        $this->assertNull($subjectClone2->getDetail());
    }

    /**
     * @return void
     *
     * @covers ::getId
     * @covers ::setId
     * @covers ::withId
     * @covers ::withoutId
     */
    public function testIdAccessors(): void
    {
        $subject = new ErrorObject();

        $this->assertNull($subject->getId());

        $subjectClone = $subject->withId('42');

        $this->assertNotSame($subject, $subjectClone);
        $this->assertSame('42', $subjectClone->getId());

        $subjectClone2 = $subjectClone->withoutId();

        $this->assertNotSame($subjectClone, $subjectClone2);
        $this->assertNull($subjectClone2->getId());
    }

    /**
     * @return void
     *
     * @covers ::getLinks
     * @covers ::setLinks
     * @covers ::withLinks
     * @covers ::withoutLinks
     */
    public function testLinksAccessors(): void
    {
        $subject = new ErrorObject();

        $links = new LinksObject([
            'about' => new LinkObject('http://example.com/error/23'),
        ]);

        $this->assertNull($subject->getLinks());

        $subjectClone = $subject->withLinks($links);

        $this->assertNotSame($subject, $subjectClone);
        $this->assertSame($links, $subjectClone->getLinks());

        $subjectClone2 = $subjectClone->withoutLinks();

        $this->assertNotSame($subjectClone, $subjectClone2);
        $this->assertNull($subjectClone2->getLinks());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing link: about');

        $subjectClone2->withLinks(new LinksObject());
    }

    /**
     * @return void
     *
     * @covers ::getMeta
     * @covers ::setMeta
     * @covers ::withMeta
     * @covers ::withoutMeta
     */
    public function testMetaAccessors(): void
    {
        $meta = new MetaObject(['foo' => 'bar']);
        $subject = new ErrorObject();

        $this->assertNull($subject->getMeta());

        $subjectClone = $subject->withMeta($meta);

        $this->assertNotSame($subject, $subjectClone);
        $this->assertSame($meta, $subjectClone->getMeta());

        $subjectClone2 = $subjectClone->withoutMeta();

        $this->assertNotSame($subjectClone, $subjectClone2);
        $this->assertNull($subjectClone2->getMeta());
    }

    /**
     * @return void
     *
     * @covers ::getSource
     * @covers ::setSource
     * @covers ::withSource
     * @covers ::withoutSource
     */
    public function testSourceAccessors(): void
    {
        $source = new ErrorSourceObject();
        $subject = new ErrorObject();

        $this->assertNull($subject->getSource());

        $subjectClone = $subject->withSource($source);

        $this->assertNotSame($subject, $subjectClone);
        $this->assertSame($source, $subjectClone->getSource());

        $subjectClone2 = $subjectClone->withoutSource();

        $this->assertNotSame($subjectClone, $subjectClone2);
        $this->assertNull($subjectClone2->getSource());
    }

    /**
     * @return void
     *
     * @covers ::getStatus
     * @covers ::setStatus
     * @covers ::withStatus
     * @covers ::withoutStatus
     */
    public function testStatusAccessors(): void
    {
        $subject = new ErrorObject();

        $this->assertNull($subject->getStatus());

        $subjectClone = $subject->withStatus(400);

        $this->assertNotSame($subject, $subjectClone);
        $this->assertSame(400, $subjectClone->getStatus());

        $subjectClone2 = $subjectClone->withoutStatus();

        $this->assertNotSame($subjectClone, $subjectClone2);
        $this->assertNull($subjectClone2->getStatus());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Invalid or non-error indicating HTTP status code: 200'
        );

        $subjectClone2->withStatus(200);
    }

    /**
     * @return void
     *
     * @covers ::getTitle
     * @covers ::setTitle
     * @covers ::withTitle
     * @covers ::withoutTitle
     */
    public function testTitleAccessors(): void
    {
        $subject = new ErrorObject();

        $this->assertNull($subject->getTitle());

        $subjectClone = $subject->withTitle('Internal Server Error');

        $this->assertNotSame($subject, $subjectClone);
        $this->assertSame('Internal Server Error', $subjectClone->getTitle());

        $subjectClone2 = $subjectClone->withoutTitle();

        $this->assertNotSame($subjectClone, $subjectClone2);
        $this->assertNull($subjectClone2->getTitle());
    }

    /**
     * @param ErrorObject $subject
     * @param array $expectedRetVal
     *
     * @return void
     *
     * @covers ::jsonSerialize
     *
     * @dataProvider provideJsonSerializeData
     */
    public function testJsonSerialize(
        ErrorObject $subject,
        array $expectedRetVal
    ): void {
        $actualRetVal = $subject->jsonSerialize();

        $this->assertEquals($expectedRetVal, $actualRetVal);
    }

    /**
     * @return array
     */
    public function provideJsonSerializeData(): array
    {
        return [
            'empty' => [
                new ErrorObject(),
                [],
            ],
            'code' => [
                (new ErrorObject())
                    ->withCode('42'),
                [
                    'code' => '42',
                ],
            ],
            'detail' => [
                (new ErrorObject())
                    ->withDetail('Foo bar'),
                [
                    'detail' => 'Foo bar',
                ],
            ],
            'id' => [
                (new ErrorObject())
                    ->withId('42'),
                [
                    'id' => '42',
                ],
            ],
            'links' => [
                (new ErrorObject())
                    ->withLinks(new LinksObject([
                        'about' => new LinkObject('https://example.com/error'),
                    ])),
                [
                    'links' => [
                        'about' => 'https://example.com/error',
                    ],
                ],
            ],
            'meta' => [
                (new ErrorObject())
                    ->withMeta(new MetaObject([
                        'foo' => 'bar',
                        'fizz' => 'buzz',
                    ])),
                [
                    'meta' => [
                        'foo' => 'bar',
                        'fizz' => 'buzz',
                    ],
                ],
            ],
            'source' => [
                (new ErrorObject())
                    ->withSource(
                        (new ErrorSourceObject())
                            ->withPointer('/data/name')
                    ),
                [
                    'source' => [
                        'pointer' => '/data/name',
                    ],
                ],
            ],
            'status' => [
                (new ErrorObject())
                    ->withStatus(400),
                [
                    'status' => 400,
                ],
            ],
            'title' => [
                (new ErrorObject())
                    ->withTitle('Internal Server Error'),
                [
                    'title' => 'Internal Server Error',
                ],
            ],
        ];
    }
}
