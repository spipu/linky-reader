<?php

declare(strict_types=1);

namespace App\WidgetSource\Data;

use App\WidgetSource\AbstractSource;
use Spipu\DashboardBundle\Entity\Source as Source;

class DataCountAll extends AbstractSource
{
    public function getDefinition(): Source\SourceSql
    {
        $definition = new Source\SourceSql("data-count-all", 'energy_data');
        $definition->setType(self::TYPE_INT);

        return $definition;
    }
}
