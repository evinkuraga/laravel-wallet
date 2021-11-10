<?php

namespace Evinkuraga\Wallet\Test\Models;

use Evinkuraga\Wallet\Interfaces\Customer;
use Evinkuraga\Wallet\Interfaces\Discount;
use Evinkuraga\Wallet\Services\WalletService;

class ItemDiscount extends Item implements Discount
{
    public function getTable(): string
    {
        return 'items';
    }

    public function getPersonalDiscount(Customer $customer): int
    {
        return app(WalletService::class)
            ->getWallet($customer)
            ->holder_id;
    }
}
