<?php

/**
 * ResourceDocument.php
 *
 * Author: Michael Leßnau <michael.lessnau@gmail.com>
 * Date:   2020-04-04
 */

declare(strict_types=1);

namespace JsonApi\Model;

/**
 * Class ResourceDocument
 */
class ResourceDocument extends AbstractDocument
{
    /**
     * @var ResourceInterface|null
     */
    private ?ResourceInterface $data;

    /**
     * @var ResourceCollectionInterface|null
     */
    private ?ResourceCollectionInterface $included = null;

    /**
     * @var MetaObject|null
     */
    private ?MetaObject $meta = null;

    /**
     * @param ResourceInterface|null $data
     */
    public function __construct(?ResourceInterface $data = null)
    {
        $this->data = $data;
    }

    /**
     * @return ResourceInterface|null
     */
    public function getData(): ?ResourceInterface
    {
        return $this->data;
    }

    /**
     * @return ResourceCollectionInterface|null
     */
    public function getIncluded(): ?ResourceCollectionInterface
    {
        return $this->included;
    }

    /**
     * @return MetaObject|null
     */
    public function getMeta(): ?MetaObject
    {
        return $this->meta;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        $document = parent::jsonSerialize();
        $document['data'] = isset($this->data) ? $this->data->jsonSerialize() : null;

        if (isset($this->meta)) {
            $document['meta'] = $this->meta->jsonSerialize();
        }

        if (isset($this->included)) {
            $document['included'] = $this->included->jsonSerialize();
        }

        return $document;
    }

    /**
     * @param ResourceInterface|null $data
     *
     * @return $this
     */
    private function setData(?ResourceInterface $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param ResourceCollectionInterface|null $included
     *
     * @return $this
     */
    private function setIncluded(?ResourceCollectionInterface $included): self
    {
        // todo: verify full linkage (data <-> included)

        $this->included = $included;

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
     * @param ResourceInterface $data
     *
     * @return $this
     */
    public function withData(ResourceInterface $data): self
    {
        return (clone $this)
            ->setData($data);
    }

    /**
     * @param ResourceCollectionInterface $included
     *
     * @return $this
     */
    public function withIncluded(ResourceCollectionInterface $included): self
    {
        return (clone $this)
            ->setIncluded($included);
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
     * @return $this
     */
    public function withoutData(): self
    {
        return (clone $this)
            ->setData(null);
    }

    /**
     * @return $this
     */
    public function withoutIncluded(): self
    {
        return (clone $this)
            ->setIncluded(null);
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