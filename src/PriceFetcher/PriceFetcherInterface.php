<?php

declare(strict_types=1);

namespace App\PriceFetcher;

use App\Entity\Piece;
use App\Entity\PiecePrice;

interface PriceFetcherInterface
{
    public function getSource(): string;

    public function fetchPrice(Piece $piece, PiecePrice $price): void;
}
