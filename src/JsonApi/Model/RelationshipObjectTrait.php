<?php
/**
 * RelationshipObjectTrait.php
 *
 * @author    michael.lessnau
 * @since     29.03.2020
 */

declare(strict_types=1);

namespace JsonApi\Model;

use InvalidArgumentException;

/**
 * Trait RelationshipObjectTrait
 */
trait RelationshipObjectTrait
{
    /**
     * @var LinksObject|null
     */
    protected ?LinksObject $links = null;

    /**
     * @var MetaObject|null
     */
    protected ?MetaObject $meta = null;

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
     * @param LinksObject|null $links
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    protected function setLinks(?LinksObject $links): self
    {
        if (
            isset($links)
            && !$links->hasLink('self')
            && !$links->hasLink('related')
        ) {
            throw new InvalidArgumentException('Missing link: self or related');
        }

        $this->links = $links;

        return $this;
    }

    /**
     * @param MetaObject|null $meta
     *
     * @return $this
     */
    protected function setMeta(?MetaObject $meta): self
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * @param LinksObject $links
     *
     * @return $this
     *
     * @throws InvalidArgumentException
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
}
