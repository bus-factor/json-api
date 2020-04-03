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
use Iterator;

/**
 * Class Collection
 */
class Collection implements Iterator
{
    private const ERR_TYPE = 'Expected type %s, got %s';

    /**
     * @var string
     */
    private string $itemFqcn;

    /**
     * @var array
     */
    private array $items = [];

    /**
     * @var int
     */
    private int $itemsIndex = 0;

    /**
     * @param string $itemFqcn
     * @param array $items
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $itemFqcn, array $items = [])
    {
        $this->itemFqcn = $itemFqcn;

        foreach ($items as $item) {
            if (get_class($item) !== $this->itemFqcn) {
                throw new InvalidArgumentException(
                    sprintf(self::ERR_TYPE, $this->itemFqcn, get_class($item))
                );
            }
        }

        $this->items = $items;
    }

    /**
     * @return void
     */
    public function __clone()
    {
        $this->itemsIndex = 0;
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
                sprintf(self::ERR_TYPE, $this->itemFqcn, get_class($item))
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
    public function addAll(array $items): self
    {
        foreach ($items as $item) {
            if (get_class($item) !== $this->itemFqcn) {
                throw new InvalidArgumentException(
                    sprintf(self::ERR_TYPE, $this->itemFqcn, get_class($item))
                );
            }
        }

        $collection = clone $this;
        $collection->items = [...$this->items, ...$items];

        return $collection;
    }

    /**
     * @return bool|mixed
     */
    public function current()
    {
        return $this->items[$this->itemsIndex];
    }

    /**
     * @return bool|float|int|mixed|string|null
     */
    public function key()
    {
        return $this->itemsIndex;
    }

    /**
     * @return bool|mixed|void
     */
    public function next()
    {
        $this->itemsIndex++;
    }

    /**
     * @return void
     */
    public function rewind()
    {
        $this->itemsIndex = 0;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return array_key_exists($this->itemsIndex, $this->items);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->items;
    }
}
