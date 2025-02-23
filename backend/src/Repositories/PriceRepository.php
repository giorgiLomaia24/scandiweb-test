<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use PDO;
use Exception;

/**
 * Class PriceRepository
 *
 * Handles price-related database queries.
 */
class PriceRepository
{
    /**
     * Retrieves the price details for a given product ID.
     *
     * @param string $productId The product ID.
     * @return array The price details or an empty array if not found.
     */
    public static function getPriceByProductId(string $productId): array
    {
        try {
            $db = Database::connect();
            $stmt = $db->prepare("SELECT amount, currency_label, currency_symbol FROM prices WHERE product_id = ?");
            $stmt->execute([$productId]);
            $price = $stmt->fetch(PDO::FETCH_ASSOC);

            return $price ?: [];
        } catch (Exception $e) {
            error_log("Error in getPriceByProductId(): " . $e->getMessage());
            return [];
        }
    }
}
