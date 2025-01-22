<?php

namespace App\Controller\Admin;

use App\Entity\RoomOption;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RoomOptionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RoomOption::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom'),
            TextareaField::new('description', 'Description'),
            NumberField::new('price', 'Prix'),
            AssociationField::new('rooms', 'Salles')
                ->setFormTypeOptions([
                    'by_reference' => false,
                    'choice_label' => 'name',
                ])
                ->onlyOnForms(),
            TextField::new('roomsList', 'Salles')
                ->onlyOnIndex()
                ->renderAsHtml(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Option de salle')
            ->setEntityLabelInPlural('Options de salle')
            ->setPageTitle(Crud::PAGE_INDEX, 'Liste des %entity_label_plural%')
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier une %entity_label_singular%')
            ->setPageTitle(Crud::PAGE_NEW, 'Ajouter une %entity_label_singular%');
    }
}
