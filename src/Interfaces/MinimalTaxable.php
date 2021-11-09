<?php

declare(strict_types=1);

namespace Evinkuraga\Wallet\Interfaces;

interface MinimalTaxable extends Taxable
{
    /**
     * @return float|int
     */
    public function getMinimalFee();
}
