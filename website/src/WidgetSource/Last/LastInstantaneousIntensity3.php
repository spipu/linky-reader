<?php

declare(strict_types=1);

namespace App\WidgetSource\Last;

use Spipu\DashboardBundle\Entity\Source as Source;
use Spipu\DashboardBundle\Service\Ui\WidgetRequest;

class LastInstantaneousIntensity3 extends AbstractLast
{
    protected string $widgetCode = 'last-instantaneous-intensity3';

    public function getDefinition(): Source\SourceFromDefinition
    {
        $definition = parent::getDefinition();
        $definition->setSuffix(' A');

        return $definition;
    }

    public function getValue(WidgetRequest $request): float
    {
        $lastData = $this->getLastData();
        return $lastData ? (float) $lastData->getInstantaneousIntensity3() : 0.;
    }
}
