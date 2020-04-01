<?php

/**
 * AbstractObject.php
 *
 * @author    michael.lessnau
 * @since     27.03.2020
 */

declare(strict_types=1);

namespace JsonApi\Model;

use JsonSerializable;

/**
 * Class AbstractObject
 */
abstract class AbstractObject implements JsonSerializable
{
    /**
     * @param string $name
     *
     * @return bool
     */
    public function isReservedFieldName(string $name): bool
    {
        return in_array($name, ['id', 'type'], true);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function isValidMemberName(string $name): bool
    {
        return preg_match('/^[a-z\d]+([\-_][a-z\d]+)*$/i', $name) === 1;
    }
}
