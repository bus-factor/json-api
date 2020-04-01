<?php

/**
 * LinkObject.php
 *
 * @author    michael.lessnau
 * @since     27.03.2020
 */

declare(strict_types=1);

namespace JsonApi\Model;

use InvalidArgumentException;

/**
 * Class LinkObject
 */
class LinkObject extends AbstractObject
{
    /**
     * @var string
     */
    private string $href;

    /**
     * @var MetaObject|null
     */
    private ?MetaObject $meta = null;

    /**
     * @param string $href
     * @param MetaObject|null $meta
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $href, ?MetaObject $meta = null)
    {
        $this->setHref($href);
        $this->setMeta($meta);
    }

    /**
     * @return string
     */
    public function getHref(): string
    {
        return $this->href;
    }

    /**
     * @return MetaObject|null
     */
    public function getMeta(): ?MetaObject
    {
        return $this->meta;
    }

    /**
     * @return string|array
     */
    public function jsonSerialize()
    {
        if (!isset($this->meta)) {
            return $this->href;
        }

        return [
            'href' => $this->href,
            'meta' => $this->meta->jsonSerialize(),
        ];
    }

    /**
     * @param string $href
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    private function setHref(string $href): self
    {
        if (filter_var($href, FILTER_VALIDATE_URL) === false) {
            throw new InvalidArgumentException('Invalid URL: ' . $href);
        }

        $this->href = $href;

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
     * @param string $href
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    public function withHref(string $href): self
    {
        return (clone $this)
            ->setHref($href);
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
