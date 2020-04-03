<?php

/**
 * LinksObjectTest.php
 *
 * @author    michael.lessnau
 * @since     28.03.2020
 */

declare(strict_types=1);

namespace Test\JsonApi\Model;

use InvalidArgumentException;
use JsonApi\Model\LinkObject;
use JsonApi\Model\LinksObject;
use JsonApi\Model\MetaObject;
use PHPUnit\Framework\TestCase;

/**
 * Class LinksObjectTest
 *
 * @coversDefaultClass \JsonApi\Model\LinksObject
 * @uses \JsonApi\Model\LinkObject
 * @uses \JsonApi\Model\LinksObject
 * @uses \JsonApi\Model\MetaObject
 */
class LinksObjectTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::setLink
     * @covers ::setLinks
     * @covers ::getLinks
     */
    public function testConstruct(): void
    {
        $links = [
            'self' => new LinkObject('http://example.com/resources'),
            'next' => new LinkObject('http://example.com/resources?page=2'),
        ];

        $subject = new LinksObject($links);

        $this->assertSame($links, $subject->getLinks());
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::getLink
     * @covers ::setLink
     * @covers ::setLinks
     */
    public function testGetLink(): void
    {
        $link = new LinkObject('https://example.com');
        $subject = new LinksObject(['self' => $link]);

        $this->assertSame($link, $subject->getLink('self'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Undefined link: foo');

        $subject->getLink('foo');
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::hasLink
     * @covers ::setLink
     * @covers ::setLinks
     */
    public function testHasLink(): void
    {
        $link = new LinkObject('https://example.com');
        $subject = new LinksObject(['self' => $link]);

        $this->assertTrue($subject->hasLink('self'));
        $this->assertFalse($subject->hasLink('foo'));
    }

    /**
     * @return void
     *
     * @covers ::jsonSerialize
     */
    public function testJsonSerialize(): void
    {
        $subject = new LinksObject([
            'self' => new LinkObject(
                'http://example.com/resources'
            ),
            'next' => new LinkObject(
                'http://example.com/resources?page=2',
                new MetaObject([
                    'foo' => 'bar',
                ])
            ),
        ]);

        $expectedRetVal = [
            'self' => 'http://example.com/resources',
            'next' => [
                'href' => 'http://example.com/resources?page=2',
                'meta' => [
                    'foo' => 'bar',
                ],
            ],
        ];

        $actualRetVal = $subject->jsonSerialize();

        $this->assertSame($expectedRetVal, $actualRetVal);
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::hasLink
     * @covers ::setLink
     * @covers ::setLinks
     * @covers ::withLink
     */
    public function testWithLink(): void
    {
        $subject = new LinksObject([
            'self' => new LinkObject('http://example.com'),
        ]);

        $link = new LinkObject('http://example.com');
        $subjectClone = $subject->withLink('other', $link);

        $this->assertNotSame($subject, $subjectClone);
        $this->assertFalse($subject->hasLink('other'));
        $this->assertSame($link, $subjectClone->getLink('other'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid link name: foo & bar');

        $subjectClone->withLink('foo & bar', $link);
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::hasLink
     * @covers ::setLink
     * @covers ::setLinks
     * @covers ::withLinks
     */
    public function testWithLinks(): void
    {
        $subject = new LinksObject([
            'self' => new LinkObject('http://example.com'),
        ]);

        $links = [
            'other' => new LinkObject('http://example.com'),
        ];

        $subjectClone = $subject->withLinks($links);

        $this->assertNotSame($subject, $subjectClone);
        $this->assertFalse($subject->hasLink('other'));
        $this->assertSame($links['other'], $subjectClone->getLink('other'));
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::setLink
     * @covers ::setLinks
     * @covers ::getLinks
     * @covers ::unsetLink
     * @covers ::withoutLink
     */
    public function testWithoutLink(): void
    {
        $subject = new LinksObject([
            'self' => new LinkObject('http://example.com'),
        ]);

        $subjectClone = $subject->withoutLink('self');

        $this->assertNotSame($subject, $subjectClone);
        $this->assertTrue($subject->hasLink('self'));
        $this->assertFalse($subjectClone->hasLink('self'));
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::setLink
     * @covers ::setLinks
     * @covers ::getLinks
     * @covers ::unsetLinks
     * @covers ::withoutLinks
     */
    public function testWithoutLinks(): void
    {
        $subject = new LinksObject([
            'self' => new LinkObject('http://example.com'),
            'next' => new LinkObject('http://example.com?page=2'),
        ]);

        $subjectClone = $subject->withoutLinks();

        $this->assertNotSame($subject, $subjectClone);
        $this->assertTrue($subject->hasLink('self'));
        $this->assertTrue($subject->hasLink('next'));
        $this->assertFalse($subjectClone->hasLink('self'));
        $this->assertFalse($subjectClone->hasLink('next'));
    }
}
