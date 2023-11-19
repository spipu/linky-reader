<?php

declare(strict_types=1);

namespace App\WidgetSource\Data;

use App\WidgetSource\AbstractSource;
use Spipu\DashboardBundle\Entity\Source as Source;

class DataConsumptionTotal extends AbstractSource
{
    public function getDefinition(): Source\SourceSql
    {
        $definition = new Source\SourceSql("data-consumption-total", 'energy_data');
        $definition->setType(self::TYPE_INT);
        $definition->setValueExpression("MAX(main.consumption_total)");
        $definition->setSuffix(' Wh');

        return $definition;
    }
}
