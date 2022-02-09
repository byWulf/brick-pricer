<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Piece;
use App\Entity\PiecePrice;
use App\PriceFetcher\PriceFetcherInterface;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class PriceService
{
    /**
     * @param iterable<PriceFetcherInterface> $priceFetchers
     */
    public function __construct(
        private iterable $priceFetchers,
        private LoggerInterface $logger,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function updatePrices(Piece $piece): void
    {
        foreach ($this->priceFetchers as $priceFetcher) {
            $price = $piece->getPriceBySource($priceFetcher->getSource());
            if ($price !== null && $price->getUpdated() >= new DateTimeImmutable('-1 day')) {
                $this->logger->debug('Piece "' . $piece . '" already has an up-to-date price from "' . $priceFetcher->getSource() . '".');
                continue;
            }
            if ($price === null) {
                $price = new PiecePrice();
                $price->setSource($priceFetcher->getSource());
                $piece->addPrice($price);
            }

            $this->logger->debug('Checking "' . $priceFetcher->getSource() . '" price for piece "' . $piece . '"...');

            $priceFetcher->fetchPrice($piece, $price);
            $price->setUpdated(new DateTimeImmutable());
        }

        $piece->updateCache();
        $this->entityManager->flush();
    }
}
