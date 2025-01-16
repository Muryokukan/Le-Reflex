<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\Topping;
use App\Entity\Pizza;

#[IsGranted("ROLE_USER")]
class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig', []);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Le Reflex - Administration');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToUrl('Page Facebook', 'fa-brands fa-facebook', 'https://www.facebook.com/LeReflexBarRestaurant/')
            ->setLinkTarget('_blank');
        yield MenuItem::linkToRoute('Retour au site', 'fa fa-arrow-left', 'app_home');
        yield MenuItem::section('Restaurant');
        yield MenuItem::linkToCrud('Pizzas', 'fa fa-pizza-slice', Pizza::class);
        yield MenuItem::linkToCrud('Suppléments', 'fa fa-plus-circle', Topping::class);
    }
}
