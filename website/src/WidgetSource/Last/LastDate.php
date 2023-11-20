<?php

declare(strict_types=1);

namespace App\WidgetSource\Last;

use Spipu\DashboardBundle\Entity\Source as Source;
use Spipu\DashboardBundle\Service\Ui\Widget\WidgetRequest;

class LastDate extends AbstractLast
{
    protected string $widgetCode = 'last-date';

    public function getDefinition(): Source\SourceFromDefinition
    {
        $definition = parent::getDefinition();
        $definition->setSpecificDisplay('history', 'dashboard/widget/text.html.twig');

        return $definition;
    }

    public function getSpecificValues(WidgetRequest $request): array
    {
        $lastData = $this->getLastData();
        return ['value' => $lastData ? date('Y-m-d H:i', $lastData->getTime()) : '-'];
    }
}
