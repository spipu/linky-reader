<?php

declare(strict_types=1);

namespace App\WidgetSource\Log;

class LogLinkyCleanup extends AbstractLog
{
    protected string $widgetCode = 'log-linky-cleanup';
    protected string $logFile = 'cron-linky-cleanup.log';
}
