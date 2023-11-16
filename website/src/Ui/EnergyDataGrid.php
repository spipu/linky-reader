<?php

declare(strict_types=1);

namespace App\Ui;

use App\Form\Options\EnergyDataPushStatusOptions;
use Spipu\UiBundle\Service\Ui\Definition\GridDefinitionInterface;
use Spipu\UiBundle\Entity\Grid;

class EnergyDataGrid implements GridDefinitionInterface
{
    private ?Grid\Grid $definition = null;
    private EnergyDataPushStatusOptions $pushStatusOptions;

    public function __construct(
        EnergyDataPushStatusOptions $pushStatusOptions
    ) {
        $this->pushStatusOptions = $pushStatusOptions;
    }

    public function getDefinition(): Grid\Grid
    {
        if (!$this->definition) {
            $this->prepareGrid();
        }

        return $this->definition;
    }

    private function prepareGrid(): void
    {
        $this->definition = (new Grid\Grid('energy_data', 'App:EnergyData'))
            ->setPersonalize(true)
            ->setPager(
                (new Grid\Pager([20, 50, 100, 200, 1440], 20))
            )
            ->addColumn(
                (new Grid\Column('id', 'app.entity.energy_data.field.id', 'id', 10))
                    ->setType((new Grid\ColumnType(Grid\ColumnType::TYPE_INTEGER)))
                    ->setFilter((new Grid\ColumnFilter(true))->useRange())
                    ->useSortable()
                    ->setOptions(['td-css-class' => 'text-center'])
            )
            ->addColumn(
                (new Grid\Column('time', 'app.entity.energy_data.field.time', 'time', 20))
                    ->setType((new Grid\ColumnType(Grid\ColumnType::TYPE_INTEGER)))
                    ->setFilter((new Grid\ColumnFilter(true))->useRange())
                    ->useSortable()
                    ->setOptions(['td-css-class' => 'text-center'])
            )
            ->addColumn(
                (new Grid\Column('consumptionTotal', 'app.entity.energy_data.field.consumption_total', 'consumptionTotal', 30))
                    ->setType((new Grid\ColumnType(Grid\ColumnType::TYPE_INTEGER)))
                    ->setFilter((new Grid\ColumnFilter(false)))
                    ->useSortable()
                    ->setOptions(['td-css-class' => 'text-center', 'suffix' => ' Wh'])
            )
            ->addColumn(
                (new Grid\Column('consumptionDelta', 'app.entity.energy_data.field.consumption_delta', 'consumptionDelta', 40))
                    ->setType((new Grid\ColumnType(Grid\ColumnType::TYPE_INTEGER)))
                    ->setFilter((new Grid\ColumnFilter(true))->useRange())
                    ->useSortable()
                    ->setOptions(['td-css-class' => 'text-center', 'suffix' => ' Wh'])
            )
            ->addColumn(
                (new Grid\Column('instantaneousIntensity', 'app.entity.energy_data.field.instantaneous_intensity', 'instantaneousIntensity', 50))
                    ->setType((new Grid\ColumnType(Grid\ColumnType::TYPE_INTEGER)))
                    ->setFilter((new Grid\ColumnFilter(true))->useRange())
                    ->useSortable()
                    ->setOptions(['td-css-class' => 'text-center', 'suffix' => ' A'])
            )
            ->addColumn(
                (new Grid\Column('apparentPower', 'app.entity.energy_data.field.apparent_power', 'apparentPower', 60))
                    ->setType((new Grid\ColumnType(Grid\ColumnType::TYPE_INTEGER)))
                    ->setFilter((new Grid\ColumnFilter(true))->useRange())
                    ->useSortable()
                    ->setOptions(['td-css-class' => 'text-center', 'suffix' => ' A'])
            )
            ->addColumn(
                (new Grid\Column('pushNbTry', 'app.entity.energy_data.field.push_nb_try', 'pushNbTry', 70))
                    ->setType((new Grid\ColumnType(Grid\ColumnType::TYPE_INTEGER)))
                    ->setFilter((new Grid\ColumnFilter(true))->useRange())
                    ->useSortable()
            )
            ->addColumn(
                (new Grid\Column('pushStatus', 'app.entity.energy_data.field.push_status', 'pushStatus', 80))
                    ->setType(
                        (new Grid\ColumnType(Grid\ColumnType::TYPE_SELECT))
                            ->setOptions($this->pushStatusOptions)
                    )
                    ->setFilter((new Grid\ColumnFilter(true)))
                    ->useSortable()
            )
            ->setDefaultSort('id', 'desc')
        ;
    }
}
