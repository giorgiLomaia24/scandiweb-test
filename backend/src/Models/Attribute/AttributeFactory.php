<?php

declare(strict_types=1);

namespace App\Models\Attribute;

use Exception;

/**
 * Class AttributeFactory
 *
 * Factory for creating Attribute instances.
 */
class AttributeFactory
{
    /**
     * Creates an attribute instance based on its type.
     *
     * @param string $name The attribute name.
     * @param string $type The attribute type (text or swatch).
     * @param array $values The attribute values.
     * @return AbstractAttribute
     * @throws Exception 
     */
    public static function create(string $name, string $type, array $values): AbstractAttribute
    {
        return match ($type) {
            'text' => new TextAttribute($name, $type, $values),
            'swatch' => new SwatchAttribute($name, $type, $values),
            default => throw new Exception("AttributeFactory Error: Unknown attribute type: $type"),
        };
    }
}
