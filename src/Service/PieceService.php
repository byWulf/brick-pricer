<?php

declare(strict_types=1);

namespace App\Service;

use App\Client\RebrickableClient;
use App\Entity\Piece;
use App\Entity\PieceNumber;

class PieceService
{
    public function __construct(
        private RebrickableClient $rebrickableClient
    ) {
    }

    public function enrichPieceInformation(Piece $piece, ?array $partData = null): Piece
    {
        if ($partData === null) {
            $partData = $this->rebrickableClient->getPart($piece->getPartNumber());
        }

        $piece->setName($partData['name']);

        $colorResponse = $this->rebrickableClient->getPartColor($piece->getPartNumber(), $piece->getColor()->getId());
        $piece->setImageUrl($colorResponse['part_img_url']);

        foreach ($partData['external_ids'] as $system => $externalIdResponse) {
            $pieceNumber = new PieceNumber();
            $pieceNumber
                ->setSystem($system)
                ->setIds($externalIdResponse)
            ;
            $piece->addExternalId($pieceNumber);
        }

        $piece->updateCache();

        return $piece;
    }
}
