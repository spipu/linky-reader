<?php
declare(strict_types = 1);

namespace App\Service;

use App\Entity\LinkyData;

class LinkyReader
{
    /**
     * @var Output
     */
    private $output;

    /**
     * @var string
     */
    private $source;

    /**
     * LinkyReader constructor.
     * @param Output $output
     * @param string $source
     */
    public function __construct(Output $output, string $source = '/dev/ttyUSB0')
    {
        $this->output = $output;
        $this->source = $source;
    }

    /**
     * @return LinkyData|null
     */
    public function read(): ?LinkyData
    {
        $this->output->write('Read Linky Data');
        $data = null;
        try {
            $this->output->write(' - connect to ' . $this->source);

            ini_set('max_execution_time', 30);
            exec("stty -F {$this->source} 1200 cs7");
            $handler = fopen($this->source, 'r');

            if (!$handler) {
                $this->output->write(' - failed');
                return null;
            }

            $this->output->write(' - read values');

            $values = $this->readNextMessage($handler);
            if ($values === null) {
                $this->output->write(' - failed');
                return null;
            }

            $this->output->write(' - convert values');
            $data = $this->createDataFromValues($values);
        } finally {
            if ($handler) {
                $this->output->write(' - close connection');
                fclose($handler);
            }
        }

        $this->output->write(' - ok');
        return $data;
    }

    /**
     * @param resource $handler
     * @return array|null
     */
    private function readNextMessage($handler): ?array
    {
        $endChar = chr(2);

        $tryLimit = 20000;

        $this->output->write('   - wait for next message');

        $try = 0;
        while (fread($handler, 1) !== $endChar) {
            $try ++;
            if ($try > $tryLimit) {
                $this->output->write('   - error limit reached');
                return null;
            }
        }

        $this->output->write('   - read message');

        $string = '';
        $try = 0;
        while (($current = fread($handler, 1)) !== $endChar) {
            $try ++;
            if ($try > $tryLimit) {
                $this->output->write('   - error limit reached');
                return null;
            }
            $string.= $current;
        }
        $rows = explode("\n", $string);

        $this->output->write('   - convert message');

        $values = [];
        foreach ($rows as $row) {
            list($key, $value) = $this->readRow($row);
            if ($key !== null) {
                $values[$key] = $value;
            }
        }

        return $values;
    }

    /**
     * @param string $row
     * @return array
     */
    private function readRow(string $row): array
    {
        $row = preg_replace('/[^a-zA-Z0-9 ]/', '', $row);

        $values = explode(" ", $row);
        if (count($values) < 3) {
            return [null, null];
        }
        $values = array_map('trim', $values);

        return $values;
    }

    /**
     * @param array $values
     * @return LinkyData
     */
    private function createDataFromValues(array $values): LinkyData
    {
        $data = new LinkyData();

        if (array_key_exists('ADCO', $values)) {
            $data->setLinkyIdentifier((string) $values['ADCO']);
        }
        if (array_key_exists('OPTARIF', $values)) {
            $data->setPricingOption((string) $values['OPTARIF']);
        }
        if (array_key_exists('ISOUSC', $values)) {
            $data->setSubscribedIntensity((int) $values['ISOUSC']);
        }
        if (array_key_exists('HCHC', $values)) {
            $data->setConsumptionOffPeakHour((int) $values['HCHC']);
        }
        if (array_key_exists('HCHP', $values)) {
            $data->setConsumptionPeakHour((int) $values['HCHP']);
        }
        if (array_key_exists('PTEC', $values)) {
            $data->setOffPeakHour($values['PTEC'] == 'HC');
        }
        if (array_key_exists('IINST', $values)) {
            $data->setInstantaneousIntensity((int) $values['IINST']);
        }
        if (array_key_exists('IMAX', $values)) {
            $data->setMaxIntensity((int) $values['IMAX']);
        }
        if (array_key_exists('PAPP', $values)) {
            $data->setApparentPower((int) $values['PAPP']);
        }
        if (array_key_exists('HHPHC', $values)) {
            $data->setTimeGroup((string) $values['HHPHC']);
        }
        if (array_key_exists('MOTDETAT', $values)) {
            $data->setStateWord((string) $values['MOTDETAT']);
        }

        if (!$data->getLinkyIdentifier()) {
            throw new \Exception('Data is invalid');
        }

        return $data;
    }
}