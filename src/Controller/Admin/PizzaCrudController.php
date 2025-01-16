<?php

namespace App\Controller\Admin;

use App\Entity\Pizza;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class PizzaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Pizza::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            TextField::new('description')->renderAsHtml(false),
            MoneyField::new('price')->setCurrency('EUR')->setStoredAsCents(false),
            ImageField::new('imageFilename')
                ->setBasePath('uploads/pizzas')
                ->setUploadDir('public/uploads/pizzas')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),
            AssociationField::new('toppings')
                ->setFormTypeOption('multiple', true)
                ->setFormTypeOption('by_reference', false),
        ];
    }
}