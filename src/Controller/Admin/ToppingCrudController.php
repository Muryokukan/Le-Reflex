<?php

namespace App\Controller\Admin;

use App\Entity\Topping;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class ToppingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Topping::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom'),
            TextField::new('pizzaList', 'Pizzas')
                ->onlyOnIndex()
                ->renderAsHtml(),
            MoneyField::new('additionalPrice', 'Prix')
                ->setCurrency('EUR')
                ->setStoredAsCents(false),
            AssociationField::new('pizzas', 'Pizzas')
                ->setFormTypeOptions([
                    'by_reference' => false,
                    'choice_label' => 'name',
                ])
                ->onlyOnForms(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Supplément pizza')
            ->setEntityLabelInPlural('Suppléments pizza')
            ->setPageTitle(Crud::PAGE_INDEX, 'Liste des %entity_label_plural%')
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier un %entity_label_singular%')
            ->setPageTitle(Crud::PAGE_NEW, 'Ajouter un %entity_label_singular%');
    }
}