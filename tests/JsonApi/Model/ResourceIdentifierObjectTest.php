<?php

/**
 * ResourceIdentifierObjectTest.php
 *
 * @author    michael.lessnau
 * @since     29.03.2020
 */

declare(strict_types=1);

namespace Test\JsonApi\Model;

use JsonApi\Model\MetaObject;
use JsonApi\Model\ResourceIdentifierObject;
use PHPUnit\Framework\TestCase;

/**
 * Class ResourceIdentifierObjectTest
 *
 * @coversDefaultClass \JsonApi\Model\ResourceIdentifierObject
 * @uses \JsonApi\Model\MetaObject
 * @uses \JsonApi\Model\ResourceIdentifierObject
 */
class ResourceIdentifierObjectTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::getId
     * @covers ::getMeta
     * @covers ::getType
     * @covers ::setId
     * @covers ::setType
     */
    public function testConstruct(): void
    {
        $type = 'people';
        $id = '42';

        $subject = new ResourceIdentifierObject($type, $id);

        $this->assertSame($type, $subject->getType());
        $this->assertSame($id, $subject->getId());
        $this->assertNull($subject->getMeta());
    }

    /**
     * @return void
     *
     * @covers ::getId
     * @covers ::setId
     * @covers ::withId
     */
    public function testIdAccessors(): void
    {
        $subject = new ResourceIdentifierObject('people', '42');

        $subjectClone = $subject->withId('1337');

        $this->assertNotSame($subject, $subjectClone);
        $this->assertSame('1337', $subjectClone->getId());
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::getMeta
     * @covers ::setId
     * @covers ::setMeta
     * @covers ::setType
     * @covers ::withMeta
     * @covers ::withoutMeta
     */
    public function testMetaAccessors(): void
    {
        $meta = new MetaObject(['foo' => 'bar']);
        $subject = new ResourceIdentifierObject('people', '42');

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
     * @covers ::getType
     * @covers ::setType
     * @covers ::withType
     */
    public function testTypeAccessors(): void
    {
        $subject = new ResourceIdentifierObject('people', '42');

        $subjectClone = $subject->withType('cars');

        $this->assertNotSame($subject, $subjectClone);
        $this->assertSame('cars', $subjectClone->getType());
    }

    /**
     * @param ResourceIdentifierObject $subject
     * @param array $expectedRetVal
     *
     * @return void
     *
     * @covers ::jsonSerialize
     *
     * @dataProvider provideJsonSerializeData
     */
    public function testJsonSerialize(
        ResourceIdentifierObject $subject,
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
            'type and id' => [
                new ResourceIdentifierObject('people', '42'),
                [
                    'type' => 'people',
                    'id' => 42,
                ],
            ],
            'type, id and meta' => [
                (new ResourceIdentifierObject('people', '42'))
                    ->withMeta(new MetaObject([
                        'foo' => 'bar',
                    ])),
                [
                    'type' => 'people',
                    'id' => 42,
                    'meta' => [
                        'foo' => 'bar',
                    ],
                ],
            ],
        ];
    }
}
