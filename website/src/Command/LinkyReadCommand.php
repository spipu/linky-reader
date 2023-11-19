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
            $this->saveData($energyData, $output);
            $output->writeln(print_r($energyData->getDataToDisplay(), true));
        }
    }

    private function saveData(EnergyData $nextData, OutputInterface $output): void
    {
        $previousData = $this->energyDataRepository->findOneBy([], ['time' => 'DESC']);
        if ($previousData === null) {
            $output->writeln('No previous data found');
            $this->entityManager->persist($nextData);
            $this->entityManager->flush();
        }

        $startTime    = $previousData->getTime();
        $startOffPeak = $previousData->getConsumptionOffPeakHour();
        $startPeak    = $previousData->getConsumptionPeakHour();

        $deltaMinutes = (int) (($nextData->getTime() - $startTime) / 60);
        $deltaOffPeak = (float) ($nextData->getConsumptionOffPeakHour() - $startOffPeak);
        $deltaPeak    = (float) ($nextData->getConsumptionPeakHour() - $startPeak);

        $output->writeln('Previous data found');
        $output->writeln(' - start time:     ' . $startTime);
        $output->writeln(' - start off-peak: ' . $startOffPeak);
        $output->writeln(' - start peak:     ' . $startPeak);
        $output->writeln(' - delta time:     ' . $deltaMinutes);
        $output->writeln(' - delta off-peak: ' . $deltaOffPeak);
        $output->writeln(' - delta peak:     ' . $deltaPeak);


        for ($minute = 1; $minute < $deltaMinutes; $minute++) {
            $output->writeln('Create missing data - ' . $minute);

            $missingData = $this->linkyReader->initNewData();
            $missingData
                ->setTime($startTime + 60 * $minute)
                ->setPricingOption($nextData->getPricingOption())
                ->setSubscribedIntensity($nextData->getSubscribedIntensity())
                ->setTimeGroup($nextData->getTimeGroup())
                ->setStateWord($nextData->getStateWord())
                ->setOffPeakHour($nextData->isOffPeakHour())
                ->setApparentPower($nextData->getApparentPower())
                ->setInstantaneousIntensity($nextData->getInstantaneousIntensity())
                ->setInstantaneousIntensity1($nextData->getInstantaneousIntensity1())
                ->setInstantaneousIntensity2($nextData->getInstantaneousIntensity2())
                ->setInstantaneousIntensity3($nextData->getInstantaneousIntensity3())
                ->setMaxIntensity($nextData->getMaxIntensity())
                ->setMaxIntensity1($nextData->getMaxIntensity1())
                ->setMaxIntensity2($nextData->getMaxIntensity2())
                ->setMaxIntensity3($nextData->getMaxIntensity3())
                ->setConsumptionOffPeakHour(
                    $startOffPeak + (int) ((float) $minute * $deltaOffPeak / (float) $deltaMinutes)
                )
                ->setConsumptionPeakHour(
                    $startPeak + (int) ((float) $minute * $deltaPeak / (float) $deltaMinutes)
                )
                ->setConsumptionTotal(
                    $missingData->getConsumptionOffPeakHour() + $missingData->getConsumptionPeakHour()
                )
                ->setConsumptionDelta(
                    $missingData->getConsumptionTotal() - $previousData->getConsumptionTotal()
                )
            ;

            $this->entityManager->persist($missingData);
            $previousData = $missingData;
        }

        $nextData->setConsumptionDelta(
            $nextData->getConsumptionTotal() - $previousData->getConsumptionTotal()
        );

        $this->entityManager->persist($nextData);
        $this->entityManager->flush();
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
