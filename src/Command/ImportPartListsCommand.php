<?php

declare(strict_types=1);

namespace App\Command;

use App\Client\RebrickableClient;
use App\Entity\Piece;
use App\Entity\PieceCount;
use App\Repository\ColorRepository;
use App\Repository\PieceListRepository;
use App\Repository\PieceRepository;
use App\Service\PieceService;
use App\Service\PriceService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:part-lists:import')]
class ImportPartListsCommand extends Command
{
    public function __construct(
        private RebrickableClient $rebrickableClient,
        private EntityManagerInterface $entityManager,
        private PieceListRepository $pieceListRepository,
        private PieceRepository $pieceRepository,
        private PieceService $pieceService,
        private ColorRepository $colorRepository,
        private PriceService $priceService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->pieceListRepository->findBy(['needImport' => true]) as $pieceList) {
            $partListResponse = $this->rebrickableClient->getPartsOfPartList($pieceList->getRebrickableListId());

            foreach ($partListResponse['results'] as $partListEntry) {
                $piece = $this->pieceRepository->findPart($partListEntry['part']['part_num'], $partListEntry['color']['id']);
                if ($piece === null) {
                    $piece = new Piece();
                    $piece->setPartNumber($partListEntry['part']['part_num']);
                    $piece->setColor($this->colorRepository->find($partListEntry['color']['id']));

                    $this->pieceService->enrichPieceInformation($piece, $partListEntry['part']);

                    $this->entityManager->persist($piece);
                }

                $count = $piece->getCountByPieceList($pieceList);
                if ($count === null) {
                    $count = new PieceCount();
                    $count->setList($pieceList);
                    $count->setPiece($piece);
                    $piece->addList($count);
                }

                $count->setCountNeeded($partListEntry['quantity']);
                $piece->updateCache();

                $this->priceService->updatePrices($piece);
            }

            $this->entityManager->flush();
        }

        return self::SUCCESS;
    }
}
