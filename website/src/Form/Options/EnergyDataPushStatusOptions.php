<?php

declare(strict_types=1);

namespace App\Form\Options;

use App\Entity\EnergyData;
use Spipu\UiBundle\Form\Options\AbstractOptions;

class EnergyDataPushStatusOptions extends AbstractOptions
{
    protected function buildOptions(): array
    {
        return [
            EnergyData::PUSH_STATUS_WAITING => 'app.entity.energy_data.status.waiting',
            EnergyData::PUSH_STATUS_PUSHED  => 'app.entity.energy_data.status.pushed',
            EnergyData::PUSH_STATUS_ERROR   => 'app.entity.energy_data.status.error',
        ];
    }
}
