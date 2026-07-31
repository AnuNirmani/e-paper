<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'key',
        'body',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function isSubscriptionNotification(): bool
    {
        return !empty($this->key) && str_starts_with($this->key, 'subscription_notify');
    }

    public function placeholderHints(): array
    {
        if (!empty($this->key) && str_starts_with($this->key, 'pdf_caption')) {
            return [
                '{name}' => "Customer's first name.",
                '{date}' => 'Publication date (e.g., Thu, 23 Jul 2026).',
            ];
        }

        if (!$this->isSubscriptionNotification()) {
            return [];
        }

        return [
            '{name}' => "Inserts the customer's full professional name.",
            '{ending_date}' => 'The formatted expiry date of the subscription.',
            '{days_remaining}' => 'Humanized time until expiry (e.g., "3 days").',
            '{newspapers_taken}' => 'Lists the newspapers the relevant customer has taken.',
        ];
    }

    public function renderBody(array $context = []): string
    {
        $replacements = [
            '{name}' => (string) ($context['name'] ?? ''),
            '{ending_date}' => (string) ($context['ending_date'] ?? ''),
            '{days_remaining}' => (string) ($context['days_remaining'] ?? ''),
            '{newspapers_taken}' => $this->resolveNewspapersTaken($context),
        ];

        return strtr((string) $this->body, $replacements);
    }

    private function resolveNewspapersTaken(array $context): string
    {
        $candidates = [
            'newspapers_taken',
            'publications_taken',
            'publication_names',
            'newspaper_names',
            'publications',
            'newspapers',
            'papers',
            'customer.newspapers',
            'customer.publications',
            'customer.papers',
        ];

        foreach ($candidates as $key) {
            $value = data_get($context, $key);
            $names = $this->normalizeNames($value);
            if (!empty($names)) {
                return implode(', ', $names);
            }
        }

        return '';
    }

    private function normalizeNames(mixed $value): array
    {
        if ($value instanceof \Illuminate\Support\Collection) {
            $value = $value->all();
        }

        if (is_string($value)) {
            $parts = array_map('trim', explode(',', $value));
            return array_values(array_filter($parts));
        }

        if (!is_array($value)) {
            return [];
        }

        $names = [];
        foreach ($value as $item) {
            if (is_string($item)) {
                $names[] = trim($item);
                continue;
            }

            $name = data_get($item, 'name')
                ?? data_get($item, 'title')
                ?? data_get($item, 'publication_name')
                ?? data_get($item, 'newspaper_name');

            if (is_string($name) && trim($name) !== '') {
                $names[] = trim($name);
            }
        }

        return array_values(array_filter($names));
    }
}
