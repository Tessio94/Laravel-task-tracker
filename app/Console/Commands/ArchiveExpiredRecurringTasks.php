<?php

namespace App\Console\Commands;

use App\Models\RecurringTask;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:archive-expired-recurring-tasks')]
#[Description('Archive recurring tasks that have passed their end date')]
class ArchiveExpiredRecurringTasks extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expired = RecurringTask::query()->whereNotNull('end_date')->where('end_date', '<', today())->delete();

        if($expired > 0) {
            $this->info('Archived ' . $expired . ' expired recurring tasks.');
        } else {
            $this->info('There are no expired recurring tasks to archive');
        }
    }
}
