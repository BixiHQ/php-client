<?php

declare(strict_types=1);

namespace Bixi\Client;

class Response
{
    protected array $response;

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function getId(): ?string
    {
        return isset($this->response['data']['id'])
            ? $this->response['data']['id']
            : null;
    }

    public function getAttributes(): ?array
    {
        return isset($this->response['data']['attributes'])
            ? $this->response['data']['attributes']
            : null;
    }

    public function getAttribute(string $key): mixed
    {
        if (! isset($this->response['data']['attributes'][$key])) {
            return null;
        }

        return $this->castAttribute($this->response['data']['attributes'][$key]);
    }

    public function toArray(): array
    {
        return $this->response;
    }

    private function castAttribute(mixed $value): mixed
    {
        if (is_numeric($value) && (string) (float) $value !== (string) (int) $value) {
            return (float) $value;
        }

        return $value;
    }
}
