<?php

/**
 * ToManyRelationshipObjectTest.php
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
use JsonApi\Model\ResourceIdentifierObjectCollection;
use JsonApi\Model\ToManyRelationshipObject;
use PHPUnit\Framework\TestCase;

/**
 * Class ToManyRelationshipObjectTest
 *
 * @coversDefaultClass \JsonApi\Model\ToManyRelationshipObject
 * @uses \JsonApi\Model\AbstractObject
 * @uses \JsonApi\Model\LinkObject
 * @uses \JsonApi\Model\LinksObject
 * @uses \JsonApi\Model\MetaObject
 * @uses \JsonApi\Model\ResourceIdentifierObject
 * @uses \JsonApi\Model\ResourceIdentifierObjectCollection
 * @uses \JsonApi\Model\ToManyRelationshipObject
 */
class ToManyRelationshipObjectTest extends TestCase
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
        $data = new ResourceIdentifierObjectCollection([]);
        $subject = new ToManyRelationshipObject();

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
        $subject = new ToManyRelationshipObject();

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
        $subject = new ToManyRelationshipObject();

        $this->assertNull($subject->getMeta());

        $subjectClone = $subject->withMeta($meta);

        $this->assertNotSame($subject, $subjectClone);
        $this->assertSame($meta, $subjectClone->getMeta());

        $subjectClone2 = $subjectClone->withoutMeta();

        $this->assertNotSame($subjectClone, $subjectClone2);
        $this->assertNull($subjectClone2->getMeta());
    }

    /**
     * @param ToManyRelationshipObject $subject
     * @param array $expectedRetVal
     *
     * @return void
     *
     * @covers ::jsonSerialize
     *
     * @dataProvider provideJsonSerializeData
     */
    public function testJsonSerialize(
        ToManyRelationshipObject $subject,
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
                new ToManyRelationshipObject(),
                [
                    'data' => [],
                ],
            ],
            'data' => [
                (new ToManyRelationshipObject())
                    ->withData(new ResourceIdentifierObjectCollection([
                        new ResourceIdentifierObject('people', '42'),
                        new ResourceIdentifierObject('people', '1337'),
                    ])),
                [
                    'data' => [
                        [
                            'type' => 'people',
                            'id' => '42',
                        ],
                        [
                            'type' => 'people',
                            'id' => '1337',
                        ],
                    ],
                ],
            ],
            'links' => [
                (new ToManyRelationshipObject())
                    ->withLinks(new LinksObject([
                        'self' => new LinkObject('https://example.com'),
                    ])),
                [
                    'links' => [
                        'self' => 'https://example.com',
                    ],
                    'data' => [],
                ],
            ],
            'meta' => [
                (new ToManyRelationshipObject())
                    ->withMeta(new MetaObject([
                        'foo' => 'bar',
                    ])),
                [
                    'meta' => [
                        'foo' => 'bar',
                    ],
                    'data' => [],
                ],
            ],
        ];
    }
}
