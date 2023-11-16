<?php

declare(strict_types=1);

namespace App\WidgetSource;

use Spipu\DashboardBundle\Entity\Source as Source;

class LinkyReaderLog extends AbstractSource
{
    private string $logsDir;

    public function __construct(string $logsDir)
    {
        $this->logsDir = $logsDir;
    }

    public function getDefinition(): Source\SourceSql
    {
        return (new Source\SourceSql("linky-reader-log", ''))
            ->setDateField(null)
            ->setSpecificDisplay('file-medical-alt', 'dashboard/widget/linky_reader_log.html.twig')
            ->setSuffix($this->getLogContent())
        ;
    }

    private function getLogContent(): string
    {
        $filename = $this->logsDir . DIRECTORY_SEPARATOR . 'cron-linky.log';
        $content = sprintf('Log File [%s] is missing', $filename);
        if (is_file($filename)) {
            $content = file_get_contents($filename);
        }

        return $content;
    }
}
