<?php

declare(strict_types=1);

namespace Evinkuraga\Wallet\Internal;

use Evinkuraga\Wallet\Internal\Dto\AvailabilityDto;

interface BasketInterface
{
    public function availability(AvailabilityDto $availabilityDto): bool;
}
