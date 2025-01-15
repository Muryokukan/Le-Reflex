<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('category', 'Catégorie')
                ->setFormTypeOptions([
                    'choice_label' => 'name',
                ])
                ->onlyOnForms(),
            TextField::new('category.name', 'Catégorie')
                ->setLabel('Catégorie')
                ->onlyOnIndex()
                ->setSortable(true),
            TextField::new('name', 'Nom'),
            NumberField::new('price', 'Prix'),
            TextField::new('description', 'Description')
                ->setRequired(false),
            TextField::new('ingredients', 'Ingrédients')
                ->setRequired(false),
            TextField::new('allergens', 'Allergènes')
                ->setRequired(false),
            BooleanField::new('available', 'Disponible')
                ->setHelp('À décocher si des ingrédients manquent, sera noté comme "indisponible".'),
            BooleanField::new('enabled', 'Afficher l\'article sur le menu')
                ->setHelp('À décocher quand le produit est hors saison par exemple.'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Article')
            ->setEntityLabelInPlural('Articles')
            ->setPageTitle(Crud::PAGE_INDEX, 'Liste des %entity_label_plural%')
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier un %entity_label_singular%')
            ->setPageTitle(Crud::PAGE_NEW, 'Ajouter un %entity_label_singular%');
    }
}
