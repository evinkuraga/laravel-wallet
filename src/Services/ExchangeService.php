<?php

declare(strict_types=1);

namespace Evinkuraga\Wallet\Services;

use Evinkuraga\Wallet\Interfaces\Rateable;
use Evinkuraga\Wallet\Interfaces\Wallet;
use Evinkuraga\Wallet\Internal\ExchangeInterface;

/**
 * @deprecated
 * @see ExchangeInterface
 */
class ExchangeService
{
    private Rateable $rate;

    public function __construct(Rateable $rate)
    {
        $this->rate = $rate;
    }

    /**
     * @return float|int
     */
    public function rate(Wallet $from, Wallet $to)
    {
        return $this->rate
            ->withAmount(1)
            ->withCurrency($from)
            ->convertTo($to)
        ;
    }
}
