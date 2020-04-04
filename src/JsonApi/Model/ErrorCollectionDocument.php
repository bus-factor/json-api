<?php

/**
 * ErrorCollectionDocument.php
 *
 * Author: Michael Leßnau <michael.lessnau@gmail.com>
 * Date:   2020-04-04
 */

declare(strict_types=1);

namespace JsonApi\Model;

/**
 * Class ErrorCollectionDocument
 */
class ErrorCollectionDocument extends AbstractDocument
{
    /**
     * @var ErrorObjectCollection
     */
    private ErrorObjectCollection $errors;

    /**
     * @var MetaObject|null
     */
    private ?MetaObject $meta = null;

    /**
     * @param ErrorObjectCollection $errors
     */
    public function __construct(ErrorObjectCollection $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @return ErrorObjectCollection
     */
    public function getErrors(): ErrorObjectCollection
    {
        return $this->errors;
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
        $document['errors'] = $this->errors->jsonSerialize();

        if (isset($this->meta)) {
            $document['meta'] = $this->meta->jsonSerialize();
        }

        return $document;
    }

    /**
     * @param ErrorObjectCollection $errors
     *
     * @return $this
     */
    private function setErrors(ErrorObjectCollection $errors): self
    {
        $this->errors = $errors;

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
     * @param ErrorObjectCollection $errors
     *
     * @return $this
     */
    public function withErrors(ErrorObjectCollection $errors): self
    {
        return (clone $this)
            ->setErrors($errors);
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
    public function withoutMeta(): self
    {
        return (clone $this)
            ->setMeta(null);
    }
}
