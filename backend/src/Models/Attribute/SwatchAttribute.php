<?php

declare(strict_types=1);

namespace App\Models\Attribute;

/**
 * Class SwatchAttribute
 *
 * Represents an attribute with swatch-based color values.
 */
class SwatchAttribute extends AbstractAttribute
{
    /**
     * Renders the swatch attribute as HTML.
     *
     * @return string The rendered swatch colors.
     */
    public function render(): string
    {
        $swatches = array_map(
            fn($color) => "<span style='background-color: {$color}; padding: 5px; border-radius: 3px;'>&nbsp;&nbsp;&nbsp;&nbsp;</span>",
            $this->values
        );

        return 'Swatch Colors: ' . implode(' ', $swatches);
    }
}
