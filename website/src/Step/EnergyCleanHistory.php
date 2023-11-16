<?php

/**
 * This file is part of a Spipu Bundle
 *
 * (c) Laurent Minguet
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

        $limitTime = time() - $nbDays * 24 * 3600;
        $limitDate = date('Y-m-d 00:00:00', $limitTime);

        $logger->debug(sprintf('Limit time: %d', $limitTime));
        $logger->debug(sprintf('Limit date: %s', $limitDate));

        $query = sprintf("DELETE FROM `energy_data` WHERE `time` < %d", $limitTime);
        $logger->debug($query);

        $this->entityManager->getConnection()->executeQuery($query);

        return true;
    }
}
