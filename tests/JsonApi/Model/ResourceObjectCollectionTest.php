<?php

/**
 * ResourceObjectCollectionTest.php
 *
 * @author    michael.lessnau
 * @since     29.03.2020
 */

declare(strict_types=1);

namespace Test\JsonApi\Model;

use InvalidArgumentException;
use JsonApi\Model\ResourceObject;
use JsonApi\Model\ResourceObjectCollection;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Class ResourceObjectCollectionTest
 *
 * @coversDefaultClass \JsonApi\Model\ResourceObjectCollection
 * @uses \JsonApi\Model\Collection
 * @uses \JsonApi\Model\ResourceObject
 * @uses \JsonApi\Model\ResourceObjectCollection
 */
class ResourceObjectCollectionTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     */
    public function testConstruct(): void
    {
        $resourceObjects = [
            new ResourceObject('people', '42'),
            new ResourceObject('people', '1337'),
        ];

        $subject = new ResourceObjectCollection(
            $resourceObjects
        );

        $this->assertSame(
            $resourceObjects,
            $subject->toArray()
        );

        $this->expectException(InvalidArgumentException::class);

        $this->expectExceptionMessage(sprintf(
            'Expected type %s, got %s',
            ResourceObject::class,
            'stdClass'
        ));

        $subject2 = new ResourceObjectCollection([new stdClass()]);
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::jsonSerialize
     */
    public function testJsonSerialize(): void
    {
        $resourceObjects = [
            new ResourceObject('people', '42'),
            new ResourceObject('people', '1337'),
        ];

        $subject = new ResourceObjectCollection(
            $resourceObjects
        );

        $expectedRetVal = [
            [
                'type' => 'people',
                'id' => '42',
            ],
            [
                'type' => 'people',
                'id' => '1337',
            ],
        ];

        $this->assertSame($expectedRetVal, $subject->jsonSerialize());
    }
}
