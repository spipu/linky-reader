<?php

declare(strict_types=1);

namespace App\WidgetSource\Data;

use App\WidgetSource\AbstractSource;
use Spipu\DashboardBundle\Entity\Source as Source;

class DataConsumptionPeakHour extends AbstractSource
{
    public function getDefinition(): Source\SourceSql
    {
        $definition = new Source\SourceSql("data-consumption-peak-hour", 'energy_data');
        $definition->setType(self::TYPE_INT);
        $definition->setValueExpression("MAX(main.consumption_peak_hour)");
        $definition->setSuffix(' Wh');

        return $definition;
    }
}
