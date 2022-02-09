<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity]
#[UniqueEntity(fields: ['partNumber', 'color'])]
class Piece implements Stringable
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

    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    private int $cachedPartsNeeded;

    /** @var Collection<PiecePrice> */
    #[ORM\OneToMany(mappedBy: 'piece', targetEntity: PiecePrice::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $prices;

    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true], nullable: true)]
    private ?int $cachedBestSinglePrice;

    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true], nullable: true)]
    private ?int $cachedBestPriceSumNeeded;

    public function __construct()
    {
        $this->lists = new ArrayCollection();
        $this->externalIds = new ArrayCollection();
        $this->prices = new ArrayCollection();
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

    public function getCountByPieceList(PieceList $list): ?PieceCount
    {
        foreach ($this->lists as $count) {
            if ($count->getList()->getId() === $list->getId()) {
                return $count;
            }
        }

        return null;
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

    public function getExternalIdsBySystem(string $system): ?PieceNumber
    {
        foreach ($this->externalIds as $externalId) {
            if ($externalId->getSystem() === $system) {
                return $externalId;
            }
        }

        return null;
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
        $pieceNumber->setPiece($this);
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

    public function getCachedPartsNeeded(): int
    {
        return $this->cachedPartsNeeded;
    }

    public function setCachedPartsNeeded(int $cachedPartsNeeded): Piece
    {
        $this->cachedPartsNeeded = $cachedPartsNeeded;
        return $this;
    }

    public function getPrices(): Collection
    {
        return $this->prices;
    }

    public function setPrices(Collection $prices): Piece
    {
        $this->prices = $prices;
        return $this;
    }

    public function addPrice(PiecePrice $price): Piece
    {
        $price->setPiece($this);
        $this->prices->add($price);

        return $this;
    }

    public function removePrice(PiecePrice $price): Piece
    {
        $this->prices->removeElement($price);

        return $this;
    }

    public function getPriceBySource(string $source): ?PiecePrice
    {
        foreach ($this->prices as $price) {
            if ($price->getSource() === $source) {
                return $price;
            }
        }

        return null;
    }

    public function getCachedBestSinglePrice(): ?int
    {
        return $this->cachedBestSinglePrice;
    }

    public function setCachedBestSinglePrice(?int $cachedBestSinglePrice): Piece
    {
        $this->cachedBestSinglePrice = $cachedBestSinglePrice;
        return $this;
    }

    public function getCachedBestPriceSumNeeded(): ?int
    {
        return $this->cachedBestPriceSumNeeded;
    }

    public function setCachedBestPriceSumNeeded(?int $cachedBestPriceSumNeeded): Piece
    {
        $this->cachedBestPriceSumNeeded = $cachedBestPriceSumNeeded;
        return $this;
    }

    public function updateCache(): Piece
    {
        $this->cachedPartsNeeded = array_sum(array_map(fn (PieceCount $pieceCount): int => $pieceCount->getCountNeeded(), $this->lists->toArray())) - array_sum(array_map(fn (PieceCount $pieceCount): int => $pieceCount->getCountHaving(), $this->lists->toArray()));

        $suitablePrices = $this->prices->filter(fn (PiecePrice $piecePrice): bool => $piecePrice->getPrice() !== null);
        $this->cachedBestSinglePrice = $suitablePrices->count() > 0 ? min(array_map(fn (PiecePrice $piecePrice): int => $piecePrice->getPrice(), $suitablePrices->toArray())) : null;

        $this->cachedBestPriceSumNeeded = $this->cachedBestSinglePrice !== null ? $this->cachedBestSinglePrice * $this->cachedPartsNeeded : null;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name . ' (' . $this->partNumber . ') in ' . $this->color->getName() . ' (' . $this->color->getId() . ')';
    }
}
