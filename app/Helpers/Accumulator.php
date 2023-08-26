<?php

declare(strict_types=1);

namespace App\Helpers;

use Closure;
use Illuminate\Support\Collection;

/**
 * Collect information and proceed it in parts.
 *
 * @template TKey of array-key
 *
 * @template-covariant TValue
 *
 * @extends Collection<TKey, TValue>
 */
final class Accumulator extends Collection
{
    /** @var Closure function, that will be used after the accumulator is full */
    private readonly Closure $callback;

    /** @var int max amount of elements before execution */
    private readonly int $threshold;

    /** @var int the amount of items in the accumulator */
    private int $count = 0;

    /** @var int total amount of added items */
    private int $total = 0;

    public function __construct(
        Closure $callback,
        int $threshold = 100
    ) {
        $this->callback = $callback;
        $this->threshold = $threshold;

        parent::__construct();
    }

    /**
     * Add new element.
     *
     * @param mixed $item
     *
     * @return $this
     */
    public function add(mixed $item): self
    {
        $this->items[] = $item;

        ++$this->total;

        return ++$this->count >= $this->threshold ? $this->release() : $this;
    }

    /**
     * Add many new elements.
     *
     * @param iterable<mixed> $items
     *
     * @return $this
     */
    public function addMany(iterable $items): self
    {
        foreach ($items as $item) {
            $this->add($item);
        }

        return $this;
    }

    /**
     * Run the callback.
     *
     * @return $this
     */
    public function release(): self
    {
        if ($this->items) {
            $callback = $this->callback;

            $callback($this->items);
        }

        return $this->clear();
    }

    /**
     * Delete all items that we have.
     *
     * @return $this
     */
    public function clear(): self
    {
        $this->items = [];
        $this->count = 0;

        return $this;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getCount(): int
    {
        return $this->count;
    }
}
