<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Client\RebrickableClient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PartListType extends AbstractType
{
    public function __construct(
        private RebrickableClient $rebrickableClient
    ) {
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $choices = [];
        $response = $this->rebrickableClient->getPartLists();
        foreach ($response['results'] as $partList) {
            $choices[$partList['name']] = $partList['id'];
        }

        $resolver->setDefaults([
            'choices' => $choices
        ]);
    }
}
