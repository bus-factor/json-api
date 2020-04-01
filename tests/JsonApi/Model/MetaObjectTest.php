<?php

/**
 * MetaObjectTest.php
 *
 * @author    michael.lessnau
 * @since     27.03.2020
 */

declare(strict_types=1);

namespace Test\JsonApi\Model;

use InvalidArgumentException;
use JsonApi\Model\MetaObject;
use PHPUnit\Framework\TestCase;

/**
 * Class MetaObjectTest
 *
 * @coversDefaultClass \JsonApi\Model\MetaObject
 * @uses \JsonApi\Model\AbstractObject
 * @uses \JsonApi\Model\MetaObject
 */
class MetaObjectTest extends TestCase
{
    /**
     * @param array $members
     *
     * @return void
     *
     * @covers ::__construct
     * @covers ::getMembers
     * @covers ::setMember
     * @covers ::setMembers
     *
     * @dataProvider provideConstructData
     */
    public function testConstruct(
        array $members,
        ?string $exception = null,
        ?string $exceptionMessage = null
    ): void {
        if (isset($exception)) {
            $this->expectException($exception);

            if (isset($exceptionMessage)) {
                $this->expectExceptionMessage($exceptionMessage);
            }
        }

        $subject = new MetaObject($members);

        if (!isset($exception)) {
            $this->assertEquals($members, $subject->getMembers());
        }
    }

    /**
     * @return array
     */
    public function provideConstructData(): array
    {
        return [
            'no members' => [
                [],
            ],
            'valid members' => [
                [
                    'foo' => 'bar',
                    'fizz' => 'buzz',
                ],
            ],
            'invalid members' => [
                [
                    'foo & bar' => 'fizz & buzz',
                ],
                InvalidArgumentException::class,
                'Invalid member name: foo & bar',
            ],
        ];
    }

    /**
     * @return void
     *
     * @covers ::getMember
     */
    public function testGetMember(): void
    {
        $subject = new MetaObject([
            'foo' => 'bar',
        ]);

        $this->assertSame('bar', $subject->getMember('foo'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Undefined member: fizz');

        $subject->getMember('fizz');
    }

    /**
     * @return void
     *
     * @covers ::jsonSerialize
     */
    public function testJsonSerialize(): void
    {
        $members = [
            'foo' => 'bar',
            'fizz' => 'buzz',
        ];

        $subject = new MetaObject($members);

        $this->assertEquals($members, $subject->jsonSerialize());
    }

    /**
     * @return void
     *
     * @covers ::hasMember
     */
    public function testHasMember(): void
    {
        $subject = new MetaObject([
            'foo' => 'bar',
        ]);

        $this->assertTrue($subject->hasMember('foo'));
        $this->assertFalse($subject->hasMember('fizz'));
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::getMember
     * @covers ::hasMember
     * @covers ::setMember
     * @covers ::withMember
     */
    public function testWithMember(): void
    {
        $subject = new MetaObject(['foo' => 'bar']);

        $subjectClone = $subject->withMember('fizz', 'buzz');

        $this->assertNotSame($subject, $subjectClone);
        $this->assertFalse($subject->hasMember('fizz'));
        $this->assertSame('buzz', $subjectClone->getMember('fizz'));
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::getMember
     * @covers ::hasMember
     * @covers ::setMember
     * @covers ::setMembers
     * @covers ::withMembers
     */
    public function testWithMembers(): void
    {
        $subject = new MetaObject(['foo' => 'bar']);

        $subjectClone = $subject->withMembers([
            'a' => 42,
            'b' => 1337,
        ]);

        $this->assertNotSame($subject, $subjectClone);
        $this->assertFalse($subject->hasMember('a'));
        $this->assertFalse($subject->hasMember('b'));
        $this->assertSame(42, $subjectClone->getMember('a'));
        $this->assertSame(1337, $subjectClone->getMember('b'));
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::setMember
     * @covers ::setMembers
     * @covers ::unsetMember
     * @covers ::withoutMember
     */
    public function testWithoutMember(): void
    {
        $subject = new MetaObject(['foo' => 'bar']);

        $subjectClone = $subject->withoutMember('foo');

        $this->assertNotSame($subject, $subjectClone);
        $this->assertTrue($subject->hasMember('foo'));
        $this->assertFalse($subjectClone->hasMember('foo'));
    }

    /**
     * @return void
     *
     * @covers ::__construct
     * @covers ::getMembers
     * @covers ::hasMember
     * @covers ::setMember
     * @covers ::setMembers
     * @covers ::unsetMembers
     * @covers ::withoutMembers
     */
    public function testWithoutMembers(): void
    {
        $subject = new MetaObject([
            'a' => 42,
            'b' => 1337,
        ]);

        $subjectClone = $subject->withoutMembers();

        $this->assertNotSame($subject, $subjectClone);
        $this->assertTrue($subject->hasMember('a'));
        $this->assertTrue($subject->hasMember('b'));
        $this->assertEquals([], $subjectClone->getMembers());
    }
}
