<?php

declare(strict_types=1);

namespace App\Service\LinkyReader\Push;

use App\Entity\EnergyData;
use App\Service\LinkyReader\Output;

interface PushInterface
{
    public function getCode(): string;

    public function push(EnergyData $energyData, Output $output): void;
}
