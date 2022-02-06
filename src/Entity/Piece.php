<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity]
#[UniqueEntity(fields: ['partNumber', 'color'])]
class Piece
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    private int $id;

    #[ORM\Column(type: Types::STRING)]
    private string $partNumber;

    #[ORM\Column(type: Types::STRING)]
    private string $name;

    #[ORM\ManyToOne(targetEntity: Color::class)]
    private Color $color;

    #[ORM\Column(type: Types::STRING)]
    private string $imageUrl;

    #[ORM\ManyToMany(targetEntity: Piece::class)]
    private Collection $alternativeParts;

    /** @var Collection<PieceCount> */
    #[ORM\OneToMany(mappedBy: 'piece', targetEntity: PieceCount::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $lists;

    /** @var Collection<PieceNumber> */
    #[ORM\OneToMany(mappedBy: 'piece', targetEntity: PieceNumber::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $externalIds;

    public function __construct()
    {
        $this->lists = new ArrayCollection();
        $this->externalIds = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Piece
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Piece
    {
        $this->name = $name;
        return $this;
    }

    public function getColor(): Color
    {
        return $this->color;
    }

    public function setColor(Color $color): Piece
    {
        $this->color = $color;
        return $this;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl): Piece
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    /**
     * @return Collection<Piece>
     */
    public function getAlternativeParts(): Collection
    {
        return $this->alternativeParts;
    }

    /**
     * @param Collection<Piece> $alternativeParts
     */
    public function setAlternativeParts(Collection $alternativeParts): Piece
    {
        $this->alternativeParts = $alternativeParts;
        return $this;
    }

    public function addAlternativePart(Piece $piece): Piece
    {
        $piece->addAlternativePart($this);
        $this->alternativeParts->add($piece);
        return $this;
    }

    public function removeAlternativePart(Piece $piece): Piece
    {
        $this->alternativeParts->removeElement($piece);
        return $this;
    }

    /**
     * @return Collection<PieceCount>
     */
    public function getLists(): Collection
    {
        return $this->lists;
    }

    /**
     * @param Collection<PieceCount> $lists
     */
    public function setLists(Collection $lists): Piece
    {
        $this->lists = $lists;
        return $this;
    }

    public function addList(PieceCount $list): Piece
    {
        $list->setPiece($this);
        $this->lists->add($list);
        return $this;
    }

    public function removeList(PieceCount $list): Piece
    {
        $this->lists->removeElement($list);
        return $this;
    }

    public function getPartNumber(): string
    {
        return $this->partNumber;
    }

    public function setPartNumber(string $partNumber): Piece
    {
        $this->partNumber = $partNumber;
        return $this;
    }

    /**
     * @return Collection<PieceNumber>
     */
    public function getExternalIds(): Collection
    {
        return $this->externalIds;
    }

    /**
     * @param Collection<PieceNumber> $externalIds
     */
    public function setExternalIds(Collection $externalIds): Piece
    {
        $this->externalIds = $externalIds;
        return $this;
    }

    public function addExternalId(PieceNumber $pieceNumber): Piece
    {
        $this->externalIds->add($pieceNumber);
        return $this;
    }

    public function removeExternalId(PieceNumber $pieceNumber): Piece
    {
        $this->externalIds->removeElement($pieceNumber);
        return $this;
    }

    public function getCountNeeded(): int
    {
        return array_sum(array_map(fn (PieceCount $count): int => $count->getCountNeeded(), $this->getLists()->toArray()));
    }

    public function getCountHaving(): int
    {
        return array_sum(array_map(fn (PieceCount $count): int => $count->getCountHaving(), $this->getLists()->toArray()));
    }
}
