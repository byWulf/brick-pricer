<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Game\OrdnungshueterSet;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

#[ORM\Entity]
class PieceList implements Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    private int $id;

    #[ORM\Column(type: Types::STRING)]
    private string $name;

    /** @var Collection<PieceCount> */
    #[ORM\OneToMany(mappedBy: 'list', targetEntity: PieceCount::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $pieces;

    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    private int $rebrickableListId;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $needImport = true;

    public function __construct()
    {
        $this->pieces = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): PieceList
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): PieceList
    {
        $this->name = $name;
        return $this;
    }

    public function getPieces(): Collection
    {
        return $this->pieces;
    }

    public function setPieces(Collection $pieces): PieceList
    {
        $this->pieces = $pieces;
        return $this;
    }

    public function addPiece(PieceCount $piece): PieceList
    {
        $piece->setList($this);
        $this->pieces->add($piece);
        return $this;
    }

    public function removePiece(PieceCount $piece): PieceList
    {
        $this->pieces->removeElement($piece);
        return $this;
    }

    public function getRebrickableListId(): int
    {
        return $this->rebrickableListId;
    }

    public function setRebrickableListId(int $rebrickableListId): PieceList
    {
        $this->rebrickableListId = $rebrickableListId;
        return $this;
    }

    public function isNeedImport(): bool
    {
        return $this->needImport;
    }

    public function setNeedImport(bool $needImport): PieceList
    {
        $this->needImport = $needImport;
        return $this;
    }

    public function getPartsNeeded(): int
    {
        return array_sum(array_map(fn (PieceCount $pieceCount): int => $pieceCount->getCountNeeded() - $pieceCount->getCountHaving(), $this->getPieces()->toArray()));
    }

    public function getPriceSumNeeded(): int
    {
        return array_sum(array_map(fn (PieceCount $pieceCount): int => $pieceCount->getPiece()->getCachedBestPriceSumNeeded() ?? 0, $this->getPieces()->toArray()));
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
