<?php

/**
 * LinksObject.php
 *
 * @author    michael.lessnau
 * @since     27.03.2020
 */

declare(strict_types=1);

namespace JsonApi\Model;

use InvalidArgumentException;

/**
 * Class LinksObject
 */
class LinksObject extends AbstractObject
{
    /**
     * @var LinkObject[]
     */
    private array $links = [];

    /**
     * @param LinkObject[] $links
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $links = [])
    {
        $this->setLinks($links);
    }

    /**
     * @param string $name
     *
     * @return LinkObject
     *
     * @throws InvalidArgumentException
     */
    public function getLink(string $name): LinkObject
    {
        if (!$this->hasLink($name)) {
            throw new InvalidArgumentException('Undefined link: ' . $name);
        }

        return $this->links[$name];
    }

    /**
     * @return LinkObject[]
     */
    public function getLinks(): array
    {
        return $this->links;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasLink(string $name): bool
    {
        return array_key_exists($name, $this->links);
    }

    /**
     * @return LinkObject[]
     */
    public function jsonSerialize()
    {
        $object = [];

        foreach ($this->links as $name => $link) {
            $object[$name] = $link->jsonSerialize();
        }

        return $object;
    }

    /**
     * @param string $name
     * @param LinkObject $link
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    private function setLink(string $name, LinkObject $link): self
    {
        if (!$this->isValidMemberName($name)) {
            throw new InvalidArgumentException('Invalid link name: ' . $name);
        }

        $this->links[$name] = $link;

        return $this;
    }

    /**
     * @param LinkObject[] $links
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    private function setLinks(array $links): self
    {
        foreach ($links as $name => $link) {
            $this->setLink($name, $link);
        }

        return $this;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    private function unsetLink(string $name): self
    {
        unset($this->links[$name]);

        return $this;
    }

    /**
     * @return $this
     */
    private function unsetLinks(): self
    {
        $this->links = [];

        return $this;
    }

    /**
     * @param string $name
     * @param LinkObject $link
     *
     * @return $this
     */
    public function withLink(string $name, LinkObject $link): self
    {
        return (clone $this)
            ->setLink($name, $link);
    }

    /**
     * @param LinkObject[] $links
     *
     * @return $this
     */
    public function withLinks(array $links): self
    {
        return (clone $this)
            ->setLinks($links);
    }

    /**
     * @param string $name
     *
     * @return LinksObject
     */
    public function withoutLink(string $name): self
    {
        return (clone $this)
            ->unsetLink($name);
    }

    /**
     * @return $this
     */
    public function withoutLinks(): self
    {
        return (clone $this)
            ->unsetLinks();
    }
}
