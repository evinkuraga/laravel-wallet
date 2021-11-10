<?php

namespace Evinkuraga\Wallet\Test\Models;

use Evinkuraga\Wallet\Interfaces\Customer;
use Evinkuraga\Wallet\Interfaces\Product;
use Evinkuraga\Wallet\Models\Transfer;
use Evinkuraga\Wallet\Models\Wallet;
use Evinkuraga\Wallet\Services\WalletService;
use Evinkuraga\Wallet\Traits\HasWallet;
use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Class Item.
 *
 * @property string $name
 * @property int    $quantity
 * @property int    $price
 */
class Item extends Model implements Product
{
    use HasWallet;

    /**
     * @var array
     */
    protected $fillable = ['name', 'quantity', 'price'];

    public function canBuy(Customer $customer, int $quantity = 1, bool $force = false): bool
    {
        $result = $this->quantity >= $quantity;

        if ($force) {
            return $result;
        }

        return $result && !$customer->paid($this);
    }

    /**
     * @return float|int
     */
    public function getAmountProduct(Customer $customer)
    {
        /** @var Wallet $wallet */
        $wallet = app(WalletService::class)->getWallet($customer);

        return $this->price + $wallet->holder_id;
    }

    public function getMetaProduct(): ?array
    {
        return null;
    }

    public function getUniqueId(): string
    {
        return $this->getKey();
    }

    /**
     * @param int[] $walletIds
     */
    public function boughtGoods(array $walletIds): MorphMany
    {
        return $this
            ->morphMany(config('wallet.transfer.model', Transfer::class), 'to')
            ->where('status', Transfer::STATUS_PAID)
            ->where('from_type', config('wallet.wallet.model', Wallet::class))
            ->whereIn('from_id', $walletIds)
        ;
    }
}
