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
                'name'      => 'Subscription Ending – 7 Days',
                'key'       => 'subscription_notify_7days',
                'body'      => "Hello {name},\n\n⚠️ REMINDER: Your subscription will expire in {days_remaining}!\n\n📅 Ending Date: {ending_date}\n\nPlease renew your subscription soon to avoid service interruption.\n\nThank you for being with us!",
                'is_active' => true,
            ],
            [
                'name'      => 'Subscription Ending – 3 Days',
                'key'       => 'subscription_notify_3days',
                'body'      => "Hello {name},\n\n🔔 URGENT REMINDER: Your subscription will expire in {days_remaining}!\n\n📅 Ending Date: {ending_date}\n\nPlease renew your subscription immediately to continue enjoying our services without interruption.\n\nThank you for being with us!",
                'is_active' => true,
            ],
            [
                'name'      => 'Subscription Ending – Today',
                'key'       => 'subscription_notify_today',
                'body'      => "Hello {name},\n\n🚨 FINAL NOTICE: Your subscription expires TODAY!\n\n📅 Ending Date: {ending_date}\n⏰ This is your last day of service.\n\nPlease renew your subscription NOW to avoid service discontinuation.\n\nThank you for being with us!",
                'is_active' => true,
            ],
            [
                'name'      => 'Subscription Expired',
                'key'       => 'subscription_notify_expired',
                'body'      => "Hello {name},\n\nYour subscription has expired on {ending_date}.\n\nPlease renew your subscription to continue enjoying our services.\n\nThank you!",
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
