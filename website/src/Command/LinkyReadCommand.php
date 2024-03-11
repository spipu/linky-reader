<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\EnergyData;
use App\Repository\EnergyDataRepository;
use App\Service\LinkyReader\LinkyReader;
use App\Service\LinkyReader\Output;
use App\Service\LinkyReader\PushService;
use DateTime;
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
    private Output $output;

    /**
     * @param LinkyReader $linkyReader
     * @param PushService $pushService
     * @param EntityManagerInterface $entityManager
     * @param EnergyDataRepository $energyDataRepository
     * @param Output $output
     * @param string|null $name
     */
    public function __construct(
        LinkyReader $linkyReader,
        PushService $pushService,
        EntityManagerInterface $entityManager,
        EnergyDataRepository $energyDataRepository,
        Output $output,
        ?string $name = null
    ) {
        parent::__construct($name);

        $this->linkyReader = $linkyReader;
        $this->pushService = $pushService;
        $this->entityManager = $entityManager;
        $this->energyDataRepository = $energyDataRepository;
        $this->output = $output;
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
        $this->readLinky();
        $this->pushData();

        return self::SUCCESS;
    }

    private function readLinky(): void
    {
        $energyData = $this->linkyReader->read();

        if ($energyData !== null) {
            $this->saveData($energyData);
            $this->output->write(print_r($energyData->getDataToDisplay(), true));
        }
    }

    private function saveData(EnergyData $nextData): void
    {
        $previousData = $this->energyDataRepository->findOneBy([], ['time' => 'DESC']);
        if ($previousData === null) {
            $this->output->write('No previous data found');
            $this->entityManager->persist($nextData);
            $this->entityManager->flush();
            return;
        }

        $startTime    = $previousData->getTime();
        $startOffPeak = $previousData->getConsumptionOffPeakHour();
        $startPeak    = $previousData->getConsumptionPeakHour();

        $deltaMinutes = (int) (($nextData->getTime() - $startTime) / 60);
        $deltaOffPeak = (float) ($nextData->getConsumptionOffPeakHour() - $startOffPeak);
        $deltaPeak    = (float) ($nextData->getConsumptionPeakHour() - $startPeak);

        $this->output->write('Previous data found');
        $this->output->write(' - start time:     ' . $startTime);
        $this->output->write(' - start off-peak: ' . $startOffPeak);
        $this->output->write(' - start peak:     ' . $startPeak);
        $this->output->write(' - delta time:     ' . $deltaMinutes);
        $this->output->write(' - delta off-peak: ' . $deltaOffPeak);
        $this->output->write(' - delta peak:     ' . $deltaPeak);


        for ($minute = 1; $minute < $deltaMinutes; $minute++) {
            $this->output->write('Create missing data - ' . $minute);

            $missingTime = $startTime + 60 * $minute;
            $missingDate = (new DateTime())->setTimestamp($missingTime);

            $missingData = $this->linkyReader->initNewData();
            $missingData
                ->setTime($missingTime)
                ->setCreatedAt($missingDate)
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
        if (!$this->pushService->getConfEnabled()) {
            $this->output->write('Push is disabled in configuration');
            return;
        }
        $rows = $this->energyDataRepository->findBy(
            ['pushStatus' => [EnergyData::PUSH_STATUS_WAITING, EnergyData::PUSH_STATUS_ERROR]],
            ['id' => 'ASC'],
            100
        );

        $this->output->write(sprintf('Found %d rows to push', count($rows)));

        foreach ($rows as $key => $row) {
            $this->pushService->push($row, $key);
        }

        $this->output->write('END OF PUSH');
    }
}
