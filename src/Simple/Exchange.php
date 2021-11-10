<?php

declare(strict_types=1);

namespace Evinkuraga\Wallet\Simple;

use Evinkuraga\Wallet\Internal\ExchangeInterface;

class Exchange implements ExchangeInterface
{
    public function convertTo(string $fromCurrency, string $toCurrency, $amount): string
    {
        return (string) $amount;
    }
}
