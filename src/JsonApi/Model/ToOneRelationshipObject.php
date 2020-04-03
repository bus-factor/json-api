<?php

/**
 * ToOneRelationshipObject.php
 *
 * @author    michael.lessnau
 * @since     29.03.2020
 */

declare(strict_types=1);

namespace JsonApi\Model;

/**
 * Class ToOneRelationshipObject
 */
class ToOneRelationshipObject implements RelationshipObjectInterface
{
    use RelationshipObjectTrait;

    /**
     * @var ResourceIdentifierObject|null
     */
    private ?ResourceIdentifierObject $data = null;

    /**
     * @return ResourceIdentifierObject|null
     */
    public function getData(): ?ResourceIdentifierObject
    {
        return $this->data;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        $object = [];

        if (isset($this->links)) {
            $object['links'] = $this->links->jsonSerialize();
        }

        if (isset($this->meta)) {
            $object['meta'] = $this->meta->jsonSerialize();
        }

        $object['data'] = isset($this->data)
            ? $this->data->jsonSerialize()
            : null;

        return $object;
    }

    /**
     * @param ResourceIdentifierObject|null $data
     *
     * @return $this
     */
    private function setData(?ResourceIdentifierObject $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param ResourceIdentifierObject $data
     *
     * @return $this
     */
    public function withData(ResourceIdentifierObject $data): self
    {
        return (clone $this)
            ->setData($data);
    }

    /**
     * @return $this
     */
    public function withoutData(): self
    {
        return (clone $this)
            ->setData(null);
    }
}
