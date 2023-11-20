<?php

declare(strict_types=1);

namespace App\WidgetSource\Last;

use Spipu\DashboardBundle\Entity\Source as Source;
use Spipu\DashboardBundle\Service\Ui\Widget\WidgetRequest;

class LastApparentPower extends AbstractLast
{
    protected string $widgetCode = 'last-apparent-power';

    public function getDefinition(): Source\SourceFromDefinition
    {
        $definition = parent::getDefinition();
        $definition->setSuffix(' VA');

        return $definition;
    }

    public function getValue(WidgetRequest $request): float
    {
        $lastData = $this->getLastData();
        return $lastData ? (float) $lastData->getApparentPower() : 0.;
    }
}
