<?php

namespace App\Controller\Admin;

use App\Entity\DailyMenu;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DailyMenuCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DailyMenu::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextEditorField::new('content', 'Contenu')
                ->onlyOnForms(),
            TextField::new('content', 'Contenu')
                ->onlyOnIndex()
                ->renderAsHtml(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Menu du jour')
            ->setEntityLabelInPlural('Menus du jour')
            ->setPageTitle(Crud::PAGE_INDEX, 'Liste des %entity_label_plural%')
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier un %entity_label_singular%')
            ->setPageTitle(Crud::PAGE_NEW, 'Ajouter un %entity_label_singular%');
    }
}
