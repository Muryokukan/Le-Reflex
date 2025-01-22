<?php

namespace App\Controller\Admin;

use App\Entity\RoomReservationSlot;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RoomReservationSlotCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RoomReservationSlot::class;
    }
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom'),
            TextField::new('description', 'Description'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Créneau')
            ->setEntityLabelInPlural('Créneaux')
            ->setPageTitle(Crud::PAGE_INDEX, 'Liste des %entity_label_plural%')
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier un %entity_label_singular%')
            ->setPageTitle(Crud::PAGE_NEW, 'Ajouter un %entity_label_singular%');
    }
}
