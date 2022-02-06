<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class PieceCount
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    private $id;

    #[ORM\ManyToOne(targetEntity: PieceList::class, inversedBy: 'pieces')]
    private PieceList $list;

    #[ORM\ManyToOne(targetEntity: Piece::class, inversedBy: 'lists')]
    private Piece $piece;

    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    private int $countNeeded = 0;

    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    private int $countHaving = 0;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id): PieceCount
    {
        $this->id = $id;
        return $this;
    }

    public function getList(): PieceList
    {
        return $this->list;
    }

    public function setList(PieceList $list): PieceCount
    {
        $this->list = $list;
        return $this;
    }

    public function getPiece(): Piece
    {
        return $this->piece;
    }

    public function setPiece(Piece $piece): PieceCount
    {
        $this->piece = $piece;
        return $this;
    }

    public function getCountNeeded(): int
    {
        return $this->countNeeded;
    }

    public function setCountNeeded(int $countNeeded): PieceCount
    {
        $this->countNeeded = $countNeeded;
        return $this;
    }

    public function getCountHaving(): int
    {
        return $this->countHaving;
    }

    public function setCountHaving(int $countHaving): PieceCount
    {
        $this->countHaving = $countHaving;
        return $this;
    }
}
