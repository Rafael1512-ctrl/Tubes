<?php

namespace App\Console\Commands;

use App\Services\ReminderService;
use Illuminate\Console\Command;

class SendReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send all pending reminders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Sending pending reminders...');
        
        $service = new ReminderService();
        $count = $service->sendPendingReminders();
        
        $this->info("Successfully sent {$count} reminders!");
        
        return Command::SUCCESS;
    }
}
