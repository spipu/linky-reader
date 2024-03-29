<?php

declare(strict_types=1);

namespace App\WidgetSource\Log;

class LogProcessRerun extends AbstractLog
{
    protected string $widgetCode = 'log-process-rerun';
    protected string $logFile = 'cron-process-rerun.log';
}
