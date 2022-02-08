<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class PiecePrice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    private $id;

    #[ORM\ManyToOne(targetEntity: Piece::class, inversedBy: 'prices')]
    private Piece $piece;

    #[ORM\Column(type: Types::STRING)]
    private string $source;

    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true], nullable: true)]
    private ?int $price;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $updated;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id): PiecePrice
    {
        $this->id = $id;
        return $this;
    }

    public function getPiece(): Piece
    {
        return $this->piece;
    }

    public function setPiece(Piece $piece): PiecePrice
    {
        $this->piece = $piece;
        return $this;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function setSource(string $source): PiecePrice
    {
        $this->source = $source;
        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): PiecePrice
    {
        $this->price = $price;
        return $this;
    }

    public function getUpdated(): DateTimeImmutable
    {
        return $this->updated;
    }

    public function setUpdated(DateTimeImmutable $updated): PiecePrice
    {
        $this->updated = $updated;
        return $this;
    }
}
