<?php

namespace PHPSTORM_META {

    use Evinkuraga\Wallet\Interfaces\Mathable;
    use Evinkuraga\Wallet\Interfaces\Rateable;
    use Evinkuraga\Wallet\Interfaces\Storable;
    use Evinkuraga\Wallet\Models\Transaction;
    use Evinkuraga\Wallet\Models\Transfer;
    use Evinkuraga\Wallet\Models\Wallet;
    use Evinkuraga\Wallet\Objects\Bring;
    use Evinkuraga\Wallet\Objects\Cart;
    use Evinkuraga\Wallet\Objects\EmptyLock;
    use Evinkuraga\Wallet\Objects\Operation;
    use Evinkuraga\Wallet\Services\CommonService;
    use Evinkuraga\Wallet\Services\ExchangeService;
    use Evinkuraga\Wallet\Services\WalletService;

    override(\app(0), map([
        Cart::class => Cart::class,
        Bring::class => Bring::class,
        Operation::class => Operation::class,
        EmptyLock::class => EmptyLock::class,
        ExchangeService::class => ExchangeService::class,
        CommonService::class => CommonService::class,
        WalletService::class => WalletService::class,
        Wallet::class => Wallet::class,
        Transfer::class => Transfer::class,
        Transaction::class => Transaction::class,
        Mathable::class => Mathable::class,
        Rateable::class => Rateable::class,
        Storable::class => Storable::class,
    ]));

}
