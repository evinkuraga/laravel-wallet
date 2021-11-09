<?php

declare(strict_types=1);

namespace Evinkuraga\Wallet\Test\Common;

use Bavix\Wallet\WalletServiceProvider as ServiceProvider;

class WalletServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        parent::boot();

        $this->loadMigrationsFrom([
            dirname(__DIR__).'/migrations',
        ]);
    }
}
