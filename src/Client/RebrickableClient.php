<?php

declare(strict_types=1);

namespace App\Client;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class RebrickableClient
{
    public function __construct(
        private HttpClientInterface $rebrickableClient
    ) {
    }

    public function getColors(): array
    {
        return $this->rebrickableClient->request(
            'GET',
            '/api/v3/lego/colors/', [
                'query' => [
                    'page_size' => 1000,
                ]
            ]
        )->toArray();
    }

    public function getPart(string $partNumber): array
    {
        return $this->rebrickableClient->request(
            'GET',
            '/api/v3/lego/parts/' . $partNumber,
        )->toArray();
    }

    public function getPartColors(string $partNumber): array
    {
        return $this->rebrickableClient->request(
            'GET',
            '/api/v3/lego/parts/' . $partNumber . '/colors',[
                'query' => [
                    'page_size' => 1000,
                ]
            ]
        )->toArray();
    }

    public function getPartColor(string $partNumber, int $colorId): array
    {
        return $this->rebrickableClient->request(
            'GET',
            '/api/v3/lego/parts/' . $partNumber . '/colors/' . $colorId
        )->toArray();
    }
}
