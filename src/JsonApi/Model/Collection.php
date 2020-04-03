<?php

/**
 * Collection.php
 *
 * Author: Michael Leßnau <michael.lessnau@gmail.com>
 * Date:   2020-04-02
 */

declare(strict_types=1);

namespace JsonApi\Model;

use InvalidArgumentException;

/**
 * Class Collection
 */
class Collection
{
    /**
     * @var string
     */
    private string $itemFqcn;

    /**
     * @var array
     */
    private array $items;

    /**
     * @param string $itemFqcn
     * @param array $items
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $itemFqcn, array $items = [])
    {
        $this->itemFqcn = $itemFqcn;

        $this->setItems($items);
    }

    /**
     * @param $item
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    public function add($item): self
    {
        if (get_class($item) !== $this->itemFqcn) {
            throw new InvalidArgumentException(
                sprintf(
                    'Expected type %s, got %s',
                    $this->itemFqcn,
                    get_class($item)
                )
            );
        }

        $collection = clone $this;

        $collection->items[] = $item;

        return $collection;
    }

    /**
     * @param array $items
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    private function setItems(array $items): self
    {
        foreach ($items as $item) {
            if (get_class($item) !== $this->itemFqcn) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Expected type %s, got %s',
                        $this->itemFqcn,
                        get_class($item)
                    )
                );
            }
        }

        $this->items = $items;

        return $this;
    }
}
