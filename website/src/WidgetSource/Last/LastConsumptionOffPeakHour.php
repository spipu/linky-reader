<?php

declare(strict_types=1);

namespace App\WidgetSource\Last;

use Spipu\DashboardBundle\Entity\Source as Source;
use Spipu\DashboardBundle\Service\Ui\Widget\WidgetRequest;

class LastConsumptionOffPeakHour extends AbstractLast
{
    protected string $widgetCode = 'last-consumption-off-peak-hour';

    public function getDefinition(): Source\SourceFromDefinition
    {
        $definition = parent::getDefinition();
        $definition->setSuffix(' Wh');

        return $definition;
    }

    public function getValue(WidgetRequest $request): float
    {
        $lastData = $this->getLastData();
        return $lastData ? (float) $lastData->getConsumptionOffPeakHour() : 0.;
    }
}
