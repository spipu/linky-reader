<?php
declare(strict_types = 1);

namespace App\Service\Push;

use App\Entity\LinkyData;
use App\Service\Output;

class ScreenPush implements PushInterface
{
    /**
     * @return string
     */
    public function getCode(): string
    {
        return 'screen';
    }

    /**
     * @param LinkyData $linkyData
     * @param Output $output
     * @return void
     */
    public function push(LinkyData $linkyData, Output $output): void
    {
        $output->write(print_r($linkyData, true));
    }
}
