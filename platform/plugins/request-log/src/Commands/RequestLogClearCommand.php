<?php

namespace Botble\RequestLog\Commands;

use Botble\RequestLog\Models\RequestLog;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand('cms:request-logs:clear', 'Clear all request error logs')]
class RequestLogClearCommand extends Command
{
    public function handle(): int
    {
        $this->components->info('Processing...');

        $count = RequestLog::query()->count();

        RequestLog::query()->truncate();

        $this->components->info(sprintf(
            'Done. Deleted %s %s.',
            $count,
            Str::plural('request log', $count)
        ));

        return self::SUCCESS;
    }
}
