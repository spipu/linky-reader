<?php

declare(strict_types=1);

namespace App\WidgetSource\Data;

use App\WidgetSource\AbstractSource;
use Spipu\DashboardBundle\Entity\Source as Source;

class DataInstantaneousIntensity extends AbstractSource
{
    public function getDefinition(): Source\SourceSql
    {
        $definition = new Source\SourceSql("data-instantaneous-intensity", 'energy_data');
        $definition->setType(self::TYPE_INT);
        $definition->setValueExpression("AVG(main.instantaneous_intensity)");
        $definition->setSuffix(' A');
        $definition->setLowerBetter(true);

        return $definition;
    }
}
