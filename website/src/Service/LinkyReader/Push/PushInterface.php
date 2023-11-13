<?php

declare(strict_types=1);

namespace App\Service\LinkyReader\Push;

use App\Entity\LinkyData;
use App\Service\LinkyReader\Output;

interface PushInterface
{
    public function getCode(): string;

    public function push(LinkyData $linkyData, Output $output): void;
}
