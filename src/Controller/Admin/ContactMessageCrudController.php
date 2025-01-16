<?php

namespace App\Controller\Admin;

use App\Entity\ContactMessage;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use Symfony\Component\HttpFoundation\Response;

class ContactMessageCrudController extends AbstractCrudController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getEntityFqcn(): string
    {
        return ContactMessage::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            BooleanField::new('isRead', 'Lu')
                ->renderAsSwitch(false)
                ->onlyOnIndex()
                ->setTemplatePath('admin\field\contact_message\_is_read_index_cell.html.twig'),
            DateTimeField::new('submitted_at', 'Date d\'envoi')
                ->setFormat('dd/MM/Y HH:mm'),
            TextField::new('fullname', 'Nom'),
            TextField::new('subject', 'Sujet'),
            TextareaField::new('message', 'Message')
                ->hideOnIndex(),
            TextField::new('email', 'Email'),
            TextField::new('phone', 'Téléphone'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $markAsUnread = Action::new('markAsUnread', 'Marquer comme non lu')
            ->linkToCrudAction('markAsUnread')
            ->setIcon('fa-regular fa-eye-slash');

        $detailAction = Action::new(Action::DETAIL)
            ->linkToCrudAction(Crud::PAGE_DETAIL)
            ->setIcon('fa-regular fa-eye')
            ->setLabel('Voir le message');

        return parent::configureActions($actions)
            ->add(Crud::PAGE_INDEX, $detailAction)
            ->add(Crud::PAGE_DETAIL, $markAsUnread)
            ->disable(Action::NEW, Action::EDIT)
            ->update(Crud::PAGE_DETAIL, Action::INDEX, function (Action $action) {
                return $action->setLabel('Retour à la liste');
            });
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Messages de contact')
            ->setPageTitle('detail', 'Détail du message')
            ->setDefaultSort(['is_read' => 'ASC', 'submitted_at' => 'DESC'])
            ->showEntityActionsInlined();
    }

    public function detail(AdminContext $context): KeyValueStore|Response
    {
        $contactMessage = $context->getEntity()->getInstance();

        if (!$contactMessage->isRead()) {
            $contactMessage->setIsRead(true);
            $this->entityManager->flush();

            $this->addFlash('success', 'Le message a été marqué comme "lu"');
        }

        return parent::detail($context);
    }

    public function markAsUnread(AdminContext $context): Response
    {
        $contactMessage = $context->getEntity()->getInstance();
        $contactMessage->setIsRead(false);
        $this->entityManager->flush();

        $this->addFlash('success', 'Le message a été marqué comme "non lu"');

        return $this->redirect($this->generateUrl('admin', [
            'crudAction' => 'index',
            'crudControllerFqcn' => $context->getCrud()->getControllerFqcn(),
        ]));
    }
}