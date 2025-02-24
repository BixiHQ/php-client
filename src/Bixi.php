<?php

declare(strict_types=1);

namespace Bixi\Client;

use Bixi\Client\Exceptions\ClientException;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class Bixi
{
    protected string $apiToken;

    protected string $baseUrl;

    protected HttpClient $client;

    public function __construct(string $apiToken, string $baseUrl = 'https://api.bixi.co/3.0/')
    {
        $this->apiToken = $apiToken;
        $this->baseUrl = $baseUrl;

        $this->client = new HttpClient([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$this->apiToken}",
            ],
        ]);
    }

    /**
     * @throws GuzzleException
     * @throws ClientException
     */
    public function pay(array $attributes): Response
    {
        try {
            $response = $this->client->post('pay', [
                'json' => [
                    'data' => [
                        'type' => 'transactions',
                        'attributes' => $attributes,
                    ],
                ],
            ]);

            $body = json_decode($response->getBody()->getContents(), true);

            return new Response($body);

        } catch (RequestException $e) {
            $body = $e->getResponse()
                ? json_decode($e->getResponse()->getBody()->getContents(), true)
                : null;

            $errors = $body['errors'] ?? [];

            throw new ClientException('Request failed', $e->getCode(), $errors);
        }
    }
}
