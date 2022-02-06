<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\PieceCount;
use App\Entity\PieceList;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PieceCountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('list', EntityType::class, [
            'class' => PieceList::class,
        ]);
        $builder->add('countNeeded', IntegerType::class);
        $builder->add('countHaving', IntegerType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PieceCount::class,
        ]);
    }
}
