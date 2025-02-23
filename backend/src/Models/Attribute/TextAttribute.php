<?php

declare(strict_types=1);

namespace App\Models\Attribute;

/**
 * Class TextAttribute
 *
 * Represents an attribute with text-based values.
 */
class TextAttribute extends AbstractAttribute
{
    /**
     * Renders the text attribute as a string.
     *
     * @return string The formatted text attribute values.
     */
    public function render(): string
    {
        return 'Text: ' . implode(', ', $this->values);
    }
}
