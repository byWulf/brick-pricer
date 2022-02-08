<?php

declare(strict_types=1);

namespace App\Command;

use App\Client\PickABrickClient;
use App\Entity\Piece;
use App\Entity\PiecePrice;
use App\PriceFetcher\PriceFetcherInterface;
use App\Repository\PieceRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\Cache\CacheInterface;

#[AsCommand(name: 'app:prices:import')]
class ImportPricesCommand extends Command
{
    /**
     * @param iterable<PriceFetcherInterface> $priceFetchers
     */
    public function __construct(
        private iterable $priceFetchers,
        private PieceRepository $pieceRepository,
        private LoggerInterface $logger,
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        /** @var Piece[] $pieces */
        $pieces = $this->pieceRepository->findAll();

        $io->progressStart(count($pieces));

        foreach ($pieces as $piece) {
            foreach ($this->priceFetchers as $priceFetcher) {
                $price = $piece->getPriceBySource($priceFetcher->getSource());
                if ($price !== null && $price->getUpdated() >= new DateTimeImmutable('-1 day')) {
                    $this->logger->debug('Piece "' . $piece . '" already has an up-to-date price from "' . $priceFetcher->getSource() . '".');
                    continue;
                }
                if ($price === null) {
                    $price = new PiecePrice();
                    $price->setSource($priceFetcher->getSource());
                    $price->setUpdated(new DateTimeImmutable());

                    $piece->addPrice($price);
                }

                $this->logger->debug('Checking "' . $priceFetcher->getSource() . '" price for piece "' . $piece . '"...');

                $price->setPrice($priceFetcher->fetchPrice($piece));
            }

            $piece->updateCache();
            $this->entityManager->flush();

            $io->progressAdvance();
        }
        $io->progressFinish();

        return self::SUCCESS;
    }
}
