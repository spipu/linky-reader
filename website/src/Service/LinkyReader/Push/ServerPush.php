<?php

declare(strict_types=1);

namespace App\Service\LinkyReader\Push;

use App\Entity\LinkyData;
use App\Service\LinkyReader\Output;
use Exception;
use Symfony\Component\HttpClient\HttpClient;

class ServerPush implements PushInterface
{
    private string $pushUrl;
    private string $apiName;
    private string $apiKey;

    public function __construct(
        string $pushUrl,
        string $apiName,
        string $apiKey
    ) {
        $this->pushUrl = $pushUrl;
        $this->apiName = $apiName;
        $this->apiKey = $apiKey;
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
        $output->write(' - prepare query');
        $values = json_decode(json_encode($linkyData), true);
        unset($values['linkyIdentifier']);

        $fields = array(
            'username' => $this->apiName,
            'time'     => time(),
            'rand'     => uniqid(),
            'values'   => json_encode($values),
        );

        $fields['hash'] = sha1(http_build_query($fields) . $this->apiKey);

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
            $this->pushUrl,
            ['body' => $fields]
        );
        if ($response->getStatusCode() !== 200) {
            $output->write(' - error');
            throw new Exception($response->getContent());
        }

        $output->write(' - ok');
    }
}
