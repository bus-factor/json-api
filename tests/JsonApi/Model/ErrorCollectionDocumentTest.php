<?php

/**
 * ErrorCollectionDocumentTest.php
 *
 * Author: Michael Leßnau <michael.lessnau@gmail.com>
 * Date:   2020-04-04
 */

declare(strict_types=1);

namespace JsonApi\Model;

use PHPUnit\Framework\TestCase;

/**
 * Class ErrorCollectionDocumentTest
 *
 * @coversDefaultClass \JsonApi\Model\ErrorCollectionDocument
 * @uses \JsonApi\Model\AbstractDocument
 * @uses \JsonApi\Model\Collection
 * @uses \JsonApi\Model\ErrorObject
 * @uses \JsonApi\Model\ErrorObjectCollection
 * @uses \JsonApi\Model\MetaObject
 */
class ErrorCollectionDocumentTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::getErrors
     * @covers ::setErrors
     * @covers ::withErrors
     */
    public function testErrorsAccessors(): void
    {
        $errors = new ErrorObjectCollection([]);
        $subject = new ErrorCollectionDocument($errors);

        $otherErrors = new ErrorObjectCollection([]);
        $subjectClone = $subject->withErrors($otherErrors);

        $this->assertNotSame($subject, $subjectClone);
        $this->assertSame($errors, $subject->getErrors());
        $this->assertSame($otherErrors, $subjectClone->getErrors());
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
        $errors = new ErrorObjectCollection([]);
        $subject = new ErrorCollectionDocument($errors);

        $this->assertNull($subject->getMeta());

        $meta = new MetaObject();
        $subjectClone = $subject->withMeta($meta);

        $this->assertNotSame($subject, $subjectClone);
        $this->assertNull($subject->getMeta());
        $this->assertSame($meta, $subjectClone->getMeta());

        $subjectClone2 = $subjectClone->withoutMeta();

        $this->assertNotSame($subjectClone, $subjectClone2);
        $this->assertSame($meta, $subjectClone->getMeta());
        $this->assertNull($subjectClone2->getMeta());
    }

    /**
     * @param ErrorCollectionDocument $subject
     * @param array $expectedRetVal
     *
     * @return void
     *
     * @covers ::jsonSerialize
     *
     * @dataProvider provideJsonSerializeData
     */
    public function testJsonSerialize(ErrorCollectionDocument $subject, array $expectedRetVal): void
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
            'errors' => [
                new ErrorCollectionDocument(new ErrorObjectCollection([
                    (new ErrorObject())
                        ->withStatus(404)
                        ->withTitle('Not Found'),
                ])),
                [
                    'errors' => [
                        [
                            'status' => 404,
                            'title' => 'Not Found',
                        ],
                    ],
                ],
            ],
            'errors, meta' => [
                (new ErrorCollectionDocument(new ErrorObjectCollection([
                    (new ErrorObject())
                        ->withStatus(404)
                        ->withTitle('Not Found'),
                ])))
                    ->withMeta(new MetaObject([
                        'foo' => 'bar',
                    ])),
                [
                    'errors' => [
                        [
                            'status' => 404,
                            'title' => 'Not Found',
                        ],
                    ],
                    'meta' => [
                        'foo' => 'bar',
                    ],
                ],
            ],
        ];
    }
}