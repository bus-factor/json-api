<?php

/**
 * ResourceIdentifierObjectCollectionTest.php
 *
 * @author    michael.lessnau
 * @since     29.03.2020
 */

declare(strict_types=1);

namespace Test\JsonApi\Model;

use InvalidArgumentException;
use JsonApi\Model\ResourceIdentifierObject;
use JsonApi\Model\ResourceIdentifierObjectCollection;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Class ResourceIdentifierObjectCollectionTest
 *
 * @coversDefaultClass \JsonApi\Model\ResourceIdentifierObjectCollection
 * @uses \JsonApi\Model\ResourceIdentifierObject
 * @uses \JsonApi\Model\ResourceIdentifierObjectCollection
 */
class ResourceIdentifierObjectCollectionTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::getResourceIdentifierObjects
     */
    public function testConstruct(): void
    {
        $resourceIdentifierObjects = [
            new ResourceIdentifierObject('people', '42'),
            new ResourceIdentifierObject('people', '1337'),
        ];

        $subject = new ResourceIdentifierObjectCollection(
            $resourceIdentifierObjects
        );

        $this->assertSame(
            $resourceIdentifierObjects,
            $subject->getResourceIdentifierObjects()
        );

        $this->expectException(InvalidArgumentException::class);

        $this->expectExceptionMessage(sprintf(
            'Type mismatch: expected %s, got %s',
            ResourceIdentifierObject::class,
            'stdClass'
        ));

        $subject2 = new ResourceIdentifierObjectCollection([new stdClass()]);
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::jsonSerialize
     */
    public function testJsonSerialize(): void
    {
        $resourceIdentifierObjects = [
            new ResourceIdentifierObject('people', '42'),
            new ResourceIdentifierObject('people', '1337'),
        ];

        $subject = new ResourceIdentifierObjectCollection(
            $resourceIdentifierObjects
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
