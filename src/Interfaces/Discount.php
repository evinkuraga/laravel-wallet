<?php

declare(strict_types=1);

namespace Evinkuraga\Wallet\Interfaces;

interface Discount extends Product
{
    /**
     * @return float|int
     */
    public function getPersonalDiscount(Customer $customer);
}
