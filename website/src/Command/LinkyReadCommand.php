<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\LinkyReader;
use App\Service\PushService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LinkyReadCommand extends Command
{
    private LinkyReader $linkyReader;
    private PushService $pushService;

    public function __construct(
        LinkyReader $linkyReader,
        PushService $pushService,
        ?string $name = null
    ) {
        parent::__construct($name);

        $this->linkyReader = $linkyReader;
        $this->pushService = $pushService;
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
            $this->pushService->push($data);
        }

        return self::SUCCESS;
    }
}
