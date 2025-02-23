<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use App\Models\Product\AbstractProduct;
use App\Repositories\ProductRepository;
use Exception;
use PDO;

/**
 * Class OrderRepository
 *
 * Handles order-related database operations.
 */
class OrderRepository
{
    /**
     * Creates a new order.
     *
     * @param array $items List of products and attributes in the order.
     * @return int The ID of the newly created order.
     * @throws Exception If an error occurs during order creation.
     */
    public static function createOrder(array $items): int
    {
        $db = Database::connect();
        $db->beginTransaction();

        try {
            
            $totalPrice = 0;
            foreach ($items as $item) {
              

                $product = ProductRepository::getProductById($item['product_id']);

                if (!$product instanceof AbstractProduct) {
                    throw new Exception("Product not found: " . $item['product_id']);
                }

                
                $prices = $product->getPrices();
                if (empty($prices) || !isset($prices[0]['amount']) || !is_numeric($prices[0]['amount'])) {
                    throw new Exception("Price not found or invalid for product: " . $item['product_id']);
                }

                $price = (float) $prices[0]['amount'];
                $totalPrice += $price * $item['quantity'];
            }

          
            $stmt = $db->prepare("INSERT INTO orders (total_price) VALUES (:total_price)");
            $stmt->execute(['total_price' => $totalPrice]);
            $orderId = (int) $db->lastInsertId();

       
            foreach ($items as $item) {
                $stmt = $db->prepare("
                    INSERT INTO order_items (order_id, product_id, quantity) 
                    VALUES (:order_id, :product_id, :quantity)
                ");
                $stmt->execute([
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                ]);
                $orderItemId = (int) $db->lastInsertId();

                if (!empty($item['attributes'])) {
                    $attributeStmt = $db->prepare("
                        INSERT INTO order_item_attributes (order_item_id, attribute_name, attribute_value) 
                        VALUES (:order_item_id, :attribute_name, :attribute_value)
                    ");

                    foreach ($item['attributes'] as $attribute) {
                        $attributeStmt->execute([
                            'order_item_id' => $orderItemId,
                            'attribute_name' => $attribute['name'],
                            'attribute_value' => $attribute['value'],
                        ]);
                    }
                }
            }


            $db->commit();
            return $orderId;
        } catch (Exception $e) {
            $db->rollBack();
            error_log("⚠️ Order Creation Failed: " . $e->getMessage());
            throw new Exception("Error while creating order: " . $e->getMessage());
        }
    }
}
