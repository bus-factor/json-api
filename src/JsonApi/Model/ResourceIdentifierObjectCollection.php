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

/**
 * Class ResourceIdentifierObjectCollection
 */
class ResourceIdentifierObjectCollection extends AbstractObject
{
    /**
     * @var ResourceIdentifierObject[]
     */
    private array $resourceIdentifierObjects = [];

    /**
     * @param ResourceIdentifierObject[] $resourceIdentifierObjects
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $resourceIdentifierObjects = [])
    {
        foreach ($resourceIdentifierObjects as $resourceIdentifierObject) {
            if (!$resourceIdentifierObject instanceof ResourceIdentifierObject) {
                throw new InvalidArgumentException(sprintf(
                    'Type mismatch: expected %s, got %s',
                    ResourceIdentifierObject::class,
                    get_class($resourceIdentifierObject)
                ));
            }

            $this->resourceIdentifierObjects[] = $resourceIdentifierObject;
        }
    }

    /**
     * @return array
     */
    public function getResourceIdentifierObjects(): array
    {
        return $this->resourceIdentifierObjects;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        $object = [];

        foreach ($this->resourceIdentifierObjects as $resourceIdentifierObject) {
            $object[] = $resourceIdentifierObject->jsonSerialize();
        }

        return $object;
    }
}
