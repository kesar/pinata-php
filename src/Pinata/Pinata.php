<?php

namespace Pinata;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class Pinata
{
    private $client;

    function __construct(string $apiKey, string $secretKey)
    {
        $client = new Client([
                'base_uri' => 'https://api.pinata.cloud',
                RequestOptions::HEADERS => [
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

    function pinFileToIPFS(string $filePath, array $metadata = null): array
    {
        $options = [
            RequestOptions::MULTIPART => [
                [
                    'name'     => 'file',
                    'contents' => fopen($filePath, 'r'),
                ],
            ],
        ];
        if (!empty($metadata)) {
            $options[RequestOptions::MULTIPART][] = [
                'name'     => 'pinataMetadata',
                'contents' => json_encode($metadata),
            ];
        }

        return json_decode($this->client->post('/pinning/pinFileToIPFS', $options)
            ->getBody()->getContents(), true);
    }

    function pinHashToIPFS(string $hashToPin): array
    {
        return $this->doCall('/pinning/pinHashToIPFS', 'POST', ['hashToPin' => $hashToPin]);
    }

    function pinJobs(): array
    {
        return json_decode($this->client->get('/pinning/pinJobs')->getBody()->getContents(), true);
    }

    function pinJSONToIPFS(array $json, array $metadata = null): array
    {
        $content = ($metadata) ? ['pinataMetadata' => $metadata, 'pinataContent' => $json] : $json;
        return $this->doCall('/pinning/pinJSONToIPFS', 'POST', $content);
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
        return json_decode($this->client->get('/data/userPinnedDataTotal')->getBody()->getContents(), true);
    }

    function pinList(): array
    {
        return json_decode($this->client->get('/data/pinList')->getBody()->getContents(), true);
    }

    private function doCall(string $endpoint, string $method = 'POST', array $params = []): array
    {
        $response = $this->client->request($method, $endpoint,
            [
                RequestOptions::JSON => $params,
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }
}


