<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ExternalColor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['unsigned' => true])]
    private int $id;

    #[ORM\Column(type: Types::STRING)]
    private string $system;

    /** @var array<int|null> */
    #[ORM\Column(type: Types::JSON)]
    private array $ids = [];

    /** @var array<string> */
    #[ORM\Column(type: Types::JSON)]
    private array $names = [];

    #[ORM\ManyToOne(targetEntity: Color::class, inversedBy: 'externalColors')]
    private Color $color;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): ExternalColor
    {
        $this->id = $id;
        return $this;
    }

    public function getSystem(): string
    {
        return $this->system;
    }

    public function setSystem(string $system): ExternalColor
    {
        $this->system = $system;
        return $this;
    }

    /**
     * @return array<int|null>
     */
    public function getIds(): array
    {
        return $this->ids;
    }

    /**
     * @param array<int|null> $ids
     */
    public function setIds(array $ids): ExternalColor
    {
        $this->ids = $ids;
        return $this;
    }

    /**
     * @return array<string>
     */
    public function getNames(): array
    {
        return $this->names;
    }

    /**
     * @param array<string> $names
     */
    public function setNames(array $names): ExternalColor
    {
        $this->names = $names;
        return $this;
    }

    public function getColor(): Color
    {
        return $this->color;
    }

    public function setColor(Color $color): ExternalColor
    {
        $this->color = $color;
        return $this;
    }
}
