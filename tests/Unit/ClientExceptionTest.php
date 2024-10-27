<?php

declare(strict_types=1);

use Bixi\Client\Exceptions\ClientException;

it('it throws an exception and retrieves errors', function (): void {
    $errors = [
        [
            'status' => 422,
            'source' => [
                'pointer' => 'data/attributes/name',
            ],
            'title' => 'Invalid request payload',
            'detail' => 'The "name" field is required.',
        ],
    ];

    $exception = new ClientException('Validation failed', 422, $errors);

    expect($exception->getErrors())->toBe($errors)
        ->and($exception->getErrors()[0]['detail'])->toBe('The "name" field is required.');
});
