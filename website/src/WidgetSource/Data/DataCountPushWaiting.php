<?php

declare(strict_types=1);

namespace App\WidgetSource\Data;

use App\Entity\EnergyData;
use App\WidgetSource\AbstractSource;
use Spipu\DashboardBundle\Entity\Source as Source;

class DataCountPushWaiting extends AbstractSource
{
    public function getDefinition(): Source\SourceSql
    {
        $definition = new Source\SourceSql("data-count-push-waiting", 'energy_data');
        $definition->setType(self::TYPE_INT);
        $definition->addCondition(
            sprintf(
                "main.push_status = '%s'",
                EnergyData::PUSH_STATUS_WAITING
            )
        );

        return $definition;
    }
}
