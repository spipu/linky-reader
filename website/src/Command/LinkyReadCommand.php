<?php
/**
 * This file is part of a Spipu Bundle
 *
 * (c) Laurent Minguet
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace App\Command;

use App\Service\LinkyReader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LinkyReadCommand extends Command
{
    /**
     * @var LinkyReader
     */
    private $linkyReader;

    /**
     * ConfigurationCommand constructor.
     * @param LinkyReader $linkyReader
     * @param null|string $name
     */
    public function __construct(
        LinkyReader $linkyReader,
        ?string $name = null
    ) {
        parent::__construct($name);
        $this->linkyReader = $linkyReader;
    }

    /**
     * Configure the command
     *
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName('app:linky:read')
            ->setDescription('Read data from Linky.')
            ->setHelp('This command will read data from Linky')
        ;
    }

    /**
     * Execute the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     * @SuppressWarnings(PMD.UnusedFormalParameter)
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $data = $this->linkyReader->read();

        print_r($data);

        return 0;
    }
}
