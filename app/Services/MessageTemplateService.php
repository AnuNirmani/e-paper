<?php

namespace App\Services;

use App\Models\Message;

class MessageTemplateService
{
    public function buildSubscriptionMessage(string $key, string $fallback, array $data = []): string
    {
        $template = Message::query()
            ->where('key', $key)
            ->where('is_active', true)
            ->first();

        $body = $template?->body ?: $fallback;

        return $this->render($body, $data);
    }

    public function render(string $body, array $data = []): string
    {
        $replacements = [];

        foreach ($data as $k => $v) {
            $replacements['{' . $k . '}'] = $v;
            $replacements['{{' . $k . '}}'] = $v;
        }

        return strtr($body, $replacements);
    }
}
