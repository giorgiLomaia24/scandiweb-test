<?php

declare(strict_types=1);

namespace App\GraphQL\Resolvers;

use App\Repositories\AttributeRepository;
use App\Models\Product\AbstractProduct;

/**
 * Class AttributeResolver
 *
 * Resolves attributes for a given product in GraphQL queries.
 */
class AttributeResolver
{
    /**
     * Resolves attributes for a given product.
     *
     * @param AbstractProduct $product The product instance.
     * @return array The attributes of the product.
     */
    public static function resolveAttributes(AbstractProduct $product): array
    {
        return AttributeRepository::getAttributesByProductId($product->getId());
    }
}
