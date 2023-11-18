<?php

declare(strict_types=1);

namespace App\WidgetSource\Log;

class LinkyReaderLog extends AbstractLog
{
    protected string $widgetCode = 'linky-reader-log';
    protected string $logFile = 'cron-linky-reader.log';
}
