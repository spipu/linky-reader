<?php

declare(strict_types=1);

namespace App\WidgetSource\Data;

use App\WidgetSource\AbstractSource;
use Spipu\DashboardBundle\Entity\Source as Source;

class DataConsumptionDelta extends AbstractSource
{
    public function getDefinition(): Source\SourceSql
    {
        $definition = new Source\SourceSql("data-consumption-delta", 'energy_data');
        $definition->setType(self::TYPE_INT);
        $definition->setValueExpression("SUM(main.consumption_delta)");
        $definition->setSuffix(' Wh');
        $definition->setLowerBetter(true);

        return $definition;
    }
}
