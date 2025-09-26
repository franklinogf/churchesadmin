<?php

declare(strict_types=1);

namespace App\Support;

use Carbon\Carbon;
use DateTimeInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

final class DiffLogger
{
    private array $diff = [
        'old' => [],
        'attributes' => [],
    ];

    private array $ignoredFields = ['updated_at', 'created_at'];

    /**
     * Set fields to ignore during comparison.
     */
    public function ignore(array $fields): self
    {
        $this->ignoredFields = array_merge($this->ignoredFields, $fields);

        return $this;
    }

    /**
     * Compare old and new arrays recursively, auto-normalizing dates.
     */
    public function addChanges(array $old, array $new): self
    {
        $this->diffRecursive($old, $new);

        return $this;
    }

    /**
     * Compare Eloquent models directly.
     */
    public function compareModels(Model $old, Model $new, array $fields = []): self
    {
        $fields = empty($fields) ? array_keys($old->getAttributes()) : $fields;
        $fields = array_diff($fields, $this->ignoredFields);

        $oldData = $old->only($fields);
        $newData = $new->only($fields);

        return $this->addChanges($oldData, $newData);
    }

    /**
     * Add a custom field comparison.
     */
    public function addCustom(string $key, mixed $old, mixed $new): self
    {
        if (in_array($key, $this->ignoredFields)) {
            return $this;
        }

        if (! $this->valuesAreEqual($old, $new)) {
            $this->diff['old'][$key] = $this->normalizeValue($old);
            $this->diff['attributes'][$key] = $this->normalizeValue($new);
        }

        return $this;
    }

    /**
     * Get the diff array.
     */
    public function get(): array
    {
        return $this->diff;
    }

    /**
     * Check if there are any changes.
     */
    public function hasChanges(): bool
    {
        return ! empty($this->diff['old']) || ! empty($this->diff['attributes']);
    }

    /**
     * Get only the changed fields as a simple array.
     */
    public function getChangedFields(): array
    {
        return array_keys($this->diff['attributes']);
    }

    /**
     * Get a summary of changes for logging.
     */
    public function getSummary(): string
    {
        $changes = $this->getChangedFields();

        return empty($changes) ? 'No changes' : 'Changed: '.implode(', ', $changes);
    }

    /**
     * Log the changes using Laravel Activitylog.
     */
    public function log(Model $model, string $event, ?string $summary = null): void
    {
        if ($this->hasChanges()) {
            activity()
                ->event($event)
                ->performedOn($model)
                ->withProperties($this->get())
                ->log($summary ?? $this->getSummary());
        }
    }

    private function diffRecursive(array $old, array $new): void
    {
        // Check for removed fields
        foreach ($old as $key => $oldValue) {
            if (in_array($key, $this->ignoredFields)) {
                continue;
            }

            if (! array_key_exists($key, $new)) {
                $this->diff['old'][$key] = $this->normalizeValue($oldValue);
                $this->diff['attributes'][$key] = null;
            }
        }

        // Check for added/changed fields
        foreach ($new as $key => $newValue) {
            if (in_array($key, $this->ignoredFields)) {
                continue;
            }

            $oldValue = array_key_exists($key, $old) ? $old[$key] : null;

            if (is_array($newValue) && is_array($oldValue)) {
                // Create a nested logger for recursive comparison
                $nested = new self();
                $nested->ignoredFields = $this->ignoredFields;
                $nested->diffRecursive($oldValue, $newValue);

                if ($nested->hasChanges()) {
                    // Instead of nesting the entire diff structure,
                    // flatten it with prefixed keys for better readability
                    foreach ($nested->diff['old'] as $nestedKey => $nestedOldValue) {
                        $this->diff['old']["{$key}.{$nestedKey}"] = $nestedOldValue;
                    }
                    foreach ($nested->diff['attributes'] as $nestedKey => $nestedNewValue) {
                        $this->diff['attributes']["{$key}.{$nestedKey}"] = $nestedNewValue;
                    }
                }
            } else {
                if (! $this->valuesAreEqual($oldValue, $newValue)) {
                    $this->diff['old'][$key] = $this->normalizeValue($oldValue);
                    $this->diff['attributes'][$key] = $this->normalizeValue($newValue);
                }
            }
        }
    }

    private function normalizeValue(mixed $value): mixed
    {
        // Handle null values
        if ($value === null) {
            return null;
        }

        // Handle Eloquent Collections
        if ($value instanceof Collection) {
            return $value->toArray();
        }

        // Handle Eloquent Models
        if ($value instanceof Model) {
            return $value->toArray();
        }

        // Handle Carbon objects (including CarbonImmutable)
        if ($value instanceof DateTimeInterface) {
            return Carbon::parse($value)->format('Y-m-d');
        }

        // Handle strings that might be dates
        if (is_string($value) && mb_trim($value) !== '') {
            if ($this->looksLikeDate($value)) {
                try {
                    $dt = Carbon::parse($value);

                    return $dt->format('Y-m-d'); // normalize date
                } catch (Exception) {
                    // If parsing fails, return original value
                }
            }

            return mb_trim($value);
        }

        // Handle empty strings
        if (is_string($value) && mb_trim($value) === '') {
            return null; // Normalize empty strings to null for comparison
        }

        // Arrays: normalize recursively
        if (is_array($value)) {
            $normalized = [];
            foreach ($value as $k => $v) {
                $normalized[$k] = $this->normalizeValue($v);
            }

            return $normalized;
        }

        // Handle boolean-like values
        if (is_bool($value) || in_array($value, [0, 1, '0', '1'], true)) {
            return (bool) $value;
        }

        return $value;
    }

    /**
     * Check if a string looks like a date.
     */
    private function looksLikeDate(string $value): bool
    {
        $patterns = [
            '/^\d{4}-\d{2}-\d{2}(\s\d{2}:\d{2}:\d{2})?$/',     // 2023-12-25 or 2023-12-25 12:30:45
            '/^\d{2}\/\d{2}\/\d{4}$/',                          // 12/25/2023
            '/^\d{2}-\d{2}-\d{4}$/',                            // 12-25-2023
            '/^\d{4}\/\d{2}\/\d{2}$/',                          // 2023/12/25
            '/^\d{1,2}-\d{1,2}-\d{4}$/',                        // 1-5-2023
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $value)) {
                return true;
            }
        }

        return false;
    }

    private function valuesAreEqual(mixed $a, mixed $b): bool
    {
        // Normalize both values first
        $aNorm = $this->normalizeValue($a);
        $bNorm = $this->normalizeValue($b);

        // Handle null comparisons after normalization
        if ($aNorm === null && $bNorm === null) {
            return true;
        }
        if ($aNorm === null || $bNorm === null) {
            return false;
        }

        // Handle array comparisons with sorting for order-independent comparison
        if (is_array($aNorm) && is_array($bNorm)) {
            // For indexed arrays (like tag lists), sort before comparison
            if ($this->isIndexedArray($aNorm) && $this->isIndexedArray($bNorm)) {
                sort($aNorm);
                sort($bNorm);
            }

            return $aNorm === $bNorm;
        }

        // Handle numeric comparisons with type coercion
        if (is_numeric($aNorm) && is_numeric($bNorm)) {
            return (float) $aNorm === (float) $bNorm;
        }

        return $aNorm === $bNorm;
    }

    /**
     * Check if an array is indexed (not associative).
     */
    private function isIndexedArray(array $array): bool
    {
        return array_keys($array) === range(0, count($array) - 1);
    }
}
