<?php

namespace App\Controller\Admin;

use App\Entity\EventImage;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class EventImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return EventImage::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('fileName', 'Aperçu')
                ->setBasePath('/images/event')
                ->onlyOnIndex(),
            TextField::new('file', 'Image')
                ->setFormType(VichImageType::class)
                ->onlyOnForms(),
            TextField::new('description', 'Description')
                ->setRequired(false),
            NumberField::new('displayOrder', 'Ordre d\'affichage')
                ->setHelp('Les images sans ordre d\'affichage apparaitront en dernier'),
            BooleanField::new('enabled', 'Afficher l\'image dans les évènements'),
            TextField::new('fileName', 'Nom du fichier')
                ->hideOnForm(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Image d\'évènement')
            ->setEntityLabelInPlural('Images d\'évènement')
            ->setPageTitle(Crud::PAGE_INDEX, 'Liste des %entity_label_plural%')
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier une %entity_label_singular%')
            ->setPageTitle(Crud::PAGE_NEW, 'Ajouter une %entity_label_singular%');
    }
}
