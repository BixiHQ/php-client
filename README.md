# Bixi PHP Client

| Main                                                                                            |                                              Dev                                               |                                Coverage                                |
|-------------------------------------------------------------------------------------------------|:----------------------------------------------------------------------------------------------:|:----------------------------------------------------------------------:|
| ![Main](https://github.com/BixiHQ/php-client/actions/workflows/tests.yml/badge.svg?branch=main) | ![Main](https://github.com/BixiHQ/php-client/actions/workflows/tests.yml/badge.svg?branch=dev) | ![Coverage](https://github.com/BixiHQ/php-client/raw/art/coverage.svg) |

---

This package provides a small client for interacting with the Bixi API to process payments and receive responses.

## Features

- Simple and intuitive client setup.
- Comprehensive test coverage using Pest.

## Requirements

- PHP 8.3 or higher
- Composer
- GuzzleHTTP
- Pest (for testing)

## Installation

Install the package via Composer:

```bash
composer require bixi/client
```

## Getting Started

### Step 1: Initialize the Client

```php
use Bixi\Client\Bixi;

$apiToken = 'Your-API-Token';
$bixi = new Bixi($apiToken);
```

### Step 2: Making a Payment Request

```php
try {
    $response = $bixi->pay([
        'memo' => 'credit',
        'accountNumber' => '+252600000000',
        'accountType' => 'mmt',
        'receiptId' => '123456',
        'amount' => 1.00,
        'description' => 'Payment for invoice No. 123456',
    ]);

    echo "Transaction Successful! Transaction ID: " . $response->getId() . PHP_EOL;
    print_r($response->toArray());
} catch (ClientException $e) {
    echo "Error: {$e->getMessage()}" . PHP_EOL;
    print_r($e->getErrors());
}
```

### Step 3: Handling Responses

You can access different parts of the response using methods like `getId()`, `getAttributes()`, and `getAttribute()`:

```php
$id = $response->getId(); // Transaction ID
$attributes = $response->getAttributes(); // All attributes
$amount = $response->getAttribute('amount'); // Specific attribute
```

### Step 4: Handling Exceptions

When an error occurs, a `ClientException` will be thrown with detailed error messages:

```php
catch (ClientException $e) {
    foreach ($e->getErrors() as $error) {
        echo "Error Code: {$error['code']} - {$error['detail']}" . PHP_EOL;
    }
}
```

## Testing

This package includes comprehensive test coverage using the Pest PHP testing framework. To run the tests, use the following command:

```bash
./vendor/bin/pest
```

## License

This package is licensed under the MIT license. Please see the `LICENSE` file for more information.

## Contributing

Contributions are welcome! Please feel free to open issues or submit pull requests.

## Changelog

For the complete list of changes, please see the [changelog file](CHANGELOG.md).

## Contact

For support or inquiries, please contact the package maintainer at hi@bixi.so.
