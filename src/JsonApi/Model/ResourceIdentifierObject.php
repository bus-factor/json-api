<?php

/**
 * ResourceIdentifierObject.php
 *
 * @author    michael.lessnau
 * @since     28.03.2020
 */

declare(strict_types=1);

namespace JsonApi\Model;

/**
 * Class ResourceIdentifierObject
 */
class ResourceIdentifierObject extends AbstractObject
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @var MetaObject|null
     */
    private ?MetaObject $meta = null;

    /**
     * @var string
     */
    private string $type;

    /**
     * @param string $type
     * @param string $id
     */
    public function __construct(string $type, string $id)
    {
        $this->setType($type);
        $this->setId($id);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return MetaObject|null
     */
    public function getMeta(): ?MetaObject
    {
        return $this->meta;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        $object = [
            'type' => $this->type,
            'id' => $this->id,
        ];

        if (isset($this->meta)) {
            $object['meta'] = $this->meta->jsonSerialize();
        }

        return $object;
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    private function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param MetaObject|null $meta
     *
     * @return $this
     */
    private function setMeta(?MetaObject $meta): self
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    private function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function withId(string $id): self
    {
        return (clone $this)
            ->setId($id);
    }

    /**
     * @param MetaObject $meta
     *
     * @return $this
     */
    public function withMeta(MetaObject $meta): self
    {
        return (clone $this)
            ->setMeta($meta);
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function withType(string $type): self
    {
        return (clone $this)
            ->setType($type);
    }

    /**
     * @return $this
     */
    public function withoutMeta(): self
    {
        return (clone $this)
            ->setMeta(null);
    }
}
