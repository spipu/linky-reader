<?php
declare(strict_types = 1);

namespace App\Service\Push;

use App\Entity\LinkyData;
use App\Service\Output;

interface PushInterface
{
    /**
     * @param LinkyData $linkyData
     * @param Output $output
     * @return void
     */
    public function push(LinkyData $linkyData, Output $output): void;
}