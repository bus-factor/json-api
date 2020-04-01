<?php

/**
 * ErrorObjectCollectionTest.php
 *
 * @author    michael.lessnau
 * @since     29.03.2020
 */

declare(strict_types=1);

namespace Test\JsonApi\Model;

use InvalidArgumentException;
use JsonApi\Model\ErrorObject;
use JsonApi\Model\ErrorObjectCollection;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Class ErrorObjectCollectionTest
 *
 * @coversDefaultClass \JsonApi\Model\ErrorObjectCollection
 * @uses \JsonApi\Model\ErrorObject
 * @uses \JsonApi\Model\ErrorObjectCollection
 */
class ErrorObjectCollectionTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::getErrorObjects
     */
    public function testConstruct(): void
    {
        $resourceIdentifierObjects = [
            new ErrorObject(),
            new ErrorObject(),
        ];

        $subject = new ErrorObjectCollection(
            $resourceIdentifierObjects
        );

        $this->assertSame(
            $resourceIdentifierObjects,
            $subject->getErrorObjects()
        );

        $this->expectException(InvalidArgumentException::class);

        $this->expectExceptionMessage(sprintf(
            'Type mismatch: expected %s, got %s',
            ErrorObject::class,
            'stdClass'
        ));

        $subject2 = new ErrorObjectCollection([new stdClass()]);
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
            (new ErrorObject())
                ->withId('test'),
            (new ErrorObject())
                ->withCode('red'),
        ];

        $subject = new ErrorObjectCollection(
            $resourceIdentifierObjects
        );

        $expectedRetVal = [
            [
                'id' => 'test',
            ],
            [
                'code' => 'red',
            ],
        ];

        $this->assertSame($expectedRetVal, $subject->jsonSerialize());
    }
}
