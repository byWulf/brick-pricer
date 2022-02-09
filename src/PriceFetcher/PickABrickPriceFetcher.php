<?php

declare(strict_types=1);

namespace App\PriceFetcher;

use App\Client\PickABrickClient;
use App\Entity\Piece;
use App\Entity\PiecePrice;
use Symfony\Contracts\Cache\CacheInterface;

class PickABrickPriceFetcher implements PriceFetcherInterface
{
    public function __construct(
        private PickABrickClient $pickABrickClient,
        private CacheInterface   $filesystemCache
    ) {
    }

    public function getSource(): string
    {
        return 'Pick a Brick';
    }

    public function fetchPrice(Piece $piece, PiecePrice $price): void
    {
        $externalIds = $piece->getExternalIdsBySystem('LEGO');
        if ($externalIds === null) {
            return;
        }

        $externalColor = $piece->getColor()->getExternalColorBySystem('LEGO');
        if ($externalColor === null) {
            return;
        }

        $parts = $this->filesystemCache->get('pickABrickParts', fn() => $this->pickABrickClient->getPickABrickParts());

        foreach ($parts as $part) {
            if (!isset($part['variant'])) {
                continue;
            }

            if (
                in_array($part['variant']['attributes']['designNumber'], $externalIds->getIds()) &&
                in_array($part['variant']['attributes']['colourId'], $externalColor->getIds())
            ) {
                $price->setPrice($part['variant']['price']['centAmount']);
                return;
            }
        }
    }
}
