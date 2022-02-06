<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;

#[ORM\Entity]
class Color implements Stringable
{
    /** ID of Rebrickable */
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(type: Types::STRING)]
    private string $name;

    #[ORM\Column(type: Types::STRING)]
    private string $rgb;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isTransparent;

    /** @var Collection<ExternalColor> */
    #[ORM\OneToMany(mappedBy: 'color', targetEntity: ExternalColor::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $externalColors;

    public function __construct()
    {
        $this->externalColors = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Color
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Color
    {
        $this->name = $name;
        return $this;
    }

    public function getRgb(): string
    {
        return $this->rgb;
    }

    public function setRgb(string $rgb): Color
    {
        $this->rgb = $rgb;
        return $this;
    }

    public function isTransparent(): bool
    {
        return $this->isTransparent;
    }

    public function setIsTransparent(bool $isTransparent): Color
    {
        $this->isTransparent = $isTransparent;
        return $this;
    }

    /**
     * @return Collection<ExternalColor>
     */
    public function getExternalColors(): Collection
    {
        return $this->externalColors;
    }

    /**
     * @param Collection<ExternalColor> $externalColors
     */
    public function setExternalColors(Collection $externalColors): Color
    {
        $this->externalColors = $externalColors;
        return $this;
    }

    public function addExternalColor(ExternalColor $externalColor): Color
    {
        $externalColor->setColor($this);
        $this->externalColors->add($externalColor);
        return $this;
    }

    public function removeExternalColor(ExternalColor $externalColor): Color
    {
        $this->externalColors->removeElement($externalColor);
        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
