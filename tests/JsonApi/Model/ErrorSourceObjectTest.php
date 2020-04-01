<?php

/**
 * ErrorSourceObjectTest.php
 *
 * @author    michael.lessnau
 * @since     28.03.2020
 */

declare(strict_types=1);

namespace Test\JsonApi\Model;

use JsonApi\Model\ErrorSourceObject;
use PHPUnit\Framework\TestCase;

/**
 * Class ErrorSourceObjectTest
 *
 * @coversDefaultClass \JsonApi\Model\ErrorSourceObject
 * @uses \JsonApi\Model\ErrorSourceObject
 */
class ErrorSourceObjectTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::getParameter
     * @covers ::setParameter
     * @covers ::withParameter
     * @covers ::withoutParameter
     */
    public function testParameterAccessors(): void
    {
        $subject = new ErrorSourceObject();

        $this->assertNull($subject->getParameter());

        $subjectClone = $subject->withParameter('foo=42');

        $this->assertNotSame($subject, $subjectClone);
        $this->assertSame('foo=42', $subjectClone->getParameter());

        $subjectClone2 = $subjectClone->withoutParameter();

        $this->assertNotSame($subjectClone, $subjectClone2);
        $this->assertNull($subjectClone2->getParameter());
    }

    /**
     * @return void
     *
     * @covers ::getPointer
     * @covers ::setPointer
     * @covers ::withPointer
     * @covers ::withoutPointer
     */
    public function testPointerAccessors(): void
    {
        $subject = new ErrorSourceObject();

        $this->assertNull($subject->getPointer());

        $subjectClone = $subject->withPointer('/data/name');

        $this->assertNotSame($subject, $subjectClone);
        $this->assertSame('/data/name', $subjectClone->getPointer());

        $subjectClone2 = $subjectClone->withoutPointer();

        $this->assertNotSame($subjectClone, $subjectClone2);
        $this->assertNull($subjectClone2->getPointer());
    }

    /**
     * @param ErrorSourceObject $subject
     * @param array $expectedRetVal
     *
     * @return void
     *
     * @covers ::jsonSerialize
     *
     * @dataProvider provideJsonSerializeData
     */
    public function testJsonSerialize(
        ErrorSourceObject $subject,
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
                new ErrorSourceObject(),
                [],
            ],
            'parameter' => [
                (new ErrorSourceObject())
                    ->withParameter('foo=42'),
                [
                    'parameter' => 'foo=42',
                ]
            ],
            'pointer' => [
                (new ErrorSourceObject())
                    ->withPointer('/data/name'),
                [
                    'pointer' => '/data/name',
                ]
            ],
        ];
    }
}
