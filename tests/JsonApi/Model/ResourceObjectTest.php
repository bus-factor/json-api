<?php

/**
 * ResourceObjectTest.php
 *
 * Author: Michael Leßnau <michael.lessnau@gmail.com>
 * Date:   2020-04-04
 */

declare(strict_types=1);

namespace Test\JsonApi\Model;

use InvalidArgumentException;
use JsonApi\Model\AttributesObject;
use JsonApi\Model\LinkObject;
use JsonApi\Model\LinksObject;
use JsonApi\Model\MetaObject;
use JsonApi\Model\RelationshipsObject;
use JsonApi\Model\ResourceObject;
use JsonApi\Model\ToOneRelationshipObject;
use PHPUnit\Framework\TestCase;

/**
 * Class ResourceObjectTest
 *
 * @coversDefaultClass \JsonApi\Model\ResourceObject
 * @uses \JsonApi\Model\AttributesObject
 * @uses \JsonApi\Model\LinkObject
 * @uses \JsonApi\Model\LinksObject
 * @uses \JsonApi\Model\MetaObject
 * @uses \JsonApi\Model\RelationshipsObject
 * @uses \JsonApi\Model\ToOneRelationshipObject
 */
class ResourceObjectTest extends TestCase
{
    /**
     * @param string $type
     * @param string|null $id
     * @param string|null $exception
     * @param string|null $exceptionMessage
     *
     * @return void
     *
     * @covers ::__construct
     * @covers ::getId
     * @covers ::getType
     * @covers ::setId
     * @covers ::setType
     *
     * @testWith ["people", null]
     *           ["people", "42"]
     *           ["#people", null, "InvalidArgumentException", "Invalid type name: #people"]
     */
    public function testConstruct(
        string $type,
        ?string $id,
        ?string $exception = null,
        ?string $exceptionMessage = null
    ): void {
        if (isset($exception)) {
            $this->expectException($exception);

            if (isset($exceptionMessage)) {
                $this->expectExceptionMessage($exceptionMessage);
            }
        }

        $subject = new ResourceObject($type, $id);

        if (!isset($exception)) {
            $this->assertSame($type, $subject->getType());
            $this->assertSame($id, $subject->getId());
        }
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::getAttributes
     * @covers ::setAttributes
     * @covers ::setId
     * @covers ::setType
     * @covers ::withAttributes
     * @covers ::withoutAttributes
     */
    public function testAttributesAccessors(): void
    {
        $attributes = new AttributesObject(['foo' => 'bar']);
        $subject = new ResourceObject('people', '42');

        $this->assertNull($subject->getAttributes());

        $subjectClone = $subject->withAttributes($attributes);

        $this->assertNull($subject->getAttributes());
        $this->assertNotSame($subject, $subjectClone);
        $this->assertSame($attributes, $subjectClone->getAttributes());

        $subjectClone2 = $subjectClone->withoutAttributes();

        $this->assertNull($subject->getAttributes());
        $this->assertSame($attributes, $subjectClone->getAttributes());
        $this->assertNull($subjectClone2->getAttributes());
        $this->assertNotSame($subject, $subjectClone2);
        $this->assertNotSame($subjectClone, $subjectClone2);
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::getAttributes
     * @covers ::getRelationships
     * @covers ::setAttributes
     * @covers ::setId
     * @covers ::setRelationships
     * @covers ::setType
     * @covers ::withAttributes
     * @covers ::withRelationships
     */
    public function testWithAttributesPreventsFieldDuplication(): void
    {
        try {
            $subject = (new ResourceObject('people', '42'))
                ->withRelationships(new RelationshipsObject(['b' => new ToOneRelationshipObject()]))
                ->withAttributes(new AttributesObject(['a' => 'bar']));
        } catch (\Throwable $exc) {
            $this->fail();
        }

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Field names must not appear as attributes and relationships: foo');

        (new ResourceObject('people', '42'))
            ->withRelationships(new RelationshipsObject(['foo' => new ToOneRelationshipObject()]))
            ->withAttributes(new AttributesObject(['foo' => 'bar']));
    }

    /**
     * @param ResourceObject $subject*
     * @param array $expectedRetVal
     *
     * @return void
     *
     * @covers ::__construct
     * @sovers ::setType
     * @covers ::setId
     * @covers ::jsonSerialize
     *
     * @dataProvider provideJsonSerializeData
     */
    public function testJsonSerialize(ResourceObject $subject, array $expectedRetVal): void
    {
        $actualRetVal = $subject->jsonSerialize();

        $this->assertSame($expectedRetVal, $actualRetVal);
    }

    /**
     * @return array
     */
    public function provideJsonSerializeData(): array
    {
        return [
            'type, id' => [
                new ResourceObject('people', '42'),
                [
                    'type' => 'people',
                    'id' => '42',
                ],
            ],
            'attributes' => [
                (new ResourceObject('people', '42'))
                    ->withAttributes(new AttributesObject(['name' => 'John Doe'])),
                [
                    'type' => 'people',
                    'id' => '42',
                    'attributes' => [
                        'name' => 'John Doe',
                    ],
                ],
            ],
            'links' => [
                (new ResourceObject('people', '42'))
                    ->withLinks(new LinksObject(['self' => new LinkObject('https://example.com')])),
                [
                    'type' => 'people',
                    'id' => '42',
                    'links' => [
                        'self' => 'https://example.com',
                    ],
                ],
            ],
            'meta' => [
                (new ResourceObject('people', '42'))
                    ->withMeta(new MetaObject(['foo' => 'bar'])),
                [
                    'type' => 'people',
                    'id' => '42',
                    'meta' => [
                        'foo' => 'bar',
                    ],
                ],
            ],
            'relationships' => [
                (new ResourceObject('people', '42'))
                    ->withRelationships(new RelationshipsObject([
                        'foo' => new ToOneRelationshipObject(),
                    ])),
                [
                    'type' => 'people',
                    'id' => '42',
                    'relationships' => [
                        'foo' => [
                            'data' => null,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::getLinks
     * @covers ::setLinks
     * @covers ::setId
     * @covers ::setType
     * @covers ::withLinks
     * @covers ::withoutLinks
     */
    public function testLinksAccessors(): void
    {
        $links = new LinksObject();
        $subject = new ResourceObject('people', '42');

        $this->assertNull($subject->getLinks());

        $subjectClone = $subject->withLinks($links);

        $this->assertNull($subject->getLinks());
        $this->assertNotSame($subject, $subjectClone);
        $this->assertSame($links, $subjectClone->getLinks());

        $subjectClone2 = $subjectClone->withoutLinks();

        $this->assertNull($subject->getLinks());
        $this->assertSame($links, $subjectClone->getLinks());
        $this->assertNull($subjectClone2->getLinks());
        $this->assertNotSame($subject, $subjectClone2);
        $this->assertNotSame($subjectClone, $subjectClone2);
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::getMeta
     * @covers ::setMeta
     * @covers ::setId
     * @covers ::setType
     * @covers ::withMeta
     * @covers ::withoutMeta
     */
    public function testMetaAccessors(): void
    {
        $meta = new MetaObject();
        $subject = new ResourceObject('people', '42');

        $this->assertNull($subject->getMeta());

        $subjectClone = $subject->withMeta($meta);

        $this->assertNull($subject->getMeta());
        $this->assertNotSame($subject, $subjectClone);
        $this->assertSame($meta, $subjectClone->getMeta());

        $subjectClone2 = $subjectClone->withoutMeta();

        $this->assertNull($subject->getMeta());
        $this->assertSame($meta, $subjectClone->getMeta());
        $this->assertNull($subjectClone2->getMeta());
        $this->assertNotSame($subject, $subjectClone2);
        $this->assertNotSame($subjectClone, $subjectClone2);
    }
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::getRelationships
     * @covers ::setRelationships
     * @covers ::setId
     * @covers ::setType
     * @covers ::withRelationships
     * @covers ::withoutRelationships
     */
    public function testRelationshipsAccessors(): void
    {
        $relationships = new RelationshipsObject();
        $subject = new ResourceObject('people', '42');

        $this->assertNull($subject->getRelationships());

        $subjectClone = $subject->withRelationships($relationships);

        $this->assertNull($subject->getRelationships());
        $this->assertNotSame($subject, $subjectClone);
        $this->assertSame($relationships, $subjectClone->getRelationships());

        $subjectClone2 = $subjectClone->withoutRelationships();

        $this->assertNull($subject->getRelationships());
        $this->assertSame($relationships, $subjectClone->getRelationships());
        $this->assertNull($subjectClone2->getRelationships());
        $this->assertNotSame($subject, $subjectClone2);
        $this->assertNotSame($subjectClone, $subjectClone2);
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::getAttributes
     * @covers ::getRelationships
     * @covers ::setAttributes
     * @covers ::setId
     * @covers ::setRelationships
     * @covers ::setType
     * @covers ::withAttributes
     * @covers ::withRelationships
     */
    public function testWithRelationshipsPreventsFieldDuplication(): void
    {
        try {
            $subject = (new ResourceObject('people', '42'))
                ->withAttributes(new AttributesObject(['a' => 'bar']))
                ->withRelationships(new RelationshipsObject(['b' => new ToOneRelationshipObject()]));
        } catch (\Throwable $exc) {
            $this->fail();
        }

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Field names must not appear as attributes and relationships: foo');

        (new ResourceObject('people', '42'))
            ->withAttributes(new AttributesObject(['foo' => 'bar']))
            ->withRelationships(new RelationshipsObject(['foo' => new ToOneRelationshipObject()]));
    }
}
