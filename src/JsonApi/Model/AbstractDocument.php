<?php

/**
 * AbstractDocument.php
 *
 * Author: Michael Leßnau <michael.lessnau@gmail.com>
 * Date:   2020-04-04
 */

declare(strict_types=1);

namespace JsonApi\Model;

use JsonSerializable;

/**
 * Class AbstractDocument
 */
abstract class AbstractDocument implements JsonSerializable
{
    /**
     * @var JsonapiObject|null
     */
    private ?JsonapiObject $jsonapi = null;

    /**
     * @var LinksObject|null
     */
    private ?LinksObject $links = null;

    /**
     * @return JsonapiObject|null
     */
    public function getJsonapi(): ?JsonapiObject
    {
        return $this->jsonapi;
    }

    /**
     * @return LinksObject|null
     */
    public function getLinks(): ?LinksObject
    {
        return $this->links;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        $document = [];

        if (isset($this->jsonapi)) {
            $document['jsonapi'] = $this->jsonapi->jsonSerialize();
        }

        if (isset($this->links)) {
            $document['links'] = $this->links->jsonSerialize();
        }

        return $document;
    }

    /**
     * @param JsonapiObject|null $jsonapi
     *
     * @return $this
     */
    protected function setJsonapi(?JsonapiObject $jsonapi): self
    {
        $this->jsonapi = $jsonapi;

        return $this;
    }

    /**
     * @param LinksObject|null $links
     *
     * @return $this
     */
    protected function setLinks(?LinksObject $links): self
    {
        $this->links = $links;

        return $this;
    }

    /**
     * @param JsonapiObject $jsonapi
     *
     * @return $this
     */
    public function withJsonapi(JsonapiObject $jsonapi): self
    {
        return (clone $this)
            ->setJsonapi($jsonapi);
    }

    /**
     * @param LinksObject $links
     *
     * @return $this
     */
    public function withLinks(LinksObject $links): self
    {
        return (clone $this)
            ->setLinks($links);
    }

    /**
     * @return $this
     */
    public function withoutJsonapi(): self
    {
        return (clone $this)
            ->setJsonapi(null);
    }

    /**
     * @return $this
     */
    public function withoutLinks(): self
    {
        return (clone $this)
            ->setLinks(null);
    }
}