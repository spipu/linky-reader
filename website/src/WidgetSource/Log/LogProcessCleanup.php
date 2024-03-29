<?php

declare(strict_types=1);

namespace App\WidgetSource\Log;

class LogProcessCleanup extends AbstractLog
{
    protected string $widgetCode = 'log-process-cleanup';
    protected string $logFile = 'cron-process-cleanup.log';
}
