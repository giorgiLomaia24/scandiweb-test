<?php

declare(strict_types=1);

namespace App\Models\Product;

/**
 * Class Clothes
 *
 * Represents a product of type "clothes".
 */
class Clothes extends AbstractProduct
{
    /**
     * Returns the product type.
     *
     * @return string
     */
    public function getProductType(): string
    {
        return 'clothes';
    }
}
