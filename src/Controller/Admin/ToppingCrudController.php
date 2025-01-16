<?php

namespace App\Controller\Admin;

use App\Entity\Topping;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
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
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            MoneyField::new('additionalPrice')->setCurrency('EUR')->setStoredAsCents(false),
        ];
    }
}