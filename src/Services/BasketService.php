<?php

declare(strict_types=1);

namespace Evinkuraga\Wallet\Services;

use Evinkuraga\Wallet\Internal\BasketInterface;
use Evinkuraga\Wallet\Internal\Dto\AvailabilityDto;

class BasketService implements BasketInterface
{
    public function availability(AvailabilityDto $availabilityDto): bool
    {
        $basketDto = $availabilityDto->getBasketDto();
        $customer = $availabilityDto->getCustomer();
        foreach ($basketDto->items() as $itemDto) {
            if (!$itemDto->product()->canBuy($customer, $itemDto->count(), $availabilityDto->isForce())) {
                return false;
            }
        }

        return true;
    }
}
