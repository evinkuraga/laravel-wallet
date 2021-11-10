<?php

namespace Evinkuraga\Wallet\Test\Common;

use Evinkuraga\Wallet\Interfaces\Wallet;
use Evinkuraga\Wallet\Internal\ExchangeInterface;
use Evinkuraga\Wallet\Internal\MathInterface;
use Evinkuraga\Wallet\Services\WalletService;
use Illuminate\Support\Arr;

class Rate extends \Bavix\Wallet\Simple\Rate
{
    /**
     * @var array
     */
    protected $rates = [
        'USD' => [
            'RUB' => 67.61,
        ],
    ];

    private $walletService;

    private $mathService;

    /**
     * Rate constructor.
     */
    public function __construct(ExchangeInterface $exchange, MathInterface $mathService, WalletService $walletService)
    {
        parent::__construct($exchange);
        $this->walletService = $walletService;
        $this->mathService = $mathService;

        foreach ($this->rates as $from => $rates) {
            foreach ($rates as $to => $rate) {
                if (empty($this->rates[$to][$from])) {
                    $this->rates[$to][$from] = $this->mathService->div(1, $rate);
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function convertTo(Wallet $wallet)
    {
        return $this->mathService->mul(
            parent::convertTo($wallet),
            $this->rate($wallet)
        );
    }

    /**
     * @return float|int
     */
    protected function rate(Wallet $wallet)
    {
        $from = $this->walletService->getWallet($this->withCurrency);
        $to = $this->walletService->getWallet($wallet);

        /**
         * @var \Bavix\Wallet\Models\Wallet $wallet
         */
        return Arr::get(
            Arr::get($this->rates, $from->currency, []),
            $to->currency,
            1
        );
    }
}
