<?php

declare(strict_types=1);

namespace App\Models\Product;

/**
 * Class Tech
 *
 * Represents a product of type "tech".
 */
class Tech extends AbstractProduct
{
    /**
     * Returns the product type.
     *
     * @return string
     */
    public function getProductType(): string
    {
        return 'tech';
    }
}
