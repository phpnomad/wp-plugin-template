<?php

namespace PHPNomad\Integrations\WordPress\Traits;

use PHPNomad\Utils\Helpers\Arr;

trait CanGetDataFormats
{
    /**
     * Determines the wpdb->prepare field type based on the field's column type.
     *
     * @param string|int|float $value The field to check against.
     * @return string The field type. Either %d, %f, of %s.
     *
     */
    protected function getFieldSprintfType($value): string
    {
        if (is_int($value)) {
            return '%d';
        } elseif (is_float($value)) {
            return '%f';
        } else {
            return '%s';
        }
    }

    /**
     * Creates formats from the provided set of data.
     *
     * @param array<string|int|float> $data
     * @return string[]
     */
    protected function getFormats(array $data): array
    {
        return Arr::map($data, fn($datum) => $this->getFieldSprintfType($datum));
    }
}
