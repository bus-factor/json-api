<?php

/**
 * ResourceIdentifierObjectCollection.php
 *
 * @author    michael.lessnau
 * @since     29.03.2020
 */

declare(strict_types=1);

namespace JsonApi\Model;

use InvalidArgumentException;
use JsonSerializable;

/**
 * Class ResourceIdentifierObjectCollection
 */
class ResourceIdentifierObjectCollection extends Collection implements
    JsonSerializable,
    ResourceCollectionInterface
{
    /**
     * @param ResourceIdentifierObject[] $resourceIdentifierObjects
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $resourceIdentifierObjects = [])
    {
        parent::__construct(
            ResourceIdentifierObject::class,
            $resourceIdentifierObjects
        );
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        $object = [];

        foreach ($this as $resourceIdentifierObject) {
            $object[] = $resourceIdentifierObject->jsonSerialize();
        }

        return $object;
    }
}
