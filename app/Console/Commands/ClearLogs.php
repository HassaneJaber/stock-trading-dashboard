<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;

class ClearLogs extends Command
{
    protected $signature = 'logs:clear';
    protected $description = 'Clear Laravel log files';

    public function handle()
    {
        File::put(storage_path('logs/laravel.log'), '');
        $this->info('Logs have been cleared successfully.');
    }
}
