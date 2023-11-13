<?php

declare(strict_types=1);

namespace App\Service\LinkyReader\Push;

use App\Entity\LinkyData;
use App\Service\LinkyReader\Output;

class ScreenPush implements PushInterface
{
    public function getCode(): string
    {
        return 'screen';
    }

    public function push(LinkyData $linkyData, Output $output): void
    {
        $output->write(print_r($linkyData, true));
    }
}
