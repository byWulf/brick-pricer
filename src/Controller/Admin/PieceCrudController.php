<?php

namespace App\Controller\Admin;

use App\Entity\Piece;
use App\Entity\PieceCount;
use App\Form\Type\PieceCountType;
use App\Service\PieceService;
use App\Service\PriceService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PieceCrudController extends AbstractCrudController
{
    public function __construct(
        private PieceService $pieceService,
        private PriceService $priceService
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
        $this->pieceService->enrichPieceInformation($entityInstance);
        $this->priceService->updatePrices($entityInstance);

        parent::persistEntity($entityManager, $entityInstance);
    }

    /**
     * @param Piece $entityInstance
     */
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->pieceService->enrichPieceInformation($entityInstance);
        $this->priceService->updatePrices($entityInstance);

        parent::updateEntity($entityManager, $entityInstance);
    }
}
