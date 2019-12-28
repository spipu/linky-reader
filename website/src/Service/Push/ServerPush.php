<?php
declare(strict_types = 1);

namespace App\Service\Push;

use App\Entity\LinkyData;
use Symfony\Component\HttpClient\HttpClient;

class ServerPush implements PushInterface
{
    /**
     * @var string
     */
    private $pushUrl;

    /**
     * @var string
     */
    private $apiName;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * ServerPush constructor.
     * @param string $pushUrl
     * @param string $apiName
     * @param string $apiKey
     */
    public function __construct(
        string $pushUrl,
        string $apiName,
        string $apiKey
    ) {
        $this->pushUrl = $pushUrl;
        $this->apiName = $apiName;
        $this->apiKey = $apiKey;
    }

    /**
     * @param LinkyData $linkyData
     * @return void
     */
    public function push(LinkyData $linkyData): void
    {
        $values = json_decode(json_encode($linkyData), true);
        unset($values['linkyIdentifier']);

        $fields = array(
            'username' => $this->apiName,
            'time'     => time(),
            'rand'     => uniqid(),
            'values'   => json_encode($values),
        );

        $fields['hash'] = sha1(http_build_query($fields).$this->apiKey);

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
            throw new \Exception($response->getContent());
        }
        echo "OK\n";
    }
}
