<?php

declare(strict_types=1);

namespace App\Models\Attribute;

use Exception;

/**
 * Class AbstractAttribute
 *
 * Represents a generic product attribute.
 */
abstract class AbstractAttribute
{
    /** @var string The name of the attribute */
    protected string $name;

    /** @var string The type of the attribute (e.g., 'text' or 'swatch') */
    protected string $type;

    /** @var array The values associated with the attribute */
    protected array $values = [];

    /**
     * AbstractAttribute constructor.
     *
     * @param string $name The attribute name.
     * @param string $type The attribute type (text or swatch).
     * @param array $values The values associated with the attribute.
     * @throws Exception If the attribute type or values are invalid.
     */
    public function __construct(string $name, string $type, array $values)
    {
        if (!in_array($type, ['text', 'swatch'], true)) {
            throw new Exception("Invalid attribute type: $type");
        }

        $this->name = $name;
        $this->type = $type;
        $this->values = $this->validateValues($values);
    }

    /**
     * Validates the attribute values.
     *
     * @param array $values The attribute values.
     * @return array The validated values.
     * @throws Exception If any value is not a string.
     */
    private function validateValues(array $values): array
    {
        foreach ($values as $value) {
            if (!is_string($value)) {
                throw new Exception("Invalid attribute value: Must be a string");
            }
        }
        return $values;
    }

    /**
     * Get the attribute name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the attribute type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Get the attribute values.
     *
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * Render the attribute.
     *
     * @return string
     */
    abstract public function render(): string;
}
