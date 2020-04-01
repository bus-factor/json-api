<?php

/**
 * ErrorSourceObject.php
 *
 * @author    michael.lessnau
 * @since     28.03.2020
 */

declare(strict_types=1);

namespace JsonApi\Model;

/**
 * Class ErrorSourceObject
 */
class ErrorSourceObject extends AbstractObject
{
    /**
     * @var string|null
     */
    private ?string $parameter = null;

    /**
     * @var string|null
     */
    private ?string $pointer = null;

    /**
     * @return string|null
     */
    public function getParameter(): ?string
    {
        return $this->parameter;
    }

    /**
     * @return string|null
     */
    public function getPointer(): ?string
    {
        return $this->pointer;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        $object = [];

        if (isset($this->parameter)) {
            $object['parameter'] = $this->parameter;
        }

        if (isset($this->pointer)) {
            $object['pointer'] = $this->pointer;
        }

        return $object;
    }

    /**
     * @param string|null $parameter
     *
     * @return $this
     */
    private function setParameter(?string $parameter): self
    {
        $this->parameter = $parameter;

        return $this;
    }

    /**
     * @param string|null $pointer
     *
     * @return $this
     */
    private function setPointer(?string $pointer): self
    {
        $this->pointer = $pointer;

        return $this;
    }

    /**
     * @param string $parameter
     *
     * @return $this
     */
    public function withParameter(string $parameter): self
    {
        return (clone $this)
            ->setParameter($parameter);
    }

    /**
     * @param string $pointer
     *
     * @return $this
     */
    public function withPointer(string $pointer): self
    {
        return (clone $this)
            ->setPointer($pointer);
    }

    /**
     * @return $this
     */
    public function withoutParameter(): self
    {
        return (clone $this)
            ->setParameter(null);
    }

    /**
     * @return $this
     */
    public function withoutPointer(): self
    {
        return (clone $this)
            ->setPointer(null);
    }
}
