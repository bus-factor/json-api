<?php

/**
 * RelationshipsObject.php
 *
 * @author    michael.lessnau
 * @since     27.03.2020
 */

declare(strict_types=1);

namespace JsonApi\Model;

use InvalidArgumentException;

/**
 * Class RelationshipsObject
 */
class RelationshipsObject extends AbstractObject
{
    /**
     * @var array|RelationshipObjectInterface[]
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
     * @return RelationshipObjectInterface
     *
     * @throws InvalidArgumentException
     */
    public function getField(string $name): RelationshipObjectInterface
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
     * @return array|RelationshipObjectInterface[]
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
        $object = [];

        /** @var RelationshipObjectInterface $field */
        foreach ($this->fields as $name => $field) {
            $object[$name] = $field->jsonSerialize();
        }

        return $object;
    }

    /**
     * @param string $name
     * @param RelationshipObjectInterface $field
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    private function setField(
        string $name,
        RelationshipObjectInterface $field
    ): self {
        if (!$this->isValidMemberName($name)) {
            throw new InvalidArgumentException('Invalid field name: ' . $name);
        }

        if ($this->isReservedFieldName($name)) {
            throw new InvalidArgumentException('Reserved field name: ' . $name);
        }

        $this->fields[$name] = $field;

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
     * @param RelationshipObjectInterface $field
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    public function withField(
        string $name,
        RelationshipObjectInterface $field
    ): self {
        return (clone $this)
            ->setField($name, $field);
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
     *
     * @return $this
     */
    public function withoutField(string $name): self
    {
        return (clone $this)
            ->unsetField($name);
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
