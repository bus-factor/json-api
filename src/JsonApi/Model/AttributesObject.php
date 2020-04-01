<?php

/**
 * AttributesObject.php
 *
 * @author    michael.lessnau
 * @since     27.03.2020
 */

declare(strict_types=1);

namespace JsonApi\Model;

use InvalidArgumentException;

/**
 * Class AttributesObject
 */
class AttributesObject extends AbstractObject
{
    /**
     * @var array
     */
    private array $fields = [];

    /**
     * @param array $fields
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $fields = [])
    {
        $this->setFields($fields);
    }

    /**
     * @param string $name
     *
     * @return mixed
     *
     * @throws InvalidArgumentException
     */
    public function getField(string $name)
    {
        if (!$this->hasField($name)) {
            throw new InvalidArgumentException('Undefined field: ' . $name);
        }

        return $this->fields[$name];
    }

    /**
     * @return array|string[]
     */
    public function getFieldNames(): array
    {
        return array_keys($this->fields);
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasField(string $name): bool
    {
        return array_key_exists($name, $this->fields);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->fields;
    }

    /**
     * @param string $name
     * @param $value
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    private function setField(string $name, $value): self
    {
        if (!$this->isValidMemberName($name)) {
            throw new InvalidArgumentException('Invalid field name: ' . $name);
        }

        if ($this->isReservedFieldName($name)) {
            throw new InvalidArgumentException('Reserved field name: ' . $name);
        }

        $this->fields[$name] = $value;

        return $this;
    }

    /**
     * @param array $fields
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    private function setFields(array $fields): self
    {
        foreach ($fields as $name => $value) {
            $this->setField($name, $value);
        }

        return $this;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    private function unsetField(string $name): self
    {
        unset($this->fields[$name]);

        return $this;
    }

    /**
     * @return $this
     */
    private function unsetFields(): self
    {
        $this->fields = [];

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
    public function withField(string $name, $value): self
    {
        return (clone $this)
            ->setField($name, $value);
    }

    /**
     * @param array $fields
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    public function withFields(array $fields): self
    {
        return (clone $this)
            ->setFields($fields);
    }

    /**
     * @param string $name
     * @param bool $force Ignore if the field does not exist.
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    public function withoutField(string $name, bool $force = true): self
    {
        return (clone $this)
            ->unsetField($name, $force);
    }

    /**
     * @return $this
     */
    public function withoutFields(): self
    {
        return (clone $this)
            ->unsetFields();
    }
}
