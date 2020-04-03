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
use JsonSerializable;

/**
 * Class ErrorObjectCollection
 */
class ErrorObjectCollection extends Collection implements JsonSerializable
{
    /**
     * @param array|ErrorObject[] $errorObjects
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $errorObjects)
    {
        parent::__construct(ErrorObject::class, $errorObjects);
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        $object = [];

        /** @var ErrorObject $errorObject */
        foreach ($this as $errorObject) {
            $object[] = $errorObject->jsonSerialize();
        }

        return $object;
    }
}
