<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Color;
use App\Entity\PieceList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PieceListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PieceList::class);
    }
}
