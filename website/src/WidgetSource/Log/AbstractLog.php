<?php

declare(strict_types=1);

namespace App\WidgetSource\Log;

use App\WidgetSource\AbstractSource;
use Spipu\DashboardBundle\Entity\Source as Source;
use Spipu\DashboardBundle\Service\Ui\WidgetRequest;
use Spipu\DashboardBundle\Source\SourceDataDefinitionInterface;

abstract class AbstractLog extends AbstractSource implements SourceDataDefinitionInterface
{
    protected string $widgetCode;
    protected string $logFile;

    private string $logsDir;

    public function __construct(string $logsDir)
    {
        $this->logsDir = $logsDir;
    }

    public function getDefinition(): Source\SourceFromDefinition
    {
        $definition = new Source\SourceFromDefinition($this->widgetCode, $this);
        $definition->setSpecificDisplay('file-medical-alt', 'dashboard/widget/log.html.twig');

        return $definition;
    }


    /**
     * @param WidgetRequest $request
     * @return float
     * @SuppressWarnings(PMD.UnusedFormalParameter)
     */
    public function getValue(WidgetRequest $request): float
    {
        return 0.;
    }

    /**
     * @param WidgetRequest $request
     * @return float
     * @SuppressWarnings(PMD.UnusedFormalParameter)
     */
    public function getPreviousValue(WidgetRequest $request): float
    {
        return 0.;
    }

    /**
     * @param WidgetRequest $request
     * @return array
     * @SuppressWarnings(PMD.UnusedFormalParameter)
     */
    public function getValues(WidgetRequest $request): array
    {
        return [];
    }

    /**
     * @param WidgetRequest $request
     * @return array
     * @SuppressWarnings(PMD.UnusedFormalParameter)
     */
    public function getSpecificValues(WidgetRequest $request): array
    {
        return [
            'content' => $this->getLogContent(),
        ];
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
