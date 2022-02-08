<?php

declare(strict_types=1);

namespace App\Client;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class RebrickableClient
{
    private ?string $token = null;

    public function __construct(
        private HttpClientInterface $rebrickableClient,
        private string $userToken
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

    public function getParts(array $partNumbers): array
    {
        return $this->rebrickableClient->request(
            'GET',
            '/api/v3/lego/parts', [
                'query' => [
                    'page_size' => 1000,
                    'part_nums' => implode(',', $partNumbers),
                    'inc_part_details' => 1,
                    'inc_color_details' => 0,
                ],
            ],
        )->toArray();
    }

    public function getPart(string $partNumber): array
    {
        return $this->rebrickableClient->request(
            'GET',
            '/api/v3/lego/parts/' . $partNumber
        )->toArray();
    }

    public function getPartColor(string $partNumber, int $colorId): array
    {
        return $this->rebrickableClient->request(
            'GET',
            '/api/v3/lego/parts/' . $partNumber . '/colors/' . $colorId
        )->toArray();
    }

    public function getPartLists(): array
    {
        return $this->rebrickableClient->request(
            'GET',
            '/api/v3/users/' . $this->userToken . '/partlists/', [
                'query' => [
                    'page_size' => 1000,
                ],
            ],
        )->toArray();
    }

    public function getPartsOfPartList(int $partListId): array
    {
        $overallResponse = null;

        $page = 0;
        do {
            $page++;

            $response = $this->rebrickableClient->request(
                'GET',
                '/api/v3/users/' . $this->userToken . '/partlists/' . $partListId . '/parts/', [
                    'query' => [
                        'page' => $page,
                        'page_size' => 1000,
                        'inc_part_details' => 0,
                        'inc_color_details' => 0,
                    ],
                ],
            )->toArray();

            if ($overallResponse === null) {
                $overallResponse = $response;
            } else {
                $overallResponse['results'] = array_merge($overallResponse['results'], $response['results']);
            }
        } while ($response['next'] !== null);

        return $overallResponse;
    }
}
