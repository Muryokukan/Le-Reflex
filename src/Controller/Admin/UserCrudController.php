<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    public function __construct(
        public UserPasswordHasherInterface $userPasswordHasher,
        private Security $security
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            EmailField::new('email', 'Email'),
            BooleanField::new('reservationNotification', 'Notification de réservation')
                ->setHelp('À cocher pour recevoir les mails de notifications de réservation.')
                ->renderAsSwitch(false),
            BooleanField::new('contactNotification', 'Notification de contact')
                ->setHelp('À cocher pour recevoir les mails de notifications de contact.')
                ->renderAsSwitch(false),
            ChoiceField::new('roles', 'Rôles')
                ->setChoices([
                    'Administrateur' => 'ROLE_ADMIN'
                ])
                ->allowMultipleChoices()
                ->setPermission('ROLE_ADMIN')
                ->onlyOnForms()
                ->setRequired(false),
            ChoiceField::new('roles', 'Rôles')
                ->setChoices([
                    'Administrateur' => 'ROLE_ADMIN'
                ])
                ->allowMultipleChoices()
                ->renderAsBadges([
                    'ROLE_ADMIN' => 'warning'
                ])
                ->onlyOnIndex(),
        ];

        if ($pageName === Crud::PAGE_NEW) {
            $fields[] = TextField::new('password', 'Mot de passe')
                ->setFormType(PasswordType::class)
                ->setRequired(true);
        } elseif ($pageName === Crud::PAGE_EDIT) {
            /** @var User $currentUser */
            $currentUser = $this->security->getUser();
            /** @var User $editedUser */
            $editedUser = $this->getContext()->getEntity()->getInstance();

            if ($this->security->isGranted('ROLE_ADMIN') || $currentUser->getId() === $editedUser->getId()) {
                $fields[] = TextField::new('password', 'Nouveau mot de passe')
                    ->setFormType(PasswordType::class)
                    ->setRequired(false)
                    ->setHelp('Laissez vide pour conserver le mot de passe actuel');
            }
        }

        return $fields;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions->setPermission(Action::NEW , 'ROLE_ADMIN')
            ->setPermission(Action::DELETE, 'ROLE_ADMIN');

        $actions->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
            return $action->displayIf(function ($entity) {
                /** @var User $currentUser */
                $currentUser = $this->security->getUser();
                return $this->security->isGranted('ROLE_ADMIN') || $currentUser->getId() === $entity->getId();
            });
        });

        return $actions;
    }

    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }

    public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }

    private function addPasswordEventListener(FormBuilderInterface $formBuilder): FormBuilderInterface
    {
        return $formBuilder->addEventListener(FormEvents::POST_SUBMIT, $this->hashPassword());
    }

    private function hashPassword()
    {
        return function ($event) {
            $form = $event->getForm();
            if (!$form->isValid())
                return;

            if (!$form->has('password'))
                return;

            $password = $form->get('password')->getData();
            if ($password === null || empty($password))
                return;

            $hash = $this->userPasswordHasher->hashPassword($form->getData(), $password);
            $form->getData()->setPassword($hash);
        };
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Utilisateur')
            ->setEntityLabelInPlural('Utilisateurs')
            ->setPageTitle(Crud::PAGE_INDEX, 'Liste des %entity_label_plural%')
            ->setPageTitle(Crud::PAGE_EDIT, 'Modifier %entity_label_singular%')
            ->setPageTitle(Crud::PAGE_NEW, 'Créer un nouvel %entity_label_singular%');
    }
}
