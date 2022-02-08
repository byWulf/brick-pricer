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
                    ],
                    'json' => [
                        'operationName' => 'PickABrickQuery',
                        'query' => 'query PickABrickQuery($page: Int, $perPage: Int) { 
                        __typename  elements(    page: $page    perPage: $perPage  ) {    
                            results 
                            {      
                            ...ElementLeafData      __typename    
                            }    
                            total    __typename  
                        }
                    }
                    fragment ElementLeafData on Element {  
                        ... on SingleVariantElement {    
                            variant {      
                                ...ElementLeafVariant      __typename    
                            }    
                            __typename  
                        }  
                        ... on MultiVariantElement 
                        {    
                            variants {      
                                ...ElementLeafVariant      __typename    
                            }    
                            __typename  
                        }  
                        __typename
                    }
                    fragment ElementLeafVariant on ElementVariant {  
                        id  price {    
                            centAmount    formattedAmount    __typename  
                        }  
                        attributes {    
                            designNumber    colourId    deliveryChannel    __typename  
                        }  
                        __typename
                    }',
                        'variables' => [
                            'page' => $page,
                            'perPage' => 500
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
