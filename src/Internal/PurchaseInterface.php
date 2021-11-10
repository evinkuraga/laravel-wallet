<?php

declare(strict_types=1);

namespace Evinkuraga\Wallet\Internal;

use Evinkuraga\Wallet\Interfaces\Customer;
use Evinkuraga\Wallet\Internal\Dto\BasketDto;
use Evinkuraga\Wallet\Models\Transfer;

interface PurchaseInterface
{
    /** @return Transfer[] */
    public function already(Customer $customer, BasketDto $basketDto, bool $gifts = false): array;
}
