<?php

declare(strict_types=1);

namespace App\Service\LinkyReader;

use App\Entity\EnergyData;

class LinkyReader
{
    private Output $output;
    private string $source;

    public function __construct(Output $output, string $source = '/dev/ttyUSB0')
    {
        $this->output = $output;
        $this->source = $source;
    }

    public function read(): ?EnergyData
    {
        $this->output->write('Read Linky Data');
        $data = null;
        $handler = null;
        try {
            $this->output->write(' - connect to ' . $this->source);

            ini_set('max_execution_time', '30');
            exec("stty -F {$this->source} 1200 sane evenp parenb cs7 -crtscts");
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

            if (empty($values['ADCO'])) {
                $this->output->write('Data is invalid, ADCO is missing');
                $this->output->write(print_r($values, true));
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

        if ($data !== null) {
            $this->output->write(' - ok');
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

        $this->output->write('   - wait for next message');

        $try = 0;
        while (fread($handler, 1) !== $endChar) {
            $try++;
            if ($try > $tryLimit) {
                $this->output->write('   - error limit reached');
                return null;
            }
        }

        $this->output->write('   - read message');

        $string = '';
        $try = 0;
        while (($current = fread($handler, 1)) !== $endChar) {
            $try++;
            if ($try > $tryLimit) {
                $this->output->write('   - error limit reached');
                return null;
            }
            $string .= $current;
        }
        $this->output->write("\n" . trim(str_replace("\n\n", "\n", $string)) . "\n");
        $rows = explode("\n", $string);

        $this->output->write('   - convert message');

        $values = [];
        foreach ($rows as $row) {
            list($key, $value) = $this->readRow($row);
            if ($key !== null) {
                $values[$key] = $value;
            }
        }
        $this->output->write(print_r($values, true));

        return $values;
    }

    private function readRow(string $row): array
    {
        $row = preg_replace('/[^a-zA-Z0-9 ]/', '', $row);

        $values = explode(" ", $row);
        if (count($values) < 3) {
            return [null, null];
        }
        return array_map('trim', $values);
    }

    private function createDataFromValues(array $values): EnergyData
    {
        $offPeakValues = ['HC'];

        $roundTime = ((int) (time() / 60)) * 60;

        $data = new EnergyData();
        $data
            ->setPushNbTry(0)
            ->setPushStatus($data::PUSH_STATUS_WAITING)
            ->setPushLastError(null)
            ->setTime($roundTime)
            ->setPricingOption((string) ($values['OPTARIF'] ?? ''))
            ->setSubscribedIntensity((int) ($values['ISOUSC'] ?? 0))
            ->setTimeGroup((string) ($values['HHPHC'] ?? ''))
            ->setStateWord((string) ($values['MOTDETAT'] ?? ''))
            ->setApparentPower((int) ($values['PAPP'] ?? 0))
            ->setOffPeakHour(in_array((string) ($values['PTEC'] ?? ''), $offPeakValues))
        ;

        $this->prepareDataInstantaneousIntensity($values, $data);
        $this->prepareDataMaxIntensity($values, $data);
        $this->prepareDataConsumption($data, $values);

        return $data;
    }

    /**
     * @param array $values
     * @param EnergyData $data
     * @return void
     */
    public function prepareDataInstantaneousIntensity(array $values, EnergyData $data): void
    {
        if (
            array_key_exists('IINST1', $values)
            && array_key_exists('IINST2', $values)
            && array_key_exists('IINST3', $values)
        ) {
            $data->setInstantaneousIntensity((int)$values['IINST1'] + (int)$values['IINST2'] + (int)$values['IINST3']);
            $data->setInstantaneousIntensity1((int)$values['IINST1']);
            $data->setInstantaneousIntensity2((int)$values['IINST2']);
            $data->setInstantaneousIntensity3((int)$values['IINST3']);
            return;
        }

        if (array_key_exists('IINST', $values)) {
            $data->setInstantaneousIntensity((int)$values['IINST']);
            $data->setInstantaneousIntensity1(null);
            $data->setInstantaneousIntensity2(null);
            $data->setInstantaneousIntensity3(null);
            return;
        }

        $data->setInstantaneousIntensity(0);
        $data->setInstantaneousIntensity1(null);
        $data->setInstantaneousIntensity2(null);
        $data->setInstantaneousIntensity3(null);
    }
    /**
     * @param array $values
     * @param EnergyData $data
     * @return void
     */
    public function prepareDataMaxIntensity(array $values, EnergyData $data): void
    {
        if (
            array_key_exists('IMAX1', $values)
            && array_key_exists('IMAX2', $values)
            && array_key_exists('IMAX3', $values)
        ) {
            $data->setMaxIntensity((int)$values['IMAX1'] + (int)$values['IMAX2'] + (int)$values['IMAX3']);
            $data->setMaxIntensity1((int)$values['IMAX1']);
            $data->setMaxIntensity2((int)$values['IMAX2']);
            $data->setMaxIntensity3((int)$values['IMAX3']);
            return;
        }

        if (array_key_exists('IMAX', $values)) {
            $data->setMaxIntensity((int)$values['IMAX']);
            $data->setMaxIntensity1(null);
            $data->setMaxIntensity2(null);
            $data->setMaxIntensity3(null);
            return;
        }

        $data->setMaxIntensity(0);
        $data->setMaxIntensity1(null);
        $data->setMaxIntensity2(null);
        $data->setMaxIntensity3(null);
    }

    /**
     * @param EnergyData $data
     * @param array $values
     * @return void
     * @SuppressWarnings(PMD.CyclomaticComplexity)
     */
    public function prepareDataConsumption(EnergyData $data, array $values): void
    {
        // Index option Base.
        if (array_key_exists('BASE', $values)) {
            $data->setConsumptionPeakHour((int) $values['BASE']);
            $data->setConsumptionOffPeakHour(0);
            return;
        }

        // Index option Heures Creuses.
        if (
            array_key_exists('HCHC', $values)
            && array_key_exists('HCHP', $values)
        ) {
            $data->setConsumptionOffPeakHour((int) $values['HCHC']);
            $data->setConsumptionPeakHour((int) $values['HCHP']);
            return;
        }

        // Index option EJP.
        if (
            array_key_exists('EJPHN', $values)
            && array_key_exists('EJPHPM', $values)
        ) {
            $data->setConsumptionOffPeakHour((int) $values['EJPHN']);
            $data->setConsumptionPeakHour((int) $values['EJPHPM']);
            return;
        }

        // Index option Tempo.
        if (
            array_key_exists('BBRHCJB', $values)
            && array_key_exists('BBRHPJB', $values)
            && array_key_exists('BBRHCJW', $values)
            && array_key_exists('BBRHPJW', $values)
            && array_key_exists('BBRHCJR', $values)
            && array_key_exists('BBRHPJR', $values)
        ) {
            $data->setConsumptionOffPeakHour(
                (int) $values['BBRHCJB'] + (int) $values['BBRHCJW'] + (int) $values['BBRHCJR']
            );
            $data->setConsumptionPeakHour(
                (int) $values['BBRHPJB'] + (int) $values['BBRHPJW'] + (int) $values['BBRHPJR']
            );
            return;
        }

        $data->setConsumptionPeakHour(0);
        $data->setConsumptionOffPeakHour(0);
    }
}
