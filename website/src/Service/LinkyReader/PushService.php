<?php

declare(strict_types=1);

namespace App\Service\LinkyReader;

use App\Entity\LinkyData;
use App\Service\LinkyReader\Push\PushInterface;

class PushService
{
    /**
     * @var PushInterface[]
     */
    private array $services = [];
    private Output $output;

    public function __construct(iterable $services, Output $output)
    {
        foreach ($services as $service) {
            $this->addService($service);
        }

        $this->output = $output;
    }

    private function addService(PushInterface $service): void
    {
        $this->services[] = $service;
    }

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
