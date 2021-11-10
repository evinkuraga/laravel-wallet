<?php

declare(strict_types=1);

namespace Evinkuraga\Wallet\Internal;

use Evinkuraga\Wallet\Internal\Dto\BasketDto;

interface CartInterface
{
    public function getBasketDto(): BasketDto;
}
