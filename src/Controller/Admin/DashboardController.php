<?php

namespace App\Controller\Admin;

// use App\Entity\Article;
// use App\Entity\ArticleCategory;
use App\Entity\ContactMessage;
use App\Entity\EventImage;
use App\Entity\MenuImage;
use App\Repository\ContactMessageRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\Topping;
use App\Entity\Pizza;
use App\Entity\Room;
use App\Entity\RoomOption;
use App\Entity\RoomReservation;
use App\Entity\RoomReservationSlot;
use App\Entity\User;
use App\Repository\RoomReservationRepository;

#[IsGranted("ROLE_USER")]
class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private ContactMessageRepository $contactMessageRepository,
        private RoomReservationRepository $roomReservationRepository
    ) {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $unreadCount = $this->contactMessageRepository->countUnread();
        $countReservationsByStatus = $this->roomReservationRepository->countReservationsByStatus();

        return $this->render('admin/dashboard.html.twig', [
            'unread_messages_count' => $unreadCount,
            'countReservationsByStatus' => $countReservationsByStatus,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Le Reflex - Administration');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Retour au site', 'fa fa-arrow-left', 'app_home');
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Général');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);

        yield MenuItem::section('Contacts');
        yield MenuItem::linkToCrud('Messages de contact', 'fa fa-envelope', ContactMessage::class);

        yield MenuItem::section('Calendrier');
        yield MenuItem::linkToCrud('Réservations de salles', 'fa-regular fa-calendar', RoomReservation::class);

        yield MenuItem::section('Gestion des salles');
        yield MenuItem::linkToCrud('Salles', 'fa-solid fa-location-dot', Room::class);
        yield MenuItem::linkToCrud('Options', 'fa fa-plus-circle', RoomOption::class);
        yield MenuItem::linkToCrud('Créneaux', 'fa-regular fa-clock', RoomReservationSlot::class);

        yield MenuItem::section('Restaurant');
        yield MenuItem::linkToCrud('Pizzas', 'fa fa-pizza-slice', Pizza::class);
        yield MenuItem::linkToCrud('Suppléments', 'fa fa-plus-circle', Topping::class);

        yield MenuItem::section('Gestion des évènements');
        yield MenuItem::linkToCrud('Images des évènements', 'fas fa-images', EventImage::class);

        yield MenuItem::section('Gestion du menu');
        yield MenuItem::linkToCrud('Images du menu', 'fas fa-images', MenuImage::class);
        // yield MenuItem::linkToCrud('Articles', 'fa-solid fa-burger', Article::class);
        // yield MenuItem::linkToCrud('Catégories', 'fa-solid fa-tag', ArticleCategory::class);
    }
}
