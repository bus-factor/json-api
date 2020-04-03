<?php

/**
 * AttributesObjectTest.php
 *
 * @author    michael.lessnau
 * @since     27.03.2020
 */

declare(strict_types=1);

namespace Test\JsonApi\Model;

use InvalidArgumentException;
use JsonApi\Model\AttributesObject;
use PHPUnit\Framework\TestCase;

/**
 * Class AttributesObjectTest
 *
 * @coversDefaultClass \JsonApi\Model\AttributesObject
 * @uses \JsonApi\Model\AttributesObject
 */
class AttributesObjectTest extends TestCase
{
    /**
     * @param array $fields
     *
     * @return void
     *
     * @covers ::__construct
     * @covers ::getFields
     * @covers ::setField
     * @covers ::setFields
     *
     * @dataProvider provideConstructData
     */
    public function testConstruct(
        array $fields,
        ?string $exception = null,
        ?string $exceptionMessage = null
    ): void {
        if (isset($exception)) {
            $this->expectException($exception);

            if (isset($exceptionMessage)) {
                $this->expectExceptionMessage($exceptionMessage);
            }
        }

        $subject = new AttributesObject($fields);

        if (!isset($exception)) {
            $this->assertEquals($fields, $subject->getFields());
        }
    }

    /**
     * @return array
     */
    public function provideConstructData(): array
    {
        return [
            'no fields' => [
                [],
            ],
            'valid fields' => [
                [
                    'foo' => 'bar',
                    'fizz' => 'buzz',
                ],
            ],
            'invalid fields' => [
                [
                    'foo & bar' => 'fizz & buzz',
                ],
                InvalidArgumentException::class,
                'Invalid field name: foo & bar',
            ],
            'reserved name: type' => [
                [
                    'type' => 'some type',
                ],
                InvalidArgumentException::class,
                'Reserved field name: type',
            ],
            'reserved name: id' => [
                [
                    'id' => '12345',
                ],
                InvalidArgumentException::class,
                'Reserved field name: id',
            ],
        ];
    }

    /**
     * @return void
     *
     * @covers ::getField
     */
    public function testGetField(): void
    {
        $subject = new AttributesObject([
            'foo' => 'bar',
        ]);

        $this->assertSame('bar', $subject->getField('foo'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Undefined field: fizz');

        $subject->getField('fizz');
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::getFields
     * @covers ::setField
     * @covers ::setFields
     */
    public function testGetFields(): void
    {
        $fields = [
            'a' => 42,
            'b' => 1337,
        ];

        $subject = new AttributesObject($fields);

        $this->assertEquals($fields, $subject->getFields());
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::getFieldNames
     * @covers ::setField
     * @covers ::setFields
     */
    public function testGetFieldNames(): void
    {
        $fields = [
            'a' => 42,
            'b' => 1337,
        ];

        $subject = new AttributesObject($fields);

        $this->assertEquals(array_keys($fields), $subject->getFieldNames());
    }

    /**
     * @return void
     *
     * @covers ::jsonSerialize
     */
    public function testJsonSerialize(): void
    {
        $fields = [
            'foo' => 'bar',
            'fizz' => 'buzz',
        ];

        $subject = new AttributesObject($fields);

        $this->assertEquals($fields, $subject->jsonSerialize());
    }

    /**
     * @return void
     *
     * @covers ::hasField
     */
    public function testHasField(): void
    {
        $subject = new AttributesObject([
            'foo' => 'bar',
        ]);

        $this->assertTrue($subject->hasField('foo'));
        $this->assertFalse($subject->hasField('fizz'));
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
        $subject = new AttributesObject([
            'a' => 42,
            'b' => 1337,
        ]);

        $subjectClone = $subject->withField('c', 23);

        $this->assertNotSame($subject, $subjectClone);
        $this->assertFalse($subject->hasField('c'));
        $this->assertTrue($subjectClone->hasField('c'));
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
        $subject = new AttributesObject([
            'a' => 42,
        ]);

        $subjectClone = $subject->withFields([
            'b' => 1337,
            'c' => 23,
        ]);

        $this->assertNotSame($subject, $subjectClone);
        $this->assertFalse($subject->hasField('b'));
        $this->assertFalse($subject->hasField('c'));
        $this->assertTrue($subjectClone->hasField('b'));
        $this->assertTrue($subjectClone->hasField('c'));
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
        $subject = new AttributesObject([
            'a' => 42,
        ]);

        $subjectClone = $subject->withoutField('a');

        $this->assertNotSame($subject, $subjectClone);
        $this->assertTrue($subject->hasField('a'));
        $this->assertFalse($subjectClone->hasField('a'));
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::getFields
     * @covers ::hasField
     * @covers ::setField
     * @covers ::setFields
     * @covers ::unsetFields
     * @covers ::withoutFields
     */
    public function testWithoutFields(): void
    {
        $subject = new AttributesObject([
            'a' => 42,
            'b' => 1337,
        ]);

        $subjectClone = $subject->withoutFields();

        $this->assertNotSame($subject, $subjectClone);
        $this->assertTrue($subject->hasField('a'));
        $this->assertTrue($subject->hasField('b'));
        $this->assertEquals([], $subjectClone->getFields());
    }
}
