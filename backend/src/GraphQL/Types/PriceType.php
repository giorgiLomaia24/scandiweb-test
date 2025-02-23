<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Class PriceType
 *
 * Represents the GraphQL type definition for a product's price.
 */
class PriceType extends ObjectType
{
    private static ?self $instance = null;

    /**
     * Provides a singleton instance of the PriceType.
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
     * PriceType constructor.
     *
     * Defines the GraphQL fields for a price object.
     */
    public function __construct()
    {
        parent::__construct([
            'name' => 'Price',
            'fields' => [
                'amount' => Type::nonNull(Type::float()),
                'currency_label' => Type::nonNull(Type::string()),
                'currency_symbol' => Type::nonNull(Type::string()),
            ],
        ]);
    }
}
