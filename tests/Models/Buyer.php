<?php

namespace Evinkuraga\Wallet\Test\Models;

use Evinkuraga\Wallet\Interfaces\Customer;
use Evinkuraga\Wallet\Traits\CanPay;
use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Class User.
 *
 * @property string $name
 * @property string $email
 */
class Buyer extends Model implements Customer
{
    use CanPay;

    public function getTable(): string
    {
        return 'users';
    }
}
