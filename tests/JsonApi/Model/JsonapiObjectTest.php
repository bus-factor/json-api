<?php

/**
 * JsonapiObjectTest.php
 *
 * @author    michael.lessnau
 * @since     27.03.2020
 */

declare(strict_types=1);

namespace Test\JsonApi\Model;

use JsonApi\Model\JsonapiObject;
use JsonApi\Model\MetaObject;
use PHPUnit\Framework\TestCase;

/**
 * Class JsonapiObjectTest
 *
 * @coversDefaultClass \JsonApi\Model\JsonapiObject
 * @uses \JsonApi\Model\AbstractObject
 * @uses \JsonApi\Model\JsonapiObject
 * @uses \JsonApi\Model\MetaObject
 */
class JsonapiObjectTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::getMeta
     * @covers ::getVersion
     * @covers ::setMeta
     * @covers ::setVersion
     */
    public function testConstruct(): void
    {
        $version = '1.0';
        $meta = new MetaObject([
            'foo' => 'bar',
        ]);

        $subject = new JsonapiObject($version, $meta);

        $this->assertSame($version, $subject->getVersion());
        $this->assertSame($meta, $subject->getMeta());
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::getMeta
     * @covers ::getVersion
     * @covers ::setMeta
     * @covers ::setVersion
     */
    public function testConstructDefaults(): void
    {
        $subject = new JsonapiObject();

        $this->assertSame('1.0', $subject->getVersion());
        $this->assertNull($subject->getMeta());
    }

    /**
     * @param string|null $version
     * @param string|null $exception
     * @param string|null $exceptionMessage
     *
     * @return void
     *
     * @covers ::getVersion
     * @covers ::setVersion
     * @covers ::withVersion
     * @covers ::withoutVersion
     *
     * @testWith [null]
     *           ["1.0"]
     *           ["1.1", "InvalidArgumentException", "Invalid version: 1.1"]
     *           ["2.0", "InvalidArgumentException", "Invalid version: 2.0"]
     */
    public function testVersionAccessors(
        ?string $version,
        ?string $exception = null,
        ?string $exceptionMessage = null
    ): void {
        $subject = new JsonapiObject();

        if (isset($exception)) {
            $this->expectException($exception);

            if (isset($exceptionMessage)) {
                $this->expectExceptionMessage($exceptionMessage);
            }
        }

        $subjectClone = isset($version)
            ? $subject->withVersion($version)
            : $subject->withoutVersion();

        if (!isset($exception)) {
            $this->assertNotSame($subject, $subjectClone);
            $this->assertSame($version, $subjectClone->getVersion());
        }
    }

    /**
     * @param array|null $metaData
     *
     * @return void
     *
     * @covers ::getMeta
     * @covers ::setMeta
     * @covers ::withMeta
     * @covers ::withoutMeta
     *
     * @testWith [{}]
     *           [{"foo": "bar"}]
     *           [null]
     */
    public function testMetaAccessors(?array $metaData): void
    {
        $subject = new JsonapiObject();

        $subjectClone = isset($metaData)
            ? $subject->withMeta(new MetaObject($metaData))
            : $subject->withoutMeta();

        if (!isset($exception)) {
            $this->assertNotSame($subject, $subjectClone);

            $this->assertEquals(
                isset($metaData) ? new MetaObject($metaData) : null,
                $subjectClone->getMeta()
            );
        }
    }

    /**
     * @param JsonapiObject $subject
     * @param array $expectedRetVal
     *
     * @return void
     *
     * @covers ::jsonSerialize
     * @covers ::setVersion
     *
     * @dataProvider provideJsonSerializeData
     */
    public function testJsonSerialize(
        JsonapiObject $subject,
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
                new JsonapiObject(null, null),
                []
            ],
            'meta set' => [
                new JsonapiObject(null, new MetaObject(['foo' => 'bar'])),
                [
                    'meta' => [
                        'foo' => 'bar',
                    ],
                ],
            ],
            'version set' => [
                new JsonapiObject('1.0'),
                [
                    'version' => '1.0',
                ],
            ],
            'meta set, version set' => [
                new JsonapiObject('1.0', new MetaObject(['foo' => 'bar'])),
                [
                    'meta' => [
                        'foo' => 'bar',
                    ],
                    'version' => '1.0',
                ],
            ],
        ];
    }
}
