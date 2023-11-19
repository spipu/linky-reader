<?php

declare(strict_types=1);

namespace App\WidgetSource\Data;

use App\Entity\EnergyData;
use App\WidgetSource\AbstractSource;
use Spipu\DashboardBundle\Entity\Source as Source;

class DataCountPushPushed extends AbstractSource
{
    public function getDefinition(): Source\SourceSql
    {
        $definition = new Source\SourceSql("data-count-push-pushed", 'energy_data');
        $definition->setType(self::TYPE_INT);
        $definition->addCondition(
            sprintf(
                "main.push_status = '%s'",
                EnergyData::PUSH_STATUS_PUSHED
            )
        );

        return $definition;
    }
}
