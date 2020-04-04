<?php

/**
 * ResourceObjectCollection.php
 *
 * @author    michael.lessnau
 * @since     29.03.2020
 */

declare(strict_types=1);

namespace JsonApi\Model;

use InvalidArgumentException;
use JsonSerializable;

/**
 * Class ResourceObjectCollection
 */
class ResourceObjectCollection extends Collection implements
    JsonSerializable,
    ResourceCollectionInterface
{
    /**
     * @param ResourceObject[] $resourceObjects
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $resourceObjects = [])
    {
        parent::__construct(ResourceObject::class, $resourceObjects);
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        $object = [];

        foreach ($this as $resourceObject) {
            $object[] = $resourceObject->jsonSerialize();
        }

        return $object;
    }
}
