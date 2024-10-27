<?php

declare(strict_types=1);

use Bixi\Client\Bixi;
use Bixi\Client\Exceptions\ClientException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Psr\Http\Message\RequestInterface;

beforeEach(function (): void {
    $this->client = Mockery::mock(Client::class);
    $this->bixi = new Bixi('My-API-Token', 'https://api.example.com/3.0');
    invade($this->bixi)->client = $this->client;
});

it('processes a successful payment', function (): void {
    $response = new GuzzleResponse(200, [], json_encode([
        'data' => [
            'id' => '9d1ad1f3-4d9b-47f2-8ea1-d766face25ed',
            'type' => 'transactions',
            'attributes' => [
                'memo' => 'credit',
                'accountNumber' => '+252600000000',
                'receiptId' => '123456',
                'amount' => 1.00,
                'description' => 'Payment for invoice No. 123456',
            ],
        ],
    ]));

    $this->client->shouldReceive('post')
        ->once()
        ->andReturn($response);

    $response = $this->bixi->pay([
        'memo' => 'credit',
        'accountNumber' => '+252600000000',
        'receiptId' => '123456',
        'amount' => 1.00,
        'description' => 'Payment for invoice No. 123456',
    ]);

    expect($response->getId())->toBe('9d1ad1f3-4d9b-47f2-8ea1-d766face25ed')
        ->and($response->getAttributes())->toHaveKey('receiptId')
        ->and($response->getAttribute('amount'))->toBe(1.00);
});

it('throws ClientException on validation error', function (): void {
    $mockResponse = new GuzzleResponse(422, [], json_encode([
        'errors' => [
            [
                'status' => 422,
                'code' => 'validation_failed',
                'title' => 'Invalid request payload',
                'detail' => 'The "accountNumber" field is required.',
            ],
        ],
    ]));

    $this->client->shouldReceive('post')
        ->once()
        ->andThrow(new RequestException('Validation error', Mockery::mock(RequestInterface::class), $mockResponse));

    try {
        $this->bixi->pay([
            'memo' => 'credit',
            'receiptId' => '123456',
            'amount' => 1.00,
            'description' => 'Test description',
        ]);
    } catch (ClientException $e) {
        expect($e->getErrors())->toBeArray()
            ->and($e->getErrors()[0]['status'])->toBe(422)
            ->and($e->getErrors()[0]['detail'])->toBe('The "accountNumber" field is required.');
    }
});

it('throws ClientException with null response', function (): void {
    $this->client->shouldReceive('post')
        ->once()
        ->andThrow(new RequestException(
            'No response from server',
            Mockery::mock(RequestInterface::class),
            null
        ));

    try {
        $this->bixi->pay([
            'memo' => 'credit',
            'accountNumber' => '+252600000000',
            'receiptId' => '123456',
            'amount' => 1.00,
            'description' => 'Test description',
        ]);
    } catch (ClientException $e) {
        expect($e->getErrors())->toBeEmpty()
            ->and($e->getMessage())->toBe('Request failed');
    }
});
