<?php

/**
 * ErrorObjectCollection.php
 *
 * @author    michael.lessnau
 * @since     29.03.2020
 */

declare(strict_types=1);

namespace JsonApi\Model;

use InvalidArgumentException;

/**
 * Class ErrorObjectCollection
 */
class ErrorObjectCollection extends AbstractObject
{
    /**
     * @var array|ErrorObject[]
     */
    private array $errorObjects = [];

    /**
     * @param array|ErrorObject[] $errorObjects
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $errorObjects)
    {
        foreach ($errorObjects as $errorObject) {
            if (!$errorObject instanceof ErrorObject) {
                throw new InvalidArgumentException(sprintf(
                    'Type mismatch: expected %s, got %s',
                    ErrorObject::class,
                    get_class($errorObject)
                ));
            }

            $this->errorObjects[] = $errorObject;
        }
    }

    /**
     * @return array|ErrorObject[]
     */
    public function getErrorObjects(): array
    {
        return $this->errorObjects;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        $object = [];

        /** @var ErrorObject $errorObject */
        foreach ($this->errorObjects as $errorObject) {
            $object[] = $errorObject->jsonSerialize();
        }

        return $object;
    }
}
