<?php

declare(strict_types=1);

namespace Evinkuraga\Wallet;

use Evinkuraga\Wallet\Commands\RefreshBalance;
use Evinkuraga\Wallet\Interfaces\Mathable;
use Evinkuraga\Wallet\Interfaces\Rateable;
use Evinkuraga\Wallet\Interfaces\Storable;
use Evinkuraga\Wallet\Internal\BasketInterface;
use Evinkuraga\Wallet\Internal\BookkeeperInterface;
use Evinkuraga\Wallet\Internal\ConsistencyInterface;
use Evinkuraga\Wallet\Internal\ExchangeInterface;
use Evinkuraga\Wallet\Internal\LockInterface;
use Evinkuraga\Wallet\Internal\MathInterface;
use Evinkuraga\Wallet\Internal\PurchaseInterface;
use Evinkuraga\Wallet\Internal\StorageInterface;
use Evinkuraga\Wallet\Internal\UuidInterface;
use Evinkuraga\Wallet\Models\Transaction;
use Evinkuraga\Wallet\Models\Transfer;
use Evinkuraga\Wallet\Models\Wallet;
use Evinkuraga\Wallet\Objects\Bring;
use Evinkuraga\Wallet\Objects\Cart;
use Evinkuraga\Wallet\Objects\EmptyLock;
use Evinkuraga\Wallet\Objects\Operation;
use Evinkuraga\Wallet\Services\AtomicService;
use Evinkuraga\Wallet\Services\BasketService;
use Evinkuraga\Wallet\Services\BookkeeperService;
use Evinkuraga\Wallet\Services\CommonService;
use Evinkuraga\Wallet\Services\ConsistencyService;
use Evinkuraga\Wallet\Services\DbService;
use Evinkuraga\Wallet\Services\ExchangeService;
use Evinkuraga\Wallet\Services\LockService;
use Evinkuraga\Wallet\Services\MathService;
use Evinkuraga\Wallet\Services\MetaService;
use Evinkuraga\Wallet\Services\PurchaseService;
use Evinkuraga\Wallet\Services\StorageService;
use Evinkuraga\Wallet\Services\UuidFactoryService;
use Evinkuraga\Wallet\Services\WalletService;
use Evinkuraga\Wallet\Simple\BrickMath;
use Evinkuraga\Wallet\Simple\Exchange;
use Evinkuraga\Wallet\Simple\Rate;
use Evinkuraga\Wallet\Simple\Store;
use function config;
use function dirname;
use function function_exists;
use Illuminate\Support\ServiceProvider;

class WalletServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @codeCoverageIgnore
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(
            dirname(__DIR__).'/resources/lang',
            'wallet'
        );

        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->commands([RefreshBalance::class]);

        if ($this->shouldMigrate()) {
            $this->loadMigrationsFrom([__DIR__.'/../database']);
        }

        if (function_exists('config_path')) {
            $this->publishes([
                dirname(__DIR__).'/config/config.php' => config_path('wallet.php'),
            ], 'laravel-wallet-config');
        }

        $this->publishes([
            dirname(__DIR__).'/database/' => database_path('migrations'),
        ], 'laravel-wallet-migrations');
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/config/config.php',
            'wallet'
        );

        $this->singletons();
        $this->legacySingleton();
        $this->bindObjects();
    }

    /**
     * Determine if we should register the migrations.
     */
    protected function shouldMigrate(): bool
    {
        return WalletConfigure::isRunsMigrations();
    }

    private function singletons(): void
    {
        // Bind eloquent models to IoC container
        $this->app->singleton(ExchangeInterface::class, config('wallet.package.exchange', Exchange::class));
        $this->app->singleton(MathInterface::class, config('wallet.package.mathable', MathService::class));
        $this->app->singleton(CommonService::class, config('wallet.services.common', CommonService::class));
        $this->app->singleton(WalletService::class, config('wallet.services.wallet', WalletService::class));

        $this->app->singleton(LockInterface::class, AtomicService::class);
        $this->app->singleton(UuidInterface::class, UuidFactoryService::class);
        $this->app->singleton(StorageInterface::class, StorageService::class);
        $this->app->singleton(BookkeeperInterface::class, BookkeeperService::class);
        $this->app->singleton(BasketInterface::class, BasketService::class);
        $this->app->singleton(ConsistencyInterface::class, ConsistencyService::class);
        $this->app->singleton(PurchaseInterface::class, PurchaseService::class);
    }

    private function legacySingleton(): void
    {
        $this->app->singleton(ExchangeService::class, config('wallet.services.exchange', ExchangeService::class));
        $this->app->singleton(Rateable::class, config('wallet.package.rateable', Rate::class));
        $this->app->singleton(Storable::class, config('wallet.package.storable', Store::class));

        $this->app->singleton(Mathable::class, BrickMath::class);

        $this->app->singleton(DbService::class, config('wallet.services.db', DbService::class));
        $this->app->singleton(LockService::class, config('wallet.services.lock', LockService::class));
        $this->app->singleton(MetaService::class);
    }

    private function bindObjects(): void
    {
        // models
        $this->app->bind(Transaction::class, config('wallet.transaction.model', Transaction::class));
        $this->app->bind(Transfer::class, config('wallet.transfer.model', Transfer::class));
        $this->app->bind(Wallet::class, config('wallet.wallet.model', Wallet::class));

        // object's
        $this->app->bind(Bring::class, config('wallet.objects.bring', Bring::class));
        $this->app->bind(Cart::class, config('wallet.objects.cart', Cart::class));
        $this->app->bind(EmptyLock::class, config('wallet.objects.emptyLock', EmptyLock::class));
        $this->app->bind(Operation::class, config('wallet.objects.operation', Operation::class));
    }
}
