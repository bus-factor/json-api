<?php

/**
 * ResourceObject.php
 *
 * @author    michael.lessnau
 * @since     27.03.2020
 */

declare(strict_types=1);

namespace JsonApi\Model;

use InvalidArgumentException;
use JsonSerializable;

/**
 * Class ResourceObject
 */
class ResourceObject implements JsonSerializable
{
    /**
     * @var AttributesObject|null
     */
    private ?AttributesObject $attributes = null;

    /**
     * @var string|null
     */
    private ?string $id = null;

    /**
     * @var LinksObject|null
     */
    private ?LinksObject $links = null;

    /**
     * @var MetaObject|null
     */
    private ?MetaObject $meta = null;

    /**
     * @var RelationshipsObject|null
     */
    private ?RelationshipsObject $relationships = null;

    /**
     * @var string
     */
    private string $type;

    /**
     * @param string $type
     * @param string|null $id
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $type, ?string $id = null)
    {
        $this->setType($type);
        $this->setId($id);
    }

    /**
     * @return AttributesObject|null
     */
    public function getAttributes(): ?AttributesObject
    {
        return $this->attributes;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return LinksObject|null
     */
    public function getLinks(): ?LinksObject
    {
        return $this->links;
    }

    /**
     * @return MetaObject|null
     */
    public function getMeta(): ?MetaObject
    {
        return $this->meta;
    }

    /**
     * @return RelationshipsObject|null
     */
    public function getRelationships(): ?RelationshipsObject
    {
        return $this->relationships;
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
        ];

        if (isset($this->id)) {
            $object['id'] = $this->id;
        }

        if (isset($this->attributes)) {
            $object['attributes'] = $this->attributes->jsonSerialize();
        }

        if (isset($this->relationships)) {
            $object['relationships'] = $this->relationships->jsonSerialize();
        }

        if (isset($this->links)) {
            $object['links'] = $this->links->jsonSerialize();
        }

        if (isset($this->meta)) {
            $object['meta'] = $this->meta->jsonSerialize();
        }

        return $object;
    }

    /**
     * @param AttributesObject|null $attributes
     *
     * @return $this
     */
    private function setAttributes(?AttributesObject $attributes): self
    {
        if (isset($this->relationships) && isset($attributes)) {
            $fields = array_intersect(
                $this->relationships->getFieldNames(),
                $attributes->getFieldNames()
            );

            throw new InvalidArgumentException(sprintf(
                'Field names must not appear as attributes and relationships: %s',
                implode(', ', $fields)
            ));
        }

        $this->attributes = $attributes;

        return $this;
    }

    /**
     * @param string|null $id
     *
     * @return $this
     */
    private function setId(?string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param LinksObject|null $links
     *
     * @return $this
     */
    private function setLinks(?LinksObject $links): self
    {
        $this->links = $links;

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
     * @param MetaObject|null $relationships
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    private function setRelationships(
        ?RelationshipsObject $relationships
    ): self {
        if (isset($relationships) && isset($this->attributes)) {
            $fields = array_intersect(
                $relationships->getFieldNames(),
                $this->attributes->getFieldNames()
            );

            throw new InvalidArgumentException(sprintf(
                'Field names must not appear as attributes and relationships: %s',
                implode(', ', $fields)
            ));
        }

        $this->relationships = $relationships;

        return $this;
    }

    /**
     * @param string $type
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    private function setType(string $type): self
    {
        if (preg_match('/^[a-z\d]+([\-_][a-z\d]+)*$/i', $type) !== 1) {
            throw new InvalidArgumentException('Invalid type name: ' . $type);
        }

        $this->type = $type;

        return $this;
    }

    /**
     * @param AttributesObject $attributes
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    public function withAttributes(AttributesObject $attributes): self
    {
        return (clone $this)
            ->setAttributes($attributes);
    }

    /**
     * @param LinksObject $links
     *
     * @return $this
     */
    public function withLinks(LinksObject $links): self
    {
        return (clone $this)
            ->setLinks($links);
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
     * @param RelationshipsObject $relationships
     *
     * @return $this
     *
     * @covers InvalidArgumentException
     */
    public function withRelationships(RelationshipsObject $relationships): self
    {
        return (clone $this)
            ->setRelationships($relationships);
    }

    /**
     * @return $this
     */
    public function withoutAttributes(): self
    {
        return (clone $this)
            ->setAttributes(null);
    }

    /**
     * @return $this
     */
    public function withoutLinks(): self
    {
        return (clone $this)
            ->setLinks(null);
    }

    /**
     * @return $this
     */
    public function withoutMeta(): self
    {
        return (clone $this)
            ->setMeta(null);
    }

    /**
     * @return $this
     */
    public function withoutRelationships(): self
    {
        return (clone $this)
            ->setRelationships(null);
    }
}
