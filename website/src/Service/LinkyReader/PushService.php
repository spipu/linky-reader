<?php

declare(strict_types=1);

namespace App\Service\LinkyReader;

use App\Entity\EnergyData;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Spipu\ConfigurationBundle\Service\ConfigurationManager;
use Symfony\Component\HttpClient\HttpClient;
use Throwable;

class PushService
{
    private Output $output;
    private ConfigurationManager $configurationManager;
    private EntityManagerInterface $entityManager;

    public function __construct(
        Output $output,
        ConfigurationManager $configurationManager,
        EntityManagerInterface $entityManager
    ) {
        $this->output = $output;
        $this->configurationManager = $configurationManager;
        $this->entityManager = $entityManager;
    }

    /**
     * @param EnergyData $energyData
     * @return void
     * @throws Throwable
     * @SuppressWarnings(PMD.StaticAccess)
     */
    public function push(EnergyData $energyData): void
    {
        $this->output->write('Push - BEGIN');

        if (!$this->getConfEnabled()) {
            $this->output->write(' => disabled in configuration');
            return;
        }

        $this->output->write(' - prepare query for #' . $energyData->getId());
        $values = $energyData->getDataToPush();

        $fields = array(
            'username' => $this->getConfApiName(),
            'time'     => time(),
            'rand'     => uniqid(),
            'values'   => json_encode($values),
        );

        $fields['hash'] = sha1(http_build_query($fields) . $this->getConfApiKey());

        $this->output->write(' - make query');

        $client = HttpClient::create(
            [
                'http_version'  => '1.1',
                'timeout'       => 10,
                'max_redirects' => 5,
            ]
        );
        try {
            $response = $client->request('POST', $this->getConfUrl(), ['body' => $fields]);
            if ($response->getStatusCode() !== 200) {
                throw new Exception($response->getContent(), $response->getStatusCode());
            }

            $this->output->write(' - ok');
            $energyData->setPushStatus($energyData::PUSH_STATUS_PUSHED);
        } catch (Throwable $e) {
            $this->output->write(' - error');
            $energyData->setPushStatus($energyData::PUSH_STATUS_ERROR);
            $energyData->setPushNbTry($energyData->getPushNbTry() + 1);
            $energyData->setPushLastError($e->getCode() . ' - ' . $e->getMessage());
        } finally {
            $this->entityManager->flush();
            $this->output->write('Push - END');
        }
    }

    private function getConfEnabled(): bool
    {
        return (int) $this->configurationManager->get('linky.server_push.enable') === 1;
    }

    private function getConfUrl(): string
    {
        return (string) $this->configurationManager->get('linky.server_push.url');
    }

    private function getConfApiName(): string
    {
        return (string) $this->configurationManager->get('linky.server_push.api_name');
    }

    private function getConfApiKey(): string
    {
        return (string) $this->configurationManager->getEncrypted('linky.server_push.api_key');
    }
}
