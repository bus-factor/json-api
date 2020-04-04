<?php

/**
 * AbstractDocumentTest.php
 *
 * Author: Michael Leßnau <michael.lessnau@gmail.com>
 * Date:   2020-04-04
 */

declare(strict_types=1);

namespace Test\JsonApi\Model;

use JsonApi\Model\AbstractDocument;
use JsonApi\Model\JsonapiObject;
use JsonApi\Model\LinkObject;
use JsonApi\Model\LinksObject;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractDocumentTest
 *
 * @coversDefaultClass \JsonApi\Model\AbstractDocument
 * @uses \JsonApi\Model\JsonapiObject
 * @uses \JsonApi\Model\LinksObject
 * @uses \JsonApi\Model\LinkObject
 */
class AbstractDocumentTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::getJsonapi
     * @covers ::setJsonapi
     * @covers ::withJsonapi
     * @covers ::withoutJsonapi
     */
    public function testJsonapiAccessors(): void
    {
        $jsonapi = new JsonapiObject();
        $subject = $this->getMockForAbstractClass(AbstractDocument::class);

        $this->assertNull($subject->getJsonapi());

        $subjectClone = $subject->withJsonapi($jsonapi);

        $this->assertNotSame($subject, $subjectClone);
        $this->assertNull($subject->getJsonapi());
        $this->assertSame($jsonapi, $subjectClone->getJsonapi());

        $subjectClone2 = $subjectClone->withoutJsonapi();

        $this->assertNotSame($subjectClone, $subjectClone2);
        $this->assertSame($jsonapi, $subjectClone->getJsonapi());
        $this->assertNull($subjectClone2->getJsonapi());
    }

    /**
     * @return void
     *
     * @covers ::getLinks
     * @covers ::setLinks
     * @covers ::withLinks
     * @covers ::withoutLinks
     */
    public function testLinksAccessors(): void
    {
        $links = new LinksObject([]);
        $subject = $this->getMockForAbstractClass(AbstractDocument::class);

        $this->assertNull($subject->getLinks());

        $subjectClone = $subject->withLinks($links);

        $this->assertNotSame($subject, $subjectClone);
        $this->assertNull($subject->getLinks());
        $this->assertSame($links, $subjectClone->getLinks());

        $subjectClone2 = $subjectClone->withoutLinks();

        $this->assertNotSame($subjectClone, $subjectClone2);
        $this->assertSame($links, $subjectClone->getLinks());
        $this->assertNull($subjectClone2->getLinks());
    }

    /**
     * @param AbstractDocument $subject
     * @param array $expectedRetVal
     *
     * @return void
     *
     * @covers ::jsonSerialize
     *
     * @dataProvider provideJsonSerializeData
     */
    public function testJsonSerialize(AbstractDocument $subject, array $expectedRetVal): void
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
            'none' => [
                $this->getMockForAbstractClass(AbstractDocument::class),
                [],
            ],
            'jsonapi' => [
                $this->getMockForAbstractClass(AbstractDocument::class)
                    ->withJsonapi(new JsonapiObject('1.0')),
                [
                    'jsonapi' => [
                        'version' => '1.0',
                    ],
                ],
            ],
            'links' => [
                $this->getMockForAbstractClass(AbstractDocument::class)
                    ->withLinks(new LinksObject([
                        'self' => new LinkObject('https://example.com'),
                    ])),
                [
                    'links' => [
                        'self' => 'https://example.com',
                    ],
                ],
            ],
        ];
    }
}
