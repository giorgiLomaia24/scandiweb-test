<?php

declare(strict_types=1);

namespace App\GraphQL\Resolvers;

use App\Repositories\PriceRepository;
use App\Models\Product\AbstractProduct;

/**
 * Class PriceResolver
 *
 * Resolves product prices in GraphQL queries.
 */
class PriceResolver
{
    /**
     * Resolves the price for a given product.
     *
     * @param AbstractProduct $product The product instance.
     * @return array The price details or an empty array if not found.
     */
    public static function resolvePrice(AbstractProduct $product): array
    {
        return PriceRepository::getPriceByProductId($product->getId());
    }
}
