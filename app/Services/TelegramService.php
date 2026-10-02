<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TelegramService
{
    public function sendMessage(?string $chatId, string $text): void
    {
        $token = env('TELEGRAM_BOT_TOKEN');

        if (!$token || !$chatId) {
            return;
        }

        try {
            Http::timeout(10)->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => true,
            ]);
        } catch (\Throwable $e) {
            // silent fail
        }
    }

    /**
     * Send PDF report to Telegram
     */
    public function sendDocument(?string $chatId, string $fileAbsolutePath, string $caption = ''): void
    {
        $token = env('TELEGRAM_BOT_TOKEN');

        if (!$token || !$chatId) {
            return;
        }

        if (!is_file($fileAbsolutePath)) {
            return;
        }

        try {
            Http::timeout(20)
                ->attach('document', file_get_contents($fileAbsolutePath), basename($fileAbsolutePath))
                ->post("https://api.telegram.org/bot{$token}/sendDocument", [
                    'chat_id' => $chatId,
                    'caption' => $caption,
                    'parse_mode' => 'HTML',
                ]);
        } catch (\Throwable $e) {
            // silent fail
        }
    }
}
