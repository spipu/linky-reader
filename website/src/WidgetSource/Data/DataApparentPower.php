<?php

declare(strict_types=1);

namespace App\WidgetSource\Data;

use App\WidgetSource\AbstractSource;
use Spipu\DashboardBundle\Entity\Source as Source;

class DataApparentPower extends AbstractSource
{
    public function getDefinition(): Source\SourceSql
    {
        $definition = new Source\SourceSql("data-apparent-power", 'energy_data');
        $definition->setType(self::TYPE_INT);
        $definition->setValueExpression("AVG(main.apparent_power)");
        $definition->setSuffix(' VA');
        $definition->setLowerBetter(true);

        return $definition;
    }
}
