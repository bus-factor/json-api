<?php

/**
 * JsonapiObject.php
 *
 * @author    michael.lessnau
 * @since     27.03.2020
 */

declare(strict_types=1);

namespace JsonApi\Model;

use InvalidArgumentException;
use JsonSerializable;

/**
 * Class JsonapiObject
 */
class JsonapiObject implements JsonSerializable
{
    private const VERSIONS = [
        '1.0',
    ];

    /**
     * @var MetaObject|null
     */
    private ?MetaObject $meta = null;

    /**
     * @var string|null
     */
    private ?string $version = null;

    /**
     * @param string|null $version
     * @param MetaObject|null $meta
     *
     * @throws InvalidArgumentException
     */
    public function __construct(
        ?string $version = '1.0',
        ?MetaObject $meta = null
    ) {
        $this->setVersion($version);
        $this->setMeta($meta);
    }

    /**
     * @return MetaObject|null
     */
    public function getMeta(): ?MetaObject
    {
        return $this->meta;
    }

    /**
     * @return string|null
     */
    public function getVersion(): ?string
    {
        return $this->version;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        $object = [];

        if (isset($this->version)) {
            $object['version'] = $this->version;
        }

        if (isset($this->meta)) {
            $object['meta'] = $this->meta->jsonSerialize();
        }

        return $object;
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
     * @param string|null $version
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    private function setVersion(?string $version): self
    {
        if (isset($version) && !in_array($version, self::VERSIONS, true)) {
            throw new InvalidArgumentException('Invalid version: ' . $version);
        }

        $this->version = $version;

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

    /**
     * @param string $version
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    public function withVersion(string $version): self
    {
        return (clone $this)
            ->setVersion($version);
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
    public function withoutVersion(): self
    {
        return (clone $this)
            ->setVersion(null);
    }
}
