<?php

/**
 * ResourceCollectionDocumentTest.php
 *
 * Author: Michael Leßnau <michael.lessnau@gmail.com>
 * Date:   2020-04-05
 */

declare(strict_types=1);

namespace Test\JsonApi\Model;

use JsonApi\Model\AttributesObject;
use JsonApi\Model\MetaObject;
use JsonApi\Model\RelationshipsObject;
use JsonApi\Model\ResourceCollectionDocument;
use JsonApi\Model\ResourceIdentifierObject;
use JsonApi\Model\ResourceIdentifierObjectCollection;
use JsonApi\Model\ResourceObject;
use JsonApi\Model\ResourceObjectCollection;
use JsonApi\Model\ToManyRelationshipObject;
use JsonApi\Model\ToOneRelationshipObject;
use PHPUnit\Framework\TestCase;

/**
 * Class ResourceCollectionDocumentTest
 *
 * @coversDefaultClass \JsonApi\Model\ResourceCollectionDocument
 * @uses \JsonApi\Model\AbstractDocument
 * @uses \JsonApi\Model\AttributesObject
 * @uses \JsonApi\Model\Collection
 * @uses \JsonApi\Model\MetaObject
 * @uses \JsonApi\Model\RelationshipsObject
 * @uses \JsonApi\Model\ResourceIdentifierObject
 * @uses \JsonApi\Model\ResourceIdentifierObjectCollection
 * @uses \JsonApi\Model\ResourceObject
 * @uses \JsonApi\Model\ResourceObjectCollection
 * @uses \JsonApi\Model\ToManyRelationshipObject
 * @uses \JsonApi\Model\ToOneRelationshipObject
 */
class ResourceCollectionDocumentTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::getData
     * @covers ::setData
     * @covers ::withData
     * @covers ::withoutData
     */
    public function testDataAccessors(): void
    {
        $data = new ResourceObjectCollection([
            new ResourceObject('people', '23'),
            new ResourceObject('people', '42'),
        ]);

        $subject = new ResourceCollectionDocument($data);

        $otherData = new ResourceObjectCollection([
            new ResourceObject('people', '17')
        ]);

        $subjectClone = $subject->withData($otherData);

        $this->assertNotSame($subject, $subjectClone);
        $this->assertSame($data, $subject->getData());
        $this->assertSame($otherData, $subjectClone->getData());

        $otherSubjectClone = $subjectClone->withoutData();

        $this->assertNotSame($subjectClone, $otherSubjectClone);
        $this->assertSame($otherData, $subjectClone->getData());
        $this->assertNull($otherSubjectClone->getData());
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::getIncluded
     * @covers ::setIncluded
     * @covers ::withIncluded
     * @covers ::withoutIncluded
     */
    public function testIncludedAccessors(): void
    {
        $data = new ResourceObjectCollection([
            new ResourceObject('people', '23'),
            new ResourceObject('people', '42'),
        ]);

        $subject = new ResourceCollectionDocument($data);

        $included = new ResourceObjectCollection();
        $subjectClone = $subject->withIncluded($included);

        $this->assertNotSame($subject, $subjectClone);
        $this->assertNull($subject->getIncluded());
        $this->assertSame($included, $subjectClone->getIncluded());

        $otherSubjectClone = $subjectClone->withoutIncluded();

        $this->assertNotSame($subjectClone, $otherSubjectClone);
        $this->assertSame($included, $subjectClone->getIncluded());
        $this->assertNull($otherSubjectClone->getIncluded());
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::getMeta
     * @covers ::setMeta
     * @covers ::withMeta
     * @covers ::withoutMeta
     */
    public function testMetaAccessors(): void
    {
        $data = new ResourceObjectCollection([
            new ResourceObject('people', '23'),
            new ResourceObject('people', '42'),
        ]);

        $subject = new ResourceCollectionDocument($data);

        $meta = new MetaObject(['foo' => 'bar']);
        $subjectClone = $subject->withMeta($meta);

        $this->assertNotSame($subject, $subjectClone);
        $this->assertNull($subject->getMeta());
        $this->assertSame($meta, $subjectClone->getMeta());

        $otherSubjectClone = $subjectClone->withoutMeta();

        $this->assertNotSame($subjectClone, $otherSubjectClone);
        $this->assertSame($meta, $subjectClone->getMeta());
        $this->assertNull($otherSubjectClone->getMeta());
    }

    /**
     * @param ResourceCollectionDocument $subject
     * @param array $expectedRetVal
     *
     * @return void
     *
     * @covers ::jsonSerialize
     *
     * @dataProvider provideJsonSerializeData
     */
    public function testJsonSerialize(ResourceCollectionDocument $subject, array $expectedRetVal): void
    {
        $actualRetVal = $subject->jsonSerialize();

        $this->assertEquals($expectedRetVal, $actualRetVal);
    }

    /**
     * @return array
     */
    public function provideJsonSerializeData(): array
    {
        return [
            'data' => [
                new ResourceCollectionDocument(
                    new ResourceObjectCollection([
                        new ResourceObject('people', '42'),
                    ])
                ),
                [
                    'data' => [
                        [
                            'type' => 'people',
                            'id' => '42',
                        ],
                    ],
                ],
            ],
            'no data' => [
                new ResourceCollectionDocument(null),
                [
                    'data' => [],
                ],
            ],
            'meta' => [
                (new ResourceCollectionDocument(null))
                    ->withMeta(new MetaObject(['foo' => 'bar'])),
                [
                    'data' => [],
                    'meta' => [
                        'foo' => 'bar',
                    ],
                ],
            ],
            'included' => [
                (new ResourceCollectionDocument(new ResourceObjectCollection([
                    (new ResourceObject('people', '42'))
                        ->withRelationships(new RelationshipsObject([
                            'comments' => (new ToManyRelationshipObject())
                                ->withData(new ResourceIdentifierObjectCollection([
                                    new ResourceIdentifierObject('comments', '23'),
                                ])),
                        ]))
                ])))
                    ->withIncluded(new ResourceObjectCollection([
                        (new ResourceObject('comments', '23'))
                            ->withAttributes(new AttributesObject([
                                'title' => 'Some comment...',
                            ]))
                            ->withRelationships(new RelationshipsObject([
                                'author' => (new ToOneRelationshipObject())
                                    ->withData(new ResourceIdentifierObject('people', '42')),
                            ]))
                    ])),
                [
                    'data' => [
                        [
                            'type' => 'people',
                            'id' => '42',
                            'relationships' => [
                                'comments' => [
                                    'data' => [
                                        [
                                            'type' => 'comments',
                                            'id' => '23',
                                        ],
                                    ],
                                ]
                            ],
                        ],
                    ],
                    'included' => [
                        [
                            'type' => 'comments',
                            'id' => '23',
                            'attributes' => [
                                'title' => 'Some comment...',
                            ],
                            'relationships' => [
                                'author' => [
                                    'data' => [
                                        'type' => 'people',
                                        'id' => '42',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

}