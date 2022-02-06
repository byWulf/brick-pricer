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
    #[ORM\OneToMany(mappedBy: 'lists', targetEntity: Piece::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $pieces;

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

    public function __toString(): string
    {
        return $this->name;
    }
}
