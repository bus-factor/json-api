<?php

/**
 * ResourceInterface.php
 *
 * Author: Michael Leßnau <michael.lessnau@gmail.com>
 * Date:   2020-04-04
 */

declare(strict_types=1);

namespace JsonApi\Model;

use JsonSerializable;

/**
 * Interface ResourceInterface
 */
interface ResourceInterface extends JsonSerializable
{
    /**
     * @return string
     */
    public function getId(): ?string;

    /**
     * @return MetaObject|null
     */
    public function getMeta(): ?MetaObject;

    /**
     * @return string
     */
    public function getType(): string;
}