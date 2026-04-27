<?php

Schedule::command('auth:clear-resets')->daily();
Schedule::command('app:generate-recurring-tasks')
    ->dailyAt('00:30')
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/recurring-tasks.log'));
Schedule::command('app:archive-expired-recurring-tasks')->daily();
