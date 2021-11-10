<?php

declare(strict_types=1);

namespace Evinkuraga\Wallet\Internal\Dto;

use Evinkuraga\Wallet\Interfaces\Product;
use Countable;

class ItemDto implements Countable
{
    private Product $product;

    private int $quantity;

    public function __construct(Product $product, int $quantity)
    {
        $this->product = $product;
        $this->quantity = $quantity;
    }

    /**
     * @return Product[]
     */
    public function items(): iterable
    {
        return array_fill(0, $this->quantity, $this->product);
    }

    public function product(): Product
    {
        return $this->product;
    }

    public function count(): int
    {
        return $this->quantity;
    }
}
