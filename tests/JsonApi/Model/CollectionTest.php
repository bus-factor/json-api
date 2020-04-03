<?php

/**
 * CollectionTest.php
 *
 * Author: Michael Leßnau <michael.lessnau@gmail.com>
 * Date:   2020-04-03
 */

declare(strict_types=1);

namespace JsonApi\Model;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Class CollectionTest
 *
 * @coversDefaultClass \JsonApi\Model\Collection
 */
class CollectionTest extends TestCase
{
    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::toArray
     */
    public function testConstruct(): void
    {
        $itemFqcn = stdClass::class;
        $items = [new stdClass(), new stdClass()];

        $subject = new Collection($itemFqcn, $items);

        $this->assertSame($items, $subject->toArray());
    }

    /**
     * @return void
     *
     * @covers ::__construct
     */
    public function testConstructWithInvalidItems(): void
    {
        $itemFqcn = 'Foo\Bar';
        $items = [new stdClass(), new stdClass()];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected type Foo\Bar, got stdClass');

        $subject = new Collection($itemFqcn, $items);
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::__clone
     * @covers ::add
     * @covers ::toArray
     */
    public function testAdd(): void
    {
        $itemFqcn = stdClass::class;
        $items = [new stdClass(), new stdClass()];
        $additionalItem = new stdClass();

        $subject = new Collection($itemFqcn, $items);

        $subjectClone = $subject->add($additionalItem);

        $this->assertNotSame($subject, $subjectClone);
        $this->assertSame($items, $subject->toArray());
        $this->assertSame([...$items, $additionalItem], $subjectClone->toArray());
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::__clone
     * @covers ::add
     * @covers ::toArray
     */
    public function testAddWithInvalidItem(): void
    {
        $itemFqcn = stdClass::class;
        $items = [new stdClass(), new stdClass()];
        $additionalItem = new class { };

        $subject = new Collection($itemFqcn, $items);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected type stdClass, got ' . get_class($additionalItem));

        $subjectClone = $subject->add($additionalItem);
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::__clone
     * @covers ::addAll
     * @covers ::toArray
     */
    public function testAddAll(): void
    {
        $itemFqcn = stdClass::class;
        $items = [new stdClass(), new stdClass()];
        $additionalItems = [new stdClass(), new stdClass()];

        $subject = new Collection($itemFqcn, $items);

        $subjectClone = $subject->addAll($additionalItems);

        $this->assertNotSame($subject, $subjectClone);
        $this->assertSame($items, $subject->toArray());
        $this->assertSame([...$items, ...$additionalItems], $subjectClone->toArray());
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::__clone
     * @covers ::addAll
     * @covers ::toArray
     */
    public function testAddAllWithInvalidItem(): void
    {
        $itemFqcn = stdClass::class;
        $items = [new stdClass(), new stdClass()];
        $additionalItems = [new class { }, new class { }];

        $subject = new Collection($itemFqcn, $items);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected type stdClass, got ' . get_class($additionalItems[0]));

        $subjectClone = $subject->addAll($additionalItems);
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::current
     * @covers ::next
     * @covers ::rewind
     * @covers ::valid
     * @covers ::key
     */
    public function testIteration(): void
    {
        $itemFqcn = stdClass::class;
        $items = [new stdClass(), new stdClass(), new stdClass()];
        $actualItems = [];

        $subject = new Collection($itemFqcn, $items);

        foreach ($subject as $key => $item) {
            $actualItems[$key] = $item;
        }

        $this->assertSame($items, $actualItems);
    }
}
