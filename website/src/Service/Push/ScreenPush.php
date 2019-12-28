<?php
declare(strict_types = 1);

namespace App\Service\Push;

use App\Entity\LinkyData;

class ScreenPush implements PushInterface
{
    /**
     * @param LinkyData $linkyData
     * @return void
     */
    public function push(LinkyData $linkyData): void
    {
        echo "=========================\n";
        echo date('Y-m-d H:i:s')."\n";
        print_r($linkyData);
        echo "=========================\n";
    }
}
