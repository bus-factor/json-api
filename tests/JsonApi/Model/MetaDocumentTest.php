<?php

/**
 * MetaDocumentTest.php
 *
 * Author: Michael Leßnau <michael.lessnau@gmail.com>
 * Date:   2020-04-04
 */

declare(strict_types=1);

namespace Test\JsonApi\Model;

use JsonApi\Model\JsonapiObject;
use JsonApi\Model\MetaDocument;
use JsonApi\Model\MetaObject;
use PHPUnit\Framework\TestCase;

/**
 * Class MetaDocumentTest
 *
 * @coversDefaultClass \JsonApi\Model\MetaDocument
 * @uses \JsonApi\Model\AbstractDocument
 * @uses \JsonApi\Model\JsonapiObject
 * @uses \JsonApi\Model\MetaObject
 */
class MetaDocumentTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::getMeta
     * @covers ::setMeta
     * @covers ::withMeta
     */
    public function testMetaAccessors(): void
    {
        $meta = new MetaObject();

        $subject = new MetaDocument($meta);

        $meta2 = new MetaObject();
        $subjectClone = $subject->withMeta($meta2);

        $this->assertNotSame($subject, $subjectClone);
        $this->assertSame($meta, $subject->getMeta());
        $this->assertSame($meta2, $subjectClone->getMeta());
    }

    /**
     * @param MetaDocument $subject
     * @param array $expectedRetVal
     *
     * @return void
     *
     * @covers ::jsonSerialize
     *
     * @dataProvider provideJsonSerializeData
     */
    public function testJsonSerialize(MetaDocument $subject, array $expectedRetVal): void
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
            'meta only' => [
                new MetaDocument(new MetaObject(['foo' => 'bar'])),
                [
                    'meta' => [
                        'foo' => 'bar',
                    ],
                ],
            ],
            'with jsonapi' => [
                (new MetaDocument(new MetaObject(['foo' => 'bar'])))
                    ->withJsonapi(new JsonapiObject('1.0')),
                [
                    'jsonapi' => [
                        'version' => '1.0',
                    ],
                    'meta' => [
                        'foo' => 'bar',
                    ],
                ],
            ],
        ];
    }
}