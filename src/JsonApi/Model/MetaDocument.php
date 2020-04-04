<?php

/**
 * MetaDocument.php
 *
 * Author: Michael Leßnau <michael.lessnau@gmail.com>
 * Date:   2020-04-04
 */

declare(strict_types=1);

namespace JsonApi\Model;

/**
 * Class MetaDocument
 */
class MetaDocument extends AbstractDocument
{
    /**
     * @var MetaObject
     */
    private MetaObject $meta;

    /**
     * @param MetaObject $meta
     */
    public function __construct(MetaObject $meta)
    {
        $this->meta = $meta;
    }

    /**
     * @return MetaObject
     */
    public function getMeta(): MetaObject
    {
        return $this->meta;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        $document = parent::jsonSerialize();
        $document['meta'] = $this->meta->jsonSerialize();

        return $document;
    }

    /**
     * @param MetaObject $meta
     *
     * @return $this
     */
    public function setMeta(MetaObject $meta): self
    {
        $this->meta = $meta;

        return $this;
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
}