<?php

declare(strict_types=1);

namespace Evinkuraga\Wallet\Services;

use Evinkuraga\Wallet\Interfaces\Product;
use Evinkuraga\Wallet\Internal\CartInterface;

/** @deprecated */
final class MetaService
{
    public function getMeta(CartInterface $cart, Product $product): ?array
    {
        $metaCart = $cart->getBasketDto()->meta();
        $metaProduct = $product->getMetaProduct();

        if ($metaProduct !== null) {
            return array_merge($metaCart, $metaProduct);
        }

        if (count($metaCart) > 0) {
            return $metaCart;
        }

        return null;
    }
}
