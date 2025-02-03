<?php

namespace App\Controller\Admin;

use App\Entity\DailyMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class DailyMenuCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DailyMenu::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextEditorField::new('content', 'Menu du jour'),
        ];
    }
}
