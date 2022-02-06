<?php

declare(strict_types=1);

namespace App\Command;

use App\Client\RebrickableClient;
use App\Entity\Color;
use App\Entity\ExternalColor;
use App\Repository\ColorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:colors:import')]
class ImportColorsCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private RebrickableClient $rebrickableClient,
        private ColorRepository $colorRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $colors = $this->rebrickableClient->getColors();

        foreach ($colors['results'] as $result) {
            $color = $this->colorRepository->find($result['id']);
            if ($color === null) {
                $color = new Color();
                $color->setId($result['id']);
            }

            $color
                ->setName($result['name'])
                ->setRgb('#' . $result['rgb'])
                ->setIsTransparent($result['is_trans'])
            ;

            $color->setExternalColors(new ArrayCollection());
            foreach ($result['external_ids'] as $system => $externalResult) {
                $externalColor = new ExternalColor();
                $externalColor
                    ->setSystem($system)
                    ->setIds(array_filter($externalResult['ext_ids'], static fn ($value): bool => $value !== null))
                    ->setNames(array_filter(array_merge(...$externalResult['ext_descrs'])))
                ;
                $color->addExternalColor($externalColor);
            }

            $this->entityManager->persist($color);
        }

        $this->entityManager->flush();

        return self::SUCCESS;
    }
}
