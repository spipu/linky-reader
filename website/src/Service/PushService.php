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
     * @var Output
     */
    private $output;

    /**
     * PushService constructor.
     * @param iterable $services
     * @param Output $output
     */
    public function __construct(iterable $services, Output $output)
    {
        foreach ($services as $service) {
            $this->addService($service);
        }

        $this->output = $output;
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
        $this->output->write('Push - BEGIN');

        foreach ($this->services as $service) {
            $this->output->write('Push to [' . $service->getCode() . ']');
            $service->push($linkyData, $this->output);
        }

        $this->output->write('Push - END');
    }
}
