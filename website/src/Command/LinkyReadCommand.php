<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\EnergyData;
use App\Repository\EnergyDataRepository;
use App\Service\LinkyReader\LinkyReader;
use App\Service\LinkyReader\PushService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LinkyReadCommand extends Command
{
    private LinkyReader $linkyReader;
    private PushService $pushService;
    private EntityManagerInterface $entityManager;
    private EnergyDataRepository $energyDataRepository;

    /**
     * @param LinkyReader $linkyReader
     * @param PushService $pushService
     * @param EntityManagerInterface $entityManager
     * @param EnergyDataRepository $energyDataRepository
     * @param string|null $name
     */
    public function __construct(
        LinkyReader $linkyReader,
        PushService $pushService,
        EntityManagerInterface $entityManager,
        EnergyDataRepository $energyDataRepository,
        ?string $name = null
    ) {
        parent::__construct($name);

        $this->linkyReader = $linkyReader;
        $this->pushService = $pushService;
        $this->entityManager = $entityManager;
        $this->energyDataRepository = $energyDataRepository;
    }

    protected function configure(): void
    {
        $this
            ->setName('app:linky:read')
            ->setDescription('Read data from Linky.')
            ->setHelp('This command will read data from Linky')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @SuppressWarnings(PMD.UnusedFormalParameter)
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->readLinky($output);
        $this->pushData();

        return self::SUCCESS;
    }

    private function readLinky(OutputInterface $output): void
    {
        $energyData = $this->linkyReader->read();

        if ($energyData !== null) {
            $previousEnergyData = $this->energyDataRepository->findOneBy([], ['time' => 'DESC']);
            if ($previousEnergyData) {
                $delta = $energyData->getConsumptionTotal() - $previousEnergyData->getConsumptionTotal();
                if ($delta > 0) {
                    $energyData->setConsumptionDelta($delta);
                }
            }

            $this->entityManager->persist($energyData);
            $this->entityManager->flush();
            $output->writeln(print_r($energyData->getDataToDisplay(), true));
        }
    }

    private function pushData(): void
    {
        $rows = $this->energyDataRepository->findBy(
            ['pushStatus' => [EnergyData::PUSH_STATUS_WAITING, EnergyData::PUSH_STATUS_ERROR]],
            ['id' => 'ASC'],
            100
        );

        foreach ($rows as $row) {
            $this->pushService->push($row);
        }
    }
}
