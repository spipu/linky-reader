<?php

declare(strict_types=1);

namespace App\WidgetSource\Last;

use App\Entity\EnergyData;
use App\Repository\EnergyDataRepository;
use App\WidgetSource\AbstractSource;
use Spipu\DashboardBundle\Entity\Source as Source;
use Spipu\DashboardBundle\Service\Ui\Widget\WidgetRequest;
use Spipu\DashboardBundle\Source\SourceDataDefinitionInterface;

abstract class AbstractLast extends AbstractSource implements SourceDataDefinitionInterface
{
    protected string $widgetCode;
    private EnergyDataRepository $energyDataRepository;

    public function __construct(EnergyDataRepository $energyDataRepository)
    {
        $this->energyDataRepository = $energyDataRepository;
    }

    public function getDefinition(): Source\SourceFromDefinition
    {
        $definition = new Source\SourceFromDefinition($this->widgetCode, $this);

        $definition->setSpecificDisplay('history', 'dashboard/widget/text.html.twig');

        return $definition;
    }

    public function getValue(WidgetRequest $request): float
    {
        return 0.;
    }

    public function getPreviousValue(WidgetRequest $request): float
    {
        return 0.;
    }

    public function getValues(WidgetRequest $request): array
    {
        return [];
    }

    public function getSpecificValues(WidgetRequest $request): array
    {
        $lastData = $this->energyDataRepository->findOneBy([], ['id' => 'DESC']);
        return ['value' => $lastData ? $this->getFromData($lastData) : '-'];
    }

    abstract protected function getFromData(EnergyData $energyData): string;
}
