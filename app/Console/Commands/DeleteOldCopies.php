<?php

namespace App\Console\Commands;

use App\Models\Copy;
use Illuminate\Console\Command;

class DeleteOldCopies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'copies:delete-old {--days=7 : Number of days to keep copies}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete sent copies older than specified days (default: 7 days)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $date = now()->subDays($days);

        $deleted = Copy::where('sent_at', '<', $date)->delete();

        $this->info("Deleted {$deleted} old copies (older than {$days} days)");

        return Command::SUCCESS;
    }
}
