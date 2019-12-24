<?php
declare(strict_types = 1);

namespace App\Service\Push;

use App\Entity\LinkyData;

interface PushInterface
{
    /**
     * @param LinkyData $linkyData
     * @return void
     */
    public function push(LinkyData $linkyData): void;
}