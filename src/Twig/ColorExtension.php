<?php

declare(strict_types=1);

namespace App\Twig;

use ColorContrast\ColorContrast;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ColorExtension extends AbstractExtension
{
    public function __construct(
        private ColorContrast $colorContrast
    ) {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('contrast', [$this, 'getContrast']),
        ];
    }

    public function getContrast(string $hex): string
    {
        $contrast = $this->colorContrast->complimentaryTheme($hex);

        return match($contrast) {
            ColorContrast::DARK => '#000000',
            ColorContrast::LIGHT => '#ffffff',
        };
    }
}
