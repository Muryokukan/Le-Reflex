<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\ArticleCategory;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ArticleCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ArticleCategory::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $articleField = AssociationField::new('articles', 'Articles')
            ->setFormTypeOption('by_reference', false)
            ->setFormTypeOptions([
                'class' => Article::class,
                'multiple' => true,
                'choice_label' => 'name',
                'query_builder' => function (ArticleRepository $repo) use ($pageName) {
                    $qb = $repo->createQueryBuilder('a')
                        ->where('a.category IS NULL');

                    if ($pageName === Crud::PAGE_EDIT) {
                        $qb->orWhere('a.category = :category')
                            ->setParameter('category', $this->getContext()->getEntity()->getInstance());
                    }

                    return $qb;
                },
            ])
            ->onlyOnForms();

        return [
            TextField::new('name', 'Nom'),
            TextField::new('description', 'Description')
                ->setRequired(false),
            BooleanField::new('enabled', 'Afficher la catégorie')
                ->setHelp('À décocher quand la catégorie est hors saison par exemple.'),
            $articleField,
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Catégorie')
            ->setEntityLabelInPlural('Catégories')
            ->setPageTitle(Crud::PAGE_INDEX, 'Liste des %entity_label_plural%')
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier une %entity_label_singular%')
            ->setPageTitle(Crud::PAGE_NEW, 'Ajouter une %entity_label_singular%');
    }
}
