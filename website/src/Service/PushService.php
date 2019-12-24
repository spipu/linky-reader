<?php
declare(strict_types = 1);

namespace App\Service;

use App\Entity\LinkyData;
use App\Service\Push\PushInterface;

class PushService
{
    /**
     * @var PushInterface[]
     */
    private $services;

    /**
     * PushService constructor.
     * @param iterable $services
     */
    public function __construct(iterable $services)
    {
        foreach ($services as $service) {
            $this->addService($service);
        }
    }

    /**
     * @param PushInterface $service
     * @return void
     */
    private function addService(PushInterface $service): void
    {
        $this->services[] = $service;
    }

    /**
     * @param LinkyData $linkyData
     * @return void
     */
    public function push(LinkyData $linkyData): void
    {
        foreach ($this->services as $service) {
            $service->push($linkyData);
        }
    }
}
