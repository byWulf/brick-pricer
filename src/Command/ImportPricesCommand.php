<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Piece;
use App\PriceFetcher\PriceFetcherInterface;
use App\Repository\PieceRepository;
use App\Service\PriceService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:prices:import')]
class ImportPricesCommand extends Command
{
    /**
     * @param iterable<PriceFetcherInterface> $priceFetchers
     */
    public function __construct(
        private PriceService $priceService,
        private PieceRepository $pieceRepository
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
            $this->priceService->updatePrices($piece);

            $io->progressAdvance();
        }
        $io->progressFinish();

        return self::SUCCESS;
    }
}
