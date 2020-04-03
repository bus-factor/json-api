<?php

/**
 * MetaObject.php
 *
 * @author    michael.lessnau
 * @since     27.03.2020
 */

declare(strict_types=1);

namespace JsonApi\Model;

use InvalidArgumentException;
use JsonSerializable;

/**
 * Class MetaObject
 */
class MetaObject implements JsonSerializable
{
    /**
     * @var array
     */
    private array $members = [];

    /**
     * @param array $members
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $members = [])
    {
        $this->setMembers($members);
    }

    /**
     * @param string $name
     *
     * @return mixed
     *
     * @throws InvalidArgumentException
     */
    public function getMember(string $name)
    {
        if (!$this->hasMember($name)) {
            throw new InvalidArgumentException('Undefined member: ' . $name);
        }

        return $this->members[$name];
    }

    /**
     * @return array
     */
    public function getMembers(): array
    {
        return $this->members;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasMember(string $name): bool
    {
        return array_key_exists($name, $this->members);
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return $this->members;
    }

    /**
     * @param string $name
     * @param $value
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    private function setMember(string $name, $value): self
    {
        if (preg_match('/^[a-z\d]+([\-_][a-z\d]+)*$/i', $name) !== 1) {
            throw new InvalidArgumentException('Invalid member name: ' . $name);
        }

        $this->members[$name] = $value;

        return $this;
    }

    /**
     * @param array $members
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    private function setMembers(array $members): self
    {
        foreach ($members as $name => $value) {
            $this->setMember($name, $value);
        }

        return $this;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    private function unsetMember(string $name): self
    {
        unset($this->members[$name]);

        return $this;
    }

    /**
     * @return $this
     */
    private function unsetMembers(): self
    {
        $this->members = [];

        return $this;
    }

    /**
     * @param string $name
     * @param $value
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    public function withMember(string $name, $value): self
    {
        return (clone $this)
            ->setMember($name, $value);
    }

    /**
     * @param array $members
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    public function withMembers(array $members): self
    {
        return (clone $this)
            ->setMembers($members);
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function withoutMember(string $name): self
    {
        return (clone $this)
            ->unsetMember($name);
    }

    /**
     * @return $this
     */
    public function withoutMembers(): self
    {
        return (clone $this)
            ->unsetMembers();
    }
}
