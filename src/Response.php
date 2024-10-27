<?php

declare(strict_types=1);

namespace Bixi\Client;

class Response
{
    protected array $data;

    public function __construct(array $response)
    {
        $this->data = $response['data'] ?? null;
    }

    public function getId(): ?string
    {
        return $this->data['id'] ?? null;
    }

    public function getAttributes(): ?array
    {
        return $this->data['attributes'] ?? null;
    }

    public function getAttribute(string $key): float|string|null
    {
        return $this->data['attributes'][$key]
            ? $this->castAttribute($this->data['attributes'][$key])
            : null;
    }

    public function toArray(): array
    {
        return $this->data;
    }

    private function castAttribute(mixed $value): mixed
    {
        if (is_numeric($value) && (string) (float) $value !== (string) (int) $value) {
            return (float) $value;
        }

        return $value;
    }
}
