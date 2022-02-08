<?php

namespace App\Controller\Admin;

use App\Client\RebrickableClient;
use App\Entity\Piece;
use App\Entity\PieceCount;
use App\Entity\PieceNumber;
use App\Form\Type\PieceCountType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PieceCrudController extends AbstractCrudController
{
    public function __construct(
        private RebrickableClient $rebrickableClient
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Piece::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm()
                ->hideOnIndex(),
            TextField::new('imageUrl')
                ->hideOnForm()
                ->setTemplatePath('easy_admin/piece/image.html.twig'),
            TextField::new('partNumber'),
            TextField::new('name')
                ->hideOnForm(),
            AssociationField::new('color')
                ->setTemplatePath('easy_admin/piece/color.html.twig')
                ->setRequired(true),
            CollectionField::new('lists')
                ->setEntryType(PieceCountType::class)
                ->setFormTypeOption('prototype_data', new PieceCount())
                ->setFormTypeOption('by_reference', false)
                ->hideOnIndex(),
            IntegerField::new('cachedPartsNeeded')
                ->setTemplatePath('easy_admin/piece/count.html.twig')
                ->hideOnForm(),
            MoneyField::new('cachedBestSinglePrice')
                ->setCurrency('EUR')
                ->hideOnForm(),
            MoneyField::new('cachedBestPriceSumNeeded')
                ->setCurrency('EUR')
                ->hideOnForm(),
        ];
    }

    /**
     * @param Piece $entityInstance
     */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $partResponse = $this->rebrickableClient->getPart($entityInstance->getPartNumber());
        $colorResponse = $this->rebrickableClient->getPartColor($entityInstance->getPartNumber(), $entityInstance->getColor()->getId());

        $entityInstance
            ->setName($partResponse['name'])
            ->setImageUrl($colorResponse['part_img_url'])
        ;

        foreach ($partResponse['external_ids'] as $system => $externalIdResponse) {
            $pieceNumber = new PieceNumber();
            $pieceNumber
                ->setSystem($system)
                ->setIds($externalIdResponse)
            ;
            $entityInstance->addExternalId($pieceNumber);
        }

        $entityInstance->updateCache();

        parent::persistEntity($entityManager, $entityInstance);
    }

    /**
     * @param Piece $entityInstance
     */
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $entityInstance->updateCache();

        parent::updateEntity($entityManager, $entityInstance);
    }
}
