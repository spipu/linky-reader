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

        $limitDate = date('Y-m-d H:i:s', time() - $nbDays * 24 * 3600);

        $logger->debug(sprintf('Keep %d day(s)', $nbDays));
        $logger->debug(sprintf('Limit date: %s', $limitDate));

        return true;
    }
}
