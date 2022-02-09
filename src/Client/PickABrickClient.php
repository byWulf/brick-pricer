<?php

declare(strict_types=1);

namespace App\Client;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class PickABrickClient
{
    public function __construct(
        private HttpClientInterface $legoClient
    ) {
    }

    public function getPickABrickParts(): array
    {
        $elements = [];
        $page = 1;
        do {
            $response = $this->legoClient->request(
                'POST',
                '/api/graphql/PickABrickQuery', [
                    'headers' => [
                        'Referer' => 'https://www.lego.com/de-de/page/static/pick-a-brick',
                        'Origin' => 'https://www.lego.com',
                        'x-locale' => 'de-DE',
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                        'session-cookie-id' => 'lego',
                    ],
                    'json' => [
                        'operationName' => 'PickABrickQuery',
                        'query' => 'query PickABrickQuery($query: String, $page: Int, $perPage: Int, $sort: SortInput, $filters: [Filter!]) {
                            elements(
                                query: $query
                                page: $page
                                perPage: $perPage
                                filters: $filters
                                sort: $sort
                            ) {
                                count
                                results {
                                    ...ElementLeafData
                                }
                                total
                            }
                        }
                        
                        fragment ElementLeafData on Element {
                            ... on SingleVariantElement {
                                variant {
                                    ...ElementLeafVariant
                                }
                            }
                        }
                        
                        fragment ElementLeafVariant on ElementVariant {
                            id
                            price {
                                centAmount
                                formattedAmount
                            }
                            attributes {
                                designNumber
                                colourId
                                deliveryChannel
                            }
                        }',
                        'variables' => [
                            'page' => $page,
                            'perPage' => 500,
                            'query' => '',
                            'filters' => [],
                            'sort' => ['key' => 'RELEVANCE', 'direction' => 'ASC'],
                        ]
                    ]
                ]
            )->toArray();

            $responseElements = $response['data']['elements']['results'];
            $elements = array_merge($elements, $responseElements);
            $page++;
        } while (count($responseElements) > 0);

        return $elements;
    }
}
