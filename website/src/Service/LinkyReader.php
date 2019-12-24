<?php
declare(strict_types = 1);

namespace App\Service;

use App\Entity\LinkyData;

class LinkyReader
{
    /**
     * @var string
     */
    private $source;

    /**
     * LinkyReader constructor.
     * @param string $source
     */
    public function __construct(string $source = '/dev/ttyUSB0')
    {
        $this->source = $source;
    }

    /**
     * @return LinkyData|null
     */
    public function read(): ?LinkyData
    {
        $data = null;
        try {
            $handler = fopen($this->source, 'r');
            if (!$handler) {
                return null;
            }

            $values = $this->readNextMessage($handler);
            if ($values === null) {
                return null;
            }

            $data = $this->createDataFromValues($values);
        } finally {
            if ($handler) {
                fclose($handler);
            }
        }

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

        $try = 0;
        while (fread($handler, 1) !== $endChar) {
            $try ++;
            if ($try > $tryLimit) {
                return null;
            }
        }

        $string = '';
        $try = 0;
        while (($current = fread($handler, 1)) !== $endChar) {
            $try ++;
            if ($try > $tryLimit) {
                return null;
            }
            $string.= $current;
        }
        $rows = explode("\n", $string);

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

        return $data;
    }
}