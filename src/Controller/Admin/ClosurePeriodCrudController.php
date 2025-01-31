<?php

namespace App\Controller\Admin;

use App\Entity\ClosurePeriod;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ClosurePeriodCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ClosurePeriod::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            DateField::new('startDate', 'Date de début')
            ->setFormat('dd/MM/Y'),
            DateField::new('endDate', 'Date de fin')
            ->setFormat('dd/MM/Y'),
            TextField::new('reason', 'Motif'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Fermeture')
            ->setEntityLabelInPlural('Fermetures')
            ->setPageTitle(Crud::PAGE_INDEX, 'Liste des %entity_label_plural%')
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier une %entity_label_singular%')
            ->setPageTitle(Crud::PAGE_NEW, 'Ajouter une %entity_label_singular%');
    }
}
