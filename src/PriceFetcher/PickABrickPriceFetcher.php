<?php

declare(strict_types=1);

namespace App\PriceFetcher;

use App\Client\PickABrickClient;
use App\Entity\Piece;
use Symfony\Contracts\Cache\CacheInterface;

class PickABrickPriceFetcher implements PriceFetcherInterface
{
    public function __construct(
        private PickABrickClient $pickABrickClient,
        private CacheInterface $pickABrickCache
    ) {
    }

    public function getSource(): string
    {
        return 'Pick a Brick';
    }

    public function fetchPrice(Piece $piece): ?int
    {
        $externalIds = $piece->getExternalIdsBySystem('LEGO');
        if ($externalIds === null) {
            return null;
        }

        $externalColor = $piece->getColor()->getExternalColorBySystem('LEGO');
        if ($externalColor === null) {
            return null;
        }

        $parts = $this->pickABrickCache->get('pickABrickParts', fn() => $this->pickABrickClient->getPickABrickParts());

        foreach ($parts as $part) {
            if (!isset($part['variant'])) {
                continue;
            }

            if (
                in_array($part['variant']['attributes']['designNumber'], $externalIds->getIds()) &&
                in_array($part['variant']['attributes']['colourId'], $externalColor->getIds())
            ) {
                return $part['variant']['price']['centAmount'];
            }
        }

        return null;
    }
}
