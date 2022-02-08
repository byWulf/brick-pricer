<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Piece;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

class PieceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Piece::class);
    }

    public function findPart(string $partNumber, int $colorId): ?Piece
    {
        $qb = $this->createQueryBuilder('p');
        $qb->join('p.color', 'c');
        $qb->where('p.partNumber = :partNumber AND c.id = :colorId');
        $qb->setParameter('partNumber', $partNumber);
        $qb->setParameter('colorId', $colorId);

        try {
            return $qb->getQuery()->getSingleResult();
        } catch (NoResultException|NonUniqueResultException) {
            return null;
        }
    }
}
