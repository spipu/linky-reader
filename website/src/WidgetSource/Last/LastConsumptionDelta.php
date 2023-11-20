<?php

declare(strict_types=1);

namespace App\WidgetSource\Last;

use App\Entity\EnergyData;
use Spipu\DashboardBundle\Entity\Source as Source;
use Spipu\DashboardBundle\Service\Ui\Widget\WidgetRequest;

class LastConsumptionDelta extends AbstractLast
{
    protected string $widgetCode = 'last-consumption-delta';

    public function getDefinition(): Source\SourceFromDefinition
    {
        $definition = parent::getDefinition();
        $definition->setSuffix(' Wh');

        return $definition;
    }

    public function getValue(WidgetRequest $request): float
    {
        $lastData = $this->getLastData();
        return $lastData ? $lastData->getConsumptionDelta() : 0.;
    }
}
