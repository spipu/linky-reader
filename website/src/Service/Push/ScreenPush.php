<?php
declare(strict_types = 1);

namespace App\Service\Push;

use App\Entity\LinkyData;
use App\Service\Output;

class ScreenPush implements PushInterface
{
    /**
     * @param LinkyData $linkyData
     * @param Output $output
     * @return void
     */
    public function push(LinkyData $linkyData, Output $output): void
    {
        $output->write('=========================');

        $output->write(print_r($linkyData, true));

        $output->write('=========================');
    }
}
