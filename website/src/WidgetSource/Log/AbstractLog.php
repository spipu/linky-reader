<?php

declare(strict_types=1);

namespace App\WidgetSource\Log;

use App\WidgetSource\AbstractSource;
use Spipu\DashboardBundle\Entity\Source as Source;

abstract class AbstractLog extends AbstractSource
{
    protected string $widgetCode;
    protected string $logFile;

    private string $logsDir;

    public function __construct(string $logsDir)
    {
        $this->logsDir = $logsDir;
    }

    public function getDefinition(): Source\SourceSql
    {
        return (new Source\SourceSql($this->widgetCode, ''))
            ->setDateField(null)
            ->setSpecificDisplay('file-medical-alt', 'dashboard/widget/log.html.twig')
            ->setSuffix($this->getLogContent())
        ;
    }

    private function getLogContent(): string
    {
        $filename = $this->logsDir . DIRECTORY_SEPARATOR . $this->logFile;
        $content = sprintf('Log File [%s] is missing', $this->logFile);
        if (is_file($filename)) {
            $content = file_get_contents($filename);
        }

        return $content;
    }
}
