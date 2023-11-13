<?php

declare(strict_types=1);

namespace App\Service\LinkyReader\Push;

use App\Entity\LinkyData;
use App\Service\LinkyReader\Output;
use Exception;
use Spipu\ConfigurationBundle\Service\ConfigurationManager;
use Symfony\Component\HttpClient\HttpClient;

class ServerPush implements PushInterface
{
    private ConfigurationManager $configurationManager;

    public function __construct(
        ConfigurationManager $configurationManager
    ) {
        $this->configurationManager = $configurationManager;
    }

    public function getCode(): string
    {
        return 'server';
    }

    /**
     * @param LinkyData $linkyData
     * @param Output $output
     * @return void
     * @SuppressWarnings(PMD.StaticAccess)
     */
    public function push(LinkyData $linkyData, Output $output): void
    {
        if (!$this->getConfEnabled()) {
            $output->write(' => disabled in configuration');
            return;
        }

        $output->write(' - prepare query');
        $values = json_decode(json_encode($linkyData), true);
        unset($values['linkyIdentifier']);

        $fields = array(
            'username' => $this->getConfApiName(),
            'time'     => time(),
            'rand'     => uniqid(),
            'values'   => json_encode($values),
        );

        $fields['hash'] = sha1(http_build_query($fields) . $this->getConfApiKey());

        $output->write(' - make query');

        $client = HttpClient::create(
            [
                'http_version'  => '1.1',
                'timeout'       => 10,
                'max_redirects' => 5,
            ]
        );
        $response = $client->request(
            'POST',
            $this->getConfUrl(),
            ['body' => $fields]
        );
        if ($response->getStatusCode() !== 200) {
            $output->write(' - error');
            throw new Exception($response->getContent());
        }

        $output->write(' - ok');
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
