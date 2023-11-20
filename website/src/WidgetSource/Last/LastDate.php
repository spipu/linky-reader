<?php

declare(strict_types=1);

namespace App\WidgetSource\Last;

use App\Entity\EnergyData;

class LastDate extends AbstractLast
{
    protected string $widgetCode = 'last-date';

    protected function getFromData(EnergyData $energyData): string
    {
        return date('Y-m-d H:i:s', $energyData->getTime());
    }
}
