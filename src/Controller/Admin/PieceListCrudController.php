<?php

namespace App\Controller\Admin;

use App\Entity\PieceList;
use App\Form\Type\PartListType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class PieceListCrudController extends AbstractCrudController
{
    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return PieceList::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm()
                ->hideOnIndex(),
            TextField::new('name'),
            Field::new('rebrickableListId')
                ->setFormType(PartListType::class)
                ->hideOnIndex(),
            IntegerField::new('partsNeeded')
                ->setVirtual(true)
                ->hideOnForm(),
            MoneyField::new('priceSumNeeded')
                ->setCurrency('EUR')
                ->setVirtual(true)
                ->hideOnForm(),
        ];
    }
}
