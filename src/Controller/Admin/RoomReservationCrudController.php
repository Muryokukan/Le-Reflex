<?php

namespace App\Controller\Admin;

use App\Entity\RoomReservation;
use App\Enum\RoomReservationStatus;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RoomReservationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RoomReservation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $statusChoices = [];
        foreach (RoomReservationStatus::cases() as $status) {
            $statusChoices[$status->label()] = $status;
        }

        return [
            ChoiceField::new('status', 'Statut')
                ->setChoices($statusChoices)
                ->setTemplatePath('admin/field/room_reservation/_index_status_badge.html.twig'),
            DateTimeField::new('createdAt', 'Date de création')
                ->hideOnForm()
                ->setFormat('dd/MM/Y HH:mm:ss'),
            DateField::new('reservationDate', 'Date de réservation')
                ->setFormat('dd/MM/Y'),
            AssociationField::new('slot', 'Créneau')
                ->setFormTypeOptions([
                    'choice_label' => 'name',
                ])
                ->onlyOnForms(),
            TextField::new('slot.name', 'Créneau')
                ->onlyOnIndex()
                ->setSortable(true),
            TextField::new('roomsList', 'Salles')
                ->onlyOnIndex()
                ->renderAsHtml(),
            AssociationField::new('rooms', 'Salles')
                ->setFormTypeOptions([
                    'by_reference' => false,
                    'choice_label' => 'name',
                ])
                ->onlyOnForms(),
            TextField::new('optionsList', 'Options')
                ->onlyOnIndex()
                ->renderAsHtml(),
            AssociationField::new('roomOptions', 'Options')
                ->setFormTypeOptions([
                    'by_reference' => false,
                    'choice_label' => 'name',
                ])
                ->onlyOnForms(),
            NumberField::new('totalAmount', 'Montant total')
                ->setNumDecimals(2)
                ->hideOnForm(),
            TextField::new('lastname', 'Nom'),
            TextField::new('firstname', 'Prénom'),
            TextField::new('phoneNumber', 'Téléphone')
                ->formatValue(function ($value, $entity) {
                    return '<a href="tel:' . $value . '">' . $value . '</a>';
                })
                ->renderAsHtml(),
            TextField::new('email')
                ->formatValue(function ($value, $entity) {
                    return $value ? '<a href="mailto:' . $value . '">' . $value . '</a>' : '';
                })
                ->renderAsHtml()
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return parent::configureActions($actions)
            ->remove(Crud::PAGE_INDEX, Action::DELETE);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Réservation de salles')
            ->setEntityLabelInPlural('Réservations de salles')
            ->setPageTitle(Crud::PAGE_INDEX, 'Liste des %entity_label_plural%')
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier une %entity_label_singular%')
            ->setPageTitle(Crud::PAGE_NEW, 'Créer une %entity_label_singular%')
            ->setDefaultSort(['createdAt' => 'DESC']);
    }
}