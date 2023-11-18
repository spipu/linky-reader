<?php

declare(strict_types=1);

namespace App\WidgetSource\Log;

class LogProcessCheckPid extends AbstractLog
{
    protected string $widgetCode = 'log-process-check-pid';
    protected string $logFile = 'cron-process-check-pid.log';
}
