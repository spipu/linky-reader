<?php

declare(strict_types=1);

namespace App\Step;

use Doctrine\ORM\EntityManagerInterface;
use Spipu\ProcessBundle\Entity\Process\ParametersInterface;
use Spipu\ProcessBundle\Service\LoggerInterface;
use Spipu\ProcessBundle\Step\StepInterface;

class EnergyCleanHistory implements StepInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function execute(ParametersInterface $parameters, LoggerInterface $logger): bool
    {
        $nbDays = (int) $parameters->get('nb_days');
        if ($nbDays < 1) {
            $nbDays = 1;
        }
        $logger->debug(sprintf('Keep %d day(s)', $nbDays));

        $limitTime = ((int) (time() / (24 * 3600)) - $nbDays) * 24 * 3600;
        $limitDate = date('Y-m-d H:i:s', $limitTime);

        $logger->debug(sprintf('Limit time: %d', $limitTime));
        $logger->debug(sprintf('Limit date: %s', $limitDate));

        $query = sprintf("DELETE FROM `energy_data` WHERE `time` < %d", $limitTime);
        $logger->debug($query);

        $this->entityManager->getConnection()->executeQuery($query);

        return true;
    }
}
