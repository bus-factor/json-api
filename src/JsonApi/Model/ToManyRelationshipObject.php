<?php

/**
 * ToManyRelationshipObject.php
 *
 * @author    michael.lessnau
 * @since     29.03.2020
 */

declare(strict_types=1);

namespace JsonApi\Model;

/**
 * Class ToManyRelationshipObject
 */
class ToManyRelationshipObject implements RelationshipObjectInterface
{
    use RelationshipObjectTrait;

    /**
     * @var ResourceIdentifierObjectCollection|null
     */
    private ?ResourceIdentifierObjectCollection $data = null;

    /**
     * @return ResourceIdentifierObjectCollection|null
     */
    public function getData(): ?ResourceIdentifierObjectCollection
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
            : [];

        return $object;
    }

    /**
     * @param ResourceIdentifierObjectCollection|null $data
     *
     * @return $this
     */
    private function setData(?ResourceIdentifierObjectCollection $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param ResourceIdentifierObjectCollection $data
     *
     * @return $this
     */
    public function withData(ResourceIdentifierObjectCollection $data): self
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
