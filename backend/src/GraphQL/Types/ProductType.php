<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\GraphQL\Resolvers\AttributeResolver;
use App\GraphQL\Resolvers\PriceResolver;
use App\Models\Product\AbstractProduct;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class ProductType
 *
 * Represents the GraphQL type definition for a product.
 */
class ProductType extends ObjectType
{
    private static ?self $instance = null;

    /**
     * Provides a singleton instance of the ProductType.
     *
     * @return self
     */
    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * ProductType constructor.
     *
     * Defines the GraphQL fields for a product.
     */
    public function __construct()
    {
        parent::__construct([
            'name' => 'Product',
            'fields' => [
                'id' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => fn(AbstractProduct $product): string => $product->getId(),
                ],
                'name' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => fn(AbstractProduct $product): string => $product->getName(),
                ],
                'description' => [
                    'type' => Type::string(),
                    'resolve' => fn(AbstractProduct $product): string => $product->getDescription() ?? '',
                ],
                'category_id' => Type::int(),
                'in_stock' => [
                    'type' => Type::boolean(),
                    'resolve' => fn(AbstractProduct $product): bool => $product->canBeSold(),
                ],
                'brand' => Type::string(),
                'gallery' => [
                    'type' => Type::listOf(Type::string()),
                    'resolve' => fn(AbstractProduct $product): array => $product->getGallery(),
                ],
                'formatted_price' => [
                    'type' => Type::string(),
                    'resolve' => fn(AbstractProduct $product): string => $product->getFormattedPrice(),
                ],
                'attributes' => [
                    'type' => Type::listOf(AttributeType::getInstance()),
                    'resolve' => fn(AbstractProduct $product): array => AttributeResolver::resolveAttributes($product),
                ],
                'price' => [
                    'type' => PriceType::getInstance(),
                    'resolve' => fn(AbstractProduct $product): array => PriceResolver::resolvePrice($product),
                ],
            ],
        ]);
    }
}
