<?php

namespace App\Services;

use App\Services\Contracts\APIInterface;
use Illuminate\Support\Facades\Http;
use Exception;

abstract class API implements APIInterface
{
    protected ?string $secret = null;
    protected ?int $perPage = null;
    protected int $retryAttempts = 3;
    protected int $retryBackoffBase = 1000;

    abstract public function baseUrl(): string;

    public function getPerPage(): ?int
    {
        return $this->perPage;
    }

    public function setPerPage(int $perPage): static
    {
        $this->perPage = $perPage;
        return $this;
    }

    protected function client()
    {
        return Http::withHeaders([
            'Authorization' => $this->secret ? 'Bearer ' . $this->secret : '',
            'Content-Type'  => 'application/json',
        ])
            ->baseUrl($this->baseUrl())
            ->retry($this->retryAttempts, $this->retryBackoffBase);
    }

    protected function execute(string $method, string $url, array $parameters = []): array
    {
        try {
            if (strtolower($method) === 'get') {
                if ($this->perPage) {
                    $parameters['limit'] = $this->perPage;
                }
                $response = $this->client()->get($url, $parameters);
            } else {
                $response = $this->client()->$method($url, $parameters);
            }

            return $response->json() ?? [];
        } catch (Exception $e) {
            return [
                'status' => false,
                'status_code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }
    }

    public function _get(?string $url = null, $parameter = []): array
    {
        return $this->execute('get', $url, $parameter);
    }

    public function _post(?string $url = null, array $parameter = []): array
    {
        return $this->execute('post', $url, $parameter);
    }

    public function _put(?string $url = null, array $parameters = []): array
    {
        return $this->execute('put', $url, $parameters);
    }

    public function _patch(?string $url = null, array $parameters = []): array
    {
        return $this->execute('patch', $url, $parameters);
    }

    public function _delete(?string $url = null, array $parameters = []): array
    {
        return $this->execute('delete', $url, $parameters);
    }

    public function _head(?string $url = null, array $parameters = []): array
{
    return $this->execute('head', $url, $parameters);
}

public function _options(?string $url = null, array $parameters = []): array
{
    return $this->execute('options', $url, $parameters);
}
}
