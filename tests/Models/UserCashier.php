<?php

namespace Evinkuraga\Wallet\Test\Models;

use Evinkuraga\Wallet\Traits\HasWallets;
use Evinkuraga\Wallet\Traits\MorphOneWallet;
use Jenssegers\Mongodb\Eloquent\Model;
use Laravel\Cashier\Billable;

/**
 * Class User.
 *
 * @property string $name
 * @property string $email
 */
class UserCashier extends Model
{
    use Billable;
    use HasWallets;
    use MorphOneWallet;

    public function getTable(): string
    {
        return 'users';
    }
}
