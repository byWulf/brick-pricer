<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class PieceNumber
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    private int $id;

    #[ORM\Column(type: Types::STRING)]
    private string $system;

    #[ORM\ManyToOne(targetEntity: Piece::class, inversedBy: 'externalIds')]
    private Piece $piece;

    /** @var array<string> */
    #[ORM\Column(type: Types::JSON)]
    private array $ids = [];

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): PieceNumber
    {
        $this->id = $id;
        return $this;
    }

    public function getSystem(): string
    {
        return $this->system;
    }

    public function setSystem(string $system): PieceNumber
    {
        $this->system = $system;
        return $this;
    }

    /**
     * @return array<string>
     */
    public function getIds(): array
    {
        return $this->ids;
    }

    /**
     * @param array<string> $ids
     */
    public function setIds(array $ids): PieceNumber
    {
        $this->ids = $ids;
        return $this;
    }

    public function getPiece(): Piece
    {
        return $this->piece;
    }

    public function setPiece(Piece $piece): PieceNumber
    {
        $this->piece = $piece;
        return $this;
    }
}
