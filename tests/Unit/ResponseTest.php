<?php

declare(strict_types=1);

use Bixi\Client\Response;

it('creates a response instance and retrieves data', function (): void {
    $responseData = [
        'data' => [
            'id' => '9d1ad1f3-4d9b-47f2-8ea1-d766face25ed',
            'attributes' => [
                'memo' => 'credit',
                'accountNumber' => '+252600000000',
                'receiptId' => '123456',
                'amount' => 1.00,
                'description' => 'Payment for invoice No. 123456',
            ],
        ],
    ];

    $response = new Response($responseData);

    expect($response->getId())->toBe('9d1ad1f3-4d9b-47f2-8ea1-d766face25ed')
        ->and($response->getAttributes())->toBe($responseData['data']['attributes'])
        ->and($response->getAttribute('amount'))->toBe(1.00)
        ->and($response->toArray())->toBe($responseData['data']);
});

it('casts a numeric value with decimals to float', function (): void {
    $responseData = [
        'data' => [
            'attributes' => [
                'amount' => '10.50',
            ],
        ],
    ];

    $response = new Response($responseData);

    expect($response->getAttribute('amount'))->toBe(10.50)
        ->and(fn ($val) => is_float($val));
});
