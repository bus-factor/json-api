<?php
/**
 * RelationshipObjectInterface.php
 *
 * @author    michael.lessnau
 * @since     29.03.2020
 */

declare(strict_types=1);

namespace JsonApi\Model;

use InvalidArgumentException;
use JsonSerializable;

/**
 * Interface RelationshipObjectInterface
 */
interface RelationshipObjectInterface extends JsonSerializable
{
    /**
     * @return LinksObject|null
     */
    public function getLinks(): ?LinksObject;

    /**
     * @return MetaObject|null
     */
    public function getMeta(): ?MetaObject;

    /**
     * @return array|mixed
     */
    public function jsonSerialize();

    /**
     * @param LinksObject $links
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    public function withLinks(LinksObject $links): self;

    /**
     * @param MetaObject $meta
     *
     * @return $this
     */
    public function withMeta(MetaObject $meta): self;

    /**
     * @return $this
     */
    public function withoutLinks(): self;

    /**
     * @return $this
     */
    public function withoutMeta(): self;
}
