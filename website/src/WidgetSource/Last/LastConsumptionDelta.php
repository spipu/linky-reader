<?php

declare(strict_types=1);

namespace App\WidgetSource\Last;

use App\Entity\EnergyData;

class LastConsumptionDelta extends AbstractLast
{
    protected string $widgetCode = 'last-consumption-delta';

    protected function getFromData(EnergyData $energyData): string
    {
        return $energyData->getConsumptionDelta() . ' Wh';
    }
}
