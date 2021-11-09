<?php

namespace Evinkuraga\Wallet\Test\Models;

use Bavix\Wallet\Interfaces\Customer;
use Bavix\Wallet\Traits\CanPay;
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
