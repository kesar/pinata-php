<?php

namespace Pinata;

use GuzzleHttp\Client;

class Pinata
{
    private $client;

    function __construct(string $apiKey, string $secretKey)
    {
        $client = new Client([
                'base_uri' => 'https://api.pinata.cloud',
                'headers' => [
                    'pinata_api_key' => $apiKey,
                    'pinata_secret_api_key' => $secretKey,
                ],
            ]
        );

        $this->client = $client;
    }

    function addHashToPinQueue(string $hashToPin): array
    {
        return $this->doCall('/pinning/addHashToPinQueue', 'POST', ['hashToPin' => $hashToPin]);
    }

    function pinFileToIPFS($file): array
    {
        return $this->doCall('/pinning/pinFileToIPFS', 'POST');
    }

    function pinHashToIPFS(string $hashToPin): array
    {
        return $this->doCall('/pinning/pinHashToIPFS', 'POST', ['hashToPin' => $hashToPin]);
    }

    function pinJobs(): array
    {
        return json_encode($this->client->get('/pinning/pinJobs')->getBody()->getContents(), true);
    }

    function pinJSONToIPFS(array $json): array
    {
        return $this->doCall('/pinning/pinJSONToIPFS', 'POST', $json);
    }

    function removePinFromIPFS(string $hash): bool
    {
        $return = $this->client->post('/pinning/removePinFromIPFS', [
            \GuzzleHttp\RequestOptions::JSON => ['ipfs_pin_hash' => $hash],
        ]);

        return $return->getStatusCode() === 200;
    }

    function userPinnedDataTotal(): array
    {
        return json_encode($this->client->get('/data/userPinnedDataTotal')->getBody()->getContents(), true);
    }

    function userPinList(): array
    {
        return json_encode($this->client->get('/data/userPinList')->getBody()->getContents(), true);
    }

    private function doCall(string $endpoint, string $method = 'POST', array $params = []): array
    {
        $response = $this->client->request($method, $endpoint,
            [
                \GuzzleHttp\RequestOptions::JSON => $params,
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }
}


