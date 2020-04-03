<?php

/**
 * ToOneRelationshipObjectTest.php
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
use JsonApi\Model\ResourceIdentifierObject;
use JsonApi\Model\ToOneRelationshipObject;
use PHPUnit\Framework\TestCase;

/**
 * Class ToOneRelationshipObjectTest
 *
 * @coversDefaultClass \JsonApi\Model\ToOneRelationshipObject
 * @uses \JsonApi\Model\LinkObject
 * @uses \JsonApi\Model\LinksObject
 * @uses \JsonApi\Model\MetaObject
 * @uses \JsonApi\Model\RelationshipObjectTrait
 * @uses \JsonApi\Model\ResourceIdentifierObject
 * @uses \JsonApi\Model\ToOneRelationshipObject
 */
class ToOneRelationshipObjectTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::getData
     * @covers ::setData
     * @covers ::withData
     * @covers ::withoutData
     */
    public function testDataAccessors(): void
    {
        $data = new ResourceIdentifierObject('people', '42');
        $subject = new ToOneRelationshipObject();

        $this->assertNull($subject->getData());

        $subjectClone = $subject->withData($data);

        $this->assertNotSame($subject, $subjectClone);
        $this->assertSame($data, $subjectClone->getData());

        $subjectClone2 = $subjectClone->withoutData();

        $this->assertNotSame($subjectClone, $subjectClone2);
        $this->assertNull($subjectClone2->getData());
    }

    /**
     * @return void
     *
     * @covers \JsonApi\Model\RelationshipObjectTrait::getLinks
     * @covers \JsonApi\Model\RelationshipObjectTrait::setLinks
     * @covers \JsonApi\Model\RelationshipObjectTrait::withLinks
     * @covers \JsonApi\Model\RelationshipObjectTrait::withoutLinks
     */
    public function testLinksAccessors(): void
    {
        $links = new LinksObject([
            'self' => new LinkObject('https://example.com'),
        ]);
        $subject = new ToOneRelationshipObject();

        $this->assertNull($subject->getLinks());

        $subjectClone = $subject->withLinks($links);

        $this->assertNotSame($subject, $subjectClone);
        $this->assertSame($links, $subjectClone->getLinks());

        $subjectClone2 = $subjectClone->withoutLinks();

        $this->assertNotSame($subjectClone, $subjectClone2);
        $this->assertNull($subjectClone2->getLinks());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing link: self or related');

        $subjectClone2->withLinks(new LinksObject());
    }

    /**
     * @return void
     *
     * @covers \JsonApi\Model\RelationshipObjectTrait::getMeta
     * @covers \JsonApi\Model\RelationshipObjectTrait::setMeta
     * @covers \JsonApi\Model\RelationshipObjectTrait::withMeta
     * @covers \JsonApi\Model\RelationshipObjectTrait::withoutMeta
     */
    public function testMetaAccessors(): void
    {
        $meta = new MetaObject(['foo' => 'bar']);
        $subject = new ToOneRelationshipObject();

        $this->assertNull($subject->getMeta());

        $subjectClone = $subject->withMeta($meta);

        $this->assertNotSame($subject, $subjectClone);
        $this->assertSame($meta, $subjectClone->getMeta());

        $subjectClone2 = $subjectClone->withoutMeta();

        $this->assertNotSame($subjectClone, $subjectClone2);
        $this->assertNull($subjectClone2->getMeta());
    }

    /**
     * @param ToOneRelationshipObject $subject
     * @param array $expectedRetVal
     *
     * @return void
     *
     * @covers ::jsonSerialize
     *
     * @dataProvider provideJsonSerializeData
     */
    public function testJsonSerialize(
        ToOneRelationshipObject $subject,
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
                new ToOneRelationshipObject(),
                [
                    'data' => null,
                ],
            ],
            'data' => [
                (new ToOneRelationshipObject())
                    ->withData(new ResourceIdentifierObject('people', '42')),
                [
                    'data' => [
                        'type' => 'people',
                        'id' => '42',
                    ],
                ],
            ],
            'links' => [
                (new ToOneRelationshipObject())
                    ->withLinks(new LinksObject([
                        'self' => new LinkObject('https://example.com'),
                    ])),
                [
                    'links' => [
                        'self' => 'https://example.com',
                    ],
                    'data' => null,
                ],
            ],
            'meta' => [
                (new ToOneRelationshipObject())
                    ->withMeta(new MetaObject([
                        'foo' => 'bar',
                    ])),
                [
                    'meta' => [
                        'foo' => 'bar',
                    ],
                    'data' => null,
                ],
            ],
        ];
    }
}
