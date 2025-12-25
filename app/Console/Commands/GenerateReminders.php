<?php

namespace App\Console\Commands;

use App\Services\ReminderService;
use Illuminate\Console\Command;

class GenerateReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate H-1 and H-3 reminders for upcoming treatment sessions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating reminders...');
        
        $service = new ReminderService();
        $count = $service->generateReminders();
        
        $this->info("Successfully generated {$count} reminders!");
        
        return Command::SUCCESS;
    }
}
