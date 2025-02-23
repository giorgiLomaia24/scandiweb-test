<?php

declare(strict_types=1);

namespace App\GraphQL\Resolvers;

use App\Repositories\OrderRepository;

/**
 * Class OrderResolver
 *
 * Handles GraphQL mutations related to orders.
 */
class OrderResolver
{
    /**
     * Creates an order.
     *
     * @param mixed $root The root query context (usually not used).
     * @param array $args The GraphQL arguments containing order details.
     * @return array The order response with ID and message.
     */
    public static function createOrder(mixed $root, array $args): array
    {
        $orderId = OrderRepository::createOrder($args['items']);

        return [
            'order_id' => $orderId,
            'message' => "Order successfully created with ID: $orderId",
        ];
    }
}
