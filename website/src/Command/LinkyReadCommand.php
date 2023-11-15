<?php

declare(strict_types=1);

namespace App\Command;

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

    public function __construct(
        LinkyReader $linkyReader,
        PushService $pushService,
        EntityManagerInterface $entityManager,
        ?string $name = null
    ) {
        parent::__construct($name);

        $this->linkyReader = $linkyReader;
        $this->pushService = $pushService;
        $this->entityManager = $entityManager;
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
        $data = $this->linkyReader->read();

        if ($data !== null) {
            $this->entityManager->persist($data);
            $this->entityManager->flush();

            $this->pushService->push($data);
        }

        return self::SUCCESS;
    }
}
