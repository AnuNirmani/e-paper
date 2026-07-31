<?php

namespace Database\Seeders;

use App\Models\Message;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name'      => 'Subscription Ending - 7 Days',
                'key'       => 'subscription_notify_7days',
                'body'      => "Hello {name},\n\nREMINDER: Your subscription will expire in {days_remaining}!\n\nEnding Date: {ending_date}\n\nPlease renew your subscription soon to avoid service interruption.\n\nThank you for being with us!",
                'is_active' => true,
            ],
            [
                'name'      => 'Subscription Ending - 3 Days',
                'key'       => 'subscription_notify_3days',
                'body'      => "Hello {name},\n\nURGENT REMINDER: Your subscription will expire in {days_remaining}!\n\nEnding Date: {ending_date}\n\nPlease renew your subscription immediately to continue enjoying our services without interruption.\n\nThank you for being with us!",
                'is_active' => true,
            ],
            [
                'name'      => 'Subscription Ending - Today',
                'key'       => 'subscription_notify_today',
                'body'      => "Hello {name},\n\nFINAL NOTICE: Your subscription expires TODAY!\n\nEnding Date: {ending_date}\nThis is your last day of service.\n\nPlease renew your subscription NOW to avoid service discontinuation.\n\nThank you for being with us!",
                'is_active' => true,
            ],
            [
                'name'      => 'Subscription Expired',
                'key'       => 'subscription_notify_expired',
                'body'      => "Hello {name},\n\nYour subscription has expired on {ending_date}.\n\nPlease renew your subscription to continue enjoying our services.\n\nThank you!",
                'is_active' => true,
            ],
            [
                'name'      => 'PDF Caption - DAILY FT (Publication 27)',
                'key'       => 'pdf_caption_27',
                'body'      => "Good morning, {name}! \nStep into the world of finance with  DAILY FT - {date} edition!\nImmerse yourself in the latest financial news and captivating stories within our PDF paper, ensuring you kickstart your day on a knowledgeable note. \nWishing you a day filled with success and prosperity!",
                'is_active' => true,
            ],
            [
                'name'      => 'PDF Caption - DAILY MIRROR (Publication 2)',
                'key'       => 'pdf_caption_2',
                'body'      => "Good morning, {name}! \nWelcome to a brand new day with the DAILY MIRROR - {date} edition!\nStart your morning right with the latest in news, business, sports and feature stories. \nWishing you a wonderful day ahead!",
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            Message::updateOrCreate(
                ['key' => $template['key']],
                $template
            );
        }
    }
}
