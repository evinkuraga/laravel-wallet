<?php

declare(strict_types=1);

namespace Evinkuraga\Wallet\Internal;

use Bavix\Wallet\Internal\Dto\BasketDto;

interface CartInterface
{
    public function getBasketDto(): BasketDto;
}
