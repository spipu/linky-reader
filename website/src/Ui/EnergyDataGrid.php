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
            )
            ->addColumn(
                (new Grid\Column('time', 'app.entity.energy_data.field.time', 'time', 20))
                    ->setType((new Grid\ColumnType(Grid\ColumnType::TYPE_INTEGER)))
                    ->setFilter((new Grid\ColumnFilter(true))->useRange())
                    ->useSortable()
            )
            ->addColumn(
                (new Grid\Column('pushStatus', 'app.entity.energy_data.field.push_status', 'pushStatus', 30))
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
