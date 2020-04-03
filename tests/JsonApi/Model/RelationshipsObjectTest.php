<?php

/**
 * RelationshipsObjectTest.php
 *
 * @author    michael.lessnau
 * @since     29.03.2020
 */

declare(strict_types=1);

namespace Test\JsonApi\Model;

use InvalidArgumentException;
use JsonApi\Model\MetaObject;
use JsonApi\Model\RelationshipsObject;
use JsonApi\Model\ResourceIdentifierObject;
use JsonApi\Model\ResourceIdentifierObjectCollection;
use JsonApi\Model\ToManyRelationshipObject;
use JsonApi\Model\ToOneRelationshipObject;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Class RelationshipsObjectTest
 *
 * @coversDefaultClass \JsonApi\Model\RelationshipsObject
 * @uses \JsonApi\Model\Collection
 * @uses \JsonApi\Model\MetaObject
 * @uses \JsonApi\Model\RelationshipObjectTrait
 * @uses \JsonApi\Model\RelationshipsObject
 * @uses \JsonApi\Model\ResourceIdentifierObject
 * @uses \JsonApi\Model\ResourceIdentifierObjectCollection
 * @uses \JsonApi\Model\ToManyRelationshipObject
 * @uses \JsonApi\Model\ToOneRelationshipObject
 */
class RelationshipsObjectTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::getFields
     * @covers ::setField
     * @covers ::setFields
     */
    public function testConstruct(): void
    {
        $fields = [
            'author' => new ToOneRelationshipObject(),
            'comments' => new ToManyRelationshipObject(),
        ];

        $subject = new RelationshipsObject($fields);

        $this->assertSame($fields, $subject->getFields());
    }

    /**
     * @param string $name
     *
     * @return void
     *
     * @covers ::__construct
     * @covers ::setField
     * @covers ::setFields
     *
     * @testWith ["id"]
     *           ["type"]
     */
    public function testReservedFieldNames(string $name): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Reserved field name: ' . $name);

        $subject = new RelationshipsObject([
            $name => new ToOneRelationshipObject(),
        ]);
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::getField
     * @covers ::setField
     * @covers ::setFields
     */
    public function testGetField(): void
    {
        $fields = [
            'author' => new ToOneRelationshipObject(),
            'comments' => new ToManyRelationshipObject(),
        ];

        $subject = new RelationshipsObject($fields);

        $this->assertSame($fields['author'], $subject->getField('author'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Undefined field: foo');

        $subject->getField('foo');
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::getFieldNames
     * @covers ::setFields
     */
    public function testGetFieldNames(): void
    {
        $fields = [
            'author' => new ToOneRelationshipObject(),
            'comments' => new ToManyRelationshipObject(),
        ];

        $subject = new RelationshipsObject($fields);

        $this->assertSame(array_keys($fields), $subject->getFieldNames());
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::hasField
     * @covers ::setField
     * @covers ::setFields
     */
    public function testHasField(): void
    {
        $fields = [
            'author' => new ToOneRelationshipObject(),
            'comments' => new ToManyRelationshipObject(),
        ];

        $subject = new RelationshipsObject($fields);

        $this->assertTrue($subject->hasField('author'));
        $this->assertFalse($subject->hasField('foo'));
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::setField
     * @covers ::setFields
     * @covers ::jsonSerialize
     */
    public function testJsonSerialize(): void
    {
        $fields = [];

        $fields['author'] = (new ToOneRelationshipObject())
            ->withMeta(new MetaObject([
                'foo' => 'bar',
            ]));

        $fields['comments'] = (new ToManyRelationshipObject())
            ->withData(new ResourceIdentifierObjectCollection([
                new ResourceIdentifierObject('people', '42'),
                new ResourceIdentifierObject('people', '1337'),
            ]));

        $subject = new RelationshipsObject($fields);

        $expectedRetVal = [
            'author' => [
                'meta' => [
                    'foo' => 'bar'
                ],
                'data' => null,
            ],
            'comments' => [
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
        ];

        $this->assertSame($expectedRetVal, $subject->jsonSerialize());
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::hasField
     * @covers ::setField
     * @covers ::setFields
     * @covers ::withField
     */
    public function testWithField(): void
    {
        $subject = new RelationshipsObject([
            'author' => new ToOneRelationshipObject(),
        ]);

        $subjectClone = $subject->withField(
            'comments',
            new ToManyRelationshipObject()
        );

        $this->assertNotSame($subject, $subjectClone);
        $this->assertFalse($subject->hasField('comments'));
        $this->assertTrue($subjectClone->hasField('comments'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid field name: foo & bar');

        $subject->withField(
            'foo & bar',
            new ToManyRelationshipObject()
        );
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::hasField
     * @covers ::setField
     * @covers ::setFields
     * @covers ::withFields
     */
    public function testWithFields(): void
    {
        $subject = new RelationshipsObject([]);

        $subjectClone = $subject->withFields([
            'author' => new ToOneRelationshipObject(),
            'comments' => new ToManyRelationshipObject(),
        ]);

        $this->assertNotSame($subject, $subjectClone);
        $this->assertFalse($subject->hasField('author'));
        $this->assertFalse($subject->hasField('comments'));
        $this->assertTrue($subjectClone->hasField('author'));
        $this->assertTrue($subjectClone->hasField('comments'));
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::hasField
     * @covers ::setField
     * @covers ::setFields
     * @covers ::unsetField
     * @covers ::withoutField
     */
    public function testWithoutField(): void
    {
        $subject = new RelationshipsObject([
            'author' => new ToOneRelationshipObject(),
        ]);

        $subjectClone = $subject->withoutField('author');

        $this->assertNotSame($subject, $subjectClone);
        $this->assertTrue($subject->hasField('author'));
        $this->assertFalse($subjectClone->hasField('author'));
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::hasField
     * @covers ::setField
     * @covers ::setFields
     * @covers ::unsetFields
     * @covers ::withoutFields
     */
    public function testWithoutFields(): void
    {
        $subject = new RelationshipsObject([
            'author' => new ToOneRelationshipObject(),
            'comments' => new ToManyRelationshipObject(),
        ]);

        $subjectClone = $subject->withoutFields();

        $this->assertNotSame($subject, $subjectClone);
        $this->assertTrue($subject->hasField('author'));
        $this->assertTrue($subject->hasField('comments'));
        $this->assertFalse($subjectClone->hasField('author'));
        $this->assertFalse($subjectClone->hasField('comments'));
    }
}
