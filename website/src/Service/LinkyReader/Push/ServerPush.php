<?php

declare(strict_types=1);

namespace App\Service\LinkyReader\Push;

use App\Entity\EnergyData;
use App\Service\LinkyReader\Output;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Spipu\ConfigurationBundle\Service\ConfigurationManager;
use Symfony\Component\HttpClient\HttpClient;
use Throwable;

class ServerPush implements PushInterface
{
    private ConfigurationManager $configurationManager;
    private EntityManagerInterface $entityManager;

    public function __construct(
        ConfigurationManager $configurationManager,
        EntityManagerInterface $entityManager
    ) {
        $this->configurationManager = $configurationManager;
        $this->entityManager = $entityManager;
    }

    public function getCode(): string
    {
        return 'server';
    }

    /**
     * @param EnergyData $energyData
     * @param Output $output
     * @return void
     * @throws Throwable
     * @SuppressWarnings(PMD.StaticAccess)
     */
    public function push(EnergyData $energyData, Output $output): void
    {
        if (!$this->getConfEnabled()) {
            $output->write(' => disabled in configuration');
            return;
        }

        $output->write(' - prepare query');
        $values = $energyData->getDataToPush();

        $fields = array(
            'username' => $this->getConfApiName(),
            'time'     => time(),
            'rand'     => uniqid(),
            'values'   => json_encode($values),
        );
        $output->write(print_r($fields, true));

        $fields['hash'] = sha1(http_build_query($fields) . $this->getConfApiKey());

        $output->write(' - make query');

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

            $output->write(' - ok');
            $energyData->setPushStatus($energyData::PUSH_STATUS_PUSHED);
        } catch (Throwable $e) {
            $output->write(' - error');
            $energyData->setPushStatus($energyData::PUSH_STATUS_ERROR);
            $energyData->setPushNbTry($energyData->getPushNbTry() + 1);
            $energyData->setPushLastError($e->getCode() . ' - ' . $e->getMessage());
            throw $e;
        } finally {
            $this->entityManager->flush();
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
