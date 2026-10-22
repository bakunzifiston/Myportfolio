<?php

namespace App\Concerns;

/**
 * Lets admin forms edit JSON list columns as plain "one item per line" textareas.
 */
trait NormalizesListInput
{
    /** @param array<int, string> $fields */
    protected function linesToArrays(array $fields): void
    {
        $merge = [];

        foreach ($fields as $field) {
            $value = $this->input($field);

            if (is_string($value)) {
                $items = array_values(array_filter(array_map('trim', preg_split('/\R/', $value))));
                $merge[$field] = $items ?: null;
            }
        }

        $this->merge($merge);
    }

    /** @param array<int, string> $fields */
    protected function normalizeBooleans(array $fields): void
    {
        $this->merge(collect($fields)->mapWithKeys(fn ($f) => [$f => $this->boolean($f)])->all());
    }
}
