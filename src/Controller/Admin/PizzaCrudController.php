<?php

namespace App\Controller\Admin;

use App\Entity\Pizza;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PizzaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Pizza::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom'),
            TextEditorField::new('description', "Description")
                ->onlyOnForms(),
            TextField::new('description', 'Description')
                ->renderAsHtml()
                ->onlyOnIndex(),
            MoneyField::new('price', 'Prix')
                ->setCurrency('EUR')
                ->setStoredAsCents(false),
            AssociationField::new('toppings', 'Suppléments')
                ->setFormTypeOptions([
                    'by_reference' => false,
                    'choice_label' => 'name',
                ])
                ->onlyOnForms(),
            TextField::new('toppingList', 'Suppléments')
                ->onlyOnIndex()
                ->renderAsHtml(),
            ImageField::new('imageFilename', 'Image')
                ->setBasePath('/images/pizzas')
                ->onlyOnIndex(),
            TextField::new('imageFile', 'Image')
                ->setFormType(VichImageType::class)
                ->onlyOnForms(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Pizza')
            ->setEntityLabelInPlural('Pizzas')
            ->setPageTitle(Crud::PAGE_INDEX, 'Liste des %entity_label_plural%')
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier une %entity_label_singular%')
            ->setPageTitle(Crud::PAGE_NEW, 'Ajouter une %entity_label_singular%');
    }
}
