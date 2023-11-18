<?php

declare(strict_types=1);

namespace App\WidgetSource\Log;

class LogLinkyReader extends AbstractLog
{
    protected string $widgetCode = 'log-linky-reader';
    protected string $logFile = 'cron-linky-reader.log';
}
